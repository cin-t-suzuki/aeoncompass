<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\Attention;

class BrattentionController extends _commonController
{
    use Traits;

    //======================================================================
    // 一覧
    //======================================================================
    public function list()
    {
        //親テーブルの配列の取得  //modify_tsはブレード側でformat調整
        $a_rows = [];
        $s_sql =
            <<<SQL
		select attention_id,
					start_date,
					title,
					display_status,
					display_flag,
					modify_ts
			from top_attention
			order by display_flag desc,start_date desc
SQL;

        $a_rows = DB::select($s_sql, []);

        //子テーブルの配列の取得
        $a_child_rows = [];
        $s_sql =
            <<<SQL
			select attention_id,
					word,
					url
				from top_attention_detail 
				order by attention_id,order_no asc
SQL;

        $a_child_rows = DB::select($s_sql, []);

        //親テーブルのattention_idと同じattention_idの子テーブルのwordを配列に入れる
        foreach ($a_rows as $parent_key => $parent_value) {
            $a_rows[$parent_key]->child_value = []; //追記
            foreach ($a_child_rows as $child_key => $child_value) {
                if ($a_rows[$parent_key]->attention_id == $child_value->attention_id) {
                    if ($a_rows[$parent_key]->display_status > count($a_rows[$parent_key]->child_value)) {
                        $set_value = array(
                            'word' => $child_value->word,
                            'url'  => $child_value->url,
                        );
                        $a_rows[$parent_key]->child_value[] = $set_value;
                    }
                }
            }
        }

        $s_sql = //where ROWNUM <= 1の書き替えはlimit 1で合っているか
            <<<SQL
			select attention_id
			from (
				select attention_id,
						start_date,
						display_flag
				from top_attention
				where display_flag = 1
				and   start_date <= now()
				order by start_date desc
			) as top_attention
			limit 1
SQL;

        $now_display_attention = DB::select($s_sql, []);


        // redirect時のguideメッセージを取得
        if (session()->has('guide')) {
            $guide = session()->pull('guide');
            $this->addGuideMessage($guide);
        }

        // データを ビューにセット
        $this->addViewData("message_list", $a_rows);
        $this->addViewData("now_display_attention", $now_display_attention[0]);

        // ビューを表示
        return view("ctl.brattention.list", $this->getViewData());
    }

    //======================================================================
    // 新規入力
    //======================================================================
    public function new()
    {

        $attentionModel = new Attention();

        // データを取得
        //別アクションからのredirectの場合は渡されたデータを反映する
        if (session()->has('return_data')) {
            $requestAttention = session()->pull('return_data');
            if (session()->has('guide')) {
                $guide = session()->pull('guide');
                $this->addGuideMessage($guide);
            }
            if (session()->has('error')) {
                $error = session()->pull('error');
                $this->addErrorMessageArray($error);
            }
        } else {
            //それ以外（初期表示）
            $requestAttention = Request::all();
        }

        $accept_header_ymd_selecter  = $attentionModel->makeYmdSelecter();
        $title = $requestAttention['title'] ?? null; //null追記

        if (!isset($title)) {
            $form_params['title'] = 'BR掲載情報の定期更新【登録日:' . date('n') . '月' . date('j') . '日】';
            $form_params['start_date_year']  = date('Y');
            $form_params['start_date_month'] = date('n');
            $form_params['start_date_day']   = date('j');
            $form_params['start_set_array'] = $attentionModel->makeStartArray();
        } else {
            $form_params['title'] = $requestAttention['title'];
            $form_params['start_date_year'] = $requestAttention['start_date_year'];
            $form_params['start_date_month'] = $requestAttention['start_date_month'];
            $form_params['start_date_day'] = $requestAttention['start_date_day'];
            $form_params['display_status'] = $requestAttention['display_status'];
            $word = $requestAttention['word'] ?? []; //[]追記でいいか？
            $url = $requestAttention['url'] ?? []; //同上
            $jwest_word = $requestAttention['jwest_word'] ?? []; //同上
            $jwest_url = $requestAttention['jwest_url'] ?? []; //同上
            foreach ($word as $key => $value) {
                $a_start_set_array[] = array(
                    "word" => $value,
                    "url" => $url[$key],
                    "jwest_word" => $jwest_word[$key],
                    "jwest_url" => $jwest_url[$key],
                );
            }
            $form_params['start_set_array'] = $a_start_set_array;
            $form_params['note'] = $requestAttention['note'] ?? null; //null追記
        }


        // データを ビューにセット
        $this->addViewData("accept_header_ymd_selecter", $accept_header_ymd_selecter);
        $this->addViewData("form_params", $form_params);

        // ビューを表示
        return view("ctl.brattention.new", $this->getViewData());
    }

    //======================================================================
    // 編集
    //======================================================================
    public function edit()
    {

        // データを取得
        $requestAttention = Request::all();
        $send_edit = (int)($requestAttention['send_edit'] ?? null);
        $attentionModel = new Attention();

        if ($send_edit == 1) {
            $set_param['attention_id'] = $requestAttention['attention_id'];
            $s_sql =
                <<<SQL
				select attention_id,
					   title,
					   start_date,
					   display_status,
					   note
				from top_attention
				where attention_id = :attention_id
SQL;

            $a_main_attention = DB::select($s_sql, $set_param);

            $a_main_param =  json_decode(json_encode($a_main_attention[0]), true); //json~追記しないとviewでエラー
            $a_main_param['start_date_year'] = date('Y', strtotime($a_main_param['start_date']));
            $a_main_param['start_date_month'] = date('m', strtotime($a_main_param['start_date']));
            $a_main_param['start_date_day'] = date('d', strtotime($a_main_param['start_date']));

            $s_sql =
                <<<SQL
				select attention_detail_id,
					   order_no,					
					   word,
					   url,
					   jwest_word,
					   jwest_url
				from top_attention_detail
				where attention_id = :attention_id
				order by order_no
SQL;

            $a_sub_param = DB::select($s_sql, $set_param);

            $accept_header_ymd_selecter  = $attentionModel->makeYmdSelecter();
            $form_detail_params['start_set_array'] = json_decode(json_encode($a_sub_param), true); //json~追記しないとviewでエラー

            // データを ビューにセット
            $this->addViewData("accept_header_ymd_selecter", $accept_header_ymd_selecter);
            $this->addViewData("form_params", $a_main_param);
            $this->addViewData("form_detail_params", $form_detail_params);
        } else {
            // データを取得
            //別アクションからのredirectの場合は渡されたデータを反映する
            if (session()->has('return_data')) {
                $requestAttention = session()->pull('return_data');
                if (session()->has('guide')) {
                    $guide = session()->pull('guide');
                    $this->addGuideMessage($guide);
                }
                if (session()->has('error')) {
                    $error = session()->pull('error');
                    $this->addErrorMessageArray($error);
                }
            }

            $accept_header_ymd_selecter  = $attentionModel->makeYmdSelecter();
            $form_params['attention_id'] = $requestAttention['attention_id'];
            $form_params['title'] = $requestAttention['title'];
            $form_params['start_date_year'] = $requestAttention['start_date_year'];
            $form_params['start_date_month'] = $requestAttention['start_date_month'];
            $form_params['start_date_day'] = $requestAttention['start_date_day'];
            $form_params['display_status'] = $requestAttention['display_status'];
            $form_params['note'] = $requestAttention['note'];
            $attention_detail_id = $requestAttention['attention_detail_id'];
            $order_no = $requestAttention['order_no'];
            $word = $requestAttention['word'];
            $url = $requestAttention['url'];
            $jwest_word = $requestAttention['jwest_word'];
            $jwest_url = $requestAttention['jwest_url'];
            foreach ($word as $key => $value) {
                $a_start_set_array[] = array(
                    "attention_detail_id" => $attention_detail_id[$key],
                    "order_no" => $order_no[$key],
                    "word" => $value,
                    "url" => $url[$key],
                    "jwest_word" => $jwest_word[$key],
                    "jwest_url" => $jwest_url[$key],
                );
            }
            $form_detail_params['start_set_array'] = $a_start_set_array;

            // データを ビューにセット
            $this->addViewData("accept_header_ymd_selecter", $accept_header_ymd_selecter);
            $this->addViewData("form_params", $form_params);
            $this->addViewData("form_detail_params", $form_detail_params);
        }

        // ビューを表示
        return view("ctl.brattention.edit", $this->getViewData());
    }

    //======================================================================
    // update
    //======================================================================
    public function update()
    {

        $requestAttention = Request::all();

        //entry,modify設定追記
        $attentionModel = new Attention();
        $attentionModel->setUpdateCommonColumn($requestAttention);
        $modify_cd = $requestAttention['modify_cd'];

        $insertCheck_result = $attentionModel->insertCheck($requestAttention);
        if ($insertCheck_result !== true) {
            //editへ戻る
            session()->put('error', $insertCheck_result);
            session()->put('return_data', $requestAttention);
            return redirect()->route('ctl.brattention.edit');
        }

        $start_date_year = $requestAttention['start_date_year'];
        $start_date_month = $requestAttention['start_date_month'];
        $start_date_day = $requestAttention['start_date_day'];

        try {
            $reg_date = date('Y-m-d', strtotime($start_date_year . '-' .
                $start_date_month . '-' .
                $start_date_day));

            $s_sql =
                <<<SQL
					update top_attention
					set start_date = :start_date,
						display_status = :display_status,
						title = :title,
						note = :note,
						modify_cd = :modify_cd,
						modify_ts = now()
					where attention_id = :attention_id
SQL;

            $a_conditions = [];
            $a_conditions['attention_id']       = $requestAttention['attention_id'];
            $a_conditions['start_date']         = $reg_date;
            $a_conditions['display_status']     = $requestAttention['display_status'];
            $a_conditions['title']    = $requestAttention['title'];
            $a_conditions['note']     =     $requestAttention['note'];
            $a_conditions['modify_cd']             = $modify_cd;

            DB::update($s_sql, $a_conditions);

            $s_sql =
                <<<SQL
					update top_attention_detail
					set word = :word,
		            	url = :url,
						jwest_word = :jwest_word,
						jwest_url = :jwest_url,
						modify_cd = :modify_cd,
						modify_ts = now()
					where attention_detail_id = :attention_detail_id
SQL;

            $attention_detail_id = $requestAttention['attention_detail_id'];
            $word = $requestAttention['word'];
            $url = $requestAttention['url'];
            $jwest_word = $requestAttention['jwest_word'];
            $jwest_url = $requestAttention['jwest_url'];

            foreach ($word as $key => $value) {
                $a_start_set_array[] = array(
                    "attention_detail_id" => $attention_detail_id[$key],
                    "word" => $value,
                    "url" => $url[$key],
                    "jwest_word" => $jwest_word[$key],
                    "jwest_url" => $jwest_url[$key],
                    "modify_cd"    => $modify_cd,
                );

                $a_detail_conditions = $a_start_set_array[$key];
                DB::update($s_sql, $a_detail_conditions);
            }

            //listへ
            session()->put('return_data', $requestAttention);
            return redirect()->route('ctl.brattention.list');
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 登録
    //======================================================================
    public function create()
    {

        $requestAttention = Request::all();

        $start_date_year = $requestAttention['start_date_year'];
        $start_date_month = $requestAttention['start_date_month'];
        $start_date_day = $requestAttention['start_date_day'];

        //entry,modify設定追記
        $attentionModel = new Attention();
        $attentionModel->setInsertCommonColumn($requestAttention);
        $modify_cd = $requestAttention['modify_cd'];
        $entry_cd = $requestAttention['entry_cd'];

        $insertCheck_result = $attentionModel->insertCheck($requestAttention);
        if ($insertCheck_result !== true) {
            //newへ戻る
            session()->put('error', $insertCheck_result);
            session()->put('return_data', $requestAttention);
            return redirect()->route('ctl.brattention.new');
        }

        //nextvalの取得、これで大丈夫？（下非表示は元ソース）
        $at_seq_id  = $attentionModel->incrementSequence('at_seq_id');
        $attention_seq_id = $at_seq_id->val; //これで値は問題なくとれているが、intelephenseエラー？どう直せばいい？
        //$s_sql =
        // <<<SQL
        //select attention_seq.nextval as nextval
        //from dual
        // SQL;

        //$at_seq_id = DB::select($s_sql,array());
        // $attention_seq_id = $at_seq_id[0]['nextval'];

        try {
            $s_sql =
                <<< SQL
						insert into top_attention(
									  attention_id,
									start_date,
									display_status,
									display_flag,
									title,
									note,
									entry_cd,
									entry_ts,
									modify_cd,
									modify_ts
						)
					   values(
									   :attention_id,
									:start_date,
									:display_status,
									1,
									:title,
									   :note,
									   :entry_cd,
									now(),
									:modify_cd,
									now()
						)
SQL;

            $reg_date = date('Y-m-d', strtotime($start_date_year . '-' .
                $start_date_month . '-' .
                $start_date_day));

            $a_conditions = [];
            $a_conditions['attention_id']       = $attention_seq_id;
            $a_conditions['start_date']         = $reg_date;
            $a_conditions['display_status']     = $requestAttention['display_status'];
            $a_conditions['title']              = $requestAttention['title'];
            $a_conditions['note']                 = $requestAttention['note'];
            $a_conditions['entry_cd']             = $entry_cd;
            $a_conditions['modify_cd']             = $modify_cd;

            $at_seq_id = DB::update($s_sql, $a_conditions);

            $s_sql =
                <<< SQL
						insert into top_attention_detail(
									attention_detail_id,
									  attention_id,
									order_no,
									word,
									url,
									jwest_word,
									jwest_url,
									entry_cd,
									entry_ts,
									modify_cd,
									modify_ts
						)
					   values(
									:attention_detail_id, 
									:attention_id,
									:order_no,
									:word,
									:url,
									   :jwest_word,
									   :jwest_url,
									   :entry_cd,
									now(),
									:modify_cd,
									now()
						)
SQL;

            $word = $requestAttention['word'] ?? []; //[]追記でいいか？
            $url = $requestAttention['url'] ?? []; //同上
            $jwest_word = $requestAttention['jwest_word'] ?? []; //同上
            $jwest_url = $requestAttention['jwest_url'] ?? []; //同上

            foreach ($word as $key => $value) {
                //nextvalの取得、これで大丈夫？
                $at_detail_seq_id  = $attentionModel->incrementSequence('at_detail_seq_id');
                $attention_detail_seq_id = $at_detail_seq_id->val; //これで値は問題なくとれているが、intelephenseエラー？どう直せばいい？

                $a_start_set_array[] = array(
                    "attention_detail_id" => $attention_detail_seq_id, //追記,SQL文内変更
                    "attention_id" => $attention_seq_id,
                    "order_no" => $key + 1,
                    "word" => $value,
                    "url" => $url[$key],
                    "jwest_word" => $jwest_word[$key],
                    "jwest_url" => $jwest_url[$key],
                    "entry_cd"             => $entry_cd,
                    "modify_cd"             => $modify_cd,
                );
                $a_detail_conditions = $a_start_set_array[$key];
                DB::update($s_sql, $a_detail_conditions);
            }
            //listへ
            session()->put('return_data', $requestAttention);
            return redirect()->route('ctl.brattention.list');
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 表示変更
    //======================================================================
    public function change()
    {
        try {
            $requestAttention = Request::all();
            $a_conditions = [];
            $s_sql =
                <<< SQL
						update top_attention
						set display_flag = :display_flag
						where attention_id = :attention_id
SQL;
            $display_flag = $requestAttention['display_flag'];
            $attention_id = $requestAttention['attention_id'];
            $title = $requestAttention['title'];

            if ($display_flag == 0) {
                $a_conditions['attention_id']       = $attention_id;
                $a_conditions['display_flag']     = 1;
                $guide = $title . 'を再表示に変更しました';
            } else {
                $a_conditions['attention_id']       = $attention_id;
                $a_conditions['display_flag']     = 0;
                $guide = $title . 'を非表示に変更しました';
            }
            DB::update($s_sql, $a_conditions);

            //listへ
            session()->put('return_data', $requestAttention);
            session()->put('guide', $guide);
            return redirect()->route('ctl.brattention.list');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
