{* 初期表示時の部屋タイプを保持 *}
          {if is_empty($v->assign->search_condition.filter.icp) or is_empty($v->assign->search_condition.filter.irt) or is_empty($v->assign->search_condition.filter.iml) or is_empty($v->assign->search_condition.filter.ism) or is_empty($v->assign->search_condition.filter.ipw)
                or is_empty($v->assign->search_condition.filter.ipt) or is_empty($v->assign->search_condition.filter.inw) or is_empty($v->assign->search_condition.filter.icd) or is_empty($v->assign->search_condition.filter.ihs)
                or is_empty($v->assign->search_condition.filter.ist) or is_empty($v->assign->search_condition.filter.ipc)}
            {assign var='params' value=$params|cat:'&icp='}{* 限定プラン *}
            {if $v->assign->search_condition.filter.ocp.on}{         assign var='params' value=$params|cat:'1'}{else}{assign var='params' value=$params|cat:'0'}{/if}
            {assign var='params' value='irt='|cat:$v->assign->search_condition.filter.irt}{* 部屋タイプ *}
            {assign var='params' value=$params|cat:'&iml='|cat:$v->assign->search_condition.filter.iml}{* 食事 *}
            {assign var='params' value=$params|cat:'&ism='|cat:$v->assign->search_condition.filter.ism}{* 禁煙喫煙 *}
            {assign var='params' value=$params|cat:'&ipw='|cat:$v->assign->search_condition.filter.ipw}{* 決済方法 *}
            {assign var='params' value=$params|cat:'&ipt='|cat:$v->assign->search_condition.filter.ipt}{* ポイント *}
            {assign var='params' value=$params|cat:'&inw='|cat:$v->assign->search_condition.filter.inw}{* ネットワーク *}
            {assign var='params' value=$params|cat:'&icd='|cat:$v->assign->search_condition.filter.icd}{* 子供 *}
            {assign var='params' value=$params|cat:'&ihs='|cat:$v->assign->search_condition.filter.ihs}{* 駅までの距離 *}
            {assign var='params' value=$params|cat:'&ist='}{* 駅 *}
            {assign var='params' value=$params|cat:'&ipc='}{* 地域 *}
            {assign var='params' value=$params|cat:'&'}
          {/if}

        <div class="sch-filter">
        {if 2 <= ($v->assign->search_condition.filter.ort|@count) or 0 < $v->assign->search_condition.filter.irt}
          <div class="roomtype">
            <dl>
              <dt class="filter-title">部屋タイプ</dt>
              {    if  $v->assign->search_condition.filter.ort.off || is_empty($v->assign->search_condition.filter.ort.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.ort.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">指定しない</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.0) && $v->assign->search_condition.filter.ort.0 == true}<dd><strong>カプセル</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.0) || substr($v->assign->search_condition.filter.irt, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=0&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">カプセル</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.1) && $v->assign->search_condition.filter.ort.1 == true}<dd><strong>シングル</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.1) || substr($v->assign->search_condition.filter.irt, 1, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=1&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">シングル</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.3) && $v->assign->search_condition.filter.ort.3 == true}<dd><strong>セミダブル</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.3) || substr($v->assign->search_condition.filter.irt, 3, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=3&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">セミダブル</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.4) && $v->assign->search_condition.filter.ort.4 == true}<dd><strong>ダブル</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.4) || substr($v->assign->search_condition.filter.irt, 4, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=4&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">ダブル</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.2) && $v->assign->search_condition.filter.ort.2 == true}<dd><strong>ツイン</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.2) || substr($v->assign->search_condition.filter.irt, 2, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=2&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">ツイン</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.5) && $v->assign->search_condition.filter.ort.5 == true}<dd><strong>トリプル</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.5) || substr($v->assign->search_condition.filter.irt, 5, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=5&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">トリプル</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.6) && $v->assign->search_condition.filter.ort.6 == true}<dd><strong>４ベッド</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.6) || substr($v->assign->search_condition.filter.irt, 6, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=6&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">４ベッド</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.8) && $v->assign->search_condition.filter.ort.8 == true}<dd><strong>メゾネット</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.8) || substr($v->assign->search_condition.filter.irt, 8, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=8&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">メゾネット</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.7) && $v->assign->search_condition.filter.ort.7 == true}<dd><strong>スイート</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.7) || substr($v->assign->search_condition.filter.irt, 7, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=7&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">スイート</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.9) && $v->assign->search_condition.filter.ort.9 == true}<dd><strong>和室</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.9) || substr($v->assign->search_condition.filter.irt, 9, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=9&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">和室</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.ort.10) && $v->assign->search_condition.filter.ort.10 == true}<dd><strong>和洋室</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.ort.10) || substr($v->assign->search_condition.filter.irt, 10, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ort=10&{$params}{$v->helper->form->to_query_correct('view,ort,page', false)}">和洋室</a></dd>
              {/if}
            </dl>
          </div>
        {/if}

        {if 1 <= ($v->assign->search_condition.filter.oml|@count) or 0 < $v->assign->search_condition.filter.iml}
          <div class="meal">
            <dl>
              <dt class="filter-title">食事</dt>
              {    if  $v->assign->search_condition.filter.oml.off || is_empty($v->assign->search_condition.filter.oml.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.oml.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,oml,page', false)}">指定しない</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.oml.nothing) && $v->assign->search_condition.filter.oml.nothing == true}<dd><strong>食事なし</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.oml.nothing) || substr($v->assign->search_condition.filter.iml, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?oml=nothing&{$params}{$v->helper->form->to_query_correct('view,oml,page', false)}">食事なし</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.oml.dinner) && $v->assign->search_condition.filter.oml.dinner == true}<dd><strong>夕食付き</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.oml.dinner) || substr($v->assign->search_condition.filter.iml, 1, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?oml=dinner&{$params}{$v->helper->form->to_query_correct('view,oml,page', false)}">夕食付き</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.oml.breakfast) && $v->assign->search_condition.filter.oml.breakfast == true}<dd><strong>朝食付き</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.oml.breakfast) || substr($v->assign->search_condition.filter.iml, 2, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?oml=breakfast&{$params}{$v->helper->form->to_query_correct('view,oml,page', false)}">朝食付き</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.oml.dinnerbreakfast) && $v->assign->search_condition.filter.oml.dinnerbreakfast == true}<dd><strong>夕朝食付き</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.oml.dinnerbreakfast) || substr($v->assign->search_condition.filter.iml, 3, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?oml=dinnerbreakfast&{$params}{$v->helper->form->to_query_correct('view,oml,page', false)}">夕朝食付き</a></dd>
              {/if}
            </dl>
          </div>
        {/if}

        {if 1 <= ($v->assign->search_condition.filter.osm|@count) or 0 < $v->assign->search_condition.filter.ism}
          <div class="smoking">
            <dl>
              <dt class="filter-title">喫煙禁煙ルーム</dt>
              {    if  $v->assign->search_condition.filter.osm.off || is_empty($v->assign->search_condition.filter.osm.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.osm.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,osm,page', false)}">指定しない</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.osm.no_smoking) && $v->assign->search_condition.filter.osm.no_smoking == true}<dd><strong>禁煙ルーム</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.osm.no_smoking) || substr($v->assign->search_condition.filter.ism, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?osm=no_smoking&{$params}{$v->helper->form->to_query_correct('view,osm,page', false)}">禁煙ルーム</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.osm.smoking) && $v->assign->search_condition.filter.osm.smoking == true}<dd><strong>喫煙ルーム</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.osm.smoking) || substr($v->assign->search_condition.filter.ism, 1, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?osm=smoking&{$params}{$v->helper->form->to_query_correct('view,osm,page', false)}">喫煙ルーム</a></dd>
              {/if}
            </dl>
          </div>
        {/if}

        {if 1 <= ($v->assign->search_condition.filter.opw|@count) or 0 < $v->assign->search_condition.filter.ipw}
          <div class="payment">
            <dl>
              <dt class="filter-title">決済方法</dt>
              {    if  $v->assign->search_condition.filter.opw.off || is_empty($v->assign->search_condition.filter.opw.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.opw.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,opw,page', false)}">指定しない</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.opw.online) && $v->assign->search_condition.filter.opw.online == true}<dd><strong>事前カード決済</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.opw.online) || substr($v->assign->search_condition.filter.ipw, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?opw=online&{$params}{$v->helper->form->to_query_correct('view,opw,page', false)}">事前カード決済</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.opw.cash) && $v->assign->search_condition.filter.opw.cash == true}<dd><strong>現地払い</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.opw.cash) || substr($v->assign->search_condition.filter.ipw, 1, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?opw=cash&{$params}{$v->helper->form->to_query_correct('view,opw,page', false)}">現地払い</a></dd>
              {/if}
            </dl>
          </div>
        {/if}

        {if 1 <= ($v->assign->search_condition.filter.opt|@count) or 0 < $v->assign->search_condition.filter.ipt}
          <div class="point">
            <dl>
              <dt class="filter-title">ポイント</dt>
              {    if  $v->assign->search_condition.filter.opt.off || is_empty($v->assign->search_condition.filter.opt.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.opt.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,opt,page', false)}">指定しない</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.opt.get) && $v->assign->search_condition.filter.opt.get == true}<dd><strong>ポイントがもらえる</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.opt.get) || substr($v->assign->search_condition.filter.ipt, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?opt=get&{$params}{$v->helper->form->to_query_correct('view,opt,page', false)}">ポイントがもらえる</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.opt.get_more) && $v->assign->search_condition.filter.opt.get_more == true}<dd><strong>ポイントがもっともらえる</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.opt.get_more) || substr($v->assign->search_condition.filter.ipt, 1, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?opt=get_more&{$params}{$v->helper->form->to_query_correct('view,opt,page', false)}">ポイントがもっともらえる</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.opt.use) && $v->assign->search_condition.filter.opt.use == true}<dd><strong>ポイントが使える</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.opt.use) || substr($v->assign->search_condition.filter.ipt, 2, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?opt=use&{$params}{$v->helper->form->to_query_correct('view,opt,page', false)}">ポイントが使える</a></dd>
              {/if}
            </dl>
          </div>
        {/if}

        {if 1 <= ($v->assign->search_condition.filter.onw|@count) or 0 < $v->assign->search_condition.filter.inw}
          <div class="network">
            <dl>
              <dt class="filter-title">ネットワーク</dt>
              {    if  $v->assign->search_condition.filter.onw.off || is_empty($v->assign->search_condition.filter.onw.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.onw.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,onw,page', false)}">指定しない</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.onw.free) && $v->assign->search_condition.filter.onw.free == true}<dd><strong>全客室にて無料で対応</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.onw.free) || substr($v->assign->search_condition.filter.inw, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?onw=free&{$params}{$v->helper->form->to_query_correct('view,onw,page', false)}">全客室にて無料で対応</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.onw.free_part) && $v->assign->search_condition.filter.onw.free_part == true}<dd><strong>一部客室にて無料で対応</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.onw.free_part) || substr($v->assign->search_condition.filter.inw, 1, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?onw=free_part&{$params}{$v->helper->form->to_query_correct('view,onw,page', false)}">一部客室にて無料で対応</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.onw.charge) && $v->assign->search_condition.filter.onw.charge == true}<dd><strong>全客室にて有料で対応</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.onw.charge) || substr($v->assign->search_condition.filter.inw, 2, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?onw=charge&{$params}{$v->helper->form->to_query_correct('view,onw,page', false)}">全客室にて有料で対応</a></dd>
              {/if}
              {    if !is_empty($v->assign->search_condition.filter.onw.charge_part) && $v->assign->search_condition.filter.onw.charge_part == true}<dd><strong>一部客室にて有料で対応</strong></dd>
              {elseif !is_empty($v->assign->search_condition.filter.onw.charge_part) || substr($v->assign->search_condition.filter.inw, 3, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?onw=charge_part&{$params}{$v->helper->form->to_query_correct('view,onw,page', false)}">一部客室にて有料で対応</a></dd>
              {/if}
            </dl>
          </div>
        {/if}

        {if 1 <= ($v->assign->search_condition.filter.ocd|@count) or 0 < $v->assign->search_condition.filter.icd}
          <div class="child">
            <dl>
              <dt class="filter-title">子供受入</dt>
              {    if $v->assign->search_condition.filter.ocd.off || is_empty($v->assign->search_condition.filter.ocd.off)}<dd><strong>指定しない</strong></dd>
              {elseif !$v->assign->search_condition.filter.ocd.off}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?{$params}{$v->helper->form->to_query_correct('view,ocd,page', false)}">指定しない</a></dd>
              {/if}
              {    if  $v->assign->search_condition.filter.ocd.on }<dd><strong>子供受入可</strong></dd>
              {elseif !$v->assign->search_condition.filter.ocd.on || substr($v->assign->search_condition.filter.icd, 0, 1) == 1}<dd><a href="{$v->env.path_base}{$v->env.path_x_uri}/{if $type=='map'}map/{/if}?ocd=on&{$params}{$v->helper->form->to_query_correct('view,ocd,page', false)}">子供受入可</a></dd>
              {/if}
            </dl>
          </div>
        {/if}
      </div>
