{{-- MEMO: 移植元 public\app\ctl\views\brhotel\_info_state_form.tpl --}}

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">施設コード</td>
        <td>
            {{ strip_tags($target_cd) }}<br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">通知媒体</td>
        <td>
            {{-- TODO: $loop 変数を使って書く --}}
            @php $device_first = true @endphp
            @foreach ($notify_device as $value)
                @if ($device_first != true)
                    +
                @endif
                @if ($value == 1)
                    ファックス
                    @php $device_first = false @endphp
                @elseif ($value == 2)
                    電子メール
                    @php $device_first = false @endphp
                @elseif ($value == 4)
                    オペレータ連絡
                    @php $device_first = false @endphp
                @elseif ($value == 8)
                    リンカーン
                    @php $device_first = false @endphp
                @endif
            @endforeach
            <br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">予約情報プッシュ通知（ねっぱん）</td>
        <td>
            @if ($hotel_notify->neppan_status === '1')
                通知する
            @elseif ($hotel_notify->neppan_status === '0')
                通知しない
            @else
                通知しない(※連動時に「通知する」に自動切替)
            @endif
            <br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">通知ステータス</td>
        <td>
            @if ($hotel_notify->notify_status == 0)
                通知しない
            @elseif ($hotel_notify->notify_status == 1)
                通知する
            @endif
            <br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">通知電子メールアドレス</td>
        <td>
            {{ strip_tags($hotel_notify->notify_email) }}<br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">通知ファックス番号</td>
        <td>
            {{ strip_tags($hotel_notify->notify_fax) }}<br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">FAXPR</td>
        <td>
            @if ($hotel_notify->faxpr_status == 0)
                表示しない
            @elseif ($hotel_notify->faxpr_status == 1)
                表示する
            @endif
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">仕入タイプ</td>
        <td>
            @if ($hotel_control->stock_type == 0)
                受託販売
            @elseif ($hotel_control->stock_type == 1)
                買取販売
            @elseif ($hotel_control->stock_type == 2)
                一括受託（東横イン）
            @elseif ($hotel_control->stock_type == 3)
                特定施設(三普)
            @endif
            <br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">送客実績送信</td>
        <td>
            @if ($hotel_control->checksheet_send == 0)
                送信しない
            @elseif ($hotel_control->checksheet_send == 1)
                送信する
            @endif
            <br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">金額切り捨て桁</td>
        <td>
            {{-- TODO: is_empty -> is_null に書き換えた。問題ないか確認 --}}
            @if ($hotel_control->charge_round == 1 || is_null($hotel_control->charge_round))
                1の位で丸める
            @elseif ($hotel_control->charge_round == 10)
                10の位で丸める
            @elseif ($hotel_control->charge_round == 100)
                100の位で丸める
            @endif
            <br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">連泊限界数</td>
        <td>
            {{ strip_tags($hotel_control->stay_cap) }}<br />
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">利用方法</td>
        <td>
            @if ($hotel_control->management_status == 1)
                ファックス管理 <br />
            @elseif ($hotel_control->management_status == 2)
                インターネット管理<br />
            @elseif ($hotel_control->management_status == 3)
                ファックス管理＋インターネット管理<br />
            @endif
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">管理システムバージョン※</td>
        <td>
            {{-- TODO: magic number --}}
            @if (in_array(1, $version) and in_array(2, $version))
                旧インターフェース / 新インターフェース
            @elseif (in_array(1, $version))
                旧インターフェース
            @elseif (in_array(2, $version))
                新インターフェース
            @endif
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">日本旅行在庫連携</td>
        <td>
            @if ($hotel_control->akafu_status == 1)
                利用する
            @else
                利用しない
            @endif
        </td>
    </tr>
    <input type="hidden" name="target_cd" value="{{ strip_tags($target_cd) }}">
</table>
