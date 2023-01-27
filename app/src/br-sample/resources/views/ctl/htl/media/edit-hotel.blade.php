{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/edithotel.tpl --}}

{{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='施設画像設定'} --}}
@extends('ctl.common._htl_base')
@section('title', '施設画像設定')

@section('headScript')
    {{-- {include file='./_css.tpl'} --}}
    @include('ctl.htl.media._css')
@endsection

@section('content')

    <div class="clear">
        <hr>
    </div>
    <hr width="100%" size="1">
    <!-- Main -->
    <div id="page_top_symbol">
        <p>
            {{-- {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'} --}}
            @include('ctl.common.message')
        </p>
        <div>
            {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_edithotel_outside.tpl'} --}}
            @include('ctl.htl.media._edithotel_outside')
        </div>
        <div>
            {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_edithotel_map.tpl'} --}}
            @include('ctl.htl.media._edithotel_map')
        </div>
        <div>
            {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_edithotel_gallery.tpl'} --}}
            @include('ctl.htl.media._edithotel_gallery')
        </div>
    </div>

    {{-- {include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'} --}}
    @include('ctl.htl.media._common_menu')

    <!-- /Main -->

    <div class="clear">
        <hr>
    </div>

    {{-- {include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'} --}}

@endsection
