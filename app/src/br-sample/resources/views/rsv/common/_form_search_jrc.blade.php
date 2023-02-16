{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_jrc.tpl --}}

<form class="parseForm" method="get" action="/jrc/{$v->assign->pre_uri}" target="_blank">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>
                <div>出発地</div>
            </th>
            <td>
                <select name="dept" size="1">
                    <option value="1"{if $v->user->member->pref_id == '1'} selected="selected"{/if}>北海道</option>
                    <option value="11"{if $v->user->member->pref_id >= 2 and $v->user->member->pref_id <= 7} selected="selected"{/if}>東北</option>
                    <option value="31"{if is_empty($v->user->member->pref_id) or ($v->user->member->pref_id >= 8 and $v->user->member->pref_id <= 14)} selected="selected"{/if}>首都圏</option>
                    <option value="41"{if $v->user->member->pref_id >= 19 and $v->user->member->pref_id <= 23} selected="selected"{/if}>中部</option>
                    <option value="46"{if $v->user->member->pref_id >= 15 and $v->user->member->pref_id <= 18} selected="selected"{/if}>北陸</option>
                    <option value="56"{if $v->user->member->pref_id >= 24 and $v->user->member->pref_id <= 30} selected="selected"{/if}>関西</option>
                    <option value="61"{if $v->user->member->pref_id >= 31 and $v->user->member->pref_id <= 35} selected="selected"{/if}>中国</option>
                    <option value="71"{if $v->user->member->pref_id >= 36 and $v->user->member->pref_id <= 39} selected="selected"{/if}>四国</option>
                    <option value="81"{if $v->user->member->pref_id >= 40 and $v->user->member->pref_id <= 46} selected="selected"{/if}>九州</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <div>出発日</div>
            </th>
            <td>
                <select name="year_month" size="1">
                </select>
                <select class="text-right" name="day" size="1">
                </select>&nbsp;
            </td>
        </tr>
        <tr>
            <th>
                <div>１室人数</div>
            </th>
            <td>
                <select class="text-right" name="guest" size="1">
                    {section name=senior start=1 loop=7}
                    <option value="{$smarty.section.senior.index}"{ if $smarty.section.senior.index==$v->assign->senior} selected="selected"{
                        /if}>{$smarty.section.senior.index}名</option>
                    {/section}
                </select>
            </td>
        </tr>
        {if is_empty($v->hotel.hotel_cd)}
        <tr>
            <th>
                <div>方面</div>
            </th>
            <td>
                <select name="dict" size="1">
                    <option value="">
                    </option>
                    <option value="l1"{if $v->assign->area_id == 'l1'} selected="selected"{/if}>北海道</option>
                    <option value="l2"{if $v->assign->area_id == 'l2'} selected="selected"{/if}>東北</option>
                    <option value="l3"{if $v->assign->area_id == 'l3'} selected="selected"{/if}>北関東</option>
                    <option value="l4"{if $v->assign->area_id == 'l4'} selected="selected"{/if}>首都圏</option>
                    <option value="l5"{if $v->assign->area_id == 'l5'} selected="selected"{/if}>甲信越</option>
                    <option value="l6"{if $v->assign->area_id == 'l6'} selected="selected"{/if}>北陸</option>
                    <option value="l7"{if $v->assign->area_id == 'l7'} selected="selected"{/if}>東海</option>
                    <option value="l8"{if $v->assign->area_id == 'l8'} selected="selected"{/if}>近畿</option>
                    <option value="l9"{if $v->assign->area_id == 'l9'} selected="selected"{/if}>中国</option>
                    <option value="l10"{if $v->assign->area_id == 'l10'} selected="selected"{/if}>四国</option>
                    <option value="l11"{if $v->assign->area_id == 'l11'} selected="selected"{/if}>九州</option>
                    <option value="l12"{if $v->assign->area_id == 'l12'} selected="selected"{/if}>沖縄</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <div>都道府県</div>
            </th>
            <td>
                <select name="pref" size="1">
                    <option value="">
                    </option>
                    <option value="p01"{if $v->assign->place_cd == 'p01'} selected="selected"{/if}>北海道</option>
                    <option value="p02"{if $v->assign->place_cd == 'p02'} selected="selected"{/if}>青森県</option>
                    <option value="p03"{if $v->assign->place_cd == 'p03'} selected="selected"{/if}>岩手県</option>
                    <option value="p04"{if $v->assign->place_cd == 'p04'} selected="selected"{/if}>宮城県</option>
                    <option value="p05"{if $v->assign->place_cd == 'p05'} selected="selected"{/if}>秋田県</option>
                    <option value="p06"{if $v->assign->place_cd == 'p06'} selected="selected"{/if}>山形県</option>
                    <option value="p07"{if $v->assign->place_cd == 'p07'} selected="selected"{/if}>福島県</option>
                    <option value="p08"{if $v->assign->place_cd == 'p08'} selected="selected"{/if}>茨城県</option>
                    <option value="p09"{if $v->assign->place_cd == 'p09'} selected="selected"{/if}>栃木県</option>
                    <option value="p10"{if $v->assign->place_cd == 'p10'} selected="selected"{/if}>群馬県</option>
                    <option value="p11"{if $v->assign->place_cd == 'p11'} selected="selected"{/if}>埼玉県</option>
                    <option value="p12"{if $v->assign->place_cd == 'p12'} selected="selected"{/if}>千葉県</option>
                    <option value="p13"{if $v->assign->place_cd == 'p13'} selected="selected"{/if}>東京都</option>
                    <option value="p14"{if $v->assign->place_cd == 'p14'} selected="selected"{/if}>神奈川県</option>
                    <option value="p15"{if $v->assign->place_cd == 'p15'} selected="selected"{/if}>新潟県</option>
                    <option value="p16"{if $v->assign->place_cd == 'p16'} selected="selected"{/if}>富山県</option>
                    <option value="p17"{if $v->assign->place_cd == 'p17'} selected="selected"{/if}>石川県</option>
                    <option value="p18"{if $v->assign->place_cd == 'p18'} selected="selected"{/if}>福井県</option>
                    <option value="p19"{if $v->assign->place_cd == 'p19'} selected="selected"{/if}>山梨県</option>
                    <option value="p20"{if $v->assign->place_cd == 'p20'} selected="selected"{/if}>長野県</option>
                    <option value="p21"{if $v->assign->place_cd == 'p21'} selected="selected"{/if}>岐阜県</option>
                    <option value="p22"{if $v->assign->place_cd == 'p22'} selected="selected"{/if}>静岡県</option>
                    <option value="p23"{if $v->assign->place_cd == 'p23'} selected="selected"{/if}>愛知県</option>
                    <option value="p24"{if $v->assign->place_cd == 'p24'} selected="selected"{/if}>三重県</option>
                    <option value="p25"{if $v->assign->place_cd == 'p25'} selected="selected"{/if}>滋賀県</option>
                    <option value="p26"{if $v->assign->place_cd == 'p26'} selected="selected"{/if}>京都府</option>
                    <option value="p27"{if $v->assign->place_cd == 'p27'} selected="selected"{/if}>大阪府</option>
                    <option value="p28"{if $v->assign->place_cd == 'p28'} selected="selected"{/if}>兵庫県</option>
                    <option value="p29"{if $v->assign->place_cd == 'p29'} selected="selected"{/if}>奈良県</option>
                    <option value="p30"{if $v->assign->place_cd == 'p30'} selected="selected"{/if}>和歌山県</option>
                    <option value="p31"{if $v->assign->place_cd == 'p31'} selected="selected"{/if}>鳥取県</option>
                    <option value="p32"{if $v->assign->place_cd == 'p32'} selected="selected"{/if}>島根県</option>
                    <option value="p33"{if $v->assign->place_cd == 'p33'} selected="selected"{/if}>岡山県</option>
                    <option value="p34"{if $v->assign->place_cd == 'p34'} selected="selected"{/if}>広島県</option>
                    <option value="p35"{if $v->assign->place_cd == 'p35'} selected="selected"{/if}>山口県</option>
                    <option value="p36"{if $v->assign->place_cd == 'p36'} selected="selected"{/if}>徳島県</option>
                    <option value="p37"{if $v->assign->place_cd == 'p37'} selected="selected"{/if}>香川県</option>
                    <option value="p38"{if $v->assign->place_cd == 'p38'} selected="selected"{/if}>愛媛県</option>
                    <option value="p39"{if $v->assign->place_cd == 'p39'} selected="selected"{/if}>高知県</option>
                    <option value="p40"{if $v->assign->place_cd == 'p40'} selected="selected"{/if}>福岡県</option>
                    <option value="p41"{if $v->assign->place_cd == 'p41'} selected="selected"{/if}>佐賀県</option>
                    <option value="p42"{if $v->assign->place_cd == 'p42'} selected="selected"{/if}>長崎県</option>
                    <option value="p43"{if $v->assign->place_cd == 'p43'} selected="selected"{/if}>熊本県</option>
                    <option value="p44"{if $v->assign->place_cd == 'p44'} selected="selected"{/if}>大分県</option>
                    <option value="p45"{if $v->assign->place_cd == 'p45'} selected="selected"{/if}>宮崎県</option>
                    <option value="p46"{if $v->assign->place_cd == 'p46'} selected="selected"{/if}>鹿児島県</option>
                    <option value="p47"{if $v->assign->place_cd == 'p47'} selected="selected"{/if}>沖縄県</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <div>エリア</div>
            </th>
            <td>
                <select name="area" size="1">
                    <option value="">
                    </option>
                    <option value="p01"{if $v->assign->place_cd == 'p01'} selected="selected"{/if}>北海道</option>
                    <option value="p02"{if $v->assign->place_cd == 'p02'} selected="selected"{/if}>東北</option>
                </select>
            </td>
        </tr>
        {/if}
    </table>
    {if is_empty($v->hotel.hotel_cd)}
    <div class="sfm-jrc-submit">
        <div class="btn-b06-138-sb" style="margin:0 auto;">
            <input class="btnimg collectBtn collectForce" src="{$v->env.root_path}img/btn/b06-jrc1.gif" type="image" alt="ＪＲ＋宿泊検索" />
        </div>
    </div>
    <div style="text-align:center;">※ご予約は日本旅行サイトでのご予約となります。</div>
    {else}
    <div class="sfm-jrc-submit">
        <div class="btn-b06-138-sb" style="margin:0 auto;">
            <input class="btnimg collectBtn collectForce" src="{$v->env.root_path}img/btn/b06-jrc3.gif" type="image" alt="ＪＲ＋宿泊検索へすすむ" />
        </div>
    </div>
    <div style="text-align:center;">この商品は株式会社日本旅行が企画・実施しております。</div>
    <input name="hotel_cd" type="hidden" value="{$v->hotel.jrc_hotel_cd}" />
    {/if}
    <input name="today" type="hidden" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
</form>
