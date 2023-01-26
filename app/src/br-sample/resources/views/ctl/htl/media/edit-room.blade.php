{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/editroom.tpl --}}

{{-- {include file=$v->env['module_root']|cat:'/views/_common/_htl_header.tpl' title='画像管理'} --}}
@extends('ctl.common._htl_base')
@section('title', '画像管理')

@section('headScript')
    @include('ctl.htl.media._css')
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
        <p>
            <font color="cdcdcd">■</font>部屋画像
        </p>
        {{--
            HACK: インデントちゃんとしたい 
                1行に表示する横並びの画像数を制限（10枚まで）してるようだが、
                flexbox で実装したほうがでふさわしいと思われる
        --}}
        <table class="table-room" style="width:1200px;">
            <tr>
                <th style="width:20%;">部屋名</th>
                <th>部屋タイプ名</th>
                <th>設定画像</th>
            </tr>
            <tr>
                {{-- 部屋名 --}}
                <td class="edit-image" style="width:25%;">
                    {{ $room->room_nm }}
                </td>

                {{-- 部屋タイプ --}}
                <td class="edit-image" style="width:15%;">
                    {{-- {include file=$v->env['module_root']|cat:'/view2/_common/_room_type.tpl' room_type=$room->room_type} --}}
                    @include('ctl.common.room_type', ['room_type' => $room->room_type])
                </td>
                <td class="edit-image" style="width:50%;">
                    <table>
                        <tr>
                            @php
                                $b_is_edit_target = true;
                                $b_already_disp_room_no_image = false;
                                $n_real_display_room_img_cnt = 0;
                            @endphp
                            @for ($i = 0; $i < $media_count_room; $i++)
                                @if ($n_real_display_room_img_cnt == $i && !$b_already_disp_room_no_image)
                                    <td>
                                        <table>
                                            @if (!array_key_exists($i, $room->medias) && !$b_already_disp_room_no_image)
                                                <tr>
                                                    <td>
                                                        <div class="no_image_box" style="padding:2px;text-align: center; font-size: 15px;">
                                                            <font color="ff0000">NO<br />IMAGE</font>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if ($b_is_edit_target)
                                                            {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                                            {{ Form::hidden('target_cd', $target_cd) }}
                                                            {{ Form::hidden('room_id', $room_id) }}
                                                            {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                            {{ Form::hidden('target_order_no', $i == 0 ? 1 : $room->medias[$i - 1]->order_no + 1) }}

                                                            {{ Form::hidden('media_type', 'room') }}
                                                            {{ Form::submit('画像設定') }}
                                                            {{ Form::close() }}
                                                            @php
                                                                $b_is_edit_target = false;
                                                            @endphp
                                                        @else
                                                            <br />
                                                        @endif
                                                    <td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <br />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <br />
                                                    </td>
                                                </tr>
                                                @php
                                                    $b_already_disp_room_no_image = true;
                                                    $n_real_display_room_img_cnt = $n_real_display_room_img_cnt + 1;
                                                @endphp
                                            @elseif (array_key_exists($i, $room->medias))
                                                <tr>
                                                    <td class="wrap_media_pop_view">
                                                        <div class="image_box">
                                                            {{-- <img src="/images/hotel/{{ $target_cd }}/trim_054/{{ $room->medias[$i]->file_nm }}" title="{{ $room->medias[$i]->title }}" border="0" width="54" height="54"> --}}
                                                            <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $room->medias[$i]->file_nm) }}" title="{{ $room->medias[$i]->title }}" border="0" width="54" height="54">
                                                        </div>
                                                        <div class="media_pop_frame">
                                                            {{-- <img class="media_pop_view" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $room->medias[$i]->file_nm }}" title="{{ $room->medias[$i]->title }}" border="0" width="1" height="1"> --}}
                                                            <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $room->medias[$i]->file_nm) }}" title="{{ $room->medias[$i]->title }}" border="0" width="1" height="1">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                                        {{ Form::hidden('target_cd', $target_cd) }}
                                                        {{ Form::hidden('room_id', $room_id) }}
                                                        {{ Form::hidden('setting_media_no', $room->medias[$i]->media_no) }}
                                                        {{ Form::hidden('target_order_no', $room->medias[$i]->order_no) }}

                                                        {{ Form::hidden('media_type', 'room') }}
                                                        {{ Form::submit('画像変更') }}
                                                        {{ Form::close() }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ Form::open(['route' => 'ctl.htl.media.remove_room', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                        {{ Form::hidden('target_cd', $target_cd) }}
                                                        {{ Form::hidden('room_id', $room_id) }}
                                                        {{ Form::hidden('setting_media_no', $room->medias[$i]->media_no) }}

                                                        {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                        {{ Form::hidden('media_type', 'room') }}
                                                        {{ Form::submit('画像を外す') }}
                                                        {{ Form::close() }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if ($i != 0)
                                                            {{ Form::open(['route' => 'ctl.htl.media.sort_room', 'method' => 'post', 'style' => 'display:inline;']) }}

                                                            {{ Form::hidden('target_cd', $target_cd) }}
                                                            {{ Form::hidden('room_id', $room_id) }}
                                                            {{ Form::hidden('source_media_no', $room->medias[$i]->media_no) }}
                                                            {{ Form::hidden('target_media_no', $room->medias[$i - 1]->media_no) }}

                                                            {{-- {{ Form::hidden('setting_media_no', $room->medias[$i]->media_no) }} --}}
                                                            {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                            {{-- {{ Form::hidden('edit_order_no', $room->medias[$i]->order_no_minus) }} --}}
                                                            {{-- {{ Form::hidden('media_type', 'room') }} --}}
                                                            {{ Form::submit('←') }}
                                                            {{ Form::close() }}
                                                        @endif
                                                        @if ($i + 1 < $media_count_room && array_key_exists($i + 1, $room->medias))
                                                            {{ Form::open(['route' => 'ctl.htl.media.sort_room', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                            {{ Form::hidden('target_cd', $target_cd) }}
                                                            {{ Form::hidden('room_id', $room_id) }}
                                                            {{ Form::hidden('source_media_no', $room->medias[$i]->media_no) }}
                                                            {{ Form::hidden('target_media_no', $room->medias[$i + 1]->media_no) }}

                                                            {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                            {{-- {{ Form::hidden('edit_order_no', $room->medias[$i]->order_no_plus) }} --}}
                                                            {{-- {{ Form::hidden('media_type', 'room') }} --}}
                                                            {{-- {{ Form::hidden('setting_media_no', $room->medias[$i]->media_no) }} --}}
                                                            {{ Form::submit('→') }}
                                                            {{ Form::close() }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $n_real_display_room_img_cnt = $n_real_display_room_img_cnt + 1;
                                                @endphp
                                            @endif
                                        </table>
                                        @if ($n_real_display_room_img_cnt % 10 == 0 && $i + 1 < $media_count_room)
                    </table>
                    <table>
                        @endif
                </td>
                @endif
                @endfor
            </tr>
        </table>
        </td>
        </tr>
        </table>
        <p>
            <font color="cdcdcd">■</font>関連プラン画像
        </p>
        @if (count($plans) === 0)
            <font color="ff0000">設定されているプランはありません</font>
        @else
            <table class="table-plan" style="width:1200px;">
                <tr>
                    <th>プラン名</th>
                    <th>設定画像</th>
                </tr>
                @php
                    $n_real_display_plan_img_cnt = 0;
                @endphp
                @foreach ($plans as $plan)
                    <tr>
                        <td class="edit-image" style="width:25%;">
                            {{ $plan->plan_nm }}
                        </td>
                        <td class="edit-image">
                            <table>
                                <tr>
                                    @php
                                        $b_already_disp_plan_no_image = false;
                                    @endphp
                                    @for ($i = 0; $i < $media_count_plan; $i++)
                                        @if (!array_key_exists($i, $plan->medias) && !$b_already_disp_plan_no_image && $i == 0)
                                            <td class="edit-image2 wrap_media_pop_view">
                                                <div class="no_image_box" style="padding:2px;text-align: center; font-size: 15px;">
                                                    <font color="ff0000">NO<br />IMAGE</font>
                                                </div>
                                            </td>
                                            @php
                                                $b_already_disp_plan_no_image = true;
                                                $n_real_display_plan_img_cnt = $n_real_display_plan_img_cnt + 1;
                                            @endphp
                                        @elseif (array_key_exists($i, $plan->medias))
                                            <td class="edit-image2 wrap_media_pop_view">
                                                <div class="image_box">
                                                    {{-- <img src="/images/hotel/{{ $target_cd }}/trim_054/{{ $plan->medias[$i]->file_nm }}" title="{{ $plan->medias[$i]->title }}" border="0" width="54" height="54"> --}}
                                                    <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $plan->medias[$i]->file_nm) }}" title="{{ $plan->medias[$i]->title }}" border="0" width="54" height="54">
                                                </div>
                                                <div class="media_pop_frame">
                                                    {{-- <img class="media_pop_view" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $plan->medias[$i]->file_nm }}" title="{{ $plan->medias[$i]->title }}" border="1" width="1" height="1"> --}}
                                                    <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $plan->medias[$i]->file_nm) }}" title="{{ $plan->medias[$i]->title }}" border="1" width="1" height="1">
                                                </div>
                                            </td>
                                            @php
                                                $n_real_display_plan_img_cnt = $n_real_display_plan_img_cnt + 1;
                                            @endphp
                                        @endif
                                        @if ($n_real_display_plan_img_cnt % 10 == 0 && $i + 1 < $media_count_plan)
                                </tr>
                                <tr>
                @endif
        @endfor
        </tr>

        </table>
        </td>
        </tr>
        @php
            $n_real_display_plan_img_cnt = 0;
        @endphp
        @endforeach
        </table>
        @endif
    </div>
    {{-- {include file=$v->env['module_root']|cat:'/view2/htlsmedia/_common_menu.tpl'} --}}
    @include('ctl.htl.media._common_menu')
    <!-- /Main -->

    <div class="clear">
        <hr>
    </div>
    {{-- {include file=$v->env['module_root']|cat:'/views/_common/_htl_footer.tpl'} --}}
@endsection
