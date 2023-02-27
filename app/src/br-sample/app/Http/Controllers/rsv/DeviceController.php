<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * デバイスごとのサイトへリダイレクト
     *
     * /device/[サイト][リダイレクト先URL]
     *
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $o_cookie = new models_Cookie($this->request);
        $o_cookie->set_domain($this->box->config->system->cookie->domain);

        if (!is_empty($this->cookies('FFS'))) {
            $o_cookie->revoke_cookie('FFS', '/');
        }

        if (substr($this->params('url'), 0, 8) != 'branches') {
            $s_path_base = $this->box->info->env->path_base;
        }

        if (substr($this->params('url'), -1, 1) == '?') {
            $s_url = $s_path_base . '/' . substr($this->params('url'), 0, strlen($this->params('url')) - 1);
        } else {
            $s_url = $s_path_base . '/' . $this->params('url');
        }

        // 表示サイトがない場合はリダイレクト
        if (is_empty($this->params('site')) or !$o_cookie->is_sp()) {
            return $this->_redirect($s_url);
        }

        // クッキーを出力
        $o_cookie->issue_cookie($this->box->config->system->cookie->fcs, $this->params('site'), null, $this->box->config->system->cookie->path);

        return $this->_redirect($s_url);
    }
}
