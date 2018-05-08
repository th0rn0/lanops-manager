<?php 

namespace App\Http\Controllers\Auth;

use Auth;

use App\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Invisnik\LaravelSteamAuth\SteamAuth;

class SteamAuthController extends Controller
{
	private $steam;

	/**
	 * @param SteamAuth $steam
	 */
	public function __construct(SteamAuth $steam)
	{
		$this->steam = $steam;
	}

	/**
	 * Login User
	 * @return Redirect
	 */
	public function login()
	{
		if ($this->steam->validate()) { 
			$info = $this->steam->getUserInfo();
			if (!is_null($info)) {
				$user = User::where('steamid', $info->steamID64)->first();
				if (!is_null($user)) {
					//username found... Log user in
					Auth::login($user, true);
					//Check if the user has changed their steam details
					$steam_changes = FALSE;
					if ($info->personaname != $user->steamname) {
						$user->steamname = $info->personaname;
						$steam_changes = TRUE;
					}
					if ($info->avatarfull != $user->avatar) {
						$user->avatar = $info->avatarfull;
						$steam_changes = TRUE;
					}
					if ($steam_changes) {
						$user->save();
					}
					return redirect('/'); // redirect to site
				} else {
					$user = [
							'steamname' => $info->personaname,
							'avatar'    => $info->avatarfull,
							'steamid'   => $info->steamID64,
					];
					return view('login.steam.register', $user); // show register page
				}
			}
		} else {
			return $this->steam->redirect(); // redirect to Steam login page
		}
	}

	// DEBUG - REDUNDENT?
	/**
	 * Steam Register to grab the users email address
	 * @param  User   $user
	 */
	public function register(User $user)
	{
		if (!is_null($user->email)) {
			return redirect('/'); // redirect to site 
		} else {
			return view('login.steam.register')->withUser($user);  
		}
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 * @param  Request  $request
	 * @param  User  $user
	 * @return User
	 */
	public function store(Request $request, User $user)
	{
		$this->validate($request, [
				'fistname' 	=> 'string',
				'surname' 	=> 'string',
				'surname' 	=> 'string',
				'steamid' 	=> 'string',
				'avatar' 	=> 'string',
				'steamname' => 'string',
				'username' 	=> 'unique:users,username',
		]);
		$user->firstname 		= $request->firstname;
		$user->surname 			= $request->surname;
		$user->username 		= $request->username;
		$user->steamname 		= $request->steamname;
		$user->avatar 			= $request->avatar;
		$user->steamid 			= $request->steamid;
		$user->username_nice 	= strtolower(str_replace(' ', '-', $request->username));
		if (User::count() >= 1) {
			$user->admin = true;
		}
		if ($user->save()) {
			Auth::login($user, true);
			return Redirect('/account');
		}
		Auth::logout();
		return Redirect('/')->withError('Something went wrong. Please Try again later');
	}

	/**
	 * Update user details.
	 * @param  Request $request
	 * @param  User    $user
	 * @return Redirect
	 */
	public function update(Request $request, User $user)
	{
		$this->validate($request, [
				'fistname' 	=> 'string',
				'surname' 	=> 'string',
				'username' 	=> 'unique:users,username',
		]);
		$user->firstname = $request->firstname;
		$user->surname = $request->surname;
		$user->username = $request->username;
		$user->username_nice = strtolower(str_replace(' ', '-', $request->username));
		if ($user->save()) {
			return Redirect('/account');
		}
		Auth::logout();
		return Redirect('/')->withError('Something went wrong. Please Try again later');
	}

	/**
	 * Logout
	 * @return Redirect
	 */
	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return redirect('/'); // redirect the user to the login screen
	}

}