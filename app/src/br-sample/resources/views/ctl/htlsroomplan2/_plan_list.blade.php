{*  引数：$plan_list  *}
{literal}
<script type="text/javascript">
  <!--
    $(document).ready(function () {
      $('input.jqs-btn-plan-del').click(function(){
         return confirm($('.jqs-plan-nm').eq($('input.jqs-btn-plan-del').index(this)).text() + '\n\nこのプランを削除します。\nよろしいですか？');
      });

      var ck_path = '{/literal}{$v->env.source_path}{$v->env.module}/{literal}';
      var ck_hide_plan = $.cookies.get('HIDE_PLAN');
      if ( ck_hide_plan == 'on' ) {
        $('.jqs-plan-list').hide();
        $('input.jqs-display-plans').val('+');
        $('input.jqs-display-plans').attr('title', 'クリックでプラン一覧を展開できます。');
      } else {
        $('.jqs-plan-list').show();
        $('input.jqs-display-plans').val('-');
        $('input.jqs-display-plans').attr('title', 'クリックでプラン一覧を収納できます。');
      }

      $('input.jqs-display-plans').click(function() {
        if ( $('input.jqs-display-plans').val() == '-' ) {
          $('.jqs-plan-list').hide();
          $('input.jqs-display-plans').val('+');
          $.cookies.set('HIDE_PLAN', 'on', {path: ck_path});
          $('input.jqs-display-plans').attr('title', 'クリックでプラン一覧を展開できます。');
        } else {
          $('.jqs-plan-list').show();
          $('input.jqs-display-plans').val('-');
          $.cookies.del('HIDE_PLAN', {path: ck_path});
          $('input.jqs-display-plans').attr('title', 'クリックでプラン一覧を収納できます。');
        }
      });

      $('a[href=#noact]').click(function(){
          if (   $(this).text() == '▲部屋タイプを収納' ) {
            $('.jqs-plan-for-room-type').eq($('a[href=#noact]').index(this)).hide();
            $(this).text('▼部屋タイプを展開');
          } else {
            $('.jqs-plan-for-room-type').eq($('a[href=#noact]').index(this)).show();
            $(this).text('▲部屋タイプを収納');
          }
          return false;
      });

      $('#jqs-all-roomtype-close').click(function(){
        $('.jqs-plan-for-room-type').hide();
        $('a[href=#cancel]').text('▼部屋タイプを展開');
      });

    });
  -->
</script>
{/literal}
<!-- plan-list -->
<div>
  <div class="plan-list-header">
    <div class="plan-list-header-back">
      <div class="plan-list-header-text">
        <span class="font-bold">プラン一覧</span></div>
      </div>
    </div>
  </div>
  <div class="jqs-plan-list">
    <hr class="bound-line" />
    <!-- search-box -->
    <div class="plan-search-box">
      <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/list/" method="post">
        <div>
          <span class="align-l">
            <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
            <input type="radio" id="spfs_0" name="search_sale_status" value="9" {if $v->assign->form_params.search_sale_status == 9}checked="checked"{/if} /><label for="spfs_0">すべてのプラン</label>
            <input type="radio" id="spfs_1" name="search_sale_status" value="1" {if $v->assign->form_params.search_sale_status == 1}checked="checked"{/if} /><label for="spfs_1">設定済のプラン</label>
            <input type="radio" id="spfs_2" name="search_sale_status" value="2" {if $v->assign->form_params.search_sale_status == 2}checked="checked"{/if} /><label for="spfs_2">非販売のプラン</label>
          </span>
          <span class="display-btn align-r"><input type="submit" value="表示" /></span>
        </div>
      </form>
    </div>
    <hr class="bound-line" />
    <!-- /search-box -->
    {if $plan_list|@count <= 0}<span class="msg-text-error">表示対象のプランが見つかりませんでした。</span>{/if}
    {foreach from=$plan_list.plans item=plan name=loop_plan_list}
      {include file=$v->env.module_root|cat:'/view2/_common/_info_plan.tpl' plan=$plan}
      <hr class="bound-line" />
    {/foreach}
  </div>
  <hr class="bound-line" />
</div>
<!-- /plan-list -->