{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnersite\_info_site.tpl --}}
<table class="br-detail-list">
    <tr><th>精算サイトコード</th><td>{$v->assign->partner_site.site_cd}</td></tr>
    <tr><th>精算サイト名称</th><td>{$v->assign->partner_site.site_nm}</td></tr>
    <tr><th>役職（部署名）</th><td>{$v->assign->partner_site.person_post}</td></tr>
    <tr><th>担当者</th><td>{$v->assign->partner_site.person_nm}</td></tr>
    <tr><th>E-Mail</th><td>{$v->assign->partner_site.email_decrypt}</td></tr>
    <tr>
        <th>通知方法</th>
        <td>
            {if $v->assign->partner_site.mail_send === "0"}通知しない{/if}
            {if $v->assign->partner_site.mail_send === "1"}メールで通知する{/if}
        </td>
    </tr>
    <tr><th>パートナーコード</th><td>{$v->assign->partner_site.partner_cd} {$v->assign->partner_site.partner_nm}</td></tr>
    <tr><th>アフィリエイトコード</th><td>{$v->assign->partner_site.affiliate_cd} {$v->assign->partner_site.affiliate_nm}</td></tr>
    <tr>
        <th>料率タイプ</th>
        <td>
            {if            $v->assign->partner_site_rate.rate_type==1}1:特別提携    0% ベストリザーブオリジナルサイト・光通信等
            {elseif $v->assign->partner_site_rate.rate_type==2}2:通常提携    1%
            {elseif $v->assign->partner_site_rate.rate_type==3}3:特別提携    2% アークスリー等
            {elseif $v->assign->partner_site_rate.rate_type==4}4:日本旅行ビジネストラベルマネージメント（BTM）
            {elseif $v->assign->partner_site_rate.rate_type==5}5:Yahoo!トラベル
            {elseif $v->assign->partner_site_rate.rate_type==6}6:日本旅行    2%
            {elseif $v->assign->partner_site_rate.rate_type==7}7:日本旅行    3% MSD等
            {elseif $v->assign->partner_site_rate.rate_type==8}8:日本旅行    4% JRおでかけネット
            {elseif $v->assign->partner_site_rate.rate_type==9}9:日本旅行    リロクラブ
            {elseif $v->assign->partner_site_rate.rate_type==10}10:GBTNTA 1%(在庫手数料0%)
            {else}0:指定なし
            {/if}
        </td>
    </tr>
    <tr><th>料率開始年月日（直近）</th><td>{$v->assign->partner_site_rate.accept_s_ymd}</td></tr>
    <tr><th>精算先ID<br /> 手数料タイプ「1:販売」用</th><td>{$v->assign->partner_customer_site.customer_id} {$v->assign->partner_customer_site.customer_nm}</td></tr>
</table>
