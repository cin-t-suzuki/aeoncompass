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
        {!! Form::open(['route' => ['ctl.brsupervisor.deletehotel'], 'method' => 'post']) !!}
        <td>
          <input type="submit" name="" value="削除">
          <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd ??null )}}">
          <input type="hidden" name="hotel_cd" value="{{$hotel_supervisor_hotel_listhotel['hotel_cd']}}">
          <input type="hidden" name="id" value="{{$hotel_supervisor_hotel_listhotel['id']}}">  
        </td>
        {!! Form::close() !!}
      </tr>
    @empty
        <div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee; margin-top:1em;">条件に該当する施設はありませんでした。</div>
    @endforelse
  </table>