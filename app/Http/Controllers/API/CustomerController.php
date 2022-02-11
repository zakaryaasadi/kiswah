<?php

namespace App\Http\Controllers\API;

use App\Models\Locations;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\Tookan;
use Validator;
use DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    private $tookan;

    public function __consturctor(Tookan $tookan)
    {
        $this->tookan = $tookan;
    }

    public function authenticate(Request $request)
    {
        $this->validateData($request, ['email' => 'required', 'password' => 'required']);
        $user = Customer::query()->orWhere('phone', $request->email)->orWhere('email', $request->email)->first();
        if (!$user) {
            return $this->respondWithError('Account not found');
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->respondWithError(['error' => 'Invalid credentials'], 400);
        }
        $token = $user->createToken('LaravelAuthApp')->accessToken;

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
            $avatar = moveFile($file, 'avatar');;
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
        DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => now()]);

        //Notify User
        //Send Email
        return $this->respondWithSuccess('Successfully created a reset token check your email ' . $token);
    }

    public function updatePassword(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'password' => ['required', 'confirmation', 'min:6'],
            'old_password' => ['required'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $user = getUser();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => Hash::make($request->password)]);
            return $this->respondWithSuccess($user);
        }
        return $this->respondWithError('Invalid Old Password');
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
        $token = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();

        //        $pass = \App\Models\
        if ($token) {
            return $this->respondWithSuccess($token);
        }
        return $this->respondWithError('Token not valid');
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
        if (!Hash::check($request->current_password, $user->password)) {
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
        $user = Customer::where('username', $request->username)->first();
        return $this->respondWithSuccess(['is_available' => !$user]);
    }


    public function locations()
    {
        $customer = auth()->user();
        $locations = $customer->locations;
        return $this->respondWithSuccess($locations);
    }

    public function store(CustomerPostRequest $request)
    {
        $customer = Customer::create(
            $request->merge(['password' => Hash::make($request->password)])
                ->except(['address', 'longitude', 'latitude'])
        );
        if ($request->has('address') && $request->has('longitude')) {
            $customer->locations()->create([
                'title' => 'Home',
                'address' => $request->address,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'is_default' => true,
            ]);
        }
        $tookan =  (new Tookan())->addCustomer($request->except(['password', 'username']));
        if ($tookan->status === 200) {
            $customer->tookan_id = $tookan->data['customer_id'];
            $customer->save();
        }
        if ($tookan->status === 201) {
            //Find Customer by phone on Tookan
        }
        $token = $customer->createToken('LaravelAuthApp')->accessToken;
        return $this->respondWithSuccess(['data' => ['token' => $token, 'user' => $customer]], 201);
    }

    public function update(Request $request)
    {
        $user = getUser();
        $user->update($request->expect(['password', 'avatar']));
        (new Tookan())->editCustomer($request->except(['password', 'username', 'avatar']));
        return $this->respondWithSuccess(['data' => $user], 201);
    }
}
