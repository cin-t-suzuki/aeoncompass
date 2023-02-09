
{{ Form::open(['route' => 'ctl.brsecurity.search', 'method' => 'post']) }}
  <table border="1" cellpadding="3" cellspacing="0">
  
    <tr>
      <td bgcolor="#EEFFEE">アカウントクラス</td>
      <td>
  
        <select name="Search[account_class]" size="1">
          <option value=''          @if($zap_is_empty) selected @endif>全て</option>
          
          <option value="staff"        @if(!$zap_is_empty && strip_tags($search['account_class']) == "staff") selected @endif>スタッフ</option>
          <option value="hotel"        @if(!$zap_is_empty && strip_tags($search['account_class']) == "hotel") selected @endif>施設</option>
          <option value="partner"    @if(!$zap_is_empty && strip_tags($search['account_class'])== "partner") selected @endif>提携先</option>
          <option value="supervisor" @if(!$zap_is_empty && strip_tags($search['account_class'])== "supervisor") selected @endif>施設統括</option>
          <option value="member"     @if(!$zap_is_empty && strip_tags($search['account_class'])== "member") selected @endif>会員</option>
          <option value="member_free" @if(!$zap_is_empty && strip_tags($search['account_class'])== "member_free") selected @endif>非会員</option>
        </select>
      </td>
    </tr>



    
    <tr>
      <td bgcolor="#EEFFEE">リクエスト日時</td>
      <td nowrap="nowrap">
       
        <select name="Search[request_dtm_after]" size="1">
 

            @foreach ($date_request_option as $option)
                <option value="{{ $option }}"
                 @if(strtotime($search['request_dtm_after']) == strtotime($option)) selected @endif
                >
                {{date('Y年m月d日', strtotime($option))}}</option>
            @endforeach
         
   
        </select>
      ～

        <select name="Search[request_dtm_before]" size="1">

  
            @foreach ($date_request_option as $option)
                <option value="{{ $option }}"
                @if(strtotime($search['request_dtm_before']) == strtotime($option)) selected @endif
                >
                {{date('Y年m月d日', strtotime($option))}}</option>
            @endforeach
    
        </select>
      </td>
    </tr>
    
    <tr>
      <td bgcolor="#EEFFEE">アカウント認証キー</td>
      <td nowrap="nowrap">
       <input name="Search[account_key]" size="30" maxlength="50" type="text" value="{{strip_tags($search['account_key'])}}"> ※完全一致
      </td>
    </tr>
    
  </table>
<input value="検索" type="submit">
{!! Form::close() !!}