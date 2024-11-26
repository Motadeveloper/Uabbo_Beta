<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id'];

    /**
     * Relacionamento com o usuário que criou o tópico.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com todos os comentários do tópico.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'topic_id', 'id')
                    ->with('user', 'replies.user') // Inclui o autor e respostas
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Retorna apenas os comentários principais (sem pai).
     */
    public function mainComments()
    {
        return $this->comments()->whereNull('parent_id');
    }

    /**
     * Contador total de comentários, incluindo respostas.
     */
    public function totalCommentsCount()
    {
        return $this->comments->reduce(function ($count, $comment) {
            return $count + 1 + $comment->replies->count();
        }, 0);
    }

    /**
     * Atualiza a data de atualização do tópico sempre que um comentário é adicionado.
     */
    protected $touches = ['comments'];
}
