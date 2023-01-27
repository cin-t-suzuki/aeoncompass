{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_edithotel_outside.tpl --}}

<p>
    <font color="cdcdcd">■</font>外観画像
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
                            @if (count($outside) === 0)
                                <tr>
                                    <td>
                                        <div class="no_image_box">
                                            <font color="ff0000">NO<br />IMAGE</font>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                        {{ Form::hidden('target_cd', $target_cd) }}
                                        {{ Form::hidden('media_type', 'hotel') }}
                                        {{ Form::hidden('label_type', '1') }}
                                        {{ Form::hidden('target_order_no', '1') }}

                                        {{ Form::submit('画像設定') }}
                                        {{ Form::close() }}
                                    <td>
                                </tr>
                            @else
                                <tr>
                                    <td class="wrap_media_pop_view">
                                        <div class="image_box">
                                            {{-- <img border="0" src="/images/hotel/{{ $target_cd }}/trim_054/{{ $outside[0]->file_nm }}" width="54" height="54" title="{{ $outside[0]->title }}"> --}}
                                            <img src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $outside[0]->file_nm) }}" title="{{ $outside[0]->title }}" border="0" width="54" height="54">
                                        </div>
                                        <div class="media_pop_frame">
                                            <img class="media_pop_view" src="{{ asset('storage/images/hotel/' . $target_cd . '/' . $outside[0]->file_nm) }}" title="{{ $outside[0]->title }}" border="1" width="1" height="1">
                                            {{-- <img border="1" src="/images/hotel/{{ $target_cd }}/trim_138/{{ $outside[0]->file_nm }}" width="1" height="1" title="{{ $outside[0]->title }}" class="media_pop_view"> --}}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ Form::open(['route' => 'ctl.htl.media.select_media', 'method' => 'get', 'style' => 'display:inline;']) }}
                                        {{ Form::hidden('target_cd', $target_cd) }}
                                        {{ Form::hidden('media_type', 'hotel') }}
                                        {{ Form::hidden('label_type', '1') }}
                                        {{ Form::hidden('target_order_no', '1') }}

                                        {{ Form::hidden('setting_media_no', $outside[0]->media_no) }}
                                        {{ Form::submit('画像変更') }}
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
