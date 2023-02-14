{!! Form::open(['route' => ['ctl.brBillpayPtn.list'], 'method' => 'get']) !!}
<p>
<table class="br-detail-list">
  <tr>
    <th>処理年月</th>
    <td>
      <select size="1" name="year">
        @php $now_year = date('Y'); $now_month = date('m'); @endphp
        @for ($y = 2000; $y <= $now_year+1; $y++)
          <option value="{{sprintf('%04d',$y)}}" 
            @if  (($year == sprintf('%04d',$y)) || ($service->is_empty($year)) && (sprintf('%04d',$y) == $now_year))
              selected="selected" 
            @endif >{{sprintf('%02d',$y)}}</option>
        @endfor</select> 年
      <select size="1" name="month" >{{-- 月表示のための12回ループ--}}
        @for ($m = 1; $m < 13; $m++)
          <option value="{{sprintf('%02d',$m)}}"
            @if  (($month == sprintf('%02d',$m)) || ($service->is_empty($month) && (sprintf('%02d',$m) == $now_month)))
             selected="selected" 
           @endif>{{sprintf('%02d',$m)}}</option>
        @endfor
      </select> 月
    </td>
  </tr>
</table>
</p>
<p>
<input type="submit" value="　検索　" />
</p>
{!! Form::close() !!}

