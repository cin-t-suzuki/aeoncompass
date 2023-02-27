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
            <h3 class="title">お問い合わせフォーム</h3>
            <p class="caution">原則として、お電話によるお問い合わせ、及び回答は行っておりませんので、ご了承ください。</p>
            <p>予め当社<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>に同意をお願いします。</p>
            <div id="stylized" class="myform">
                <h4>会員情報を照会される場合のご注意</h4>
                <p>「<a href="{$v->env.ssl_path}{$v->env.module}/reminder/ptm11/">会員コード・パスワードの照会</a>」フォームをご利用ください。照会できない場合は下記フォームへご登録の「氏名」「生年月日」「電話番号」を本文にご入力の上、お問い合わせください。会員様の特定ができましたら、ご登録のメールアドレスへ会員コード・パスワードをご案内させていただきます。</p>
<label>{{$category_nm}}&nbsp; </label>
<label class="confirm">@foreach ($categorys as $category_cd => $category_value)
@if ($category_cd == $category){{$category_value}}@endif
@endforeach</label><br clear="all" />

<label>ご氏名&nbsp; </label>
<label class="confirm">{{strip_tags($full_nm)}}</label><br clear="all" />

@if (!$service->is_empty($account_id))
<label>会員コード&nbsp;</label>
<label class="confirm">{{strip_tags($account_id)}}</label><br clear="all" />
@endif

<label>ご返信用メールアドレス&nbsp; </label>
<label class="confirm">{{strip_tags($email)}}</label><br clear="all" />

@if (!$service->is_empty($rsv_cd))
<label>予約コード&nbsp;</label>
<label class="confirm">{{strip_tags($rsv_cd)}}</label><br clear="all" />
@endif

@if (!$service->is_empty($hotel_nm))
<label>ご予約された施設名&nbsp;</label>
<label class="confirm">{{strip_tags($hotel_nm)}}</label><br clear="all" />
@endif

@if (!$service->is_empty($date_ymd))
<label>ご宿泊日&nbsp;</label>
<label class="confirm">{{strip_tags($date_ymd)}}</label><br clear="all" />
@endif

@if (!$service->is_empty($guest_nm))
<label>宿泊代表者かな氏名&nbsp;</label>
<label class="confirm">{{strip_tags($guest_nm)}}</label><br clear="all" />
@endif

<label>本文&nbsp; </label>
<label class="confirm_txt">{{--strip_tags($note)|nl2br--}}{!! nl2br(e(strip_tags($note))) !!}</label><br clear="all" />

              <div id="confirm_box">
                  <p>上記の内容にて送信してよろしいでしょうか？</p>
                {!! Form::open(['route' => ['rsv.contact.customerComplete'], 'method' => 'post']) !!}
                  <input type="hidden" name="category"   value="{{strip_tags($category)}}" />
                  <input type="hidden" name="full_nm"    value="{{strip_tags($full_nm)}}" />
                  <input type="hidden" name="account_id" value="{{strip_tags($account_id)}}" />
                  <input type="hidden" name="email"      value="{{strip_tags($email)}}" />
                  @if (!$service->is_empty($rsv_cd))<input type="hidden" name="rsv_cd"      value="{{strip_tags($rsv_cd)}}" />@endif
                  @if (!$service->is_empty($hotel_nm))<input type="hidden" name="hotel_nm"    value="{{strip_tags($hotel_nm)}}" />@endif
                  @if (!$service->is_empty($date_ymd))<input type="hidden" name="date_ymd"    value="{{strip_tags($date_ymd)}}" />@endif
                  @if (!$service->is_empty($guest_nm))<input type="hidden" name="guest_nm"    value="{{strip_tags($guest_nm)}}" />@endif
                  <input type="hidden" name="note"       value="{{strip_tags($note)}}" />
                  <button type="submit" title="はい（送信）" class="btnimg">はい（送信）</button>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['rsv.contact.customer'], 'method' => 'get']) !!}
                  <input type="hidden" name="category"   value="{{strip_tags($category)}}" />
                  <input type="hidden" name="full_nm"    value="{{strip_tags($full_nm)}}" />
                  <input type="hidden" name="account_id" value="{{strip_tags($account_id)}}" />
                  <input type="hidden" name="email"      value="{{strip_tags($email)}}" />
                  @if (!$service->is_empty($rsv_cd))<input type="hidden" name="rsv_cd"      value="{{strip_tags($rsv_cd)}}" />@endif
                  @if (!$service->is_empty($hotel_nm))<input type="hidden" name="hotel_nm"    value="{{strip_tags($hotel_nm)}}" />@endif
                  @if (!$service->is_empty($date_ymd))<input type="hidden" name="date_ymd"    value="{{strip_tags($date_ymd)}}" />@endif
                  @if (!$service->is_empty($guest_nm))<input type="hidden" name="guest_nm"    value="{{strip_tags($guest_nm)}}" />@endif
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


@endsection
