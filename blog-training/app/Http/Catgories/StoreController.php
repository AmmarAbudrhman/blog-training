<?php

namespace App\Http\Catgories;

use App\Http\Controllers\Controller;
use App\Models\Catgories;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CategoryResource;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:catgories,name',
            'image' => ImageHelper::getValidationRules(),
        ]);

        return DB::transaction(function () use ($request) {
            $catgory = new Catgories();
            $catgory->name = $request->name;

            if ($request->hasFile('image')) {
                $catgory->image = ImageHelper::upload($request->file('image'), 'categories');
            }

            $catgory->save();
            return $this->successResponse(new CategoryResource($catgory), 'Category created successfully', 201);
        });
    }
}
