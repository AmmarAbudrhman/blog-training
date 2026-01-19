<?php

namespace App\Http\Catgories;

use App\Http\Controllers\Controller;
use App\Models\Catgories;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $catgory = Catgories::find($id);
        if (!$catgory) {
            return $this->errorResponse('Category not found', 404);
        }

        return DB::transaction(function () use ($catgory) {
            if ($catgory->image) {
                ImageHelper::delete($catgory->image);
            }

            $catgory->delete();
            return $this->successResponse(null, 'Category deleted successfully');
        });
    }
}
