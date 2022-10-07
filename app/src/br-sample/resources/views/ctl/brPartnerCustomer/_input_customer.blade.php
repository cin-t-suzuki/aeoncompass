{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnercustomer\_input_customer.tpl --}}

<table class="br-detail-list">
    <tr>
        <th>精算先ID</th>
        <td>{{ $partner_customer['customer_id'] }}
            <input type="hidden" name="partner_customer[customer_id]" value="{{ strip_tags($partner_customer['customer_id']) }}">
        </td>
    </tr>
    <tr>
        <th>精算先名称</th>
        <td><input type="text" name="partner_customer[customer_nm]" SIZE="50" MAXLENGTH="150" value="{{ strip_tags($partner_customer['customer_nm']) }}"></td>
    </tr>
    <tr>
        <th>役職（部署名）</th>
        <td><input type="text" name="partner_customer[person_post]" SIZE="50" MAXLENGTH="50" value="{{ strip_tags($partner_customer['person_post']) }}"></td>
    </tr>
    <tr>
        <th>担当者</th>
        <td><input type="text" name="partner_customer[person_nm]" SIZE="20" MAXLENGTH="20" value="{{ strip_tags($partner_customer['person_nm']) }}"></td>
    </tr>
    <tr>
        <th>郵便番号・都道府県</th>
        <td>〒<input type="text" name="partner_customer[postal_cd]" SIZE="9" MAXLENGTH="8" value="{{ strip_tags($partner_customer['postal_cd']) }}">
            <select size="1" name="partner_customer[pref_id]">
                @foreach ($mast_pref['values'] as $value) {{-- HACK: naming --}}
                    <option value="{{ $value['pref_id'] }}" {{ $partner_customer['pref_id'] == $value['pref_id'] ? 'selected' : '' }}>
                        {{ $value['pref_nm'] }}
                    </option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <th>住所</th>
        <td><input type="text" name="partner_customer[address]" SIZE="50" MAXLENGTH="200" value="{{ strip_tags($partner_customer['address']) }}"></td>
    </tr>
    <tr>
        <th>電話番号</th>
        <td><input type="text" name="partner_customer[tel]" SIZE="15" MAXLENGTH="15" value="{{ strip_tags($partner_customer['tel']) }}"></td>
    </tr>
    <tr>
        <th>ファックス番号</th>
        <td><input type="text" name="partner_customer[fax]" SIZE="15" MAXLENGTH="15" value="{{ strip_tags($partner_customer['fax']) }}"></td>
    </tr>
    <tr>
        <th>E-Mail</th>
        <td><input type="text" name="partner_customer[email]" SIZE="50" MAXLENGTH="50" value="{{ strip_tags($partner_customer['email_decrypt']) }}"></td>
    </tr>
    <tr>
        <th>通知方法</th>
        <td>
            <label for="mail_send_1">
                <input type="radio" id="mail_send_1" name="partner_customer[mail_send]" value="0" {{ ($partner_customer['mail_send'] ?? "0") === "0" ? 'checked' : '' }} />
                郵送（手動印刷）
            </label>
            <label for="mail_send_0">
                <input type="radio" id="mail_send_0" name="partner_customer[mail_send]" value="1" {{ $partner_customer['mail_send'] === "1" ? 'checked' : '' }} />
                メールで通知する
            </label>
        </td>
    </tr>
    <tr>
        <th>手数料キャンセル対象状態</th>
        <td>
            <label for="cancel_status_0">
                <input type="radio" id="cancel_status_0" name="partner_customer[cancel_status]" value="0" {{ ($partner_customer['cancel_status'] ?? "0") === "0" ? 'checked' : '' }} />
                予約のみ（キャンセル料金精算対象外）
            </label>
            <label for="cancel_status_1">
                <input type="radio" id="cancel_status_1" name="partner_customer[cancel_status]" value="1" {{ $partner_customer['cancel_status'] === "1" ? 'checked' : '' }} />
                キャンセル含む（キャンセル料金精算対象）
            </label>
        </td>
    </tr>
    <tr>
        <th>明細書の通知有無</th>
        <td>
            <label for="detail_status_0">
                <input type="radio" id="detail_status_0" name="partner_customer[detail_status]" value="0" {{ ($partner_customer['detail_status'] ?? "0") === "0" ? 'checked' : '' }} />
                通知不用
            </label>
            <label for="detail_status_1">
                <input type="radio" id="detail_status_1" name="partner_customer[detail_status]" value="1" {{ $partner_customer['detail_status'] === "1" ? 'checked' : '' }} />
                通知必要
            </label>
            <br />※ 精算書確認画面下部にあります「予約明細ダウンロード」からCSVファイルをダウンロードして必要に応じて加工して通知してください。
        </td>
    </tr>
    <tr>
        <th>精算日</th>
        <td>
            <select size="1" name="partner_customer[billpay_day]">
            @for ($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}" {{ $d == ($partner_customer['billpay_day'] ?? "8") ? 'selected="selected"' : '' }}>
                    {{ $d }}日
                </option>
            @endfor
            </select>
        </td>
    </tr>
    <tr>
        <th>精算必須月</th>
        <td nowrap>
            {{-- TODO: 4月はじまり必要？ --}}
            @for ($m = 1; $m <= 12; $m++)
                <label for="billpay_month{{ sprintf("%02d", $m) }}">
                    <input type="checkbox" name="partner_customer[billpay_month{{ sprintf("%02d", $m) }}]" id="billpay_month{{ sprintf("%02d", $m) }}" value="1" {{ $partner_customer['billpay_required_month'][$m - 1] == '1' ? 'checked' : '' }}>
                    {{ $m }}月
                </label>
            @endfor
        </td>
    </tr>
    <tr>
        <th>精算最低金額</th>
        <td>
            <input type="text" name="partner_customer[billpay_charge_min]" SIZE="5" MAXLENGTH="5" value="{{ strip_tags($partner_customer['billpay_charge_min']) }}" class="charge" /> 円
            <span>空欄にすると精算必須月欄指定月のみ処理されます。</span>
        </td>
    </tr>
</table>
{{-- 消費税単位 --}}
<input type="hidden" name="partner_customer[tax_unit]" value="{{ $partner_customer['custmer_id'] == "1" ? 2 : 1 }}" />{{-- 1:料率毎（一般） 2:在庫種類単位（NTA） --}}{{-- TODO: マジックナンバーを定数化したい --}}{{-- TODO: 要確認 custmer_id --}}
