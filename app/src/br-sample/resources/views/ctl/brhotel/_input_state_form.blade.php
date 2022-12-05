{{-- MEMO: 移植元 public\app\ctl\views\brhotel\_input_state_form.tpl --}}

<table border="1" cellspacing="0" cellpadding="3">


    <tr>
      <td  bgcolor="#EEFFEE" >施設コード</td>
      <td>
        {{strip_tags($target_cd)}}
      </td>
      <td><br /></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知媒体</td>
      <td>
        <input type="checkbox" name="notify_device[]" value="1" @if ({{-- MEMO: 移植元は is_empty --}} is_null($notify_device)) checked @else @foreach ($notify_device as $item) @if ($item == 1) checked @endif @endforeach @endif id="nd1">
          <label for="nd1">
            ファックス
          </label>
        <input type="checkbox" name="notify_device[]" value="2" @foreach ($notify_device as $item) @if ($item == 2) checked @endif @endforeach id="nd2">
          <label for="nd2">
            電子メール
          </label>
        <input type="checkbox" name="notify_device[]" value="4" @foreach ($notify_device as $item) @if ($item == 4) checked @endif @endforeach id="nd3">
          <label for="nd3">
            オペレータ連絡
          </label>
        <input type="checkbox" name="notify_device[]" value="8" @foreach ($notify_device as $item) @if ($item == 8) checked @endif @endforeach id="nd4">
          <label for="nd4">
            リンカーン
          </label>
      </td>
      <td><small>選択 <font color="#ff0000">※ここを変更する場合は2行下の通知ステータスも必ず確認すること</font></small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE">予約情報プッシュ通知（ねっぱん）</td>
      <td>
        <label><input type="radio" name="Hotel_Notify[neppan_status]" value="1" @if ($hotel_notify->neppan_status === '1' ) checked="checked" @endif />通知する</label>
        <label><input type="radio" name="Hotel_Notify[neppan_status]" value="0" @if ($hotel_notify->neppan_status === '0' ) checked="checked" @endif />通知しない</label>
        <label><input type="radio" name="Hotel_Notify[neppan_status]" value="" @if ({{-- MEMO: 移植元は is_empty --}} is_null($hotel_notify->neppan_status)) checked="checked" @endif />通知しない(※連動時に「通知する」に自動切替)</label>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知ステータス</td>
      <td>
        <input type="radio" name="Hotel_Notify[notify_status]" value="1" @if ($hotel_notify->notify_status == 1 || {{-- MEMO: 移植元は is_empty --}} is_null($hotel_notify->notify_status)) checked @endif id="j4">
          <label for="j4">
            通知する
          </label>
        <input type="radio" name="Hotel_Notify[notify_status]" value="0" @if ($hotel_notify->notify_status == 0 && !{{-- MEMO: 移植元は is_empty --}} is_null($hotel_notify->notify_status)) checked @endif id="j5">
          <label for="j5">
            通知しない
          </label>
      </td>
      <td><small>選択<font color="#ff0000">※ここが「通知しない」だとファックス、電子メール、リンカーンの通知はされません</font></small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知電子メールアドレス</td>
      <td>
        <input type="text" name="Hotel_Notify[notify_email]" value="{{strip_tags($hotel_notify->notify_email)}}" size="50" maxlength="50">
      </td>
      <td><br /></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >通知ファックス番号</td>
      <td>
        <input type="text" name="Hotel_Notify[notify_fax]" value="{{strip_tags($hotel_notify->notify_fax)}}" size="20" maxlength="15">
      </td>
      <td><small>xxxx-xxxx-xxxx</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >FAXPR</td>
      <td>
        <input type="radio" name="Hotel_Notify[faxpr_status]" value="1" @if ($hotel_notify->faxpr_status == 1 || {{-- MEMO: 移植元は is_empty --}} is_null($hotel_notify->faxpr_status)) checked @endif id="j9">
          <label for="j9">
            表示する
          </label>
        <input type="radio" name="Hotel_Notify[faxpr_status]" value="0" @if ($hotel_notify->faxpr_status == 0 && !{{-- MEMO: 移植元は is_empty --}} is_null($hotel_notify->faxpr_status)) checked @endif id="j8">
          <label for="j8">
            表示しない
          </label>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >仕入タイプ</td>
      <td>
  @if ($hotel_control->stock_type == "2") 一括受託（東横イン）<input type="hidden" name="Hotel_Control[stock_type]" value="2" />
  @else
        <label>
        <input type="radio" name="Hotel_Control[stock_type]" value="0" @if ($hotel_control->stock_type == 0) checked @endif id="i1">
          <label for="i1">
            受託販売
          </label>
        </label>
        <label>
        <input type="radio" name="Hotel_Control[stock_type]" value="1" @if ($hotel_control->stock_type == 1) checked @endif id="i2">
          <label for="i2">
            買取販売
          </label>
      <input type="radio" name="Hotel_Control[stock_type]" value="3" @if ($hotel_control->stock_type == 3) checked @endif id="i3">
          <label for="i3">
            特定施設(三普)
          </label>
   @endif 
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >送客実績送信</td>
      <td>
        <input type="radio" name="Hotel_Control[checksheet_send]" value="1" @if ($hotel_control->checksheet_send == 1 && !{{-- MEMO: 移植元は is_empty --}} is_null($hotel_control->checksheet_send)) checked @endif id="i4">
          <label for="i4">
            送信する
          </label>
        <input type="radio" name="Hotel_Control[checksheet_send]" value="0" @if ($hotel_control->checksheet_send == 0 || {{-- MEMO: 移植元は is_empty --}} is_null($hotel_control->checksheet_send)) checked @endif id="i3">
          <label for="i3">
            送信しない
          </label>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >金額切り捨て桁</td>
      <td>
        <label>
        <input type="radio" name="Hotel_Control[charge_round]" value="1" @if ($hotel_control->charge_round == 1 || {{-- MEMO: 移植元は is_empty --}} is_null($hotel_status->entry_status)) checked @endif id="i5">
          <label for="i5">
            1の位で丸める
          </label>
          </label>
        <label>
        <input type="radio" name="Hotel_Control[charge_round]" value="10" @if ($hotel_control->charge_round == 10) checked @endif id="i6">
          <label for="i6">
            10の位で丸める
          </label>
        <input type="radio" name="Hotel_Control[charge_round]" value="100" @if ($hotel_control->charge_round == 100) checked @endif id="i7">
          <label for="i7">
            100の位で丸める
          </label>
      </td>
      <td><small>選択</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >連泊限界数</td>
      <td>
        <input type="text" name="Hotel_Control[stay_cap]" value="{{strip_tags($hotel_control->stay_cap)}}" size="4" maxlength="2"><small>設定する場合入力</small>
      </td>
      <td><small>数字2桁</small></td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >利用方法</td>
      <td>
        <label>
        <input type="radio" name="Hotel_Control[management_status]" value="1" @if ($hotel_control->management_status == 1) checked @endif id="management_status1">
          <label for="management_status1">ファックス管理</label>
        </label>
        <label>
        <input type="radio" name="Hotel_Control[management_status]" value="2" @if ($hotel_control->management_status == 2 || {{-- MEMO: 移植元は is_empty --}} is_null($hotel_control->management_status)) checked @endif id="management_status2">
          <label for="management_status2">インターネット管理</label>
        <label>
        <input type="radio" name="Hotel_Control[management_status]" value="3" @if ($hotel_control->management_status == 3) checked @endif id="management_status3">
          <label for="management_status3">ファックス管理＋インターネット管理</label>
      </td>
      <td><small>選択</small></td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >管理システムバージョン</td>
      <td>
        <input type="checkbox" name="version[]" value="1" id="system_version1" @if (!{{-- MEMO: 移植元は is_empty --}} is_null($version) and in_array(1, $version)) checked @endif /><label for="system_version1">旧インターフェース</label>
        <input type="checkbox" name="version[]" value="2" id="system_version2" @if ({{-- MEMO: 移植元は is_empty --}} is_null($version) or in_array(2, $version)) checked @endif /><label for="system_version2">新インターフェース</label>
      </td>
      <td><small>複数選択可<font color="#0000ff">（必須）</font></small></td>
    </tr>
    <tr>
      <td  bgcolor="#EEFFEE" >日本旅行在庫連携</td>
      <td>
        <input type="radio" id="akafu_status_1" name="Hotel_Control[akafu_status]" value="1" @if ($hotel_control->akafu_status == 1) checked="checked" @endif /><label for="akafu_status_1">利用する</label>
        <input type="radio" id="akafu_status_0" name="Hotel_Control[akafu_status]" value="0" @if ($hotel_control->akafu_status != 1) checked="checked" @endif /><label for="akafu_status_0">利用しない</label>
      </td>
      <td><small>選択</small></td>
    </tr>
    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
  </table>
  