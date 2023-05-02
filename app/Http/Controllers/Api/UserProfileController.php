<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function getUserProfile(Request $request):  JsonResponse
    {
        $user =  auth('sanctum')->user();
        $user_model = User::with('verify_user')->findOrFail($user['id']);
        $verify_account = true;
        if (isset($user_model['verify_user']['verify_token'])) {
            $verify_account = false;
        }

        $user->role = $user->getRoleNames();
        $permissions = $user->getPermissionsViaRoles()->unique('name')->pluck('name');
        $user->permissions = $permissions;

        return response()->json([
            'status' => true,
            'info' => $user,
            'verified' => $verify_account
        ], 200);
    }

    public function updateProfile(Request $request):  JsonResponse
    {
        $request->validate([
            'user.first_name' => 'max:255',
            'user.last_name' => 'max:255',
            'user.address' => 'max:255'
        ]);
        $user =  auth('sanctum')->user();
        $profileImage = $request->file('profile_image');
        $request = json_decode($request->input('user'), true);

        $urlImage = $user['profile_image'];
        // Check if an image file was uploaded
        if ($profileImage) {
            // Get the original file name
            $fileName = $profileImage->getClientOriginalName();
            // Move the uploaded file to a public storage directory
            $profileImage->move(public_path('storage/profile_images/' . $user->id . '/' ), $fileName);
            $urlImage = asset('storage/profile_images/' . $user->id . '/' . $fileName);
        }


        $user->update(['first_name' =>  $request['first_name'],
            'last_name' => $request['last_name'],
            'address' => $request['address'],
            'profile_image' => $urlImage,
            ]);

        $user->role = $user->getRoleNames();
        $permissions = $user->getPermissionsViaRoles()->unique('name')->pluck('name');
        $user->permissions = $permissions;

        return response()->json([
            'status' => true,
            'info' => $user
        ], 200);
    }


}
