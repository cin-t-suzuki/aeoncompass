  <tr>
    <td bgcolor="#EEEEFF" nowrap>
        <b>ページ</b>
    </td>
    <td bgcolor="cccccc">
        入力例
    </td>
    <td>
        @if($a_hotel_link['type'] == 1)
            施設トップページ
        @elseif($a_hotel_link['type'] == 2)
            携帯トップページ
        @elseif($a_hotel_link['type'] == 3)
            その他ページ{{strip_tags($a_hotel_link['othercount'])}}
        @endif
    </td>
  </tr>
  <tr>
      <td bgcolor="#EEEEFF" nowrap>
        <b>タイトル</b>
      </td>
      <td bgcolor="cccccc">
        イオンコンパスホテルＨＰ
      </td>
      <td>
        <input type="text" name="HotelLink[title]" value="{{old('HotelLink.title', strip_tags($a_hotel_link['title']))}}" size="45">
      </td>
  </tr>
  <tr>
      <td bgcolor="#EEEEFF" nowrap>
        <b>Webサイトアドレス</b>
      </td>
      <td bgcolor="cccccc">
        https://www.aeon-tabi.com/
      </td>
      <td>
        <input type="text" name="HotelLink[url]" value="{{old('HotelLink.url', strip_tags($a_hotel_link['url']))}}" size="45">
      </td>
  </tr>