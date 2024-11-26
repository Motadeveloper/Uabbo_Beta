<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos
    protected $fillable = ['content', 'user_id', 'topic_id', 'parent_id'];

    /**
     * Relacionamento com o autor do comentário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o tópico ao qual o comentário pertence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    /**
     * Relacionamento com o comentário pai (se houver).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    /**
     * Relacionamento com respostas do comentário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')
                    ->with('user', 'replies') // Carregar respostas recursivamente
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Escopo para buscar apenas comentários principais (sem pai).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Escopo para buscar respostas de um comentário específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $parentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRepliesOf($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Obter estrutura em árvore para respostas encadeadas.
     *
     * @return array
     */
    public function getRepliesTree()
    {
        return $this->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'content' => $reply->content,
                'user' => $reply->user,
                'created_at' => $reply->created_at->toDateTimeString(),
                'replies' => $reply->getRepliesTree(), // Chamado recursivamente
            ];
        });
    }

    /**
     * Verifica se o comentário é uma resposta encadeada.
     *
     * @return bool
     */
    public function isReply()
    {
        return $this->parent_id !== null;
    }

    /**
     * Carregar respostas em profundidade limitada.
     *
     * @param int $depth
     * @return \Illuminate\Support\Collection
     */
    public function getLimitedReplies($depth = 1)
    {
        if ($depth <= 0) {
            return [];
        }

        return $this->replies->map(function ($reply) use ($depth) {
            return [
                'id' => $reply->id,
                'content' => $reply->content,
                'user' => $reply->user,
                'created_at' => $reply->created_at->toDateTimeString(),
                'replies' => $reply->getLimitedReplies($depth - 1), // Limita a profundidade
            ];
        });
    }

    /**
     * Obter o caminho completo do comentário na árvore.
     *
     * @return array
     */
    public function getPath()
    {
        $path = [];
        $current = $this;
        while ($current) {
            $path[] = $current->id;
            $current = $current->parent;
        }
        return array_reverse($path);
    }
}
