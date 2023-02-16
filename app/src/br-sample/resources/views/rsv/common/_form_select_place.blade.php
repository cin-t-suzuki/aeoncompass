{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_select_place.tpl --}}

<select size="1" name="place_p">
    {foreach from=$v->assign->search_condition.form.prefs item=prefs}
      <option value="{$prefs.place}"{if $prefs.current_status} selected="selected"{/if}>{$prefs.place_nm}</option>
    {/foreach}
  </select>
  {if (is_empty($v->assign->search_condition.form.cws)) }
    <br />
    <span>
    <select style="width:200px;" size="1" name="place_ms">
      {foreach from=$v->assign->search_condition.form.areas item=areas}
        <option value="{$areas.place}"{if $areas.current_status} selected="selected"{/if}>{$areas.place_nm}</option>
      {/foreach}
    </select>
    </span>
  {else}
    <span>
    <select style="width:140px;" size="1" name="place_ms">
      {foreach from=$v->assign->search_condition.form.areas item=areas}
        <option value="{$areas.place}"{if $areas.current_status} selected="selected"{/if}>{$areas.place_nm}</option>
      {/foreach}
    </select>
    </span>
    <span>
      <select style="width:140px;" size="1" name="place_cw">
        {foreach from=$v->assign->search_condition.form.cws item=cws}
          <option value="{$cws.place}"{if $cws.current_status} selected="selected"{/if}>{$cws.place_nm}</option>
        {/foreach}
      </select>
    </span>
  {/if}
  