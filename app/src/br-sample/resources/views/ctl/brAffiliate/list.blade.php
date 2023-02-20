@extends('ctl.common.base')
@section('title', 'アフィリエイト管理TOP')
@inject('service', 'App\Http\Controllers\ctl\BrAffiliateController')

@section('page_blade')

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/affiliaternew/">
      <td>
        <input type="submit" value="アフィリエイター新規登録">
      </td>
    </form>
  </tr>	
</table>
<table border="1" cellspacing="0" cellpadding="4">
  <tr>
    <td nowrap align="center" bgcolor="#EEFFEE" colspan="2">アフィリエイター</td>
    <td nowrap align="center" bgcolor="#EEFFEE" >プログラム</td>
    <td nowrap align="center" bgcolor="#EEFFEE" >予約システム</td>
    <td nowrap align="center" bgcolor="#EEFFEE"  >COOKIE</td>
    <td nowrap align="center" bgcolor="#EEFFEE" >サービス期間</td>
    <td nowrap  bgcolor="#EEFFEE" >U : アフィリエイトＵＲＬ<br />R : リダイレクト先</td>
  </tr>
{{-- loop部分 --}}
@php 
  $temp_room_cd = "";
  $row_cnt = 1;
@endphp


{{--一覧表示のループ --}}
@foreach ($affiliate_list['values'] as $affiliate)

 {{-- $tmp_affiliater_cdは初期値無し ??null追記でいいか？ --}}
  @if ((($tmp_affiliater_cd ?? null) != $affiliate->affiliater_cd) && (!$loop->first))

    {{-- 
          @foreach (from=$v->helper->store->gets('affiliater') item=affiliater name=affiliater)
        {if $smarty.foreach.affiliater.first == true}
          <tr>
            {$affiliater_tpl}
        {/if}
          {$affiliater}
        </tr>
      {/foreach}
    --}}
    @foreach ([[],[]] as $affiliater)
    {{-- ↑アフィリエイトログイン？実装後に修正 --}}
      @if ($loop->first)
        <tr>
          @if($in_affiliater_tpl) @include ('ctl.brAffiliate._affiliate_cd') @endif
      @endif
          @if($in_affiliater) @include ('ctl.brAffiliate._affiliate_list') @endif
      </tr>
    @endforeach

   {{-- storeの初期化 --}}
   {{--アフィリエイトログイン？実装後に再表示 {$v->helper->store->clear()} --}}

   @php $row_cnt = 1; @endphp
  @endif
  
  {{-- {*アフィリエイターのテンプレートを退避*}
  {include file=$v->env.module_root|cat:'/views/braffiliate/_affiliate_cd.tpl' assign=affiliater_tpl}
  {*アフィリエイター以降のテンプレートを退避*}
  {include file=$v->env.module_root|cat:'/views/braffiliate/_affiliate_list.tpl' assign=affiliater} --}}
  @php
  //変数名がかぶるので元ソースから変更,@includeは変数格納できなさそうなのでtrueで読み込み時に条件分岐
  $in_affiliater_tpl = true;
  $in_affiliater = true;
  @endphp

  {{--storeにhtml配列？の追加--}}
  {{-- アフィリエイトログイン？実装後に再表示 --}}
  {{-- {$v->helper->store->add('affiliater',$affiliater)} --}}
  
  @php
    $row_cnt = $row_cnt+1; 
    $tmp_affiliater_cd = $affiliate->affiliater_cd; 
  @endphp
@endforeach

{{-- 最後の行の表示 --}}
{{--
  {foreach (from=$v->helper->store->gets('affiliater') item=affiliater name=affiliater}
    {if $smarty.foreach.affiliater.first == true}
      <tr>{$affiliater_tpl}
    {/if}
    {$affiliater}</tr>
  {/foreach}
 --}}
@foreach ([[],[]] as $affiliater)
{{-- ↑アフィリエイトログイン？実装後に修正 --}}

  @if ($loop->first == true)
    <tr>
      @if($in_affiliater_tpl) @include ('ctl.brAffiliate._affiliate_cd') @endif
  @endif
    @if($in_affiliater) @include ('ctl.brAffiliate._affiliate_list') @endif
  </tr>

@endforeach
{{-- loop部分 --}}
</table>
<ul style="line-height: 130%">
  <li>/ac/アフィリエイトコード-枝番/ を記入することで COOKIE を食わせた後に任意のURLに遷移します。<br>
      下記の場合はクッキーを食わせた後、施設情報ページへ遷移します。<br>
    例：<a href="http://www.bestrsv.com/ac/0A00000001/hotel/2000050101/" target="blank">http://www.bestrsv.com/ac/0A00000001/hotel/2000050101/</a>

</ul>

@endsection