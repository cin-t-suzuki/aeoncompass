{{-- {* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='利用可能カード'}
{* header end *} --}}
@extends('ctl.common._htl_base')
@section('title', '利用可能カード')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelCardController')

@section('content')

{{-- {* サブメニュー *}
<a href="{$v->env.source_path}{$v->env.module}/htltop/index/target_cd/{$v->assign->target_cd}">
  メインメニュー
</a>&nbsp;&gt;&nbsp;
<a href="{$v->env.source_path}{$v->env.module}/htlhotel/show/target_cd/{$v->assign->target_cd}">
  施設情報詳細
</a>&nbsp;&gt;&nbsp;
利用可能カード
<br>
<br> --}}

{{-- {* サブメニュー *} --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' => $target_cd]) }}">
  メインメニュー
</a>&nbsp;&gt;&nbsp;
<a href="{{ route('ctl.htl_hotel.show', ['target_cd' => $target_cd]) }}">
  施設情報詳細
</a>&nbsp;&gt;&nbsp;
利用可能カード
<br>
<br>


{{-- {* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'} --}}
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

{{ Form::open(['route' => 'ctl.htl_hotel_card.update', 'method' => 'post']) }}
@if(count($a_hotelcard['values']) > 0)
  <table border="1" cellspacing="0" cellpadding="5">
  @php
      $gyocount = 1;
  @endphp
  @foreach($a_hotelcard['values'] as $key => $values)
    @if($loop->first)
        <tr>
    @endif
        <td @if(isset($a_chk[$key]) && $a_chk[$key] == true) bgcolor="#EEEEEE" @endif>
            <input type="checkbox" name="chk[]" value="{{strip_tags($values->card_id)}}" @if(isset($a_chk[$key]) && $a_chk[$key] == true) checked @endif id="hotelcard_chk{{$key}}}">
              <label for="hotelcard_chk{{$key}}">
                {{strip_tags($values->card_nm)}}
              </label>
        </td>
      @if ($loop->count % 5 == 0)
        </tr>
            @if(count($a_hotelcard['values']) - $loop->count <= 5)
              @php        
                $gyocount = $gyocount + 1
              @endphp
               <tr>
            @else
              <tr>
              @php        
                $gyocount = $gyocount + 1
              @endphp
            @endif
      @else
        </tr>
      @endif
  @endforeach
    </tr>
    <tr align="center">
        <td colspan="5">
          <input type="submit" value="変更">
        </td>
    </tr>
  </table>
@endif  
<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">

{{ Form::close() }}
@endsection