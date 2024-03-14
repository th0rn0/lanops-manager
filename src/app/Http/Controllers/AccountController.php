<?php

namespace App\Http\Controllers;

use Auth;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{
    /**
     * Show Account Index Page
     * @return View
     */
    public function index()
    {
        $user = Auth::user();
        $purchases = $user->purchases()->paginate(5, ['*'], 'pu');
        $tickets = $user->eventParticipants()->paginate(5, ['*'], 'ti');

        $state = bin2hex(openssl_random_pseudo_bytes(12));
        Session::put('discordoauth', $state);
        $discordLinkUrl = 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . config('app.discord_client_id') . '&redirect_uri=' . config('app.discord_redirect_url') . '&scope=' . config('app.discord_scope') . "&state=" . $state;

        return view("accounts.index")
            ->withUser($user)
            ->withPurchases($purchases)
            ->withEventParticipants($tickets)
            ->withDiscordLinkUrl($discordLinkUrl)
        ;

    }

    public function unlinkDiscord(Request $request) 
    {
        $user = Auth::user();
        $user->discord_id = null;
        $user->discord_avatar = null;
        $user->discord_username = null;

        if (!$user->save()) {
            Session::flash('alert-danger', 'Something went wrong, please try again!');
        }

        return Redirect::to('account');
    }

    public function linkDiscord(Request $request) 
    {
        // DEBUG - WE HAVE A TOKEN - GO GRAB THE USER DETAILS AND SAVE THEM
        $code = $request->input('code');
        $state = $request->input('state');
        // DEBUG
        # Check if $state == $_SESSION['state'] to verify if the login is legit | CHECK THE FUNCTION get_state($state) FOR MORE INFORMATION.
        $url = "https://discord.com/api/oauth2/token";
        $data = array(
            "client_id" => config('app.discord_client_id'),
            "client_secret" => config('app.discord_client_secret'),
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => config('app.discord_redirect_url')
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $results = json_decode($response, true);

        $accessToken = $results['access_token'];

        $url = "https://discord.com/api/users/@me";
        $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $accessToken);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);
        $results = json_decode($response, true);

        $user = Auth::user();
        $user->discord_id = $results['id'];
        $user->discord_username = $results['global_name'];
        $user->discord_avatar = $results['avatar'];

        if (!$user->save()) {
            Session::flash('alert-danger', 'Something went wrong, please try again!');
        }
        return Redirect::to('account');
    }

    public function update(Request $request)
    {
        $rules = [
            'firstname'     => 'filled',
            'surname'       => 'filled',
            'email'         => 'filled|email',
            'password1'     => 'same:password2',
            'password2'     => 'same:password1',
        ];
        $messages = [
            'email.filled'      => 'Email Cannot be blank.',
            'firstname.filled'  => 'Firstname Cannot be blank.',
            'surname.filled'    => 'Surname Cannot be blank.',
            'email.email'       => 'Email must be a valid Email Address.',
            'password1.same'    => 'Passwords must be the same.',
            'password2.same'    => 'Passwords must be the same.',
        ];
        $this->validate($request, $rules, $messages);

        $user = Auth::user();
        if (isset($request->password1) && $request->password1 != null) {
            $rules = [
                'password1'     => 'same:password2|min:8',
                'password2'     => 'same:password1|min:8',
            ];
            $messages = [
                'password1.same'    => 'Passwords must be the same.',
                'password1.min'     => 'Password must be atleast 8 characters long.',
                'password2.same'    => 'Passwords must be the same.',
                'password2.min'     => 'Password must be atleast 8 characters long.',
            ];
            $this->validate($request, $rules, $messages);
            $user->password = Hash::make($request->password1);
        }

        $user->email = @$request->email;
        $user->firstname = @$request->firstname;
        $user->surname = @$request->surname;

        if (!$user->save()) {
            return Redirect::back()->withFail("Oops, Something went Wrong.");
        }
        return Redirect::back()->withSuccess('Account successfully updated!');
    }
}
