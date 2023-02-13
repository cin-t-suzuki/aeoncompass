{{-- MEMO: 移植元 public\app\rsv\view2\_common\_pgh1.tpl --}}
{{--
--------------------------------------------------------------------------------
パラメータ
pgh1_mnv : メインナビゲーション
1 = 初めての方、ＢＲポイント、予約・キャンセル、ヘルプ
2 = ヘルプ
3 = ヘルプ
--------------------------------------------------------------------------------
--}}
@php
    $pgh1_mnv  = 1;
    $isLogin = false;
    $isFree = true;
    $svc_rsv = false;
    $env = [
        'x_uri' => '',
        'controller' => '',
        'port_https' => '',
        'in_security' => '',
        'path_base' => '',
        'path_img' => '',
        'ssl_path' => '',
        'root_path' => '',
    ];
@endphp
@if ($isLogin && !$isFree)
@else
    @php
        $next_url = '';
        if (!is_null($next_url)) {
            // ↑ もとは is empty 
            $next_url .= $next_url; 
        } elseif (is_null($env['x_uri'])) { 
            // ↑ もとは is empty 
        } elseif ($env['x_uri'] =='/auth/login/') {
        } elseif (strpos($env['x_uri'], '/rsv/free/') === 0) {
        } elseif (strpos($env['x_uri'], '/,/') === 0) {
        } elseif ($env['controller'] == 'reserve') {
            $next_url .= $env['port_https'];
            $next_url .= $env['path_base'];
            $next_url .= '/reserve/';
        } else {
            $next_url .= 'http';
        
            if ($env['in_security']) {
                $next_url .= 's';
            }
        
            $next_url .= '://';
            $next_url .= $v->config->system->host_name;
            $next_url .= $env['x_uri'];
        
            if (!is_null($v->helper->form->to_query_correct('', false))) {
                // ↑ もとは is empty
                $next_url .= '?';
                $next_url .= $v->helper->form->to_query_correct('', false);
            }
        }
    @endphp
@endif
<div id="pgh1">
    <div class="pg">
        <div class="pgh1-logo">
            <h1 class="logo">
                @if ($env['controller'] != 'top')
                    <a href="{{ $env['path_base'] }}/">
                @endif
                <img src="{{ $env['path_img'] }}/pg/pgh-logo2['gif']" title="旅館・ホテル予約のベストリザーブ・宿ぷらざ" alt="旅館・ホテル予約のベストリザーブ・宿ぷらざ" width="170" height="70" />
                @if ($env['controller'] != 'top')
                    </a>
                @endif
            </h1>
        </div>
        <div class="pgh1-catch">
            <h2 class="catch">レジャー・ビジネスに！国内の宿泊予約サイト。旅館・ホテルの予約はベストリザーブ・宿ぷらざで。</h2>
            <div class="today">
            </div>
        </div>

        <div class="pgh1-usr pgh1-usr-guest" style="display:none;">
            <div class="pgh1-usr2 guest">
                <div class="pgh1-usr3">
                    <div class="button">
                        {{-- MEMO: ↓ もとは is_empty() --}}
                        <a class="btnimg btn-active" href="{{ $env['port_https'] }}{{ $env['path_base'] }}/auth/login/{{ !is_null($next_url) ? '?next_url=' . strip_tags($next_url, 'url') : '' }}">
                            <img src="/img/usr/usr-login['gif']" title="ログイン" alt="ログイン" width="119" height="32" />
                        </a>
                    </div>
                    <div class="welcome">ようこそ</div>
                    <div class="user">会員登録は<a href="{{ $env['ssl_path'] }}/ctl/subscribe/new/" title="会員登録は無料です！" style="color:#9cf;">こちら</a>から</div>
                </div>
            </div>
        </div>

        <div class="pgh1-usr pgh1-usr-member" style="display:none;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="pgh1-usr2 member">
                            <a class="user-link" href="{{ $env['ssl_path'] }}point/history/" title="ポイントの発行状況はこちら" style="display: block;">
                                <div class="pgh1-usr3">
                                    <span class="sankaku">
                                    </span>
                                    <span class="welcome">ようこそ</span>
                                    <span class="user username">
                                    </span>
                                </div>
                                <div class="clearfix">
                                    <span class="hoyu-point">保有ポイント</span>
                                    <span class="valid_point hoyu-point">
                                    </span>
                                </div>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <div>
                        <a class="" href="{{ $env['ssl_path'] }}rsv/auth/logout/">ログアウト</a>
                    </div>
                </tr>
            </table>
        </div>
        <div class="pgh1-mnv">

            @if ($pgh1_mnv == 1)
                <div class="mnv">
                    @if (strpos($env['x_uri'], $env['path_base'] . '/guide/visitor/') !== 0)
                        <a class="" href="{{ $env['path_base'] }}/guide/visitor/">
                            <img src="{{ $env['root_path'] }}img/mnv/mnv-first2['gif']" alt="初めての方へ" width="102" height="33" />
                        </a>
                    @else
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-first2_disable['gif']" alt="初めての方へ" width="102" height="33" />
                    @endif
                </div>
                <div class="mnv">
                    @if (strpos($env['x_uri'], $env['path_base'] . '/point/') !== 0)
                        <a class="" href="{{ $env['path_base'] }}/point/">
                            <img src="{{ $env['root_path'] }}img/mnv/mnv-point2['gif']" alt="ＢＲポイント" width="102" height="33" />
                        </a>
                    @else
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-point2_disable['gif']" alt="ＢＲポイント" width="102" height="33" />
                    @endif
                </div>
                <div class="mnv">
                    @if ($env['x_uri'] != $env['path_base'] . '/reserve/')
                        <a class="" href="{{ $env['port_https'] }}{{ $env['path_base'] }}/reserve/">
                            <img src="{{ $env['root_path'] }}img/mnv/mnv-rsv2['gif']" alt="予約の確認・キャンセル・日程短縮" width="182" height="33" />
                        </a>
                    @else
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-rsv2_disable['gif']" alt="予約の確認・キャンセル・日程短縮" width="182" height="33" />
                    @endif
                </div>
                <div class="mnv">
                    <a class="" href="{{ $env['path_base'] }}/help/">
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-help2['gif']" alt="ヘルプ" width="92" height="33" />
                    </a>
                </div>
            @elseif ($pgh1_mnv == 2)
                <div class="mnv">
                    <a class="" href="{{ $env['path_base'] }}/help/">
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-help2['gif']" alt="ヘルプ" width="92" height="33" />
                    </a>
                </div>
            @elseif ($pgh1_mnv == 3)
                <div class="mnv">
                    @if (strpos($env['x_uri'], $env['path_base'] . '/guide/coupon/') !== 0)
                        <a class="" href="{{ $env['path_base'] }}/guide/coupon/">
                            <img src="{{ $env['root_path'] }}img/mnv/mnv-first2['gif']" alt="初めての方へ" width="112" height="40" />
                        </a>
                    @else
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-first2_disable['gif']" alt="初めての方へ" width="112" height="40" />
                    @endif
                </div>
                <div class="mnv">
                    @if (strpos($env['x_uri'], $env['path_base'] . '/point/') !== 0)
                        <a class="" href="{{ $env['path_base'] }}/point/">
                            <img src="{{ $env['root_path'] }}img/mnv/mnv-point2['gif']" alt="ＢＲポイント" width="112" height="40" />
                        </a>
                    @else
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-point2_disable['gif']" alt="ＢＲポイント" width="112" height="40" />
                    @endif
                </div>
                <div class="mnv">
                    @if (strpos($env['x_uri'], $env['path_base'] . '/reserve/') !== 0)
                        <a class="" href="{{ $env['port_https'] }}{{ $env['path_base'] }}/reserve/">
                            <img src="{{ $env['root_path'] }}img/mnv/mnv-rsv2['gif']" alt="予約の確認・キャンセル・日程短縮" width="112" height="40" />
                        </a>
                    @else
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-rsv2_disable['gif']" alt="予約の確認・キャンセル・日程短縮" width="112" height="40" />
                    @endif
                </div>
                <div class="mnv">
                    <a class="" href="{{ $env['path_base'] }}/help/">
                        <img src="{{ $env['root_path'] }}img/mnv/mnv-help2['gif']" alt="ヘルプ" width="92" height="33" />
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- gnav 追加 -->
<div id="glovalnavi">
    <ul>
        <li>
            <a class="@if ($svc_rsv != false) 'btnimg' @endif " href="@if ($svc_rsv != false) {{ $env['path_base'] }} . '/' @endif">
                <img src="/img/mnv/gnv-tab-rsv @if ($svc_rsv == false) '_over' @endif ['gif']" alt="国内宿泊" width="115" height="30" />
            </a>
        </li>
    </ul>
    <hr class="glovalnavi-line" />
</div>
