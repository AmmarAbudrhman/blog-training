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
class UpdateController extends Controller
{
    use ApiResponse;

    /**
     * Update Comment
     * 
     * Update an existing comment.
     */
    public function __invoke(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);
        
        return $this->successResponse(new CommentResource($comment), 'Comment updated successfully');
    }
}
