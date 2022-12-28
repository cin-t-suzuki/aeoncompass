<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td  bgcolor="#EEEEFF" >登録状態</td>
    <td>
      @if($hotel_status['entry_status'] == 1)
        登録作業中
      @elseif($hotel_status['entry_status'] == 2)
        解約
      @elseif($hotel_status['entry_status'] == 0)
        公開中
      @endif

    </td>
  </tr>
  <tr>
    <td  bgcolor="#EEEEFF" >施設名</td>
    <td>
      {{strip_tags($hotel['hotel_nm'])}}<br>
    </td>
  </tr>

  <tr>
    <td  bgcolor="#EEEEFF" >ＩＤ・パスワード</td>
    <td>
      ＩＤ：[{{strip_tags($hotel_account['account_id_begin'])}}]　　パスワード：[*******] <small>※1</small>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >ホームページURL</td>
    <td>
      @if(count($hotel_links) != 0)
        @foreach($hotel_links as $hotel_link)
          <a href="{{strip_tags($hotel_link->url)}}" target="_blank">{{strip_tags($hotel_link->title)}}</a>
          @if(!next($hotel_links)) <br> @endif
        @endforeach
      @endif
     <small>※</small><small>2</small>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >深夜予約受付(24:00～30:00)</td>
    <td>
      @if($hotel['midnight_status'] == 1)
        可
      @else
        否
      @endif <small>※</small><small>3</small>
    </td>
  </tr>

  <tr>
    <td  bgcolor="#EEEEFF" >予約の通知</td>
    <td>
      {{$notify_message}}<small>※</small><small>3</small>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF"  nowrap>郵便番号</td>
    <td>
      <INPUT TYPE="text" NAME="Hotel[postal_cd]" value="{{strip_tags($hotel['postal_cd'])}}" SIZE="8" MAXLENGTH="8"><br>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >住所</td>
    <td>
      {{strip_tags($pref['pref_nm'])}} <INPUT TYPE="text" NAME="Hotel[address]" SIZE="50" MAXLENGTH="200" VALUE="{{strip_tags($hotel['address'])}}">(全角100文字まで)
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >TEL/FAX</td>
    <td>
      <INPUT TYPE="text" NAME="Hotel[tel]" SIZE="15" MAXLENGTH="15" VALUE="{{strip_tags($hotel['tel'])}}">
      ／
      <INPUT TYPE="text" NAME="Hotel[fax]" SIZE="15" MAXLENGTH="15" VALUE="{{strip_tags($hotel['fax'])}}">
      (入力例:06-000-0000)
    </td>
  </tr>
  <tr>
    <td  bgcolor="#EEEEFF" >保有部屋数</td>
    <td>
      <INPUT TYPE="text" NAME="Hotel[room_count]" SIZE="4" MAXLENGTH="4" VALUE="{{strip_tags($hotel['room_count'])}}">
      (半角数字）
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >チェックイン</td>
    <td>
    <select size="1" NAME="Hotel[check_in]">
      @for($i = 0; $i < 31; $i++)
        <option value="{{$i}}:00" @if($hotel['check_in'] == $i.":00") selected @endif>{{$i}}:00</option>
        <option value="{{$i}}:30" @if($hotel['check_in'] == $i.":30") selected @endif>{{$i}}:30</option>
      @endfor
    </select> 
     ～ 
    <select size="1" NAME="Hotel[check_in_end]">.
      <option value="" @if(empty($hotel['check_in_end'])) selected @endif>指定無し</option>
      @for($i = 0; $i < 31; $i++)
        <option value="{{$i}}:00" @if($hotel['check_in_end'] == $i.":00") selected @endif>{{$i}}:00</option>
        <option value="{{$i}}:30" @if($hotel['check_in_end'] == $i.":30") selected @endif>{{$i}}:30</option>
      @endfor
    </select> 
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >チェックアウト</td>
    <td>
    <select size="1" NAME="Hotel[check_out]">
      @for($i = 0; $i < 24; $i++)
        <option value="{{$i}}:00" @if($hotel['check_out'] == $i.":00") selected @endif>{{$i}}:00</option>
        <option value="{{$i}}:30" @if($hotel['check_out'] == $i.":30") selected @endif>{{$i}}:30</option>
     @endfor
    </select> 
    </td>
  </tr>

</table>