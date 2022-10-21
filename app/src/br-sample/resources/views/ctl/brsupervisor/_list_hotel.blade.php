<table border="1" cellspacing="0" cellpadding="4">
      <tr>
        <td nowrap align="left" bgcolor="#EEFFEE" >施設コード</td>
        <td nowrap align="left" bgcolor="#EEFFEE" >施設名</td>
        <td nowrap align="left" bgcolor="#EEFFEE" >削除</td>
      </tr>

    @forelse($views->a_hotel_supervisor_hotel['values'] as $hotel_supervisor_hotel_listhotel)
      <tr>
        <td style="width: 200px;display: table-cell;">{{$hotel_supervisor_hotel_listhotel['hotel_cd']}}</td>
        <td style="width: 500px;display: table-cell;">{{$hotel_supervisor_hotel_listhotel['hotel_nm']}}</td>
        <td><input type="submit" name="" value="削除"></td>
      </tr>
    @empty
        <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee; margin-top:1em;">条件に該当する施設はありませんでした。</div>
    @endforelse
  </table>