{{-- header start --}}
  {include file=$v->env.module_root|cat:'/views/_common/_htl_login_header.tpl' acceptance_status_flg=false title='予約者へのメール(履歴検索)'}
{{-- header end --}}

<center>
    <p><br>
    管理画面への接続はＩＤ・パスワードを入力の上<br>
        「ログイン」ボタンをクリックください。</p>
    <p>※ＩＤ・パスワードがご不明な宿泊施設様は下記サービスセンター宛にご連絡ください。<br><br>

{{-- メッセージ --}}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<form action="{$v->env.source_path}{$v->env.module}/htllogin/login/" method="POST">
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td>ＩＤ</td>
    <td colspan="2"><input type="text" name="account_id" value="{$v->helper->form->strip_tags($v->assign->account_id)}" size="26" maxlength="10"></td>
  </tr>
  <tr>
    <td>パスワード</td>
    <td colspan="2"><input type="password" name="password" value="" size="26" maxlength="10"></td>
  </tr>
</table>
<input type="checkbox" name="keep" value="1" {if !is_empty($v->assign->keep)} checked {/if} id="keep_1">
<label for="keep_1">ログイン情報を持続する</label><br><br>
<input type="submit" value="ログイン">
</form>
<br>
<br>
  <table border="0" cellpadding="0" cellspacing="0" bgcolor="#9999FF">
    <tr>
      <td>
        <table border="0" cellpadding="4" cellspacing="1">
          <tr>
            <td bgcolor="#EEEEFF" align="center">お知らせ</td>
          </tr>

          <tr>
            <td bgcolor="#FFFFFF"><img src="/images/qi/new.gif" border="0" width="38" height="11" alt="新着"> インターネット客室販売収益向上システム「<a href="http://price.bestrsv.com/" target="price" title="プライスコンシェルジュ">プライスコンシェルジュ</a>」好評ご案内中</td>
          </tr>
          <tr>
            <td bgcolor="#FFFFFF"><img src="/images/qi/new.gif" border="0" width="38" height="11" alt="新着"> 「<a href="http://price.bestrsv.com/" target="price" title="プライスコンシェルジュ">プライスコンシェルジュ</a>」無料おためし利用は<a href="http://price.bestrsv.com/" target="price" title="無料おためし利用">こちら</a></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFFF"><small>ご不明点や登録にお手伝いが必要な場合はお気兼ね無く<a href="mailto:{$v->config->environment->mail->from->opc}">ご連絡</a>ください。</small></td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
<p>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#9999ff">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="6">
          <tr>
            <td align="left" nowrap="" bgcolor="#ffffff">
              お問い合わせ先<br>
              MAIL:<a href="mailto:{$v->config->environment->mail->from->opc}">{$v->config->environment->mail->from->opc}</a><br>
              TEL : 03-5751-8243<br>
              FAX : 03-5751-8242<br>
              受付: 月～金 9:30～18:30<br>
            （土曜・日曜・祝祭日・弊社休日は除く）
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</p>
</center>
<br>
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}