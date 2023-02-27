<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td bgcolor="#EEEEFF">
      駐車場詳細
    </td>
    <td>
      <textarea name="HotelInfo[parking_info]" cols="40" rows="6" wrap="off">@if(isset($views->input_data['parking_info'])){{strip_tags($views->input_data['parking_info'])}} @endif</textarea>
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEEEFF">
      カード利用条件
    </td>
    <td>
      <input type="text" name="HotelInfo[card_info]" value="@if(isset($views->input_data['card_info'])){{strip_tags($views->input_data['card_info'])}} @endif" style="width:25em;">
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEEEFF">
      特色
    </td>
    <td>
      <textarea name="HotelInfo[info]" cols="40" rows="16" wrap="off">@if(isset($views->input_data['info'])) {{strip_tags($views->input_data['info'])}} @endif</textarea>
    </td>    
  </tr>
</table>