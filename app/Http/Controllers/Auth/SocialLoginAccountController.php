<?php
/**
 * Created by PhpStorm.
 * User: AJIM
 * Date: 11-10-2022
 * Time: 14:38
 */

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\SocialLoginAccount;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\User as ProviderUser;


class SocialLoginAccountController extends Controller
{
    use AuthenticatesUsers;

    public function oAuthRedirect($provider)
    {
        // Store the previous URL into the session
        session(['url.intended' => url()->previous()]);

        return Socialite::driver($provider)->redirect();

    }

    public  function oAuthCallback($provider)
    {

        $user = $this->findOrCreateUser(Socialite::driver($provider)->user(), $provider);

        Auth::guard('web')->login($user);

// Retrieve the previously stored URL or default to the root
        $redirectUrl = session()->pull('url.intended', '/');

        return redirect($redirectUrl);
    }

    public function findOrCreateUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialLoginAccount::where('provider', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($account) {
            return $user = User::where('id',$account->user_id)->first();
        } else {

            $user = User::where('email', '=', $providerUser->getEmail())->first();
            $name = explode(' ',$providerUser->getName());
            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'uFName' => $name[0],
                    'uLName' => $name[1],
                    'customer_name' => $providerUser->getName(),
                    'status' => 1,
                ]);
            }

            $user->social_login_accounts()->updateOrCreate(
                [
                    'provider' => $provider,
                    'provider_id' => $providerUser->getId(),
                ]
            );

            return $user;
        }
    }
}
