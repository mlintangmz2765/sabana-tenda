<?php

return [
    'daily_penalty' => (int) env('SABANA_DAILY_PENALTY', 25000),
    'max_rental_days' => (int) env('SABANA_MAX_RENTAL_DAYS', 14),
    'max_login_attempts' => (int) env('SABANA_MAX_LOGIN_ATTEMPTS', 5),
    'lockout_minutes' => (int) env('SABANA_LOCKOUT_MINUTES', 15),
    'business' => [
        'name' => 'SABANA Tenda',
        'tagline' => 'Sewa Alat Camping Lengkap & Berkualitas',
        'address' => 'Jl. Sabana No. 1, Yogyakarta',
        'phone' => '0812-3456-7890',
        'whatsapp' => 'wa.me/6281234567890',
        'email' => 'hello@sabanatenda.id',
    ],
];
