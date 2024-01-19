<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SocialLoginAccount;
use App\Models\TmpData;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Customer Registration
     */
    public function register(Request $request)
    {
        $nameArr = explode(' ',$request->customer_name);
        $exist = User::where('email', $request->email)->exists();

        if (!$exist){
            $user = User::create([
                'customer_name' => $request->customer_name,
                'uFName' => $nameArr[0],
                'uLName' => $nameArr[1],
                'email' => $request->email,
                'phone_number' => (isset($request->phone_number))?$request->phone_number:'',
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'User registered successfully!', 'user' => $user,'success' => true], 201);
        }else{
            return response()->json(['error' => 'User already exists!','success' => false], 403);
        }
    }

   /**
     * Customer Login
   */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $exist = User::where('email', $request->email)->exists();
        if ($exist){
            if (Auth::attempt($credentials)) {
                $token = Auth::user()->createToken('HLApp')->accessToken;
                return response()->json(['token' => $token,'success' => true], 200);
            } else {
                return response()->json(['error' => 'Invalid credentials','success' => false], 401);
            }
        }else {
            return response()->json(['error' => 'User does not exist!','success' => false], 404);
        }
    }



    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Social Login
     */

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            $data = $request->all();

            // Validate the incoming data (Ensure this validation is adequate for your use case)
            $validator = Validator::make($data, [
                'email' => 'required|email|max:255',
                'name' => 'required|max:255',
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $account = SocialLoginAccount::where('provider', 'google')
                ->where('provider_id', $data['id'])
                ->first();

            if ($account) {
                $user = User::where('id',$account->user_id)->first();
                $token = $user->createToken('HLApp')->accessToken;
            } else {
                $user = User::where('email', '=', $data['email'])->first();
                $name = explode(' ', $data['name']);
                if (!$user) {
                    $user = User::create([
                        'email' => $data['email'],
                        'uFName' => $name[0],
                        'uLName' => $name[1],
                        'customer_name' => $data['name'],
                        'status' => 1,
                    ]);
                }
                $user->social_login_accounts()->updateOrCreate(
                    [
                        'provider' => 'google',
                        'provider_id' => $data['id'],
                    ]
                );

                $token = $user->createToken('HLApp')->accessToken;
            }

            return response()->json(['token' => $token, 'user' => $user, 'success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed', 'success' => false], 401);
        }
    }

    public function authCheck()
    {
        $guardUsed = null;
        $user = null;

        if (Auth::guard('web')->check()) {
            $guardUsed = 'web';
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('api')->check()) {
            $guardUsed = 'api';
            $user = Auth::guard('api')->user();
        }

        return response()->json([
            'isLoggedIn' => $guardUsed !== null,
            'guard' => $guardUsed,
            'user' => $user
        ]);
    }
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $exists = User::where('email', $request->email)->exists();

        if ($exists) {
            return response()->json(['message' => 'Already have an account.'], 200);
        }

    }

    public function registerEmailOnly(Request $request)
    {
        $request->validate([
            'uFName' => 'required|string|max:255',
            'uLName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $customer_name = $request->uFName.' '.$request->uLName;
        $userId = DB::table('customers')->insertGetId([
            'customer_name' => $customer_name,
            'uFName' => $request->uFName,
            'uLName' => $request->uLName,
            'email' => $request->email,
            'customer_type' => (isset($request->customer_type) ? $request->customer_type : null),
            'uCompanyName' => (isset($request->uCompanyName) ? $request->uCompanyName : null),
            'taxNumber' => (isset($request->taxNumber) ? $request->taxNumber : null),
            'phone_code' => (isset($request->phone_code) ? $request->phone_code : null),
            'phone_number' => (isset($request->phone_number) ? $request->phone_number : null),
        ]);



//        echo $userId;
        Auth::loginUsingId($userId);
        $user = Auth::user();


        return response()->json([
            'message' => 'User registered and logged in successfully!',
            'user' => $user,  // you might want to unset sensitive information
//            'redirectTo' => url('/booking?step=5'),  // or any other route
        ], 201);
    }

    public function getTempData($token)
    {

        $orderTempData = TmpData::where('token',$token)->first();

        $user = User::where('magic_token',$token)->first();

        $tempData = ($orderTempData) ? json_decode($orderTempData->order_data,true) : null;

        return response()->json(['temp_data' => $tempData, 'user' => $user]);
    }



}
