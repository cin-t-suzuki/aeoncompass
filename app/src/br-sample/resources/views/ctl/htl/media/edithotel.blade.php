{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/edithotel.tpl --}}

<!-- Header -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='施設画像設定'}
<!-- /Header -->
<!-- CSS -->
{include file='./_css.tpl'}
<!-- /CSS -->
<div class="clear"><hr></div>
<hr width="100%" size="1">
<!-- Main -->
<div id="page_top_symbol">
  <p>
   {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
  </p>
  <div>
    {include file=$v->env.module_root|cat:'/view2/htlsmedia/_edithotel_outside.tpl'}
  </div>
  <div>
    {include file=$v->env.module_root|cat:'/view2/htlsmedia/_edithotel_map.tpl'}
  </div>
  <div>
    {include file=$v->env.module_root|cat:'/view2/htlsmedia/_edithotel_gallery.tpl'}
  </div>
</div>
{include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'}
<!-- /Main -->
<div class="clear"><hr></div>
<!-- Footer -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}
<!-- /Footer -->