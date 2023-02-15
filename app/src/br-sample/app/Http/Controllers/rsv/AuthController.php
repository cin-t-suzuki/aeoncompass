<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        var_dump($request->input('next_url'));
        if ($request->has('next_url')) {
            // TODO:
            // $request->session()->put('route.intended', $request->input('next_url'));
        }
        return view('rsv.auth.login', [
            'banner'    => $request->input('banner'),
            'type'      => $request->input('type'),
            'reconfirm' => $request->input('reconfirm'),
            'button_nm' => $request->input('button_nm'),

            // TODO: 暫定
            'next_url' => '',
        ]);
    }

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
        $nextRoute = $request->session()->pull('route.intended', 'rsv.top');
        return redirect()->intended(route($nextRoute));
    }

    /**
     * ログアウトを実行する
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();      // ユーザーのセッションを無効に
        $request->session()->regenerateToken(); // CSRFトークンを再生成
        return redirect()->route('rsv.top');
    }
}
