
<table border="1" cellpadding="4" cellspacing="0">
  @if ( Route::currentRouteName() === 'ctl.brhoteladvert2009000400.new' || Route::currentRouteName() === 'ctl.brhoteladvert2009000400.create')
  <tr>
    <td bgcolor="#eeffee">施設コード</td>
    <td><input type="text" name="hotel_advert[hotel_cd]" value="{{$views->hotel_advert['hotel_cd']??null}}" size="15" maxlength="10" /></td>
  </tr>
  @else
  <tr>
    <td bgcolor="#eeffee">施設コード</td>
    <td>{{strip_tags($views->hotel_advert['hotel_cd']??null)}}</td>
    {{-- null追記 --}}
  </tr>
  <tr>
    <td bgcolor="#eeffee">施設名称</td>
    <td>{{strip_tags($views->hotel_advert['hotel_nm']??null)}}</td>
    {{-- null追記 --}}
  </tr>
  @endif
  <tr>
    <td bgcolor="#eeffee">掲載開始年月日</td>
    {{-- 要書き換えあっているか --}}
    <td><input type="text" name="hotel_advert[advert_s_ymd]" value="{{strip_tags($views->hotel_advert['advert_s_ymd']??date('Y-m-d'))}}" size="15" maxlength="10" /><small>YYYY-MM-DD or YYYY/MM/DD</small></td>
    
  </tr>
  <tr>
    <td bgcolor="#eeffee">掲載最終年月日</td>
    {{-- 要書き換えあっているか --}}
    <td><input type="text" name="hotel_advert[advert_e_ymd]" value="{{strip_tags($views->hotel_advert['advert_e_ymd']??date('Y-m-d'))}}" size="15" maxlength="10" /><small>YYYY-MM-DD or YYYY/MM/DD</small></td>
  </tr>
  <tr>
    <td bgcolor="#eeffee">掲載順序</td>
     <td><input type="text" name="hotel_advert[advert_order]" value="{{strip_tags($views->hotel_advert['advert_order']??null)}}" size="15" maxlength="10" /><small>優先したい場合にみ設定</small></td>
     {{-- 優先したい場合のみと書いてあるが、必須のバリデーションついている＆バリデーションの桁数と違う（8桁） --}}
  </tr>
  <tr>
    <td bgcolor="#eeffee">掲載金額</td>
     <td><input type="text" name="hotel_advert[advert_charge]" value="{{strip_tags($views->hotel_advert['advert_charge']??null)}}" size="10" maxlength="7" /></td>
  </tr>
  <tr>
    <td bgcolor="#eeffee">掲載状態</td>
    <td><input type="radio" value="0" name="hotel_advert[advert_status]" id="advert_status0" @if (($views->hotel_advert['advert_status']??null) == 0) checked @endif /><label for="advert_status0">無効</label>
        <input type="radio" value="1" name="hotel_advert[advert_status]" id="advert_status1" @if (($views->hotel_advert['advert_status']??null) == 1) checked @endif /><label for="advert_status1">有効</label></td>
  </tr>
  <tr>
    <td><br></td>
    <td><input type="submit" value="更新する" /></td>
  </tr>
</table>

