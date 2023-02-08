@section('title', 'セキュリティログ一覧')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrSecurityController')


<!-- 
{* header start *}
{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title = 'セキュリティログ一覧'}
{* header end *} -->
<!-- @include('ctl.common.message') -->
{{-- エラーメッセージの表示 --}}
@if (!empty($errors) && is_array($errors) && count($errors) > 0)
<div style="border-style:solid;border-color:#f00;border-width:1px;padding:6px;background-color:#fee;">
    @foreach ($errors as $error)
        <div>{!! nl2br($error) !!}</div>
    @endforeach
</div>
@endif
<br>
<!-- {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'} -->

@include('ctl.brsecurity._form')
<!-- {* 検索フォーム *}
{include file=$v->env.module_root|cat:'/views/brsecurity/_form.tpl'} -->

<hr size="1">
<br>

<!-- {if $v->assign->log_securities.values|@count != 0} -->

@if(isset($log_securities))

 @include('ctl.brsecurity._list')
<!-- 
  {* 一覧 *}
  {include file=$v->env.module_root|cat:'/views/brsecurity/_list.tpl'} -->
<!-- {/if} -->
@endif

<!-- {* footer start *}
{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *} -->
@include('ctl.common.footer')