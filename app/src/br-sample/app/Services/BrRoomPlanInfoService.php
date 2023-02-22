<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;


class BrRoomPlanInfoService
{

    //検索一覧の表示
    /**
    * @param array
    *       aa_conditions
    *			hotel_cd ホテルID
    * @return array
    * 		result		結果内容
    *			hotel_nm		ホテル名
    *			hotel_cd		ホテルID
    *			room_list		部屋リスト
    *			plan_list	    プランリスト
    */
    public function listMethod($aa_conditions)
    {
        try {
            
            $s_sql1 =
            <<< SQL
                                select  room2.hotel_cd,
                                        room2.room_id,
                                        room2.room_nl as room_nm,
                                        room2.label_cd,
                                        room2.accept_status,
                                        hotel.hotel_nm
                                from room2, hotel
                                where room2.hotel_cd = :hotel_cd
                                    and room2.hotel_cd  = hotel.hotel_cd
                                    and room2.display_status = '1'
                                order by room2.room_id
            SQL;

            $s_sql2 =
            <<< SQL
                            select  plan.hotel_cd,
                                    plan.plan_id,
                                    plan.plan_nm,
                                    plan.label_cd,
                                    plan.accept_status
                            from plan
                            where plan.hotel_cd = :hotel_cd
                            and plan.display_status = '1'
                            order by plan.plan_id
            SQL;

            $s_sql3 =
            <<< SQL
                            select	hotel_system_version.hotel_cd,
                                    hotel_system_version.version
                            from hotel_system_version
                            where hotel_system_version.hotel_cd = :hotel_cd
            SQL;

            $s_sql4 =
            <<< SQL
                            select  migration.hotel_cd
                            from migration
                            where migration.hotel_cd = :hotel_cd
            SQL;

            //バインディング用の変数の用意
            $a_conditions['hotel_cd']=$aa_conditions;

            
            //クエリの発行、4個の配列にて結果の保持
            // 部屋情報
            $a_room_list = DB::select($s_sql1, $a_conditions);
            // プラン情報
            $a_plan_list = DB::select($s_sql2, $a_conditions);
            // ヴァージョン情報
            $a_version = DB::select($s_sql3, $a_conditions);
            // migration情報
            $a_migration = DB::select($s_sql4, $a_conditions);
            

            //返却用の変数の初期化
            $use_screen='';
            $mygration='';
            $hotel_cd='';
            $hotel_nm='';
            $result=array();

            if (empty($a_room_list) == false || empty($a_plan_list) == false) {

                if (empty($a_version) == false) {

                    if ($a_version[0]->version == '1') {
                        $use_screen           = '旧';
                    } elseif ($a_version[0]->version == '2')  {	
                        $use_screen           = '新';
                    } else {
                        $use_screen           = '';
                    }

                }  else {
                        $use_screen           = '';
                }	

                if (empty($a_migration) == false) {
                    $mygration           = '済';
                } else {	
                    $mygration           = '未';
                }	

                if (empty($a_room_list) == false){
                    $result['hotel_nm']=$a_room_list[0]->hotel_nm;
                }else{
                    $result['hotel_nm']='';
                }
                
                $result['hotel_cd']=$aa_conditions;
                $result['use_screen']=$use_screen;
                $result['mygration']=$mygration;


            }else {
                $result['hotel_nm'] = '';
                $result['hotel_cd'] = '';
            }

            $result['room_list']=$a_room_list;
            $result['plan_list']=$a_plan_list;

            return $result;

            
        } catch (Exception $e) {
            throw $e;
        }
        
    }
    

}

