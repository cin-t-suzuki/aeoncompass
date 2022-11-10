{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\htlhotel\show.tpl --}}

@extends('ctl.common._htl_base')
@section('title', '施設情報詳細')

@section('content')
{{-- パンクズ --}}
<a href="{$v->env.source_path}{$v->env.module}/htltop/index/target_cd/{$v->assign->target_cd}">
    メインメニュー
</a>&nbsp;&gt;&nbsp;施設情報詳細

<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

施設情報
<table border="1" cellspacing="0" cellpadding="4">
    {{-- 施設情報登録内容 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            施設情報登録内容
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotel/edit/" method="post">
            <td>
                <input type="submit" value="詳細">
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
        <td>
            施設情報登録内容の変更
        </td>
    </tr>
    {{-- 利用可能カード --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            利用可能カード
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelcard/show/" method="post">
            <td>
                <input type="submit" value="詳細">
                <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
            </td>
        </form>
        <td>
            {if $v->assign->a_hotle_card.values|@count > 0}
                {foreach from=$v->assign->a_hotle_card.values name=hotelcard item=values}
                    {if $smarty.foreach.hotelcard.last}
                        {$v->helper->form->strip_tags($values.card_nm)}
                    {else}
                        {$v->helper->form->strip_tags($values.card_nm)}、
                    {/if}
                {/foreach}
            {else}
                <span style="color:#ccc">なし</span>
            {/if}
        </td>
    </tr>
    {{-- 施設情報 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            施設情報
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelinfo/" method="post">
            <td>
                <input type="submit" value="詳細">
            </td>
        <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
        <td>
            {if zap_is_empty($v->assign->a_hotel_info)}
                <span style="color:#ccc">駐車場詳細、カード利用条件、特色</span>
            {else}
                駐車場詳細、カード利用条件、特色
            {/if}
        </td>
    </tr>
    {{-- 施設情報ページ（キャンセル等条件） --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            施設連絡事項
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelinform/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_inform_cancel.values) and zap_is_empty($v->assign->a_hotel_inform_free.values)}
                    <span style="color:#ccc">注意事項 その他記入欄</span>
                {else}
                    注意事項 その他記入欄
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- リンクページ --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap rowsupan="{$v->assign->a_hotel_link|@count}">
            リンクページ
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotellink/list/" method="post" >
            <td rowsupan="{$v->assign->a_hotel_link.values|@count}">
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_links.values)}
                    <span style="color:#ccc">施設情報ページからのリンク</span>
                {else}
                    施設情報ページからのリンク
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- 交通アクセス --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            交通アクセス
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelstation/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_station)}
                    <span style="color:#ccc">なし</span>
                {else}
                    @include('ctl.common._hotel_stations')
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- アメニティ --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            アメニティ
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelamenity/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_amenities.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$v->assign->a_amenity.values name=amenity item=values}
                        {if $smarty.foreach.amenity.last}
                            {$v->helper->form->strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.amenity.total}
                                等
                            {/if}
                        {else}
                            {$v->helper->form->strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- サービス --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            サービス
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelservice/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_services.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$v->assign->a_service.values name=service item=values}
                        {if $smarty.foreach.service.last}
                            {$v->helper->form->strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.service.total}
                                等
                            {/if}
                        {else}
                            {$v->helper->form->strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- 周辺情報 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            周辺情報
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelnearby/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_nearbies.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$v->assign->a_nearby.values name=nearby item=values}
                        {if $smarty.foreach.nearby.last}
                            {$v->helper->form->strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.nearby.total}
                                等
                            {/if}
                        {else}
                            {$v->helper->form->strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- 設備 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            設備
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelfacility/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_facilities.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$v->assign->a_facility.values name=facility item=values}
                        {if $smarty.foreach.facility.last}
                            {$v->helper->form->strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.facility.total}
                                等
                            {/if}
                        {else}
                            {$v->helper->form->strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- 部屋設備 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            部屋設備
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelfacilityroom/list/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($v->assign->a_hotel_facility_rooms.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$v->assign->a_facility_room.values name=facility_room item=values}
                        {if $smarty.foreach.facility_room.last}
                            {$v->helper->form->strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.facility_room.total}
                                等
                            {/if}
                        {else}
                            {$v->helper->form->strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- 施設管理 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            早割丸め設定
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelchargeround/index/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if count($v->assign->a_hotel_control) != 0}
                    {if $v->assign->a_hotel_control.charge_round == 1 || zap_is_empty($v->assign->a_hotel_control.charge_round)}
                        1の位で丸める
                    {elseif $v->assign->a_hotel_control.charge_round == 10}
                        10の位で丸める
                    {elseif $v->assign->a_hotel_control.charge_round == 100}
                        100の位で丸める
                    {/if}<br />
                {else}
                    <span style="color:#ccc">未設定</span>
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- キャンセルポリシー --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            キャンセルポリシー
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelcancel/index/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if is_empty($v->assign->a_hotel_cancel_rates.values) and is_empty($v->assign->a_hotel_cancel_policy)}
                    <span style="color:#ccc">施設 キャンセルポリシーの選択</span>
                {else}
                    施設 キャンセルポリシーの選択
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    {{-- 領収書発行 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            領収書発行ポリシー
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htlhotelreceipt/index/" method="post" >
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if is_empty($v->assign->a_hotel_receipt)}
                    <span style="color:#ccc">領収書発行ポリシー</span>
                {else}
                    領収書発行ポリシー
                {/if}
            </td>
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
        </form>
    </tr>
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            入湯税
        </td>
        {if $v->assign->a_hotel_bath_tax_flg == 1}
            <form action="{$v->env.source_path}{$v->env.module}/htlsbathtax/" method="post" >
                <td>
                    <input type="submit" value="詳細" />
                    <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
                </td>
            </form>
            <td>
                <font size="2" color="#5555FE">
                    ＪＲコレクション用の設定になります。<br />
                    入湯税が必要な旨の表示は「施設連絡事項」をご利用頂き、現地にて徴収してください。
                </font><br />
                {if is_empty($v->assign->a_hotel_bath_tax)}
                    <span style="color:#ccc">未設定</span>
                {else}
                    {if $v->assign->a_hotel_bath_tax.bath_tax_status == 1}
                        (大人)１人１泊：￥{$v->assign->a_hotel_bath_tax.bath_tax_charge|number_format}<br />
                        (子供)１人１泊：￥{$v->assign->a_hotel_bath_tax.bath_tax_charge_child|number_format}
                    {else}
                        入湯税不要
                    {/if}
                {/if}
            </td>
        {else}
        <td>
        </td>
        <td>
            <font size="2" color="#5555FE">
                ※ＪＲコレクション用の設定になります。<br />
                入湯税が必要な旨の表示は「施設連絡事項」をご利用頂き、現地にて徴収してください。
            </font><br />
            入湯税が変動制のエリアになりますので<br />
            こちらの機能はご利用頂けません。
        </td>
        {/if}
    </tr>
</table>
<br />

<div style="float:right">
    <FORM ACTION="{$v->env.source_path}{$v->env.module}/htlhotel/staticupdate/" METHOD="POST" target="page_test">
        情報ページHTML
        <small>
            <INPUT TYPE="submit" VALUE="更新する">
            <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
            <input type="hidden" name="redirect_url" value="http://{$v->config->system->rsv_host_name}/hotel/{$v->helper->form->strip_tags($v->assign->target_cd)}/">
        </small>
    </form>
</div>

@endsection
