<?php

namespace App\Service;

use App\Models\Comment;
class CommentService
{
    public static ?Comment $comment = null;

    public static function updateOrCreate($data, ?Comment $comment): ?Comment
    {
        self::$comment = $comment;
        if (empty(self::$comment)){
            self::$comment = new Comment();
        }
        self::$comment->fill($data);
        self::$comment->save();
        return self::$comment->fresh();
    }

    public function findComment(string $id)
    {
        return Comment::find($id) ?? false;
    }

    public function deleteComment(string $id)
    {
        return Comment::find($id)->delete() ?? false;
    }

}
