<div align="right">
  <small>
    <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/list/" method="post">
      <input type="submit" value="「プランメンテナンス」{{$v->user->hotel->hotel_nm}}へ">
      <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
      <input type="hidden" name="partner_group_id" value="{$v->helper->form->strip_tags($v->assign->partner_group_id)}" />
      <input type="hidden" name="room_cd" value="{$v->helper->form->strip_tags($v->assign->room_cd)}" />
      <input type="hidden" name="room_disp" value="{$v->helper->form->strip_tags($v->assign->room_disp)}" />
      <input type="hidden" name="display_status" value="{$v->helper->form->strip_tags($v->assign->display_status)}" />
    </form>
  </small>
</div>