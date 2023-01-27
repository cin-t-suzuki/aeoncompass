{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/editmedia.tpl --}}

{{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='画像管理'} --}}
@extends('ctl.common._htl_base')
@section('title', '画像管理')

@section('headScript')
    {{-- {include file='./_css.tpl'} --}}
    @include('ctl.htl.media._css')
@endsection

@section('content')
    <div class="clear">
        <hr>
    </div>
    <hr width="100%" size="1">

    {{-- Main --}}
    <div>
        <p>
            {{-- {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'} --}}
            @include('ctl.common.message')
        </p>
        <p>
            <font color="cdcdcd">■</font>部屋画像情報
        </p>
        {{ Form::open(['route' => 'ctl.htl.media.update_media', 'method' => 'post', 'style' => 'display:inline;']) }}
        <table border="1" cellspacing="0" cellpadding="4">
            <tr style="background-color: #EEEEFF;">
                <th>画像</th>
                <th>詳細情報</th>
            </tr>
            <tr>
                <td>
                    {{-- <img border="0" src="/images/hotel/{{ $target_cd }}/{{ $media->file_nm }}" title="{{ $media->title }}"> --}}
                    <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $media->file_nm) }}" title="{{ $media->title }}" border="0">
                </td>
                <td>
                    <table border="1" cellspacing="0" cellpadding="4">
                        <tr>
                            <td>ファイル名</td>
                            <td>
                                <span>{{ $media->disp_file_nm }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>サイズ</td>
                            <td>{{ $media->width }}*{{ $media->height }}</td>
                        </tr>
                        <tr>
                            <td>更新日</td>
                            <td>{{ date('Y年m月d日', strtotime($media->modify_ts)) }}</td>
                        </tr>
                        <tr>
                            <td>タイトル</td>
                            <td>
                                {{-- <input type="text" name="title" value="{{ $media->title }}" maxlength="30" size="40" /> --}}
                                {{ Form::text('title', old('title', $media->title), ['size' => '40']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>画像ラベル</td>
                            <td>
                                @if ($label_cd['map'])
                                    <span style="color:#ff0000;">地図画像の為、変更できません。</span>
                                    {{ Form::hidden('label_cd[map]', '1') }}
                                @else
                                    {{ Form::checkbox('label_cd[outside]', '1', $label_cd['outside'], ['id' => 'label_outside']) }}
                                    <label for="label_outside">
                                        <font title="外観" color="#FF9999">■</font>外観
                                    </label>
                                    {{ Form::checkbox('label_cd[inside]', '1', $label_cd['inside'], ['id' => 'label_inside']) }}
                                    <label for="label_inside">
                                        <font title="フォトギャラリー" color="#99FF99">■</font>フォトギャラリー
                                    </label>
                                    {{ Form::checkbox('label_cd[room]', '1', $label_cd['room'], ['id' => 'label_room']) }}
                                    <label for="label_room">
                                        <font title="客室" color="#66CCFF">■</font>客室
                                    </label>
                                    {{ Form::checkbox('label_cd[other]', '1', $label_cd['other'], ['id' => 'label_other']) }}
                                    <label for="label_other">
                                        <font title="その他" color="#FF99FF">■</font>その他
                                    </label>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                {{ Form::hidden('target_cd', $target_cd) }}
                                {{ Form::hidden('media_no', $media_no) }}
                                {{ Form::submit('更新') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        {{ Form::close() }}
    </div>
    {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'} --}}
    @include('ctl.htl.media._common_menu')

    {{-- /Main --}}
    <div class="clear">
        <hr>
    </div>
    {{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'} --}}
@endsection
