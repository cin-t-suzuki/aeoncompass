{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\_common\_change_acceptance.tpl --}}

<table border="1" cellpadding="2" cellspacing="0">
    <tr>
      <td align="center">
        <table border="0" cellspacing="0" cellpadding="2">
          <form action="{$v->env.source_path}{$v->env.module}/htlacceptance/update/" method="post">
            {if $v->user->hotel.accept_status == 1}
              <tr><td align="center"><small><font color="#0000ff">予約受付中</font></small></td></tr>
              <tr><td align="center"><input type="submit" value="停止中にする"></td></tr>
            {else}
              <tr><td align="center"><input value="受付中にする" type="submit"></td></tr>
              <tr><td align="center"><small><font color="#ff0000">予約受付停止中</font></small></td></tr>
            {/if}
            {* request値の取得 *}
            {assign var=request value=$v->helper->form->get_request()}
            {* hidden生成処理 *}
            {foreach from=$request->getParams() key=key item=value name=post}
              {if $key != 'error_handler' 
              and $key != 'module'
              and $key != 'controller'
              and $key != 'action'}
                {if is_array($value)}
                  {* 配列のhidden値生成 *}
                  {foreach from=$value key=key2 item=value2 name=post2}
                    <input type="hidden" name="{$key}[{$key2}]" value="{$v->helper->form->strip_tags($value2)}" />
                  {/foreach}
                {else}
                  {* hidden値生成 *}
                  <input type="hidden" name="{$key}" value="{$v->helper->form->strip_tags($value)}" />
                {/if}
              {/if}
            {/foreach}
            <input type="hidden" name="base_controller" value="{$v->helper->form->strip_tags($v->env.controller)}" />
            <input type="hidden" name="base_action" value="{$v->helper->form->strip_tags($v->env.action)}" />
            <input type="hidden" name="hotel[accept_status]" value="{if $v->user->hotel.accept_status == 0}1{else}0{/if}" />
          </form>
        </table>
      </td>
    </tr>
  </table>