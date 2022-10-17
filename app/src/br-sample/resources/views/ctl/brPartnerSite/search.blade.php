{{-- 提携先管理ヘッダー・フッター --}}
@extends('ctl.common.basekarioki')

@section('title', 'パートナー精算サイト一覧')

@section('headScript')
    @include('ctl.brPartnerSite._css')
@endsection

@section('footerScript')
    @include('ctl.brPartnerSite._js')
@endsection

@section('content')
    <hr class="contents-margin" />

    <div style="text-align:left;">

        {{-- 検索フォーム --}}
        @include('ctl.brPartnerSite._form')

        <hr class="contents-margin" />

        {{-- 新規登録 --}}
        {{-- TODO: Form Facades --}}
        <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/edit/" method="POST">
            <small>
                <input type="submit" value="対象サイトの新規登録">
                {{-- {foreach from=$v->assign->search_params (TODO: ) item=value key=key}
                    <input type="hidden" name="{$key}" value="{$value}" />
                {/foreach} --}}
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
        @foreach ($sites as $site)
            <tr>
                <td>{{ $site->site_cd }}</td>
                <td>
                    {{ $site->site_nm }}
                    @if (!empty($site->person_post))
                        <br />{{ $site->person_post }}
                    @endif
                    @if (!empty($site->person_nm))
                        <br />{{ $site->person_nm }} 様
                    @endif
                </td>
                <td>
                    通知方法：
                    @if ($site->mail_send == 1)
                        メールで通知する
                    @else
                        通知しない
                    @endif
                    <br />
                    email:
                    {{-- TODO: メール復号したら表示する --}}
                    {{-- @foreach (explode(',', $customer->email_decrypt) as $email)
                        {{ $email }}<br />
                    @endforeach --}}
                </td>
                <td>
                    @if (!empty($site->partner_cd))
                        パートナー<br />
                        {{ $site->partner_nm }}
                        （{{ $site->partner_cd }}）
                    @endif
                    @if (!empty($site->affiliate_cd))
                        アフィリエイト<br />
                        {{ $site->affiliate_nm }}
                        （{{ $site->affiliate_cd }}）
                    @endif
                </td>
                <td>
                    @if (empty($site->sales_customer_id) and empty($site->stock_customer_id))
                        @if (!empty($site->partner_cd))
                            料率タイプを設定してください。
                        @endif
                        @if (!empty($site->affiliate_cd))
                            指定なし
                        @endif
                    @endif
                    @if (!empty($site->stock_customer_id))
                        {{ $site->stock_customer_nm }}
                        （{{ $site->stock_customer_id }}）
                        {{-- TODO: Form Facades --}}
                        <form action="{$v->env.path_base_module}/brpartnercustomer/edit/" method="post">
                            <input type="submit" value=" 精算先表示 ">
                            <input type="hidden" name="customer_id" value="{{ $site->sales_customer_id }}" />
                            {{-- {foreach from=$v->assign->search_params (TODO: ) item=value key=key}
                                {if ($key!= 'customer_id')}
                                    <input type="hidden" name="{$key}" value="{$value}" />
                                {/if}
                            {/foreach} --}}
                        </form>
                    @endif
                    @if (!empty($site->sales_customer_id))
                        @if (!empty($site->stock_customer_id))
                            <hr />
                        @endif
                        {{ $site->sales_customer_nm }}
                        （{{ $site->sales_customer_id }}）
                        {{-- TODO: Form Facades --}}
                        <form action="{$v->env.path_base_module}/brpartnercustomer/edit/" method="post">
                            <input type="submit" value=" 精算先表示 ">
                            <input type="hidden" name="customer_id" value="{{ $site->sales_customer_id }}" />
                            {{-- {foreach from=$v->assign->search_params (TODO: ) item=value key=key}
                                {if ($key!= 'customer_id')}
                                    <input type="hidden" name="{$key}" value="{$value}" />
                                {/if}
                            {/foreach} --}}
                        </form>
                    @endif
                </td>
                <td style="text-align:center;">
                    {{-- TODO: Form Facades --}}
                    <form action="{$v->env.path_base_module}/brpartnersite/edit/" method="post">
                        <input type="submit" value=" 表示・編集 ">
                        <input type="hidden" name="site_cd" value="{{ $site->site_cd }}" />
                        {{-- {foreach from=$v->assign->search_params (TODO: ) item=value key=key}
                            <input type="hidden" name="{$key}" value="{$value}" />
                        {/foreach} --}}
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <hr class="contents-margin" />

    {{-- 請求先一覧へ --}}
    {{-- TODO: Form Facades --}}
    <form action="{$v->env.source_path}{$v->env.module}/brpartnercustomer/search/" method="POST">
        <small>
            {{-- {foreach from=$v->assign->search_params (TODO: ) item=value key=key}
                <input type="hidden" name="{$key}" value="{$value}" />
            {/foreach} --}}

            <input type="submit" value="請求先一覧へ">
        </small>
    </form>

    <hr class="contents-margin" />

@endsection
