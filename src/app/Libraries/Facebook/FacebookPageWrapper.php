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
}