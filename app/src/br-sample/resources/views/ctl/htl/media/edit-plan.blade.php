{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/editplan.tpl --}}

{{-- {include file=$v->env['module_root']|cat:'/views/_common/_htl_header.tpl' title='プラン画像設定'} --}}
@extends('ctl.common._htl_base')
@section('title', 'プラン画像設定')

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
            <font color="cdcdcd">■</font>プラン画像
        </p>
        <table class="table-plan" style="width:1200px;">
            <tr>
                <th>プラン名</th>
                <th>設定画像</th>
            </tr>
            <tr>
                <td class="edit-image" style="width:30%;">
                    {{ $plan->plan_nm }}
                </td>
                <td class="edit-image">
                    <table>
                        <tr>
                            @php
                                $b_is_edit_target = true;
                                $b_already_disp_plan_no_image = false;
                                $n_real_display_plan_img_cnt = 0;
                            @endphp
                            @for ($i = 0; $i < $plan_media_limit; $i++)
                                @if ($n_real_display_plan_img_cnt == $i && !$b_already_disp_plan_no_image)
                                    <td>
                                        <table>
                                            {{-- MEMO: ↓ もとは is_empty() --}}
                                            @if (!array_key_exists($i, $plan->medias) && !$b_already_disp_plan_no_image)
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
                                                            {{ Form::hidden('plan_id', $plan_id) }}
                                                            {{ Form::hidden('target_order_no', $i == 0 ? 1 : $plan->medias[$i - 1]->order_no + 1) }}

                                                            {{ Form::hidden('media_type', 'plan') }}
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
                                                    $b_already_disp_plan_no_image = true;
                                                    $n_real_display_plan_img_cnt = $n_real_display_plan_img_cnt + 1;
                                                @endphp
                                            @elseif (array_key_exists($i, $plan->medias))
                                                <tr>
                                                    <td class="wrap_media_pop_view">
                                                        <div class="image_box">
                                                            {{-- <img src="/images/hotel/{{ $target_cd }}/trim_054/{{ $plan->medias[$i]->file_nm }}" title="{{ $plan->medias[$i]->title }}" border="0" width="54" height="54"> --}}
                                                            <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $plan->medias[$i]->file_nm) }}" title="{{ $plan->medias[$i]->title }}" border="0" width="54" height="54">
                                                        </div>
                                                        <div class="media_pop_frame">
                                                            {{-- <img class="media_pop_view" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $plan->medias[$i]->file_nm }}" title="{{ $plan->medias[$i]->title }}" border="1" width="1" height="1"> --}}
                                                            <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $plan->medias[$i]->file_nm) }}" title="{{ $plan->medias[$i]->title }}" border="1" width="1" height="1">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                                        {{ Form::hidden('target_cd', $target_cd) }}
                                                        {{ Form::hidden('plan_id', $plan_id) }}
                                                        {{ Form::hidden('target_order_no', $plan->medias[$i]->order_no) }}

                                                        {{ Form::hidden('setting_media_no', $plan->medias[$i]->media_no) }}

                                                        {{ Form::hidden('media_type', 'plan') }}
                                                        {{ Form::submit('画像変更') }}
                                                        {{ Form::close() }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ Form::open(['route' => 'ctl.htl.media.remove_plan', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                        {{ Form::hidden('target_cd', $target_cd) }}
                                                        {{ Form::hidden('plan_id', $plan_id) }}
                                                        {{ Form::hidden('setting_media_no', $plan->medias[$i]->media_no) }}

                                                        {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                        {{-- {{ Form::hidden('media_type', 'plan') }} --}}
                                                        {{ Form::submit('画像を外す') }}
                                                        {{ Form::close() }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if (!$i == 0)
                                                            {{ Form::open(['route' => 'ctl.htl.media.sort_plan', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                            {{ Form::hidden('target_cd', $target_cd) }}
                                                            {{ Form::hidden('plan_id', $plan_id) }}
                                                            {{ Form::hidden('source_media_no', $plan->medias[$i]->media_no) }}
                                                            {{ Form::hidden('target_media_no', $plan->medias[$i - 1]->media_no) }}

                                                            {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                            {{-- {{ Form::hidden('media_type', 'plan') }} --}}
                                                            {{ Form::submit('←') }}
                                                            {{ Form::close() }}
                                                        @endif
                                                        @if ($i + 1 < $plan_media_limit && array_key_exists($i + 1, $plan->medias))
                                                            {{ Form::open(['route' => 'ctl.htl.media.sort_plan', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                            {{ Form::hidden('target_cd', $target_cd) }}
                                                            {{ Form::hidden('plan_id', $plan_id) }}
                                                            {{ Form::hidden('source_media_no', $plan->medias[$i]->media_no) }}
                                                            {{ Form::hidden('target_media_no', $plan->medias[$i + 1]->media_no) }}

                                                            {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                                            {{-- {{ Form::hidden('media_type', 'plan') }} --}}
                                                            {{ Form::submit('→') }}
                                                            {{ Form::close() }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $n_real_display_plan_img_cnt = $n_real_display_plan_img_cnt + 1;
                                                @endphp
                                            @endif
                                        </table>
                                        @if ($n_real_display_plan_img_cnt % 10 == 0 && $i + 1 < $plan_media_limit)
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
            <font color="cdcdcd">■</font>関連部屋画像
        </p>
        @if (count($rooms) === 0)
            <font color="ff0000">設定されている部屋はありません</font>
        @else
            <table class="table-room" style="width:1200px;">
                <tr>
                    <th>部屋名</th>
                    <th>設定画像</th>
                </tr>
                @php
                    $n_real_display_room_img_cnt = 0;
                @endphp
                @foreach ($rooms as $room)
                    <tr>
                        <td class="edit-image" style="width:30%;">
                            {{ $room->room_nm }}
                        </td>
                        <td class="edit-image">
                            <table>
                                <tr>
                                    @php
                                        $b_already_disp_room_no_image = false;
                                    @endphp
                                    @for ($i = 0; $i < $room_media_limit; $i++)
                                        @if (!array_key_exists($i, $room->medias) && !$b_already_disp_room_no_image && $i == 0)
                                            <td class="edit-image2 wrap_media_pop_view">
                                                <div class="no_image_box" style="padding:2px;text-align: center; font-size: 15px;">
                                                    <font color="ff0000">NO<br />IMAGE</font>
                                                </div>
                                            </td>
                                            @php
                                                $b_already_disp_room_no_image = true;
                                                $n_real_display_room_img_cnt = $n_real_display_room_img_cnt + 1;
                                            @endphp
                                        @elseif (array_key_exists($i, $room->medias))
                                            <td class="edit-image2 wrap_media_pop_view">
                                                <div class="image_box">
                                                    {{-- <img src="/images/hotel/{{ $target_cd }}/trim_054/{{ $room->medias[$i]->file_nm }}" title="{{ $room->medias[$i]->title }}" border="0" width="54" height="54"> --}}
                                                    <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $room->medias[$i]->file_nm) }}" title="{{ $room->medias[$i]->title }}" border="0" width="54" height="54">
                                                </div>
                                                <div class="media_pop_frame">
                                                    {{-- <img class="media_pop_view" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $room->medias[$i]->file_nm }}" title="{{ $room->medias[$i]->title }}" border="1" width="1" height="1"> --}}
                                                    <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $room->medias[$i]->file_nm) }}" title="{{ $room->medias[$i]->title }}" border="1" width="1" height="1">
                                                </div>
                                            </td>
                                            @php
                                                $n_real_display_room_img_cnt = $n_real_display_room_img_cnt + 1;
                                            @endphp
                                        @endif
                                        @if ($n_real_display_room_img_cnt % 10 == 0 && $i + 1 < $room_media_limit)
                                </tr>
                                <tr>
                @endif
        @endfor
        </tr>
        </table>
        </td>
        </tr>
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
