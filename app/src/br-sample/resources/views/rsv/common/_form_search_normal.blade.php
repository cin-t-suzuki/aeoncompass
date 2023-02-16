{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_normal.tpl --}}

{if ($v->assign->search_condition.form.rooms|@count == 0)}
<div style="background:#ffffff;margin:0 auto 10px;width:300px;height:168px;padding:9px 8px 8px;">
    <div>ご指定のプランは満室となりましたので、ご予約いただけなくなりました。 他のプランをご検討ください。</div>
</div>
{else}
{{-- 検索フォーム --}}
<form class="jqs-query parseForm" method="get" action="{$v->env.path_base}/query/">
    <table border="0" cellpadding="0" cellspacing="0" width="322">
        <tr>
            <th>
                <div class="div-h">旅行日程</div>
            </th>
            <td>
                {{-- 年月表示（13月まで表示） --}}
                <select name="year_month" size="1">
                    {foreach from=$v->assign->search_condition.form.year_month item=months}
                    <option value="{$months.date_ym}"{if $months.current_status} selected="selected"{/if}>{$months.date_ym|substr:0:4}年{$months.date_ym|substr:5:2|number_format}月</option>
                    {/foreach}
                </select>
                {{-- 日表示のための31回ループ --}}
                <select class="text-right" name="day" size="1">
                    {foreach from=$v->assign->search_condition.form.days item=days}
                    <option value="{$days.date_ymd}"{if $days.current_status} selected="selected"{/if}>{$days.date_ymd}日</option>
                    {/foreach}
                </select>
                &nbsp;&nbsp;
                <a class="jqs-calendar" href="#calendar">
                    <img src="{$v->env.path_img}/lhd/lhd-calendar.gif" alt="" />
                </a>
                <br />
                <select class="text-right" name="stay" size="1">
                    {foreach from=$v->assign->search_condition.form.stay item=stay}
                    <option value="{$stay.days}"{if $stay.current_status} selected="selected"{/if}>{$stay.days}泊</option>
                    {/foreach}
                </select>
                &nbsp;&nbsp;
                {if is_empty($v->assign->search_condition.form.hotel.room_id)}
                <input name="date_status" type="checkbox"{if value="on" $v->assign->search_condition.form.date_status == 'on'} checked="checked"{/if} id="date_status" /> <label class="checkbox" for="date_status">日程未定</label>
                {/if}
            </td>
        </tr>
        <tr>
            <th>
                <div>部屋数</div>
            </th>
            <td>
                <select class="text-right" name="rooms" size="1">
                    {foreach from=$v->assign->search_condition.form.rooms item=rooms}
                    <option value="{$rooms.room_count}"{if $rooms.current_status} selected="selected"{/if}>{$rooms.room_count}室</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <div>利用人数／室</div>
            </th>
            <td>
                大人<select class="text-right" name="senior" size="1">
                    {foreach from=$v->assign->search_condition.form.senior.capacities item=senior}
                    <option value="{$senior.capacity}"{if $senior.current_status} selected="selected"{/if}>{$senior.capacity}名</option>
                    {/foreach}
                </select>
                {if $v->assign->search_condition.form.childs.accept_status }
                <a class="panelsw" name="guests-normal" href="">子供 <span name="children">{$v->assign->params.child1+$v->assign->params.child2+$v->assign->params.child3+$v->assign->params.child4+$v->assign->params.child5}</span>名</a>
                {include file='../_common/_form_capacity.tpl' form_capacity_nm='guests-normal'}
                {/if}
            </td>
        </tr>
        { if is_empty($v->assign->search_condition.form.hotel.hotel_cd)}
        <tr>
            <th>
                <div class="div-h">予算</div>
            </th>
            <td>1泊1部屋1人あたり<br />
                {foreach from=$v->assign->search_condition.form.charges.min item=charge_min name=charge_min}
                {if $smarty.foreach.charge_min.total == 1}
                {$charge_min.name}
                <input name="charge_min" type="hidden" value="{$charge_min.charge}" />
                {else}
                {if $smarty.foreach.charge_min.first}
                <select class="text-right" name="charge_min" size="1">
                    {/if}
                    <option value="{$charge_min.charge}"{if $charge_min.current_status} selected="selected"{/if}>{$charge_min.name}</option>
                    {if $smarty.foreach.charge_min.last}
                </select>
                {/if}
                {/if}
                {/foreach}
                {foreach from=$v->assign->search_condition.form.charges.max item=charge_max name=charge_max}
                {if $smarty.foreach.charge_max.total == 1}
                {if $smarty.foreach.charge_min.total == 1 and $charge_max.charge == $v->assign->search_condition.form.charges.min.0.charge}{else}～{$charge_max.name}{/if}
                <input name="charge_max" type="hidden" value="{$charge_max.charge}" />
                {else}
                {if $smarty.foreach.charge_max.first}
                ～<select class="text-right" name="charge_max" size="1">
                    {/if}
                    <option value="{$charge_max.charge}"{if $charge_max.current_status} selected="selected"{/if}>{$charge_max.name}</option>
                    {if $smarty.foreach.charge_max.last}
                </select>
                {/if}
                {/if}
                {/foreach}
            </td>
        </tr>
        { /if}
        {{-- 地図 --}}
        {if is_empty($v->assign->search_condition.form.hotel.hotel_cd) and is_empty($v->assign->search_condition.form.hotel.title)}
        <tr>
            <th>
                <div class="div-h">地域</div>
            </th>
            <td>
                {include file='../_common/_form_select_place.tpl'}
            </td>
        </tr>
        { /if}
        {if (!is_empty($v->assign->search_condition.form.type)) }
        <tr>
            <th>
                <div>表示方法</div>
            </th>
            <td>
                <input name="type" value=""{if $v->assign->search_condition.form.type == 'list'} checked="checked"{/if} type="radio" id="list" />
                <label for="list">テキストで表示</label>&nbsp;&nbsp;
                <input name="type" value="map"{if $v->assign->search_condition.form.type == 'map'} checked="checked"{/if} type="radio" id="map" />
                <label for="map">地図で表示</label>
            </td>
        </tr>
        {/if}
        <tr>
            <th>
                <div>GoToトラベル<br>キャンペーン</div>
            </th>
            <td>
                対象プランのみ表示<input id="goto" name="goto" type="checkbox" value="1" {if $v->assign->search_condition.form.goto == "1" }checked="checked"{/if}>
            </td>
        </tr>
    </table>
    {if is_empty($v->assign->search_condition.form.hotel.hotel_cd)}
    <div class="btn-b01-143-sb" style="margin:0 auto;">
        <input class="btnimg collectBtn" src="{$v->env.root_path}img/btn/b01-search1.gif" type="image" alt="空室検索" />
    </div>
    { else}
    <div class="btn-b06-138-sc" style="margin:0 auto;">
        <input class="btnimg collectBtn" src="{$v->env.root_path}img/btn/b06-booking.gif" type="image" alt="予約へすすむ" />
    </div>
    { /if}
    <input name="today" type="hidden" value="{$smarty.now|date_format:'%Y-%m-%d'}" />

    {{-- 施設・プラン --}}
    { if !is_empty($v->assign->search_condition.form.hotel.hotel_cd)}<input name="hotel_cd" type="hidden" value="{$v->assign->search_condition.form.hotel.hotel_cd}" />{/if}
    { if !is_empty($v->assign->search_condition.form.hotel.plan_id)}<input name="plan_id" type="hidden" value="{$v->assign->search_condition.form.hotel.plan_id}" />{/if}
    { if !is_empty($v->assign->search_condition.form.hotel.room_id)}<input name="room_id" type="hidden" value="{$v->assign->search_condition.form.hotel.room_id}" />{/if}
</form>
{/if}
