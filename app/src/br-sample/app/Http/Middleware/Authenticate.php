<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $guards = [];

    /**
     * Handle an incoming request.
     *
     * ガードを退避して未認証時のリダイレクトを分岐するためにオーバーライド
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // guardを変数に退避
        $this->guards = $guards;
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // ガード名から、各ログイン画面にリダイレクトする
            if (in_array('staff', $this->guards, true)) {
                return route('ctl.br.login.index');
            }
            // TODO: ↑ 各ロールについて、未認証時に遷移先とするログイン画面を追加

            // 上記以外の場合は、ログインが不要なページに移動 TODO: 遷移先は仮
            // return route('user.top');
            return view('user top (cf. app/Http/Middleware/Authenticate.php');
        }
    }
}
