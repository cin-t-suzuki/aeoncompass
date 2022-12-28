<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\StaffAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BrChangePasswordController extends Controller
{
    /**
     * パスワード変更画面表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('ctl.br.change.password.index');
    }

    /**
     * パスワード変更処理
     *
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ChangePasswordRequest $request)
    {
        try {
            /** @var StaffAccount */
            $staffAccount = Auth::user();
            $staffAccount->password = bcrypt($request->input('password'));
            $staffAccount->save();
        } catch (\Exception $e) {
            Log::error($e);
            return back()->withErrors([
                'パスワードの更新に失敗いたしました。',
            ]);
        }
        return redirect()->route('ctl.br.top')->with([
            'guides' => ['パスワードの更新が完了いたしました。'],
        ]);
    }
}
