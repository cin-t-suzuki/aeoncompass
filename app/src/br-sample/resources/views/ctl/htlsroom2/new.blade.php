<!-- hearder -->
@include('ctl.common._htl_header', ['title' => '部屋登録')
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
    @include('ctl.htlsroom2._input_room')
    <!-- common_input_form_room_spec -->
    @include('ctl.htlsroom2._input_room_spec')
    <!-- common_input_form_room_network -->
    @include('ctl.htlsroom2._input_room_network')
    @php
      $roomtype_cd = null;
      $is_login = true;
      $is_staff = false;
      $no_print = false;
    @endphp
    
    @if(is_null($roomtype_cd))
      @include('ctl.htlsroom2._input_room_count')
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