<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'main_category_id'
    ];

    public function items()

    {
        return $this->belongsToMany(Item::class, 'category_item');
    }

    public function main_categories () {

        return $this->belongsTo(MainCategory::class);
    }
}
