<!-- {* header start *} -->
@include('ctl.common._htl_header', ['title' => '部屋登録'])
<!-- {* header end *} -->

<!-- Hotel Information -->
<br>

<form action="{$v->env.source_path}{$v->env.module}/htlsplan/new/" method="post">
  <!-- {* メッセージ *} -->
  @include('ctl.common.message')

  <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
  <input type="hidden" name="room_cd" value="{$v->helper->form->strip_tags($v->assign->room_cd)}" />
  <input type="hidden" name="display_status" value="{$v->helper->form->strip_tags($v->assign->display_status)}" />
</form>
   
<table cellspacing="0" cellpadding="4" border="1">
  <tbody>
   
<!-- {* 共通確認 room *} -->
  @include('ctl.htlsroom2._info_room')
<!-- {* 共通確認 room *} -->

<!-- {* 共通確認 room_spec *} -->
	@include('ctl.htlsroom2._info_room_spec')
<!-- {* 共通確認 room_spec *} -->

<!-- {* 共通確認 room_network *} -->
  @include('ctl.htlsroom2._info_room_network')
@if(! isset($room->roomtype_cd))
<!-- {* 共通確認 room_network *} -->
  @include('ctl.htlsroom2._info_room_count')
@endif

@if(isset($room->room_cd))
        <tr>
          <td nowrap  bgcolor="#EEEEFF" >部屋コード</td>
        <td>{$v->helper->form->strip_tags($v->assign->room_cd)}</td>
        <td><small>半角英数10文字（大文字に自動変換）</small></td>
      </tr>
@endif
  </tbody>
</table>

@if(isset($roomtype_cd))
  <div style="text-align:right;">
    <form action="{$v->env.source_path}{$v->env.module}/htlssettingakf/list/" method="post">
      <input type="hidden" name="target_cd" value="{$v->assign->target_cd}" />
      <input type="submit" value="連動在庫設定へ" />
    </form>
  </div>
@endif

<!-- {* 部屋プランメンテナンスindexへのform *} -->
  @include('ctl.htlsroom2._form_stock_index')
<!-- {* 部屋プランメンテナンスindexへのform *} -->

<br>
<!-- {* footer start *} -->
@include('ctl.common._htl_footer')
<!-- {* footer end *} -->
