<?php

namespace App\Libraries;

class Settings
{

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

}
