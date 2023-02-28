{{-- {include file='../_common/_header.tpl' title='送信内容確認 - 宿泊施設関係者の方へ・ベストリザーブ・宿ぷらざ 参画のご案内'} --}}
@extends('rsv.common.base', ['title' => '宿泊施設関係者の方へ・ベストリザーブ・宿ぷらざ 参画のご案内'])
@include ('rsv.common._pgh1', ['pgh1_mnv' => 1])

<div id="pgh2v2">
  <div class="pg">
    <div class="pgh2-inner">
@include ('rsv.common._pgh2_inner')
    </div>
  </div>
</div>

<div id="pgc1v2">
  <div class="pg">
    <div class="pgc1-inner">
@include ('rsv.contact._pgc1_breadcrumbs')
@include ('rsv.contact._snv_text', ['current' => 'hotel'])
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner advance">
      <div style="text-align:center;">
      <div style="width:700px; margin:0 auto;text-align:left;">
      <div id="contact_container" class="clearfix">
      <div id="contact_contents">

        <div class="section">
          <h3 class="title">資料請求</h3>

          <ul id="step">
          <li class="step-1-1">資料請求内容の入力</li>
          <li class="step-2-0">入力内容の確認</li>
          <li class="step-3-1">お手続き完了</li>
          </ul>

          <div id="stylized" class="myform" style="padding:26px 0 20px 28px;">

            {if $v->assign->send_status == 1}
              <p class="confirm-title">＜宿泊施設様情報＞</p>
            {else}
              <p class="confirm-title">＜宿泊施設様情報・送付先＞</p>
            {/if}

            <label>宿泊施設名&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->hotel_nm)}</label><br clear="all" />
            
            <label class="no-req">部署・役職</label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->person_post)}</label><br clear="all" />
            
            <label>氏名&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->person_nm)}</label><br clear="all" />
            
            <label>氏名（ふりがな）&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->person_nm_kana)}</label><br clear="all" />
            
            <label>郵便番号&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->postal_cd)}</label><br clear="all" />
            
            <label>住所（都道府県）&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->pref_nm)}</label><br clear="all" />
            
            <label>住所&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->address)}</label><br clear="all" />
            
            <label>TEL&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->tel)}</label><br clear="all" />
            
            <label class="no-req">FAX</label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->fax)}</label><br clear="all" />
            
            <label class="no-req">メールアドレス</label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->email)}</label><br clear="all" />
            
            <label class="no-req">ホームページURL</label>
            <label class="confirm">{$v->helper->form->strip_tags($v->assign->url)}</label><br clear="all" />
            
            <label>旅館業登録の有無&nbsp; <br /><span class="required">【必須】</span></label>
            <label class="confirm">{if $v->assign->travel_trade == 1}あり{elseif $v->assign->travel_trade == 2}取得予定{/if}</label><br clear="all" />

            {if $v->assign->travel_trade == 2}
              <label class="no-req">旅館業登録 取得予定日</label>
              <label class="confirm">{$v->assign->estimate_dtm}</label><br clear="all" />
            {/if}

            {if $v->assign->send_status == 1}

              <br clear="all" />
              <p class="confirm-title">＜送付先＞</p>
              
              <label>郵便番号&nbsp; <br /><span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->postal_cd2)}</label><br clear="all" />
              
              <label>住所（都道府県）<br /><span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->pref_nm2)}</label><br clear="all" />
              
              <label>住所&nbsp; <br /><span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->address2)}</label><br clear="all" />
              
              <label>宿泊施設名また&nbsp; <br />は会社名<span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->hotel_nm2)}</label><br clear="all" />
              
              <label class="no-req">部署・役職</label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->person_post2)}</label><br clear="all" />
              
              <label>氏名&nbsp; <br /><span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->person_nm2)}</label><br clear="all" />
              
              <label>氏名（ふりがな）<br /><span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->person_nm_kana2)}</label><br clear="all" />
              
              <label>TEL&nbsp; <br /><span class="required">【必須】</span></label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->tel2)}</label><br clear="all" />
              
              <label class="no-req">メールアドレス</label>
              <label class="confirm">{$v->helper->form->strip_tags($v->assign->email2)}</label><br clear="all" />
              
            {/if}

            <label class="no-req">ご質問等</label>
            <label class="confirm_txt">{if is_empty($v->helper->form->strip_tags($v->assign->note))}なし{else}{$v->helper->form->strip_tags($v->assign->note)|nl2br}{/if}</label><br clear="all" />

            <div id="confirm_box">
              <p>上記内容で資料を発送させていただきます。よろしいですか？</p>
              <form action="{$v->env.path_base_module}/contact/hotelcomplete/" method="post">
                <input type="hidden" name="hotel_nm" value="{      $v->helper->form->strip_tags($v->assign->hotel_nm)}" />
                <input type="hidden" name="person_post" value="{   $v->helper->form->strip_tags($v->assign->person_post)}" />
                <input type="hidden" name="person_nm" value="{     $v->helper->form->strip_tags($v->assign->person_nm)}" />
                <input type="hidden" name="person_nm_kana" value="{$v->helper->form->strip_tags($v->assign->person_nm_kana)}" />
                <input type="hidden" name="postal_cd" value="{     $v->helper->form->strip_tags($v->assign->postal_cd)}" />
                <input type="hidden" name="pref_id" value="{       $v->helper->form->strip_tags($v->assign->pref_id)}" />
                <input type="hidden" name="address" value="{       $v->helper->form->strip_tags($v->assign->address)}" />
                <input type="hidden" name="tel" value="{           $v->helper->form->strip_tags($v->assign->tel)}" />
                <input type="hidden" name="fax" value="{           $v->helper->form->strip_tags($v->assign->fax)}" />
                <input type="hidden" name="email" value="{         $v->helper->form->strip_tags($v->assign->email)}" />
                <input type="hidden" name="url" value="{           $v->helper->form->strip_tags($v->assign->url)}" />
                <input type="hidden" name="travel_trade" value="{  $v->helper->form->strip_tags($v->assign->travel_trade)}" />
                <input type="hidden" name="estimate_dtm" value="{  $v->helper->form->strip_tags($v->assign->estimate_dtm)}" />
                <input type="hidden" name="send_status" value="{     $v->helper->form->strip_tags($v->assign->send_status)}" />

                <input type="hidden" name="postal_cd2" value="{     $v->helper->form->strip_tags($v->assign->postal_cd2)}" />
                <input type="hidden" name="pref_id2" value="{       $v->helper->form->strip_tags($v->assign->pref_id2)}" />
                <input type="hidden" name="address2" value="{       $v->helper->form->strip_tags($v->assign->address2)}" />
                <input type="hidden" name="hotel_nm2" value="{      $v->helper->form->strip_tags($v->assign->hotel_nm2)}" />
                <input type="hidden" name="person_post2" value="{   $v->helper->form->strip_tags($v->assign->person_post2)}" />
                <input type="hidden" name="person_nm2" value="{     $v->helper->form->strip_tags($v->assign->person_nm2)}" />
                <input type="hidden" name="person_nm_kana2" value="{$v->helper->form->strip_tags($v->assign->person_nm_kana2)}" />
                <input type="hidden" name="tel2" value="{           $v->helper->form->strip_tags($v->assign->tel2)}" />
                <input type="hidden" name="email2" value="{         $v->helper->form->strip_tags($v->assign->email2)}" />
                <input type="hidden" name="note" value="{           $v->helper->form->strip_tags($v->assign->note)}" />
                <input type="submit" title="はい" value="はい" class="btnimg form-btn-o" />
              </form>
              <form action="{$v->env.path_base_module}/contact/hotel/" method="post">
                <input type="hidden" name="hotel_nm" value="{      $v->helper->form->strip_tags($v->assign->hotel_nm)}" />
                <input type="hidden" name="person_post" value="{   $v->helper->form->strip_tags($v->assign->person_post)}" />
                <input type="hidden" name="person_nm" value="{     $v->helper->form->strip_tags($v->assign->person_nm)}" />
                <input type="hidden" name="person_nm_kana" value="{$v->helper->form->strip_tags($v->assign->person_nm_kana)}" />
                <input type="hidden" name="postal_cd" value="{     $v->helper->form->strip_tags($v->assign->postal_cd)}" />
                <input type="hidden" name="pref_id" value="{       $v->helper->form->strip_tags($v->assign->pref_id)}" />
                <input type="hidden" name="address" value="{       $v->helper->form->strip_tags($v->assign->address)}" />
                <input type="hidden" name="tel" value="{           $v->helper->form->strip_tags($v->assign->tel)}" />
                <input type="hidden" name="fax" value="{           $v->helper->form->strip_tags($v->assign->fax)}" />
                <input type="hidden" name="email" value="{         $v->helper->form->strip_tags($v->assign->email)}" />
                <input type="hidden" name="url" value="{           $v->helper->form->strip_tags($v->assign->url)}" />
                <input type="hidden" name="travel_trade" value="{  $v->helper->form->strip_tags($v->assign->travel_trade)}" />
                <input type="hidden" name="estimate_dtm" value="{  $v->helper->form->strip_tags($v->assign->estimate_dtm)}" />
                <input type="hidden" name="send_status" value="{     $v->helper->form->strip_tags($v->assign->send_status)}" />

                <input type="hidden" name="postal_cd2" value="{     $v->helper->form->strip_tags($v->assign->postal_cd2)}" />
                <input type="hidden" name="pref_id2" value="{       $v->helper->form->strip_tags($v->assign->pref_id2)}" />
                <input type="hidden" name="address2" value="{       $v->helper->form->strip_tags($v->assign->address2)}" />
                <input type="hidden" name="hotel_nm2" value="{      $v->helper->form->strip_tags($v->assign->hotel_nm2)}" />
                <input type="hidden" name="person_post2" value="{   $v->helper->form->strip_tags($v->assign->person_post2)}" />
                <input type="hidden" name="person_nm2" value="{     $v->helper->form->strip_tags($v->assign->person_nm2)}" />
                <input type="hidden" name="person_nm_kana2" value="{$v->helper->form->strip_tags($v->assign->person_nm_kana2)}" />
                <input type="hidden" name="tel2" value="{           $v->helper->form->strip_tags($v->assign->tel2)}" />
                <input type="hidden" name="email2" value="{         $v->helper->form->strip_tags($v->assign->email2)}" />
                <input type="hidden" name="note" value="{           $v->helper->form->strip_tags($v->assign->note)}" />
                <input type="submit" title="もどる" value="もどる" class="btnimg form-btn-n" />
              </form>
            </div>

            <div class="spacer clearfix"></div>
          </div>

        </div>

        <div><p class="return"><a href="#top">↑ページのトップに戻る</a></p></div>

      </div>
      </div>
      </div>
      </div>
    
    </div>
  </div>
</div>


{{-- {include file='../_common/_footer.tpl'} --}}
@endsection