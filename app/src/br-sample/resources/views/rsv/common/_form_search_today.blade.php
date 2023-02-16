{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_today.tpl --}}

{if ($v->assign->search_condition.form.rooms|@count == 0)}
<div style="background:#ffffff;margin:0 auto 10px;width:300px;height:168px;padding:9px 8px 8px;">
    <div>ご指定のプランは満室となりましたので、ご予約いただけなくなりました。 他のプランをご検討ください。</div>
</div>
{else}
{{-- 検索フォーム --}}
<form class="jqs-query parseForm" method="get" action="{$v->env.base_path}query/">
    <table border="0" cellpadding="0" cellspacing="0" width="322">
        <tr>
            <td class="info" colspan="2">今夜の宿（最大３０時まで受付）を予約いただけます。</td>
        </tr>
        <tr>
            <th>
                <div>旅行日程</div>
            </th>
            <td>
                {if $v->helper->date->set($v->assign->search_condition.form.midnight.date_ymd)}{/if}
                <input name="year_month" type="hidden" value="{$v->helper->date->to_format('Y-m')}">
                <input name="day" type="hidden" value="{$v->helper->date->to_format('d')}">
                <span class="font-n" name="year_month_day">{$v->helper->date->to_format('Y年n月j日')}（{$v->helper->date->to_week('j')}） より</span>
                <select class="text-right" name="stay" size="1">
                    {foreach from=$v->assign->search_condition.form.stay item=stay}
                    <option value="{$stay.days}"{if $stay.current_status} selected="selected"{/if}>{$stay.days}泊</option>
                    {/foreach}
                </select>
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
