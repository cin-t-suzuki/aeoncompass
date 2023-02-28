<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateInterval;
use DatePeriod;

class Htlsroom2Service
{
    /**
     * Rules for the validation.
     */
    protected $rules = [
        'Room.room_nm'              => 'required|max:40',
        'Room.capacity_min'         => 'required|integer',
        'Room.capacity_max'         => 'required|integer|gt:Room.capacity_min',
        'Room.room_type'            => 'required|integer',
        'Room.floorage_min'         => 'required|integer',
        'Room.floorage_max'         => 'required|integer|gt:Room.floorage_min',
        'Room.floor_unit'           => 'required|integer',
        'element_value_id[1]'       => 'integer',
        'element_value_id[2]'       => 'integer',
        'element_value_id[3]'       => 'integer',
        'Room_NetWork.network'      => 'integer',
        'Room_NetWork.rental'       => 'integer',
        'Room_NetWork.connector'    => 'integer',
        'Room_NetWork.network_note' => 'max:500',
        'rooms_1'                   => 'integer',
        'rooms_2'                   => 'integer',
        'rooms_3'                   => 'integer',
        'rooms_4'                   => 'integer',
        'rooms_5'                   => 'integer',
        'rooms_6'                   => 'integer',
        'rooms_7'                   => 'integer',
        'rooms_8'                   => 'integer',
        'rooms_9'                   => 'integer'
    ];

    protected $validateMessage = [
        'required'              => ':attributeは入力必須です。',
        'integer'               => ':attributeは半角数字で入力してください。',
        'max'                   => ':attributeは:max文字以内で入力してください。'
    ];

    protected $attributeNames = [
        'Room.room_nm'              => '部屋名称',
        'Room.capacity_min'         => '最小適用人数',
        'Room.capacity_max'         => '最大適用人数',
        'Room.room_type'            => '部屋タイプ',
        'Room.floorage_min'         => '最小広さ',
        'Room.floorage_max'         => '最大広さ',
        'Room.floor_unit'           => '広さ単位',
        'element_value_id[1]'       => '風呂',
        'element_value_id[2]'       => 'トイレ',
        'element_value_id[3]'       => '禁煙喫煙',
        'Room_NetWork.network'      => 'ネットワーク環境',
        'Room_NetWork.rental'       => '接続必要機器',
        'Room_NetWork.connector'    => 'コネクタ',
        'Room_NetWork.network_note' => '備考欄',
        'rooms_1'                   => '日曜在庫数',
        'rooms_2'                   => '月曜在庫数',
        'rooms_3'                   => '火曜在庫数',
        'rooms_4'                   => '水曜在庫数',
        'rooms_5'                   => '木曜在庫数',
        'rooms_6'                   => '金曜在庫数',
        'rooms_7'                   => '土曜在庫数',
        'rooms_8'                   => '祝日在庫数',
        'rooms_9'                   => '休前日在庫数'        
    ];

    /**
     * Validate Request parameters
     */
    public function Validate($request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->validateMessage);
        $validator->setAttributeNames($this->attributeNames);
        
        if($validator->fails()){
            $errorMsg = $validator->messages();
            Session::put('validate-error', $errorMsg);
            return false;
        }

        return true;
    }

    /**
     * check which relation the hotel have
     */
    public function check_hotel_status($hotel_cd)
    {
        $is_locked = DB::table('extend_switch')
                       ->where('hotel_cd', '=', $hotel_cd)
                       ->first();
        if(! is_null($is_locked)){
            // 販売延長作業を行っている場合、遷移元画面にリダイレクト
            // return Redirect::route('ctl.htlsroomplan2.index')->withInput($hotel_cd);
        }

        $is_jrset =  DB::table('hotel_jr_entry_status')
                       ->where('hotel_cd', '=', $hotel_cd)
                       ->first();
        
        if(isset($request->roomtype_cd)){
            $is_related_akafu = DB::table('akafu')
                                  ->where('hotel_cd', '=', $hotel_cd)
                                  ->where('room_type', '=', $roomtype_cd)
                                  ->first();

            if(! is_null($is_related_akafu)){
                // 『赤い風船在庫』と既に紐づけがある場合、連動在庫設定画面に遷移
                // return view('ctl.htlssettingakf.index');
            }
        }
    }

    /**
     * register new room
     */
    public function create($request)
    {
        try{
            DB::beginTransaction();

            //部屋情報テーブルへの登録
            $max_order_no = DB::table('room2')
                            ->where('hotel_cd', '=', $request->target_cd)
                            ->where('display_status', '=', 1)
                            ->where('active_status', '=', 1)
                            ->max('order_no');
            
            DB::table('room2')->insert(['hotel_cd'              => $request->target_cd,
                                        'room_id'               => $max_order_no + 1,
                                        'room_nm'               => $request->Room['room_nm'],
                                        'room_type'             => $request->Room['room_type'],
                                        'floorage_min'          => $request->Room['floorage_min'],
                                        'floorage_max'          => $request->Room['floorage_max'],
                                        'floor_unit'            => $request->Room['floor_unit'],
                                        'capacity_min'          => $request->Room['capacity_min'],
                                        'capacity_max'          => $request->Room['capacity_max'],
                                        'active_status'         => 1,
                                        'display_status'        => 1,
                                        'accept_status'         => 1,
                                        'order_no'              => $max_order_no + 1,
                                        'user_side_order_no'    => $max_order_no + 1,
                                        'entry_cd'              => 'admin',
                                        'entry_ts'              => date('Y-m-d H:i:s'),
                                        'modify_cd'             => 'admin',
                                        'modify_ts'             => date('Y-m-d H:i:s')]);
            
            if(isset($request->roomtype_cd)){
                DB::table('room_akafu_relation')->insert(['hotel_cd'    => $request->target_cd,
                                                          'room_id'     => $max_order_no + 1,
                                                          'roomtype_cd' => $request->roomtype_cd,
                                                          'entry_cd'    => 'admin',
                                                          'entry_ts'    => date('Y-m-d H:i:s'),
                                                          'modify_cd'   => 'admin',
                                                          'modify_ts'   => date('Y-m-d H:i:s')]);
            }
            if(isset($request->is_jrset)){
                DB::table('room_jr')->insert(['hotel_cd'        => $request->target_cd,
                                              'room_id'         => $max_order_no + 1,
                                              'room_nm'         => $request->Room['room_nm'],
                                              'accept_status'   => 1,
                                              'active_status'   => 1,
                                              'entry_cd'        => 'admin',
                                              'entry_ts'        => date('Y-m-d H:i:s'),
                                              'modify_cd'       => 'admin',
                                              'modify_ts'       => date('Y-m-d H:i:s')]);
            }

            //部屋のネットワーク環境テーブルへの登録
            if($request->Room_Network['network'] == 9 || $request->Room_Network['network'] == 0){
                $Room_Network = $request->Room_Network;
                $Room_Network['rental'] = null;
                $Room_Network['connector'] = null;
                $request->merge(['Room_Network' => $Room_Network]);
            }
            DB::table('room_network2')->insert(['hotel_cd'  => $request->target_cd,
                                                'room_id'   => $max_order_no + 1,
                                                'network'   => $request->Room_Network['network'],
                                                'rental'    => $request->Room_Network['rental'],
                                                'connector' => $request->Room_Network['connector'],
                                                'entry_cd'  => 'admin',
                                                'entry_ts'  => date('Y-m-d H:i:s'),
                                                'modify_cd' => 'admin',
                                                'modify_ts' => date('Y-m-d H:i:s')]);

            //部屋スペックテーブルへの登録
            for($element_id = 1; $element_id <= 3; $element_id++){
                DB::table('room_spec2')->insert(['hotel_cd'         => $request->target_cd,
                                                 'room_id'          => $max_order_no + 1,
                                                 'element_id'       => $element_id,
                                                 'element_value_id' => $request->element_value_id[$element_id],
                                                 'entry_cd'  => 'admin',
                                                 'entry_ts'  => date('Y-m-d H:i:s'),
                                                 'modify_cd' => 'admin',
                                                 'modify_ts' => date('Y-m-d H:i:s')]);
            }

            //部屋の在庫数テーブルへの登録
            $from_date = new DateTime($request->from_year.'-'.$request->from_month.'-'.$request->from_day);
            $to_date = new DateTime($request->to_year.'-'.$request->to_month.'-'.$request->to_day);
            $interval = new DateInterval('P1D');
            $period = new DatePeriod($from_date, $interval, $to_date);
            $stock_week = [$request->rooms_1,
                           $request->rooms_2,
                           $request->rooms_3,
                           $request->rooms_4,
                           $request->rooms_5,
                           $request->rooms_6,
                           $request->rooms_7,
                           $request->rooms_8,
                           $request->rooms_9];
            
            foreach($period as $date_ymd){                            
                $is_holiday = DB::table('mast_holiday')
                                ->where('holiday', '=', $date_ymd)
                                ->first();
                $next_date_ymd = $date_ymd->modify('+1 day');
                $is_before_holiday = DB::table('mast_holiday')
                                       ->where('holiday', '=', $next_date_ymd)
                                       ->first();
                $date_ymd->modify('-1 day');

                if(! is_null($is_holiday)){
                    DB::table('room_count2')->insert(['hotel_cd'        => $request->target_cd,
                                                      'room_id'         => $max_order_no + 1,
                                                      'date_ymd'        => $date_ymd,
                                                      'rooms'           => $stock_week[7],
                                                      'reserve_rooms'   => 0,
                                                      'entry_cd'        => 'admin',
                                                      'entry_ts'        => date('Y-m-d H:i:s'),
                                                      'modify_cd'       => 'admin',
                                                      'modify_ts'       => date('Y-m-d H:i:s'),
                                                      'accept_status'   => 1]);
                }elseif(! is_null($is_before_holiday)){
                    DB::table('room_count2')->insert(['hotel_cd'        => $request->target_cd,
                                                      'room_id'         => $max_order_no + 1,
                                                      'date_ymd'        => $date_ymd,
                                                      'rooms'           => $stock_week[8],
                                                      'reserve_rooms'   => 0,
                                                      'entry_cd'        => 'admin',
                                                      'entry_ts'        => date('Y-m-d H:i:s'),
                                                      'modify_cd'       => 'admin',
                                                      'modify_ts'       => date('Y-m-d H:i:s'),
                                                      'accept_status'   => 1]);
                }else{
                    DB::table('room_count2')->insert(['hotel_cd'        => $request->target_cd,
                                                      'room_id'         => $max_order_no + 1,
                                                      'date_ymd'        => $date_ymd,
                                                      'rooms'           => $stock_week[$date_ymd->format('w')],
                                                      'reserve_rooms'   => 0,
                                                      'entry_cd'        => 'admin',
                                                      'entry_ts'        => date('Y-m-d H:i:s'),
                                                      'modify_cd'       => 'admin',
                                                      'modify_ts'       => date('Y-m-d H:i:s'),
                                                      'accept_status'   => 1]);
                }  
            }

            DB::commit();
            $room = $this->get_room_status($request, $max_order_no + 1);

            return $room;

        }catch(\Exception $e){
            DB::rollback();
            Log::error('部屋新規登録失敗'.$e->getMessage());
            return false;
        }
    }

    /**
     * get room status after registration hotel room
     */
    public function get_room_status($request, $room_id){
        $room = DB::table('room2')
                  ->leftJoin('room_network2', function($join){
                        $join->on('room_network2.hotel_cd', '=', 'room2.hotel_cd')
                             ->on('room_network2.room_id', '=', 'room2.room_id'); 
                    })
                  ->where('room2.hotel_cd', '=', $request->target_cd)
                  ->where('room2.room_id', '=', $room_id)
                  ->first();

        $plan_elements = DB::table('room_spec2')
                           ->leftJoin('mast_plan_element', function($join){
                                $join->on('mast_plan_element.element_id', '=', 'room_spec2.element_id');
                             })
                           ->leftJoin('mast_plan_element_value', function($join){
                                $join->on('mast_plan_element_value.element_id', '=', 'room_spec2.element_id')
                                     ->on('mast_plan_element_value.element_value_id', '=', 'room_spec2.element_value_id');
                             })
                           ->where('room_spec2.hotel_cd', '=', $request->target_cd)
                           ->where('room_spec2.room_id', '=', $room_id)
                           ->get();
                           
        $room->plan_elements = $plan_elements;
        $room->from_year = $request->from_year;
        $room->from_month = $request->from_month;
        $room->from_day = $request->from_day;
        $room->to_year = $request->to_year;
        $room->to_month = $request->to_month;
        $room->to_day = $request->to_day;
        $room->rooms_1 = $request->rooms_1;
        $room->rooms_2 = $request->rooms_2;
        $room->rooms_3 = $request->rooms_3;
        $room->rooms_4 = $request->rooms_4;
        $room->rooms_5 = $request->rooms_5;
        $room->rooms_6 = $request->rooms_6;
        $room->rooms_7 = $request->rooms_7;
        $room->rooms_8 = $request->rooms_8;
        $room->rooms_9 = $request->rooms_9;

        return $room;
    }

    /**
     * update registered hotel room
     */
    public function update($request)
    {
        try{
            DB::beginTransaction();

            DB::table('room2')
              ->where('hotel_cd', '=', $request->target_cd)
              ->where('room_id', '=', $request->room_id)
              ->update(['room_nm'       => $request->Room['room_nm'],
                        'room_type'     => $request->Room['room_type'],
                        'floorage_min'  => $request->Room['floorage_min'],
                        'floorage_max'  => $request->Room['floorage_max'],
                        'floor_unit'    => $request->Room['floor_unit'],
                        'capacity_min'  => $request->Room['capacity_min'],
                        'capacity_max'  => $request->Room['capacity_max'],
                        'modify_cd'     => 'admin',
                        'modify_ts'     => date('Y-m-d H:i:s')]);
            
            if($request->Room_Network['network'] == 9 ||$request->Room_Network['network'] == 0){
                $Room_Network = $request->Room_Network;
                $Room_Network['rental'] = null;
                $Room_Network['connecter'] = null;
                $request->merge(['Room_Network' => $Room_Network]);
            }            
            DB::table('room_network2')
              ->where('hotel_cd', '=', $request->target_cd)
              ->where('room_id', '=', $request->room_id)
              ->update(['network'   => $request->Room_Network['network'],
                        'rental'    => $request->Room_Network['rental'],
                        'connector' => $request->Room_Network['connecter'],
                        'modify_cd' => 'admin',
                        'modify_ts' => date('Y-m-d H:i:s')]);

            for($element_id = 1; $element_id <= 3; $element_id++){
                DB::table('room_spec2')
                  ->where('hotel_cd', '=', $request->target_cd)
                  ->where('room_id', '=', $request->room_id)
                  ->where('element_id', '=', $element_id)
                  ->update(['element_value_id' => $request->element_value_id[$element_id],
                            'modify_cd'        => 'admin',
                            'modify_ts'        => date('Y-m-d H:i:s')]);
            }

            DB::commit();
            return true;

        }catch(\Exception $e){
            DB::rollback();
            Log::error('部屋編集失敗'.$e->getMessage());
            return false;
        }
    }

}