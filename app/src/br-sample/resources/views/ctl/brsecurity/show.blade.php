
@extends('ctl.common.base')
@section('title', 'セキュリティログ詳細')


@section('page_blade')


<!-- エラーメッセージの表示 -->
@if (!empty($errors) && is_array($errors) && count($errors) > 0)
<div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
    @foreach ($errors as $error)
        <div>{!! nl2br($error) !!}</div>
    @endforeach
</div>
@endif
<br>

@if(isset($log_securities))
<table border="1" cellpadding="3" cellspacing="0">
  <tr>
    <td bgcolor="#EEFFEE">セキュリティログコード</td>
    <td>{{strip_tags($log_securities->security_cd)}}<br /></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">セッションID</td>
    <td>{{strip_tags($log_securities->security_cd)}}<br /></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">リクエスト日時</td>
    <td>{{strip_tags($log_securities->request_dtm)}}<br /></td>
   </tr>
  <tr>
    <td bgcolor="#EEFFEE">アカウントクラス</td>
    <td>{{strip_tags($log_securities->account_class)}}<br /></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">アカウント認証キー</td>
    <td>{{strip_tags($log_securities->account_key)}}<br /></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">IPアドレス</td>
    <td>{{strip_tags($log_securities->ip_address)}}<br /></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">リクエストURI</td>
    <td>{{strip_tags($log_securities->uri)}}<br /></td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE">パラメータ</td>
    <td>{{strip_tags($log_securities->parameter)}}<br /></td>
  </tr>
</table>
@endif

@endsection