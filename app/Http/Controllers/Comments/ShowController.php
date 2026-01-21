<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * @group Comments
 */
class ShowController extends Controller
{
    use ApiResponse;

    /**
     * Show Comment
     * 
     * Get details of a specific comment.
     */
    public function __invoke(Comment $comment)
    {
        return $this->successResponse(new CommentResource($comment->load('user')), 'Comment retrieved successfully');
    }
}
