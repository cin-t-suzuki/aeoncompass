{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\htlHotel\show.tpl --}}

@extends('ctl.common._htl_base')
@section('title', '施設情報詳細')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelController')

{{-- HACK: （工数次第） #ccc はグレーアウトっぽくしているところなので、 class でまとめて css に定義したい --}}
{{-- HACK: （工数次第） 各行のタイトルが青背景で改行禁止になっている。 css にまとめたい --}}
@section('headScript')
    <style type="text/css">
        .grayout {
            color: #ccc;
        }
    </style>
@endsection

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' => $target_cd]) }}">
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
                {{ $value->card_nm }}
                {{ $loop->last ? '' : '、' }}
            @empty
                <span class="grayout">なし</span>
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
            <span class="{{ is_null($a_hotel_info) ? 'grayout' : '' }}">
                駐車場詳細、カード利用条件、特色
            </span>
        </td>
    </tr>
    {{-- 施設情報ページ（キャンセル等条件） --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            施設連絡事項
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_inform.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                <span class="{{ $a_hotel_inform_cancel->isEmpty() && $a_hotel_inform_free->isEmpty() ? 'grayout' : ''}}">
                    注意事項 その他記入欄
                </span>
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- リンクページ --}}
    <tr>
        {{--
            MEMO: 移植元では未定義配列の要素数で rowsupan を設定している。
            rowspan の typo と思われるが、正しく設定するとテーブルが崩れる。
            内容から推測して、 rowspan 指定は必要ないと判断した。
        --}}
        <td bgcolor="#EEEEFF" nowrap>
            リンクページ
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_link.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                <span class="{{ $a_hotel_links->isEmpty() ? 'grayout' : '' }}">
                    施設情報ページからのリンク
                </span>
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 交通アクセス --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            交通アクセス
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_station.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if ($a_hotel_station->isEmpty())
                    <span class="grayout">なし</span>
                @else
                    @include('ctl.common._hotel_stations', [
                        'hotel_stations' => $a_hotel_station,
                        'limit' => null, // 未定義だと動作しないため、 null で定義
                    ])
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- アメニティ --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            アメニティ
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_amenity.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if ($a_hotel_amenities->isEmpty())
                    <span class="grayout">なし</span>
                @else
                    @forelse ($a_amenity as $value)
                        {{ strip_tags($value['element_nm']) . (!$loop->last ? '、' : '') }}
                        @if ($loop->last && $loop->count >= 3)
                            等
                        @endif
                    @empty
                        <span class="grayout">なし</span>
                    @endforelse
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- サービス --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            サービス
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_service.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if ($a_hotel_services->isEmpty())
                    <span class="grayout">なし</span>
                @else
                    @forelse ($a_service as $value)
                        {{ strip_tags($value['element_nm']) . (!$loop->last ? '、' : '') }}
                        @if ($loop->last && $loop->count >= 3)
                            等
                        @endif
                    @empty
                        <span class="grayout">なし</span>
                    @endforelse
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 周辺情報 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            周辺情報
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_nearby.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if ($a_hotel_nearbies->isEmpty())
                    <span class="grayout">なし</span>
                @else
                    @forelse ($a_nearby as $value)
                        {{ strip_tags($value['element_nm']) . (!$loop->last ? '、' : '') }}
                        @if ($loop->last && $loop->count >= 3)
                            等
                        @endif
                    @empty
                        <span class="grayout">なし</span>
                    @endforelse
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 設備 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            設備
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_facility.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if ($a_hotel_facilities->isEmpty())
                    <span class="grayout">なし</span>
                @else
                    @forelse ($a_facility as $value)
                        {{ strip_tags($value['element_nm']) . (!$loop->last ? '、' : '') }}
                        @if ($loop->last && $loop->count >= 3)
                            等
                        @endif
                    @empty
                        <span class="grayout">なし</span>
                    @endforelse
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 部屋設備 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            部屋設備
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_facility_room.list', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if ($a_hotel_facility_rooms->isEmpty())
                    <span class="grayout">なし</span>
                @else
                    @forelse ($a_facility_room as $value)
                        {{ strip_tags($value['element_nm']) . (!$loop->last ? '、' : '') }}
                        @if ($loop->last && $loop->count >= 3)
                            等
                        @endif
                    @empty
                        <span class="grayout">なし</span>
                    @endforelse
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 施設管理 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            早割丸め設定
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_charge_round.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                @if (!is_null($a_hotel_control))
                    @if ($a_hotel_control->charge_round == 1 || is_null($a_hotel_control->charge_round))
                        1の位で丸める
                    @elseif ($a_hotel_control->charge_round == 10)
                        10の位で丸める
                    @elseif ($a_hotel_control->charge_round == 100)
                        100の位で丸める
                    @else
                        {{-- MEMO: 移植元では実装なし。空欄になる想定 --}}
                    @endif<br />
                @else
                    <span class="grayout">未設定</span>
                @endif
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- キャンセルポリシー --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            キャンセルポリシー
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_cancel.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                <span class="{{ $a_hotel_cancel_rates->isEmpty() && is_null($a_hotel_cancel_policy) ? 'grayout' : '' }}">
                    施設 キャンセルポリシーの選択
                </span>
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 領収書発行ポリシー --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            領収書発行ポリシー
        </td>
        {{ Form::open(['route' => 'ctl.htl_hotel_receipt.index', 'method' => 'post']) }}
            <td>
                <input type="submit" value="詳細">
            </td>
            <td>
                <span class="{{ is_null($a_hotel_receipt) ? 'grayout' : '' }}">
                    領収書発行ポリシー
                </span>
            </td>
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
        {{ Form::close() }}
    </tr>

    {{-- 入湯税 --}}
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            入湯税
        </td>
        @if ($a_hotel_bath_tax_flg == 1)
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
                @if (is_null($a_hotel_bath_tax))
                    <span class="grayout">未設定</span>
                @else
                    {{-- HACK: magic number --}}
                    @if ($a_hotel_bath_tax->bath_tax_status == 1)
                        (大人)１人１泊：￥{{ number_format($a_hotel_bath_tax->bath_tax_charge) }}<br />
                        (子供)１人１泊：￥{{ number_format($a_hotel_bath_tax->bath_tax_charge_child) }}
                    @else
                        入湯税不要
                    @endif
                @endif
            </td>
        @else
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
        @endif
    </tr>
</table>
<br />

<div style="float:right">
    <form action="/ctl/htlHotel/staticupdate/" method="post" target="page_test">
        情報ページHTML
        <small>
            <input type="submit" value="更新する">
            {{ Form::hidden('target_cd', strip_tags($target_cd)) }}
            <input type="hidden" name="redirect_url" value="http://{$v->config->system->rsv_host_name}/hotel/{{ strip_tags($target_cd) }}/">
        </small>
    </form>
</div>

@endsection
