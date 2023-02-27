{include file='../_common/_header.tpl' title='ご意見・ご要望 - ヘルプ' css='_contact_customer.tpl'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
{include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
{include file='./_pgc1_breadcrumbs.tpl'}
{include file='./_snv_text_customer.tpl' current='voice'}
{$v->helper->store->add('step', 'ご意見・ご要望の入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '送信完了')}
{include file='../_common/_pgc1_steps.tpl' pgc1_steps_current=2}
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner advance">

<div style="text-align:center;">
  <div style="margin:0 auto;width:700px;text-align:left;">

    <div id="contact_container" class="clearfix">
      <div id="contact_contents">


          <div class="section">
            <h3 class="title">ご意見・ご要望フォーム</h3>
            <p class="caution">ご意見・ご要望への個別の回答は差し上げていません。予めご了承ください。</p>
            <p>予め当社<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>に同意をお願いします。</p>
            <div id="stylized" class="myform">
                <h4>回答を必要とされる場合はお問い合わせへ</h4>
                <p>回答を必要とされるご意見・ご要望の場合は、恐れ入りますが下記フォームではなく「<a href="/contact/customer/">お問い合わせ</a>」フォームよりご連絡ください。</p>

<label>{$v->assign->category_nm}&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm">{foreach from=$v->assign->categorys key=category_cd item=category_value}
{if ($category_cd == $v->assign->category)}{$category_value}{/if}
{/foreach}</label><br clear="all" />

<label>ご氏名&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm">{$v->helper->form->strip_tags($v->assign->full_nm)}</label><br clear="all" />
{if (!is_empty($v->assign->account_id))}
<label>会員コード<br /></label>
<label class="confirm">{$v->helper->form->strip_tags($v->assign->account_id)}</label><br clear="all" />
{/if}
<label>メールアドレス&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm">{$v->helper->form->strip_tags($v->assign->email)}</label><br clear="all" />

<label>本文&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm_txt">{$v->helper->form->strip_tags($v->assign->note)|nl2br}</label><br clear="all" />

              <div id="confirm_box">
                  <p>上記の内容にて送信してよろしいでしょうか？</p>
                  <form action="{$v->env.path_base_module}/contact/customervoicecomplete/" method="post">
                  <input type="hidden" name="category"   value="{$v->helper->form->strip_tags($v->assign->category)}" />
                  <input type="hidden" name="full_nm"    value="{$v->helper->form->strip_tags($v->assign->full_nm)}" />
                  <input type="hidden" name="account_id" value="{$v->helper->form->strip_tags($v->assign->account_id)}" />
                  <input type="hidden" name="email"      value="{$v->helper->form->strip_tags($v->assign->email)}" />
                  <input type="hidden" name="note"       value="{$v->helper->form->strip_tags($v->assign->note)}" />
                  <button type="submit" title="はい（送信）" class="btnimg">はい（送信）</button>
                </form>
                <form action="{$v->env.path_base_module}/contact/customervoice/" method="post">
                  <input type="hidden" name="category"   value="{$v->helper->form->strip_tags($v->assign->category)}" />
                  <input type="hidden" name="full_nm"    value="{$v->helper->form->strip_tags($v->assign->full_nm)}" />
                  <input type="hidden" name="account_id" value="{$v->helper->form->strip_tags($v->assign->account_id)}" />
                  <input type="hidden" name="email"      value="{$v->helper->form->strip_tags($v->assign->email)}" />
                  <input type="hidden" name="note"       value="{$v->helper->form->strip_tags($v->assign->note)}" />
                  <button type="submit" title="いいえ（戻る）" class="btnimg">いいえ（戻る）</button>
                </form>
              </div>

           <div class="spacer clearfix"></div>
           </div>
        </div>

      </div>
    </div>

  </div>
</div>

    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}