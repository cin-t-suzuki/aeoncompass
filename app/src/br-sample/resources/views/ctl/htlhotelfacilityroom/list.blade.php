@extends('ctl.common._htl_base')
@section('title', '部屋設備')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelFacilityRoomController')

@section('content')

{{-- パンくず --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
部屋設備
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')
{!! Form::open(['route' => ['ctl.htl_hotel_facility_room.create'], 'method' => 'get']) !!}
    <table border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td colspan="2" bgcolor="#EEEEFF">
            <b>部屋設備</b>
            </td>
        </tr>
        @if(count($hotel_facility_room['values']) > 0)
        <tr>
            <td>
                @foreach($hotel_facility_room['values'] as $key => $value)
                    @if($loop->first)
                        {{strip_tags($value->element_nm)}}
                        </td>
                        <td>
                    @endif
                    <input type="radio" name="HotelFacilityRoom[{{strip_tags($value->element_id)}}]" value="{{strip_tags($value->element_value_id)}}" @if($value->facilityroomvalue == $value->element_value_id) checked @endif id ="hotelffacilityroom_radio{{$key}}">
                    <label for="hotelffacilityroom_radio{{$key}}">
                        {{strip_tags($value->element_value_text)}}
                    </label>
                    @if(!$loop->last)
                        @if($value->element_id != $hotel_facility_room['values'][$loop->iteration]->element_id)
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{strip_tags($hotel_facility_room['values'][$loop->iteration]->element_nm)}}
                            </td>
                            <td>
                        @endif
                    @endif
                @endforeach
            </td>
        </tr>
        @endif
    </table>
    @if(count($hotel_facility_room['values']) > 0)
        <br>
        <input type="submit" value="変更">
    @endif
<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}
@endsection