<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TmpData;
use App\Notifications\MagicLink;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'The provided credentials do not match our records.');
        }

        $user = $request->user();

        // Retrieve the user's existing tokens
        $tokens = $user->tokens;

        // Check if the user has an existing token and it is not expired
        $now = Carbon::now();
        $existingToken = $tokens->first(function ($token) use ($now) {
            return $token->revoked === false && $token->expires_at > $now;
        });

        if ($existingToken) {
            // If a valid token exists, reuse it
            $accessToken = $existingToken->token;
            $expiresAt = $existingToken->expires_at;
        } else {
            // If no valid token exists, create a new one
            $tokenResult = $user->createToken('Personal Access Token',['*']);
            $token = $tokenResult->token;
            $token->save();

            $accessToken = $tokenResult->accessToken;
            $expiresAt = $token->expires_at;
        }

        // Store token information in the session
        $request->session()->put('token', $accessToken);
        $request->session()->put('token_type', 'Bearer');
        $request->session()->put('expires_at', Carbon::parse($expiresAt)->toDateTimeString());

        // Redirect the user to a different page
        if (isset($request['return_url'])){
            return redirect()->to($request['return_url']);
        }else{
            return redirect($this->redirectTo);
        }

    }

    public function authenticateMagicLink($token)
    {
        $user = User::where('magic_token', $token)
            ->where('magic_token_expiry', '>', Carbon::now())
            ->first();

        if (!$user) {
            if (request()->expectsJson()) {
                // For API requests, return a JSON response with an error message
                return response()->json(['error' => 'The magic link is invalid or expired.'], 401);
            } else {
                // For web requests, redirect with an error message
                return redirect('/booking')->withErrors(['magic_link' => 'The magic link is invalid or expired.']);
            }
        }

        // Log in the user

        Auth::login($user);

        $user = Auth::user();


        // If no valid token exists, create a new one
        $tokenResult = $user->createToken('Personal Access Token',['*']);
        $token = $tokenResult->token;
        $token->save();

        $accessToken = $tokenResult->accessToken;
        $expiresAt = $token->expires_at;
        $postcode = Session::get('postcode');

        // Store token information in the session
        Session::put('token', $accessToken);
        Session::put('magic_token', $user->magic_token);
        Session::put('postcode', $postcode);
        Session::put('token_type', 'Bearer');
        Session::put('expires_at', Carbon::parse($expiresAt)->toDateTimeString());

        if (request()->expectsJson()) {
            // For API requests, return a JSON response with a success message or user data
            return response()->json(['message' => 'Login successful', 'token' => $accessToken]);
        } else {
            // For web requests, redirect to the specified URL or the default URL
            $redirectUrl = request()->query('redirect', '/');
            return redirect($redirectUrl);
        }
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function sendMagicLink(Request $request)
    {
        // Validate the request to ensure the email is provided
        $request->validate(['email' => 'required|email']);

        // Try to retrieve the user first
        $user = User::where('email', $request->email)->first();

        // If user doesn't exist, create a new user
        if (!$user) {

            $user = User::create([
                'email' => $request->email,
                'customer_name' => (isset($request->uFName) && isset($request->uLName)) ? $request->uFName .' '.$request->uLName : null,
                'uFName' => (isset($request->uFName)) ? $request->uFName : null,
                'uLName' => (isset($request->uLName)) ? $request->uLName : null,
                'uCompanyName' => (isset($request->uCompanyName)) ? $request->uCompanyName : null,
                'customer_type' => (isset($request->customer_type)) ? $request->customer_type : null,
                'taxNumber' => (isset($request->taxNumber)) ? $request->taxNumber : null,
            ]);

        }

        if ($user && !empty($user->magic_token) && Carbon::parse($user->magic_token_expiry) > Carbon::now()){
            $token = $user->magic_token;
        }else{
            // Generate a unique token and set the expiry time
            $token = hash_hmac('sha256', Str::random(40) . $user->email, config('app.key')); // Unique token
            $user->magic_token = $token;
            $user->magic_token_expiry = Carbon::now()->addHours(24); // Expiry time is 24 hours from now
            $user->save();
        }




        if (isset($request->return_url)){
            $redirectUrl = $request->return_url;
            // Check if order_data exists in the request
            if (isset($request->order_data)) {
                // Decode the existing order_data JSON
                $orderData = json_decode($request->order_data, true);

                // Add or update customer values in the order_data
                $orderData['customer_id'] = $user->id; // Add the customer ID
                $orderData['customer_type'] = $user->customer_type; // Add the customer type
                $orderData['customer_name'] = $user->customer_name?: $user->uFName.' '.$user->uLName; // Add the customer name
                $orderData['uFName'] = $user->uFName; // Add the first name
                $orderData['uLName'] = $user->uLName; // Add the last name
                $orderData['uCompanyName'] = $user->uCompanyName; // Add the Company Name
                $orderData['taxNumber'] = $user->taxNumber; // Add the tax number
                $orderData['email'] = $user->email; // Add the email
                $orderData['phone_number'] = $user->phone_number; // Add the phone number

                // Encode the modified order_data back to JSON
                $encodedOrderData = json_encode($orderData, true);

                // Save the modified order_data to TmpData
                TmpData::updateOrCreate([
                    'token' => $token],
                   [ 'order_data' => $encodedOrderData,
                ]);

                Session::put('postcode',$orderData['postcode']);

            }
        }else{
            $redirectUrl = '/';
        }

        // Create the magic link
        $magicLink = url('/magic-link/' . $token . '?redirect=' . urlencode($redirectUrl));


        // Send the magic link via the MagicLinkNotification
        $user->notify(new MagicLink($magicLink));

        return response()->json(['message' => 'Magic link sent!']);
    }


}
