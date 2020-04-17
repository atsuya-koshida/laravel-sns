<?php

namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Controller;
//==========ここから追加==========
use App\User;
//==========ここまで追加==========
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//==========ここから追加==========
use Illuminate\Http\Request;
//==========ここまで追加==========
use Laravel\Socialite\Facades\Socialite;

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
    
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, string $provider)
    {
        $providerUser = Socialite::driver($provider)->stateless()->user();
 
        $user = User::where('email', $providerUser->getEmail())->first();
 
        if ($user) {
            $this->guard()->login($user, true);
            return $this->sendLoginResponse($request);
        }
    }
}
