  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括コード</td>
      <td nowrap>
         {{strip_tags($views->supervisor_cd)}}<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設コード</td>
      <td nowrap>
        <input type="text" name="Hotel_Supervisor_Hotel[hotel_cd]" value="{$v->helper->form->strip_tags($v->assign->hotel_supervisor.supervisor_nm)}" size="12" maxlength="10"><br>
      </td>
    </tr>
  </table>