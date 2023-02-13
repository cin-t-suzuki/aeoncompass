<?php

namespace App\Providers;

use App\Hashing\BlowfishCipherHasher;
use App\Hashing\CustomHashManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        // 'custom' ドライバーを登録（ハッシュロジックを旧ソースの暗号化ロジックに差し替え）
        Auth::provider('custom', function (Application $app, array $config) {

            // デフォルトの $app['hash'] の代わりに、自前の HashManager を準備
            // この HashManager のデフォルトの Hasher 指定は 'blowfish_cipher' になっている。
            //（config を使用する場合はデフォルトの HashManager でOKな部分。）
            $customHashManager = new CustomHashManager($app);

            // extend() を使用して、'blowfish_cipher' というキーで自前の Hasher を登録
            $customHashManager->extend('blowfish_cipher', function ($app) {
                return new BlowfishCipherHasher();
            });

            // コンストラクタで HashManager を渡す。
            // （EloquentUserProvider クラスを差し替えたい場合はこのタイミングで変えられます）
            return new EloquentUserProvider($customHashManager, $config['model']);
        });
    }
}
