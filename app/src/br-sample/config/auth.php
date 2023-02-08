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
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],

        'supervisor' => [
            'driver' => 'session',
            'provider' => 'supervisor',
        ],
        'hotel' => [
            'driver' => 'session',
            'provider' => 'hotel',
        ],
        'partner' => [
            'driver' => 'session',
            'provider' => 'partner',
        ],
        'affiliate' => [
            'driver' => 'session',
            'provider' => 'affiliate',
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
    | MEMO: パスワード を Laravel 標準のハッシュロジックでハッシュ化するのではなく、
    |     旧ソースの暗号ロジックで暗号化する場合は、 'driver' に 'custom' を指定。
    |     これは、 app/Providers/AuthServiceProvider.php で定義されている。
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],

        'staff' => [
            'driver' => 'custom', // ハッシュロジックを旧ソースの暗号化ロジックに差し替え
            'model' => App\Models\StaffAccount::class,
        ],
        'supervisor' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // TODO: 各ログイン機能実装時、対応するモデルに書き換える
        ],
        'hotel' => [
            'driver' => 'custom',
            'model' => App\Models\HotelAccount::class,
        ],
        'partner' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // TODO: 各ログイン機能実装時、対応するモデルに書き換える
        ],
        'affiliate' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // TODO: 各ログイン機能実装時、対応するモデルに書き換える
        ],
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
    | The expire time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        // TODO: 詳細要確認。
        'staff' => [
            'provider' => 'staff',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
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

    'password_timeout' => 10800, // 3 hours

];
