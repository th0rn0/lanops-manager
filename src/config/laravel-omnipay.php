<?php

return [

    // The default gateway to use
    'default' => 'paypal',

    // Add in each gateway here
    'gateways' => [
        'paypal_express' => [
            'driver'  => 'PayPal_Express',
            'options' => [
                'solutionType'      => '',
                'landingPage'       => '',
                'headerImageUrl'    => '',
                'displayName'       => 'Paypal',
                'note'              => 'You will be redirected offsite for this payment.',
            ],
            'credentials' => [
                'username' => '',
                'password' => '',
                'signature' => ''
            ]
        ],
        'stripe' => [
            'driver'  => 'stripe',
            'options' => [
                'displayName'   => 'Debit/Credit Card',
                'note'          => 'You may be redirected offsite for this payment.',
            ],
            'credentials' => [
                'public' => env('STRIPE_PUBLIC_KEY'),
                'secret' => env('STRIPE_SECRET_KEY')
            ]
        ]
    ]

];
