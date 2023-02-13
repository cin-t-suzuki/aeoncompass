{{-- MEMO: 移植元 public\app\rsv\view2\auth\login.tpl --}}

{include file='../_common/_header.tpl' title='会員認証'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}
<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">

      {if $v->assign->type == 'withdraw'}
        <dl class="pgc1-breadcrumbs">
        </dl>
        <div class="snv-text">
          <ul class="snv-text-l3">
            <li><a href="{$v->env.path_base}/rsv/member/"      >会員情報        </a></li>
            <li><a href="{$v->env.path_base}/rsv/member/mail1/">メルマガ受信状態</a></li>
            <li><a href="{$v->env.path_base}/rsv/reminder/"    >パスワード照会  </a></li>
          </ul>
        </div>
      {/if}

    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">
      <div style="text-align:center;">
      <div style="width:99%; margin:0 auto;text-align:left;">

      {if $v->assign->type == 'withdraw'}
        <div style="padding:1em 0;margin-bottom:1em;">
          <h1 style="font-size:150%;font-weight:bold;border-left:4px solid #666;padding:4px;">会員退会手続き</h1>
        </div>
      {/if}

      <form action="{$v->env.ssl_path}{$v->env.module}/auth/exelogin/" method="post">
        <div class="reg-container">

          {* エラーメッセージ *}
          {include file='../_common/_message.tpl'}

          {if !is_empty($v->assign->banner)}<p style="margin-bottom:1em;"><img src="{$v->assign->banner}" /></p>{/if}
          {if is_empty($v->assign->reconfirm) or !$v->user->member->is_login()}
            <p>会員コード・パスワードを入力してください。</p>
          {else}
            <p>ご本人の確認のため、パスワードの入力をお願いします。</p>
          {/if}

          <div class="reg-box border-f90">

            {if is_empty($v->assign->reconfirm) or !$v->user->member->is_login()}
              <div class="form-group">
                <div class="lft"><label for="account_id" class="">会員コード： </label></div>
                <div class="rgt"><input type="text" name="account_id" size="20" maxlength="100" value="{$v->helper->form->strip_tags($v->assign->account_id)}" class="form-control" /></div>
              </div>
            {/if}

            <div class="form-group">
              <div class="lft"><label for="password" class="">パスワード：</label></div>
              <div class="rgt"><input type="password" name="password" size="20" class="form-control" /></div>
            </div>
 
           <input type="submit" name="submit" value="{$v->helper->form->strip_tags($v->assign->button_nm)|nvl:'認  証'}" class="btn" />
          </div>

          {if $v->assign->type == 'withdraw'}
            <div>
              <img src="/images/reserve/dot-sr5.gif" width="11" height="11" alt="" />会員コード・パスワードを  お忘れの方は  → <a href="{$v->env.port_https}{$v->env.path_base_module}/member/withdraw4/">こちら</a>
              <img src="/images/reserve/dot-sr5.gif" width="11" height="11" alt="" />
              <br />
              <br />※2011年11月28日まで旅ぷらざ会員だった方は会員コードにメールアドレスを入力してください。
            </div>
          {else}
            <div>
              <img src="/images/reserve/dot-sr5.gif" width="11" height="11" alt="" />会員コード・パスワードを  お忘れの方は  → <a href="{$v->env.port_https}{$v->env.path_base_module}/reminder/">こちら</a>
              <img src="/images/reserve/dot-sr5.gif" width="11" height="11" alt="" />
              <br />
              <br />※2011年11月28日まで旅ぷらざ会員だった方は会員コードにメールアドレスを入力してください。
            </div>
          {/if}

          <input type="hidden" name="auth_type" value="member" />
          <input type="hidden" name="check_passwd" value="noneed" />
          <input type="hidden" name="finger_cd" value=""       />
          <input type="hidden" name="banner" value="{$v->assign->banner}" />
          <input type="hidden" name="type" value="{$v->assign->type}" />
          <input type="hidden" name="button_nm" value="{$v->assign->button_nm}" />
          <input type="hidden" name="reconfirm" value="{$v->assign->reconfirm}" />
          <input type="hidden" name="next_url" value="{$v->assign->next_url}" />
        </div>
      </form>
      </div>
      </div>
    </div>
  </div>
</div>

{include file='../_common/_footer.tpl'}
