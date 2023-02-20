  <td nowrap>{$affiliate_cds.affiliate_cd}{if !is_empty($affiliate_cd_sub)}-{/if}{$v->helper->form->strip_tags($affiliate_cd_sub)}<br /></td>
  {foreach from=$branches item=date_ymd}
    <td align="right">{*
      *}{if $date_ymd.count <= 0}{*
        *}<font color="#cccccc">{$v->helper->form->strip_tags($date_ymd.count)|number_format}<br /></font>{*
      *}{else}{*
        *}{$v->helper->form->strip_tags($date_ymd.count)|number_format}<br />{*
      *}{/if}{*
    *}</td>
    <td align="right">{*
      *}{if $date_ymd.sales_charge <= 0}{*
        *}<font color="#cccccc">{$v->helper->form->strip_tags($date_ymd.sales_charge)|number_format}<br /></font>{*
      *}{else}{*
        *}{$v->helper->form->strip_tags($date_ymd.sales_charge)|number_format}<br />{*
      *}{/if}{*
    *}</td>
    {* 合計の退避 *}
    {$v->helper->store->add("`$date_ymd.date_ymd`_count",$date_ymd.count)}
    {$v->helper->store->add("`$date_ymd.date_ymd`_sales_charge",$date_ymd.sales_charge)}
  {/foreach}
</tr>