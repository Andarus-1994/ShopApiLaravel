<?php

namespace App\Http\Controllers\Api;

use App\Mail\sendUserMail;
use App\Mail\sendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Verify_user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'user' => 'required|unique:users',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }
            $verify_token = $this->quickRandom(32);
            $user = User::create([
                'user' => $request->user,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_image' => $request->profile_image
            ]);
            if (User::count() === 1) {
                $user->assignRole('super-admin');
            } else {
                $user->assignRole($request->role);
            }

            $verify_user = new Verify_user;
            $verify_user->verify_token = $verify_token;
            $user->verify_user()->save($verify_user);

            Mail::to($user->email)->send(new sendUserMail(['user' => $user->user, 'token' => $verify_token, 'frontend_url' => env('FRONTEND_URL')]));

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully! Check your mail for verification',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function verifyUser(Request $request): JsonResponse
    {
        $verify_user = Verify_user::query()->where('verify_token', $request->token)->delete();
        if ($verify_user) {
            return response()->json([
                'status' => true,
                'message' => 'User verified!',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'User already verified!',
        ], 200);
    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'user' => 'required',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            if (!Auth::attempt($request->only(['user', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'User & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('user', $request->user)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function sendPasswordReset (Request $request): JsonResponse {
        $email = $request['email'];
        $code = mt_rand(1000, 9999);
        if (User::where('email', $email)->first()){
            $user = User::where('email', $email)->first()->user;
            Mail::to($email)->send(new sendCodeResetPassword(['code' => $code, 'user' =>$user, 'frontend_url' => env('FRONTEND_URL')]));
        } else {
            return response()->json([
                'status' => true,
                'error' => 'There is no user created using this email.',
            ], 200);
        }

        ResetCodePassword::where('email', $email)->delete();
        ResetCodePassword::create(["email" => $email, "code" => $code, 'created_at' => Carbon::now()]);
        return response()->json([
            'status' => true,
            'request' => $request,
            'message' => 'Check your email'
        ], 200);
    }

    public function checkCode (Request $request): JsonResponse {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        $passwordReset = ResetCodePassword::firstWhere('code', $request['code']);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response()->json([
                'status' => true,
                'error' => 'Expired code.'
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Code is valid'
        ], 200);
    }

    public function resetPassword (Request $request): JsonResponse {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $passwordReset = ResetCodePassword::firstWhere('code', $request['code']);

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response()->json([
                'status' => true,
                'error' => 'Expired code.'
            ], 200);
        }

        $user = User::firstWhere('email', $passwordReset->email);
        $user->update(['password' =>  Hash::make($request->password)]);

        $passwordReset->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password has been successfully reset!'
        ], 200);
    }
}
