<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentController extends Controller
{
    /**
     * Retornar todos os comentários principais de um tópico com suas respostas.
     */
    public function index(Topic $topic)
    {
        try {
            $comments = $topic->comments()
                ->whereNull('parent_id')
                ->with('user', 'replies.user')
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json($comments, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar os comentários.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Criar um novo comentário para um tópico.
     */
    public function storeReply(Request $request, $topicId)
    {
        // Verificar autenticação
        if (!auth()->check()) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:800',
        ]);

        try {
            $topic = Topic::findOrFail($topicId);

            $comment = $topic->comments()->create([
                'user_id' => auth()->id(),
                'content' => $validated['content'],
                'topic_id' => $topic->id, // Garantir a associação ao tópico
            ]);

            $topic->touch();

            return response()->json([
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at->toDateTimeString(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tópico não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar o comentário.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Criar uma resposta para outro comentário.
     */
    public function storeNestedReply(Request $request, Comment $reply)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:800',
        ]);

        try {
            // Criar resposta encadeada
            $nestedReply = $reply->replies()->create([
                'content' => $validated['content'],
                'user_id' => auth()->id(),
                'topic_id' => $reply->topic->id, // Garantir que a relação "topic" esteja associada
            ]);

            $reply->topic->touch();

            return response()->json([
                'id' => $nestedReply->id,
                'content' => $nestedReply->content,
                'created_at' => $nestedReply->created_at->toDateTimeString(),
                'user' => [
                    'id' => $nestedReply->user->id,
                    'name' => $nestedReply->user->name,
                ],
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Comentário original não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar a resposta.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Retornar todos os comentários de um tópico com limite opcional.
     */
    public function getComments(Request $request, $topicId)
    {
        try {
            $limit = $request->get('limit', 10); // Limite padrão: 10
            $topic = Topic::findOrFail($topicId);
            $isAuthenticated = auth()->check();

            $comments = $topic->comments()
                ->whereNull('parent_id')
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'asc')
                ->take($limit)
                ->get()
                ->map(function ($comment) use ($isAuthenticated) {
                    $comment->reply_button = $isAuthenticated
                        ? "<button class='btn btn-link btn-sm' onclick='toggleReplyBox({$comment->id})'>Responder</button>"
                        : '';

                    return $comment;
                });

            return response()->json($comments, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Tópico não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar comentários.', 'message' => $e->getMessage()], 500);
        }
    }
}
