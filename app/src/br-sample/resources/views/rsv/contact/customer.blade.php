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
{{-- エラー戻りの場合はSTEPを表示させる --}}
{{--書き換え以下であっているか？ @if ($v->error->has()) --}}
@if (isset($errors))
{{-- {$v->helper->store->add('step', 'お問い合わせ内容の入力')}
{$v->helper->store->add('step', '入力内容の確認')}
{$v->helper->store->add('step', '送信完了')}
{$v->helper->store->add('step', '後日担当者より連絡')} --}}
{{-- 書き替えあっているか？（include先含め） --}}
@php $step = ['お問い合わせ内容の入力', '入力内容の確認', '送信完了', '後日担当者より連絡'] @endphp
@include ('rsv.common._pgc1_steps', ['pgc1_steps_current' => 1])
@endif
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner advance">

<div style="text-align:center;">
  <div style="margin:0 auto;width:700px;text-align:left;">
     {{-- メッセージ  --}}

@if ($service->is_empty($category))

    <div style="padding:1em 0">
      <h2 id="h2_title">お問い合わせ</h2>
    </div>

    <div id="txt_info">ベストリザーブ・宿ぷらざでは、各種お問い合わせを下記のお問い合わせフォームにて承っております。<br />
      商品やサービス、会員登録や宿泊予約手続きなどについて、ご不明な点がありましたら、お気軽にお問い合わせください。</div>
@endif

    <div id="contact_container" class="clearfix">
      <div id="contact_contents">

@if ($service->is_empty($category))
        <div class="section">
          <h3 class="title">まずは「よくあるご質問」をご確認ください。</h3>
          <p>以下の情報については<a href="/help/faq/">よくあるご質問</a>にてご確認いただけます。<br />
              よくあるご質問ページでお困りごとの解決方法が見当たらない場合は、下記お問い合わせフォームをご活用ください。</p>
          <ul>
            <li>会員登録について</li>
            <li>宿泊予約について</li>
            <li>お天気保険付きプランについて</li>
            <li>コンピュータ、インターネットなどご利用環境について</li>
            <li>クチコミ、メールマガジン配信停止などについて</li>
          </ul>
        </div>

        <div class="section">
          <h3 class="title">ご利用案内</h3>
          <ul class="guide">
            <li>お客様より提供いただいた個人情報につきましては、お客様への連絡・回答あるいは個人を特定できないよう加工した資料を作成・開示すること以外の目的に使用することはありません。<br />個人情報の管理につきましては、<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>をご覧ください。</li>
            <li>お寄せいただいた内容の事実確認や状況確認等を行いますので、回答に時間がかかる場合があります。<br />
また、いただいたお問い合わせに対して、回答を差し上げられない場合（ご希望に沿ったホテルの紹介など）がありますので、ご了承ください。</li>
            <li>弊社から回答させていただいた内容は、お客様個人に宛てたものです。<br />
回答メールの内容の一部または全文を転用、二次使用、または当該お客様以外に開示することは、固くお断りします。</li>
            <li>お問い合わせに対する回答は、bestrsv.comを含むドメインよりお送りしますので、<br />受信できるようメールソフト（メールサービス含む）の設定をお願いします。</li>
            <li>お問い合わせへの返信は土・日・祝日を除くAM 9:30～PM 17:30までとなっています。<br />
緊急のご予約・キャンセルなどの場合は、宿泊施設へ直接お問い合わせください。</li>
            <li>弊社休業日を除き、数日お待ちいただいても返信がない場合には、迷惑メールフォルダやゴミ箱もご確認の上、<br />大変恐れ入りますが、再度お問い合わせをお願いいたします。</li>
          </ul>
        </div>
@endif

        <div class="section">
            <h3 class="title">お問い合わせフォーム</h3>
            <p class="caution">原則として、お電話によるお問い合わせ、及び回答は行っておりませんので、ご了承ください。</p>

@if (time() < strtotime('2019-01-04 09:45'))
<p class="caution" style="color:#C00;">
＜年末年始のお客様サポート対応について＞<br /><br />
誠に勝手ながら年末年始休業のため、<br />
2018年12月28日(金)16時 ～ 2019年1月4日(金)10時 の期間中、<br />
お問い合わせ窓口のサポート業務を休業とさせていただきます。<br /><br />
休業期間内にいただいた問い合わせは、2019年1月4日10時以降に<br />
順次対応させていただきます。<br /><br />
※休業期間明けは、お問い合わせの回答に通常よりもお時間を<br />
いただく場合がございます。あらかじめご了承くださいませ。<br /><br />
ご不便をおかけしますが、何卒よろしくお願い申し上げます。</p>
@endif

            <p>予め当社<a href="/about/policy/privacy/" target="_blank">プライバシーポリシー</a>に同意をお願いします。</p>

{{-- {if ($v->error->has())} --}}
{{-- ないはずなのに空が出力される --}}
            {{-- <div class="ei">{foreach from=$v->error->gets() item=error} --}}
            {{-- エラーメッセージの表示 --}}
            {{-- ctl側に実装済の共通部品を埋め込む形でいいか？デザイン違うが、そこまで問題ないかと思われる --}}
            @include('ctl.common.message')
<script>
function category_change(){
  obj = document.form.category;

  index = obj.selectedIndex;
  if (index != 0){
    $('#rsv_data').hide();
  } else {
    $('#rsv_data').show();
  }
}
</script>
            <div id="stylized" class="myform">
              {!! Form::open(['route' => ['rsv.contact.customerConfirm'], 'method' => 'get']) !!}
                <h4>会員情報を照会される場合のご注意</h4>
                <p>「<a href="{$v->env.ssl_path}{$v->env.module}/reminder/ptm11/">会員コード・パスワードの照会</a>」フォームをご利用ください。照会できない場合は下記フォームへご登録の「氏名」「生年月日」「電話番号」を本文にご入力の上、お問い合わせください。会員様の特定ができましたら、ご登録のメールアドレスへ会員コード・パスワードをご案内させていただきます。</p>

                <label>{{$category_nm}}&nbsp; <br /><span class="required">【必須】</span></label>
                <select name="category" id="category" onChange="category_change()">
                @foreach ($categorys as $category_cd => $category_value)
                <option value="{{old('category_cd', $category_cd)}}"@if ($category_cd == $category) selected="selected"@endif >{{$category_value}}</option>
                @endforeach
                </select><br clear="all" />

                <label>ご氏名&nbsp; <br /><span class="required">【必須】</span></label>
                <input type="text" name="full_nm" id="name" value="{{old('full_nm', strip_tags($full_nm))}}" /><br clear="all" />

                <label>会員コード<br /></label>
                <input type="text" name="account_id" id="membercode" class="plus_a" value="{{old('account_id', strip_tags($account_id))}}" /><br clear="all" />
                <p class="attention">※ベストリザーブ・宿ぷらざ会員の方のみご記入ください。</p>

                <label>ご返信用メールアドレス&nbsp; <br /><span class="required">【必須】</span></label>
                <input type="text" name="email" id="email" class="plus_a" value="{{old('email', strip_tags($email))}}" /><br clear="all" />
                <p class="attention">※お間違えになると返信出来ませんのでご注意ください。</p>

                <div id="rsv_data">
                <label><br>
                ・予約コード　　　　　&nbsp;<br><br>
                または　　　　　　　　&nbsp;<br><br>
                ご予約された施設名、　&nbsp;<br>
                ご宿泊日、　　　　　　&nbsp;<br>
                宿泊代表者のかな氏名　&nbsp;<br>
                の3つが必須入力です。 &nbsp;</label>
                <table border="1" cellpadding="6" cellspacing="0" width="480" bgcolor="#E0ECF8">
                    <tr><td>
                <label>予約コード　　　　　&nbsp;</label>
                <input type="text" name="rsv_cd" id="rsv_cd" class="plus_a" value="{{old('rsv_cd', strip_tags($rsv_cd))}}" /><br clear="all" />
                    </td>
                    <td>
                <label>ご予約された施設名　&nbsp;</label>
                <input type="text" name="hotel_nm" id="hotel_nm" class="plus_a" value="{{old('hotel_nm', strip_tags($hotel_nm))}}" /><br clear="all" />

                <label>ご宿泊日　　　　　　&nbsp;</label>
                <input type="text" name="date_ymd" id="date_ymd" class="plus_a" value="{{old('date_ymd', strip_tags($date_ymd))}}" /><br clear="all" />

                <label>宿泊代表者のかな氏名&nbsp;</label>
                <input type="text" name="guest_nm" id="guest_nm" class="plus_a" value="{{old('guest_nm', strip_tags($guest_nm))}}" /><br clear="all" />
                    </td></tr>
                </table>
                <br>
                </div>

                <label>本文&nbsp; <br /><span class="required">【必須】</span></label>
                <textarea type="text" name="note" id="note" rows="8" cols="36" class="plus_a" />{{old('note', strip_tags($note))}}</textarea><br clear="all" />
                <p class="attention">※すでにご予約済みのお客様は「予約コード」を必ず明記の上、お問い合わせください。<br clear="all" />
※プラン内容や施設詳細・サービスに関するご質問は、直接施設へお問い合わせください。</p>
                <button type="submit" title="送信する">送信する</button>
                <div class="spacer"></div>
              {!! Form::close() !!}
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