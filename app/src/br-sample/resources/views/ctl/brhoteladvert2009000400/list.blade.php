{{-- acceptance_status_flg=false --}}
@section('title', '迷わずここ！(2009000400 )')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brhoteladvert2009000400.new'], 'method' => 'post']) !!} 
<input type="submit" value="新規作成" />
{!! Form::close() !!}
@foreach ($views->hotel_adverts as $hotel_advert)
@if ($loop->first)
  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td bgcolor="#eeffee">都道府県名称</td>
      <td bgcolor="#eeffee">掲載順序</td>
      <td bgcolor="#eeffee">施設コード</td>
      <td bgcolor="#eeffee">施設名称</td>
      <td bgcolor="#eeffee">掲載開始年月日</td>
      <td bgcolor="#eeffee">掲載最終年月日</td>
      <td bgcolor="#eeffee">掲載金額</td>
      <td bgcolor="#eeffee"><br></td>
    </tr>
@endif
{{-- 有効でないデータ --}}
@if ($hotel_advert['advert_status'] == 0)
    <tr style="background-color:#ccc">
{{-- 更新完了後の場合は対象データに色を付ける --}}
{{-- ??null追記でいいか --}}
@elseif ($hotel_advert['record_id'] == ($views->record_id??null))
    <tr style="background-color:#fef">
{{-- 通常 --}}
@else
    <tr>
@endif
      <td>{{$hotel_advert['pref_nm']}}</td>
      <td>{{$hotel_advert['advert_order']}}</td>
      <td>{{$hotel_advert['hotel_cd']}}</td>
      <td>{{$hotel_advert['hotel_nm']}}</td>
      {{-- 書き換え要確認 *1は何か --}}
      {{-- @if ($v->helper->date->set($hotel_advert['advert_s_ymd*1']))@endif --}}
      {{-- {if v->helper->date->set($hotel_advert['advert_s_ymd*1)}{/if} --}}
      <td>@include ('ctl.common._date',['timestamp' => $hotel_advert['advert_s_ymd'] , 'format' => 'ymd'])</td>
      <td>@include ('ctl.common._date',['timestamp' => $hotel_advert['advert_e_ymd'] , 'format' => 'ymd'])</td>
      <td align="right">{{number_format($hotel_advert['advert_charge'])}}</td>
      {!! Form::open(['route' => ['ctl.brhoteladvert2009000400.edit'], 'method' => 'post']) !!} 
      <td align="right"><input type="submit" value=" 編集 " /></td>
      <input type="hidden" name="hotel_advert[record_id]" value="{{$hotel_advert['record_id']}}" />
      {!! Form::close() !!}
    </tr>

@if ($loop->last)
  </table>
@endif
@endforeach

@section('title', 'footer')
@include('ctl.common.footer')
