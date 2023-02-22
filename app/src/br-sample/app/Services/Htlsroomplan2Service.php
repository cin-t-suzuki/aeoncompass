<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Htlsroomplan2Service
{
    /**
     * get registered rooms
     * 
     * @param {string} $target_cd
     */
    public function get_room_list($target_cd)
    {
       $room_list_sql = <<<SQL
                            select 
                                room2.*,
                                room_network2.network,
                                room_network2.rental,
                                room_network2.connector,
                                room_media2.room_media_count as room_media_count,
                                bathroom_status.element_value_id as bathroom,
                                toilet_status.element_value_id as toilet,
                                smoking_status.element_value_id as smoking,
                                room_plan_match.relation_plan_count as relation_plan_count,
                                room_count2.last_registered_ymd as last_registered_ymd
                            from
                                room2
                            left join
                                room_network2
                            on
                                room_network2.hotel_cd = room2.hotel_cd
                                and room_network2.room_id = room2.room_id
                            left join
                                (select
                                    * 
                                from room_spec2
                                where element_id = 1) as bathroom_status
                            on
                                bathroom_status.hotel_cd = room2.hotel_cd
                                and bathroom_status.room_id = room2.room_id
                            left join
                                (select
                                    * 
                                from room_spec2
                                where element_id = 2) as toilet_status
                            on
                                toilet_status.hotel_cd = room2.hotel_cd
                                and toilet_status.room_id = room2.room_id
                            left join
                                (select
                                    * 
                                from room_spec2
                                where element_id = 3) as smoking_status
                            on
                                smoking_status.hotel_cd = room2.hotel_cd
                                and smoking_status.room_id = room2.room_id
                            left join
                                (select
                                    hotel_cd, room_id, count(media_no) as room_media_count
                                from room_media2
                                group by hotel_cd, room_id) as room_media2
                            on
                                room_media2.hotel_cd = room2.hotel_cd
                                and room_media2.room_id = room2.room_id
                            left join
                                (select
                                    hotel_cd, room_id, count(plan_id) as relation_plan_count
                                from room_plan_match
                                group by hotel_cd, room_id) as room_plan_match
                            on
                                room_plan_match.hotel_cd = room2.hotel_cd
                                and room_plan_match.room_id = room2.room_id
                            left join
                                (select
                                    hotel_cd, room_id, max(date_ymd) as last_registered_ymd
                                from room_count2
                                where date_ymd >= DATE_FORMAT(NOW(), '%Y-%m-01')
                                group by hotel_cd, room_id) as room_count2
                            on
                                room_count2.hotel_cd = room2.hotel_cd
                                and room_count2.room_id = room2.room_id
                            where
                                room2.hotel_cd = $target_cd
                                and room2.display_status = 1
                                and room2.active_status = 1
                        SQL;

        $room_list = DB::select($room_list_sql, []);
    
        return $room_list;
    }


    /**
     * get registered plans
     * 
     * @param {string} $target_cd
     */
    public function get_plan_list($target_cd)
    {
        //プランの基本情報を主軸として、関連するキャンペーン数や仕入タイプを取得
        $plan_list_sql = <<<SQL
                            select
                                plan.*,
                                extend_switch_plan2.extend_status,
                                plan_spec.element_value_id,
                                plan_point.point_status,
                                hotel_camp_plan2.camp_cnt,
                                hotel_control.stock_type,
                                plan_media.media_cnt
                            from
                                plan
                            left join
                                extend_switch_plan2
                            on
                                extend_switch_plan2.hotel_cd = plan.hotel_cd
                                and extend_switch_plan2.plan_id = plan.plan_id
                            left join
                                plan_spec
                            on
                                plan_spec.hotel_cd = plan.hotel_cd
                                and plan_spec.plan_id = plan.plan_id
                            left join
                                plan_point
                            on
                                plan_point.hotel_cd = plan.hotel_cd
                                and plan_point.plan_id = plan.plan_id
                            left join
                                hotel_control
                            on
                                hotel_control.hotel_cd = plan.hotel_cd
                            left join
                                (select
                                    hotel_cd, plan_id, count(camp_cd) as camp_cnt
                                 from hotel_camp_plan2
                                 group by hotel_cd, plan_id) as hotel_camp_plan2
                            on
                                hotel_camp_plan2.hotel_cd = plan.hotel_cd
                                and hotel_camp_plan2.plan_id = plan.plan_id
                            left join
                                (select
                                    hotel_cd, plan_id, count(media_no) as media_cnt
                                 from plan_media
                                 group by hotel_cd, plan_id) as plan_media
                            on
                                plan_media.hotel_cd = plan.hotel_cd
                                and plan_media.plan_id = plan.plan_id
                            where
                                plan.hotel_cd = $target_cd
                                and plan.display_status = 1
                                and plan.active_status = 1
                         SQL;
        
        $plan_list = DB::select($plan_list_sql, []);
        

        //プランに関連する部屋の在庫や、最小・最大の在庫登録日を取得
        $relation_rooms_sql = <<<SQL
                                select
                                    room2.*,
                                    room_plan_match.plan_id,
                                    room_count2.max_reg_rooms_ymd,
                                    room_count2.accept_status_room_count
                                from
                                    room2
                                left join
                                    room_plan_match
                                on
                                    room_plan_match.hotel_cd = room2.hotel_cd
                                    and room_plan_match.room_id = room2.room_id
                                left join
                                    (select
                                        hotel_cd, room_id, max(date_ymd) as max_reg_rooms_ymd, max(accept_status) as accept_status_room_count
                                     from room_count2
                                     where date_ymd >= DATE_FORMAT(NOW(), '%Y-%m-01')
                                     group by hotel_cd, room_id) as room_count2
                                on
                                    room_count2.hotel_cd = room2.hotel_cd
                                    and room_count2.room_id = room2.room_id
                                where
                                    room2.display_status = 1
                                    and room2.active_status = 1
                              SQL;
        
        $plan_relation_rooms = DB::select($relation_rooms_sql, []);
        

        //部屋ごとの最小・最大の料金登録日と、予約受付状態を取得
        $rooms_charge_sql = <<<SQL
                                select
                                    hotel_cd,
                                    room_id,
                                    plan_id,
                                    min(date_ymd) as min_reg_charge_ymd,
                                    max(date_ymd) as max_reg_charge_ymd,
                                    max(accept_status) as charge_accept_status
                                from
                                    charge
                                where
                                    date_ymd >= DATE_FORMAT(NOW(), '%Y-%m-01')
                                group by
                                    hotel_cd, room_id, plan_id
                            SQL;
        
        $rooms_charge = DB::select($rooms_charge_sql, []);
        

        //３つのSQLから取得した配列から、プランに紐づく部屋の配列を作成
        foreach($plan_list as $plan){
            $plan->rooms = [];
            $plan->sale_cnt = 0;
            $plan->non_sale_cnt = 0;

            foreach($plan_relation_rooms as $room){
                if($plan->hotel_cd === $room->hotel_cd
                   && $plan->plan_id === $room->plan_id){

                    $room->max_reg_charge_ymd = null;
                    $room->min_reg_charge_ymd = null;
                    $room->charge_accept_status = null;
                    $room->setting_status = null;
                    $room->is_accept_s_dtm = null;
                    $room->is_accept_e_dtm = null;

                    foreach($rooms_charge as $charge){
                        if($plan->hotel_cd === $charge->hotel_cd
                           && $room->room_id === $charge->room_id
                           && $plan->plan_id === $charge->plan_id){

                            $room->max_reg_charge_ymd = $charge->max_reg_charge_ymd;
                            $room->min_reg_charge_ymd = $charge->min_reg_charge_ymd;
                            $room->charge_accept_status = $charge->charge_accept_status;
                            $room->setting_status = null;

                           }
                    }

                    array_push($plan->rooms, $room);
                    if($room->accept_status === 1){
                        $plan->sale_cnt++;
                    }else{
                        $plan->non_sale_cnt++;
                    }

                }
            }
        }

        return $plan_list;
    }
}