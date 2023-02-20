  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap colspan="2" bgcolor="#EEFFEE" >プログラム詳細情報</td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >プログラム</td>
      <td nowrap><small>{$v->helper->form->strip_tags($v->assign->affiliate_program.affiliate_cd)}</small><br>
        <input type="text" name="Affiliate_Program[program_nm]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.program_nm)}" size="40" maxlength="40"><br>
      </td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >管理画面ログインパスワード</td>
      <td nowrap>
        <input type="text" name="Affiliate_Program[password]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.password)}" size="40" maxlength="40">
      </td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >予約システム</td>
      <td nowrap>
        <select name="Affiliate_Program[reserve_system]">
          <option value="reserve">reserve</opiton>
          <option value="biztrip">biztrip</opiton>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >COOKIE有効期限</td>
      <td nowrap>
        <input type="text" name="Affiliate_Program[limit_cookie]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.limit_cookie)}" size="3" maxlength="3">日
      </td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >COOKIE上書き可否</td>
      <td nowrap >
        <input type="radio" name="Affiliate_Program[overwrite_status]" value="1" {if $v->assign->affiliate_program.overwrite_status == 1} checked{/if} id="overwrite_status_1"><label for="overwrite_status_1">OK</label></option>
        <input type="radio" name="Affiliate_Program[overwrite_status]" value="0" {if $v->assign->affiliate_program.overwrite_status == 0} checked{/if} id="overwrite_status_0"><label for="overwrite_status_0">NG</label></option>
      </td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >R : リダイレクト先<br>T : タグ</td>
      <td nowrap>
        R : <input type="text" name="Affiliate_Program[redirect]" value="{$v->assign->affiliate_program.redirect}" size="70" maxlength="512"><br />
        T : <textarea name="Affiliate_Program[tag]" rows="3" cols="49" wrap="off">{$v->assign->affiliate_program.tag|escape:'html'}</textarea><br />
      </td>
    </tr>
    <tr>
      <td nowrap  bgcolor="#EEFFEE" >開始日時<br>終了日時</td>
      <td nowrap>
        <input type="text" name="Affiliate_Program[accept_s_ymd]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.accept_s_ymd)}" size="10" maxlength="10">
        <input type="text" name="Affiliate_Program[accept_s_hms]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.accept_s_hms)}" size="7"  maxlength="8"><br/>
        <input type="text" name="Affiliate_Program[accept_e_ymd]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.accept_e_ymd)}" size="10" maxlength="10">
        <input type="text" name="Affiliate_Program[accept_e_hms]" value="{$v->helper->form->strip_tags($v->assign->affiliate_program.accept_e_hms)}" size="7"  maxlength="8"><br/>
        <small>
          YYYY/MM/DD HH:MM:SS <small>又は</small> YYYY-MM-DD HH:MM:SS
        </small>
      </td>
    </tr>
  </table>
  <input type="hidden" name="Affiliate_Program[report_layout_version]" value="2">