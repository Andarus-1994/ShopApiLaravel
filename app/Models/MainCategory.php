<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function categories () {

        return $this->hasMany(Category::class, 'main_category_id');
    }
}
