<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;

use DB;


class RoleController extends Controller
{
    //

    function __construct()

    {

        $this->middleware('permission:role-list', ['only' => ['getRoles']]);

        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);

        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);

        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function getRoles(Request $request): array

    {

        $roles = Role::orderBy('id','DESC')->get();

        return ['roles' => compact('roles')];

    }

}
