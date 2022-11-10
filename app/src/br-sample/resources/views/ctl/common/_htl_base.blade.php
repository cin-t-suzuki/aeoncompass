
{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_header.tpl --}}
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="robots" content="none">
    <script type="text/javascript" src="/scripts/jquery.js?9465-2"></script>
    <script type="text/javascript" src="/scripts/jquery.cookies.js"></script>
    <title>[ストリーム]予約受付管理 [@yield('title')]</title>
    <link type="text/css" rel="stylesheet" href="/styles/base.css?9465-2">
    {{-- 印刷用スタイルシート --}}
    {if $print_flg}
        <link type="text/css" rel="stylesheet" href="/styles/print.css" media="print">
        <link type="text/css" rel="stylesheet" href="/styles/screen.css" media="screen">
    {/if}
    {{-- Googleアナリティクス --}}
    {include file=$v->env.module_root|cat:'/views/_common/_google_analytics.tpl'}
</head>

{{-- TODO: タグのインデントを修正 --}}
{if $v->config->environment->status == "test"}
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #297;">
        <div style="margin-left:-12px;padding:0.25em 0;background-color:#297;color:#fff;font-weifht:bold;width:6em;text-align:center;">検証環境</div>
{elseif $v->config->environment->status == "development"}
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #36A;">
        <div style="margin-left:-12px;padding:0.25em 0;background-color:#36A;color:#fff;font-weifht:bold;width:6em;text-align:center;">開発環境</div>
{elseif $v->config->environment->status != "product"}
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #A63;">
        <div style="margin-left:-12px;padding:0.25em 0;background-color:#A63;color:#fff;font-weifht:bold;width:6em;text-align:center;">環境不明</div>
{else}
    <body topmargin="0" marginheight="0">
{/if}

{if $no_print == true}
    <div class="noprint">
{/if}

{{-- staffのみ情報を表示 --}}
{if $v->user->operator->is_staff()}
    {include file=$v->env.module_root|cat:'/views/_common/_htl_staff_header.tpl'}
{elseif $v->user->operator->is_nta()}
    {include file=$v->env.module_root|cat:'/view2/_common/_nta_staff_header.tpl'}
{/if}

<table border="0" width="100%" cellspacing="0" cellpadding="5" bgcolor="#EEEEFF" >
    <tr>
        <td nowrap>{$v->helper->form->strip_tags($header_number)}</td>
        <td>
            <a href="{$v->env.source_path}{$v->env.module}/redirect/rsvhotel/?target_cd={$v->user->hotel.hotel_cd}" target="_blank">
                {$v->helper->form->strip_tags($v->user->hotel.hotel_nm)}
                {if (!is_empty($v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)))}
                    (旧{$v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)})
                {/if}
            </a> 様
            (施設コード：{$v->helper->form->strip_tags($v->user->hotel.hotel_cd)})
        </td>
        <td nowrap align="right">
            <table cellspacing="0" cellpadding="2" border="0">
                <tr>
                    {{-- aやん! --}}
                    <td rowspan="2">
                        {{-- <a href="http://www.nihon-weekly.com/hotelask/" target="_blank"><img src="/images/intro/ayan/banner.gif" width="300" height="56" border="0" alt="aやん! ウィークリーホテルズ" /></a> --}}
                        {if $smarty.now >= '2020-06-01 00:00:00'|strtotime and $smarty.now <= '2020-07-15 23:59:59'|strtotime}
                            <a href="https://www.kanxashi.co.jp/cp/202006kanxashi/?key=brv" target="_blank"><img src="/images/intro/kanzashi/kanxashi_zenryoku.png" alt="かんざしクラウド" height="56" width="300" /></a>
                        {/if}
                    </td>
                    {{-- 一時削除 ２月早々にリニューアル予定 --}}
                    {{-- <td rowspan="2"><a href="http://{$v->config->system->rsv_host_name}/intro/htlorimo/" target="_blank"><img src="/images/intro/htlorimo/banner.gif" width="328" height="56" border="0" alt="ＯＲＩＭＯケータイプレミアムモニターキャンペーン" /></a></td> --}}
                    <td rowspan="2">
                        {if $v->user->hotel_status.entry_status == 0 && !($acceptance_status_flg === false)}
                            {include file=$v->env.module_root|cat:'/views/_common/_change_acceptance.tpl'}
                        {elseif $v->user->hotel.ydp2_status && !($acceptance_status_flg === false)}
                            {include file=$v->env.module_root|cat:'/views/_common/_change_acceptance.tpl'}
                        {/if}
                    </td>
                        <td><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action="{$v->env.source_path}{$v->env.module}/htltop/" method="POST">
                            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
                            <input type="submit" value="メニュー">
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br />

{{-- サービスセンター --}}
{if $service_info_flg !== false}
    {include file=$v->env.module_root|cat:'/views/_common/_htl_service_info.tpl' ad=$ad}
{/if}

{if $no_print == true}
    </div>
{/if}

{if $no_print_title == true}
    <div class="noprint">
{/if}

{{-- サービスセンター --}}
{if !(is_empty($menu_title)) || !(is_empty($title))}
    <table border="3" cellspacing="0" cellpadding="2">
        <tr>
            <td bgcolor="#EEEEFF" align="center">
                <big>
                    {if is_empty($menu_title)}
                        {$v->helper->form->strip_tags($title)}
                    {else}
                        {$v->helper->form->strip_tags($menu_title)}
                    {/if}
                </big>
            </td>
        </tr>
    </table>
{/if}

<br />

{if $no_print_title == true}
    </div>
{/if}

{{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_header.tpl --}}

@yield('content')

{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}
{if $no_print == true}
    <div class="noprint">
{/if}
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0" >
    <tr>
        <td colspan="2" width="100%"><hr size="1" width="100%"></td>
    </tr>
    <tr>
        {{-- ログインしていれば --}}
        {if $v->user->operator->is_login() == true}
            <td nowrap>
                {if !$v->user->operator->is_staff()}
                    <small><a href="{$v->env.source_path}{$v->env.module}/logout/">ログアウト</a></small>
                {/if}
            </td>
        {/if}
        <td align="right">
            <small>画面更新日時({$v->helper->form->strip_tags($smarty.now)|date_format:'%Y-%m-%d %T'})</small>
        </td>
    </tr>
</table>

<br />

<small>(c)Copyright {$smarty.now|date_format:'%Y'} BestReserve Co.,Ltd. All Rights Reserved.</small>
<br><br>
{if $no_print == true}
    </div>
{/if}

</body>
</HTML>
{{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}
