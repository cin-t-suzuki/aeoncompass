@extends('ctl.common._htl_base')
@section('title', 'リンクページ')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelLinkController')

@section('content')

{{-- サブメニュー --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
リンクページ
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

<table border="1" cellspacing="0" cellpadding="4">
    <tr align="center">
        <td bgcolor="#EEEEFF" nowrap>ページ</td>
        <td bgcolor="#EEEEFF">表示順序<br><small>（その他のページ）</small></td>
        <td bgcolor="#EEEEFF">タイトル</td>
        <td bgcolor="#EEEEFF">Webサイトアドレス</td>
        <td bgcolor="#EEEEFF">&nbsp;</td>
    </tr>
    <tr>  
    {{-- 施設トップページ --}}
    @if(count($a_hotel_link_type1['values']) > 0)
        @foreach($a_hotel_link_type1['values'] as $hotel_link1)
            <td>
                施設トップページ
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                {{strip_tags($hotel_link1['title'])}}
            </td>
            <td>
                <a href="{{strip_tags($hotel_link1['url'])}}">{{strip_tags($hotel_link1['url'])}}</a>
            </td>
            <td>
                <table>
                    <tr>
                        {!! Form::open(['route' => ['ctl.htl_hotel_link.edit'], 'method' => 'get']) !!}
                        <td>    
                            <input type="submit" value="編集">
                            <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link1['branch_no'])}}">
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                            <input type="hidden" name="HotelLink[type]" value="1">
                            <input type="hidden" name="HotelLink[title]" value="{{strip_tags($hotel_link1['title'])}}">
                            <input type="hidden" name="HotelLink[url]" value="{{strip_tags($hotel_link1['url'])}}">
                        </td>
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['ctl.htl_hotel_link.delete'], 'method' => 'get']) !!}
                        <td valign="middle">
                            <input type="submit" value="削除">
                            <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link1['branch_no'])}}">
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                            <input type="hidden" name="HotelLink[type]" value="1">
                            <input type="hidden" name="HotelLink[title]" value="{{strip_tags($hotel_link1['title'])}}">
                        </td>
                        {!! Form::close() !!}
                    </tr>
                </table>
            </td>
        @endforeach
    @else
        <td>
            施設トップページ
        </td>
        <td>
                &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
        {!! Form::open(['route' => ['ctl.htl_hotel_link.new'], 'method' => 'get']) !!}
        <td>
            <input type="submit" value="新規登録">
            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
            <input type="hidden" name="HotelLink[type]" value="1">
        </td>
        {!! Form::close() !!}
    @endif
    </tr>
    <tr>
    {{-- 携帯トップページ --}}
    @if(count($a_hotel_link_type2['values']) > 0)
        @foreach($a_hotel_link_type2['values'] as $hotel_link2)
            <td>
                携帯トップページ
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                {{strip_tags($hotel_link2['title'])}}
            </td>
            <td>
                <a href="{{strip_tags($hotel_link2['url'])}}">{{strip_tags($hotel_link2['url'])}}</a>
            </td>
            <td>
            <table>
                <tr>
                    {!! Form::open(['route' => ['ctl.htl_hotel_link.edit'], 'method' => 'get']) !!}      
                        <td>
                            <input type="submit" value="編集">
                            <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link2['branch_no'])}}">
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                            <input type="hidden" name="HotelLink[type]" value="2">
                            <input type="hidden" name="HotelLink[title]" value="{{strip_tags($hotel_link2['title'])}}">
                            <input type="hidden" name="HotelLink[url]" value="{{strip_tags($hotel_link2['url'])}}">
                        </td>
                    {!! Form::close() !!}
                    {!! Form::open(['route' => ['ctl.htl_hotel_link.delete'], 'method' => 'get']) !!}      
                        <td valign="middle">
                            <input type="submit" value="削除">
                            <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link2['branch_no'])}}">
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                            <input type="hidden" name="HotelLink[type]" value="2">
                            <input type="hidden" name="HotelLink[title]" value="{{strip_tags($hotel_link2['title'])}}">
                    </td>
                    {!! Form::close() !!}
                </tr>
            </table>
            </td>
        @endforeach
    @else
            <td>
                携帯トップページ
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                &nbsp;
            </td>
            <td>
                &nbsp;
            </td>
            {!! Form::open(['route' => ['ctl.htl_hotel_link.new'], 'method' => 'get']) !!}      
            <td>
                <input type="submit" value="新規登録">
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                <input type="hidden" name="HotelLink[type]" value="2">
            </td>
            {!! Form::close() !!}
    @endif
    </tr>
  
    {{-- その他 --}}
    @if(count($a_hotel_link_type3['values']) > 0)
        @foreach($a_hotel_link_type3['values'] as $hotel_link3)
            <tr>
                <td>
                    その他ページ{{$loop->iteration}}
                    @php
                        $iteration = $loop->iteration;
                    @endphp
                </td>
                {{-- オーダー --}}
                {!! Form::open(['route' => ['ctl.htl_hotel_link.changeorderno'], 'method' => 'get']) !!}      
                <td align="center">
                    {{-- ループの初めの場合 --}}
                    @if($loop->first)
                        @if(count($a_hotel_link_type3['values']) > 1)
                            <input type="submit" name="order[down]" value=" ↓ ">
                        @endif
                    {{-- ループが最後の場合 --}}
                    @elseif($loop->last)
                        <input type="submit" name="order[up]" value=" ↑ ">
                    @else
                        <input type="submit" name="order[up]" value=" ↑ ">
                        <input type="submit" name="order[down]" value=" ↓ ">
                    @endif
                &nbsp;
                </td>
                    <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link3['branch_no'])}}">
                    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                {!! Form::close() !!}
                <td>
                    {{strip_tags($hotel_link3['title'])}}
                </td>
                <td>
                    <a href="{{strip_tags($hotel_link3['url'])}}">{{strip_tags($hotel_link3['url'])}}</a>
                </td>
                <td>
                    <table>
                    <tr>
                        {!! Form::open(['route' => ['ctl.htl_hotel_link.edit'], 'method' => 'get']) !!}      
                        <td>
                            <input type="submit" value="編集">
                            <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link3['branch_no'])}}">
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                            <input type="hidden" name="HotelLink[type]" value="3">
                            <input type="hidden" name="HotelLink[othercount]" value={{$loop->iteration}}>
                            <input type="hidden" name="HotelLink[title]" value="{{strip_tags($hotel_link3['title'])}}">
                            <input type="hidden" name="HotelLink[url]" value="{{strip_tags($hotel_link3['url'])}}">
                        </td>
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['ctl.htl_hotel_link.delete'], 'method' => 'get']) !!}      
                        <td>
                            <input type="submit" value="削除">
                            <input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($hotel_link3['branch_no'])}}">
                            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                            <input type="hidden" name="HotelLink[type]" value="3">
                            <input type="hidden" name="HotelLink[othercount]" value={{$loop->iteration}}>
                            <input type="hidden" name="HotelLink[order_no]" value="{{strip_tags($hotel_link3['order_no'])}}">
                        </td>
                        {!! Form::close() !!}
                    </tr>
                    </table>  
                </td>
            </tr>
        @endforeach
        
        {{-- その他の新規登録部分の作成 --}}
        @if(count($a_hotel_link_type3['values']) == $iteration)
            <tr>
                <td>
                    @php 
                        $othercount = $iteration + 1;
                    @endphp
                    その他ページ{{$othercount}}
                </td>
                {{-- オーダー --}}
                <td>

                &nbsp;
                </td>
                <td>
                    &nbsp;
                </td>
                <td>
                    &nbsp;
                </td>
                {!! Form::open(['route' => ['ctl.htl_hotel_link.new'], 'method' => 'get']) !!}      
                <td>
                    <input type="submit" value="新規登録">
                    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                    <input type="hidden" name="HotelLink[othercount]" value="{{$othercount}}">
                    <input type="hidden" name="HotelLink[type]" value="3">
                </td>
                {!! Form::close() !!}
            </tr>
        @endif
    {{-- その他にデータが存在しない場合 --}}
    @else
        <td>
            その他ページ1
        </td>
        {{-- オーダー --}}
        <td>
            &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
        <td>
            &nbsp;
        </td>
        {!! Form::open(['route' => ['ctl.htl_hotel_link.new'], 'method' => 'get']) !!}      
        <td>
            <input type="submit" value="新規登録">  
            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
            <input type="hidden" name="HotelLink[type]" value="3">
            <input type="hidden" name="HotelLink[othercount]" value=1>
        </td>
        {!! Form::close() !!}
    @endif
    </tr>
</table>
<ul>
<li>
<small>
      貴ホテルにて独自運営されているホームページのURLのみご登録いただけます。<br>
      他のインターネット予約サイトのURLなどのご登録はご遠慮願います。<br>
            「その他のページ」は系列ホテルトップページ等の登録にご利用ください。
</small>
</ul>
@endsection