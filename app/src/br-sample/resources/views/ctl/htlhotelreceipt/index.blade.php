@extends('ctl.common._htl_base')
@section('title', '領収書発行ポリシー')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelReceiptController')

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
領収書発行ポリシー
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

お客様が宿泊料金の支払いにポイントを利用された場合の領収書発行ポリシーをご選択ください。

{!! Form::open(['route' => ['ctl.htl_hotel_receipt.update'], 'method' => 'get']) !!}
    <table border="1" cellspacing="0" cellpadding="4">
    <tr>
        <td colspan="2" bgcolor="#EEEEFF">
            <b>領収書発行ポリシー</b>
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="Hotel_Receipt[receipt_policy]" value="1" @if(!isset($hotel_receipt['receipt_policy']) || $hotel_receipt['receipt_policy'] == 1) checked @endif>
        </td>
        <td>
            {{config('default_receipt_policy.receipt_policy.pattern1')}}
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="Hotel_Receipt[receipt_policy]" value="2" @if(isset($hotel_receipt['receipt_policy']) && $hotel_receipt['receipt_policy'] == 2) checked @endif>
        </td>
        <td>
            {{config('default_receipt_policy.receipt_policy.pattern2')}}
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="Hotel_Receipt[receipt_policy]" value="4" @if(isset($hotel_receipt['receipt_policy']) && $hotel_receipt['receipt_policy'] == 4) checked @endif
        </td>
        <td>
            {{config('default_receipt_policy.receipt_policy.pattern3')}}
        </td>
    </tr>
    </table>
<br>
<input type="submit" value="選択した内容にポリシーを変更する">
<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}
@endsection