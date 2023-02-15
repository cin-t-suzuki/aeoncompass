<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * ログイン画面表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('next_url')) {
            // ログイン認証後に遷移する url を指定
            $request->session()->put('url.intended', $request->input('next_url'));
        }

        return view('rsv.auth.login');
    }

    /**
     * ログイン認証
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only(
            'email',
            'password',
        );

        $remember = false;

        if (!Auth::guard('web')->attempt($credentials, $remember)) {
            return back()->withErrors([
                '認証に失敗しました。ＩＤまたはパスワードをお確かめください。',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('rsv.top'));
    }

    /**
     * ログアウトを実行する
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();      // ユーザーのセッションを無効に
        $request->session()->regenerateToken(); // CSRFトークンを再生成
        return redirect()->route('rsv.top');
    }
}
