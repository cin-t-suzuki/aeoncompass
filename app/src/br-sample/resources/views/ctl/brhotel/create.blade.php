{{-- MEMO: 移植元 public\app\ctl\views\brhotel\create.tpl --}}

@extends('ctl.common.base')
@section('title', '施設登録情報　STEP2/6')
{{-- header start --}}
	{{-- {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設登録情報　STEP2/6"} --}}
{{-- header end --}}

@section('page_blade')
{{-- メッセージ --}}
{{-- {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'} --}}
@include('ctl.common.message')

<form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/management/">

  {{-- {include file=$v->env.module_root|cat:'/views/brhotel/_info_hotel_form.tpl'} --}}
    @include('ctl.brhotel._info_hotel_form', [
        // "hotel" => $views->hotel
        // ,"a_mast_pref" => $views->a_mast_pref
        // ,"a_mast_city" => $views->a_mast_city
        // ,"a_mast_ward" => $views->a_mast_ward??null
    ])

<input type="submit" value="施設管理情報登録へ">

</form>

<hr size="1">
<form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/">
<small>
  <input type="submit" value="施設情報メインへ">
</small>
</form>
<br>

{{-- footer start --}}
	{{-- {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'} --}}
{{-- footer end --}}

@endsection