<!-- {* header start *} -->
@include('ctl.common._htl_header', ['title' => '部屋メンテナンス'])
<!-- {* header end *} -->

<!--  javascript  -->
<script type="text/javascript">
    $(document).ready(function () {

      $('input[name^="copy_exe"]').click(function () {
        for ( i = 2; i <= 9; i++) {
          $('input[name^="rooms_' + i + '"]').val($('#copy_src').val())
        }
      });
      
      
      $('input[name^="Room_Network[network]"]').change(function() {
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
      
      if ($('input[name^="Room_Network[network]"]:checked').val() != 9 && $('input[name^="Room_Network[network]"]:checked').val() != 0) {
        $('.jqs-default-hide').show();
        $('input[name^="Room_Network[rental]"]').removeAttr('disabled');
        $('input[name^="Room_Network[connector]"]').removeAttr('disabled');
        $('input[name^="Room_Network[network_note]"]').removeAttr('disabled');
      }
      
    });
</script>
<!--  /javascript  -->

<!-- Hotel Information -->
<br>

<!-- {* メッセージ *} -->
@include('ctl.common.message')

<br>

<hr size="1">

{{ Form::open(['route' => ['ctl.htlsroom2.update'], 'method' => 'post']) }}
<div>部屋／編集</div>
<br>

<table cellspacing="0" cellpadding="4" border="1">
  <tbody>
      
<!-- {* 共通フォーム room *} -->  
	@include('ctl.htlsroom2._input_room')
<!-- {* 共通フォーム room *} -->

<!-- {* 共通フォーム room_spec *} -->
	@include('ctl.htlsroom2._input_room_spec')
<!-- {* 共通フォーム room_spec *} -->

<!-- {* 共通フォーム room_network *} -->
	@include('ctl.htlsroom2._input_room_network')
<!-- {* 共通フォーム room_network *} -->

<!-- {* 共通確認 room_media *} -->
	@include('ctl.htlsroom2._info_room_media')
<!-- {* 共通確認 room_media *} -->

    <tr>
      <td nowrap  bgcolor="#EEEEFF" >表示可否</td>
      <td>
      @if($room->display_status == 1)
      表示する
      @else
      表示しない
      @endif
      </td>
      <td><br></td>
    </tr>
    @if(isset($room->room_cd))
    <tr>
      <td nowrap  bgcolor="#EEEEFF" >部屋コード</td>
      <td>
      {{$room->room_cd}}<br>
      </td>
      <td><small>半角英数10文字（大文字に自動変換）</small></td>
    </tr>
    @endif
      </td>
    </tr>
    <tr>
      <td  bgcolor="#EEEEFF" >　</td>
      <td colspan="2"><input type="submit" value="更新する"></td>
    </tr>
  </tbody>
</table>
  <input type="hidden" name="target_cd" value="{{ $hotel_cd }}" />
  <input type="hidden" name="room_id" value="{{ $room_id }}" />
  <input type="hidden" name="display_status" value="{$v->helper->form->strip_tags($v->assign->display_status)}" />
  <input type="hidden" name="finger" value="{$v->assign->finger}" />
{{ Form::close() }}
  
<!-- {* 共通フォーム info *} -->
	@include('ctl.htlsroom2._input_info')
<!-- {* 共通フォーム info *} -->


<!-- {* 部屋プランメンテナンスindexへのform *} -->
	@include('ctl.htlsroom2._form_stock_index')
<!-- {* 部屋プランメンテナンスindexへのform *} -->
<br>
<!-- {* footer start *} -->
	@include('ctl.common._htl_footer')
<!-- {* footer end *} -->