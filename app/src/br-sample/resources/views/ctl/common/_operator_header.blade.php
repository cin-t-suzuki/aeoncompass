@if($v->user->operator->is_staff())
  {include file=$v->env.module_root|cat:'/view2/_common/_br_header.tpl'}
@elseif($v->user->operator->is_nta())
  {include file=$v->env.module_root|cat:'/view2/_common/_nta_staff_header2.tpl'}
@endif