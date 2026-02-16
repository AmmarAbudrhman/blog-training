<?php

namespace App\Http\Tags;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return $this->successResponse(null, 'Tag deleted successfully');
    }
}
