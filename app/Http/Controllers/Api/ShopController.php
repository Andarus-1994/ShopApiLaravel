<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //

    public function retrieveMainCategories () {
        $categories = MainCategory::with('categories')->get();
        $categories->each(function ($category) {
            $category['open'] = false;
        });
        return $categories;
    }

    public function retrieveItems (Request $request): \Illuminate\Http\JsonResponse
    {
        $categoryId = $request->categoryId;
        $items = Item::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->get();
        return response()->json($items, 200);
    }
}
