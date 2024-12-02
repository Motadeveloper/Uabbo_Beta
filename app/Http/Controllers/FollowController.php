<?php

// app/Http/Controllers/FollowController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // Função para seguir um usuário
    public function follow($userId)
    {
        $user = User::findOrFail($userId);

        // Verifica se o usuário está tentando seguir a si mesmo
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Você não pode seguir a si mesmo.'], 400);
        }

        // Verifica se o usuário já segue a pessoa
        if (Follow::where('follower_id', Auth::id())->where('followed_id', $user->id)->exists()) {
            return response()->json(['error' => 'Você já segue este usuário.'], 400);
        }

        // Cria o registro de "seguir"
        Follow::create([
            'follower_id' => Auth::id(),
            'followed_id' => $user->id,
        ]);

        // Atualiza os contadores de seguidores e seguidos
        $followersCount = $user->followers()->count();
        $followingCount = Auth::user()->following()->count();

        return response()->json([
            'success' => 'Você seguiu o usuário com sucesso!',
            'followersCount' => $followersCount,
            'followingCount' => $followingCount
        ]);
    }

    // Função para deixar de seguir um usuário
    public function unfollow($userId)
    {
        $user = User::findOrFail($userId);

        // Verifica se o usuário está tentando deixar de seguir a si mesmo
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Você não pode deixar de seguir a si mesmo.'], 400);
        }

        // Verifica se o usuário está seguindo a pessoa
        $follow = Follow::where('follower_id', Auth::id())->where('followed_id', $user->id)->first();
        
        if (!$follow) {
            return response()->json(['error' => 'Você não segue este usuário.'], 400);
        }

        // Remove o relacionamento de seguir
        $follow->delete();

        // Atualiza os contadores de seguidores e seguidos
        $followersCount = $user->followers()->count();
        $followingCount = Auth::user()->following()->count();

        return response()->json([
            'success' => 'Você deixou de seguir o usuário.',
            'followersCount' => $followersCount,
            'followingCount' => $followingCount
        ]);
    }

    // Função para obter a quantidade de seguidores de um usuário
    public function followersCount($userId)
    {
        $user = User::findOrFail($userId);
        $followersCount = $user->followers()->count(); // A relação "followers" deve ser definida no modelo User
        return response()->json(['followersCount' => $followersCount]);
    }

    // Função para obter a quantidade de seguidos de um usuário
    public function followingCount($userId)
    {
        $user = User::findOrFail($userId);
        $followingCount = $user->following()->count(); // A relação "following" deve ser definida no modelo User
        return response()->json(['followingCount' => $followingCount]);
    }

    // Função para obter a lista de seguidores de um usuário
    public function followersList($userId)
    {
        $user = User::findOrFail($userId);
        // Obtém todos os seguidores do usuário
        $followers = $user->followers()->with('follower')->get();
        return response()->json($followers);
    }

    // Função para obter a lista de seguidos de um usuário
    public function followingList($userId)
    {
        $user = User::findOrFail($userId);
        // Obtém todos os seguidos do usuário
        $following = $user->following()->with('followed')->get();
        return response()->json($following);
    }
    public function toggleFollow($userId, Request $request)
{
    $user = User::findOrFail($userId);
    $action = $request->input('action');

    // Verifica se o usuário está tentando seguir a si mesmo
    if ($user->id === Auth::id()) {
        return response()->json(['error' => 'Você não pode seguir a si mesmo.'], 400);
    }

    if ($action === 'follow') {
        // Cria o relacionamento de seguir
        Follow::create([
            'follower_id' => Auth::id(),
            'followed_id' => $user->id,
        ]);
    } elseif ($action === 'unfollow') {
        // Remove o relacionamento de seguir
        Follow::where('follower_id', Auth::id())
              ->where('followed_id', $user->id)
              ->delete();
    }

    // Atualiza os contadores de seguidores e seguidos
    $followersCount = $user->followers()->count();
    $followingCount = Auth::user()->following()->count();

    return response()->json([
        'success' => true,
        'followersCount' => $followersCount,
        'followingCount' => $followingCount,
    ]);
}

public function isFollowing($userId)
{
    $user = User::findOrFail($userId);
    $isFollowing = $user->followers()->where('follower_id', Auth::id())->exists();

    return response()->json(['isFollowing' => $isFollowing]);
}

}
