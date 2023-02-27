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
{include file='../_common/_pgc1_steps.tpl' pgc1_steps_current=3}
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

{literal}
      <style type="text/css">
.ei { margin: 1em 0; padding: 0.8em 1.2em; border: 2px solid #900; color: #900; background-color: #FFF; line-height: 1.25em; }
.ei ul, .ei ol { margin: 0 1em; padding:0 1em; }
.gi { margin: 1em 0; padding: 0.8em 1.2em; border: 2px solid #009; color: #009; background-color: #FFF; line-height: 1.25em; }
.gi ul, .gi ol { margin: 0 1em; padding:0 1em; }
      </style>
{/literal}
<div class="gi">
ご質問を受け付けました。<br />後日弊社担当よりご連絡いたします。
</div>

      </div>
      </div>


    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}