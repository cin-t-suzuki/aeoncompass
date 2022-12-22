@extends('ctl.common.base')
@section('title', 'PARTNER')

@section('page_blade')

<br>
  <table border="1" cellspacing="0" cellpadding="1">
    <tr>
      <td  BGCOLOR="#EEFFEE" >提携先コード</td>
      <td>{{strip_tags($views->partners["partner_cd"])}}</td>
    </tr>
    <tr><td  BGCOLOR="#EEFFEE" >提携先名</td>
      <td>{{strip_tags($views->partner_value["partner_nm"])}}</big>
      </td>
    </tr>
  </table>
<p>更新</p>
  {!! Form::open(['route' => ['ctl.brpartner.partnerupdate'], 'method' => 'post']) !!}
    <table border="1" cellspacing="0" cellpadding="2">
      <tr>
        <td bgcolor="#EEFFEE" nowrap>提携先コード</td>
        <td>{{strip_tags($views->partner_value["partner_cd"])}}</td>
        <input type="hidden" name="partner_cd" value="{{strip_tags($views->partner_value["partner_cd"])}}" />
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>提携先名称</td>
        <td>
          <INPUT TYPE="text" NAME="partner_nm" SIZE="40" MAXLENGTH="128" VALUE="{{strip_tags($views->partner_value["partner_nm"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>システム名称</td>
        <td>
          <INPUT TYPE="text" NAME="system_nm" SIZE="40" MAXLENGTH="128" VALUE="{{strip_tags($views->partner_value["system_nm"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>提携先略称</td>
        <td>
          <INPUT TYPE="text" NAME="partner_ns" SIZE="40" MAXLENGTH="128" VALUE="{{strip_tags($views->partner_value["partner_ns"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>ウェブサイトアドレス</td>
        <td>
          <INPUT TYPE="text" NAME="url" SIZE="40" MAXLENGTH="100" VALUE="{{$views->partner_value["url"]}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>提携先郵便番号</td>
        <td>
          <INPUT TYPE="text" NAME="postal_cd" SIZE="8" MAXLENGTH="8" VALUE="{{strip_tags($views->partner_value["postal_cd"])}}">
          999-9999
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>提携先住所</td>
        <td>
          <INPUT TYPE="text" NAME="address" SIZE="40" MAXLENGTH="200" VALUE="{{strip_tags($views->partner_value["address"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>電話番号</td>
        <td>
          <INPUT TYPE="text" NAME="tel" SIZE="15" MAXLENGTH="15" VALUE="{{strip_tags($views->partner_value["tel"])}}">
          00-000-0000
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>ファックス番号</td>
        <td>
          <INPUT TYPE="text" NAME="fax" SIZE="15" MAXLENGTH="15" VALUE="{{strip_tags($views->partner_value["fax"])}}">
          00-000-0000
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>担当者役職</td>
        <td>
          <INPUT TYPE="text" NAME="person_post" SIZE="40" MAXLENGTH="60" VALUE="{{strip_tags($views->partner_value["person_post"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>担当者氏名</td>
        <td>
          <INPUT TYPE="text" NAME="person_nm" SIZE="20" MAXLENGTH="60" VALUE="{{strip_tags($views->partner_value["person_nm"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>担当者氏名ふりがな</td>
        <td>
          <INPUT TYPE="text" NAME="person_kn" SIZE="20" MAXLENGTH="60" VALUE="{{strip_tags($views->partner_value["person_kn"])}}">
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>電子メールアドレス</td>
        <td>
          <INPUT TYPE="text" NAME="person_email" SIZE="30" MAXLENGTH="100" VALUE="{{strip_tags($views->partner_value["person_email"])}}"> 
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE" nowrap>サービス開始日</td>
        <td>
          <INPUT TYPE="text" NAME="open_ymd" SIZE="10" MAXLENGTH="10" VALUE='{{strip_tags($views->partner_value['open_ymd'])}}'>           
          YYYY/MM/DD
        </td>
      </tr>
    </table>
    <p>
      <INPUT TYPE="submit" VALUE="設定を変更する">
    </p>
  {!! Form::close() !!}

@endsection