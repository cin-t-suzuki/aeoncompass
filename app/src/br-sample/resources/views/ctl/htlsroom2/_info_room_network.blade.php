<tr>
  <td nowrap  bgcolor="#EEEEFF" >ネットワーク環境</td>
  <td colspan="2">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#cccccc">
          <table border="0" cellpadding="4" cellspacing="1">
            <tr>
              <td nowrap bgcolor="#FFFFFF">ネット接続可否</td>
              <td bgcolor="#FFFFFF">
                @if($room->network == 0)
                  接続環境なし 
                @elseif($room->network == 1)
                  無料（全客室）
                @elseif($room->network == 2)
                  無料（一部客室）
                @elseif($room->network == 3)
                  有料（全客室）
                @elseif($room->network == 4)
                  有料（一部客室）
                @elseif($room->network == 9)
                  不明
                @endif
              </td>
              <td bgcolor="#FFFFFF"><small>有料時の料金情報は備考欄に記載ください。<br />[接続環境なし・不明]をご選択の場合、[接続必要機器・コネクタ・備考欄]で設定された情報はユーザーページに表示されません。</small></td>
            </tr>
            @if($room->network != 9 && $room->network != 0)
              <tr>
                <td nowrap bgcolor="#FFFFFF">接続必要機器<br />（パソコン除く）</td>
                <td bgcolor="#FFFFFF">
                  @if($room->rental == 1)
                    部屋常設（不要）
                  @elseif($room->rental == 2)
                    無料貸出 
                  @elseif($room->rental == 3)
                    有料貸出 
                  @elseif($room->rental == 4)
                    持ち込み
                  @endif
                  <br>
                </td>
                <td bgcolor="#FFFFFF"><small>接続に必要な機器（ケーブルなど）の状況をご選択ください。<br>貸出先着順や料金、機器名などは備考欄に記載ください。</small></td>
              </tr>
              <tr>
                <td nowrap bgcolor="#FFFFFF">コネクタ</td>
                <td bgcolor="#FFFFFF">
                  @if($room->connector == 1)
                    無線 
                  @elseif($room->connector == 2)
                    LAN 
                  @elseif($room->connector == 3)
                    TEL
                  @elseif($room->connector == 4)
                    その他
                  @endif
                  <br>
                </td>
                <td bgcolor="#FFFFFF"><small>無線の種類やその他情報は備考欄に記載ください。</small></td>
              </tr>
              <tr>
                <td nowrap bgcolor="#FFFFFF">備考欄</td>
                <td bgcolor="#FFFFFF">
                {{$room->network_note}}
                <br></td>
                <td bgcolor="#FFFFFF"><small>全角250文字  HTMLタグ不可<br>右記欄には料金情報や貸出し等の補足情報のご記入をお願いします。<br>ネットワーク環境 備考欄記入例）参照</small></td>
              </tr>
            @endif
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>