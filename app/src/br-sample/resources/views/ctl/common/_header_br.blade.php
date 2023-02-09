{{-- MEMO: 移植元 public\app\ctl\view2\_common\_header_br.tpl --}}

<div class="header-br">
    <div class="header-br-back">
        <div class="header-br-contents">
            <div id="system-name">STREAM社内管理</div>
            <div id="main-menu">
                <form action="{{ route('ctl.br.top') }}" method="post">
                    <div>
                        <input type="submit" value="メインメニュー" />
                        担当：{{ $staffName }}
                    </div>
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
