<!-- <FORM ACTION="{$v->env.source_path}{$v->env.module}/brsecurity/search/" METHOD="POST"> -->
{{ Form::open(['route' => 'ctl.brsecurity.search', 'method' => 'post']) }}
  <table border="1" cellpadding="3" cellspacing="0">
  
    <tr>
      <td bgcolor="#EEFFEE">アカウントクラス</td>
      <td>
        <!-- <select name="Search[account_class]" size="1">
          <option value=""            {if zap_is_empty($v->assign->search.account_class)}{/if}>全て</option>
          <option value="staff"       {if !zap_is_empty($v->assign->search.account_class) && $v->helper->form->strip_tags($v->assign->search.account_class) == "staff"} selected {/if}>スタッフ</option>
          <option value="hotel"       {if !zap_is_empty($v->assign->search.account_class) && $v->helper->form->strip_tags($v->assign->search.account_class) == "hotel"} selected {/if}>施設</option>
          <option value="partner"     {if !zap_is_empty($v->assign->search.account_class) && $v->helper->form->strip_tags($v->assign->search.account_class) == "partner"} selected {/if}>提携先</option>
          <option value="supervisor"  {if !zap_is_empty($v->assign->search.account_class) && $v->helper->form->strip_tags($v->assign->search.account_class) == "supervisor"} selected {/if}>施設統括</option>
          <option value="member"      {if !zap_is_empty($v->assign->search.account_class) && $v->helper->form->strip_tags($v->assign->search.account_class) == "member"} selected {/if}>会員</option>
          <option value="member_free" {if !zap_is_empty($v->assign->search.account_class) && $v->helper->form->strip_tags($v->assign->search.account_class) == "member_free"} selected {/if}>非会員</option>
        </select> -->
        <select name="Search[account_class]" size="1">
          <option value=""            @if($zap_is_empty) selected @endif>全て</option>
          
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
        <!-- {if $v->helper->date->set()}{/if} -->
       
        <select name="Search[request_dtm_after]" size="1">
        <!-- {section name = request_dtm_after start = 0 loop = 32} -->
        
          <!-- <option value="{$v->helper->date->to_format('Y-m-d')}"

          {if $v->assign->search.request_dtm_after == $v->helper->date->to_format('Y-m-d')
          || zap_is_empty($v->assign->search.request_dtm_after) && $v->helper->date->to_format('Y-m-d') == $smarty.now|date_format:"%Y-%m-%d"}
            selected
          {/if}
          > -->

            @foreach ($date_request_option as $option)
                <option value="{{ $option }}">{{date('Y年m月d日', strtotime($option))}}</option>
            @endforeach
         
          <!-- {$v->helper->date->to_format('Y年m月d日')} -->
          <!-- </option> -->
          <!-- {if $v->helper->date->add('d', -1)}{/if} -->
        <!-- {/section} -->
        </select>
      ～
        <!-- {if $v->helper->date->set()}{/if} -->
        <select name="Search[request_dtm_before]" size="1">
        <!-- {section name = request_dtm_before start = 0 loop = 32} -->
          <!-- <option value="{$v->helper->date->to_format('Y-m-d')}"
          {if $v->assign->search.request_dtm_before == $v->helper->date->to_format('Y-m-d')
          || zap_is_empty($v->assign->search.request_dtm_before) && $v->helper->date->to_format('Y-m-d') == $smarty.now|date_format:"%Y-%m-%d"}
            selected
          {/if}> -->
         
          <!-- <option value="{$v->helper->date->to_format('Y-m-d')}"> -->
            <!-- {$v->helper->date->to_format('Y年m月d日')} -->
          <!-- </option> -->
            @foreach ($date_request_option as $option)
                <option value="{{ $option }}">{{date('Y年m月d日', strtotime($option))}}</option>
            @endforeach
          <!-- {if $v->helper->date->add('d', -1)}{/if}
        {/section} -->
        </select>
      </td>
    </tr>
    
    <tr>
      <td bgcolor="#EEFFEE">アカウント認証キー</td>
      <td nowrap="nowrap">
        <!-- <input name="Search[account_key]" size="30" maxlength="50" type="text" value="{$v->helper->form->strip_tags($v->assign->search.account_key)}"> ※完全一致 -->
        <input name="Search[account_key]" size="30" maxlength="50" type="text" value="{{strip_tags($search['account_key'])}}"> ※完全一致
      </td>
    </tr>
    
  </table>
<input value="検索" type="submit">
<!-- </form> -->
{!! Form::close() !!}