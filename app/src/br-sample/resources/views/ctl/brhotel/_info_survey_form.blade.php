{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\_info_survey_form.tpl --}}

<table border="1" cellspacing="0" cellpadding="3">

    <tr>
      <td  bgcolor="#EEFFEE" >世界測地系-度-緯度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.wgs_lat_d)}<br />
      </td>
    </tr>
    
    <tr>
      <td  bgcolor="#EEFFEE" >世界測地系-度-経度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.wgs_lng_d)}<br />
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >世界測地系-度分秒-緯度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.wgs_lat)}<br />
      </td>
    </tr>
    
    <tr>
      <td  bgcolor="#EEFFEE" >世界測地系-度分秒-経度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.wgs_lng)}<br />
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >東京測地系-度-緯度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.td_lat_d)}<br />
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >東京測地系-度-経度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.td_lng_d)}<br />
      </td>
    </tr>
  
    <tr>
      <td  bgcolor="#EEFFEE" >東京測地系-度分秒-緯度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.td_lat)}<br />
      </td>
    </tr>
    
    <tr>
      <td  bgcolor="#EEFFEE" >東京測地系-度分秒-経度</td>
      <td>
        {$v->helper->form->strip_tags($v->assign->hotel_survey.td_lng)}<br />
      </td>
    </tr>
    
    {{-- <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}"> --}}
    {{ Form::hidden('target_cd', $target_cd) }}
  </table>