<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function items()

    {
        return $this->hasMany(Item::class);
    }

    public function main_categories () {

        return $this->belongsTo(MainCategory::class);
    }
}
