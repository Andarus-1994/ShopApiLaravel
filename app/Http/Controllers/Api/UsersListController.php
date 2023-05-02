<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($page, $word = '')
    {
        $users = User::where('user', 'LIKE', '%'.$word.'%')->with('roles')->paginate(10, ['*'], 'page', $page);
        $userArray = $users->toArray();

        foreach ($userArray['data'] as &$user) {
            $roleNames = implode(', ', array_column($user['roles'], 'name'));
            $user['roles'] = $roleNames;
        }
        while (count($userArray['data']) < 10) {
            $userArray['data'][] = ['address' => '', 'first_name' => '', 'last_name' => '', 'user' => ''];
        }
        return response()->json(['data' => $userArray['data'], 'pages' => $users->lastPage()], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $deleted = User::where('id', $id)->delete();
        if ($deleted) {
            return response()->json(['data' => 'User deleted successfully'], 200);
        }
        return response()->json(['data' => 'User could not be deleted'], 400);
    }
}
