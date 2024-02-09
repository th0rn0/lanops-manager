<?php

namespace App\Libraries;

class Settings
{

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
