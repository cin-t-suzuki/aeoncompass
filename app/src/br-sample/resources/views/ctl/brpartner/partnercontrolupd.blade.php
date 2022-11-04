@section('title', 'PARTNER')
@include('ctl.common.base')

{{-- メッセージ --}}
@include('ctl.common.message',['guides'=>$messages["guides"]])

{!! Form::open(['route' => ['ctl.brpartner.partnercontroledt'], 'method' => 'post']) !!}
  @foreach ($views->partner_control_value as $key => $value)
  {{-- 下記、value(item)の書き換え問題ない？ --}}
  <input type="hidden" name="{{$key}}" value="{{$value}}" /> 
  @endforeach
  <input type='hidden' name='return_flg' value='true'>
  {{-- ↓がないとnmがとれないので追加したがいいか？（元ソースにはない） --}}
  <input type='hidden' name='partner_nm' value='{{$views->partners["partner_nm"]}}'>
  <INPUT TYPE="submit" VALUE="内容確認">
{!! Form::close() !!}

@section('title', 'footer')
@include('ctl.common.footer')