{include file='../_common/_header.tpl' title='ご意見・ご要望 - ヘルプ' css='_contact_customer.tpl'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

<div id="pgh2v2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
{include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

<div id="pgc1v2">
  <div class="pg">
    <div class="pgc1-inner">
{include file='./_pgc1_breadcrumbs.tpl'}
{include file='./_snv_text_customer.tpl' current='voice'}
{if $v->error->has()}
{$v->helper->store->add('step', 'ご意見・ご要望の入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '送信完了')}
{include file='../_common/_pgc1_steps.tpl' pgc1_steps_current=1}
{/if}
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner advance">

<div style="text-align:center;">
  <div style="margin:0 auto;width:700px;text-align:left;">

{if (is_empty($v->assign->category))}
    <div style="padding:1em 0">
      <h2 id="h2_title">ご意見・ご要望</h2>
    </div>

<div id="txt_info">お客様からお寄せいただきましたご意見・ご要望は、当社ならびにベストリザーブ・宿ぷらざのサービス改善に活かしております。いただいたお客様の声に対し、速やかに内容を確認、社員への指導などに活かすだけでなく、必要な対策を検討・実施し、今後のサービス改善・向上に取り組んでいます。</div>
{/if}

<div id="contact_container" class="clearfix">
<div id="contact_contents">

{if (is_empty($v->assign->category))}

          <div class="section">
            <h3 class="title">貴重なお客様の声、お聞かせください。</h3>
            <p>下記の内容のほかにも、サービス改善・向上に役立つご意見・ご要望を広く受け付けています。</p>
            <ul>
              <li><b>機能改善してほしい</b>&nbsp; （例）予約時にクレジットカード情報を毎回入力するのが面倒</li>
              <li><b>サービスや機能を追加してほしい</b>&nbsp;  （例）Tポイントやポンタなどの共通ポイントプログラムを利用したい</li>
              <li><b>企画のアイデア</b>&nbsp;  （例）ペット同伴なのでペットと泊まれるホテルの特集を組んでほしい</li>
              <li><b>その他</b>&nbsp;  （例）お気に入りの京都の○○旅館を掲載してほしい</li>
            </ul>
          </div>

          <div class="section">
            <h3 class="title">ご利用案内</h3>
            <ul class="guide">
              <li>お客様より提供いただいた個人情報につきましては、お客様への連絡・回答あるいは個人を特定できないよう加工した資料を作成・開示すること以外の目的に使用することはありません。<br />
                  個人情報の管理につきましては、<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>をご覧ください。</li>
              <li>商品セールス、資機材の斡旋等の弊社へのご意見・ご要望以外の内容を送信することは、固くお断りします。</li>
              <li>ご意見・ご要望への個別の回答は差し上げていません。予めご了承ください。</li>
            </ul>
          </div>
{/if}

          <div class="section">
            <h3 class="title">ご意見・ご要望フォーム</h3>
            <p class="caution">ご意見・ご要望への個別の回答は差し上げていません。予めご了承ください。</p>
            <p>予め当社<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>に同意をお願いします。</p>

{if ($v->error->has())}
            <div class="ei">{foreach from=$v->error->gets() item=error}
            ※{$error}<br />
            {/foreach}
            </div>
{/if}

            <div id="stylized" class="myform">
              <form id="form" name="form" method="post" action="{$v->env.path_base_module}/contact/customervoiceconfirm/">
                <h4>回答を必要とされる場合はお問い合わせへ</h4>
                <p>回答を必要とされるご意見・ご要望の場合は、恐れ入りますが下記フォームではなく「<a href="/contact/customer/">お問い合わせ</a>」フォームよりご連絡ください。</p>
                <label>{$v->assign->category_nm}&nbsp; <br /><span class="required">【必須】</span></label>
                <select name="category" id="category" class="voice">
                {foreach from=$v->assign->categorys key=category_cd item=category_value}
                <option value="{$category_cd}"{if ($category_cd == $v->assign->category)} selected="selected"{/if} >{$category_value}</option>
                {/foreach}
                </select><br clear="all" />

                <label>ご氏名&nbsp; <br /><span class="required">【必須】</span></label>
                <input type="text" name="full_nm" id="full_nm" value="{$v->helper->form->strip_tags($v->assign->full_nm)}" /><br clear="all" />

                <label>会員コード<br /></label>
                <input type="text" name="account_id" id="account_id" value="{$v->helper->form->strip_tags($v->assign->account_id)}" /><br clear="all" />

                <label>メールアドレス&nbsp; <br /><span class="required">【必須】</span></label>
                <input type="text" name="email" id="email" value="{$v->helper->form->strip_tags($v->assign->email)}" /><br clear="all" />

                <label>本文&nbsp; <br /><span class="required">【必須】</span></label>
                <textarea type="text" name="note" id="voice" rows="8" cols="36" class="voice" />{$v->helper->form->strip_tags($v->assign->note)}</textarea><br clear="all" />
                <button type="submit" title="送信する">送信する</button>
                <div class="spacer"></div>
              </form>
            </div>

        </div>

{if (is_empty($v->assign->category))}
        <div><p class="return"><a href="#top">↑ページのトップに戻る</a></p></div>
{/if}
      </div>
    </div>

  </div>
</div>

    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}