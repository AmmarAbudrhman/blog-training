<?php

namespace App\Http\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $comment = Comment::findOrFail($id);

        // Allow owner or admin (if role exists, user requested role earlier) to delete
        // Assuming user role logic: $user->role === 'admin' check, or just owner for now
        if ($comment->user_id !== Auth::id()) {
             // Optional: Check if user is admin
             // if (Auth::user()->role !== 'admin') { return 403 }
            return $this->errorResponse('Unauthorized', 403);
        }

        $comment->delete();

        return $this->successResponse(null, 'Comment deleted successfully');
    }
}
