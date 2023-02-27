@inject('service', 'App\Http\Controllers\rsv\ContactController')

{{-- {include file='../_common/_header.tpl' title='ご意見・ご要望 - ヘルプ' css='_contact_customer.tpl'} --}}
@extends('rsv.common.base', ['title' => 'ご意見・ご要望 - ヘルプ'])
@include ('rsv.common._pgh1', ['pgh1_mnv' => 1])
@section('page_css')
  {{-- 元ソースはヘッダーに変数渡して判断させているが、共通ヘッダーにはその機能なし→直読込に変更 --}}
  {{-- <link type="text/css" rel="stylesheet" href="https://www.bestrsv.com/contact/customer/css/module.css" /> --}}
  <link rel="stylesheet" href="{{ asset('css/contact/customer/css/module.css') }}">
@endsection

@section('page_blade')

<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
@include ('rsv.common._pgh2_inner')
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
@include ('rsv.contact._pgc1_breadcrumbs')
@include ('rsv.contact._snv_text_customer', ['current' => 'voice'])
{{-- {$v->helper->store->add('step', 'ご意見・ご要望の入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '送信完了')} --}}
{{-- 書き替えあっているか？（include先含め） --}}
@php $step = ['ご意見・ご要望の入力', '入力内容の確認', '送信完了'] @endphp
@include ('rsv.common._pgc1_steps', ['pgc1_steps_current' => 2])
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

<label>{{$category_nm}}&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm">@foreach ($categorys as $category_cd => $category_value)
@if ($category_cd == $category){{$category_value}}@endif
@endforeach</label><br clear="all" />

<label>ご氏名&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm">{{strip_tags($full_nm)}}</label><br clear="all" />
@if (!$service->is_empty($account_id))
<label>会員コード<br /></label>
<label class="confirm">{{strip_tags($account_id)}}</label><br clear="all" />
@endif
<label>メールアドレス&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm">{{strip_tags($email)}}</label><br clear="all" />

<label>本文&nbsp; <br /><span class="required">【必須】</span></label>
<label class="confirm_txt">{{--strip_tags($note)|nl2br--}}{!! nl2br(e(strip_tags($note))) !!}</label><br clear="all" />

              <div id="confirm_box">
                  <p>上記の内容にて送信してよろしいでしょうか？</p>
                  {!! Form::open(['route' => ['rsv.contact.customerVoiceComplete'], 'method' => 'post']) !!}
                  <input type="hidden" name="category"   value="{{strip_tags($category)}}" />
                  <input type="hidden" name="full_nm"    value="{{strip_tags($full_nm)}}" />
                  <input type="hidden" name="account_id" value="{{strip_tags($account_id)}}" />
                  <input type="hidden" name="email"      value="{{strip_tags($email)}}" />
                  <input type="hidden" name="note"       value="{{strip_tags($note)}}" />
                  <button type="submit" title="はい（送信）" class="btnimg">はい（送信）</button>
                  {!! Form::close() !!}
                {!! Form::open(['route' => ['rsv.contact.customerVoice'], 'method' => 'get']) !!}
                  <input type="hidden" name="category"   value="{{strip_tags($category)}}" />
                  <input type="hidden" name="full_nm"    value="{{strip_tags($full_nm)}}" />
                  <input type="hidden" name="account_id" value="{{strip_tags($account_id)}}" />
                  <input type="hidden" name="email"      value="{{strip_tags($email)}}" />
                  <input type="hidden" name="note"       value="{{strip_tags($note)}}" />
                  <button type="submit" title="いいえ（戻る）" class="btnimg">いいえ（戻る）</button>
                {!! Form::close() !!}
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


{{-- {include file='../_common/_footer.tpl'} --}}
@endsection