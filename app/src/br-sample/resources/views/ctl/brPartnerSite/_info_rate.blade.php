{if (!is_empty($v->assign->rate))}
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
                {foreach from=$v->assign->rate item=rate name=rates}
                    <tr>
                        <td rowspan="2">{$rate.accept_s_ymd}～</td>
                        <td>1:販売</td>
                        <td class="charge">
                            {if is_empty($rate.sales_1_rate)}
                                -
                            {else}
                                {$rate.sales_1_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.sales_2_rate)}
                                -
                            {else}
                                {$rate.sales_2_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.sales_3_rate)}
                                -
                            {else}
                                {$rate.sales_3_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.sales_4_rate)}
                                -
                            {else}
                                {$rate.sales_4_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.sales_5_rate)}
                                -
                            {else}
                                {$rate.sales_5_rate}%
                            {/if}
                        </td>
                        <td>
                            {if ($smarty.foreach.rates.first)}
                                {if !is_empty($v->assign->partner_site.sales_customer_id)}
                                    {$v->assign->partner_site.sales_customer_nm}
                                    （{$v->assign->partner_site.sales_customer_id}）
                                {elseif (
                                        !is_empty($rate.sales_1_rate)
                                    or !is_empty($rate.sales_2_rate)
                                    or !is_empty($rate.sales_3_rate)
                                    or !is_empty($rate.sales_4_rate)
                                    or !is_empty($rate.sales_5_rate)
                                )}
                                    -
                                {/if}
                            {else}
                                過去の精算先は実績からご確認ください。
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>2:在庫</td>
                        <td class="charge">
                            {if is_empty($rate.stock_1_rate)}
                                -
                            {else}
                                {$rate.stock_1_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.stock_2_rate)}
                                -
                            {else}
                                {$rate.stock_2_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.stock_3_rate)}
                                -
                            {else}
                                {$rate.stock_3_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.stock_4_rate)}
                                -
                            {else}
                                {$rate.stock_4_rate}%
                            {/if}
                        </td>
                        <td class="charge">
                            {if is_empty($rate.stock_5_rate)}
                                -
                            {else}
                                {$rate.stock_5_rate}%
                            {/if}
                        </td>
                        <td>
                            {if !is_empty($v->assign->partner_site.stock_customer_id)}
                                {$v->assign->partner_site.stock_customer_nm}
                                （{$v->assign->partner_site.stock_customer_id}）
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </p>
{/if}
