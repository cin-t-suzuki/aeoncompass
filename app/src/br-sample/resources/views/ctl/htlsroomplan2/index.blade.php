
{include file=$v->env['module_root']|cat:'/view2/_common/_doctype.tpl'}
<html>
  @php
    // TODO: 他の guard で認証しているとき、これを追加
    // 他の guard: hotel(施設管理者), supervisor(施設統括), partner, affiliate
    // MEMO: 移植元では、 lib\Br\Models\Authorize\Operator.php の is_login() で判定
    $isLogin = Auth::guard('staff')->check();

    $isStaff = Auth::guard('staff')->check();
    if ($isStaff) {
        $staffName = Auth::guard('staff')->user()->staffInfo->staff_nm;
    } else {
        $staffName = 'TODO: ロール未実装';
    }

    // TODO: 認証関連、環境変数関連
    $v = new \stdClass;

    $v->user = new \stdClass;

    class Operator
    {
        public $staff_nm = '';
        public $nta_login_data;
        public function __construct($staffName)
        {
            $this->staff_nm = $staffName;
            $this->nta_login_data = (object)[
                'staff_nm' => 'staff_nm' . Str::random(3,6),
            ];
        }

        public function is_staff()
        {
          return false;
        }

        public function is_nta()
        {
          return false;
        }
    }
    $v->user->operator = new Operator(Str::random(16));

    $v->user->hotel = (object)[
        'hotel_nm'      => 'hotel_nm_'      . Str::random(rand(3,8)),
        'hotel_cd'      => 'hotel_cd_'      . Str::random(rand(3,8)),
        'hotel_old_nm'  => 'hotel_old_nm_'  . Str::random(rand(3,8)),
        'ydp2_status'   => rand(0,1) == 0,

        'premium_status' => rand(0,1) == 0,
        'visual_package_status' => rand(0,1) == 0,
        'accept_status' => rand(0,1),
    ];

    $v->user->hotel_status = (object)[
        'entry_status' => rand(0,1),
    ];

    $v->user->hotel_control = (object)[
        'stock_type' => rand(0,2),
    ];
    $v->user->hotel_person = (object)[
        'person_nm' => 'person_nm_' . Str::random(rand(8, 16)),
        'person_post' => 'person_post_' . Str::random(rand(8, 16)),
        'person_tel' => 'person_tel_' . rand(1000000, 9999999),
        'person_fax' => 'person_fax_' . rand(1000000, 9999999),
    ];

    $v->user->hotel_system_version = (object)[
        'version' => 0
    ];

    $v->env = (object)[
        'controller'        => "htlsroomplan2",
        'action'            => "index",
        'source_path'       => '',
        'module'            => '/ctl',
        'path_base_module'  => 'ctl/statics',
    ];

    $v->config = new \stdClass;
    $v->config->environment = new \stdClass;
    $statuses = ['development', 'test', 'product', 'unknown'];
    $v->config->environment->status = $statuses[rand(0,3)];

    $v->config->environment->mail = (object)[
        'from' => (object)[
            'opc' => Str::random(rand(20, 30)),
        ],
    ];

    $v->helper = new \stdClass;
    class form
    {
      public function strip_tags($title)
      {
        return $title;
      }
    }
    $v->helper->form = new form();

    $print_flg              = rand(0,9) == 0;
    $no_print               = rand(0,1) == 0;
    $no_print_title         = rand(0,1) == 0;
    $service_info_flg       = rand(0,1) == 0;
    $acceptance_status_flg  = rand(0,1) == 0;
    $header_number          = 'header_number_' . rand(0,100);
    $ad = Str::random(16);
    $title = 'プランメンテナンス';
    $is_edit_jrset = true;

    $v->assign = (object)[
        'is_migration' => 'true'
    ];
  @endphp

  <head>
    @include('ctl.common._head')
      <script type="text/javascript" src="/scripts/jquery['cookies']['js']"></script>
      <script type="text/javascript">
          <!--
            $(document)['ready'](function () {});
          -->
      </script>
  </head>
  <body>
    <div class="development">
      <a name="ptop" id="ptop"></a>
      <!-- env-info       -->
      @include('ctl.common._env_info')
      <!-- /env-info -->
      <!-- operator-heder -->
      @include('ctl.common._operator_header')
      <!-- /operator-header -->
      <!-- htl-header     -->
      @include('ctl.common._header_htl')
      <!-- /htl-header -->
      <div class="page-head">
        <div class="page-head-back">
        <!-- display-title  -->
        <div class="display-title">プランメンテナンス</div>
        <!-- /display-title -->
        <!-- br-info        -->
        @include('ctl.common._htl_service_info', ['ad' => $ad])
        <!-- /br-info -->
        </div>
      </div>
      <div class="header-menu-base">
        <div class="header-menu-base-back">
          @include('ctl.common._display_menu')
        </div>
      </div>
      <hr class="bound-line" />
      <!-- message        -->
      @include('ctl.common.message')
      <!-- /message -->
      <!-- setting-menu -->
      <div><span class="msg-text-deactive">■</span><span class="font-bold">部屋・プラン登録</span></div>
      <table class="htl">
        <tr class="align-l">
          @if($views->user['akafu_status'] == 1 )
            <th rowspan="3">部屋</th>
          @else
            <th rowspan="2">部屋</th>
          @endif
          <td>
            <form action="/ctl/htlsroom2/new/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
                <input type="submit" value="新規登録" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">部屋を新しく作成します。</span></td>
        </tr>
        @if ($views->user['akafu_status'] == 1)
          <tr>
            <td>
              <form action="/ctl/htlssettingakf/" method="post">
                <div>
                  <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
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
                <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
                <input type="submit" value="表示順序" />
              </div>
            </form>
          </td>
          <td><span class="msg-text-success">管理ページ、販売ページに表示される部屋の表示順を設定します。</span></td>
        </tr>
        <tr class="align-l">
          <th rowspan="4">プラン</th>
          <td>
            <form action="/ctl/htlsplan2/new/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
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
                <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
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
                <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
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
                <input type="hidden" name="target_cd" value="{{ $views->user['target_cd'] }}" />
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
        <hr class="bound-line" />
        <!-- footer -->
        @include('ctl.common._footer')
        <!-- /footer -->
      </div>
    </div>
  </body>
</html>