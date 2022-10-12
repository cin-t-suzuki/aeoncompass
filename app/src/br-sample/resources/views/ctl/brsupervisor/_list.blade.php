@inject('service', 'App\Http\Controllers\ctl\BrsupervisorController')

<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td nowrap align="center" bgcolor="#EEFFEE" >施設統括</td>
    <td nowrap align="center" bgcolor="#EEFFEE" >施設統括コード</td>
    <td nowrap align="center" bgcolor="#EEFFEE" >施設統括名称</td>
    <td nowrap align="center" bgcolor="#EEFFEE" >グループホテル一覧</td>
  </tr>


    @forelse($views->a_hotel_supervisor['values'] as $hotel_supervisor_list)
      <tr>
        <td style ="text-align:center"><input type="submit" name="" value="詳細"></td>
        <td style="width: 200px;display: table-cell;">{{$hotel_supervisor_list['supervisor_cd']}}</td>
        <td style="width: 500px;display: table-cell;">{{$hotel_supervisor_list['supervisor_nm']}}</td>
        <td style ="text-align:center"><input type="submit" name="" value="一覧"></td>
      </tr>
    @empty
        <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee; margin-top:1em;">条件に該当する施設はありませんでした。</div>
    @endforelse

</table>