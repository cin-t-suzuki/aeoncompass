<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrLoginController extends Controller
{
    /**
     * ログイン画面を表示する
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('ctl.br.login.index', [
            'account_id' => $request->input('account_id', ''),
        ]);
    }

    /**
     * ログインを試行する
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only(
            'account_id',
            'password',
        );

        // MEMO: 移植元ではかなり長いあいだログインを保持している。
        $remember = true;

        if (!Auth::guard('staff')->attempt($credentials, $remember)) {
            return back()->withErrors([
                '認証に失敗しました。ＩＤまたはパスワードをお確かめください。',
            ])->onlyInput('account_id');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('ctl.br.top'));
    }

    /**
     * ログアウトを実行する
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();      // ユーザーのセッションを無効に
        $request->session()->regenerateToken(); // CSRFトークンを再生成
        return redirect()->route('ctl.br.top');
    }
}
