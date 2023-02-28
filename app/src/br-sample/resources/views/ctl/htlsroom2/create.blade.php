<!-- {* header start *} -->
@php
  $hotel = (object) [
    'hotel_nm' => 'ホテル',
    'hotel_old_nm' => '旧ホテル',
    'hotel_cd' => '0000000001',
    'premium_status' => false,
    'visual_package_status' => false,
    'ydp2_status' => false,
    'accept_status' => 0
  ];

  $hotel_control = (object) [
    'stock_type' => 1
  ];

  $hotel_person = (object) [
    'person_nm' => 'admin',
    'person_post' => '平',
    'person_tel' => '01-0001-0001',
    'person_fax' => '01-0001-0001'
  ];

  $user = (object) [
    'hotel' => $hotel,
    'hotel_control' => $hotel_control,
    'hotel_person' => $hotel_person
  ];

  $env = (object) [
    'source_path' => 'ctl',
    'module' => 'ctl',
    'controller' => 'htlsroom2',
    'action' => 'new'
  ];

  $from = (object)['opc' => 'A@sample.co.jp'];
  $mail = (object)['from' => $from];
  $environment = (object)['mail' => $mail];  
  $config = (object)['environment' => $environment];
  $v = (object) [
    'user' => $user,
    'env' => $env,
    'config' => $config
  ];

  class service{
    public function is_empty(){
      return true;
    }
  }
  $service = new service;
  $staffName = 'admin';
@endphp
@include('ctl.common._htl_header', ['title' => '部屋登録',
                                    'print_flg' => 'false',
                                    'screen_type' => 'test',
                                    'no_print' => 'false',
                                    'is_staff' => 'true',
                                    'is_nta' => 'false',
                                    'header_number' => '0',
                                    'hotel_nm' => 'ホテル',
                                    'hotel_old_nm' => null,
                                    'hotel_cd' => '1',
                                    'entry_status' => 0,
                                    'ydp2_status' => null,
                                    'acceptance_status_flg' => true,
                                    'service_info_flg' => true,
                                    'no_print_title' => false,
                                    'menu_title' => null])
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
@php
  $roomtype_cd = null;
  $is_login = true;
  $is_staff = false;
  $no_print = false;
@endphp
<!-- {* footer start *} -->
@include('ctl.common._htl_footer')
<!-- {* footer end *} -->
