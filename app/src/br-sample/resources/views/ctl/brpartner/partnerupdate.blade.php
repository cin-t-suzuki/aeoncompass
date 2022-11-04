@section('title', 'PARTNER')
@include('ctl.common.base')

{{-- メッセージ/TODO 他と書き方違う --}}
@section('message')
@include('ctl.common.message',['guides'=>$messages["guides"]])


{!! Form::open(['route' => ['ctl.brpartner.partnerconf'], 'method' => 'post']) !!}
  <input type="hidden" name="partner_cd" value="{{$views->partner_value["partner_cd"]}}" />
  <input type="hidden" name="partner_nm" value="{{$views->partner_value["partner_nm"]}}" />
  <input type="hidden" name="system_nm" value="{{$views->partner_value["system_nm"]}}" />
  <input type="hidden" name="partner_ns" value="{{$views->partner_value["partner_ns"]}}" />
  <input type="hidden" name="url" value="{{$views->partner_value["url"]}}" />
  <input type="hidden" name="postal_cd" value="{{$views->partner_value["postal_cd"]}}" />
  <input type="hidden" name="address" value="{{$views->partner_value["address"]}}" />
  <input type="hidden" name="tel" value="{{$views->partner_value["tel"]}}" />
  <input type="hidden" name="fax" value="{{$views->partner_value["fax"]}}" />
  <input type="hidden" name="person_post" value="{{$views->partner_value["person_post"]}}" />
  <input type="hidden" name="person_nm" value="{{$views->partner_value["person_nm"]}}" />
  <input type="hidden" name="person_kn" value="{{$views->partner_value["person_kn"]}}" />
  <input type="hidden" name="person_email" value="{{$views->partner_value["person_email"]}}" />
  <input type="hidden" name="open_ymd" value="{{$views->partner_value["open_ymd"]}}" />
  <input type='hidden' name='return_flg' value='true'> 
  <INPUT TYPE="submit" VALUE="内容確認">
</form>
{!! Form::close() !!}

@section('title', 'footer')
@include('ctl.common.footer')