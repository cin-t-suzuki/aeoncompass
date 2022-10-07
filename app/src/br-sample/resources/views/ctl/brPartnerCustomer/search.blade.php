
@extends('ctl.common.brPartnerCustomerBaseToBeRenamed')

@section('title', 'パートナー精算一覧')


@section('headScript')
    @include('ctl.brPartnerCustomer._css')
@endsection

@section('footerScript')
    @include('ctl.brPartnerCustomer._js')
@endsection

@section('content')
<hr class="contents-margin" />

<h1>
    パートナー＆アフィリエイト 精算先一覧
</h1>
<p>
    この画面はパートナーおよびアフィリエイトの精算先の登録と<br>精算先に紐づく提携先コードやアフィリエイトコード毎の情報である「サイト」を登録する画面です。<br>サイトの登録時にそのサイトに紐づける提携先コードまたはアフィリエイトコードを指定することで<br>精算先と提携先コードまたはアフィリエイトコードが紐付された状態になります。
</p>

<div style="text-align:left;">

    {{-- 検索フォーム --}}
    @include('ctl.brPartnerCustomer._form')

    <hr class="contents-margin" />

    {{ Form::open(['route' => 'brpartnercustomer.edit', 'method' => 'post']) }}
        <small>
            <input type="submit" value="新規登録">

            @foreach ($search_params as $key => $value)
                @if ($key != 'customer_id')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                @endif
            @endforeach
        </small>
    {{ Form::close() }}
</div>

{{-- 一覧表示 --}}
<table class="br-detail-list">
    <tr>
        <th>精算先ID</th>
        <th>精算先</th>
        <th>通知方法<br />連絡先</th>
        <th>精算パターン</th>
        <th>対象サイト</th>
        <th></th>
    </tr>
    @foreach ($customers as $customer)
        <tr>
            <td class="charge">{{ $customer->customer_id }}</td>
            <td>
                {{ $customer->customer_nm }}
                @if (!empty($customer->person_post))
                    <br />{{ $customer->person_post }}
                @endif
                @if (!empty($customer->person_nm))
                    <br />{{ $customer->person_nm }} 様
                @endif
            </td>
            <td>
                通知方法：
                @if ($customer->mail_send == '1')
                    メールで通知する
                @else
                    郵送（手動印刷）
                @endif
                <br />
                tel:{{ $customer->tel }} fax:{{ $customer->fax }}<br />

                email:
                @foreach (explode(',', $customer->email_decrypt) as $email)
                    {{ $email }}<br />
                @endforeach
            </td>
            <td>
                締め日：{{ $customer->billpay_day }}日<br />
                @if ($customer->billpay_required_month == '000000000000')
                    指定なし
                @elseif ($customer->billpay_required_month == '111111111111')
                    必須月：毎月
                @else
                    @for ($m = 1; $m <= 12; $m++) {{-- TODO: 移植元は 4月はじまり、合わせたほうがよい？ --}}
                        @if ($customer->billpay_required_month[$m - 1] == '1')
                            {{ $m . "月 " }}
                        @endif
                    @endfor
                @endif
                <br />
                最低金額：{{ number_format($customer->billpay_charge_min) }}
            </td>

            <td>
                @if (empty($customer->site_cd))
                    対象サイトを設定してください。
                    {{ Form::open(['route' => 'brpartnersite.search', 'method' => 'post']) }}
                        <input type="submit" value=" 設定 ">
                        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}" />

                        @foreach ($search_params as $key => $value)
                            @if ($key != 'customer_id' && $key != 'customer_off')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                            @endif
                        @endforeach
                    {{ Form::close() }}
                @else
                    @if (!empty($customer->partner_cd))
                        パートナー: {{ $customer->site_nm }}（{{ $customer->partner_cd }}）
                    @elseif (!empty($customer->affiliate_cd))
                        アフィリエイト: {{ $customer->site_nm }}（{{ $customer->affiliate_cd }}）
                    @endif
                    <br >
                    @if ($customer->sales_cnt + $customer->stock_cnt > 1)
                        その他サイト {{ $customer->sales_cnt + $customer->stock_cnt - 1 }}件<br />
              @endif
                    {{ Form::open(['route' => 'brpartnersite.search', 'method' => 'post']) }}
                        <input type="submit" value=" サイト表示 ">
                        <input type="hidden" name="customer_id"      value="{{ $customer->customer_id }}" />
                        @foreach ($search_params as $key => $value)
                            @if ($key != 'customer_id')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                            @endif
                        @endforeach
                    {{ Form::close() }}
                @endif
            </td>
            <td style="text-align:center;">
                {{ Form::open(['route' => 'brpartnercustomer.edit', 'method' => 'post']) }}
                    <input type="submit" value=" 表示 ">
                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}" />
                    @foreach ($search_params as $key => $value)
                        @if ($key != 'customer_id')
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                        @endif
                    @endforeach
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
</table>

<hr class="contents-margin" />
@endsection