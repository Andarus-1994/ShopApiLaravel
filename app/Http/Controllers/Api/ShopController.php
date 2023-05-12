<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MainCategories;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //

    public function retrieveMainCategories () {

        return MainCategories::all();
    }
}
