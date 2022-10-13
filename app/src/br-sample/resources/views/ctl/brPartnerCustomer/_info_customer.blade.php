{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnercustomer\_info_customer.tpl --}}

<table class="br-detail-list">
    <tr>
        <th>精算先ID</th>
        <td>{{ $partner_customer->customer_id }}</td>
    </tr>
    <tr>
        <th>精算先名称</th>
        <td>{{ strip_tags($partner_customer->customer_nm) }}</td>
    </tr>
    <tr>
        <th>役職（部署名）</th>
        <td>{{ strip_tags($partner_customer->person_post) }}</td>
    </tr>
    <tr>
        <th>担当者</th>
        <td>{{ strip_tags($partner_customer->person_nm) }}</td>
    </tr>
    <tr>
        <th>郵便番号・都道府県</th>
        <td>
            〒{{ strip_tags($partner_customer->postal_cd) }}
            @foreach ($mast_pref['values'] as $value) {{-- HACK: naming --}}
                @if ($partner_customer->pref_id == $value['pref_id'])
                    {{ $value['pref_nm'] }}
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>住所</th>
        <td>{{ strip_tags($partner_customer->address) }}</td>
    </tr>
    <tr>
        <th>電話番号</th>
        <td>{{ strip_tags($partner_customer->tel) }}</td>
    </tr>
    <tr>
        <th>ファックス番号</th>
        <td>{{ strip_tags($partner_customer->fax) }}</td>
    </tr>
    <tr>
        <th>E-Mail</th>
        <td>{{ strip_tags($partner_customer->email_decrypt) }}</td>
    </tr>
    <tr>
        <th>通知方法</th>
        <td>
            @if ((string)$partner_customer->mail_send === "0")
                郵送（手動印刷）
            @endif
            @if ((string)$partner_customer->mail_send === "1")
                メールで通知する
            @endif
        </td>
    </tr>
    <tr>
        <th>手数料キャンセル対象状態</th>
        <td>
            @if ((string)$partner_customer->cancel_status === "0")
                予約のみ（キャンセル料金精算対象外）
            @endif
            @if ((string)$partner_customer->cancel_status === "1")
                キャンセル含む（キャンセル料金精算対象）
            @endif
        </td>
    </tr>
    <tr>
        <th>明細書の通知有無</th>
        <td>
            @if ((string)$partner_customer->detail_status === "0")
                通知不用
            @endif
            @if ((string)$partner_customer->detail_status === "1")
                通知必要
            @endif
            <br />※ 精算書確認画面下部にあります「予約明細ダウンロード」からCSVファイルをダウンロードして必要に応じて加工して通知してください。
        </td>
    </tr>
    <tr>
        <th>精算日</th>
        <td>{{ $partner_customer->billpay_day }}日</td>
    </tr>
    <tr>
        <th>精算必須月</th>
        <td nowrap>
            {{-- TODO: 相談、一覧画面と合わせて、「毎月」「指定なし」とやったほうがよい？ --}}
            @for ($m = 1; $m <= 12; $m++) {{-- TODO: 移植元は 4月はじまり、合わせたほうがよい？ --}}
                @if ($partner_customer->billpay_required_month[$m - 1] === '1')
                    {{ $m . "月 " }}
                @endif
            @endfor
        </td>
    </tr>
    <tr>
        <th>精算最低金額</th>
        <td>{{ number_format($partner_customer->billpay_charge_min) }}円</td>
    </tr>
</table>
