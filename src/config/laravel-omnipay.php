<?php

return [

    // The default gateway to use
    'default' => 'paypal',

    // Add in each gateway here
    'gateways' => [
        'paypal' => [
            'driver'  => 'PayPal_Express',
            'options' => [
                'solutionType'   => '',
                'landingPage'    => '',
                'headerImageUrl' => '',
            ],
            'credentials' => [
                'username' => env('PAYPAL_USERNAME'),
                'password' => env('PAYPAL_PASSWORD'),
                'signature' => env('PAYPAL_SIGNATURE')
            ]
        ],
        'stripe' => [
            'driver'  => 'PayPal_Express',
            'credentials' => [
                'apikey' => env('STRIPE_API_KEY')
            ]
        ]
    ]

];
