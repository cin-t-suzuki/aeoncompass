{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_upload_form.tpl --}}

{literal}
<script language="javascript"  type="text/javascript">
<!--
  $(document).ready(function () {
    if ( $('input[name=select]:checked').val() == 'normal') {
      $('#el').show();
    } else {
      $('#el').hide();
    }

    $('input[name=select]').change (function () {
      if ( $('input[name=select]:checked').val() == 'normal') {
        $('#el').show();
      } else {
        $('#el').hide();
      }
    });

    $('input[name=upload]').click (function () {
      if ( $('input[name=select]:checked').val() == 'normal') {
        // 処理なし
      } else {
        $(':checkbox').val('label_cd').attr('checked', '');
        $('#upload_form').append('<input type="hidden" name="label_cd[map]" value="1" />');
      }
      $('#upload_form').submit();
    });
    
    $('#inputFile').change( function() {
      var file = this.files[0];
      if(file != null) {
        var file_nm = file.name;
        var type = file_nm.split('.');
        if(type.length < 2 ){
          alert('画像ファイルは「.gif」・「.jpeg」・「.jpg」の拡張子を持つファイルのみアップロード可能です。');
          return;
        }
      
        var extension = type[type.length - 1];
        if (extension.toLowerCase() != 'gif' &&
            extension.toLowerCase() != 'jpg' &&
            extension.toLowerCase() != 'jpeg'){
              alert('画像ファイルは「.gif」・「.jpeg」・「.jpg」の拡張子を持つファイルのみアップロード可能です。');
              return;
            } else {
                var regExp = new RegExp(extension, "g" );
                file_nm = file_nm.replace(regExp,"");
                file_nm = file_nm.replace(/\.$/,"");
                file_nm = file_nm.substr(0, 30)
                $('#inputTitle').val(file_nm);
            }
      }
    });
  });
-->
</script>
{/literal}
<strong>画像の追加</strong>
<p style="color:#a81f42; font-size:12.5px; font-weight: bold; margin:1px 1px 10px 1px;">※ファイル名称に全角文字を含むファイルのアップロードが可能です。</p>
<form action="{$v->env.source_path}{$v->env.module}/htlsmedia/upload/" method="post" enctype="multipart/form-data" id="upload_form" >
  <input type="hidden" name="target_cd"        value="{$v->assign->form_params.target_cd}" />
  <input type="hidden" name="room_id"          value="{$v->assign->form_params.room_id}" />
  <input type="hidden" name="plan_id"          value="{$v->assign->form_params.plan_id}" />
  <input type="hidden" name="media_no"         value="{$media.media_no}" />
  <input type="hidden" name="media_type"       value="{$v->assign->form_params.media_type}" />
  <input type="hidden" name="target_order_no"  value="{$v->assign->form_params.target_order_no}" />
  <input type="hidden" name="setting_media_no" value="{$v->assign->form_params.setting_media_no}" />
  <input type="hidden" name="label_type"       value="{$v->assign->form_params.label_type}" />
  {assign var=tracking_id value=$v->config->system->cookie->tracking_id_name}
  <input type="hidden" name="sfck"             value="{$smarty.cookies.$tracking_id}.{$smarty.now}" />
  <script language="javascript"  type="text/javascript">
    <!--
      function window_open(){ldelim}
        window.open('{$v->env.source_path}{$v->env.module}/htlmedia/rule/target_cd/{$v->helper->form->strip_tags($v->assign->target_cd)}/', '_blank', 'width=600,height=750,resizable=yes,scrollbars=yes,menubar=no');
        {rdelim}
        document.write('<a href="#" onclick="window_open()">－ ご利用について －</a>');
    //-->
  </script>
  <noscript>
    <a href="{$v->env.source_path}{$v->env.module}/htlmedia/rule/target_cd/{$v->helper->form->strip_tags($v->assign->target_cd)}/" target="_blank">－ ご利用について －</a>
  </noscript>
  <table border="1" cellpadding="4" cellspacing="0" width="700">
    <tr>
      <td  bgcolor="#EEEEFF"  nowrap>ファイル指定</td>
      <td width="100%"><input id="inputFile" type="file" size="40" name="file" accept="image/jpeg,image/gif,image/pjpeg"></td>
    </tr>
    <tr>
      <td  bgcolor="#EEEEFF"  nowrap>画像タイトル</td>
      <td><input id="inputTitle" type="text" size="60" name="title" maxlength="30" value=""><br><small>タイトルはお客様画面に表示されます。全角３０文字以内で必ず入力ください。</small></td>
    </tr>
    <tr>
      <td  bgcolor="#EEEEFF"  nowrap>画像ラベル</td>
      <td>
        <table border="0" cellpadding="4" cellspacing="1" width="100%">
        {* 「画像一覧画面」と「地図画像以外の編集画面」でのみ表示 *}
        {if ($v->env.action === 'list') or ($v->assign->form_params.label_cd.map !== '1')}
          <tr>
            <td bgcolor="#FFFFFF"><input type="radio" value="normal" name="select"  checked id="normal" {if $v->assign->form_params.label_cd.map !== '1'}checked="checked"{/if} ><label for="normal">地図以外</label>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" id="el" style="margin-left:50px">
                <tr class="visible-normal">
                  <td bgcolor="#FFFFFF"><input type="checkbox" value="1" name="label_cd[outside]" id="outside" {if $v->assign->form_params.label_cd.outside === '1'} checked="checked" {/if}><label for="outside"><font color="#FF9999">■</font>外観</label></td>
                  <td bgcolor="#FFFFFF"><input type="checkbox" value="1" name="label_cd[inside]"  id="inside"  {if $v->assign->form_params.label_cd.inside  === '1'} checked="checked" {/if}><label for="inside"><font color="#99FF99">■</font>フォトギャラリー</label></td>
                  <td bgcolor="#FFFFFF"><input type="checkbox" value="1" name="label_cd[room]"    id="room"    {if $v->assign->form_params.label_cd.room    === '1'} checked="checked" {/if}><label for="room"><font color="#66CCFF">■</font>客室</label></td>
                  <td bgcolor="#FFFFFF"><input type="checkbox" value="1" name="label_cd[other]"   id="other"   {if $v->assign->form_params.label_cd.other   === '1'} checked="checked" {/if}><label for="other"><font color="#FF99FF">■</font>その他</label></td>
                </tr>
              </table>
            </td>
          </tr>
        {/if}
        {* 「画像一覧画面」と「地図画像の編集画面」でのみ表示 *}
        {if ($v->env.action === 'list') or (($v->assign->form_params.label_cd.map === '1') and ($v->assign->form_params.label_cd.outside !== '1' and $v->assign->form_params.label_cd.inside  !== '1' and $v->assign->form_params.label_cd.room !== '1' and $v->assign->form_params.label_cd.other !== '1'))}
          <tr>
            <td bgcolor="#FFFFFF" colspan="5"><input type="radio" value="map" name="select"  id="map" {if $v->assign->form_params.label_cd.map === '1'}checked="checked"{/if} ><label for="map"><font color="#FFCC66">■</font>地図</label></td>
          </tr>
        {/if}
        </table>
        <small>ラベルを選択すると、たくさんの画像をアップロードした時の絞込表示に役立ちます。</small>
      </td>
    </tr>
    <tr>
      <td bgcolor="#EEEEFF" >　</td>
      <td><input type="button" name="upload" value="アップロード"></td>
    </tr>
  </table>
</form>