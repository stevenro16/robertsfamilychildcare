<?php

use App\Models\Employee;
use App\Models\ParentUser;

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'employees',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'employees',
        ],
        'parent' => [
            'driver'   => 'session',
            'provider' => 'parents',
        ],
    ],

    'providers' => [
        'employees' => [
            'driver' => 'eloquent',
            'model'  => Employee::class,
        ],
        'parents' => [
            'driver' => 'eloquent',
            'model'  => ParentUser::class,
        ],
    ],

    'passwords' => [
        'employees' => [
            'provider' => 'employees',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
