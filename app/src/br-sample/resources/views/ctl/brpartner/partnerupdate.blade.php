@extends('ctl.common.base')
@section('title', 'PARTNER')

@section('page_blade')

{{-- メッセージ --}}
{{-- content内の書き換えあっているか？ --}}
@include('ctl.common.message',['guides'=>$messages["guides"]])


{!! Form::open(['route' => ['ctl.brpartner.partnerconf'], 'method' => 'post']) !!}
  <input type="hidden" name="partner_cd" value="{{strip_tags($views->partner_value["partner_cd"])}}" />
  <input type="hidden" name="partner_nm" value="{{strip_tags($views->partner_value["partner_nm"])}}" />
  <input type="hidden" name="system_nm" value="{{strip_tags($views->partner_value["system_nm"])}}" />
  <input type="hidden" name="partner_ns" value="{{strip_tags($views->partner_value["partner_ns"])}}" />
  <input type="hidden" name="url" value="{{strip_tags($views->partner_value["url"])}}" />
  <input type="hidden" name="postal_cd" value="{{strip_tags($views->partner_value["postal_cd"])}}" />
  <input type="hidden" name="address" value="{{strip_tags($views->partner_value["address"])}}" />
  <input type="hidden" name="tel" value="{{strip_tags($views->partner_value["tel"])}}" />
  <input type="hidden" name="fax" value="{{strip_tags($views->partner_value["fax"])}}" />
  <input type="hidden" name="person_post" value="{{strip_tags($views->partner_value["person_post"])}}" />
  <input type="hidden" name="person_nm" value="{{strip_tags($views->partner_value["person_nm"])}}" />
  <input type="hidden" name="person_kn" value="{{strip_tags($views->partner_value["person_kn"])}}" />
  <input type="hidden" name="person_email" value="{{strip_tags($views->partner_value["person_email"])}}" />
  <input type="hidden" name="open_ymd" value="{{strip_tags($views->partner_value["open_ymd"])}}" />
  <input type='hidden' name='return_flg' value='true'> 
  <INPUT TYPE="submit" VALUE="内容確認">
</form>
{!! Form::close() !!}

@endsection