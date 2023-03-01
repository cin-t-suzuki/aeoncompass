<table border="1" cellspacing="0" cellpadding="4">
  <tr><td bgcolor="#eeffee" colspan="3">基本</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">請求連番</td><td>{{$customer['customer_id']}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">精算先名称</td><td>{{strip_tags($customer['customer_nm'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">請求書宛名</td><td>{{strip_tags($customer['section_nm'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">担当者役職・部署名</td><td>{{strip_tags($customer['person_post'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">担当者</td><td>{{strip_tags($customer['person_nm'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">郵便番号・都道府県</td><td>〒{{strip_tags($customer['postal_cd'])}} @if ($pref_nm != '未選択'){{$pref_nm}}@endif</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">住所</td><td>{{strip_tags($customer['address'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">電話番号</td><td>{{strip_tags($customer['tel'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">ファックス番号</td><td>{{strip_tags($customer['fax'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">E-Mail</td><td>{{strip_tags($customer['email'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">請求方法</td><td>@if ($customer['bill_way'] === "0")振込 @elseif ($customer['bill_way'] === "1")引落@endif</td></tr>
  <tr><td bgcolor="#eeffee" colspan="3">請求銀行</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">振込銀行と支店</td><td>{{strip_tags($customer['bill_bank_nm'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">振込口座</td><td>{{strip_tags($customer['bill_bank_account_no'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="3">引落銀行</td></tr>
  {{--  ?? null追記 --}}
  <tr><td bgcolor="#eeffee" colspan="2">銀行コード</td><td>{{strip_tags($customer['factoring_bank_cd'])}} : {{strip_tags($factoring_bank['bank_nm'] ?? null)}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支店コード</td><td>{{strip_tags($customer['factoring_bank_branch_cd'])}} : {{strip_tags($factoring_bank_branch['bank_branch_nm'] ?? null)}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座種別</td><td>
          @if ($customer['factoring_bank_account_type'] == 1)普通
      @elseif ($customer['factoring_bank_account_type'] == 2)当座
      @elseif ($customer['factoring_bank_account_type'] == 4)貯蓄
      @elseif ($customer['factoring_bank_account_type'] == 9)その他
          @endif</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座番号</td><td>{{strip_tags($customer['factoring_bank_account_no'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座名義</td><td>{{strip_tags($customer['factoring_bank_account_kn'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落顧客番号</td><td>{{strip_tags($customer['factoring_cd'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="3">支払銀行</td></tr>
  {{--  ?? null追記 --}}
  <tr><td bgcolor="#eeffee" colspan="2">銀行コード</td><td>{{strip_tags($customer['payment_bank_cd'] ?? null)}} : {{strip_tags($bank['bank_nm'] ?? null)}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支店コード</td><td>{{strip_tags($customer['payment_bank_branch_cd'] ?? null)}} : {{strip_tags($bank_branch['bank_branch_nm'] ?? null)}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支払口座種別</td><td>
          @if ($customer['payment_bank_account_type'] == 1)普通
      @elseif ($customer['payment_bank_account_type'] == 2)当座
      @elseif ($customer['payment_bank_account_type'] == 4)貯蓄
      @elseif ($customer['payment_bank_account_type'] == 9)その他
          @endif</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支払口座番号</td><td>{{strip_tags($customer['payment_bank_account_no'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支払口座名義</td><td>{{strip_tags($customer['payment_bank_account_kn'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" COLSPAN="3">その他</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">請求必須月</td>
    <td nowrap>
      @if ($customer['bill_month04'] == 1)04月@endif
      @if ($customer['bill_month05'] == 1)05月@endif
      @if ($customer['bill_month06'] == 1)06月@endif
      @if ($customer['bill_month07'] == 1)07月@endif
      @if ($customer['bill_month08'] == 1)08月@endif
      @if ($customer['bill_month09'] == 1)09月@endif
      @if ($customer['bill_month10'] == 1)10月@endif
      @if ($customer['bill_month11'] == 1)11月@endif
      @if ($customer['bill_month12'] == 1)12月@endif
      @if ($customer['bill_month01'] == 1)01月@endif
      @if ($customer['bill_month02'] == 1)02月@endif
      @if ($customer['bill_month03'] == 1)03月@endif
    <br /></td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="2">支払必須月</td>
    <td nowrap>
      @if ($customer['payment_month04'] == 1)04月@endif
      @if ($customer['payment_month05'] == 1)05月@endif
      @if ($customer['payment_month06'] == 1)06月@endif
      @if ($customer['payment_month07'] == 1)07月@endif
      @if ($customer['payment_month08'] == 1)08月@endif
      @if ($customer['payment_month09'] == 1)09月@endif
      @if ($customer['payment_month10'] == 1)10月@endif
      @if ($customer['payment_month11'] == 1)11月@endif
      @if ($customer['payment_month12'] == 1)12月@endif
      @if ($customer['payment_month01'] == 1)01月@endif
      @if ($customer['payment_month02'] == 1)02月@endif
      @if ($customer['payment_month03'] == 1)03月@endif
    <br /></td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="2">請求最低金額</td><td>@if ($service->is_empty($customer['bill_charge_min']))@else{{number_format($customer['bill_charge_min'])}}@endif<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支払最低金額</td><td>@if ($service->is_empty($customer['payment_charge_min']))@else{{number_format($customer['payment_charge_min'])}}@endif<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">振込予定日</td><td>
  請求書発行月の
  @if ($customer['bill_add_month']== 0)当月@endif
  @if ($customer['bill_add_month']== 1)翌月@endif
  @if ($customer['bill_add_month']== 2)翌々月@endif
  @if ($customer['bill_day']== 99)末@else{{$customer['bill_day']}}@endif 日

  <br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="3">発送方法</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">請求書</td><td>
      @if ($customer['bill_send'] === "1")印刷（郵送）
      @elseif ($customer['bill_send'] === "2")FAX
      @elseif ($customer['bill_send'] === "3")両方（印刷・FAX）
      @elseif (($customer['bill_send'] ?? "0") === "0")不要　{{-- nvl→??へ書き換えであっているか？ --}}
      @endif
    </td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="2">支払通知書</td><td>
      @if ($customer['payment_send'] === "1")印刷（郵送）
      @elseif ($customer['payment_send'] === "2")FAX
      @elseif ($customer['payment_send'] === "3")両方（印刷・FAX）
      @elseif (($customer['payment_send'] ?? "0") === "0")不要　{{-- nvl→??へ書き換えであっているか？ --}}
      @endif
    </td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落通知書</td><td>
      @if ($customer['factoring_send'] === "1")印刷（郵送）
      @elseif ($customer['factoring_send'] === "2")FAX
      @elseif ($customer['factoring_send'] === "3")両方（印刷・FAX）
      @elseif (($customer['factoring_send'] ?? "0") === "0")不要　{{-- nvl→??へ書き換えであっているか？ --}}
      @endif
    </td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="3">FAX通知先</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">通知先</td><td>
      {{-- nvl→??へ書き換えであっているか？ --}}
      @if (($customer['fax_recipient_cd'] ?? "1")  === "1")精算先（上付なし）
      @elseif ($customer['fax_recipient_cd'] === "2")任意宛先（上付あり <a href="{$v->env.source_path}{$v->env.module}/brcustomer/sendletter/" target="_blank">サンプル</a>）
      @endif
    </td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="3">FAX通知任意宛先</td></tr>
  {{-- 以下すべて、nvl→??へ書き換えであっているか？ --}}
  <tr><td bgcolor="#eeffee" colspan="2">施設・会社名</td><td @if (($customer['fax_recipient_cd'] ?? "1")  === "1") style="color:darkgray"@endif>{{strip_tags($customer['optional_nm'])}}<br /></td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="2">役職（部署名）</td><td @if (($customer['fax_recipient_cd'] ?? "1")  === "1") style="color:darkgray"@endif>{{strip_tags($customer['optional_section_nm'])}}<br /></td>
  <tr><td bgcolor="#eeffee" colspan="2">担当者</td><td @if (($customer['fax_recipient_cd'] ?? "1")  === "1") style="color:darkgray"@endif>{{strip_tags($customer['optional_person_nm'])}}<br /></td>
  </tr>
  <tr><td bgcolor="#eeffee" colspan="2">ファックス番号</td><td @if (($customer['fax_recipient_cd'] ?? "1")  === "1") style="color:darkgray"@endif>{{strip_tags($customer['optional_fax'])}}<br /></td>
  </tr>
  </table>
