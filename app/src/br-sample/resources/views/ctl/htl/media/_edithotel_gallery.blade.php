{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_edithotel_gallery.tpl --}}

<p>
    <font color="cdcdcd">■</font>フォトギャラリー画像
</p>
<table border="1" cellpadding="4" cellspacing="0">
    <tr bgcolor="#EEEEFF">
        <th>設定画像</th>
    </tr>
    <tr>
        <td class="edit-image">

            {{-- TODO: インデントをちゃんとする --}}
            {{-- HACK: 10画像ごとに改行しようとしたインデントの崩れは、 flexbox で実装することで修正できると思われる --}}
            <table>
                <tr>
                    @php
                        $b_is_edit_target = true;
                        $b_already_disp_hotel_no_image = false;
                        $n_real_display_hotel_img_cnt = 0;
                    @endphp

                    {{-- {{section name=loop_galleryPhotos[$i]s_media start=0 loop=$media_count_inside}} --}}
                    @for ($i = 0; $i < $media_count_inside; $i++)
                        @if ($n_real_display_hotel_img_cnt % 10 == 0 && $n_real_display_hotel_img_cnt != 0 && $n_real_display_hotel_img_cnt == $i)
                            {{-- 10枚ごとに、テーブルを改行する --}}
                </tr>
            </table>
            <table>
                <tr>
                    @endif

                    @if ($n_real_display_hotel_img_cnt == $i && !$b_already_disp_hotel_no_image)
                        <td>
                            <table>
                                @if (!array_key_exists($i, $galleryPhotos) && !$b_already_disp_hotel_no_image)
                                    {{-- MEMO: 表示する画像が存在せず、NO IMAGE が表示されていない場合、 NO IMAGE を表示 --}}
                                    {{-- 画像設定 --}}
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
                                                {{ Form::hidden('media_type', 'hotel') }}
                                                {{ Form::hidden('label_type', '3') }}
                                                {{ Form::hidden('target_order_no', $i == 0 ? 1 : $galleryPhotos[$i - 1]->order_no + 1) }}
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
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                    </tr>
                                    {{-- /画像設定 --}}
                                    @php
                                        $b_already_disp_hotel_no_image = true;
                                        $n_real_display_hotel_img_cnt++;
                                    @endphp
                                @elseif (array_key_exists($i, $galleryPhotos))
                                    {{-- 表示する画像がある場合、画像（及び変更ボタン、外すボタン、ソートボタン）を表示 --}}
                                    {{-- 画像 --}}
                                    <tr>
                                        <td class="wrap_media_pop_view">
                                            <div class="image_box">
                                                {{-- <img border="0" src="/images/hotel/{{ $target_cd }}/trim_054/{{ $galleryPhotos[$i]->file_nm }}" width="54" height="54" title="{{ $galleryPhotos[$i]->title }}"> --}}
                                                <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $galleryPhotos[$i]->file_nm) }}" title="{{ $galleryPhotos[$i]->title }}" border="0" width="54" height="54">
                                            </div>
                                            <div class="media_pop_frame">
                                                {{-- <img border="1" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $galleryPhotos[$i]->file_nm }}" width="1" height="1" title="{{ $galleryPhotos[$i]->title }}" class="media_pop_view"> --}}
                                                <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $galleryPhotos[$i]->file_nm) }}" title="{{ $galleryPhotos[$i]->title }}" border="1" width="1" height="1">
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- 画像変更 --}}
                                    <tr>
                                        <td>
                                            {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                            {{ Form::hidden('target_cd', $target_cd) }}
                                            {{ Form::hidden('media_type', 'hotel') }}
                                            {{ Form::hidden('label_type', '3') }}
                                            {{ Form::hidden('target_order_no', $i + 1) }}
                                            {{ Form::hidden('setting_media_no', $galleryPhotos[$i]->media_no) }}
                                            {{ Form::submit('画像変更') }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>

                                    {{-- 画像を外す --}}
                                    <tr>
                                        <td>
                                            {{ Form::open(['route' => 'ctl.htl.media.remove_gallery', 'method' => 'post', 'style' => 'display:inline;']) }}
                                            {{ Form::hidden('target_cd', $target_cd) }}
                                            {{-- {{ Form::hidden('media_type', 'hotel') }} --}}
                                            {{-- {{ Form::hidden('label_type', '3') }} --}}
                                            {{-- {{ Form::hidden('target_order_no', $i + 1) }} --}}
                                            {{ Form::hidden('setting_media_no', $galleryPhotos[$i]->media_no) }}
                                            {{ Form::submit('画像を外す') }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>

                                    {{-- 並べ替え --}}
                                    <tr>
                                        <td>
                                            @if ($i != 0)
                                                {{-- ループの最初ではない, foreach を使えば $loop->first で書き換えられる --}}
                                                {{ Form::open(['route' => 'ctl.htl.media.sort_gallery', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                {{ Form::hidden('target_cd', $target_cd) }}
                                                {{ Form::hidden('media_no', $galleryPhotos[$i]->media_no) }}

                                                {{ Form::hidden('target_media_no', $galleryPhotos[$i - 1]->media_no) }}

                                                {{-- {{ Form::hidden('setting_media_no', $galleryPhotos[$i]->media_no) }} --}}
                                                {{-- {{ Form::hidden('label_type', '3') }} --}}
                                                {{ Form::submit('←') }}
                                                {{ Form::close() }}
                                            @endif

                                            @if ($i != $media_count_inside - 1 && array_key_exists($i + 1, $galleryPhotos))
                                                {{-- ループの最後でなく、 次の画像が存在する場合、並べ替えボタンを表示 --}}

                                                {{ Form::open(['route' => 'ctl.htl.media.sort_gallery', 'method' => 'post', 'style' => 'display:inline;']) }}
                                                {{ Form::hidden('target_cd', $target_cd) }}
                                                {{ Form::hidden('media_no', $galleryPhotos[$i]->media_no) }}

                                                {{ Form::hidden('target_media_no', $galleryPhotos[$i + 1]->media_no) }}

                                                {{-- {{ Form::hidden('setting_media_no', $galleryPhotos[$i]->media_no) }} --}}
                                                {{-- {{ Form::hidden('label_type', '3') }} --}}
                                                {{ Form::submit('→') }}
                                                {{ Form::close() }}
                                            @endif
                                        </td>
                                    </tr>
                                    @php $n_real_display_hotel_img_cnt++; @endphp
                                @else
                                    {{-- ignored --}}
                                @endif
                            </table>
                        </td>
                    @endif
                    {{-- {{/section}} --}}
                    @endfor
                </tr>
            </table>
        </td>
    </tr>
</table>
