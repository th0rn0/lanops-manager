<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Settings;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Prompt Login User
     * @return Redirect
     */
    public function prompt()
    {
        return view('login.prompt')
            ->withActiveLoginMethods(Settings::getLoginMethods());
    }

    /**
     * Show Register User Page
     * @param  User   $user
     */
    public function showRegister($method)
    {
        if (!in_array($method, Settings::getLoginMethods())) {
            Session::flash('alert-danger', 'Login Method is not supported.');
            return Redirect::back();
        }
        switch ($method) {
            case 'steam':
                if (!Session::has('user')) {
                    return Redirect::to('/');
                }

                $user = Session::get('user');
                
                if (is_null($user['steamid']) ||
                    is_null($user['avatar']) ||
                    is_null($user['steamname'])
                ) {
                    return redirect('/'); // redirect to site
                }
                return view('login.register', $user)->withLoginMethod('steam');
                break;
            default:
                return view('login.register')->withLoginMethod('standard');
                break;
        }
    }

    /**
     * Register User Page
     * @param  User   $user
     * @param  Request   $request
     * @return Redirect
     */
    public function register($method, Request $request, User $user)
    {
        if (!in_array($method, Settings::getLoginMethods())) {
            Session::flash('alert-danger', 'Login Method is not supported.');
            return Redirect::back();
        }
        switch ($method) {
            case 'steam':
                $this->validate($request, [
                    'fistname'  => 'string',
                    'surname'   => 'string',
                    'steamid'   => 'string',
                    'avatar'    => 'string',
                    'steamname' => 'string',
                    'username'  => 'unique:users,username',
                ]);
                $user->avatar           = $request->avatar;
                $user->steamid          = $request->steamid;
                $user->steamname        = $request->steamname;
                break;
            
            default:
                $this->validate($request, [
                    'fistname'  => 'string',
                    'surname'   => 'string',
                    'username'  => 'unique:users,username',
                ]);
                $user->email          = $request->email;
                $user->password       = Hash::make($request->password);
                break;
        }
        dd($request);
       
        $user->firstname        = $request->firstname;
        $user->surname          = $request->surname;
        $user->username         = $request->username;
        $user->username_nice    = strtolower(str_replace(' ', '-', $request->username));

        // Set first user on system as admin
        if (User::count() == 0 && User::where('admin', 1)->count() == 0) {
            $user->admin = 1;
        }

        if ($user->save()) {
            Session::forget('user');
            Auth::login($user, true);
            return Redirect('/account');
        }
        
        Auth::logout();
        return Redirect('/')->withError('Something went wrong. Please Try again later');
    }

    // /**
    //  * Create a new user instance after a valid registration.
    //  *
    //  * @param  array  $data
    //  * @return User
    //  */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'name' => $data['name'],
    //         'username' => $data['username'],
    //         'steamid' => $data['steamid'],
    //         'avatar' => $data['avatar'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    
    public function handleProviderCallback($provider)
    {
     //notice we are not doing any validation, you should do it

        $user = Socialite::driver($provider)->user();
         
        // stroing data to our use table and logging them in
        $data = [
           'name' => $user->getName(),
           'email' => $user->getEmail()
        ];
     
        Auth::login(User::firstOrCreate($data));

        //after login redirecting to home page
        return redirect($this->redirectPath());
    }
}
