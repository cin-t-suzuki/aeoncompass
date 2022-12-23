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
    | このオプションは、アプリケーションのデフォルトの認証「ガード (guard)」と
    | パスワードのリセットオプションを制御します。これらのデフォルトは
    | 必要に応じて変更できますが、ほとんどのアプリケーションにとって最適な出発点です。
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
    | 次に、アプリケーションのすべての認証ガード (guard) を定義できます。 もちろん、
    | ここでは、セッションストレージと Eloquent ユーザープロバイダーを使用する優れた
    | デフォルト構成が定義されています。
    |
    | すべての認証ドライバーには、ユーザープロバイダー (provider) があります。 これは、
    | ユーザーのデータを永続化するためにこのアプリケーションが使用するデータベース
    | またはその他のストレージメカニズムからユーザーを実際に取得する方法を定義します。
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
    | すべての認証ドライバーには、ユーザープロバイダー (provider) があります。これは、
    | ユーザーのデータを永続化するためにこのアプリケーションが使用するデータベース
    | またはその他のストレージメカニズムからユーザーを実際に取得する方法を定義します。
    |
    | 複数のユーザー テーブルまたはモデルがある場合は、各モデル/テーブルを表す
    | 複数のソースを構成できます。これらのソースは、定義した追加の認証ガードに
    | 割り当てることができます。
    |
    | Supported: "database", "eloquent"
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
            'driver' => 'eloquent',
            'model' => App\Models\StaffAccount::class,
        ],
        'supervisor' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // TODO: 各ログイン機能実装時、対応するモデルに書き換える
        ],
        'hotel' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // TODO: 各ログイン機能実装時、対応するモデルに書き換える
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
    | アプリケーションに複数のユーザー テーブルまたはモデルがあり、特定のユーザータイプに
    | 基づいて個別のパスワードリセット設定が必要な場合は、複数のパスワードリセット構成を
    | 指定できます。
    |
    | 有効期限は、各リセットトークンが有効と見なされる分数です。このセキュリティ機能により、
    | トークンの有効期間が短くなるため、推測される時間が少なくなります。 これは必要に応じて
    | 変更できます。
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
    | ここでは、パスワードの確認がタイムアウトし、ユーザーが確認画面でパスワードの
    | 再入力を求められるまでの秒数を定義できます。デフォルトでは、タイムアウトは
    | 3 時間続きます。
    |
    */

    'password_timeout' => 10800, // 3 hours

];
