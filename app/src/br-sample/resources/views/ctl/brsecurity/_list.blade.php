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
  <!-- {foreach from=$v->assign->log_securities.values item=log_security} -->
  @foreach ($log_securities as $log_security)
  <tr>
    <td>
    <form action="{$v->env.source_path}{$v->env.module}/brsecurity/show/" method="post">
      <input name="security_cd" value="{$log_security.security_cd}" type="hidden">
      <input name="request_dtm" value="$v->helper->form->strip_tags($log_security.request_dtm)|date_format:'%Y-%m-%d}" type="hidden">
      
      <input value="詳細" type="submit">
    </form>
    </td>
    <!-- <td>{$v->helper->form->strip_tags($log_security.security_cd)}<br /></td>
    <td>{$v->helper->form->strip_tags($log_security.session_id)}<br /></td>
    <td>{$v->helper->form->strip_tags($log_security.request_dtm)|date_format:'%Y-%m-%d %T'}<br /></td>
    <td>{$v->helper->form->strip_tags($log_security.account_class)}<br /></td>
    <td>{$v->helper->form->strip_tags($log_security.account_key)}<br /></td>
    <td>{$v->helper->form->strip_tags($log_security.ip_address)}<br /></td>
    <td>{$v->helper->form->strip_tags($log_security.uri)}<br /></td> -->
     <td>{{strip_tags($log_security['security_cd'])}}<br /></td>
    <td>{{strip_tags($log_security['session_id'])}}<br /></td>
    <td>{{strip_tags($log_security['request_dtm'])}}<br /></td>
    <td>{{strip_tags($log_security['account_class'])}}<br /></td>
    <td>{{strip_tags($log_security['account_key'])}}<br /></td>
    <td>{{strip_tags($log_security['ip_address'])}}<br /></td>
    <td>{{strip_tags($log_security['uri'])}}<br /></td>
  </tr>
  <!-- {/foreach} -->
  @endforeach
</table>

