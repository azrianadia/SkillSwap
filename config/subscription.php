<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Define your subscription plans here. Each plan has a name, price,
    | swap limit per month, and features.
    |
    */
    'plans' => [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'price_id' => null,
            'swap_limit' => 5,
            'features' => [
                'Maksimal 5 swap per bulan',
                'Akses chat dasar',
                'Profil publik',
                'Review & rating',
            ],
            'popular' => false,
        ],
        'pro' => [
            'name' => 'Pro',
            'price' => 25000,
            'price_id' => env('MIDTRANS_PRO_PRICE_ID'),
            'swap_limit' => -1,
            'features' => [
                'Swap tak terbatas (Unlimited)',
                'Badge Pro di profil',
                'Prioritas di pencarian',
                'Analytics swap',
                'Dukungan prioritas',
            ],
            'popular' => true,
        ],
    ],

    'default_plan' => 'free',
    'currency' => 'IDR',
];