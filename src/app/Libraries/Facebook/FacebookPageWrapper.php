<?php

namespace App\Libraries\Facebook;

use Settings;
use Facebook\Facebook;

class FacebookPageWrapper
{
	protected $api;

	protected $app_id;

	protected $app_secret;

	protected $page_access_tokens;

	protected static $permissions = [
		'manage_pages',
		'publish_pages'
	];

	public function __construct()
    {
    	$this->_setAppId(config('facebook.config.app_id'));
    	$this->_setAppSecret(config('facebook.config.app_secret'));
    	$this->_setPageAccessTokens(Settings::getSocialFacebookPageAccessTokens());
    	$this->_setApi(config('facebook.config'));
    }

    protected function _setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    protected function _setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;
    }

    protected function _setPageAccessTokens($page_access_tokens)
    {
        $this->page_access_tokens = $page_access_tokens;
    }

    protected function _setApi($config)
    {
    	$this->api = new Facebook(config('facebook.config'));
    }

    public static function isEnabled()
    {
    	if (empty(config('facebook.config.app_id')) || empty(config('facebook.config.app_secret'))) {
    		return false;
    	}
    	return true;
    }

    public static function isLinked()
    {
    	if (empty(Settings::getSocialFacebookPageAccessTokens()))
    	{
    		return false;
    	}
    	return true;
    }

    public static function getLoginUrl()
    {
    	$api = new Facebook(config('facebook.config'));
    	return $api->getRedirectLoginHelper()->getLoginUrl(url('/admin/settings/link/facebook'), self::$permissions);
    }

    public static function getUserAccessToken($long_lived = true)
    {
    	$api = new Facebook(config('facebook.config'));
    	$facebook_helper = $api->getRedirectLoginHelper();
    	try {
		  	$user_access_token = $facebook_helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'alert-danger', 'Graph returned an error: ' . $e->getMessage();
			return false;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			return false;
		}
		if (!isset($user_access_token)) {
			if ($facebook_helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
				$message = "Error: " . $facebook_helper->getError() . "\n";
				$message .= "Error Code: " . $facebook_helper->getErrorCode() . "\n";
				$message .= "Error Reason: " . $facebook_helper->getErrorReason() . "\n";
				$message .= "Error Description: " . $facebook_helper->getErrorDescription() . "\n";
				echo 'HTTP/1.0 401 Unauthorized.' . $message;
				return false;
			}
			echo 'HTTP/1.0 400 Bad Request.';
			return false;
		}
		// The OAuth 2.0 client handler helps us manage access tokens
		$oauth_client = $api->getOAuth2Client();

		// Get the access token metadata from /debug_token
		$token_metadata = $oauth_client->debugToken($user_access_token);
		$token_metadata->validateAppId(config('facebook.config.app_id'));
		$token_metadata->validateExpiration();

		if ($long_lived && !$user_access_token->isLongLived()) {
			// Exchanges a short-lived access token for a long-lived one
			try {
				$user_access_token = $oauth_client->getLongLivedAccessToken($user_access_token);
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				Session::flash('alert-danger', "Error getting long-lived access token: " . $e->getMessage());
				return Redirect::to('/admin/settings');
			}
		}

	    return $user_access_token;
    }

    public static function getPageAccessTokens($user_access_token)
    {
    	$api = new Facebook(config('facebook.config'));
		try{
			$response = ($api->get('/me/accounts?fields=access_token', $user_access_token))->getDecodedBody();
		} catch (Facebook\Exceptions\FacebookSDKException $e) { 
			echo "Error getting long-lived access token: " . $e->getMessage();
			return false;
		}
		$page_access_tokens = array();
		foreach ($response['data'] as $pages) {
			array_push($page_access_tokens, $pages['access_token']);
		}
		return $page_access_tokens;
    }

    public static function postNewsToPage($title, $article, $slug)
    {
		$api = new Facebook;
	 	$linkData = [
		 	'link' => url("/news/{$slug}"),
		 	'message' => $title . "\n \n" . strip_tags(substr($article, strpos($article, "<p"), strpos($article, "</p>")+4)) . "\n \n" . "Read More Below..."
		];
		if (!in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
			$linkData['link'] = "http://example.com/news/{$slug}";
		}
		foreach (Settings::getSocialFacebookPageAccessTokens() as $page_access_token) {
			try {
			 	$response = $api->post('/me/feed', $linkData, $page_access_token);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: '.$e->getMessage();
				return false;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: '.$e->getMessage();
				return false;
			}
			$graphNode = $response->getGraphNode();
		}
		return true;
    }
}