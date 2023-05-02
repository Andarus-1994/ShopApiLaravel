<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;

class Role extends Model
{
    use HasFactory;
    use HasPermissions;

    protected $table = 'roles';
    protected $guard_name = 'api';

    protected $fillable = [
        'name',
    ];
}
