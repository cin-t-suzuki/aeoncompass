@extends('ctl.common._htl_base')
@section('title', 'サービス')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelServiceController')

@section('content')

<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
サービス
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

{!! Form::open(['route' => ['ctl.htl_hotel_service.create'], 'method' => 'get']) !!}
    <table border="1" cellspacing="0" cellpadding="4">
    <tr>
        <td colspan="2" bgcolor="#EEEEFF">
        <b>サービス</b>
        </td>
    </tr>
    
    @if(count($hotel_service['values']) > 0)
    <tr>
        <td>
            @foreach($hotel_service['values'] as $key => $value)        
                @if($loop->first)
                        {{strip_tags($value->element_nm)}}
                    </td>
                    <td>
                @endif
                <input type="radio" name="HotelService[{{strip_tags($value->element_id)}}]" value="{{strip_tags($value->element_value_id)}}" @if($value->servicevalue == $value->element_value_id) checked @endif id="hotelservice_radio{{$key}}">
                <label for="hotelservice_radio{{$key}}">
                        {{strip_tags($value->element_value_text)}}
                </label>
                @if(!$loop->last)
                    @if($value->element_id != $hotel_service['values'][$loop->iteration]->element_id)
                    </td>
                </tr>
                <tr>
                    <td>
                        {{strip_tags($hotel_service['values'][$loop->iteration]->element_nm)}}
                    </td>
                    <td>
                    @endif
                @endif
            @endforeach
        </td>
    </tr>
    @endif
    </table>
    @if(count($hotel_service['values']) > 0)
        <br>
        <input type="submit" value="変更">
    @endif
        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}
@endsection