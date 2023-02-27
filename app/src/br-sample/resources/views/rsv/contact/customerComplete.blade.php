@inject('service', 'App\Http\Controllers\rsv\ContactController')

{{--cssは？ {include file='../_common/_header.tpl' title='お問い合わせ - ヘルプ' css='_contact_customer.tpl'} --}}
@extends('rsv.common.base', ['title' => 'お問い合わせ - ヘルプ'])
@include ('rsv.common._pgh1', ['pgh1_mnv' => 1])
@section('page_css')
  {{-- 元ソースはヘッダーに変数渡して判断させているが、共通ヘッダーにはその機能なし→直読込に変更 --}}
  {{-- <link type="text/css" rel="stylesheet" href="https://www.bestrsv.com/contact/customer/css/module.css" /> --}}
  <link rel="stylesheet" href="{{ asset('css/contact/customer/css/module.css') }}">
@endsection

@section('page_blade')

<div id="pgh2v2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
@include ('rsv.common._pgh2_inner')
  </div>
</div>

<div id="pgc1v2">
  <div class="pg">
    <div class="pgc1-inner">
@include ('rsv.contact._pgc1_breadcrumbs')
@include ('rsv.contact._snv_text_customer', ['current' => 'customer'])
{{-- {$v->helper->store->add('step', 'お問い合わせ内容の入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '送信完了')}
{$v->helper->store->add('step', '後日担当者より連絡')} --}}
{{-- 書き替えあっているか？（include先含め） --}}
@php $step = ['お問い合わせ内容の入力', '入力内容の確認', '送信完了', '後日担当者より連絡'] @endphp
@include ('rsv.common._pgc1_steps', ['pgc1_steps_current' => 3])
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
            <h3 class="title">お問い合わせフォーム</h3>
            <p class="caution">原則として、お電話によるお問い合わせ、及び回答は行っておりませんので、ご了承ください。</p>
            <p>予め当社<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>に同意をお願いします。</p>
            <div id="stylized" class="myform">
                <h4>会員情報を照会される場合のご注意</h4>
                <p>「<a href="{$v->env.ssl_path}{$v->env.module}/reminder/ptm11/">会員コード・パスワードの照会</a>」フォームをご利用ください。照会できない場合は下記フォームへご登録の「氏名」「生年月日」「電話番号」を本文にご入力の上、お問い合わせください。会員様の特定ができましたら、ご登録のメールアドレスへ会員コード・パスワードをご案内させていただきます。</p>
                <label>&nbsp;</label>
                <label class="thx1">送信を完了いたしました。</label><br clear="all" />

                <label>&nbsp;</label>
                <label class="thx2">お問い合わせいただきありがとうございます。<br />
                後ほど担当より折り返しご連絡を差し上げます。窓口の混雑状況により<br />
                回答に時間がかかる場合がありますが、予めご了承ください。<br /><br />
                今後ともイオントラベルモールをよろしくお願いいたします。</label><br clear="all" />

                <label>&nbsp;</label>
                <label class="thx1"><a href="/">＞TOPページへ移動する</a></label><br clear="all" />

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


@endsection