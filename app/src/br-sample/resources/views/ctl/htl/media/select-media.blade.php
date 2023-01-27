{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/selectmedia.tpl --}}

{{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='画像選択'} --}}
@extends('ctl.common._htl_base')
@section('title', '画像選択')

@section('headScript')
    {{-- {include file='./_css.tpl'} --}}
    @include('ctl.htl.media._css')
    {{-- {include file='./_script.tpl'} --}}
    @include('ctl.htl.media._script')
@endsection

@section('content')
    <div class="clear">
        <hr>
    </div>
    <hr width="100%" size="1">
    {{-- Main --}}
    <div id="page_top_symbol">
        <p>
            {{-- {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'} --}}
            @include('ctl.common.message')
        </p>
        <div>
            {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_upload_form.tpl'} --}}
            @include('ctl.htl.media._upload_form')
        </div>
        <p>
            <font color="cdcdcd">■</font>登録画像一覧
        </p>
        @if ($wide_list)
            @php
                $table_width = 1170;
                $disp_order_width = 'width:50px;';
                $label_width = 'width:50px;';
                $image_width = 'width:64px;';
                $title_label_width = 'width:12%;';
            @endphp

            @if ($label_cd['map'])
                @php
                    $update_label_width = 'width:13%;';
                @endphp
            @else
                @php
                    $update_label_width = 'width:11%;';
                @endphp
            @endif

            @php
                $update_width = 'width:15%;';
                $file_colspan = 3;
            @endphp
        @else
            @php
                $table_width = 700;
                $disp_order_width = '';
                $label_width = '';
                $image_width = '';
                $file_colspan = 1;
                $title_label_width = 'width:24%;';
            @endphp
        @endif
        @if (count($media_list) === 0)
            <font color="ff0000">現在アップロードされている画像はありません。</font>
        @else
            @if (!$label_cd['map'])
                {{-- 地図画像の設定時は検索BOXは表示しない --}}
                <div>
                    {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                    <table border="1" cellpadding="4" cellspacing="0" width="700">
                        <tr>
                            <td>
                                {{ Form::checkbox('label_cd[outside]', '1', !is_null($label_cd['outside']), ['id' => 'label_outside']) }}
                                <label for="label_outside">
                                    <font title="外観" color="#FF9999">■</font>外観
                                </label>
                            </td>
                            <td>
                                {{ Form::checkbox('label_cd[inside]', '1', !is_null($label_cd['inside']), ['id' => 'label_inside']) }}
                                <label for="label_inside">
                                    <font title="館内" color="#99FF99">■</font>フォトギャラリー
                                </label>
                            </td>
                            <td>
                                {{ Form::checkbox('label_cd[room]', '1', !is_null($label_cd['room']), ['id' => 'label_room']) }}
                                <label for="label_room">
                                    <font title="客室" color="#66CCFF">■</font>客室
                                </label>
                            </td>
                            <td>
                                {{ Form::checkbox('label_cd[other]', '1', !is_null($label_cd['other']), ['id' => 'label_other']) }}
                                <label for="label_other">
                                    <font title="その他" color="#FF99FF">■</font>その他
                                </label>
                            </td>
                            <td>
                                {{ Form::checkbox('label_cd[nothing]', '1', !is_null($label_cd['nothing']), ['id' => 'label_nothing']) }}
                                <label for="label_nothing">
                                    <font title="ラベル無し" color="#cccccc">■</font>ラベル無し
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" align="center">
                                {{ Form::hidden('target_cd', $target_cd) }}
                                {{ Form::hidden('room_id', $room_id) }}
                                {{ Form::hidden('plan_id', $plan_id) }}
                                {{ Form::hidden('label_type', $label_type) }}
                                {{ Form::hidden('target_order_no', $target_order_no) }}
                                {{ Form::hidden('setting_media_no', $setting_media_no) }}
                                {{ Form::hidden('media_type', $media_type) }}
                                {{ Form::hidden('wide_list_ref', '1') }}
                                {{ Form::submit('表示') }}
                                {{ Form::checkbox('wide_list_check', '1', $wide_list, ['id' => 'wide_list_check']) }}
                                <label for="wide_list_check">
                                    <span style="color:#2655a0; font-size: 13px;">画像一覧をワイド表示にする</span>
                                </label>
                            </td>
                        </tr>
                    </table>
                    {{ Form::close() }}
                </div>
                <br />
            @endif
            <div class="wrap_media_scroll" style="width:{{ $table_width }}px;">
                <div class="scroll_button_btm">
                    <a href="#page-bottom">▼画像一覧の最下部へ</a>
                </div>
            </div>
            <table border="1" cellpadding="4" cellspacing="0" width="{{ $table_width }}">
                <tr bgcolor="#EEEEFF">
                    <th style="{{ $disp_order_width }}">表示順</th>
                    <th style="{{ $label_width }}">ラベル</th>
                    <th style="{{ $image_width }}">画像</th>
                    <th>ファイル</th>
                    <th>利用状況</th>
                    <th>設定</th>
                </tr>
                @foreach ($media_list as $media)
                    <tr>
                        <td style="{{ $disp_order_width }}">
                            @if (!$loop->first)
                                {{-- TODO: 同じコントローラで適応できてるか確認 --}}
                                {{ Form::open(['route' => 'ctl.htl.media.sort_media', 'method' => 'post', 'style' => 'display:inline;']) }}
                                {{ Form::submit('↑') }}

                                {{ Form::hidden('target_cd', $target_cd) }}
                                {{ Form::hidden('media_no', $media->media_no) }}
                                {{ Form::hidden('target_media_no', $media_list[$loop->index - 1]->media_no) }}

                                {{ Form::hidden('room_id', $room_id) }}
                                {{ Form::hidden('plan_id', $plan_id) }}

                                {{ Form::hidden('target_order_no', $target_order_no) }}
                                {{ Form::hidden('setting_media_no', $setting_media_no) }}
                                {{ Form::hidden('media_type', $media_type) }}

                                {{ Form::hidden('label_type', $label_type) }}
                                {{ Form::hidden('label_cd[outside]', $label_cd['outside']) }}
                                {{ Form::hidden('label_cd[map]', $label_cd['map']) }}
                                {{ Form::hidden('label_cd[inside]', $label_cd['inside']) }}
                                {{ Form::hidden('label_cd[room]', $label_cd['room']) }}
                                {{ Form::hidden('label_cd[other]', $label_cd['other']) }}
                                {{ Form::hidden('label_cd[nothing]', $label_cd['nothing']) }}
                                {{ Form::close() }}
                            @endif

                            @if (!$loop->last)
                                {{ Form::open(['route' => 'ctl.htl.media.sort_media', 'method' => 'post', 'style' => 'display:inline;']) }}
                                {{ Form::submit('↓') }}

                                {{ Form::hidden('target_cd', $target_cd) }}
                                {{ Form::hidden('media_no', $media->media_no) }}
                                {{ Form::hidden('target_media_no', $media_list[$loop->index + 1]->media_no) }}

                                {{ Form::hidden('room_id', $room_id) }}
                                {{ Form::hidden('plan_id', $plan_id) }}

                                {{ Form::hidden('target_order_no', $target_order_no) }}
                                {{ Form::hidden('setting_media_no', $setting_media_no) }}
                                {{ Form::hidden('media_type', $media_type) }}

                                {{ Form::hidden('label_type', $label_type) }}
                                {{ Form::hidden('label_cd[outside]', $label_cd['outside']) }}
                                {{ Form::hidden('label_cd[map]', $label_cd['map']) }}
                                {{ Form::hidden('label_cd[inside]', $label_cd['inside']) }}
                                {{ Form::hidden('label_cd[room]', $label_cd['room']) }}
                                {{ Form::hidden('label_cd[other]', $label_cd['other']) }}
                                {{ Form::hidden('label_cd[nothing]', $label_cd['nothing']) }}

                                {{ Form::close() }}
                            @endif
                        </td>
                        <td style="{{ $label_width }}">
                            {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_label_cd_type.tpl' label_cd=$media.label_cd} --}}
                            @include('ctl.htl.media._label_cd_type', ['label_cd' => $media->label_cd])
                        </td>
                        <td class="wrap_media_pop_view" style="{{ $image_width }}" align="center">
                            {{-- <img border="0" src="/images/hotel/{{ $target_cd }}/trim_054/{{ $media->file_nm }}" width="54" height="54" title="{{ $media->title }}"> --}}
                            <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $media->file_nm) }}" title="{{ $media->title }}" border="0" width="54" height="54">
                            <div class="media_pop_frame">
                                {{-- <img border="1" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $media->file_nm }}" width="1" height="1" title="{{ $media->title }}" class="media_pop_view"> --}}
                                <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $media->file_nm) }}" title="{{ $media->title }}" border="1" width="1" height="1">
                            </div>
                        </td>
                        <td>
                            <table border="1" cellspacing="0" cellpadding="6" width="100%">
                                <tr>
                                    <td class="title_label" style="{{ $title_label_width }}">タイトル</td>
                                    <td>{{ $media->title }}</td>
                                    @if ($wide_list)
                                        <td class="update_label" style="{{ $update_label_width }}">更新日時</td>
                                        <td style="{{ $update_width }}">{{ date('Y-m-d', strtotime($media->modify_ts)) }}
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td class="file_label">ファイル名</td>
                                    <td colspan="{{ $file_colspan }}">
                                        <span style="font-size:18px; display:block; margin-bottom: 3px; ">{{ $media->disp_file_nm }}</span>
                                    </td>
                                </tr>
                                @if (!$wide_list)
                                    <tr>
                                        <td class="update_label">更新日時</td>
                                        <td>{{ date('Y-m-d', strtotime($media->modify_ts)) }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                        <td>
                            {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_use_type.tpl' is_use=$media.is_use} --}}
                            @include('ctl.htl.media._use_type', ['is_use' => $media->is_use])
                        </td>
                        <td>
                            {{-- MEMO: 分岐 'ctl/htlsmedia/update(hotel|room|plan)' --}}
                            {{ Form::open(['route' => 'ctl.htl.media.update_' . $media_type, 'method' => 'post', 'style' => 'display:inline;']) }}
                            {{ Form::hidden('target_cd', $target_cd) }}
                            {{ Form::hidden('media_type', $media_type) }}

                            {{ Form::hidden('media_no', $media->media_no) }}
                            {{ Form::hidden('setting_media_no', $setting_media_no) }}
                            {{ Form::hidden('target_order_no', $target_order_no) }}

                            {{-- 部屋・プランメンテナンスからの遷移時 --}}
                            {{ Form::hidden('room_id', $room_id) }}
                            {{ Form::hidden('plan_id', $plan_id) }}

                            {{ Form::hidden('label_type', $label_type) }}
                            {{ Form::submit('設定') }}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
        <div class="wrap_media_scroll" style="width:{{ $table_width }}px;">
            <div class="scroll_button_btm">
                <a href="#page-top">▲画像一覧のTOPへ</a>
            </div>
        </div>
    </div>
    {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'} --}}
    @include('ctl.htl.media._common_menu')

    {{-- /Main --}}

    <div class="clear">
        <hr>
    </div>
    {{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'} --}}
@endsection
