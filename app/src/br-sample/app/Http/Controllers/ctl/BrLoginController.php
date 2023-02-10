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
     * 社内管理スタッフ登録画面
     *
     * TODO: to be deleted
     *
     * @return void
     */
    public function create()
    {
        return view('ctl.br.login.create');
    }
    /**
     * 社内管理スタッフ登録処理
     *
     * 移植元には存在していない（？）
     * TODO: to be deleted
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $request->validate([
            'account_id' => 'required|string|unique:staff_account',
            'password' => 'required|string',
        ]);

        $user = new \App\Models\StaffAccount();
        $user->account_id = $request->account_id;

        // $user->password = bcrypt($request->password);
        $user->password = (new \App\Util\Models_Cipher(config('settings.cipher_key')))->encrypt($request->password);

        $user->accept_status = \App\Models\StaffAccount::ACCEPT_STATUS_OK;
        $user->save();

        return redirect()->route('ctl.br.top');
    }

    /**
     * ログアウトを実行する
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();      // ユーザーのセッションを無効に
        $request->session()->regenerateToken(); // CSRFトークンを再生成
        return redirect()->route('ctl.br.top');
    }
}
