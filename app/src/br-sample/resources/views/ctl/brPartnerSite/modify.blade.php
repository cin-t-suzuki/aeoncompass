{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnersite\modify.tpl --}}
{{-- css --}}
{include file='./_css.tpl'}
{strip}
    {{-- 提携先管理ヘッダー --}}
    {include file='../_common/_br_header2.tpl' title="精算サイト情報"}

    <hr class="contents-margin" />

    {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

    <hr class="contents-margin" />

    {{-- 精算先情報表示 --}}
    {include file='./_info_site.tpl'}

    {{-- 料率表示 --}}
    {include file='./_info_rate.tpl'}

    <hr class="contents-margin" />

    {{-- 入力画面を表示 --}}
    <p>
        <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/edit/" method="POST">
            <small>
                <input type="hidden" name="site_cd" value="{$v->assign->partner_site.site_cd}" />
                {foreach from=$v->assign->search_params item=value key=key}
                    <input type="hidden" name="{$key}" value="{$value}" />
                {/foreach}
                <input type="submit" value="精算サイト情報の表示・編集へ">
            </small>
        </form>
    </p>

    {{-- 一覧に戻る --}}
    <p>
        <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/search/" method="POST">
            <small>
                {foreach from=$v->assign->search_params item=value key=key}
                    <input type="hidden" name="{$key}" value="{$value}" />
                {/foreach}
                <input type="submit" value="精算サイト一覧へ">
            </small>
        </form>
    </p>

    {{-- 提携先管理フッター --}}
    {include file='../_common/_br_footer.tpl'}
    {{-- /提携先管理フッター --}}
{/strip}