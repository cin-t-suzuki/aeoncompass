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
{$v->helper->store->add('step', 'ご意見・ご要望の入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '送信完了')}
{include file='../_common/_pgc1_steps.tpl' pgc1_steps_current=3}
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
              <form id="form" name="form" method="post" action="{$v->env.base_path}{$v->env.module}/contact/customervoiceconfirm/">
                <h4>回答を必要とされる場合はお問い合わせへ</h4>
                <p>回答を必要とされるご意見・ご要望の場合は、恐れ入りますが下記フォームではなく「<a href="/contact/customer/">お問い合わせ</a>」フォームよりご連絡ください。</p>
                <label>&nbsp;</label>
                <label class="thx1">無事、送信を完了いたしました。</label><br clear="all" />

                <label>&nbsp;</label>
                <label class="thx2">貴重なご意見・ご要望をお寄せいただきありがとうございます。<br />
                  いただいたご意見・ご要望をもとに、サービスの改善と向上に<br />取り組んでまいります。<br /><br />
                  今後ともベストリザーブ・宿ぷらざをよろしくお願いいたします。</label><br clear="all" />
                <label>&nbsp;</label>
                <label class="thx1"><a href="{$v->env.path_base}/">＞TOPページへ移動する</a></label><br clear="all" />

             <div class="spacer"></div>
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