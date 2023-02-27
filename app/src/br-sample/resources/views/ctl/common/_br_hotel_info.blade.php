<!-- Hotel Information -->
<p>
  <table border="1" cellpadding="4" cellspacing="0">
    <tr>
      <td bgcolor="#EEFFEE" >施設</td>
      <td nowrap>
        <small>
          {{-- TODO $v->user->hotelをどう実装するか。そちらの実装まで表示不可のため、reserveck側の導線を一時的に非表示にしている --}}
          {{strip_tags($v->user->hotel.hotel_cd)}}@if ($v->user->hotel_control['stock_type'] == 1) <font color="#0000ff">[買]</font>@endif
        </small>
        <br />
        {{strip_tags($v->user->hotel.hotel_nm)}}
        <br />
        <small>
          TEL : {{strip_tags($v->user->hotel.tel)}} 
          FAX : {{strip_tags($v->user->hotel.fax)}}
        </small>
      </td>
    </tr>
  </table>
</p>