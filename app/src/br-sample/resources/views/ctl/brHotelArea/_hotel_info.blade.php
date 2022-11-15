{{-- MEMO: 移植元 svn_trunk\public\app\ctl\view2\brhotelarea\_hotel_info.tpl --}}

  <div class="br-search-box">
    <table class="br-search-field">
      <tr>
        <th class="item-nm">施設コード</th>
        <td>{$hotel_info.hotel_cd}</td>
      </tr>
      <tr>
        <th class="item-nm">施設名称</th>
        <td>{$hotel_info.hotel_nm}</td>
      </tr>
      <tr>
        <th class="item-nm">郵便番号</th>
        <td>{$hotel_info.postal_cd}</td>
      </tr>
      <tr>
        <th class="item-nm">所在地</th>
        <td>{$hotel_info.pref_nm}&nbsp;{$hotel_info.address}</td>
      </tr>
      <tr>
        <th class="item-nm">電話番号</th>
        <td>{$hotel_info.tel}</td>
      </tr>
      <tr>
        <th class="item-nm">FAX番号</th>
        <td>{$hotel_info.fax}</td>
      </tr>
    </table>
  </div>
  <div class="clear"></div>
