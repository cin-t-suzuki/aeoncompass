<script type="text/javascript">
  <!--
    $(document).ready(function () {
      var cookie_path = '{/literal}{$v->env.source_path}{$v->env.module}/{literal}';
      var cookie_expert_menu = $.cookies.get('EXPERT');

      if (cookie_expert_menu == 'on') {
        $('#expert_menu_on').hide();
        $('#expert_menu_off').show();
        $('.jqs-expert_menu').show();
      } else {
        $('#expert_menu_off').hide();
        $('#expert_menu_on').show();
        $('.jqs-expert_menu').hide();
      }

      $('#expert_menu_on').click(function () {
        $.cookies.set('EXPERT', 'on', {path: cookie_path});
        $('#expert_menu_off').show();
        $('#expert_menu_on').hide();
        $('.jqs-expert_menu').show();
      })

      $('#expert_menu_off').click(function () {
        $.cookies.del('EXPERT', {path: cookie_path});
        $('#expert_menu_on').show();
        $('#expert_menu_off').hide();
        $('.jqs-expert_menu').hide();
      })
    });
  // -->
</script>
<div style="float:left; margin-left:2px;">
  @if($v->env->controller == 'htlsroomoffer')
    【室数・料金・期間の調整】
  @else
    <form action="{$v->env.source_path}{$v->env.module}/htlsroomoffer/" method="post" style="display:inline;">
      <div>
        <input type="hidden" name="target_cd"        value="{$v->assign->target_cd}" />
        <input type="hidden" name="partner_group_id" value="{$v->assign->partner_group_id}" />
        <input type="submit" value="室数・料金・期間の調整" />
      </div>
    </form>
  @endif
</div>
<div style="float:left; margin-left:2px;">
  @if($v->env->controller == 'htlsroomplan2')
    【プランメンテナンス】
  @else
    <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/list/" method="post" style="display:inline;">
      <div>
        <input type="hidden" name="target_cd"        value="{$v->assign->target_cd}" />
        <input type="hidden" name="partner_group_id" value="{$v->assign->partner_group_id}" />
        <input type="submit" value="プランメンテナンス" />
      </div>
    </form>
  @endif
</div>
<div style="float:left; margin-left:2px;">
  <form action="{$v->env.source_path}{$v->env.module}/htlreserve/" method="post" style="display:inline;">
    <div>
      <input type="hidden" name="target_cd"        value="{$v->assign->target_cd}" />
      <input type="hidden" name="partner_group_id" value="{$v->assign->partner_group_id}" />
      <input type="submit" value="予約情報の確認" />
    </div>
  </form>
</div>
<!-- {*  JRセットプラン設定の編集可否  *} -->
@if(empty($v->user->hotel->jrset_status))
  {assign var=is_edit_jrset value=false}
@else
  @if($v->user->hotel.jrset_status|intval == 0
      or $v->user->hotel.jrset_status|intval == 1 
      or $v->user->hotel.jrset_status|intval == 4)
    {assign var=is_edit_jrset value=true}
  @else
    {assign var=is_edit_jrset value=false}
  @endif
@endif
@if($is_edit_jrset)
<div style="float:left; margin-left:2px;">
  <form action="{$v->env.source_path}{$v->env.module}/htlsroomplandp/" method="post" style="display:inline;">
    <div>
      <input type="hidden" name="target_cd"        value="{$v->assign->target_cd}" />
      <input type="submit" value="JRコレクション審査状況" />
    </div>
  </form>
</div>
@endif
<!-- {*  エキスパートメニュー  *} -->
<div class="jqs-expert_menu" style="display:none;">
  <div style="float:left; margin-left:2px;">
    @if($v->env->controller == 'pmscode')
    【PMSコード】
    @else
      <form action="{$v->env.source_path}{$v->env.module}/pmscode/" method="post" style="display:inline;">
        <div>
          <input type="hidden" name="target_cd"        value="{$v->assign->target_cd}" />
          <input type="hidden" name="partner_group_id" value="{$v->assign->partner_group_id}" />
          <input type="submit" value="PMSコード" />
        </div>
      </form>
    @endif
  </div>
</div>
{*  プラン・部屋登録方式移行ツール  *}
@if(!$v->assign->is_migration && $v->user->hotel_system_version.version == 1)
  <div class="jqs-expert_menu" style="display:none;">
    <div style="float:left; margin-left:2px;">
      <form method="post" action="{$v->env.source_path}{$v->env.module}/htlmigration/" style="display:inline;">
        <div>
          <input type="submit" value="プラン・部屋登録方式移行ツール" />
          <input type="hidden" name="ctl_nm" value="{$v->env.controller}" />
          <input type="hidden" name="act_nm" value="{$v->env.action}" />
          <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
        </div>
      </form>
    </div>
  </div>
@endif
<!-- {*  /エキスパートメニュー  *} -->
<div class="align-r" style="margin-left:2px;">
  <input id="expert_menu_on"  type="button" value="エキスパートメニュー表　示" style="display:none;" />
  <input id="expert_menu_off" type="button" value="エキスパートメニュー非表示" style="display:none;" />
</div>
<div style="clear:both; margin-top:0;"> </div>
<div class="clear"></div>
<hr />