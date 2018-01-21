<?php

namespace App\Libraries;

class Settings
{
	public static function getOrgName()
	{
		return \App\Setting::getOrgName();
	}

	public static function setOrgName($name)
	{
		return \App\Setting::setOrgName($name);
	}

	public static function getOrgLogo()
	{
		return \App\Setting::getOrgLogo();
	}

	public static function setOrgLogo($logo)
	{
		return \App\Setting::setOrgLogo($logo);
	}

	public static function getOrgFavicon()
	{
		return \App\Setting::getOrgFavicon();
	}

	public static function setOrgFavicon($favicon)
	{
		return \App\Setting::setOrgFavicon($logo);
	}

	public static function getTermsAndConditions()
	{
		return \App\Setting::getTermsAndConditions();
	}

	public static function setTermsAndConditions($text)
	{
		return \App\Setting::setTermsAndConditions($text);
	}

	public static function getDiscordLink()
	{
		return \App\Setting::getDiscordLink();
	}

	public static function setDiscordLink($text)
	{
		return \App\Setting::setDiscordLink($text);
	}

	public static function getFacebookLink()
	{
		return \App\Setting::getFacebookLink();
	}

	public static function setFacebookLink($text)
	{
		return \App\Setting::setFacebookLink($text);
	}

	public static function getSteamLink()
	{
		return \App\Setting::getSteamLink();
	}

	public static function setSteamLink($text)
	{
		return \App\Setting::setSteamLink($text);
	}

	public static function getRedditLink()
	{
		return \App\Setting::getRedditLink();
	}

	public static function setRedditLink($text)
	{
		return \App\Setting::setRedditLink($text);
	}

	public static function getTeamspeakLink()
	{
		return \App\Setting::getTeamspeakLink();
	}

	public static function setTeamspeakLink($text)
	{
		return \App\Setting::setTeamspeakLink($text);
	}

	public static function getParticipantCountOffset()
	{
		return \App\Setting::getParticipantCountOffset();
	}

	public static function setParticipantCountOffset($number)
	{
		return \App\Setting::setParticipantCountOffset($number);
	}

	public static function getLanCountOffset()
	{
		return \App\Setting::getLanCountOffset();
	}

	public static function setLanCountOffset($number)
	{
		return \App\Setting::setLanCountOffset($number);
	}

	public static function getCurrency()
	{
		return \App\Setting::getCurrency();
	}

	public static function setCurrency($currency)
	{
		return \App\Setting::setCurrency($currency);
	}

	public static function getAboutMain()
	{
		return \App\Setting::getAboutMain();
	}

	public static function setAboutMain($text)
	{
		return \App\Setting::setAboutMain($text);
	}

	public static function getAboutShort()
	{
		return \App\Setting::getAboutShort();
	}

	public static function setAboutShort($text)
	{
		return \App\Setting::setAboutShort($text);
	}

	public static function getAboutOurAim()
	{
		return \App\Setting::getAboutOurAim();
	}

	public static function setAboutOurAim($text)
	{
		return \App\Setting::setAboutOurAim($text);
	}

	public static function getAboutWho()
	{
		return \App\Setting::getAboutWho();
	}

	public static function setAboutWho($text)
	{
		return \App\Setting::setAboutWho($text);
	}
}