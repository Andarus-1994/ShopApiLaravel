<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
}
