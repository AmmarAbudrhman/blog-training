<?php

namespace App\Http\Controllers\Comments;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * @group Comments
 */
class IndexController extends Controller
{
    use ApiResponse;

    /**
     * List Comments
     * 
     * Get a paginated list of all comments.
     */
    public function __invoke(Request $request)
    {
        $comments = Comment::with('user')->paginate(10);
        $data = PaginationHelper::format($comments, CommentResource::class);

        return $this->successResponse($data, 'Comments retrieved successfully');
    }
}
