<div id="stylized" class="myform">
  {!! Form::open(['route' => ['rsv.contact.hotelConfirm'], 'method' => 'get']) !!}
    <ul class="style01">
      <li><label>宿泊施設名&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="hotel_nm" size="60" maxlength="50" value="{{strip_tags(old('hotel_nm', $params['hotel_nm'] ?? null))}}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label class="no-req">部署・役職</label>
      <input type="text" name="person_post" size="30" maxlength="50" value="{{strip_tags(old('person_post', $params['person_post'] ?? null))}}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm" size="30" maxlength="20" value="{{strip_tags(old('person_nm', $params['person_nm'] ?? null))}}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名（ふりがな）&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm_kana" size="30" maxlength="20" value="{{strip_tags(old('person_nm_kana', $params['person_nm_kana'] ?? null))}}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>郵便番号&nbsp; <br /><span class="required">【必須】</span></label><span class="t-zip">〒</span>
      <input type="text" name="postal_cd" size="9" maxlength="8" value="{{strip_tags(old('postal_cd', $params['postal_cd'] ?? null))}}" class="input-t-half"><span class="r-txt">&nbsp;《半角 例：999-9999》</span></li>

      <li><label>住所&nbsp; <br /><span class="required">【必須】</span></label>
      <select name="pref_id" size="1" class="pd-pref">
        @foreach ($pref_data['values'] as $prefs)
          <option value="{{strip_tags($prefs['pref_id'])}}" @if ($prefs['pref_id'] == ($params['pref_id'] ?? null)) selected @endif>{{strip_tags($prefs['pref_nm'])}}</option>
        @endforeach
      </select>
      <input type="text" name="address" size="58" maxlength="50" value="{{strip_tags(old('address', $params['address'] ?? null))}}" class="input-t-wide"></li>

      <li><label>TEL&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="tel" size="20" maxlength="20" value="{{strip_tags(old('tel', $params['tel'] ?? null))}}"><span class="r-txt">&nbsp;《半角 例：9999-9999-9999》 </span></li>

      <li><label class="no-req">FAX</label>
      <input type="text" name="fax" size="20" maxlength="20" value="{{strip_tags(old('fax', $params['fax'] ?? null))}}"><span class="r-txt">&nbsp;《半角 例：9999-9999-9999》 </span></li>

      <li><label class="no-req">メールアドレス</label>
      <input type="text" name="email" size="40" maxlength="50" value="{{strip_tags(old('email', $params['email'] ?? null))}}"><span class="r-txt">&nbsp;《半角 例：xxxx@xxx.xx.xx》</span></li>

      <li><label class="no-req">ホームページURL</label>
      <input type="text" name="url" SIZE="50" maxlength="50" value="{{strip_tags(old('url', $params['url'] ?? null))}}"></li>

      <li><label>旅館業登録の有無&nbsp; <br /><span class="required">【必須】</span></label>
      <table class="touroku-table">
      <tr>
      <td><input type="radio" name="travel_trade" value="1" id="travel_trade_1" class="radiobtn jqs-tab jqs-saveoff" @if ((old('travel_trade',$params['travel_trade'] ?? null)) == 1)checked="checked" @endif><label for="travel_trade_1" class="radiobtn-lbl">あり</label></td>
      <td><input type="radio" name="travel_trade" value="2" id="travel_trade_2" class="radiobtn jqs-tab jqs-saveoff" @if ((old('travel_trade',$params['travel_trade'] ?? null)) == 2)checked="checked" @endif><label for="travel_trade_2" class="radiobtn-lbl">取得予定</label></td>
      <td width="260" align="left"></td>
      </tr>
      </table>
      <p class="attention1">※本サービスは「旅館業登録」をお持ちの宿泊施設様向けとなっております。</p></li>

      <li name="travel_trade_2_box" id="travel_trade_2_box" @if ((old('travel_trade',$params['travel_trade'] ?? null)) != 2) style="display:none;" @endif><label class="no-req">旅館業登録 取得予定日 <br /><span class="required">【必須】</span></label>
      <input type="text" name="estimate_dtm" size="10" maxlength="100" value="{{strip_tags(old('estimate_dtm', $params['estimate_dtm'] ?? null))}}" class="input-t mb-8">
    </ul>
    <br clear="all" />

    <hr />

    <div class="soufu-box"><label class="soufu-check" for="soufu"><input type="checkbox" name="send_status" id="send_status" value="1" class="soufu-check" @if ((old('send_status',$params['send_status'] ?? null)) == 1) checked="checked"@endif>上記以外の宛先へ資料の送付を希望する</label></div>

    <ul @if ((old('send_status',$params['send_status'] ?? null)) == 1) class="style02" @else class="style02 grayout"@endif>
      <li><label>郵便番号&nbsp; <br /><span class="required">【必須】</span></label><span class="t-zip">〒</span>
      <input type="text" name="postal_cd2" id="postal_cd2" size="9" maxlength="8" value="{{strip_tags(old('postal_cd2', $params['postal_cd2'] ?? null))}}" class="input-t-half"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif></li>

      <li><label>住所&nbsp; <br /><span class="required">【必須】</span></label>
      <select name="pref_id2" id="pref_id2" size="1" class="pd-pref"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif>
        @foreach ($pref_data['values'] as $prefs)
          <option value="{{strip_tags($prefs['pref_id'])}}" @if ($prefs['pref_id'] == ($params['pref_id2'] ?? null)) selected @endif>{{strip_tags($prefs['pref_nm'])}}</option>
        @endforeach
      </select>
      <input type="text" name="address2" id="address2" size="58" maxlength="50" value="{{strip_tags(old('address2', $params['address2'] ?? null))}}"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif></li>

      <li><label>宿泊施設名または会社名<br /><span class="required">【必須】</span></label>
      <input type="text" name="hotel_nm2" id="hotel_nm2" size="60" maxlength="50" value="{{strip_tags(old('hotel_nm2', $params['hotel_nm2'] ?? null))}}" @if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label class="no-req">部署・役職</label>
      <input type="text" name="person_post2" id="person_post2" size="30" maxlength="50" value="{{strip_tags(old('person_post2', $params['person_post2'] ?? null))}}"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm2" id="person_nm2" size="30" maxlength="20" value="{{strip_tags(old('person_nm2', $params['person_nm2'] ?? null))}}"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名（ふりがな）&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm_kana2" id="person_nm_kana2" size="30" maxlength="20" value="{{strip_tags(old('person_nm_kana2', $params['person_nm_kana2'] ?? null))}}"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>TEL&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="tel2" id="tel2" size="20" maxlength="20" value="{{strip_tags(old('tel2', $params['tel2'] ?? null))}}"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif><span class="r-txt">&nbsp;《半角 例：9999-9999-9999》 </span></li>

      <li><label class="no-req">メールアドレス</label>
      <input type="text" name="email2" id="email2" size="40" maxlength="50" value="{{strip_tags(old('email2', $params['email2'] ?? null))}}"@if ((old('send_status',$params['send_status'] ?? null)) != 1) disabled @endif><span class="r-txt">&nbsp;《半角 例：xxxx@xxx.xx.xx》</span></li>
    </ul>

    <hr />

    <ul class="style01">
      <li><label class="no-req" style="margin-top:14px;">ご質問等</label><span class="note-cap">《3,000文字まで》</span>
      <textarea type="text" name="note" id="note" rows="8" cols="36" class="plus_a" maxlength="3000" />{{strip_tags(old('note', $params['note'] ?? null))}}</textarea><br clear="all" />
      <p class="attention2">※ご質問に対する回答は、後日弊社担当よりご連絡いたします。</p></li>
      <li><input type="submit" title="入力内容の確認" class="btnimg form-btn-o" value="入力内容の確認" />
      <div class="spacer"></div></li>
    </ul>
  {!! Form::close() !!}

</div>



