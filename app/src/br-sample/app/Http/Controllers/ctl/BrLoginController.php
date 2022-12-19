<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Services\BrLoginService as Service;
use Illuminate\Http\Request;

class BrLoginController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('ctl.br.login.index', [
            'account_id'    => $request->input('account_id'),
            // 'keep'          => $request->input('keep'), // MEMO: 使われていない
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, Service $service)
    {
        $errorMessages = [];

        // モデルの取得
        $o_models_system = new models_System();

        //   account_id       アカウントID
        //   keep             true ログインを持続 false ログイン情報を持続しない
        $b_res = $o_models_system->login_staff($this->_request->getParam('account_id'), $this->_request->getParam('password'));

        // $b_res == true or false
        // ログイン失敗時ログイン画面へ
        if ($b_res == false) {

            $this->box->item->error->add("認証に失敗しました。ＩＤまたはパスワードをお確かめください。 ");

            // Member モデルを生成
            $o_models_system = new models_System();

            // 失敗時login情報削除
            $o_models_system->logout();

            return $this->_forward('index');
        }


        // ログイン成功時ホテルTOPへ
        return $this->_redirect($this->box->info->env->source_path . $this->box->info->env->module . '/brtop/');

        $this->set_assign();


        return 'login success';
    }
}
