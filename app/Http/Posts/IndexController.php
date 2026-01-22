<?php

namespace App\Http\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Http\Resources\PostResource;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Post::with('user');

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $posts = $query->paginate(10);

        return $this->successResponse(PaginationHelper::format($posts, PostResource::class));
    }
}
