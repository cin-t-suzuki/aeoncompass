{{-- MEMO: 移植元 svn_trunk\public\app\ctl\view2\brhotelarea\index.tpl --}}

{strip}
  {*--------------------------------------------------------------------------*}
  {* JavaScript指定                                                           *}
  {*--------------------------------------------------------------------------*}
  {capture name=js_action}
    {literal}
      <script type="text/javascript">
        <!--
          $(document).ready(function () {
            $('input.jqs-area-delete').click(function(){
               return confirm($('.jqs-area-nm').eq($('input.jqs-area-delete').index(this)).val() + '\n\nこの地域情報を削除します。\nよろしいですか？');
            });
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
    title = '施設と地域の関連付け【一覧】'
    screen_type  = 'br'
    js_action    = $smarty.capture.js_action
  }

  {* 余白 *}
  <hr class="bound-line-l" />
  
  {* メッセージ *}
  {include file=$v->env.module_root|cat:'/view2/_common/_message.tpl'}

  {* 余白 *}
  <hr class="bound-line-l" />
  
  {include file='./_hotel_info.tpl' hotel_info=$v->assign->hotel_info}
  
  {* 余白 *}
  <hr class="bound-line-l" />
  
  <div>
    <input type="hidden" name="target_cd" value="{$v->assign->request_params.target_cd}" />
    <table class="br-list">
      <tr>
        <th class="fc">大エリア</th>
        <th>都道府県</th>
        <th>中エリア</th>
        <th>小エリア</th>
        <th colspan="2" class="lc">
          <form method="post" action="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/new/">
            <div>
              <input type="submit" value="新規追加" />
              <input type="hidden" name="target_cd" value="{$v->assign->request_params.target_cd}" />
            </div>
          </form>
        </th>
      </tr>
      {foreach from=$v->assign->hotel_areas item=hotel_area}
        <tr class="{if $v->assign->request_params.target_no === $hotel_area.entry_no}active{else}{cycle values='odd,even'}{/if}">
          <td>{$hotel_area.area_nm_l}</td>
          <td>{$hotel_area.area_nm_p}</td>
          <td>{$hotel_area.area_nm_m}</td>
          <td>{$hotel_area.area_nm_s}</td>
          <td>
            <form method="post" action="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/edit/">
              <div>
                <input type="hidden" name="target_cd" value="{$hotel_area.hotel_cd}" />
                <input type="hidden" name="entry_no"  value="{$hotel_area.entry_no}" />
                <input type="submit" value="編集" />
              </div>
            </form>
          </td>
          <td>
            <form method="post" action="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/delete/">
              <div>
                <input type="hidden" name="area_pattern" class="jqs-area-nm" value="{$hotel_area.area_nm_l|cat:' '|cat:$hotel_area.area_nm_p|cat:' '|cat:$hotel_area.area_nm_m|cat:' '|cat:$hotel_area.area_nm_s}" />
                <input type="hidden" name="target_cd" value="{$hotel_area.hotel_cd}" />
                <input type="hidden" name="entry_no"  value="{$hotel_area.entry_no}" />
                <input type="submit" value="削除" class="jqs-area-delete" />
              </div>
            </form>
          </td>
        </tr>
      {foreachelse}
        <tr>
          <td colspan="6"><p class="msg-text-error">現在登録されている地域はありません</p></td>
        </tr>
      {/foreach}
    </table>
    <div class="br-list-tail">&nbsp;</div>
  </div>
  
  {* 余白 *}
  <hr class="bound-line-l" />
  
  <form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/show/">
    <div class="br-back-main-menu-form">
      <input type="hidden" name="target_cd" value="{$hotel_area.hotel_cd}" />
      <input type="submit" value="詳細変更へ" />
    </div>
  </form>

  {* 余白 *}
  <hr class="bound-line-l" />

  {*===============================================================================================*}
  {* フッター                                                                                      *}
  {*===============================================================================================*}
  {include file=$v->env.module_root|cat:'/view2/_common/_footer2.tpl'}
{/strip}