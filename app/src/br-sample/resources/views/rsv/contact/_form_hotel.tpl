<div id="stylized" class="myform">
  <form method="post" action="{$v->env.path_base_module}/contact/hotelconfirm/">
    <ul class="style01">
      <li><label>宿泊施設名&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="hotel_nm" size="60" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.hotel_nm)}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label class="no-req">部署・役職</label>
      <input type="text" name="person_post" size="30" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.person_post)}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm" size="30" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.person_nm)}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名（ふりがな）&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm_kana" size="30" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.person_nm_kana)}"><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>郵便番号&nbsp; <br /><span class="required">【必須】</span></label><span class="t-zip">〒</span>
      <input type="text" name="postal_cd" size="9" maxlength="8" value="{$v->helper->form->strip_tags($v->assign->params.postal_cd)}" class="input-t-half"><span class="r-txt">&nbsp;《半角 例：999-9999》</span></li>

      <li><label>住所&nbsp; <br /><span class="required">【必須】</span></label>
      <select name="pref_id" size="1" class="pd-pref">
        {foreach name=pref_data from=$v->assign->pref_data.values item=pref_data}
          <option value="{$v->helper->form->strip_tags($pref_data.pref_id)}" {if $pref_data.pref_id == $v->assign->params.pref_id} selected{/if}>{$v->helper->form->strip_tags($pref_data.pref_nm)}</option>
        {/foreach}
      </select>
      <input type="text" name="address" size="58" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.address)}" class="input-t-wide"></li>

      <li><label>TEL&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="tel" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.tel)}"><span class="r-txt">&nbsp;《半角 例：9999-9999-9999》 </span></li>

      <li><label class="no-req">FAX</label>
      <input type="text" name="fax" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.fax)}"><span class="r-txt">&nbsp;《半角 例：9999-9999-9999》 </span></li>

      <li><label class="no-req">メールアドレス</label>
      <input type="text" name="email" size="40" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.email)}"><span class="r-txt">&nbsp;《半角 例：xxxx@xxx.xx.xx》</span></li>

      <li><label class="no-req">ホームページURL</label>
      <input type="text" name="url" SIZE="50" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.url)}"></li>

      <li><label>旅館業登録の有無&nbsp; <br /><span class="required">【必須】</span></label>
      <table class="touroku-table">
      <tr>
      <td><input type="radio" name="travel_trade" value="1" id="travel_trade_1" class="radiobtn jqs-tab jqs-saveoff" {if $v->assign->params.travel_trade == 1}checked="checked"{/if}><label for="travel_trade_1" class="radiobtn-lbl">あり</label></td>
      <td><input type="radio" name="travel_trade" value="2" id="travel_trade_2" class="radiobtn jqs-tab jqs-saveoff" {if $v->assign->params.travel_trade == 2}checked="checked"{/if}><label for="travel_trade_2" class="radiobtn-lbl">取得予定</label></td>
      <td width="260" align="left"></td>
      </tr>
      </table>
      <p class="attention1">※本サービスは「旅館業登録」をお持ちの宿泊施設様向けとなっております。</p></li>

      <li name="travel_trade_2_box" {if $v->assign->params.travel_trade != 2}style="display:none;"{/if}><label class="no-req">旅館業登録 取得予定日 <br /><span class="required">【必須】</span></label>
      <input type="text" name="estimate_dtm" size="10" maxlength="100" value="{$v->helper->form->strip_tags($v->assign->params.estimate_dtm)}" class="input-t mb-8">
    </ul>
    <br clear="all" />

    <hr />

    <div class="soufu-box"><label class="soufu-check" for="soufu"><input type="checkbox" name="send_status" id="send_status" value="1" class="soufu-check" {if $v->assign->params.send_status == 1}checked="checked"{/if}>上記以外の宛先へ資料の送付を希望する</label></div>

    <ul{if $v->assign->params.send_status == 1} class="style02"{else} class="style02 grayout"{/if}>
      <li><label>郵便番号&nbsp; <br /><span class="required">【必須】</span></label><span class="t-zip">〒</span>
      <input type="text" name="postal_cd2" id="postal_cd2" size="9" maxlength="8" value="{$v->helper->form->strip_tags($v->assign->params.postal_cd2)}" class="input-t-half"{if $v->assign->params.send_status != 1} disabled{/if}></li>

      <li><label>住所&nbsp; <br /><span class="required">【必須】</span></label>
      <select name="pref_id2" id="pref_id2" size="1" class="pd-pref"{if $v->assign->params.send_status != 1} disabled{/if}>
        {foreach name=pref_data from=$v->assign->pref_data.values item=pref_data}
          <option value="{$v->helper->form->strip_tags($pref_data.pref_id)}" {if $pref_data.pref_id == $v->assign->params.pref_id2} selected{/if}>{$v->helper->form->strip_tags($pref_data.pref_nm)}</option>
        {/foreach}
      </select>
      <input type="text" name="address2" id="address2" size="58" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.address2)}"{if $v->assign->params.send_status != 1} disabled{/if}></li>

      <li><label>宿泊施設名または会社名<br /><span class="required">【必須】</span></label>
      <input type="text" name="hotel_nm2" id="hotel_nm2" size="60" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.hotel_nm2)}"{if $v->assign->params.send_status != 1} disabled{/if}><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label class="no-req">部署・役職</label>
      <input type="text" name="person_post2" id="person_post2" size="30" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.person_post2)}"{if $v->assign->params.send_status != 1} disabled{/if}><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm2" id="person_nm2" size="30" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.person_nm2)}"{if $v->assign->params.send_status != 1} disabled{/if}><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>氏名（ふりがな）&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="person_nm_kana2" id="person_nm_kana2" size="30" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.person_nm_kana2)}"{if $v->assign->params.send_status != 1} disabled{/if}><span class="r-txt">&nbsp;《全角文字》</span></li>

      <li><label>TEL&nbsp; <br /><span class="required">【必須】</span></label>
      <input type="text" name="tel2" id="tel2" size="20" maxlength="20" value="{$v->helper->form->strip_tags($v->assign->params.tel2)}"{if $v->assign->params.send_status != 1} disabled{/if}><span class="r-txt">&nbsp;《半角 例：9999-9999-9999》 </span></li>

      <li><label class="no-req">メールアドレス</label>
      <input type="text" name="email2" id="email2" size="40" maxlength="50" value="{$v->helper->form->strip_tags($v->assign->params.email2)}"{if $v->assign->params.send_status != 1} disabled{/if}><span class="r-txt">&nbsp;《半角 例：xxxx@xxx.xx.xx》</span></li>
    </ul>

    <hr />

    <ul class="style01">
      <li><label class="no-req" style="margin-top:14px;">ご質問等</label><span class="note-cap">《3,000文字まで》</span>
      <textarea type="text" name="note" id="note" rows="8" cols="36" class="plus_a" maxlength="3000" />{$v->helper->form->strip_tags($v->assign->params.note)}</textarea><br clear="all" />
      <p class="attention2">※ご質問に対する回答は、後日弊社担当よりご連絡いたします。</p></li>
      <li><input type="submit" title="入力内容の確認" class="btnimg form-btn-o" value="入力内容の確認" />
      <div class="spacer"></div></li>
    </ul>
  </form>

</div>

