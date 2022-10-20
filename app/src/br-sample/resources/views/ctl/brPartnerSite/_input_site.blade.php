<table class="br-detail-list">
    <tr>
        <th>精算サイトコード</th>
        <td>
            {{ $partner_site->site_cd }}
            <input type="hidden" name="partner_site[site_cd]" value="{{ strip_tags($partner_site->site_cd) }}" />
        </td>
    </tr>
    <tr>
        <th>精算サイト名称</th>
        <td><input type="text" name="partner_site[site_nm]" size="50" maxlength="50" value="{{ strip_tags($partner_site->site_nm) }}" /></td>
    </tr>
    <tr>
        <th>役職（部署名）</th>
        <td><input type="text" name="partner_site[person_post]" size="50" maxlength="50" value="{{ strip_tags($partner_site->person_post) }}"></td>
    </tr>
    <tr>
        <th>担当者</th>
        <td><input type="text" name="partner_site[person_nm]" size="20" maxlength="20" value="{{ strip_tags($partner_site->person_nm) }}" /></td>
    </tr>
    <tr>
        <th>E-Mail</th>
        <td><input type="text" name="partner_site[email]" size="50" maxlength="50" value="{{ strip_tags($partner_site->email_decrypt) }}" /></td>
    </tr>
    <tr>
        <th>通知方法</th>
        <td>
            <label for="mail_send_1">
                <input type="radio" id="mail_send_1" name="partner_site[mail_send]" value="0" {{ (string)$partner_site->mail_send === '0' ? 'checked' : '' }} />
                通知しない
            </label>
            <label for="mail_send_0">
                <input type="radio" id="mail_send_0" name="partner_site[mail_send]" value="1" {{ (string)$partner_site->mail_send === "1" ? 'checked' : '' }} />
                メールで通知する
            </label>
            <br />※ 精算先「株式会社日本旅行（1）」の場合のみ有効
        </td>
    </tr>
    <tr>
        <th>パートナーコード</th>
        <td>
            <input type="text" size="15" maxlength="20" name="partner_site[partner_cd]" value="{{ strip_tags($partner_site->partner_cd) }}" />
            {{ $partner_site->partner_nm }}
        </td>
    </tr>
    <tr>
        <th>アフィリエイトコード</th>
        <td>
            <input type="text" size="15" maxlength="20" name="partner_site[affiliate_cd]" value="{{ strip_tags($partner_site->affiliate_cd) }}" />
            {{ $partner_site->affiliate_nm }}
        </td>
    </tr>
    <tr>
        <th>料率タイプ</th>
        <td>
            <select size="1" name="partner_site_rate[rate_type]">
                @for ($i = 0; $i < 11; $i++)
                    <option value="{{ $i }}" {{ ($partner_site_rate->select_rate_index == $i) ? 'selected' : '' }}>
                        @if ($i == 1)
                            1:特別提携    0% ベストリザーブオリジナルサイト・光通信等
                        @elseif ($i == 2)
                            2:通常提携    1%
                        @elseif ($i == 3)
                            3:特別提携    2% アークスリー等
                        @elseif ($i == 4)
                            4:日本旅行ビジネストラベルマネージメント（BTM）
                        @elseif ($i == 5)
                            5:Yahoo!トラベル
                        @elseif ($i == 6)
                            6:日本旅行    2%
                        @elseif ($i == 7)
                            7:日本旅行    3% MSD等
                        @elseif ($i == 8)
                            8:日本旅行    4% JRおでかけネット
                        @elseif ($i == 9)
                            9:日本旅行    リロクラブ
                        @elseif ($i == 10)
                            10:GBTNTA 1%(在庫手数料0%)
                        @else
                            0:指定なし
                        @endif
                    </option>
                @endfor
            </select>
            <div>アフィリエイトでの提携で手数料が発生しない場合は「0:指定なし」を選んでください。</div>
            <div>パートナーでの提携で手数料が発生しない場合は、「1:特別提携 0% ベストリザーブオリジナルサイト・光通信等」を選んでください。</div>
        </td>
    </tr>
    <tr>
        <th>料率開始年月日（直近）</th>
        <td>
            <input type="text" size="15" maxlength="10" name="partner_site_rate[accept_s_ymd]" value="{{ $partner_site_rate->accept_s_ymd }}" />
             ～（例：yyyy-mm-dd）
        </td>
    </tr>
    <tr>
        <th>精算先ID<br /> 手数料タイプ「1:販売」用</th>
        <td>
            <input type="text" size="5" maxlength="20" name="partner_customer_site[customer_id]" value="{{ strip_tags($partner_customer_site->customer_id) }}" />
            {{ $partner_customer_site->customer_nm }}
            <br />※ 料率タイプが「6～9」のNTA用の場合は、「1:販売」の精算が発生しませんので登録できません。
        </td>
    </tr>
</table>
