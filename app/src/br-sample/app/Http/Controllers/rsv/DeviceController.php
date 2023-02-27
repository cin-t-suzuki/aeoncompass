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
     *      ex. http://xxx.com/rsv/device/?site=$1&url=$2?%{QUERY_STRING}
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = $request->input('site', 'pc');
        $url = $request->input('url', '');

        /*
        |--------------------------------------------------------------------------
        | MEMO: 移植元の Apache の conf の設定
        |--------------------------------------------------------------------------
        |   # スマートフォン以外はどちらでも自由に表示できる。
        |   # スマートフォンの場合は基本はスマートフォン用サイトが表示されるが
        |   # クッキーに pc があった場合には デスクトップ用サイトが表示される
        |   SetEnvIfNoCase User-Agent (iPhone|Android.*Mobile|Windows.*Phone) sp=yes
        |   SetEnvIf Cookie [Ff][Cc][Ss]=pc pc=yes
        |   SetEnvIf Cookie [Ff][Cc][Ss]=sp sp=yes
        |   SetEnvIf pc "yes" sp=no
        |
        */
        $user_agent =  $request->header('User-Agent');
        $userAgentSmartPhone = preg_match('/(iPhone|Android.*Mobile|Windows.*Phone)/', $user_agent);
        $fcs = $request->cookie(config('settings.system.cookie.fcs'));
        $isSmartPhone = $userAgentSmartPhone && $fcs != 'pc' || $fcs == 'sp';

        if (substr($url, -1, 1) == '?') {
            $s_url = '/' . substr($url, 0, strlen($url) - 1);
        } else {
            $s_url = '/' . $url;
        }

        if ($site === 'sp' && $isSmartPhone) {
            // スマートフォンとして判定し、クッキーを出力
            return redirect($s_url)->withCookie(
                config('settings.system.cookie.fcs'),
                $site,
                time() + 10 * 365 * 24 * 60 * 60, // MEMO: Cookie の有効期限を約10年後として設定
                config('settings.system.cookie.path')
            );
        }
        return redirect('rsv/auth');
    }
}
