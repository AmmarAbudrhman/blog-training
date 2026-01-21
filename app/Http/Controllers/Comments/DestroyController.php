<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * @group Comments
 */
class DestroyController extends Controller
{
    use ApiResponse;

    /**
     * Delete Comment
     * 
     * Remove a comment from the database.
     */
    public function __invoke(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        
        return $this->successResponse(null, 'Comment deleted successfully');
    }
}
