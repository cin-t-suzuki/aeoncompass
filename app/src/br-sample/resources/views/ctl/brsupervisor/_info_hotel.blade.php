  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設統括コード</td>
      <td nowrap>
        {{strip_tags($views->supervisor_cd)}}
      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設コード</td>
      <td nowrap>
        {{strip_tags($views->hotelData['hotel_cd'])}}

      </td>
    </tr>
    <tr>
      <td nowrap bgcolor="#EEFFEE" >施設名</td>
      <td nowrap>
        {{strip_tags($views->hotelData['hotel_nm'])}}

      </td>
    </tr>
  </table>