@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td bgcolor="#EEFFEE">施設コード</td>
        <td>
            {{ strip_tags($hotel['hotel_cd']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">施設区分</td>
        <td>
            @if ($hotel['hotel_category'] == 'a')
                カプセルホテル
            @elseif($hotel['hotel_category'] == 'b')
                ビジネスホテル
            @elseif($hotel['hotel_category'] == 'c')
                シティホテル
            @elseif($hotel['hotel_category'] == 'j')
                旅館
            @endif
            <br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">施設名称</td>
        <td>
            {{ strip_tags($hotel['hotel_nm']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">施設名称かな</td>
        <td>
            {{ strip_tags($hotel['hotel_kn']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">旧施設名称</td>
        <td>
            {{ strip_tags($hotel['hotel_old_nm']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">郵便番号</td>
        <td>
            {{ strip_tags($hotel['postal_cd']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">都道府県</td>
        <td>
            @if (isset($a_mast_pref['pref_nm']))
                {{ strip_tags($a_mast_pref['pref_nm']) }}
            @endif
            @if (isset($a_mast_city['city_nm']))
                {{ strip_tags($a_mast_city['city_nm']) }}
            @endif
            @if (isset($a_mast_ward['ward_nm']))
                {{ strip_tags($a_mast_ward['ward_nm']) }}
            @endif
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">住所</td>
        <td>
            {{ strip_tags($hotel['address']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">電話番号</td>
        <td>
            {{ strip_tags($hotel['tel']) }}<br>
        </td>

    </tr>

    <tr>
        <td bgcolor="#EEFFEE">ＦＡＸ番号</td>
        <td>
            {{ strip_tags($hotel['fax']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">保有部屋数</td>
        <td>
            {{ strip_tags($hotel['room_count']) }} 室<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">チェックイン時刻</td>
        <td>
            {{ strip_tags($hotel['check_in']) }}
            ～
            @if ($service->is_empty($hotel['check_in_end']))
                指定無し
            @else
                {{ strip_tags($hotel['check_in_end']) }}<br>
            @endif
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">チェックイン時刻コメント</td>
        <td>
            <pre>{{ strip_tags($hotel['check_in_info']) }}</pre>
            <br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">チェックアウト時刻</td>
        <td>
            {{ strip_tags($hotel['check_out']) }}<br>
        </td>
    </tr>

    <tr>
        <td bgcolor="#EEFFEE">深夜受付</td>
        <td>
            @if ($hotel['midnight_status'] == 0)
                受け入れない
            @else
                受け入れる
            @endif
            <br>
        </td>
    </tr>

    <input type="hidden" name="target_cd" value="{{ strip_tags($hotel['hotel_cd']) }}">
    <!-- target_stock_type は施設情報更新では扱っていないがcreateでは使っている セットしている処理は施設管理(hotel_control) -->
    <input type="hidden" name="target_stock_type" value="{{ isset($target_stock_type) ? strip_tags($target_stock_type) : '' }}">
</table>
