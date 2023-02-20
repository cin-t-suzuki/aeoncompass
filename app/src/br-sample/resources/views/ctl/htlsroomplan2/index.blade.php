@extends('ctl.common.base3', ['title'         => 'プランメンテナンス',
                              'screen_type'   => 'htl',
                              'is_staff_navi' => 'on',
                              'is_htl_navi'   => 'on',
                              'is_ctl_menu'   => 'on',])

@section('content')
      <!-- setting-menu -->
      <div><span class="msg-text-deactive">■</span><span class="font-bold">部屋・プラン登録</span></div>
      <table class="htl align-l">
        <tr>
          @if($user['akafu_status'] == 1 )
            <th rowspan="3">部屋</th>
          @else
            <th rowspan="2">部屋</th>
          @endif
          <td>
            <form action="/ctl/htlsroom2/new/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                <input type="submit" value="新規登録" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">部屋を新しく作成します。</span></td>
        </tr>
        @if ($user['akafu_status'] == 1)
          <tr>
            <td>
              <form action="/ctl/htlssettingakf/" method="post">
                <div>
                  <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                  <input type="submit" value="日本旅行連動在庫" />
                </div>
              </form>
            </td>
            <td><span class="msg-text-success">日本旅行連動在庫を使用する為の設定を行います。</span></td>
          </tr>
        @endif
        <tr>
          <td>
            <form action="/ctl/htlsroomorder/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                <input type="submit" value="表示順序" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">管理ページ、販売ページに表示される部屋の表示順を設定します。</span></td>
        </tr>
        <tr>
          <th rowspan="4">プラン</th>
          <td>
            <form action="/ctl/htlsplan2/new/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                <input type="submit" value="新規登録" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">プランを新しく作成します。</span></td>
        </tr>
        <tr>
          <td>
            <form action="/ctl/htlsplanorder/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                <input type="submit" value="表示順序" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">管理ページ、販売ページに表示されるプランの表示順を設定します。</span></td>
        </tr>
        <tr>
          <td>
            <form action="/ctl/htlscopycharge/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                <input type="submit" value="料金のコピー" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">プランに設定された料金を別プランへコピーします。</span></td>
        </tr>
        <tr>
          <td>
            <form action="/ctl/htlsextendoffer/edit/" method="POST" >
              <div>
                <input type="hidden" name="target_cd" value="{{ $user['target_cd'] }}" />
                <input type="hidden" name="plan_id" value="" />
                <input type="submit" value="期間延長" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">プランの期間を一括して延長できます <font color="#ff0000">NEW!!</font></span></td>
        </tr>
      </table>
      <!-- /hsetting-menu -->
      <hr class="bound-line" />
      <hr class="bound-line" />
      <!-- room-list -->
      @include('ctl.htlsroomplan2._room_list')
      <hr class="bound-line" />
      <hr class="bound-line" />
      <hr class="bound-line" />
      <!-- plan-list -->
      @include('ctl.htlsroomplan2._plan_list')
      <hr class="bound-line" />
      <div style="width:1024px;">
        <div class="align-r"><a href="#ptop">▲ページトップへ</a></div>
        
@endsection