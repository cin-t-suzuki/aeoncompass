<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class CorePlan extends CommonDBModel
{
    use Traits;

    // カラム

    public function __construct()
    {
        // カラム情報の設定
    }

    /**
     * パワー？
     *
     * Core_Hotel->_s_hotel_cd 施設コード
     * Core_Room->_s_room_cd   部屋コード
     * this->_s_plan_cd        プランコード
     *
     * example
     * >> true  : パワー
     * >> false : パワーでない
     *
     * @return bool
    */
    public function isPower($hotel_cd, $room_cd, $plan_cd) //引き数追加でわざわざ各モデルからとる形ではなくていいか？
    {
        try {
            if ($this->is_empty($plan_cd)) {
                return false;
            }

            $hotel_control = new HotelControl();
            $roomPlanModel     = new RoomPlan();

            $a_hotel_control = $hotel_control->selectByKey($hotel_cd); //find→selectByKeyでいいか？

            // レコードが存在していなかったまたは値がNULLの場合はエラー
            if ($this->is_empty($a_hotel_control['hotel_cd'] ?? null)) { //null追記でいいか？
                //予約記録から各コード取得しているので基本あるはず？以下書き換えなしでいいか？
                throw new Exception('対象となるプランが見つかりませんでした。', 404);
            }

            //非表示部分が元ソース、書き換え合っているか？
            $a_room_plan = $roomPlanModel->selectByTripleKey( //find→selectByTripleKeyでいいか？
                $hotel_cd,
                $room_cd,
                $plan_cd
            );

            // レコードが存在していなかったまたは値がNULLの場合はエラー
            if ($this->is_empty($a_room_plan['hotel_cd'] ?? null)) { //null追記でいいか？
                //予約記録から各コード取得しているので基本あるはず？以下書き換えなしでいいか？
                throw new Exception('対象となるプランが見つかりませんでした。', 404);
            }

            return ($a_hotel_control['stock_type'] == 1 && $a_room_plan['payment_way'] == 1);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
