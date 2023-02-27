<tr>
	<td nowrap  bgcolor="#EEEEFF" >部屋名称</td>
	<td>
    @if(isset($is_jrset))
      <div>通常販売時の部屋名称</div>
      <input type="text"  maxlength="40" name="Room[room_nm]" size="70" value="">
      <div><small>※特定サイトにおいて<span style="color:#ff0000;">「部屋名称」が１２文字迄しか表示されない場合</span>があります。</small></div>
      <br />
      <div>JRコレクション販売時の部屋名称</div>
      <div style="color:#ff0000;"><small>※未入力(空欄)の場合は通常販売時用の部屋名称を使用します。</small></div>
      <input type="text"  maxlength="40" name="Room[room_nm_dp]" size="70" value="">
    @else
      <input type="text"  maxlength="40" name="Room[room_nm]" size="70" value="">
    @endif
  </td>
	<td>
		<small>40文字 <font color="#ff0000">(半角カナ使用禁止)<br></font><font color="#339933">予約ページに表示されます。<font color="#0000ff">（必須）</font></small></font>
	</td>
</tr>
<tr>
  <td nowrap  bgcolor="#EEEEFF" >適用人数</td>
  <td>
    最小<input type="text" name="Room[capacity_min]" size="2" value="" />人
    <br />
    最大<input type="text" name="Room[capacity_max]" size="2" value="" />人
  </td>
  <td><small>半角数字<font color="#0000ff">（必須）</font></small></td>
</tr>
<tr>
	<td nowrap  bgcolor="#EEEEFF" >部屋タイプ</td>
	<td>
		<nobr><input type="radio" value="1" name="Room[room_type]" id="room_type1" @if($room_type == 1)checked @endif /><label for="room_type1">シングル</label></nobr>
		<nobr><input type="radio" value="2" name="Room[room_type]" id="room_type2" @if($room_type == 2)checked @endif /><label for="room_type2">ツイン</label></nobr>
		<nobr><input type="radio" value="3" name="Room[room_type]" id="room_type3" @if($room_type == 3)checked @endif /><label for="room_type3">セミダブル</label></nobr>
		<nobr><input type="radio" value="4" name="Room[room_type]" id="room_type4" @if($room_type == 4)checked @endif /><label for="room_type4">ダブル</label></nobr>
		<nobr><input type="radio" value="5" name="Room[room_type]" id="room_type5" @if($room_type == 5)checked @endif /><label for="room_type5">トリプル</label></nobr>
		<nobr><input type="radio" value="6" name="Room[room_type]" id="room_type6" @if($room_type == 6)checked @endif /><label for="room_type6">４ベッド</label></nobr>
		<nobr><input type="radio" value="7" name="Room[room_type]" id="room_type7" @if($room_type == 7)checked @endif /><label for="room_type7">スイート</label></nobr>
		<nobr><input type="radio" value="8" name="Room[room_type]" id="room_type8" @if($room_type == 8)checked @endif /><label for="room_type8">メゾネット</label></nobr>
		<nobr><input type="radio" value="9" name="Room[room_type]" id="room_type9" @if($room_type == 9)checked @endif /><label for="room_type9">和室</label></nobr>
		<nobr><input type="radio" value="10" name="Room[room_type]" id="room_type10" @if($room_type == 10)checked @endif /><label for="room_type10">和洋室</label></nobr>
		<nobr><input type="radio" value="0" name="Room[room_type]" id="room_type0" @if($room_type == 0)checked @endif /><label for="room_type0">カプセル</label></nobr>
		<nobr><input type="radio" value="11" name="Room[room_type]" id="room_type11" @if($room_type == 11)checked @endif /><label for="room_type11">その他</label></nobr>
		<nobr><input type="radio" value="" name="Room[room_type]" id="room_type99" @if(is_null($room_type))checked @endif /><label for="room_type99">未選択</label></nobr>
	</td>
	<td><small>選択</small></td>
</tr>
<tr>
	<td nowrap  bgcolor="#EEEEFF" >広さ <small>最小～最大</small></td>
	<td>
		<input type="text" maxlength="3" size="3" name="Room[floorage_min]" value=""> ～ 
		<input type="text" maxlength="3" size="3" name="Room[floorage_max]" value="">
	</td>
	<td><small>半角数字<font color="#0000ff">（必須）</font></small></td>
</tr>
<tr>
  <td nowrap  bgcolor="#EEEEFF" >広さ単位</small></td>
  <td>
    <input type="radio" id="floor_unit_0" name="Room[floor_unit]" value="0" @if($floor_unit == 0 && !is_null($floor_unit)) checked @endif /><label for="floor_unit_0">平米</label>
    <input type="radio" id="floor_unit_1" name="Room[floor_unit]" value="1" @if($floor_unit == 1) checked @endif  /><label for="floor_unit_1">畳</label>
  </td>
  <td><small>選択</small></td>
</tr>