@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

<table border="1" cellspacing="0" cellpadding="3">

	@if ( ! $service->is_empty($hotel["hotel_cd"]))
		<tr>
			<td  bgcolor="#EEFFEE" >施設コード</td>
			<td>
				{{$hotel['hotel_cd']}}<br>
			</td>
			<td><br /></td>
		</tr>
	@endif

  <tr>
	<td  bgcolor="#EEFFEE" >施設区分※</td>
	<td>
	<select size="1" name="Hotel[hotel_category]">
	  <option value="b" @if ($hotel['hotel_category'] == "b") selected @endif>b:ビジネスホテル</option>
	  <option value="a" @if ($hotel['hotel_category'] == "a") selected @endif>a:カプセルホテル</option>
	  <option value="c" @if ($hotel['hotel_category'] == "c") selected @endif>c:シティホテル</option>
	  <option value="j" @if ($hotel['hotel_category'] == "j") selected @endif>j:旅館</option>
	</select>
	</td>
	<td><small>選択</small><br><small><font color="#339933">施設の区分です。</font></small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >施設名称※</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[hotel_nm]" value="{{strip_tags($hotel['hotel_nm'])}}" SIZE="50" MAXLENGTH="50">
	</td>
	<td><small>50文字<font color="#0000ff">（必須）</font></small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >施設名称カナ※</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[hotel_kn]" value="{{strip_tags($hotel['hotel_kn'])}}" SIZE="50" MAXLENGTH="150">
	</td>
	<td><small>全角カナ150文字<font color="#0000ff">（必須）</font></small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >旧施設名称</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[hotel_old_nm]" value="{{strip_tags($hotel['hotel_old_nm'])}}" SIZE="50" MAXLENGTH="50">
	</td>
	<td><small>50文字</small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >郵便番号※</td>
	<td>
	<INPUT TYPE="text" NAME="Hotel[postal_cd]" value="{{strip_tags($hotel['postal_cd'])}}" SIZE="10" MAXLENGTH="8">
	</td>
	<td><small>xxx-xxxx<font color="#0000ff">（必須）</font></small></td>
  </tr>

  {{--TODO include file=$v->env.module_root|cat:'/views/brhotel/_pref_city_ward_select_form.tpl'--}}
  @section('select_parts')
  @include('ctl.brhotel._pref_city_ward_select_form',
		  ["hotel" => $hotel
			,"mast_prefs" => $mast_prefs
			,"mast_cities" => $mast_cities
			,"mast_wards" => $mast_wards
			])

  <tr>
	<td  bgcolor="#EEFFEE" >住所※</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[address]" value="{{strip_tags($hotel['address'])}}" SIZE="50" MAXLENGTH="200"><br><small>市区以下を入力　例）大阪市北区・・・</small>
	</td>
	<td><small>100文字<font color="#0000ff">（必須）</font></small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >電話番号※</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[tel]" value="{{strip_tags($hotel['tel'])}}" SIZE="20" MAXLENGTH="15">
	</td>
	<td><small>xxxx-xxxx-xxxx<font color="#0000ff">（必須）</font></small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >ＦＡＸ番号</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[fax]" value="{{strip_tags($hotel['fax'])}}" SIZE="20" MAXLENGTH="15">
	</td>
	<td><small>xxxx-xxxx-xxxx</small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >保有部屋数</td>
	<td>
	  <INPUT TYPE="text" NAME="Hotel[room_count]" value="{{strip_tags($hotel['room_count'])}}" SIZE="4" MAXLENGTH="4"> 室
	</td>
	<td><small>数字4桁</small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >チェックイン時刻</td>
	<td>

	<select size="1" NAME="Hotel[check_in]">
	  @for($check_in = 0; $check_in <31; $check_in++)
		<option value="{{sprintf("%02d",strip_tags($check_in))}}:00" @if ($hotel['check_in'] == sprintf("%02d",$check_in).":00") selected @endif>	{{sprintf("%02d",strip_tags($check_in))}}:00</option>
		<option value="{{sprintf("%02d",strip_tags($check_in))}}:30" @if ($hotel['check_in'] == sprintf("%02d",$check_in).":30") selected @endif>	{{sprintf("%02d",strip_tags($check_in))}}:30</option>
	  @endfor
	</select>
	～
	<select size="1" NAME="Hotel[check_in_end]">
	  <option value="" @if ($service->is_empty($hotel['check_in_end'])) selected @endif>指定無し</option>
	  @for($check_in_end = 0; $check_in_end <31; $check_in_end++)
		<option value="{{sprintf("%02d",strip_tags($check_in_end))}}:00" @if ($hotel['check_in_end'] == sprintf("%02d",$check_in_end).":00") selected @endif>	{{sprintf("%02d",strip_tags($check_in_end))}}:00</option>
		<option value="{{sprintf("%02d",strip_tags($check_in_end))}}:30" @if ($hotel['check_in_end'] == sprintf("%02d",$check_in_end).":30") selected @endif>	{{sprintf("%02d",strip_tags($check_in_end))}}:30</option>
	  @endfor
	</select>
	</td>
	<td><small>選択</small><br></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >チェックイン時刻コメント</td>
	<td>
	  <textarea NAME="Hotel[check_in_info]" cols=40 rows=4>{{strip_tags($hotel['check_in_info'])}}</textarea>
	</td>
	<td><small>75文字</small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >チェックアウト時刻</td>
	<td>
	<select size="1" NAME="Hotel[check_out]">
		@for($check_out = 0; $check_out <24; $check_out++)
		<option value="{{sprintf("%02d",strip_tags($check_out))}}:00" @if ($hotel['check_out'] == sprintf("%02d",$check_out).":00") selected @endif>	{{sprintf("%02d",strip_tags($check_out))}}:00</option>
		<option value="{{sprintf("%02d",strip_tags($check_out))}}:30" @if ($hotel['check_out'] == sprintf("%02d",$check_out).":30") selected @endif>	{{sprintf("%02d",strip_tags($check_out))}}:30</option>
	  @endfor
	</select>
	</td>
	<td><small>選択</small></td>
  </tr>

  <tr>
	<td  bgcolor="#EEFFEE" >深夜受付</td>
	<td>
	  <label>
	  <INPUT TYPE="radio" NAME="Hotel[midnight_status]" VALUE="0" @if ($hotel['midnight_status'] == 0) CHECKED @endif id="i1">
		<LABEL for="i1">
		  受け入れない
		</LABEL>　
	  </label>
	  <label>
	  <INPUT TYPE="radio" NAME="Hotel[midnight_status]" VALUE="1" @if ($hotel['midnight_status'] == 1) CHECKED @endif id="i2">
		<LABEL for="i2">
		  受け入れる
		</label>
	</td>
	<td><small>選択</small></td>
  </tr>

@if (isset($action) && $action == "new")
  <tr>
	<td  bgcolor="#EEFFEE" >仕入タイプ</td>
	<td>
	  <label>
		{{--TODO hotel_controlは施設情報更新では出てこないので、条件追加が必要--}}
	  <INPUT TYPE="radio" NAME="Hotel_Control[stock_type]" VALUE="0" @if ($hotel_control['stock_type'] == 0) CHECKED @endif id="i1">
		<LABEL for="i1">
		  受託販売
		</LABEL>
	  </label>
	  <label>
	  <INPUT TYPE="radio" NAME="Hotel_Control[stock_type]" VALUE="1" @if ($hotel_control['stock_type'] == 1) CHECKED @endif id="i2">
		<LABEL for="i2">
		  買取販売
	  </label>
	<INPUT TYPE="radio" NAME="Hotel_Control[stock_type]" VALUE="3" @if ($hotel_control['stock_type'] == 3) CHECKED @endif id="i3">
		<LABEL for="i3">
		  特定施設(三普)
		</label>
	</td>
	<td><small>選択</small></td>
  </tr>
@endif
  <input type="hidden" name="target_cd" value="{{isset($target_cd) ? strip_tags($target_cd) : ''}}">
  <input type="hidden" name="Hotel[hotel_cd]" value="{{$hotel['hotel_cd']}}">
</table>