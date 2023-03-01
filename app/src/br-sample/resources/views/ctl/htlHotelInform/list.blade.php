@extends('ctl.common._htl_base')
@section('title', '施設連絡事項')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelInformController')

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
施設連絡事項
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

<br>
{!! Form::open(['route' => ['ctl.htl_hotel_inform.new'], 'method' => 'post']) !!}
    <input type="submit" value="新規登録">
    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}

<table border="1" cellspacing="0" cellpadding="4">
    {{-- キャンセルデータ情報 --}}
    @foreach($a_hotel_inform_cancel['values'] as $values)
        <tr>
            @if($loop->first)
            <td bgcolor="#EEEEFF" rowspan={{count($a_hotel_inform_cancel['values'])}}>
                注意事項
            </td>
            @endif
            {!! Form::open(['route' => ['ctl.htl_hotel_inform.changeinformorder'], 'method' => 'post']) !!}
            <td align="center" nowrap>
            @if(count($a_hotel_inform_cancel['values']) > 1)
                {{-- ループの初めの場合 --}}
                @if($loop->first)
                    @if(count($a_hotel_inform_cancel['values']) > 1)
                <input type="submit" name="order[down]" value=" ↓ ">
                @endif
                {{-- ループが最後の場合 --}}
                @elseif($loop->last)
                <input type="submit" name="order[up]" value=" ↑ ">
                @else
                <input type="submit" name="order[up]" value=" ↑ ">
                <input type="submit" name="order[down]" value=" ↓ ">
                @endif
            @else
            &nbsp;
            @endif
        </td>
        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
        <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($values->branch_no)}}">
        {!! Form::close() !!}
        <td>
            {{strip_tags(str_replace("\n", "<br />", $values->inform,))}}
        </td>
        <td>
            <table cellspacing="0" cellpadding="0">
            <tr>
                {!! Form::open(['route' => ['ctl.htl_hotel_inform.edit'], 'method' => 'post']) !!}
                <td>
                    <input type="submit" value="編集">
                    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                    <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($values->branch_no)}}">
                    <input type="hidden" name="HotelInform[order_no]" value="{{strip_tags($values->order_no)}}">
                    <input type="hidden" name="HotelInform[inform]" value="{{strip_tags($values->inform)}}">
                <td>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['ctl.htl_hotel_inform.deleteinform'], 'method' => 'post']) !!}
                <td>
                    <input type="submit" value="削除">
                    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                    <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($values->branch_no)}}">
                    <input type="hidden" name="HotelInform[order_no]" value="{{strip_tags($values->order_no)}}">
                    <input type="hidden" name="HotelInform[inform]" value="{{strip_tags($values->inform)}}">
                </td>
                {!! Form::close() !!}
                </td>
            </tr>
        </table>
        </td>
        </tr>
    @endforeach
    {{-- フリーデータ情報 --}}
    @foreach($a_hotel_inform_free['values'] as $values)
        <tr>
            @if($loop->first)
                <td bgcolor="#EEEEFF" rowspan={{count($a_hotel_inform_free['values'])}}>
                    その他記入欄
                </td>
            @endif
        {!! Form::open(['route' => ['ctl.htl_hotel_inform.changeotherorder'], 'method' => 'post']) !!}
        <td align="center">
        @if(count($a_hotel_inform_free['values']) > 1)
                {{-- ループの初めの場合 --}}
                @if($loop->first)
                    @if($a_hotel_inform_free['values'] > 1)
                <input type="submit" name="order[down]" value=" ↓ ">
                    @endif
                {{--ループが最後の場合 --}}
                @elseif($loop->last)
                <input type="submit" name="order[up]" value=" ↑ ">
                @else
                <input type="submit" name="order[up]" value=" ↑ ">
                <input type="submit" name="order[down]" value=" ↓ ">
                @endif
            @else
            &nbsp;
            @endif
        </td>
        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
        <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($values->branch_no)}}">
        {!! Form::close() !!}
        <td>
            {{strip_tags(str_replace("\n", "<br />", $values->inform,))}}
        </td>
        <td>
            <table cellspacing="0" cellpadding="0">
            <tr>
                {!! Form::open(['route' => ['ctl.htl_hotel_inform.edit'], 'method' => 'post']) !!}
                <td>
                <input type="submit" value="編集">
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($values->branch_no)}}">
                <input type="hidden" name="HotelInform[order_no]" value="{{strip_tags($values->order_no)}}">
                <input type="hidden" name="HotelInform[inform]" value="{{strip_tags($values->inform)}}">
                </td>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['ctl.htl_hotel_inform.deleteother'], 'method' => 'post']) !!}
                <td>
                <input type="submit" value="削除">
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($values->branch_no)}}">
                <input type="hidden" name="HotelInform[order_no]" value="{{strip_tags($values->order_no)}}">
                <input type="hidden" name="HotelInform[inform]" value="{{strip_tags($values->inform)}}">
                </td>
                {!! Form::close() !!}
            </tr>
            </table>
        </td>
        </tr>
    @endforeach
</table>
@endsection