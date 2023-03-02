<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\rsv\_commonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Mast;
use App\Models\MastPref;
use App\Models\MastWardZone;
use App\Models\MastWard;
use App\Models\MastArea;
use App\Models\MastCity;
use Log;
use App\Common\Traits;
use App\Services\BrCustomerService as Service;

/**
 * Action2コントローラ
 */
class Action2Controller extends _commonController
{
    use Traits;

    // １つのパラメータ（place）でまとめられているパラメータを都道府県市区に分割
    //
    //     place  先頭の文字列により判断し下記のようにパラメータを設定します。
    //       p    都道府県 -> pref_id
    //       c    市       -> city_id
    //       w    区       -> ward_id
    //       z    区地域   -> wardzone_id
    //       [lms] エリア   -> area_id
    public function setPlasticPlace($request) //protected→publicでいいか？
    {

        // インスタンスを取得
        $mast_pref     = new MastPref();
        $mast_wardzone = new MastWardZone(); //Mast_Wardzone
        $mast_city   = new MastCity();
        $mast_ward   = new MastWard();
        $mast_area   = new MastArea();

        // // Mast の モジュールを取得
        // $o_models_mast = new models_Mast();
        //models_Mastを使うところはMastWardZoneへまとめた
        $o_models_mast = new Service(); //新たにServiceを作成する形にしたがいいか？

        // $this->request->setParam('retrieval_place', $this->request->getParam('place'));
        $retrieval_place = $request->input('place');

        // 都道府県が選択された場合
        if (strpos($request->input('place'), 'p') !== false) {
            // 都道府県取得
            // $this->request->setParam('pref_id', str_replace('p', '', $request->input('place')));
            $param['pref_id'] = str_replace('p', '', $request->input('place'));

            // 都道府県取得
            $a_mast_pref = $mast_pref->selectByKey($request->input('pref_id')); //find→selectByKeyでいいか？

            // $this->request->setParam('place_nm', $a_mast_pref['pref_nm'] . '全域');
            $param['place_nm'] = $a_mast_pref['pref_nm'] . '全域';

            // 市が選択された場合
        } elseif (strpos($request->input('place'), 'c') !== false) {
            // 市取得
            $city_id = str_replace('c', '', $request->input('place'));

            // 市情報取得
            $a_mast_city = $mast_city->selectByKey($city_id); //find→selectByKeyでいいか？

            // $this->request->setParam('pref_id', $a_mast_city['pref_id']);
            $param['pref_id'] = $a_mast_city['pref_id'];

            // 市の登録
            // $this->request->setParam('city_id', $city_id);
            $param['city_id'] = $city_id;

            // 都道府県選択
            $a_mast_pref = $mast_pref->selectByKey($a_mast_city['pref_id']); //find→selectByKeyでいいか？

            // $this->request->setParam('place_nm', $a_mast_pref['pref_ns'] . ' ' . $a_mast_city['city_nm']);
            $param['place_nm'] = $a_mast_pref['pref_ns'] . ' ' . $a_mast_city['city_nm'];

            // ホテル一覧リンク遷移用
            // $this->request->setParam('place_url', $a_mast_city['city_cd']);
            $param['place_url'] = $a_mast_city['city_cd'];

            // 区が選択された場合
        } elseif (strpos($request->input('place'), 'w') !== false) {

            // 区ID取得
            $ward_id = str_replace('w', '', $request->input('place'));

            // 区情報取得
            $a_mast_ward = $mast_ward->selectByKey($ward_id); //find→selectByKeyでいいか？

            // 区マスタが存在すれば
            if (count($a_mast_ward) != 0) {
                // 都道府県、市登録
                // $this->request->setParam('pref_id', $a_mast_ward['pref_id']);
                // $this->request->setParam('city_id', $a_mast_ward['city_id']);
                $pref_id = $a_mast_ward['pref_id'];
                $city_id = $a_mast_ward['city_id'];
            }

            // 区の登録
            // $this->request->setParam('ward_id', $ward_id);
            $param['ward_id'] = $ward_id;

            // 都道府県選択
            $a_mast_pref = $mast_pref->selectByKey($a_mast_ward['pref_id']); //find→selectByKeyでいいか？

            // 市取得
            $a_mast_city = $mast_city->selectByKey($request->input('city_id')); //find→selectByKeyでいいか？

            // $this->request->setParam('place_nm', $a_mast_pref['pref_ns'] . ' ' . $a_mast_city['city_nm'] . ' ' . $a_mast_ward['ward_nm']);
            $param['place_nm'] = $a_mast_pref['pref_ns'] . ' ' . $a_mast_city['city_nm'] . ' ' . $a_mast_ward['ward_nm'];

            // 地区が選択された場合
        } elseif (strpos($request->input('place'), 'z') !== false) {
            // 地区ID取得
            $wardzone_id = str_replace('z', '', $request->input('place'));

            // wardzoneIdに紐づく区マスタを取得
            $a_wardzone_wards = $o_models_mast->getWardZoneWards(['wardzone_id' => $wardzone_id]);

            // wardzoneIdに紐づく区マスタが存在すれば
            if (count($a_wardzone_wards['values']) != 0) {
                // 都道府県、市の登録
                // $this->request->setParam('pref_id', $a_wardzone_wards['values'][0]['pref_id']);
                // $this->request->setParam('city_id', $a_wardzone_wards['values'][0]['city_id']);
                $param['pref_id'] = $a_wardzone_wards['values'][0]['pref_id'];
                $param['city_id'] = $a_wardzone_wards['values'][0]['city_id'];
            }

            // 地区IDのセット
            // $this->request->setParam('wardzone_id', $wardzone_id);
            $param['wardzone_id'] = $wardzone_id;

            // 地区取得
            $a_wardzone = $mast_wardzone->selectByKey($request->input('wardzone_id')); //find→selectByKeyでいいか？

            // 都道府県選択
            $a_mast_pref = $mast_pref->selectByKey($a_wardzone_wards['values'][0]['pref_id']); //find→selectByKeyでいいか？

            // $this->request->setParam('place_nm', $a_mast_pref['pref_ns'] . ' ' . $a_wardzone['wardzone_nm']);
            $param['place_nm'] = $a_mast_pref['pref_ns'] . ' ' . $a_wardzone['wardzone_nm'];

            // エリアが選択された場合
        } elseif (strpos($request->input('place'), 'l') !== false or strpos($request->input('place'), 'm') !== false or strpos($request->input('place'), 's') !== false) {

            // エリアID取得
            $area_id = str_replace('l', '', str_replace('m', '', str_replace('s', '', $request->input('place'))));

            // エリア情報取得
            $a_mast_area = $mast_area->selectByKey($area_id); //find→selectByKeyでいいか？

            $a_pcw = $o_models_mast->getMastAreaMatchReverse(['area_id' => $area_id]);

            if (!$this->is_empty($a_pcw['values']['pref_id'])) {
                // $this->request->setParam('pref_id', $a_pcw['values']['pref_id']);
                $param['pref_id'] = $a_pcw['values']['pref_id'];
            }
            if (!$this->is_empty($a_pcw['values']['city_id'])) {
                // $this->request->setParam('city_id', $a_pcw['values']['city_id']);
                $param['city_id'] = $a_pcw['values']['city_id'];
            }
            if (!$this->is_empty($a_pcw['values']['ward_id'])) {
                // $this->request->setParam('ward_id', $a_pcw['values']['ward_id']);
                $param['ward_id'] = $a_pcw['values']['ward_id'];
            }

            // エリアの登録
            // $this->request->setParam('area_id', $area_id);
            $param['area_id'] = $area_id;

            // $this->request->setParam('place_nm', $a_mast_area['area_nm']);
            $param['place_nm'] = $a_mast_area['area_nm'];
        }

        // 都道府県コードが選択された場合
        if (!$this->is_empty($request->input('pref_cd'))) {
            // $this->request->setParam('pref_id', $o_models_mast->get_pref_id(array('pref_cd' => $request->input('pref_cd'))));
            $param['pref_id'] = $o_models_mast->getPrefId(['pref_cd' => $request->input('pref_cd')]);
        }

        // 市郡コードが選択された場合
        if (!$this->is_empty($request->input('city_cd'))) {
            // $this->request->setParam('city_id', $o_models_mast->get_city_id(array('city_cd' => $request->input('city_cd'))));
            $param['city_id'] = $o_models_mast->getCityId(['city_cd' => $request->input('city_cd')]);
        }

        // 区コードが選択された場合
        if (!$this->is_empty($request->input('ward_cd'))) {
            // $this->request->setParam('ward_id', $o_models_mast->get_ward_id(array('ward_cd' => $request->input('ward_cd'))));
            $param['ward_id'] = $o_models_mast->getWardId(['ward_cd' => $request->input('ward_cd')]);
        }
    }
}
