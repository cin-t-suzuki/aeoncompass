<!-- hearder -->
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
<!-- message -->
@include('ctl.common.message')

<!--  javascript  -->
  <script type="text/javascript">
      $(document).ready(function () {

        $('input[name="copy_exe"]').click(function () {
          for ( i = 2; i <= 9; i++) {
            $('input[name="rooms_' + i + '"]').val($('#copy_src').val())
          }
        });
        
        
        $('input[name="Room_Network[network]"]').change(function() {
          if ($(this).val() != 9 && $(this).val() != 0) {
            $('.jqs-default-hide').show();
            $('input[name^="Room_Network[rental]"]').removeAttr('disabled');
            $('input[name^="Room_Network[connector]"]').removeAttr('disabled');
            $('input[name^="Room_Network[network_note]"]').removeAttr('disabled');
          } else {
            $('.jqs-default-hide').hide();
            $('input[name^="Room_Network[rental]"]').attr('disabled', 'disabled');
            $('input[name^="Room_Network[connector]"]').attr('disabled', 'disabled');
            $('input[name^="Room_Network[network_note]"]').attr('disabled', 'disabled');
          }
        });
        
        if ($('input[name="Room_Network[network]"]:checked').val() != 9 && $('input[name="Room_Network[network]"]:checked').val() != 0) {
          $('.jqs-default-hide').show();
          $('input[name^="Room_Network[rental]"]').removeAttr('disabled');
          $('input[name^="Room_Network[connector]"]').removeAttr('disabled');
          $('input[name^="Room_Network[network_note]"]').removeAttr('disabled');
        }
        
      });
  </script>
<!--  /javascript  -->

<!-- input_form -->
{{ Form::open(['route' => ['ctl.htlsroom2.create'], 'method' => 'post']) }}
<form action="{$v->env.source_path}{$v->env.module}/htlsroom2/create/" method="post">
  <input type="hidden" name="target_cd"      value="{{ $hotel_cd }}" />
  <input type="hidden" name="display_status" value="{$v->helper->form->strip_tags($v->assign->display_status)}" />
  <input type="hidden" name="finger"         value="{$v->assign->finger}" />
  <!-- {* 連携在庫の部屋タイプコード：これの有無で連携在庫の登録かどうかを判断している *} -->
  <!-- <input type="hidden" name="roomtype_cd"    value="{$v->assign->form_params.roomtype_cd}" /> -->
  <!-- {* 初期遷移か、エラーで戻されたかを判定する為のフラグ *} -->
  <input type="hidden" name="is_create"      value="true"                                  />
  <table cellspacing="0" cellpadding="4" border="1">
    <!-- common_input_form room -->
    @include('ctl.htlsroom2._input_room', ['room_type' => null,
                                           'floor_unit' => null])
    <!-- common_input_form_room_spec -->
    @include('ctl.htlsroom2._input_room_spec')
    <!-- common_input_form_room_network -->
    @include('ctl.htlsroom2._input_room_network', ['network' => 9,
                                                   'rental' => null,
                                                   'connector' => null])
    @php
      $roomtype_cd = null;
      $is_login = true;
      $is_staff = false;
      $no_print = false;
    @endphp
    
    @if(is_null($roomtype_cd))
      @include('ctl.htlsroom2._input_room_count', ['rooms_1' => '',
                                                   'rooms_2' => '',
                                                   'rooms_3' => '',
                                                   'rooms_4' => '',
                                                   'rooms_5' => '',
                                                   'rooms_6' => '',
                                                   'rooms_7' => '',
                                                   'rooms_8' => '',
                                                   'rooms_9' => ''])
    @endif
    <tr>
      <td  bgcolor="#EEEEFF" >　</td>
      <td colspan="2"><input type="submit" value="登録する"></td>
    </tr>
  </table>
  <!-- common_info -->
  @include('ctl.htlsroom2._input_info')
{{ Form::close() }}
<!-- footer -->
@include('ctl.common._htl_footer')