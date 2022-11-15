{{-- MEMO: 移植元 svn_trunk\public\app\ctl\view2\brhotelarea\_input.tpl --}}

{strip}
  {*================================================================================================*}
  {* 引数                                                                                           *}
  {*                                                                                                *}
  {*   $title                                                                                       *}
  {*     ページのタイトルを指定します。                                                             *}
  {*                                                                                                *}
  {*   $action_type                                                                                 *}
  {*     実行されるアクションを指定します。（create：新規作成, update：更新）                       *}
  {*================================================================================================*}
  
  {*--------------------------------------------------------------------------*}
  {* JavaScript指定                                                           *}
  {*--------------------------------------------------------------------------*}
  {capture name=js_action}
    {literal}
      <script type="text/javascript">
        <!--
          $(document).ready(function () {
            $('#jqs-hotel-area').loadHotelArea({uri:'{/literal}{$v->env.source_path}{$v->env.module}/brhotelarea/json/{literal}', area_large:{/literal}{$v->assign->request_params.area_large}{literal}, area_pref:{/literal}{$v->assign->request_params.area_pref}{literal}, area_middle:{/literal}{$v->assign->request_params.area_middle}{literal}, area_small:{/literal}{$v->assign->request_params.area_small}{literal}});
          });
        -->
      </script>
    {/literal}
  {/capture}



  {*--------------------------------------------------------------------------*}
  {* ヘッダーのテンプレート読み込み                                           *}
  {*--------------------------------------------------------------------------*}
  {include
    file  = $v->env.module_root|cat:'/view2/_common/_header2.tpl'
    title = $title
    screen_type  = 'br'
    js_action    = $smarty.capture.js_action
  }

  {* 余白 *}
  <hr class="bound-line-l" />
  
  {include file='./_hotel_info.tpl' hotel_info=$v->assign->hotel_info}
  
  {* 余白 *}
  <hr class="bound-line-l" />
  
  {* メッセージ *}
  {include file=$v->env.module_root|cat:'/view2/_common/_message.tpl'}
  
  {* 余白 *}
  <hr class="bound-line" />

  <form method="post" action="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$action_type}/">
    <div>
      <input type="hidden" name="target_cd" value="{$v->assign->request_params.target_cd}" />
      <input type="hidden" name="is_submit" value="true" />
      
      {if $action_type === 'update'}
        <input type="hidden" name="entry_no" value="{$v->assign->request_params.entry_no}" />
      {/if}
      
      <table class="br-list" id="jqs-hotel-area">
        <tr>
          <th class="fc">大エリア</th>
          <th>都道府県</th>
          <th>中エリア</th>
          <th>小エリア</th>
          <th class="lc">&nbsp;</th>
        </tr>
        <tr>
          <td><select name="area_large"  id="jqs-area-l-list"><option value="">未選択</option></select></td>
          <td><select name="area_pref"   id="jqs-area-p-list"><option value="">未選択</option></select></td>
          <td><select name="area_middle" id="jqs-area-m-list"><option value="">未選択</option></select></td>
          <td><select name="area_small"  id="jqs-area-s-list"><option value="">未選択</option></select></td>
          <td><input type="submit" value="{if $action_type === 'create'}追加{elseif $action_type === 'update'}変更{/if}" /></td>
        </tr>
      </table>
      <div class="br-list-tail">&nbsp;</div>
    </div>
  </form>

  {* 余白 *}
  <hr class="bound-line-l" />
  
  <form method="post" action="{$v->env.source_path}{$v->env.module}/brhotelarea/">
    <div class="br-back-main-menu-form">
      <input type="hidden" name="target_cd" value="{$v->assign->request_params.target_cd}" />
      <input type="submit" value="施設と地域の関連付け【一覧】へ" />
    </div>
  </form>
  
  {* 余白 *}
  <hr class="bound-line-l" />

  {*===============================================================================================*}
  {* フッター                                                                                      *}
  {*===============================================================================================*}
  {include file=$v->env.module_root|cat:'/view2/_common/_footer2.tpl'}
{/strip}
