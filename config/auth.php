<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'hotel' => [
            'driver' => 'session',
            'provider' => 'hotels',
        ],

        'partner' => [
            'driver' => 'session',
            'provider' => 'partners',
        ],

        'dispatcher' => [
            'driver' => 'session',
            'provider' => 'dispatchers',
        ],

        'provider' => [
            'driver' => 'session',
            'provider' => 'providers',
        ],

        'account' => [
            'driver' => 'session',
            'provider' => 'accounts',
        ],

        'corporate' => [
            'driver' => 'session',
            'provider' => 'corporates',
        ],

        'customercare' => [
            'driver' => 'session',
            'provider' => 'customercares',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],

        'providerapi' => [
            'driver' => 'jwt',
            'provider' => 'providers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'hotels' => [
            'driver' => 'eloquent',
            'model' => App\Models\Hotel::class,
        ],

        'partners' => [
            'driver' => 'eloquent',
            'model' => App\Models\Partner::class,
        ],

        'dispatchers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Dispatcher::class,
        ],

        'providers' => [
           'driver' => 'eloquent',
            'model' => App\Models\Provider::class,
        ],

        'accounts' => [
            'driver' => 'eloquent',
            'model' => App\Models\Account::class,
        ],

        'corporates' => [
            'driver' => 'eloquent',
            'model' => App\Models\Corporate::class,
        ],

        'customercares' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customercare::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'hotels' => [
            'provider' => 'hotels',
            'table' => 'hotel_password_resets',
            'expire' => 60,
        ],

        'partners' => [
            'provider' => 'partners',
            'table' => 'partner_password_resets',
            'expire' => 60,
        ],

        'dispatchers' => [
            'provider' => 'dispatchers',
            'table' => 'dispatcher_password_resets',
            'expire' => 60,
        ],

        'providers' => [
            'provider' => 'providers',
            'table' => 'provider_password_resets',
            'expire' => 60,
        ],

        'accounts' => [
            'provider' => 'accounts',
            'table' => 'account_password_resets',
            'expire' => 60,
        ],

        'corporates' => [
            'provider' => 'corporates',
            'table' => 'corporate_password_resets',
            'expire' => 60,
        ],

        'customercares' => [
            'provider' => 'customercares',
            'table' => 'customercare_password_resets',
            'expire' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'admin_password_resets',
            'expire' => 60,
        ],

        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
