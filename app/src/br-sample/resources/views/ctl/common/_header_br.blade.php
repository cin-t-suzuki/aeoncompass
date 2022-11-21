{{-- MEMO: 移植元 public\app\ctl\view2\_common\_header_br.tpl --}}

{strip}
  <div class="header-br">
    <div class="header-br-back">
      <div class="header-br-contents">
        <div id="system-name">STREAM社内管理</div>
        <div id="main-menu">
          <form action="{$v->env.source_path}{$v->env.module}/brtop/" method="post">
            <div>
              <input type="submit" value="メインメニュー" />
              担当：{$v->user->operator->staff_nm}
            </div>
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
{/strip}