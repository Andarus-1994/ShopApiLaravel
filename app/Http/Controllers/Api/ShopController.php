<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MainCategories;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //

    public function retrieveMainCategories () {
        $categories = MainCategories::all();
        $categories->each(function ($category) {
            $category['open'] = false;
        });
        return $categories;
    }
}
