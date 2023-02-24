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
    <td>ＩＤ：[{{strip_tags($hotel_account['account_id_begin'])}}]　　パスワード：[*******]</td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >ホームページURL</td>
    <td>
    @if(count($hotel_links) != 0)
      @foreach($hotel_links as $hotel_link)
        {{strip_tags($hotel_link->title)}}
        @if(!next($hotel_links)) <br> @endif
      @endforeach
    @else
      <br>
    @endif
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >深夜予約受付(24:00～30:00)</td>
    <td>
      @if($hotel['midnight_status'] == 1)
        可
      @else
        不可
      @endif
    </td>
  </tr>

  <tr>
    <td  bgcolor="#EEEEFF" >予約の通知</td>
    <td>
      {{$notify_message}}<br>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF"  nowrap>郵便番号</td>
    <td>
      {{strip_tags($hotel['postal_cd'])}}<br>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >住所</td>
    <td>
      {{strip_tags($pref['pref_nm'])}} {{strip_tags($hotel['address'])}}<br>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >TEL/FAX</td>
    <td>
      {{strip_tags($hotel['tel'])}}
      ／
      {{strip_tags($hotel['fax'])}}<br>
    </td>
  </tr>
  <tr>
    <td  bgcolor="#EEEEFF" >保有部屋数</td>
    <td>
      {{strip_tags($hotel['room_count'])}}<br>
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >チェックイン</td>
    <td>
      {{strip_tags($hotel['check_in'])}}
     ～
     @if(is_null($hotel['check_in_end']))
        指定無し
      @else
      {{strip_tags($hotel['check_in_end'])}}
      @endif
    </select> 
    </td>
  </tr>
  
  <tr>
    <td  bgcolor="#EEEEFF" >チェックアウト</td>
    <td>
      {{strip_tags($hotel['check_out'])}}<br>
    </select> 
    </td>
  </tr>
  
</table>