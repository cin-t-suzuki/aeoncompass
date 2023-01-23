@extends('ctl.common._htl_base')
@section('title', '周辺情報')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelNearbyController')

@section('content')

{{-- パンくず --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
周辺情報
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')

{!! Form::open(['route' => ['ctl.htl_hotel_nearby.create'], 'method' => 'get']) !!}
<table border="1" cellspacing="0" cellpadding="4">
    <tr>
        <td colspan="2" bgcolor="#EEEEFF">
        <b>周辺情報</b>
        </td>
    </tr>
    @if(count($hotel_nearby['values']) > 0)
        <tr>
            <td>
                @foreach($hotel_nearby['values'] as $key => $value)
                    @if($loop->first)
                        {{strip_tags($value->element_nm)}}
                        </td>
                        <td>
                    @endif
                        <input type="radio" name="HotelNearby[{{strip_tags($value->element_id)}}]" value="{{strip_tags($value->element_value_id)}}" @if($value->nearbiesvalue == $value->element_value_id) checked @endif id="hotelnearby_radio{{$key}}">
                        <label for="hotelnearby_radio{{$key}}">
                            {{strip_tags($value->element_value_text)}}
                        </label>
                    @if(!$loop->last)
                        @if($value->element_id != $hotel_nearby['values'][$loop->iteration]->element_id)
                            </td>
                        </tr>
                        <tr>
                            <td>
                        {{strip_tags($hotel_nearby['values'][$loop->iteration]->element_nm)}} 
                            </td>
                            <td>
                        @endif
                    @endif
                @endforeach

            </td>
        </tr>
    @endif
</table>
@if(count($hotel_nearby['values']) > 0)
    <br>
    <input type="submit" value="変更">
@endif
<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}
@endsection