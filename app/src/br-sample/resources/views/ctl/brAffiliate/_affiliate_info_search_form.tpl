<table border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td  bgcolor="#EEFFEE" >対象年月</td>
    <td>
      {if $v->helper->date->set($smarty.now*1)}{/if}
      {if $v->helper->date->add('m', -1)}{/if}
      {assign var=start_year value=$v->helper->form->strip_tags($v->assign->start_year)}
      <select size="1" name="after_year">
        {section name=after_year loop=$v->helper->form->strip_tags($v->assign->loop_cnt)}
          <option value="{$start_year}"
          {if $v->assign->after_year == $start_year
          ||  is_empty($v->assign->after_year) && $start_year == $v->helper->date->to_format('Y')}
            selected="selected"
          {/if}>{$start_year}</option>
          {assign var=start_year value=$start_year+1}
        {/section}
      </select> 年 
      <select size="1" name="after_month">
        {section name = after_month start = 1 loop = 13}
          <option value="{$v->helper->form->strip_tags($smarty.section.after_month.index)|string_format:"%02d"}"
          {if $v->assign->after_month == $smarty.section.after_month.index|string_format:"%02d"
          ||  is_empty($v->assign->after_month) && $smarty.section.after_month.index|string_format:"%02d" == $v->helper->date->to_format('m')}
            selected="selected"
          {/if}>
          {$v->helper->form->strip_tags($smarty.section.after_month.index)|string_format:"%02d"}
          </option>
        {/section}
      </select> 月 ～ 
      
      {assign var=start_year value=$v->helper->form->strip_tags($v->assign->start_year)}
      <select size="1" name="before_year">
        {section name=before_year loop=$v->helper->form->strip_tags($v->assign->loop_cnt)}
          <option value="{$start_year}"
          {if $v->assign->before_year == $start_year
          ||  is_empty($v->assign->before_year) && $start_year == $v->helper->date->to_format('Y')}
            selected="selected"
          {/if}>{$start_year}</option>
          {assign var=start_year value=$start_year+1}
        {/section}
      </select> 年 
      <select size="1" name="before_month">
        {section name = before_month start = 1 loop = 13}
          <option value="{$v->helper->form->strip_tags($smarty.section.before_month.index)|string_format:"%02d"}"
          {if $v->assign->before_month == $smarty.section.before_month.index|string_format:"%02d"
          ||  is_empty($v->assign->before_month) && $smarty.section.before_month.index|string_format:"%02d" == $v->helper->date->to_format('m')}
            selected="selected"
          {/if}>
          {$v->helper->form->strip_tags($smarty.section.before_month.index)|string_format:"%02d"}
          </option>
        {/section}
      </select> 月     
    </td>
  </tr>

  <tr>
    <td  bgcolor="#EEFFEE" >アフィリエイター</td>
    <td>
      <select size="1" name="affiliater_cd">
        <option value="" {if $v->assign->affiliater_cd === ""} selected="selected" {/if}>
          指定しない(全社)
        </option>
        {foreach from=$v->assign->affiliater_lists.values item=affiliater_lists}
          {if $affiliater_lists.affiliater_cd != $tmp_affiliater_cd}
            <option value="{$v->helper->form->strip_tags($affiliater_lists.affiliater_cd)}"
              {if $v->assign->affiliater_cd == $affiliater_lists.affiliater_cd
              ||  $v->assign->affiliater_cd === null && $affiliater_lists.affiliater_cd == 'A050600007'}
                selected="selected"
              {/if}>
              {$v->helper->form->strip_tags($affiliater_lists.affiliater_cd)} {$v->helper->form->strip_tags($affiliater_lists.affiliater_nm)}
            </option>
            {assign var=tmp_affiliater_cd value=$v->helper->form->strip_tags($affiliater_lists.affiliater_cd)}
          {/if}
        {/foreach}
      </select>
    </td>
  </tr>
  <tr>
    <td  bgcolor="#EEFFEE" >　</td>
    <td><input type="submit" name="search" value=" 表示 "></td>
  </tr>
</table>