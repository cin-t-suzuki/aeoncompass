@if (!empty($rates))
    <p>
        <div style="text-align:left;">
            ■ 手数料率の登録状態
            <table class="br-detail-list">
                <tr>
                    <th rowspan="2">開始年月日</th>
                    <th rowspan="2">手数料タイプ</th>
                    <th rowspan="2">1:一般ネット在庫</th>
                    <th colspan="3">連動在庫</th>
                    <th rowspan="2">5:東横イン在庫</th>
                    <th rowspan="2">精算先</th>
                </tr>
                <tr>
                    <th>2:通常</th>
                    <th>3:ヴィジュアル</th>
                    <th>4:プレミアム</th>
                </tr>
                @foreach ($rates as $rate)
                    <tr>
                        <td rowspan="2">{{ $rate->accept_s_ymd }}～</td>
                        <td>1:販売</td>
                        <td class="charge">
                            @if (empty($rate->sales_1_rate))
                                -
                            @else
                                {{ $rate->sales_1_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->sales_2_rate))
                                -
                            @else
                                {{ $rate->sales_2_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->sales_3_rate))
                                -
                            @else
                                {{ $rate->sales_3_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->sales_4_rate))
                                -
                            @else
                                {{ $rate->sales_4_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->sales_5_rate))
                                -
                            @else
                                {{ $rate->sales_5_rate }}%
                            @endif
                        </td>
                        <td>
                            @if (($loop->first))
                                @if (!empty($partner_site->sales_customer_id))
                                    {{ $partner_site->sales_customer_nm }}
                                    （{{ $partner_site->sales_customer_id }}）
                                @elseif (
                                        !empty($rate->sales_1_rate)
                                    or !empty($rate->sales_2_rate)
                                    or !empty($rate->sales_3_rate)
                                    or !empty($rate->sales_4_rate)
                                    or !empty($rate->sales_5_rate)
                                )
                                    -
                                @endif
                            @else
                                過去の精算先は実績からご確認ください。
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>2:在庫</td>
                        <td class="charge">
                            @if (empty($rate->stock_1_rate))
                                -
                            @else
                                {{ $rate->stock_1_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->stock_2_rate))
                                -
                            @else
                                {{ $rate->stock_2_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->stock_3_rate))
                                -
                            @else
                                {{ $rate->stock_3_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->stock_4_rate))
                                -
                            @else
                                {{ $rate->stock_4_rate }}%
                            @endif
                        </td>
                        <td class="charge">
                            @if (empty($rate->stock_5_rate))
                                -
                            @else
                                {{ $rate->stock_5_rate }}%
                            @endif
                        </td>
                        <td>
                            @if (!empty($partner_site->stock_customer_id))
                                {{ $partner_site->stock_customer_nm }}
                                （{{ $partner_site->stock_customer_id }}）
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </p>
@endif
