<hr size="1">

<h4>精算先担当者更新履歴</h4>
※施設管理画面側での更新履歴のみ保存されます。
    <table border="1" cellspacing="0" cellpadding="3">
      <tr>
        <td bgcolor="#EEFFEE">
        請求書宛名
        </td>
        <td bgcolor="#EEFFEE">
        担当者役職・部署名
        </td>
        <td bgcolor="#EEFFEE">
        担当者
        </td>
        <td bgcolor="#EEFFEE">
        電話番号
        </td>
        <td bgcolor="#EEFFEE">
        ファックス番号
        </td>
        <td bgcolor="#EEFFEE">
        電子メールアドレス
        </td>
        <td bgcolor="#EEFFEE">
        更新日時
        </td>
      </tr>

@foreach ($views->log_customer as $customer)
      <tr>
        <td nowrap>
          {{strip_tags($customer->section_nm)}}
        </td>
        <td nowrap>
          {{strip_tags($customer->person_post)}}
        </td>
        <td nowrap>
          {{strip_tags($customer->person_nm)}}
        </td>
        <td nowrap>
          {{strip_tags($customer->tel)}}
        </td>
        <td nowrap>
          {{strip_tags($customer->fax)}}
        </td>
        <td nowrap>
          {{strip_tags($customer->email)}}
        </td>
        <td nowrap>
          @include('ctl.common._date',["timestamp" => $customer->modify_ts, "format" =>"y/m/d H:M:S" ] )
        </td>
      </tr>
@endforeach

    </table><br>