@extends('ctl.common._htl_base')
@section('content')
@section('title', '提供室数調整')
  @include('ctl.common._htls_room_header')
    <script type="text/javascript"> 
      <!--
        function select_submit(form_name) {
          document.forms[form_name].submit();
        }
      -->
    </script>

  @include('ctl.common.message', $messages)

    @if ($views->form_params['ui_type'] == 'date')
      @include('ctl.htlsroomoffer._edit_ui_date')
    @elseif($views->form_params['ui_type'] =='room' || $views->form_params['ui_type'] == 'accept')
      @include('ctl.htlsroomoffer._edit_ui_room')
    @elseif($views->form_params['ui_type'] =='calender')
      @include('ctl.htlsroomoffer._edit_ui_calender')
    @endif
@endsection