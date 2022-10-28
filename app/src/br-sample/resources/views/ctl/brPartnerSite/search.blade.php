{{-- 提携先管理ヘッダー・フッター --}}
@extends('ctl.common.base2')

@inject('service', 'App\Http\Controllers\ctl\BrPartnerSiteController')

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
        {{ Form::open(['route' => 'ctl.brPartnerSite.edit', 'method' => 'get']) }}
            <small>
                <input type="submit" value="対象サイトの新規登録">
                @foreach ($search_params as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                @endforeach
            </small>
        {{ Form::close() }}
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
                    @if (!$service->is_empty($site->person_post))
                        <br />{{ $site->person_post }}
                    @endif
                    @if (!$service->is_empty($site->person_nm))
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
                    @foreach (explode(',', $site->email_decrypt) as $email)
                        {{ $email }}<br />
                    @endforeach
                </td>
                <td>
                    @if (!$service->is_empty($site->partner_cd))
                        パートナー<br />
                        {{ $site->partner_nm }}
                        （{{ $site->partner_cd }}）
                    @endif
                    @if (!$service->is_empty($site->affiliate_cd))
                        アフィリエイト<br />
                        {{ $site->affiliate_nm }}
                        （{{ $site->affiliate_cd }}）
                    @endif
                </td>
                <td>
                    @if ($service->is_empty($site->sales_customer_id) and $service->is_empty($site->stock_customer_id))
                        @if (!$service->is_empty($site->partner_cd))
                            料率タイプを設定してください。
                        @endif
                        @if (!$service->is_empty($site->affiliate_cd))
                            指定なし
                        @endif
                    @endif
                    @if (!$service->is_empty($site->stock_customer_id))
                        {{ $site->stock_customer_nm }}
                        （{{ $site->stock_customer_id }}）
                        {{ Form::open(['route' => ['brpartnercustomer.edit', ['customer_id' => $site->sales_customer_id]], 'method' => 'get']) }}
                            <input type="submit" value=" 精算先表示 ">
                            <input type="hidden" name="customer_id" value="{{ $site->sales_customer_id }}" />
                            @foreach ($search_params as $key => $value)
                                @if ($key != 'customer_id')
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                                @endif
                            @endforeach
                        {{ Form::close() }}
                    @endif
                    @if (!$service->is_empty($site->sales_customer_id))
                        @if (!$service->is_empty($site->stock_customer_id))
                            <hr />
                        @endif
                        {{ $site->sales_customer_nm }}
                        （{{ $site->sales_customer_id }}）
                        {{ Form::open(['route' => ['brpartnercustomer.edit', ['customer_id' => $site->sales_customer_id]], 'method' => 'get']) }}
                            <input type="submit" value=" 精算先表示 ">
                            <input type="hidden" name="customer_id" value="{{ $site->sales_customer_id }}" />
                            @foreach ($search_params as $key => $value)
                                @if ($key != 'customer_id')
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                                @endif
                            @endforeach
                        {{ Form::close() }}
                    @endif
                </td>
                <td style="text-align:center;">
                    {{ Form::open(['route' => 'ctl.brPartnerSite.edit', 'method' => 'get']) }}
                        <input type="submit" value=" 表示・編集 ">
                        <input type="hidden" name="site_cd" value="{{ $site->site_cd }}" />
                        @foreach ($search_params as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                        @endforeach
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
    </table>
    <hr class="contents-margin" />

    {{-- 請求先一覧へ --}}
    {{ Form::open(['route' => 'brpartnercustomer.search', 'method' => 'get']) }}
        <small>
            @foreach ($search_params as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
            @endforeach

            <input type="submit" value="請求先一覧へ">
        </small>
    {{ Form::close() }}

    <hr class="contents-margin" />

@endsection
