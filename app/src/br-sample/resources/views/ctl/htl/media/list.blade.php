{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/list.tpl --}}

{{-- {include file=$v->env->module_root|cat:'/views/_common/_htl_header.tpl' title='画像一覧管理'} --}}
@extends('ctl.common._htl_base')
@section('title', '画像一覧管理')

@section('headScript')
    @include('ctl.htl.media._css')
    @include('ctl.htl.media._script')
@endsection

@section('content')
    <div class="clear">
        <hr>
    </div>
    <hr width="100%" size="1">

    <!-- Main -->
    <div id="page_top_symbol">
        <p>
            @include('ctl.common.message')
        </p>
        <div>
            {{-- {include file=$v->env->module_root|cat:'/view2/htlsmedia/_upload_form.tpl'} --}}
            @include('ctl.htl.media._upload_form')
        </div>
        <p>
            <font color="cdcdcd">■</font>登録画像一覧
        </p>

        {{-- MEMO: 適当な値を設定しないと、未定義変数エラーとなる。 --}}
        @php
            $table_width = '1170';
        @endphp

        @if (count($media_list) === 0)
            <font color="ff0000">現在アップロードされている画像はありません。</font>
        @else
            <div>
                {{-- 絞込フォーム --}}
                {{ Form::open(['route' => 'ctl.htl.media.list', 'method' => 'get', 'style' => 'display:inline;']) }}
                <table border="1" cellpadding="4" cellspacing="0" width="700">
                    <tr>
                        <td>
                            {{-- <input type="checkbox" id="label_outside" name="label_cd[outside]" value="1" {if !is_empty($v->assign->form_params->label_cd->outside)}checked="checked"{/if} /> --}}
                            {{ Form::checkbox('label_cd[outside]', '1', !is_null($form_params['label_cd']['outside']), ['id' => 'label_outside']) }}
                            <label for="label_outside">
                                <font title="外観" color="#FF9999">■</font>外観
                            </label>
                        </td>
                        <td>
                            {{-- <input type="checkbox" id="label_map" name="label_cd[map]" value="1" {if !is_empty($v->assign->form_params->label_cd->map)}checked="checked"{/if} /> --}}
                            {{ Form::checkbox('label_cd[map]', '1', !is_null($form_params['label_cd']['map']), ['id' => 'label_map']) }}
                            <label for="label_map">
                                <font title="地図" color="#FFCC66">■</font>地図
                            </label>
                        </td>
                        <td>
                            {{-- <input type="checkbox" id="label_inside" name="label_cd[inside]" value="1" {if !is_empty($v->assign->form_params->label_cd->inside)}checked="checked"{/if} /> --}}
                            {{ Form::checkbox('label_cd[inside]', '1', !is_null($form_params['label_cd']['inside']), ['id' => 'label_inside']) }}
                            <label for="label_inside">
                                <font title="フォトギャラリー" color="#99FF99">■</font>フォトギャラリー
                            </label>
                        </td>
                        <td>
                            {{-- <input type="checkbox" id="label_room" name="label_cd[room]" value="1" {if !is_empty($v->assign->form_params->label_cd->room)}checked="checked"{/if} /> --}}
                            {{ Form::checkbox('label_cd[room]', '1', !is_null($form_params['label_cd']['room']), ['id' => 'label_room']) }}
                            <label for="label_room">
                                <font title="客室" color="#66CCFF">■</font>客室
                            </label>
                        </td>
                        <td>
                            {{-- <input type="checkbox" id="label_other" name="label_cd[other]" value="1" {if !is_empty($v->assign->form_params->label_cd->other)}checked="checked"{/if} /> --}}
                            {{ Form::checkbox('label_cd[other]', '1', !is_null($form_params['label_cd']['other']), ['id' => 'label_other']) }}
                            <label for="label_other">
                                <font title="その他" color="#FF99FF">■</font>その他
                            </label>
                        </td>
                        <td>
                            {{-- <input type="checkbox" id="label_nothing" name="label_cd[nothing]" value="1" {if !is_empty($v->assign->form_params->label_cd->nothing)}checked="checked"{/if} /> --}}
                            {{ Form::checkbox('label_cd[nothing]', '1', !is_null($form_params['label_cd']['nothing']), ['id' => 'label_nothing']) }}
                            <label for="label_nothing">
                                <font title="ラベル無し" color="#cccccc">■</font>ラベル無し
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" align="center">
                            {{ Form::hidden('target_cd', $form_params['target_cd']) }}
                            {{ Form::hidden('media_type', $form_params['media_type']) }}
                            {{ Form::hidden('target_order_no', $target_order_no) }}
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

            {{-- HACK: (refactor, 工数次第) 装飾は css にまとめて、 class の分岐で実装できないか？ --}}
            @if ($wide_list)
                @php
                    $table_width = '1170';
                    $disp_order_width = 'width:50px;';
                    $label_width = 'width:50px;';
                    $image_width = 'width:64px;';
                    $title_label_width = 'width:12%;';
                    $update_label_width = 'width:11%;';
                    $update_width = 'width:15%;';
                    $file_colspan = '3';
                @endphp
            @else
                @php
                    $table_width = '700';
                    $disp_order_width = '';
                    $label_width = '';
                    $image_width = '';
                    $file_colspan = '1';
                    $title_label_width = 'width:24%;';
                @endphp
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
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                @foreach ($media_list as $media)
                    <tr>
                        {{-- 表示順 --}}
                        <td style="{{ $disp_order_width }}">
                            @if (!$loop->first)
                                {{ Form::open(['route' => 'ctl.htl.media.sort_media', 'method' => 'post', 'style' => 'display:inline;']) }}
                                {{ Form::submit('↑') }}
                                {{ Form::hidden('target_cd', $form_params['target_cd']) }}
                                {{ Form::hidden('media_no', $media->media_no) }}
                                {{-- {{ Form::hidden('order_no', $media->order_no) }} --}}
                                {{ Form::hidden('target_media_no', $media_list[$loop->index - 1]->media_no) }}
                                {{ Form::hidden('change_flg', 'up') }}

                                {{ Form::hidden('label_type', $label_type) }}
                                {{ Form::hidden('label_cd[outside]', $form_params['label_cd']['outside']) }}
                                {{ Form::hidden('label_cd[map]', $form_params['label_cd']['map']) }}
                                {{ Form::hidden('label_cd[inside]', $form_params['label_cd']['inside']) }}
                                {{ Form::hidden('label_cd[room]', $form_params['label_cd']['room']) }}
                                {{ Form::hidden('label_cd[other]', $form_params['label_cd']['other']) }}
                                {{ Form::hidden('label_cd[nothing]', $form_params['label_cd']['nothing']) }}
                                {{ Form::close() }}
                            @endif

                            @if (!$loop->last)
                                {{ Form::open(['route' => 'ctl.htl.media.sort_media', 'method' => 'post', 'style' => 'display:inline;']) }}
                                {{ Form::submit('↓') }}
                                {{ Form::hidden('target_cd', $form_params['target_cd']) }}
                                {{ Form::hidden('media_no', $media->media_no) }}
                                {{-- {{ Form::hidden('order_no', $media->order_no) }} --}}
                                {{ Form::hidden('target_media_no', $media_list[$loop->index + 1]->media_no) }}
                                {{ Form::hidden('change_flg', 'down') }}

                                {{ Form::hidden('label_type', $label_type) }}
                                {{ Form::hidden('label_cd[outside]', $form_params['label_cd']['outside']) }}
                                {{ Form::hidden('label_cd[map]', $form_params['label_cd']['map']) }}
                                {{ Form::hidden('label_cd[inside]', $form_params['label_cd']['inside']) }}
                                {{ Form::hidden('label_cd[room]', $form_params['label_cd']['room']) }}
                                {{ Form::hidden('label_cd[other]', $form_params['label_cd']['other']) }}
                                {{ Form::hidden('label_cd[nothing]', $form_params['label_cd']['nothing']) }}
                                {{ Form::close() }}
                            @endif
                        </td>

                        {{-- ラベル --}}
                        <td style="{{ $label_width }}">
                            {{-- {include file=$v->env->module_root|cat:'/view2/htlsmedia/_label_cd_type.tpl' label_cd=$media->label_cd} --}}
                            @include('ctl.htl.media._label_cd_type', [
                                'label_cd' => $media->label_cd,
                            ])
                        </td>

                        {{-- 画像 --}}
                        <td class="wrap_media_pop_view" style="{{ $image_width }}" align="center">
                            {{-- <img border="0" src="/images/hotel/{{ $form_params['target_cd'] }}/trim_054/{{ $media->file_nm }}" width="54" height="54" title="{{ $media->title }}"> --}}
                            <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $media->file_nm) }}" title="{{ $media->title }}" border="0" width="54" height="54">
                            <div class="media_pop_frame">
                                {{-- <img border="1" src="/images/hotel/{{ $form_params['target_cd'] }}/trim_138/{{ $media->file_nm }}" width="1" height="1" title="{{ $media->title }}" class="media_pop_view"> --}}
                                <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $media->file_nm) }}" title="{{ $media->title }}" border="1" width="1" height="1">
                            </div>
                        </td>

                        {{-- ファイル --}}
                        <td>
                            <table border="1" cellspacing="0" cellpadding="6" width="100%">
                                <tr>
                                    <td class="title_label" style="{{ $title_label_width }}">タイトル</td>
                                    <td>{{ $media->title }}</td>

                                    {{-- HACK: 暫定実装 --}}
                                    @if ($wide_list)
                                        <td class="update_label" style="{{ $update_label_width }}">更新日時</td>
                                        <td style="{{ $update_width }}">{{ date('Y-m-d', strtotime($media->modify_ts)) }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td class="file_label">ファイル名</td>
                                    <td colspan="{{ $file_colspan }}">{{ $media->disp_file_nm }}</td>
                                </tr>
                                @if (!$wide_list)
                                    <tr>
                                        <td class="update_label">更新日時</td>
                                        <td>{{ date('Y-m-d', strtotime($media->modify_ts)) }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>

                        {{-- 利用状況 --}}
                        <td>
                            {{-- {include file=$v->env->module_root|cat:'/view2/htlsmedia/_use_type.tpl' is_use=$media->is_use} --}}
                            @include('ctl.htl.media._use_type', [
                                'is_use' => $media->is_use,
                            ])
                        </td>

                        {{-- 編集 --}}
                        <td>
                            {{ Form::open(['route' => 'ctl.htl.media.edit_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                            {{ Form::hidden('target_cd', $form_params['target_cd']) }}
                            {{ Form::hidden('media_no', $media->media_no) }}
                            {{ Form::submit('編集') }}
                            {{ Form::close() }}
                        </td>

                        {{-- 削除 --}}
                        <td>
                            {{ Form::open(['route' => 'ctl.htl.media.destroy_media', 'method' => 'post', 'style' => 'display:inline;', 'onSubmit' => 'return is_confirm();']) }}
                            {{ Form::hidden('target_cd', $form_params['target_cd']) }}
                            {{ Form::hidden('media_no', $media->media_no) }}

                            {{-- <input type="submit" value="削除" {if $media->media_no == $v->assign->outside[0].media_no or $media->media_no == $v->assign->map[0].media_no}disabled="true"{/if} /> --}}
                            {{ Form::submit('削除', ['disabled' => (count($outside) > 0 && $media->media_no == $outside[0]->media_no) || (count($map) > 0 && $media->media_no == $map[0]->media_no)]) }}
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

    {{-- {include file=$v->env->module_root|cat:'/view2/htlsmedia/_common_menu.tpl'} --}}
    @include('ctl.htl.media._common_menu')
    <!-- /Main -->

    <div class="clear">
        <hr>
    </div>

    {{-- {include file=$v->env->module_root|cat:'/views/_common/_htl_footer.tpl'} --}}
@endsection
