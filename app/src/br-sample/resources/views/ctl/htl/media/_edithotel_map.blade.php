{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_edithotel_map.tpl --}}

<p>
    <font color="cdcdcd">■</font>地図画像
</p>
<table border="1" cellpadding="4" cellspacing="0">
    <tr bgcolor="#EEEEFF">
        <th>設定画像</th>
    </tr>
    <tr>
        <td class="edit-image">
            <table>
                <tr>
                    <td>
                        <table>
                            @if (count($map) === 0)
                                <tr>
                                    <td>
                                        <div class="no_image_box">
                                            <font color="ff0000">NO<br />IMAGE</font>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{-- <form action="/ctl/htlsmedia/selectmedia/" method="post" style="display:inline;"> --}}
                                        {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                            {{ Form::hidden('target_cd', $target_cd) }}
                                            {{ Form::hidden('media_type', 'hotel') }}
                                            {{ Form::hidden('label_type', '2') }}
                                            {{ Form::hidden('target_order_no', '1') }}

                                            {{ Form::hidden('label_cd[map]', '1') }}
                                            {{ Form::submit('画像設定') }}
                                        {{-- </form> --}}
                                        {{ Form::close() }}
                                    <td>
                                </tr>
                            @else
                                <tr>
                                    <td class="wrap_media_pop_view">
                                        <div class="image_box">
                                            {{-- <img border="0" src="/images/hotel/{{ $target_cd }}/trim_054/{{ $map[0]->file_nm }}" width="54" height="54" title="{{ $map[0]->title }}"> --}}
                                            <img border="0" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $map[0]->file_nm) }}" width="54" height="54" title="{{ $map[0]->title }}">
                                        </div>
                                        <div class="media_pop_frame">
                                            {{-- <img border="1" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $map[0]->file_nm }}" width="1" height="1" title="{{ $map[0]->title }}" class="media_pop_view"> --}}
                                            <img border="1" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $map[0]->file_nm) }}" width="1" height="1" title="{{ $map[0]->title }}" class="media_pop_view">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{-- <form action="/ctl/htlsmedia/selectmedia/" method="post" style="display:inline;"> --}}
                                        {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                            {{ Form::hidden('target_cd', $target_cd) }}
                                            {{ Form::hidden('media_type', 'hotel') }}
                                            {{ Form::hidden('label_type', '2') }}
                                            {{ Form::hidden('target_order_no', '1') }}

                                            {{ Form::hidden('setting_media_no', $map[0]->media_no) }}
                                            {{ Form::hidden('label_cd[map]', '1') }}
                                            {{ Form::submit('画像変更') }}
                                        {{-- </form> --}}
                                        {{ Form::close() }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
