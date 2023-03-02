<?php

namespace App\Http\Controllers\rsv;

use App\Common\Traits;
use App\Http\Controllers\rsv\_commonController;
use App\Models\Core;
use App\Services\QueryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Log;

/**
 * 検索コントローラ
 */
class QueryController extends _commonController
{
    use Traits;

    // 日程指定各種検索
    public function index(Request $request)
    {
        // 都道府県地域情報を変更
        // $this->set_plastic_place();
        //元ソースではextends元のさらにextends元がAction2Controllerでその処理を呼び出している
        //Action2を作成して以下のように呼び出す形でいいか？
        $action2Controller = new Action2Controller();
        $action2Controller->setPlasticPlace($request);



        // 入力例の場合は、空に更新する。
        $keyword = $request->input('keyword');
        if (mb_strpos($keyword, '入力例') !== false) {
            $keyword = '';
        } //setparamから書き換え

        // キーワードがある場合はビッグキーワードの調整を行う。
        if (!$this->is_empty($keyword)) {
            // キーワードの末尾が <数字10桁> だった場合は
            // 数字10ケタをhotel_cdとみなして施設ページを表示する。
            if (preg_match('/^\<([0-9]{10})\>$/', substr($keyword, -12))) {
                return $this->_redirect("/hotel/" . substr($keyword, -11, 10) . "/");
            } else {
                $this->set_plastic_keywords();
            }
        }

        // 表示形式が地図だった場合は緯度経度を算出し地図を表示
        if ($request->input('type') == 'map') {
            if ($this->is_empty($request->input('lat')) || $this->is_empty($request->input('lat_min'))) {
                $a_survey = $this->get_survey();

                if (!$this->is_empty($a_survey)) {
                    // $this->_request->setParam('lat', $a_survey['wgs_lat_d']);
                    // $this->_request->setParam('lng', $a_survey['wgs_lng_d']);
                    // $this->_request->setParam('zoomlevel', 15);
                    $lat = $a_survey['wgs_lat_d'];
                    $lng = $a_survey['wgs_lng_d'];
                    $zoomlevel = 15;
                    // $s_query_string =  $this->request->to_correct_query(array(), false);
                    // ↑どう書き換えるか ↓であっている？
                    $coreModel = new Core();
                    $s_query_string =  $coreModel->toQueryCorrect([], false);
                    $redirect_url  = '/query' . '/map/?' .  $s_query_string . '&lat=' . $a_survey['wgs_lat_d'] . '&lng=' . $a_survey['wgs_lng_d'] . '&zoomlevel=15';

                    // リダイレクト
                    // return $this->_redirect($redirect_url);
                    return redirect($redirect_url);
                }
            } else {
                // $this->_forward('map');
                // return;
                return redirect()->route('rsv.query.map');
            }
        }

        if (
            $this->is_empty($request->input('hotel_category_business'))
            && $this->is_empty($request->input('hotel_category_inn'))
            && $this->is_empty($request->input('hotel_category_capsule'))
        ) {
            // $this->_request->setParam('hotel_category_business', 'on');
            // $this->_request->setParam('hotel_category_inn',      'on');
            // $this->_request->setParam('hotel_category_capsule',  'on');
            $hotel_category_business = 'on';
            $hotel_category_inn = 'on';
            $hotel_category_capsule = 'on';
        }


        // 空室検索を実行
        if (!$this->is_empty($request->input('date'))) {
            $this->_forward('vacant');
            return;
        }

        // プラン一覧を表示
        if (!$this->is_empty($request->input('capacity'))) {
            // $this->_forward('plan');
            // return;
            return redirect()->route('rsv.query.plan');
        }

        // 施設一覧を表示
        // $this->_forward('hotel');
        return redirect()->route('rsv.query.hotel');
    }

    // 施設一覧
    public function hotel(Request $request)
    {
        //hotelMethodは処理が多いのでこちらにうつさずserviceを別途作成してみたがいいか？
        // if (!$this->hotelMethod()) {

        $service = new QueryService();

        $hotel = $service->hotelMethod($request);
        if ($hotel == false) {
            if ($this->get_error_type() == 'parameter') {
                $this->box->item->error->Add('恐れ入りますが、画面を戻りもう一度お手続きをお願いします。');
                $this->set_assign();
                return $this->render('error');
            }
            if ($this->get_error_type() == 'again') {
                // キーワードが指定されていた場合はキーワードから探す。
                // 以外は旅館・ホテル検索画面にリダイレクト
                if ($this->box->info->env->path_x_uri != '/keywords') {
                    return $this->_redirect($this->box->info->env->path_base . '/');
                }
            }
        }

        $this->box->item->assign  = $this->_assign;
        $this->set_assign();

        if ($this->params('view') == 'markup') {
            header('Content-type: application/json');
            $this->render('json');
        } elseif ($this->params('view') == 'marker') {
            $this->render('marker');
        }
    }
}
