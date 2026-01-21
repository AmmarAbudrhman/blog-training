<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        
        return $this->successResponse(null, 'Comment deleted successfully');
    }
}
