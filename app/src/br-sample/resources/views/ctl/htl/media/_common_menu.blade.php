{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_common_menu.tpl --}}

<br />
<div align="right">
  {if $v->env.action != 'list'}
    <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/list/" method="post">
      <input type="submit" value="「画像一覧管理」{$v->helper->form->strip_tags($v->user->hotel.hotel_nm)}へ">
      <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
    </form>
  {/if}
  {if $v->env.action != 'edithotel' and $v->env.action != 'updatehotel' and $v->env.action != 'sorthotel'}
    <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/edithotel/" method="post">
      <input type="submit" value="「施設画像設定」{$v->helper->form->strip_tags($v->user->hotel.hotel_nm)}へ">
      <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
    </form>
  {/if}
  <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/list/" method="post">
    <input type="submit" value="「プランメンテナンス」{$v->helper->form->strip_tags($v->user->hotel.hotel_nm)}へ">
    <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
  </form>
</div>