施設情報詳細
    <table border="2" cellspacing="0" cellpadding="4">
      <tr>
        <td bgcolor="#EEFFEE">
        施設コード
        </td>
        <td>
          {{strip_tags($hotel['hotel_cd'])}}<br />
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE">
        施設名
        </td>
        <td>
          {{strip_tags($hotel['hotel_nm'])}}<br />
        </td>
      </tr>
      <tr>
        <td bgcolor="#EEFFEE">
        都道府県
        </td>
        <td>
            {{-- MEMO: 移植元ソースでは sprit_tags() を通しているが、マスタデータの表示なので必要ないと判断した。 --}}
            {{ !is_null($mast_pref) ? $mast_pref['pref_nm'] : '' }}
            {{ !is_null($mast_city) ? $mast_city['city_nm'] : '' }}
            {{ !is_null($mast_ward) ? $mast_ward['ward_nm'] : '' }}
        </td>
      </tr>
    </table>