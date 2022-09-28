<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td bgcolor="#EEEEFF">
      駐車場詳細
    </td>
    <td>
      <TEXTAREA name="HotelInfo[parking_info]" cols="40" rows="6" wrap="off">{{strip_tags($views->hotelInfo['parking_info'])}}</TEXTAREA>
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEEEFF">
      カード利用条件
    </td>
    <td>
      <input type="text" name="HotelInfo[card_info]" value="{{strip_tags($views->hotelInfo['card_info'])}}" style="width:25em;">
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEEEFF">
      特色
    </td>
    <td>
      <TEXTAREA name="HotelInfo[info]" cols="40" rows="16" wrap="off">{{strip_tags($views->hotelInfo['info'])}}</TEXTAREA>
    </td>    
  </tr>
</table>