  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括コード</td>
      <td nowrap>
        <p name="supervisor_cd" value="{{strip_tags($views->supervisor_cd)}}">{{strip_tags($views->supervisor_cd)}}</p>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括名</td>
      <td nowrap>
        <input type="text" name="Hotel_Supervisor[supervisor_nm]" value="{{strip_tags($views->a_hotel_supervisor['supervisor_nm'])}}" size="42" maxlength="40"><br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >アカウントID</td>
      <td nowrap>
        <input type="text" name="Hotel_Supervisor_Account[account_id]" value="{{strip_tags($views->a_hotel_supervisor_account['account_id'])}}"size="12" maxlength="10">半角英数字（10文字まで）<br>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >パスワード</td>
      <td nowrap>
        <p name="Hotel_Supervisor_Account[password]" value="{{strip_tags($views->a_hotel_supervisor_account['password'])}}">{{strip_tags($views->a_hotel_supervisor_account['password'])}}</p>
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >ステータス</td>
      <td nowrap>
        <input type="radio" name="Hotel_Supervisor_Account[accept_status]" id="j1" value="1" @if($views->a_hotel_supervisor_account['accept_status'] == 1 || !isset($views->a_hotel_supervisor_account['accept_status'])) checked @endif>
          <label for="j1">
            利用可
          </label>
        <input type="radio" name="Hotel_Supervisor_Account[accept_status]" id="j2" value="0" @if($views->a_hotel_supervisor_account['accept_status'] == 0) checked @endif>
          <label for="j2">
            利用不可
          </label>
      </td>
    </tr>
  </table>