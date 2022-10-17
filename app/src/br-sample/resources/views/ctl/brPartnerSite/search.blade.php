{{-- css --}}
{include file='./_css.tpl'}
{{-- js --}}
{include file='./_js.tpl'}
{{-- 提携先管理ヘッダー --}}
{include file='../_common/_br_header2.tpl' title="パートナー精算サイト一覧"}

    <hr class="contents-margin" />

    <div style="text-align:left;">

        {{-- 検索フォーム --}}
        {include file='./_form.tpl'}

        <hr class="contents-margin" />

        {{-- 新規登録 --}}
        <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/edit/" method="POST">
            <small>
                <input type="submit" value="対象サイトの新規登録">
                {foreach from=$v->assign->search_params item=value key=key}
                    <input type="hidden" name="{$key}" value="{$value}" />
                {/foreach}
            </small>
        </form>

    </div>

    {{-- 一覧表示 --}}
    <table class="br-detail-list">
        <tr>
            <th>精算サイト<br />コード</th>
            <th>精算サイト<br />名称</th>
            <th>通知方法<br />通知先Email</th>
            <th>対象サイト</th>
            <th>精算先</th>
            <th></th>
        </tr>
        { foreach from=$v->assign->sites item=site name=sites}
            <tr>
                <td>{$site.site_cd}</td>
                <td>
                    {$site.site_nm}
                    {if !is_empty($site.person_post)}
                        <br />{$site.person_post}
                    {/if}
                    {if !is_empty($site.person_nm)}
                        <br />{$site.person_nm} 様
                    {/if}
                </td>
                <td>
                    通知方法：
                    {if ($site.mail_send==1)}
                        メールで通知する
                    {else}
                        通知しない
                    {/if}<br />
                    email:{$site.email_decrypt|replace:',':'<br />'}<br />
                </td>
                <td>
                    {if !is_empty($site.partner_cd)}
                        パートナー<br />{$site.partner_nm}（{$site.partner_cd}）
                    {/if}
                    {if !is_empty($site.affiliate_cd)}
                        アフィリエイト<br />{$site.affiliate_nm}（{$site.affiliate_cd}）
                    {/if}
                </td>
                <td>
                    {if is_empty($site.sales_customer_id) and is_empty($site.stock_customer_id)}
                        {if !is_empty($site.partner_cd)}
                            料率タイプを設定してください。
                        {/if}
                        {if !is_empty($site.affiliate_cd)}
                            指定なし
                        {/if}
                    {/if}
                    {if !is_empty($site.stock_customer_id)}
                        {$site.stock_customer_nm}（{$site.stock_customer_id}）
                        <form action="{$v->env.path_base_module}/brpartnercustomer/edit/" method="post">
                            <input type="submit" value=" 精算先表示 ">
                            <input type="hidden" name="customer_id"    value="{$site.sales_customer_id}" />
                            {foreach from=$v->assign->search_params item=value key=key}
                                {if ($key!= 'customer_id')}
                                    <input type="hidden" name="{$key}" value="{$value}" />
                                {/if}
                            {/foreach}
                        </form>
                    {/if}
                    {if !is_empty($site.sales_customer_id)}
                        {if !is_empty($site.stock_customer_id)}
                            <hr />
                        {/if}
                        {$site.sales_customer_nm}（{$site.sales_customer_id}）
                        <form action="{$v->env.path_base_module}/brpartnercustomer/edit/" method="post">
                            <input type="submit" value=" 精算先表示 ">
                            <input type="hidden" name="customer_id"    value="{$site.sales_customer_id}" />
                            {foreach from=$v->assign->search_params item=value key=key}
                                {if ($key!= 'customer_id')}
                                    <input type="hidden" name="{$key}" value="{$value}" />
                                {/if}
                            {/foreach}
                        </form>
                    {/if}
                </td>
                <td style="text-align:center;">
                    <form action="{$v->env.path_base_module}/brpartnersite/edit/" method="post">
                        <input type="submit" value=" 表示・編集 ">
                        <input type="hidden" name="site_cd" value="{$site.site_cd}" />
                        {foreach from=$v->assign->search_params item=value key=key}
                            <input type="hidden" name="{$key}" value="{$value}" />
                        {/foreach}
                    </form>
                </td>
            </tr>
        { /foreach}
    </table>
    <hr class="contents-margin" />

    {{-- 請求先一覧へ --}}
    <form action="{$v->env.source_path}{$v->env.module}/brpartnercustomer/search/" method="POST">
        <small>
            {foreach from=$v->assign->search_params item=value key=key}
                <input type="hidden" name="{$key}" value="{$value}" />
            {/foreach}

            <input type="submit" value="請求先一覧へ">
        </small>
    </form>

    <hr class="contents-margin" />

{{-- 提携先管理フッター --}}
{include file='../_common/_br_footer.tpl'}
{{-- /提携先管理フッター --}}