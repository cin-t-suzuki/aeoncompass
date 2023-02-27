<tr>
  <td  bgcolor="#EEEEFF" nowrap="nowrap">基本提供室数の設定</td>

  <td colspan="2">
    期間の設定<br />
    <select name="from_year">
      @for($i = date('Y'); $i <= date('Y') + 2; $i++)
        <option value="{{$i}}" @if($i == date('Y')) selected @endif>{{$i}}</option>
      @endfor
    </select>年
    &nbsp;
    <select name="from_month">
      @for($i = 1; $i <= 12; $i++)
        <option value="{{$i}}" @if($i == date('m')) selected @endif>{{$i}}</option>
      @endfor
    </select>月
    &nbsp;
    <select name="from_day">
      @for($i = 1; $i <= 31; $i++)
        <option value="{{$i}}" @if($i == date('d')) selected @endif>{{$i}}</option>
      @endfor
    </select>日
    &nbsp;～&nbsp;
    <select name="to_year">
      @for($i = date('Y'); $i <= date('Y') + 2; $i++)
        <option value="{{$i}}" @if($i == date('Y')) selected @endif>{{$i}}</option>
      @endfor
    </select>年
    &nbsp;
    <select name="to_month">
      @for($i = 1; $i <= 12; $i++)
        <option value="{{$i}}" @if($i == date('m') + 1) selected @endif>{{$i}}</option>
      @endfor
    </select>月
    &nbsp;
    <select name="to_day">
      @for($i = 1; $i <= 31; $i++)
        <option value="{{$i}}" @if($i == date('t', strtotime(date('Y-m-01') .'+1 month'))) selected @endif>{{$i}}</option>
      @endfor
    </select>日
    <br /><br />
    <table border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
      <tr>
        <td>
          <table border="0" cellpadding="4" cellspacing="1" width="100%">
            <tr>
              <td bgcolor="#FFFFFF"><font color="#FF0000">日曜</font></td>
              <td bgcolor="#FFFFFF">月曜</td>
              <td bgcolor="#FFFFFF">火曜</td>
              <td bgcolor="#FFFFFF">水曜</td>
              <td bgcolor="#FFFFFF">木曜</td>
              <td bgcolor="#FFFFFF">金曜</td>
              <td bgcolor="#FFFFFF"><font color="#0000FF">土曜</font></td>
							<td bgcolor="#EEEEEE"><font color="#FF0000">祝日</font></td>
							<td bgcolor="#EEEEEE">休前日</td>
            </tr>
            <tr valign="top" align="center">
              <!-- {* 日1～土7   *} -->
              <td bgcolor="#FFFFFF"><input id="copy_src" maxlength="3" size="5" name="rooms_1" value="{{$rooms_1}}"><br />
                                    <input type="button" name="copy_exe" value="コピー" /></td>
              <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="rooms_2" value="{{$rooms_2}}"></td>
              <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="rooms_3" value="{{$rooms_3}}"></td>
              <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="rooms_4" value="{{$rooms_4}}"></td>
              <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="rooms_5" value="{{$rooms_5}}"></td>
              <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="rooms_6" value="{{$rooms_6}}"></td>
              <td bgcolor="#FFFFFF"><input maxlength="3" size="5" name="rooms_7" value="{{$rooms_7}}"></td>
							<td bgcolor="#EEEEEE"><input maxlength="3" size="5" name="rooms_8" value="{{$rooms_8}}"></td>
							<td bgcolor="#EEEEEE"><input maxlength="3" size="5" name="rooms_9" value="{{$rooms_9}}"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>