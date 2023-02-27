        <td nowrap  bgcolor="#EEEEFF" >ネットワーク環境</td>

        <td colspan="2">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td bgcolor="#cccccc">
                <table border="0" cellpadding="4" cellspacing="1">
                  <tr>
                    <td nowrap bgcolor="#FFFFFF">ネット接続可否</td>
                    <td bgcolor="#FFFFFF">

                      <table border="0" cellpadding="2" cellspacing="0">
                        <tr>
                          <td>
                            <input type="radio" id="network_1" value="1" name="Room_Network[network]" @if($network == 1) checked @endif></td>
                          <td nowrap>
                            <label for="network_1">無料（全客室）</label>
                          </td>
                          <td>
                            <input type="radio" id="network_2"value="2" name="Room_Network[network]" @if($network == 2) checked @endif>
                          </td>
                          <td nowrap>
                            <label for="network_2">無料（一部客室）</label>
                          </td>
                        </tr>
                        
                        <tr>
                          <td>
                            <input type="radio" id="network_3" value="3" name="Room_Network[network]" @if($network == 3) checked @endif>
                          </td>
                          <td nowrap>
                            <label for="network_3">有料（全客室）</label>
                          </td>
                          <td>
                            <input type="radio" id="network_4" value="4" name="Room_Network[network]" @if($network == 4) checked @endif>
                          </td>
                          <td nowrap>
                            <label for="network_4">有料（一部客室）</label>
                          </td>
                        </tr>
                        
                        <tr>
                          <td>
                            <input type="radio" id="network_0" value="0" name="Room_Network[network]" @if($network == 0 && !is_null($network)) checked @endif >
                          </td>
                          <td nowrap>
                            <label for="network_0">接続環境なし</label>
                          </td>
                          <td>
                            <input type="radio" id="network_9" value="9" name="Room_Network[network]" @if($network == 9) checked @endif>
                          </td>
                          <td nowrap>
                            <label for="network_9">不明</label>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td bgcolor="#FFFFFF"><small>有料時の料金情報は備考欄に記載ください。<br />[接続環境なし・不明]をご選択の場合、[接続必要機器・コネクタ・備考欄]で設定された情報はユーザーページに表示されません。</small></td>
                  </tr>
                  <tr class="jqs-default-hide" style="display: none;">

                    <td nowrap bgcolor="#FFFFFF">接続必要機器<br>（パソコン除く）</td>
                    <td bgcolor="#FFFFFF">
                      <table border="0" cellpadding="2" cellspacing="0">
                        <tr>
                          <td>
                            <input type="radio" id="rental_1" value="1" name="Room_Network[rental]" @if($rental == 1) checked @endif>
                          </td>
                          <td nowrap><label for="rental_1">部屋常設（不要）</label></td>
                          <td>
                            <input type="radio" id="rental_2" value="2" name="Room_Network[rental]" @if($rental == 2) checked @endif>
                          </td>
                          <td nowrap><label for="rental_2">無料貸出</label></td>
                          <td><br></td>
                          <td><br></td>
                        </tr>
                        <tr>
                          <td><br></td>
                          <td><br></td>
                          <td>
                            <input type="radio" id="rental_3" value="3" name="Room_Network[rental]" @if($rental == 3) checked @endif>
                          </td>
                          <td nowrap><label for="rental_3">有料貸出</label></td>
                          <td>
                            <input type="radio" id="rental_4" value="4" name="Room_Network[rental]" @if($rental == 4) checked @endif>
                          </td>
                          <td nowrap><label for="rental_4">持ち込み</label></td>
                        </tr>
                      </table>
                    </td>
                    <td bgcolor="#FFFFFF"><small>接続に必要な機器（ケーブルなど）の状況をご選択ください。<br>貸出先着順や料金、機器名などは備考欄に記載ください。</small></td>

                  </tr>
                  <tr class="jqs-default-hide" style="display: none;">
                    <td nowrap bgcolor="#FFFFFF">コネクタ</td>
                    <td bgcolor="#FFFFFF">
                      <table border="0" cellpadding="2" cellspacing="0">
                        <tr>
                          <td>
                            <input type="radio" id="connector_1" value="1" name="Room_Network[connector]" @if($connector == 1) checked @endif>
                          </td>
                          <td nowrap><label for="connector_1">無線</label></td>
                          <td>
                            <input type="radio" id="connector_3" value="3" name="Room_Network[connector]" @if($connector == 2) checked @endif>
                          </td>
                          <td nowrap><label for="connector_3">TEL</label></td>
                          <td>
                            <input type="radio" id="connector_2" value="2" name="Room_Network[connector]" @if($connector == 3) checked @endif>
                          </td>
                          <td nowrap><label for="connector_2">LAN</label></td>
                          <td>
                            <input type="radio" id="connector_4" value="4" name="Room_Network[connector]" @if($connector == 4) checked @endif>
                          </td>
                          <td nowrap><label for="connector_4">その他</label></td>
                        </tr>
                      </table>
                    </td>
                    <td bgcolor="#FFFFFF"><small>無線の種類やその他情報は備考欄に記載ください。</small></td>
                  </tr>
                  <tr class="jqs-default-hide" style="display: none;">
                    <td nowrap bgcolor="#FFFFFF">備考欄</td>
                    <td bgcolor="#FFFFFF">
                      <input type="text" name="Room_Network[network_note]" maxlength="500" size="60" value="">
                    </td>
                    <td bgcolor="#FFFFFF"><small>全角250文字 HTMLタグ不可<br>右記欄には料金情報や貸出し等の補足情報のご記入をお願いします。<br>ネットワーク環境 備考欄記入例）参照</small></td>

                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>