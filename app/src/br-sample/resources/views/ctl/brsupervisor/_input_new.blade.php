  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括コード</td>
      <td nowrap>
        <input type="text" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd)}}" size="12" maxlength="10"><br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括名</td>
      <td nowrap>
        <input type="text" name="supervisor_nm" value="{{strip_tags($views->supervisor_nm)}}" size="42" maxlength="40"><br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >アカウントID</td>
      <td nowrap>
        <input type="text" name="account_id" value="{{strip_tags($views->account_id)}}"size="12" maxlength="10">半角英数字（10文字まで）<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >パスワード</td>
      <td nowrap>
        <input type="text" name="password" value="{{strip_tags($views->password)}}" size="12" maxlength="10">半角英数字（10文字まで）<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >ステータス</td>
      <td nowrap>
        <INPUT TYPE="radio" NAME="accept_status" id="j1" VALUE="1" @if ($views->accept_status == 1 || !isset($views->accept_status))checked @endif>
        <LABEL for="j1">利用可</label>
        <INPUT TYPE="radio" NAME="accept_status" id="j2" VALUE="0" @if ($views->accept_status == 0)checked @endif>
        <LABEL for="j2">利用不可</LABEL>　
      </td>
    </tr>
  </table>