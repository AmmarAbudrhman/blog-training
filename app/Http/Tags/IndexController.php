<?php

namespace App\Http\Tags;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $tags = Tag::all();
        return $this->successResponse(TagResource::collection($tags));
    }
}
