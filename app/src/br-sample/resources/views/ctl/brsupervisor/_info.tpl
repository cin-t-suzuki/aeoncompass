  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括コード</td>
      <td nowrap>
        {$v->helper->form->strip_tags($v->assign->hotel_supervisor.supervisor_cd)}<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括名</td>
      <td nowrap>
        {$v->helper->form->strip_tags($v->assign->hotel_supervisor.supervisor_nm)}<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >アカウントID</td>
      <td nowrap>
        {$v->helper->form->strip_tags($v->assign->hotel_supervisor_account.account_id)}<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >パスワード</td>
      <td nowrap>
        {if zap_is_empty($v->assign->hotel_supervisor_account.password)}
          ＊＊＊＊＊＊
        {else}
          {$v->helper->form->strip_tags($v->assign->hotel_supervisor_account.password)}
        {/if}
        <br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >ステータス</td>
      <td nowrap>
        {if $v->assign->hotel_supervisor_account.accept_status == 0}
          利用不可
        {elseif $v->assign->hotel_supervisor_account.accept_status == 1}
          利用可
        {/if}
      </td>
    </tr>
  </table>