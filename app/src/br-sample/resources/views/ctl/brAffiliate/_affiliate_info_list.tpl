<table border="1" cellpadding="4" cellspacing="0">

<tr>
  <td colspan="3"  bgcolor="#EEFFEE" ><br /></td>
  {if $v->helper->date->set($v->assign->after_year|cat:'-'|cat:$v->assign->after_month|cat:'-01')}{/if}
  {section name=header loop=$v->assign->claim_lists.values.date_cnt start=0}
    <td align="center" colspan="2" nowrap  bgcolor="#EEFFEE" >
      <small>{$v->helper->date->to_format('Y/m')}</small>
      {if $v->helper->date->add('m', 1)}{/if}
    </td>
  {/section}
</tr>
<tr>
  <td nowrap  bgcolor="#EEFFEE" ><small>名称</small></td>
  <td nowrap  bgcolor="#EEFFEE" ><small>プログラム名称</small></td>
  <td nowrap  bgcolor="#EEFFEE" ><small>アフィリエイト番号</small></td>
  {section name=header loop=$v->assign->claim_lists.values.date_cnt start=0}
    <td nowrap  bgcolor="#EEFFEE" ><small>宿泊総件数</small></td>
    <td nowrap  bgcolor="#EEFFEE" ><small>宿泊料金合計</small></td>
  {/section}
</tr>

{foreach from=$v->assign->claim_lists.values.affiliater_cd item=affiliaters key=affiliater_cd name=affiliaters}

  {include file=$v->env.module_root|cat:'/views/braffiliate/_affiliate.tpl' assign=affiliate}
  {$affiliate}

  {foreach from=$affiliaters.affiliate_cd item=affiliate_cds key=affiliate_cd name=affiliate_cds}

    {include file=$v->env.module_root|cat:'/views/braffiliate/_program.tpl' assign=program}
    {$program}

    {foreach from=$affiliate_cds.claim item=branches key=affiliate_cd_sub name=branches}

      {include file=$v->env.module_root|cat:'/views/braffiliate/_branch.tpl' assign=branch}
      {$branch}
    {/foreach}
  {/foreach}
{/foreach}



<tr>
  <td colspan="3" align="right"  bgcolor="#EEFFEE" >総合計</td>
  {if $v->helper->date->set($v->assign->after_year|cat:'-'|cat:$v->assign->after_month|cat:'-01')}{/if}
  {section name=header loop=$v->assign->claim_lists.values.date_cnt start=0}
    {assign var=total_count value=""}
    {assign var=total_sales_charge value=""}

    {assign var=date_ymd value=$v->helper->date->get()}
    {foreach from=$v->helper->store->gets("`$date_ymd`_count") item=count name=count}
      {assign var=total_count value=$total_count+$count}
    {/foreach}

    {foreach from=$v->helper->store->gets("`$date_ymd`_sales_charge") item=sales_charge name=sales_charge}
      {assign var=total_sales_charge value=$total_sales_charge+$sales_charge}
    {/foreach}
    <td align="right" bgcolor="#EEFFEE" >{$total_count|number_format}<br /></td>
    <td align="right" bgcolor="#EEFFEE" >{$total_sales_charge|number_format}<br /></td>
    {if $v->helper->date->add('m', 1)}{/if}
  {/section}
</tr>
</table>