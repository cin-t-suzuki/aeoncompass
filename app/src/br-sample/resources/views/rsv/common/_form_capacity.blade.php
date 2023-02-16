{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_capacity.tpl --}}

{strip}
<div name="panel-{$form_capacity_nm}" style="display:none;position:absolute;background:#fff;width:500px;border:1px solid #ccc;">
  <div style="margin:12px;{if ($form_capacity_nm == 'guests-query')}background: none repeat scroll 0% 0% #F9F6E;{/if}">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <th>
          <div style="text-align:center;{if ($form_capacity_nm == 'guests-query')}background: none repeat scroll 0 0 #575347;color: #FFFFFF;margin-right: 0.5em;padding: 0.25em 0.5em;{/if}">
            小学生
          </div>
        </th>
        <th colspan="4">
          <div style="text-align:center;{if ($form_capacity_nm == 'guests-query')}background: none repeat scroll 0 0 #575347;color: #FFFFFF;margin-right: 0.5em;padding: 0.25em 0.5em;{/if}">
            幼児
          </div>
        </th>
        </tr>
        <tr>
        <td  class="text-center" style="width:100px;"><div style="height:32px;">大人並の食事・<br />布団あり</div>
          {if is_empty($v->assign->search_condition.form.childs.child1_capacities)}
             受け入れなし
          {else}
            <select size="1" name="child1" class="text-right"  {$s_disabled} >
              {foreach from=$v->assign->search_condition.form.childs.child1_capacities item=child}
                <option value="{if $child.capacity > 0}{$child.capacity}{/if}"{if $child.current_status} selected="selected"{/if}>{$child.capacity}名</option>
              {/foreach}
            </select>
          {/if}
        </td>
        <td  class="text-center" style="width:100px;"><div style="height:32px;">子供食事・<br />布団あり</div>
          {if is_empty($v->assign->search_condition.form.childs.child2_capacities)}
            受け入れなし
          {else}
            <select size="1" name="child2" class="text-right" {$s_disabled} >
              {foreach from=$v->assign->search_condition.form.childs.child2_capacities item=child}
                <option value="{if $child.capacity > 0}{$child.capacity}{/if}"{if $child.current_status} selected="selected"{/if}>{$child.capacity}名</option>
              {/foreach}
            </select>
          {/if}
        </td>
        <td  class="text-center" style="width:100px;"><div style="height:32px;">子供食事あり</div>
          {if is_empty($v->assign->search_condition.form.childs.child4_capacities)}
            受け入れなし
          {else}
            <select size="1" name="child4" class="text-right" {$s_disabled} >
              {foreach from=$v->assign->search_condition.form.childs.child4_capacities item=child}
                <option value="{if $child.capacity > 0}{$child.capacity}{/if}"{if $child.current_status} selected="selected"{/if}>{$child.capacity}名</option>
              {/foreach}
            </select>
          {/if}
        </td>
        <td  class="text-center" style="width:100px;"><div style="height:32px;">布団あり</div>
          {if is_empty($v->assign->search_condition.form.childs.child3_capacities)}
            受け入れなし
          {else}
            <select size="1" name="child3" class="text-right" {$s_disabled} >
              {foreach from=$v->assign->search_condition.form.childs.child3_capacities item=child}
                <option value="{if $child.capacity > 0}{$child.capacity}{/if}"{if $child.current_status} selected="selected"{/if}>{$child.capacity}名</option>
              {/foreach}
            </select>
          {/if}
        </td>
        <td  class="text-center" style="width:100px;"><div style="height:32px;">子供食事なし・<br />布団なし</div>
          {if is_empty($v->assign->search_condition.form.childs.child5_capacities)}
            受け入れなし
          {else}
            <select size="1" name="child5" class="text-right" {$s_disabled} >
              {foreach from=$v->assign->search_condition.form.childs.child5_capacities item=child}
                <option value="{if $child.capacity > 0}{$child.capacity}{/if}"{if $child.current_status} selected="selected"{/if}>{$child.capacity}名</option>
              {/foreach}
            </select>
          {/if}
        </td>
      </tr>
    </table>
    <div class="text-center" style="margin:8px 0 0;"><a name="{$form_capacity_nm}" class="panelsw" href="">×閉じる</a></div>
  </div>
</div>
{/strip}
