@extends('ctl.common._htl_base')
@section('title', '早割丸め設定')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelChargeRoundController')

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
早割丸め設定
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')

{!! Form::open(['route' => ['ctl.htl_hotel_charge_round.update'], 'method' => 'get']) !!}
    <table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td  bgcolor="#EEEEFF" >金額切り捨て桁</td>
        <td>
            <label>
                <input TYPE="radio" NAME="Hotel_Control[charge_round]" VALUE="1" @if($hotel_control['charge_round'] == 1 || empty($hotel_status['entry_status'])) checked @endif id="i5">
                    <label for="i5">
                        1の位で丸める 
                    </label>
            </label>
            <label>
                <input TYPE="radio" NAME="Hotel_Control[charge_round]" VALUE="10" @if($hotel_control['charge_round'] == 10) checked @endif id="i6">
                    <label for="i6">
                        10の位で丸める
                    </label>
            </label>
            <label>
                <input TYPE="radio" NAME="Hotel_Control[charge_round]" VALUE="100" @if($hotel_control['charge_round'] == 100) checked @endif id="i7">
                    <label for="i7">
                        100の位で丸める
                    </label>
            </label>
        </td>
    </tr>
    </table>
    <br>
    <input type="submit" value="変更">
    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}
@endsection