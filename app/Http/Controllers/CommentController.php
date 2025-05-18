<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Service\CommentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse;

    public static ?Comment $comment = null;
    public static CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        self::$commentService = $commentService;
    }

    public function store(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $data = $request->except('_token', 'id');

        if (!blank($id)) {
            self::$comment = Comment::find($id);
        }
        self::$comment = CommentService::updateOrCreate($data, self::$comment);
        $message = $id ? "updated" : "created";
        return self::success(self::$comment, "Comment $message successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = self::$commentService->findTicket($id);

        if (!empty($comment)) {
            return $this->success($comment);
        }
        return $this->error();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(self::$commentService->deleteTicket($id)){
            return self::success(message: "ticket $id successfully deleted");
        }
        return $this->error();
    }
}
