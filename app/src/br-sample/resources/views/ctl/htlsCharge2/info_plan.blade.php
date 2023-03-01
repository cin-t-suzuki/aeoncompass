{{-- TODO:is_nta認証系のため追加恐らく要らない --}}
@php
$is_nta = false;
@endphp

  {{-- プラン情報コンテナ --}}
  <div class="gen-container">
    <h2 class="contents-header">プラン情報</h2>

    {{-- 余白 --}}
    <hr class="bound-line" />

    <div class="info-plan-base">
      <div class="info-plan-base-back">
        <div class="info-plan-base-inline">
          {{-- プラン名称 --}}
          <p>{{ $plan->plan_nm }}</p>

          {{-- PMSコード（プラン） --}}
          @if  (!$is_nta && $plan->is_relo != 1)
          {{-- (!$v->env->controller->is_nta() && $plan->is_relo != 1)  元ｺｰﾄﾞ--}}
            <p>[{{ $plan->pms_cd }}]</p>
          @elseif ($is_nta && $plan->is_relo == 1)
          {{-- ($v->env->controller->is_nta() && $plan->is_relo == 1) 元ｺｰﾄﾞ--}}
            <p>[{{ $plan->pms_cd }}]</p>
          @endif

          {{-- プランスペック --}}
          {{-- {include file=$v->env['module_root']|cat:'/view2/_common/_plan_spec_icons.tpl' plan = $plan} --}}
          @include('ctl.common._plan_spec_icons',[
            'plan' => $plan])
        </div>
      </div>
    </div>
  </div>
