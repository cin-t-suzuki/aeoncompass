<table border="1" cellpadding="3" cellspacing="0">
  <tr bgcolor="#EEFFEE">
    <td><br></td>
    <td>セキュリティログコード</td>
    <td>セッションID</td>
    <td>リクエスト日時</td>
    <td>アカウントクラス</td>
    <td>アカウント認証キー</td>
    <td>IPアドレス</td>
    <td>リクエストURI</td>
  </tr>
  @foreach ($log_securities as $log_security)
  <tr>
    <td>
    {{ Form::open(['route' => 'ctl.brsecurity.show', 'method' => 'post']) }}
      <input name="security_cd" value={{$log_security->security_cd}} type="hidden">
      <input name="request_dtm" value={{$log_security->request_dtm}} type="hidden">
      
      <input value="詳細" type="submit">
    {!! Form::close() !!}
    </td>
  

    <td>{{strip_tags($log_security->security_cd)}}<br /></td>
    <td>{{strip_tags($log_security->session_id)}}<br /></td>
    <td>{{strip_tags($log_security->request_dtm)}}<br /></td>
    <td>{{strip_tags($log_security->account_class)}}<br /></td>
    <td>{{strip_tags($log_security->account_key)}}<br /></td>
    <td>{{strip_tags($log_security->ip_address)}}<br /></td>
    <td>{{strip_tags($log_security->uri)}}<br /></td>
  </tr>

  @endforeach
</table>

