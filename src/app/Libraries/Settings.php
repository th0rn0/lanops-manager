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
     * Set Org Name
     * @param String $name
     */
    public static function setOrgName($name)
    {
        return \App\Setting::setOrgName($name);
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
     * Get Lan Count Offset
     * @return String
     */
    public static function getLanCountOffset()
    {
        return \App\Setting::getLanCountOffset();
    }

    /**
     * Set Lan Count Offset
     * @param Integer $number
     */
    public static function setLanCountOffset($number)
    {
        return \App\Setting::setLanCountOffset($number);
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
     * Is Credit System Enabled
     * @return Boolean
     */
    public static function isCreditEnabled()
    {
        return \App\Setting::isCreditEnabled();
    }

    /**
     * Enable Credit System
     * @return Boolean
     */
    public static function enableCreditSystem()
    {
        return \App\Setting::enableCreditSystem();
    }

    /**
     * Disable Credit System
     * @return Boolean
     */
    public static function disableCreditSystem()
    {
        return \App\Setting::disableCreditSystem();
    }
}
