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
       $room_list = DB::table('room2')
                    ->where('room2.hotel_cd', '=', $target_cd)
                    ->where('room2.display_status', '=', 1)
                    ->where('room2.active_status', '=', 1)
                    ->leftJoin('room_akafu_relation', function($join){
                        $join->on('room2.hotel_cd', '=', 'room_akafu_relation.hotel_cd')
                            ->on('room2.room_id', '=', 'room_akafu_relation.room_id');
                    })
                    ->leftJoin('room_count2', function($join){
                        $join->on('room2.hotel_cd', '=', 'room_count2.hotel_cd')
                            ->on('room2.room_id', '=', 'room_count2.room_id')
                            ->where('room_count2.date_ymd', '>=', 'DATE_FORMAT(CURRENT_DATE, "%Y-%m-01")');
                    })
                    ->leftJoin('room_plan_match', function($join){
                        $join->on('room2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                            ->on('room2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('plan', function($join){
                        $join->on('plan.hotel_cd', '=', 'room_plan_match.hotel_cd')
                            ->on('plan.plan_id', '=', 'room_plan_match.plan_id')
                            ->where('plan.display_status', '=', 1)
                            ->where('plan.active_status', '=', 1);
                    })
                    ->leftJoin('room_media2', function($join){
                        $join->on('room_media2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                            ->on('room_media2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('room_spec2', function($join){
                        $join->on('room_spec2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                            ->on('room_spec2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('room_network2', function($join){
                        $join->on('room_network2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                            ->on('room_network2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->get();

        return $room_list;
    }


    /**
     * get registered plans
     * 
     * @param {string} $target_cd
     */
    public function get_plan_list($target_cd)
    {
       $plan_list = DB::table('plan')
                    ->where('plan.hotel_cd', '=', $target_cd)
                    ->where('plan.display_status', '=', 1)
                    ->where('plan.active_status', '=', 1)
                    ->leftJoin('room_plan_match', function($join){
                        $join->on('room_plan_match.hotel_cd', '=', 'plan.hotel_cd')
                             ->on('room_plan_match.plan_id', '=', 'plan.plan_id');
                    })
                    ->leftJoin('hotel_control', function($join){
                        $join->on('room_plan_match.hotel_cd', '=', 'hotel_control.hotel_cd');
                    })
                    ->leftJoin('room2', function($join){
                        $join->on('room2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('room2.room_id', '=', 'room_plan_match.room_id')
                             ->where('room2.display_status', '=', 1)
                             ->where('room2.active_status', '=', 1);
                    })
                    ->leftJoin('room_akafu_relation', function($join){
                        $join->on('room_akafu_relation.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('room_akafu_relation.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('room_count_initial2', function($join){
                        $join->on('room_count_initial2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('room_count_initial2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('extend_switch_plan2', function($join){
                        $join->on('extend_switch_plan2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('extend_switch_plan2.plan_id', '=', 'room_plan_match.plan_id')
                             ->on('extend_switch_plan2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('hotel_camp_plan2', function($join){
                        $join->on('hotel_camp_plan2.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('hotel_camp_plan2.plan_id', '=', 'room_plan_match.plan_id')
                             ->on('hotel_camp_plan2.room_id', '=', 'room_plan_match.room_id');
                    })
                    ->leftJoin('hotel_camp', function($join){
                        $join->on('hotel_camp.camp_cd', '=', 'hotel_camp_plan2.camp_cd');
                    })
                    ->leftJoin('plan_spec', function($join){
                        $join->on('plan_spec.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('plan_spec.plan_id', '=', 'room_plan_match.plan_id');
                    })
                    ->leftJoin('plan_point', function($join){
                        $join->on('plan_point.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('plan_point.plan_id', '=', 'room_plan_match.plan_id');
                    })
                    ->leftJoin('plan_partner_group', function($join){
                        $join->on('plan_partner_group.hotel_cd', '=', 'room_plan_match.hotel_cd')
                             ->on('plan_partner_group.plan_id', '=', 'room_plan_match.plan_id');
                    })
                    ->leftJoin('partner_group_join', function($join){
                        $join->where('partner_group_join.partner_cd', '=', '3015008796')
                             ->on('partner_group_join.partner_group_id', '=', 'plan_partner_group.partner_group_id');                          
                    })
                    ->get();

        return $plan_list;
    }
}