<?php

namespace App\Libraries;

class Settings
{
    /**
     * Get Organization Name
     * @return String
     */
    public static function getOrgName()
    {
        return \App\Setting::getOrgName();
    }

    /**
     * Set Organization Name
     * @param String $name
     */
    public static function setOrgName($name)
    {
        return \App\Setting::setOrgName($name);
    }

    /**
     * Get Organization Tagline
     * @return String
     */
    public static function getOrgTagline()
    {
        return \App\Setting::getOrgTagline();
    }

    /**
     * Set Organization Tagline
     * @param String $tagline
     */
    public static function setOrgTagline($tagline)
    {
        return \App\Setting::setOrgTagline($tagline);
    }

    /**
     * Get Organization Logo Path
     * @return String
     */
    public static function getOrgLogo()
    {
        return \App\Setting::getOrgLogo();
    }

    /**
     * Set Organization Logo Path
     * @param Image $logo
     */
    public static function setOrgLogo($logo)
    {
        return \App\Setting::setOrgLogo($logo);
    }

    /**
     * Get Organization Favicon Path
     * @return String
     */
    public static function getOrgFavicon()
    {
        return \App\Setting::getOrgFavicon();
    }
    
    /**
     * Set Organization Favicon
     * @param Image $favicon
     */
    public static function setOrgFavicon($favicon)
    {
        return \App\Setting::setOrgFavicon($favicon);
    }

    /**
     * Get Terms and Conditions
     * @return String
     */
    public static function getPurchaseTermsAndConditions()
    {
        return \App\Setting::getPurchaseTermsAndConditions();
    }

    /**
     * Set Terms and Conditions
     * @param String $text
     */
    public static function setPurchaseTermsAndConditions($text)
    {
        return \App\Setting::setPurchaseTermsAndConditions($text);
    }

    /**
     * Get Registration Terms and Conditions
     * @return String
     */
    public static function getRegistrationTermsAndConditions()
    {
        return \App\Setting::getRegistrationTermsAndConditions();
    }

    /**
     * Set Registration Terms and Conditions
     * @param String $text
     */
    public static function setRegistrationTermsAndConditions($text)
    {
        return \App\Setting::setRegistrationTermsAndConditions($text);
    }

    /**
     * Get Discord Link
     * @return String
     */
    public static function getDiscordLink()
    {
        return \App\Setting::getDiscordLink();
    }

    /**
     * Set Discord Link
     * @param String $text
     */
    public static function setDiscordLink($text)
    {
        return \App\Setting::setDiscordLink($text);
    }

    /**
     * Get Discord ID
     * @return String
     */
    public static function getDiscordId()
    {
        return \App\Setting::getDiscordId();
    }

    /**
     * Set Discord ID
     * @param String $text
     */
    public static function setDiscordId($text)
    {
        return \App\Setting::setDiscordId($text);
    }

    /**
     * Get Facebook Link
     * @return String
     */
    public static function getFacebookLink()
    {
        return \App\Setting::getFacebookLink();
    }

    /**
     * Set Facebook Link
     * @param String $text
     */
    public static function setFacebookLink($text)
    {
        return \App\Setting::setFacebookLink($text);
    }

    /**
     * Get Steam Link
     * @return String
     */
    public static function getSteamLink()
    {
        return \App\Setting::getSteamLink();
    }

    /**
     * Set Steam Link
     * @param String $text
     */
    public static function setSteamLink($text)
    {
        return \App\Setting::setSteamLink($text);
    }

    /**
     * Get Reddit Link
     * @return String
     */
    public static function getRedditLink()
    {
        return \App\Setting::getRedditLink();
    }

    /**
     * Set Reddit Link
     * @param String $text
     */
    public static function setRedditLink($text)
    {
        return \App\Setting::setRedditLink($text);
    }

    /**
     * Get Teamspeak Link
     * @return String
     */
    public static function getTeamspeakLink()
    {
        return \App\Setting::getTeamspeakLink();
    }

    /**
     * Set Teamspeak Link
     * @param String $text
     */
    public static function setTeamspeakLink($text)
    {
        return \App\Setting::setTeamspeakLink($text);
    }

    /**
     * Get Participant Count Offset
     * @return String
     */
    public static function getParticipantCountOffset()
    {
        return \App\Setting::getParticipantCountOffset();
    }

    /**
     * Set Participant Count Offset
     * @param Integer $number
     */
    public static function setParticipantCountOffset($number)
    {
        return \App\Setting::setParticipantCountOffset($number);
    }

    /**
     * Get Event Count Offset
     * @return String
     */
    public static function getEventCountOffset()
    {
        return \App\Setting::getEventCountOffset();
    }

    /**
     * Set Event Count Offset
     * @param Integer $number
     */
    public static function setEventCountOffset($number)
    {
        return \App\Setting::setEventCountOffset($number);
    }

    /**
     * Get Frontpage Alot Tagline
     * @return String
     */
    public static function getFrontpageAlotTagline()
    {
        return \App\Setting::getFrontpageAlotTagline();
    }

    /**
     * Set Frontpage Alot Tagline
     * @param String $text
     */
    public static function setFrontpageAlotTagline($text)
    {
        return \App\Setting::setFrontpageAlotTagline($text);
    }

    /**
     * Get Currency
     * @return String
     */
    public static function getCurrency()
    {
        return \App\Setting::getCurrency();
    }

    /**
     * Get Currency Symbol
     * @return String
     */
    public static function getCurrencySymbol()
    {
        return \App\Setting::getCurrencySymbol();
    }

    /**
     * Set Currency
     * @param String $currency
     */
    public static function setCurrency($currency)
    {
        return \App\Setting::setCurrency($currency);
    }

    /**
     * Get About Main Text
     * @return String
     */
    public static function getAboutMain()
    {
        return \App\Setting::getAboutMain();
    }

    /**
     * Set About Main Text
     * @param String $text
     */
    public static function setAboutMain($text)
    {
        return \App\Setting::setAboutMain($text);
    }

    /**
     * Get About Short Text
     * @return String
     */
    public static function getAboutShort()
    {
        return \App\Setting::getAboutShort();
    }

    /**
     * Set About Short Text
     * @param String $text
     */
    public static function setAboutShort($text)
    {
        return \App\Setting::setAboutShort($text);
    }

    /**
     * Get About Our Aim Text
     * @return String
     */
    public static function getAboutOurAim()
    {
        return \App\Setting::getAboutOurAim();
    }

    /**
     * Set About Our Aim Text
     * @param String $text
     */
    public static function setAboutOurAim($text)
    {
        return \App\Setting::setAboutOurAim($text);
    }

    /**
     * Get About Who's Who Text
     * @return String
     */
    public static function getAboutWho()
    {
        return \App\Setting::getAboutWho();
    }

    /**
     * Set About Who's Who Text
     * @param String $text
     */
    public static function setAboutWho($text)
    {
        return \App\Setting::setAboutWho($text);
    }

    /**
     * Get Facebook Page Access Tokens
     * @return Array
     */
    public static function getSocialFacebookPageAccessTokens()
    {
        return \App\Setting::getSocialFacebookPageAccessTokens();
    }

    /**
     * Set Facebook Page Access Tokens
     * @param Array $facebook_access_tokens
     */
    public static function setSocialFacebookPageAccessTokens($facebook_access_tokens)
    {
        return \App\Setting::setSocialFacebookPageAccessTokens($facebook_access_tokens);
    }

    /**
     * Get Active Payment Gateways
     * @return Array
     */
    public static function getPaymentGateways()
    {
        return \App\Setting::getPaymentGateways();
    }

    /**
     * Get Supported Payment Gateways
     * @return Array
     */
    public static function getSupportedPaymentGateways()
    {
        return \App\Setting::getSupportedPaymentGateways();
    }

    /**
     * Enable Payment Gateway
     * @return Boolean
     */
    public static function enablePaymentGateway($gateway)
    {
        return \App\Setting::enablePaymentGateway($gateway);
    }

    /**
     * Disable Payment Gateway
     * @return Boolean
     */
    public static function disablePaymentGateway($gateway)
    {
        return \App\Setting::disablePaymentGateway($gateway);
    }

    /**
     * Get Payment Gateway Display Name
     * @return String
     */
    public static function getPaymentGatewayDisplayName($gateway)
    {
        return \App\Setting::getPaymentGatewayDisplayName($gateway);
    }

    /**
     * Get Payment Gateway Note
     * @return String
     */
    public static function getPaymentGatewayNote($gateway)
    {
        return \App\Setting::getPaymentGatewayNote($gateway);
    }

    /**
     * Get Active Login Methods
     * @return Array
     */
    public static function getLoginMethods()
    {
        return \App\Setting::getLoginMethods();
    }

    /**
     * Get Supported Login Methods
     * @return Array
     */
    public static function getSupportedLoginMethods()
    {
        return \App\Setting::getSupportedLoginMethods();
    }

    /**
     * Enable Login Method
     * @return Boolean
     */
    public static function enableLoginMethod($method)
    {
        return \App\Setting::enableLoginMethod($method);
    }

    /**
     * Disable Login Method
     * @return Boolean
     */
    public static function disableLoginMethod($method)
    {
        return \App\Setting::disableLoginMethod($method);
    }

    /**
     * Is the App Installed
     * @return Boolean
     */
    public static function isInstalled()
    {
        return \App\Setting::isInstalled();
    }

    /**
     * Set the App as Installed
     * @return Boolean
     */
    public static function setInstalled()
    {
        return \App\Setting::setInstalled();
    }

    /**
     * Get SEO Keywords
     * @return Integer $amount
     */
    public static function getSeoKeywords()
    {
        return \App\Setting::getSeoKeywords();
    }

    /**
     * Set SEO Keywords
     * @param String $text
     * @return Boolean
     */
    public static function setSeoKeywords($text)
    {
        return \App\Setting::setSeoKeywords($text);
    }
}
