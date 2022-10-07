<table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td  bgcolor="#EEFFEE"><big>料率適用開始日</big></td>
    <td>
      <input type="text" name="HotelRate[accept_s_ymd]" value="{{strip_tags($hotelrate['accept_s_ymd'])}}" style="width:100px;ime-mode:disabled;" maxlength="10" > YYYY/MM/DD <small>又は</small> YYYY-MM-DD
    </td>
  </tr>
  <tr>
    <td  bgcolor="#EEFFEE"><big>イオンコンパスサイト料率</big></td>
    <td>
      <input type="text" name="HotelRate[system_rate]" value="{{strip_tags($hotelrate['system_rate'])}}" style="width:40px;ime-mode:disabled">%
    </td>
  </tr>
  <tr>
    <td  bgcolor="#EEFFEE"><big>その他サイト料率</big></td>
    <td>
      <input type="text" name="HotelRate[system_rate_out]" value="{{strip_tags($hotelrate['system_rate_out'])}}" style="width:40px;ime-mode:disabled">%
    </td>
  </tr>
</table>
<div style="margin-top:1em;">
　※イオンコンパスサイト料率：提携先コード「0000000000」の予約に適用<br />
　※その他サイト料率：提携先コード「0000000000」以外の予約に適用<br />
</div>