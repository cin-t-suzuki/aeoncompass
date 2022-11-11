{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\htlHotel\show.tpl --}}

@extends('ctl.common._htl_base')
@section('title', '施設情報詳細')

{{-- HACK: （工数次第） #ccc はフェードアウトっぽくしているところなので、 class でまとめて css に定義したい --}}
@section('headScript')
    <style type="text/css">
        .fadeout {
            color: #ccc;
        }
    </style>
@endsection

@section('content')

{{-- TODO: 共通ヘッダとの境界 to be deleted --}}
<hr>
<hr>
<hr>
<hr>
<hr>


{{-- パンクズ --}}
{{-- TODO: 名前付きルートに変更 --}}
<a href="ctl/htltop/index/target_cd/{{ $target_cd }}">
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
        {{ Form::open(['route' => 'ctl.htl_hotel.edit', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
        <td>
            施設情報登録内容の変更
        </td>
    </tr>
    {{-- 利用可能カード --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            利用可能カード
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_card.show', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
                {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
            </td>
        {{ Form::close() }}
        <td>
            @forelse ($a_hotel_card as $value)
                {{ $value->card_id }}
                {{ $loop->last ? '' : '、' }}
            @empty
                <span style="color:#ccc">なし</span>
            @endforelse
        </td>
    </tr>
    {{-- 施設情報 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            施設情報
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_info.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
        <td>
            <span class="{{ is_null($a_hotel_info) ? 'fadeout' : '' }}">
                駐車場詳細、カード利用条件、特色
            </span>
        </td>
    </tr>
    {{-- 施設情報ページ（キャンセル等条件） --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            施設連絡事項
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_inform.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_inform_cancel.values) and zap_is_empty($a_hotel_inform_free.values)}
                    <span style="color:#ccc">注意事項 その他記入欄</span>
                {else}
                    注意事項 その他記入欄
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- リンクページ --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap rowsupan="{count($a_hotel_link)}">
            リンクページ
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_link.list', 'method' => 'post']) }}
            <td rowsupan="{count($a_hotel_link.values)}">
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_links.values)}
                    <span style="color:#ccc">施設情報ページからのリンク</span>
                {else}
                    施設情報ページからのリンク
                {/if}
                <span class="{{ rand(0,1) ? 'fadeout' : '' }}">
                    施設情報ページからのリンク
                </span>
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 交通アクセス --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            交通アクセス
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_station.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_station)}
                    <span style="color:#ccc">なし</span>
                {else}
                    @include('ctl.common._hotel_stations')
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- アメニティ --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            アメニティ
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_amenity.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_amenities.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$a_amenity.values name=amenity item=values}
                        {if $smarty.foreach.amenity.last}
                            {strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.amenity.total}
                                等
                            {/if}
                        {else}
                            {strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- サービス --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            サービス
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_service.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_services.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$a_service.values name=service item=values}
                        {if $smarty.foreach.service.last}
                            {strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.service.total}
                                等
                            {/if}
                        {else}
                            {strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 周辺情報 --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            周辺情報
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_nearby.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_nearbies.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$a_nearby.values name=nearby item=values}
                        {if $smarty.foreach.nearby.last}
                            {strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.nearby.total}
                                等
                            {/if}
                        {else}
                            {strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 設備 --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            設備
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_facility.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_facilities.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$a_facility.values name=facility item=values}
                        {if $smarty.foreach.facility.last}
                            {strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.facility.total}
                                等
                            {/if}
                        {else}
                            {strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 部屋設備 --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            部屋設備
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_facility_room.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if zap_is_empty($a_hotel_facility_rooms.values)}
                    <span style="color:#ccc">なし</span>
                {else}
                    {foreach from=$a_facility_room.values name=facility_room item=values}
                        {if $smarty.foreach.facility_room.last}
                            {strip_tags($values.element_nm)}
                            {if 3 <= $smarty.foreach.facility_room.total}
                                等
                            {/if}
                        {else}
                            {strip_tags($values.element_nm)}、
                        {/if}
                    {foreachelse}
                        <span style="color:#ccc">なし</span>
                    {/foreach}
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 施設管理 --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            早割丸め設定
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_charge_round.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if count($a_hotel_control) != 0}
                    {if $a_hotel_control.charge_round == 1 || zap_is_empty($a_hotel_control.charge_round)}
                        1の位で丸める
                    {elseif $a_hotel_control.charge_round == 10}
                        10の位で丸める
                    {elseif $a_hotel_control.charge_round == 100}
                        100の位で丸める
                    {/if}<br />
                {else}
                    <span style="color:#ccc">未設定</span>
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- キャンセルポリシー --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            キャンセルポリシー
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_cancel.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if is_empty($a_hotel_cancel_rates.values) and is_empty($a_hotel_cancel_policy)}
                    <span style="color:#ccc">施設 キャンセルポリシーの選択</span>
                {else}
                    施設 キャンセルポリシーの選択
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 領収書発行ポリシー --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            領収書発行ポリシー
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_receipt.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                {if is_empty($a_hotel_receipt)}
                    <span style="color:#ccc">領収書発行ポリシー</span>
                {else}
                    領収書発行ポリシー
                {/if}
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>
    {{-- 入湯税 --}}TODO:
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            入湯税
        </td>
        {if $a_hotel_bath_tax_flg == 1}
            {{ Form::open(['route' => 'ctl.htl_bath_tax.index', 'method' => 'post']) }}
                <td>
                    <input type="submit" value="詳細" />
                    {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
                </td>
            {{ Form::close() }}
            <td>
                <font size="2" color="#5555FE">
                    ＪＲコレクション用の設定になります。<br />
                    入湯税が必要な旨の表示は「施設連絡事項」をご利用頂き、現地にて徴収してください。
                </font><br />
                {if is_empty($a_hotel_bath_tax)}
                    <span style="color:#ccc">未設定</span>
                {else}
                    {if $a_hotel_bath_tax.bath_tax_status == 1}
                        (大人)１人１泊：￥{$a_hotel_bath_tax.bath_tax_charge|number_format}<br />
                        (子供)１人１泊：￥{$a_hotel_bath_tax.bath_tax_charge_child|number_format}
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
    <FORM action="/ctl/htlHotel/staticupdate/" METHOD="POST" target="page_test">
        情報ページHTML
        <small>
            <input type="submit" value="更新する">
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
            <input type="hidden" name="redirect_url" value="http://{$v->config->system->rsv_host_name}/hotel/{{ strip_tags($target_cd) }}/">
        </small>
    </form>
</div>


<hr>
<hr>
<hr>
<hr>
<hr>
{{-- TODO: 共通フッタとの境界 to be deleted --}}
@endsection
