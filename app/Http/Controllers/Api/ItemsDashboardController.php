<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemsDashboardController extends Controller
{
    //

    public function newMainCategory (Request $request) {
             //Validated
            $validateMainCategory = Validator::make($request->all(),
                [
                    'name' => 'required|unique:main_categories'
                ]);

            if ($validateMainCategory->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validateMainCategory->errors()
                ], 422);
            }

        // Create the MainCategory
        $mainCategory = MainCategory::create([
            'name' => $request->input('name')
        ]);

        // Add the Categories relationship
        if ($request->has('categories')) {
            $categoryIds = $request->input('categories');
            foreach ($categoryIds as $categoryId) {
                $category = Category::find($categoryId);
                if ($category) {
                    $category->main_category_id = $mainCategory->id;
                    $category->save();
                }
            }
        }

        return response()->json([
            'message' => 'Main Category created successfully !',
            'data' => $mainCategory
        ], 200);
    }

    public function newCategory (Request $request) {
        //Validated
        $validateCategory = Validator::make($request->all(),
            [
                'name' => 'required|unique:main_categories'
            ]);

        if ($validateCategory->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validateCategory->errors()
            ], 422);
        }

        // Create the MainCategory
        $category = Category::create([
            'name' => $request->input('name'),
            'main_category_id' => $request->input('mainCategory')
        ]);

        return response()->json([
            'message' => 'Category created successfully !',
            'data' => $category
        ], 200);
    }

    public function getMainCategories () {
        $categories = MainCategory::all();
        $categories->each(function ($category) {
            $category['label'] = $category['name'];
            $category['value'] = $category['id'];
        });

        return response()->json($categories, 200);
    }

    public function getCategories () {
        $categories = Category::all();
        $categories->each(function ($category) {
            $category['label'] = $category['name'];
            $category['value'] = $category['id'];
        });

        return response()->json($categories, 200);
    }
}
