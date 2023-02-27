{include file='../_common/_header.tpl' title='業務提携について'}
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
{include file='./_snv_text.tpl' current='partner'}
{$v->helper->store->add('step', '連絡先および質問などの入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '受け付け完了')}
{$v->helper->store->add('step', '後日担当者より連絡')}
{include file='../_common/_pgc1_steps.tpl' pgc1_steps_current=1}
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">
      <div style="text-align:center;">
      <div style="width:700px; margin:0 auto;text-align:left;">
      <div style="padding:1em 0">
         <h1 style="font-size:150%;font-weight:bold;border-left:4px solid #666;padding:4px;">業務提携について</h1>
      </div>
{* メッセージ *}
{include file='../_common/_message_org.tpl'}

        <div style="text-align:center;">
        <div style="width:550px; margin:0 auto;text-align:left;">
        <div style="margin:2em 0;text-align:center;">
<img border="0" src="{$v->env.root_path}contact/partner/mail-ico.gif" hspace="10" width="30" height="21">
ご質問がありましたらお気軽にどうぞ<img border="0" src="{$v->env.root_path}contact/partner/mail-ico.gif" hspace="10" width="30" height="21"><br>
        </div>
お手数ですが下記のフォームに入力してください。あらかじめ当社<a href="{$v->env.path_base}/about/policy/privacy/" title="プライバシーポリシー" target="_blank">プライバシーポリシー</a>に同意をお願いします。
{include file='./_form_partner.tpl'}
        </div>
        </div>
      </div>
      </div>


    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}