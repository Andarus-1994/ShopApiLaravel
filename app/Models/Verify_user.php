<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verify_user extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    private $verify_token;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
