<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

/**
 * @group Comments
 */
class StoreController extends Controller
{
    use ApiResponse;

    /**
     * Store Comment
     * 
     * Create a new comment.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            // 'post_id' => 'required|integer|exists:posts,id',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'user_id' => FacadesAuth::id(),
            // 'blog_id' => $request->post_id, // Assuming mapping if needed, or omitted
        ]);

        return $this->successResponse(new CommentResource($comment), 'Comment created successfully', 201);
    }
}
