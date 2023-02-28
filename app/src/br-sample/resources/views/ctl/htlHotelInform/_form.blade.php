<table border="1" cellspacing="0" cellpadding="4">
    <tr>
      <td rowspan="3" bgcolor="#EEEEFF">
        連絡事項
      </td>
      <td>
        <input type="radio" name="HotelInform[inform_type]" value="0" @if(isset($a_hotel_inform['inform_type']) && $a_hotel_inform['inform_type'] == 0) checked @endif id="hotelinform_inform_type0"><label for="hotelinform_inform_type0">注意事項</label>  
      </td>
    </tr>
    <tr>
      <td>
        <input type="radio" name="HotelInform[inform_type]" value="1" @if(isset($a_hotel_inform['inform_type']) && $a_hotel_inform['inform_type'] == 1) checked @endif id=hotelinform_inform_type1><label for="hotelinform_inform_type1">その他記入欄</label>
      </td>
    </tr>
    <tr>
      <td>
        <table cellspacing="0" cellpadding="4">
          <tr>
            <td>
              <textarea name="HotelInform[inform]" rows="4" cols="100">{{strip_tags($a_hotel_inform['inform'])}}</textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>