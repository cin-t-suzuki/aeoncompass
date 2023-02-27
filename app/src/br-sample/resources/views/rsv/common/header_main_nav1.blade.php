{{--
--------------------------------------------------------------------------------
 パラメータ
   pgh1_mnv : メインナビゲーション
                   1 = 初めての方、ＢＲポイント、予約・キャンセル、ヘルプ
                   2 = ヘルプ
                   3 = ヘルプ
--------------------------------------------------------------------------------
--}}

{{-- TODO: 大変そうだから後回し (by suzuki san)
{if $v->user->member->is_login() and !$v->user->member->is_free()}
{else}
{ capture name=next_url}{
      if !is_empty($v->assign->next_url)}{$v->assign->next_url}{
  elseif is_empty($v->env.x_uri)}{
  elseif $v->env.x_uri =='/auth/login/'}{
  elseif strpos($v->env.x_uri, '/rsv/free/') === 0}{
  elseif strpos($v->env.x_uri, '/,/') === 0}{
  elseif $v->env.controller == 'reserve'}{$v->env.port_https}{$v->env.path_base}/reserve/{
    else}http{if $v->env.in_security}s{/if}://{$v->config->system->host_name}{$v->env.x_uri}{
      if (!is_empty($v->helper->form->to_query_correct('', false)))}?{$v->helper->form->to_query_correct('', false)}{
      /if}{
    /if}{
  /capture}
{/if}
--}}

<div id="pgh1">
    <div class="pg">
        <div class="pgh1-logo">
            <h1 class="logo">
                @if (\Route::currentRouteName()=='rsv.top')<a href="{{-- $v->env.path_base --}}/">@endif
                <img src="{{ asset('/img/pg/pgh-logo2.gif') }}" width="170" height="70" alt="旅館・ホテル予約のイオンコンパスホテル" title="旅館・ホテル予約のイオンコンパスホテル" />
                @if (\Route::currentRouteName()=='rsv.top')</a>@endif
            </h1>
        </div>
        <div class="pgh1-catch">
            <h2 class="catch">レジャー・ビジネスに！国内の宿泊予約サイト。旅館・ホテルの予約はイオンコンパスホテルで。</h2>
            <div class="today"></div>
        </div>
        <div class="pgh1-usr pgh1-usr-guest" style="display:none;">
            <div class="pgh1-usr2 guest">
                <div class="pgh1-usr3">
                    <div class="button">
                        <a class="btnimg btn-active" href="{{-- {$v->env.port_https}{$v->env.path_base}/auth/login/{if !is_empty($smarty.capture.next_url)}?next_url={$smarty.capture.next_url|escape:'url'}{/if} --}}">
                            <img src="{{ asset('/img/usr/usr-login.gif') }}" width="119" height="32" alt="ログイン" title="ログイン" />
                        </a>
                    </div>
                    <div class="welcome">ようこそ</div>
                    <div class="user">会員登録は<a style="color:#9cf;" href="{{-- {$v->env.ssl_path}{$v->env.module}/subscribe/new/ --}}" title="会員登録は無料です！">こちら</a>から</div>
                </div>
            </div>
        </div>

        <div class="pgh1-usr pgh1-usr-member" style="display:none;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="pgh1-usr2 member">
                            <a class="user-link" href="{{ route('rsv.point.history') }}" title ="ポイントの発行状況はこちら" style = "display: block;">
                                <div class="pgh1-usr3">
                                    <span class="sankaku"></span>
                                    <span class="welcome">ようこそ</span>
                                    <span class="user username" ></span>
                                </div>
                                <div class="clearfix">
                                    <span class="hoyu-point">保有ポイント</span>
                                    <span class="valid_point hoyu-point"></span>
                                </div>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <div>
                        <a class="" href="{{-- {$v->env.ssl_path}rsv/auth/logout/ --}}">ログアウト</a>
                    </div>
                </tr>
            </table>
        </div>
        <div class="pgh1-mnv">
            @if($pgh1_mnv == 1)
                <div class="mnv">
                @if(false)
                {{-- if (strpos($v->env.x_uri, $v->env.path_base|cat:'/guide/visitor/') !== 0) --}}
                    <a class="" href="{{-- {$v->env.path_base}/guide/visitor/ --}}">
                        <img src="{{ asset('/img/mnv/mnv-first2.gif') }}" width="102" height="33" alt="初めての方へ" />
                    </a>
                @else
                    <img src="{{ asset('/img/mnv/mnv-first2_disable.gif') }}" width="102" height="33" alt="初めての方へ" />
                @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.point.index')
                        <a class="" href="{{ route('rsv.point.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-point2.gif') }}" width="102" height="33" alt="ＢＲポイント" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-point2_disable.gif') }}" width="102" height="33" alt="ＢＲポイント" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.reserve.index')
                        <a class="" href="{{ route('rsv.reserve.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-rsv2.gif') }}" width="182" height="33" alt="予約の確認・キャンセル・日程短縮" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-rsv2_disable.gif') }}" width="182" height="33" alt="予約の確認・キャンセル・日程短縮" />
                    @endif
                </div>
                <div class="mnv">
                    <a class="" href="{{ route('rsv.help.index') }}">
                        <img src="{{ asset('/img/mnv/mnv-help2.gif') }}" width="92" height="33" alt="ヘルプ" />
                    </a>
                </div>
            @elseif ($pgh1_mnv == 2)
                <div class="mnv">
                    <a class="" href="{{ route('rsv.help.index') }}">
                        <img src="{{ asset('/img/mnv/mnv-help2.gif') }}" width="92" height="33" alt="ヘルプ" />
                    </a>
                </div>
            @elseif ($pgh1_mnv == 3)
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.guide.coupon')
                        <a class="" href="{{ route('rsv.guide.coupon') }}">
                            <img src="{{ asset('/img/mnv/mnv-first2.gif') }}" width="112" height="40" alt="初めての方へ" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-first2_disable.gif') }}" width="112" height="40" alt="初めての方へ" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.point.index')
                        <a class="" href="{{ route('rsv.point.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-point2.gif') }}" width="112" height="40" alt="ＢＲポイント" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-point2_disable.gif') }}" width="112" height="40" alt="ＢＲポイント" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.reserve.index')
                        <a class="" href="{{ route('rsv.reserve.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-rsv2.gif') }}" width="112" height="40" alt="予約の確認・キャンセル・日程短縮" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-rsv2_disable.gif') }}" width="112" height="40" alt="予約の確認・キャンセル・日程短縮" />
                    @endif
                </div>
                <div class="mnv">
                    <a class="" href="{{ route('rsv.help.index') }}">
                        <img src="{{ asset('/img/mnv/mnv-help2.gif') }}" width="92" height="33" alt="ヘルプ" />
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
            @if(isset($svc_rsv) && $svc_rsv == true)
                <a class="btnimg" href="{{-- $v->env.path_base --}}">
                    <img src="{{ asset('/img/mnv/gnv-tab-rsv.gif') }}" width="115" height="30" alt="国内宿泊" />
                </a>
            @else
                <a>
                    <img src="{{ asset('/img/mnv/gnv-tab-rsv_over.gif') }}" width="115" height="30" alt="国内宿泊" />
                </a>
            @endif
        </li>
    </ul>
    <hr class ="glovalnavi-line" />
</div>
