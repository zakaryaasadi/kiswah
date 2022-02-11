<?php

namespace App\Http\Controllers\API;

use App\Models\Locations;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


    public function authenticate(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->respondWithError('Account not found');
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->respondWithError(['error' => 'Invalid credentials'], 400);
        }
        $token = $user->createToken('ADMIN_TOKEN', ['view_as_admin'])->accessToken;
        return $this->respondWithSuccess(['data' => ['token' => $token, 'user' => $user]], 201);
    }


    /**
     * Gets Authenticated User
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        if (!$user = getUser()) {
            return response()->json(['error' => 'user_not_found'], 404);
        }
        return $this->respondWithSuccess($user);
    }

    public function uploadAvatar(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'avatar' => ['required', 'file', 'mimes:jpg,bmp,png'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        if ($file = $request->file('avatar')) {
            $user = getUser();
            $avatar = moveFile($file, 'avatar');
            $user->update(['avatar' => $avatar]);
            return $this->respondWithSuccess($user);
        }
        return $this->respondWithError('Avatar upload was not successful');

    }


    public function forgetPassword(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'email' => ['required', 'email'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return $this->respondWithError('Account does not match record');
        }
        $token = rand(777777, 999999);
        DB::table('password_resets')->create(['email' => $request->email, 'token' => $token, 'created_at' => now()]);

        //Notify User
        //Send Email
        return $this->respondWithSuccess('Successfully created a reset token check your email');
    }

    public function updatePassword(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'avatar' => ['required', 'file', 'mimes:jpg,bmp,png'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        if ($file = $request->file('avatar')) {
            $user = getUser();
            $avatar = moveFile($file, 'avatar');
            $user->update(['avatar' => $avatar]);
            return $this->respondWithSuccess($user);
        }
        return $this->respondWithError('Avatar upload was not successful');

    }

    public function verifyOtp(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'otp' => ['required', 'min:6'],
            'email' => ['required', 'min:6'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
//        $pass = \App\Models\
        if (false) {
            return $this->respondWithSuccess($user);
        }
        return $this->respondWithError('Avatar upload was not successful');

    }

    public function changePassword(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'current_password' => ['required', 'min:6',],
            'password' => ['required', 'min:6',],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $user = getUser();
        if (!Hash::check($request->password, $user->password)) {
            return $this->respondWithError('Current password is wrong');
        }
        $user->update(['password' => Hash::make($request->password)]);
        return $this->respondWithSuccess('Password changed successfully');

    }


    public function index(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'username' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $user = User::where('username', $request->username)->first();
        return $this->respondWithSuccess(['is_available' => !$user]);
    }


    public function locations()
    {
        $customer = auth()->user();
        $locations = $customer->locations;
        return $this->respondWithSuccess($locations);
    }

    public function store(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'email' => 'required|unique:users',
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $customer = User::create(
            $request->merge(['password' => Hash::make($request->password)])
                ->except(['address', 'longitude', 'latitude'])
        );

        return $this->respondWithSuccess($customer);
    }

    public function update(CustomerPostRequest $request)
    {
        $user = getUser();
        return $customer;
    }

//    public function destroy(Request $request, User $customer)
//    {
//        $customer->delete();
//        return $customer;
//    }

}
