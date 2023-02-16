{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\top\_form_hotel.tpl --}}

<div class="sfm-keyword">
    <div class="sfm-keyword-inner">
        <form method="get" action="{$v->env.path_base_module}/hotellist/search/">
            <input name="keywords" type="text" value="" maxlength="40" placeholder="入力例：プリンスホテル">
            <div class="btn-b01-143-s" style="margin:-8px 16px 0 0; float:right; padding:5px;">
                <input class="btnimg" src="/img/btn/b01-search4.gif" type="image" alt="ホテル検索" />
            </div>
        </form>
        <dl>
            <dt>●ホテル・旅館名の入力例</dt>
            <dd>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=東横イン">東横イン</a>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=アパホテル">アパホテル</a>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=ヴィアイン">ヴィアイン</a>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=ホテルグランヴィア">ホテルグランヴィア</a>
                <br />
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=スーパーホテル">スーパーホテル</a>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=ドーミーイン">ドーミーイン</a>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=チサンホテル">チサンホテル</a>
                <a href="{$v->env.path_base_module}/hotellist/search/?keywords=東急イン">東急イン</a>
            </dd>
        </dl>
    </div>
</div>
