<?php

namespace App\Models;
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Models\Reserve;
use Exception;

/**
 * パートナーマスタ
 */
class Voice extends CommonDBModel
{
    use Traits;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
    }


    // 宿泊体験の検索
    //
    // Core->_s_partner_cd 提携先コード
    // aa_conditions   検索条件を設定
    //   hotel_cd      施設コード
    //   keyword       キーワード
    //   not_limit_dtm true:30ヶ月の有効期限内を取得 false:有効期限外も取得
    //   not_status    true:有効な状態のみを取得 false:削除状態も取得
    //   experience_dtm
    //     after  =       日付 > 日付以降の投稿 YYYY-MM-DD HH:MM:SS
    //     before =       日付 > 日付以前の投稿 YYYY-MM-DD HH:MM:SS
    //   reply_dtm
    //     after  =       日付 > 日付以降の投稿 YYYY-MM-DD HH:MM:SS
    //     before =       日付 > 日付以前の投稿 YYYY-MM-DD HH:MM:SS
    // aa_offsets
    //   page ページ
    //   size レコード数(1から) ページ数を指定した場合必須
    //
    // example
    //   find(array('hotel_cd' => '2000040001'))
    //   find(array('hotel_cd' => '2000040001'), array('page'  => 1, 'size' => 20))
    public function voiceLists($aa_conditions, $aa_offsets = null)
    {
        // 返答日があり、投稿日が無い場合のみ返答日時優先で宿泊体験の検索（キーワードは返信内容に当てはまるもの）
        if (!$this->is_empty($aa_conditions['reply_dtm'] ?? null) && $this->is_empty($aa_conditions['experience_dtm'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
            $a_result = $this->voiceListsReply($aa_conditions, $aa_offsets);
        } else {
            // 投稿日が検索条件に含まれていれば投稿日優先で宿泊体験の検索（キーワードは投稿内容に当てはまるもの、ただし返答日もチェックされていればどちらかに当てはまるもの）
            $a_result = $this->voiceListsPost($aa_conditions, $aa_offsets);
        }

        $reserveModel = new Reserve();

        foreach ($a_result['values'] as $key => $value) {
            $reserveModel->reserves(['reserve_cd' => $value->reserve_cd]);

            $a_result['values'][$key]->reserve = $reserveModel->getReserveRooms(['include_member' => true]);
        }

        return $a_result;
    }


    // 宿泊体験の検索(返答日時優先検索)
    //
    // Core->_s_partner_cd 提携先コード
    // aa_conditions   検索条件を設定
    //   hotel_cd      施設コード
    //   keyword       キーワード
    //   not_limit_dtm true:30ヶ月の有効期限内を取得 false:有効期限外も取得
    //   not_status    true:有効な状態のみを取得 false:削除状態も取得
    //   experience_dtm
    //     after  =       日付 > 日付以降の投稿 YYYY-MM-DD HH:MM:SS
    //     before =       日付 > 日付以前の投稿 YYYY-MM-DD HH:MM:SS
    //   reply_dtm
    //     after  =       日付 > 日付以降の投稿 YYYY-MM-DD HH:MM:SS
    //     before =       日付 > 日付以前の投稿 YYYY-MM-DD HH:MM:SS
    // aa_offsets
    //   page ページ
    //   size レコード数(1から) ページ数を指定した場合必須
    //
    // example
    //   find(array('hotel_cd' => '2000040001'))
    //   find(array('hotel_cd' => '2000040001'), array('page'  => 1, 'size' => 20))
    private function voiceListsReply($aa_conditions, $aa_offsets = null)
    {
        try {
            $coreModel = new Core();

            //コントローラから移植（newした後でないとリセットされるため）問題ないか？
            $coreModel->setPartnerCd('0000000000');

            if ($this->is_empty($coreModel->s_partner_cd)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）
                throw new Exception('提携先コードを設定してください。 partner_cd => xxxxxxxxxx');
            }

            //削除でいいか？
            // // 条件を定義
            // // ベストリザーブホテルを制御
            // if (!$this->in_inside()) {
            //     $s_br_hotel = "and   hotel_status.hotel_cd >= '2000050000'";
            // }

            //初期化
            $a_station['select'] = '';
            $a_landmark['select'] = '';
            $s_hotel_cd = '';
            $a_station['where'] = '';
            $a_landmark['where'] = '';
            $s_br_hotel = ''; //定義の方が削除でいいなら削除
            $s_pref_id = '';
            $s_city_id = '';
            $s_ward_id = '';
            $s_status = '';
            $s_limit_dtm = '';
            $s_after_experience_dtm = '';
            $s_before_experience_dtm = '';
            $s_after_reply_dtm = '';
            $s_before_reply_dtm = '';
            $a_keyword['searchtitle']   = '';
            $a_keyword['searchexplain'] = '';
            $a_keyword['searchanswer']  = '';


            // 施設コードを設定
            if (!$this->is_empty($aa_conditions['hotel_cd'])) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）
                $s_sql =
                <<<SQL
					select	hotel_relation.relation_cd
					from	hotel_relation,
						(
							select	hotel_cd
							from	hotel_relation
							where	hotel_relation.relation_cd = :hotel_cd1
						) q1
					where	hotel_relation.hotel_cd = q1.hotel_cd
						and	hotel_relation.relation_cd != :hotel_cd2
SQL;
                // データの取得
                $htl_conditions['hotel_cd1'] = $aa_conditions['hotel_cd'];
                $htl_conditions['hotel_cd2'] = $aa_conditions['hotel_cd']; //invaliedparameter対応,a_conditionへの代入ではバッティングするため変数名変更
                $a_row = DB::select($s_sql, $htl_conditions);

                $s_hotel_cd = 'and	hotel_status.hotel_cd in (';

                for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                    $s_hotel_cd .= ':hotel_cd' . $n_cnt . ', ';
                    $a_conditions['hotel_cd' . $n_cnt] = $a_row[$n_cnt]['relation_cd'];
                }
                $s_hotel_cd .= ":hotel_cd)";
                $a_conditions['hotel_cd'] = $aa_conditions['hotel_cd'];
            }

            // 有効期限を設定
            if ($aa_conditions['not_limit_dtm'] ?? true) { //nvl→??でいいか？
                // $s_limit_dtm = ' and sysdate < voice_stay.limit_dtm(+)'; //書き換えあっているか？
                $s_limit_dtm = '	and	now() < voice_stay.limit_dtm'; //代入先ですでにonがある
            }

            // 状態を設定
            if ($aa_conditions['not_status'] ?? true) { //nvl→??でいいか？
                // $s_status = '    and  voice_stay.status(+) = 0'; //書き換えあっているか？
                $s_status = '	and	voice_stay.status = 0'; //代入先ですでにonがある
            }

            // 都道府県IDを設定
            if (!$this->is_empty($aa_conditions['pref_id'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_pref_id = '	and	hotel.pref_id = :pref_id';
                $a_conditions['pref_id'] = $aa_conditions['pref_id'];
            }

            // 市IDを設定
            if (!$this->is_empty($aa_conditions['city_id'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_city_id = '	and	hotel.city_id = :city_id';
                $a_conditions['city_id'] = $aa_conditions['city_id'];
            }

            // 区IDを設定
            if (!$this->is_empty($aa_conditions['ward_id'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_ward_id = '	and	hotel.ward_id = :ward_id';
                $a_conditions['ward_id'] = $aa_conditions['ward_id'];
            }

            // 駅IDを設定
            if (!$this->is_empty($aa_conditions['station_id'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $a_station['where']  = '	and	hotel_status.hotel_cd = hotel_station.hotel_cd';
                $a_station['select'] =
                <<<SQL
						,(
							select	hotel_station.hotel_cd
							from	hotel_station
							where	hotel_station.station_id = :station_id
						) hotel_station
SQL;
                $a_conditions['station_id'] = $aa_conditions['station_id'];

                // 路線IDを設定 （駅が決まっていれば路線を見る必要はない）
            } elseif (!$this->is_empty($aa_conditions['route_id'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $a_station['where']  = '	and	hotel_status.hotel_cd = hotel_station.hotel_cd';
                $a_station['select'] =
                <<<SQL
						,(
							select	hotel_station.hotel_cd
							from	hotel_station,
								(
									select	mast_station.station_id
									from	mast_station
									where	mast_station.route_id = :route_id
								) mast_station
							where	hotel_station.station_id = mast_station.station_id
						) hotel_station
SQL;
                $a_conditions['route_id'] = $aa_conditions['route_id'];
            }

            // ランドマークIDを設定
            if (!$this->is_empty($aa_conditions['landmark_id'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $a_landmark['where']  = '	and	hotel_status.hotel_cd = hotel_landmark.hotel_cd';
                $a_landmark['select'] =
                <<<SQL
						,(
							select	hotel_landmark.hotel_cd
							from	hotel_landmark
							where	hotel_landmark.landmark_id = :landmark_id
						) hotel_landmark
SQL;
                $a_conditions['landmark_id'] = $aa_conditions['landmark_id'];
            }

            // キーワードを取得(スペース、全角スペースで分割)
            $a_keywords = preg_split('/ |　/', $aa_conditions['keywords']);

            // ソート用の値の初期値設定
            $a_keyword['sortanswer']  = '	(';
            $a_keyword['sortexplain'] = '	(';
            // ソート用の値を整形開始（キーワードがあれば必ず通る）
            for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                if (strlen($a_keywords[$n_cnt]) != 0) {
                    $a_keyword['sortexplain'] .= " case when instr( voice_stay.title, :sort" . $n_cnt  . '1' . ") + instr(voice_stay.explain, :sort" . $n_cnt  . '2' . ") = 0 then 0 else 1 end ||"; //decodeから書き換え合っているか？（下も）
                    $a_keyword['sortanswer']  .= " case when instr(voice_reply.answer, :sort" . $n_cnt  . '3' . ") = 0 then 0 else 1 end ||";

                    $a_conditions['sort' . $n_cnt . '1']   = $a_keywords[$n_cnt];
                    $a_conditions['sort' . $n_cnt . '2']   = $a_keywords[$n_cnt];
                    $a_conditions['sort' . $n_cnt . '3']   = $a_keywords[$n_cnt]; //invalied parameter対策
                    $b_buf = 'キーワードが存在';
                }
            }

            // 投稿日と返答日が空の場合
            if (($this->is_empty($aa_conditions['experience_dtm'] ?? null)) && ($this->is_empty($aa_conditions['reply_dtm'] ?? null))) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                //  宿泊体験用キーワード整形
                $a_keyword['searchexplain']  = '	or	';
                $a_keyword['searchtitle']  = '	and	(';
                for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                    if (strlen($a_keywords[$n_cnt]) != 0) {
                        $a_keyword['searchtitle']   .= " voice_stay.title like :search" . $n_cnt . '1' . " or";
                        $a_keyword['searchexplain'] .= " voice_stay.explain like :search" . $n_cnt . '2' . " or";

                        $a_conditions['search' . $n_cnt . '1'] = '%' . $a_keywords[$n_cnt] . '%';
                        $a_conditions['search' . $n_cnt . '2'] = '%' . $a_keywords[$n_cnt] . '%';
                    }
                }

                //  宿泊体験返答用キーワード整形
                $a_keyword['searchanswer']  = '	or	';
                for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                    if (strlen($a_keywords[$n_cnt]) != 0) {
                        $a_keyword['searchanswer'] .= " q6.answer like :search" . '3' . $n_cnt . " or";

                        $a_conditions['search' . $n_cnt . '3'] = '%' . $a_keywords[$n_cnt] . '%';
                    }
                }

                // 投稿日若しくは返答日が引数で渡されていなければ
            } else {
                // 投稿日が存在すれば検索用キーワード整形開始
                if ((!$this->is_empty($aa_conditions['experience_dtm'] ?? null))) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                    //  宿泊体験用キーワード整形
                    $a_keyword['searchexplain'] = '	or	';
                    $a_keyword['searchtitle']   = '	and	(';
                    for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                        if (strlen($a_keywords[$n_cnt]) != 0) {
                            $a_keyword['searchtitle']   .= " voice_stay.title like :search" . $n_cnt . '1' . " or";
                            $a_keyword['searchexplain'] .= " voice_stay.explain like :search" . $n_cnt . '2' . " or";

                            $a_conditions['search' . $n_cnt . '1'] = '%' . $a_keywords[$n_cnt] . '%';
                            $a_conditions['search' . $n_cnt . '2'] = '%' . $a_keywords[$n_cnt] . '%';
                        }
                    }
                }

                // 返答日が存在すれば検索用キーワード整形開始
                if ((!$this->is_empty($aa_conditions['reply_dtm'] ?? null))) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                    //  宿泊体験用キーワードが存在しなければ
                    if ($this->is_empty($a_keyword['searchexplain'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                        $a_keyword['searchanswer']  = '	and	(';
                    } else {
                        $a_keyword['searchanswer']  = '	or	';
                    }

                    //  宿泊体験返答用キーワード整形
                    for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                        if (strlen($a_keywords[$n_cnt]) != 0) {
                            $a_keyword['searchanswer'] .= " q6.answer like :search" . $n_cnt . '3' . " or";

                            $a_conditions['search' . $n_cnt . '3'] = '%' . $a_keywords[$n_cnt] . '%';
                        }
                    }
                }
            }

            // キーワードが存在しなければ
            if ($this->is_empty($b_buf ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                // 投稿日、返答日共にキーワード検索が存在しないのでnullを代入
                $a_keyword['searchtitle']   = null;
                $a_keyword['searchexplain'] = null;
                $a_keyword['searchanswer']  = null;

                // ソートのデフォルト値代入
                $a_keyword['sortexplain']   = 'null as explain_order_no,';
                $a_keyword['sortanswer']    = 'null as answer_order_no,';

                // キーワードが存在すれば閉じ括弧の整形を行う
            } else {
                // 閉じ括弧の整形
                // 投稿日のキーワード検索が存在すれば整形終了
                if ($this->is_empty($a_keyword['searchanswer'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                    $a_keyword['searchtitle']   = substr($a_keyword['searchtitle'], 0, -2);
                    $a_keyword['searchexplain'] = substr($a_keyword['searchexplain'], 0, -2) . ')';

                    // 返答日のキーワード検索が存在すれば整形終了
                } elseif ($this->is_empty($a_keyword['searchexplain'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                    $a_keyword['searchanswer']  = substr($a_keyword['searchanswer'], 0, -2) . ')';

                    // 投稿日、返答日共にキーワード検索が存在すれば整形終了
                } else {
                    $a_keyword['searchtitle']   = substr($a_keyword['searchtitle'], 0, -2);
                    $a_keyword['searchexplain'] = substr($a_keyword['searchexplain'], 0, -2);
                    $a_keyword['searchanswer']  = substr($a_keyword['searchanswer'], 0, -2) . ')';
                }

                // キーワードが存在する場合はソートの整形終了
                $a_keyword['sortexplain']   = substr($a_keyword['sortexplain'], 0, -2) . ') as explain_order_no,';
                $a_keyword['sortanswer']    = substr($a_keyword['sortanswer'], 0, -2) . ') as answer_order_no,';
            }

            // 投稿日時を設定
            if (!$this->is_empty($aa_conditions['experience_dtm']['after'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_after_experience_dtm = "	and	voice_stay.experience_dtm >= date_format(:after_experience_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['after_experience_dtm'] = $aa_conditions['experience_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['experience_dtm']['before'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_before_experience_dtm = "	and	voice_stay.experience_dtm <= date_format(:before_experience_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['before_experience_dtm'] = $aa_conditions['experience_dtm']['before'];
            }

            // 返答日時を設定
            if (!$this->is_empty($aa_conditions['reply_dtm']['after'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_after_reply_dtm = "	and	voice_reply.reply_dtm >= date_format(:after_reply_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['after_reply_dtm'] = $aa_conditions['reply_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['reply_dtm']['before'] ?? null)) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
                $s_before_reply_dtm = "	and	voice_reply.reply_dtm <= date_format(:before_reply_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['before_reply_dtm'] = $aa_conditions['reply_dtm']['before'];
            }

            // 提携先コードを設定
            $a_conditions['partner_cd'] = $coreModel->s_partner_cd; //this→CoreModelへ変更でいいか？

            $s_sql =
            <<<SQL
			select	voice_stay.voice_cd,
					voice_stay.reserve_cd,
					voice_stay.member_cd,
					voice_stay.title,
					voice_stay.explain,
					voice_stay.experience_dtm,
					voice_stay.limit_dtm,
					voice_stay.status as voice_status,
					{$a_keyword['sortexplain']}
					q6.hotel_cd,
					q6.hotel_nm,
					q6.hotel_old_nm,
					q6.check_in,
					q6.check_out,
					q6.accept_status,
					q6.pref_id,
					q6.city_id,
					q6.ward_id,
					q6.address,
					q6.check_in_end,
					q6.pref_nm,
					q6.city_cd,
					q6.city_nm,
					q6.pref_city_nm,
					q6.ward_nm,
					q6.ward_cd,
					q6.city_ward_nm,
					q6.pref_city_ward_nm,
					q6.reply_type,
					q6.answer,
					q6.reply_dtm as reply_dtm -- 書き換えあっているか？
			from	voice_stay
            right outer join
					(
				select	q5.hotel_cd,
						q5.hotel_nm,
						q5.hotel_old_nm,
						q5.check_in,
						q5.check_out,
						q5.accept_status,
						q5.pref_id,
						q5.city_id,
						q5.ward_id,
						q5.address,
						q5.check_in_end,
						q5.pref_nm,
						q5.city_cd,
						q5.city_nm,
						q5.pref_city_nm,
						q5.ward_nm,
						q5.ward_cd,
						q5.city_ward_nm,
						q5.pref_city_ward_nm,
						voice_reply.voice_cd,
						voice_reply.reply_type,
						voice_reply.answer,
						{$a_keyword['sortanswer']}
						voice_reply.reply_dtm
				from	voice_reply,
					(
						select	q4.hotel_cd,
								q4.hotel_nm,
								q4.hotel_old_nm,
								q4.check_in,
								q4.check_out,
								q4.accept_status,
								q4.pref_id,
								q4.city_id,
								q4.ward_id,
								q4.address,
								q4.check_in_end,
								q4.pref_nm,
								q4.city_cd,
								q4.city_nm,
								q4.pref_city_nm,
								mast_ward.ward_nm,
								mast_ward.ward_cd,
								mast_ward.city_ward_nm,
								mast_ward.pref_city_ward_nm
						from	mast_ward
                        right outer join
							(
								select	q3.hotel_cd,
										q3.hotel_nm,
										q3.hotel_old_nm,
										q3.check_in,
										q3.check_out,
										q3.accept_status,
										q3.pref_id,
										q3.city_id,
										q3.ward_id,
										q3.address,
										q3.check_in_end,
										q3.pref_nm,
										mast_city.city_cd,
										mast_city.city_nm,
										mast_city.pref_city_nm
								from	mast_city,
									(
										select	q2.hotel_cd,
												q2.hotel_nm,
												q2.hotel_old_nm,
												q2.check_in,
												q2.check_out,
												q2.accept_status,
												q2.pref_id,
												q2.city_id,
												q2.ward_id,
												q2.address,
												q2.check_in_end,
												mast_pref.pref_nm
										from	mast_pref,
											(
												select	hotel.hotel_cd,
														hotel.hotel_nm,
														hotel.hotel_old_nm,
														hotel.check_in,
														hotel.check_out,
														hotel.accept_status,
														hotel.pref_id,
														hotel.city_id,
														hotel.ward_id,
														hotel.address,
														hotel.check_in_end
												from	hotel,
													(
														select	hotel_status.hotel_cd
														from	hotel_status
															{$a_station['select']}
															{$a_landmark['select']}
														where	null is null
															{$s_hotel_cd}
															{$a_station['where']}
															{$a_landmark['where']}
															and	hotel_status.hotel_cd not in (
																					select	deny_list.hotel_cd
																					from	deny_list
																					where	deny_list.partner_cd = :partner_cd
																				)
															and	hotel_status.entry_status = 0
															{$s_br_hotel}
													) q1
												where	null is null
													and	hotel.hotel_cd = q1.hotel_cd
													{$s_pref_id}
													{$s_city_id}
													{$s_ward_id}
											) q2
										where	mast_pref.pref_id = q2.pref_id
									) q3
								where	mast_city.city_id = q3.city_id
							) q4
						-- where	mast_ward.ward_id(+) = q4.ward_id -- 書き替えあっているか？
                        on mast_ward.ward_id = q4.ward_id
					) q5
				where	voice_reply.hotel_cd = q5.hotel_cd
					{$s_after_reply_dtm}
					{$s_before_reply_dtm}
			) q6
        -- where	voice_stay.hotel_cd(+) = q6.hotel_cd -- 書き替えあっているか？
		-- 	and	voice_stay.voice_cd(+) = q6.voice_cd
        on voice_stay.hotel_cd = q6.hotel_cd
        and voice_stay.voice_cd = q6.voice_cd
			{$s_status}
			{$s_limit_dtm}
        where null is null -- これより下はjoinの条件じゃないため追記
			{$s_after_experience_dtm}
			{$s_before_experience_dtm}
			{$a_keyword['searchtitle']}
			{$a_keyword['searchexplain']}
			{$a_keyword['searchanswer']}
			order by (q6.answer_order_no + explain_order_no) desc, ifNull(q6.reply_dtm, voice_stay.experience_dtm) desc
SQL;

            // データの取得
            $a_row = DB::select($s_sql, $a_conditions, $aa_offsets);

            $hotelModel = new Hotel();
            for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                $hotelModel->setHotelCd($a_row[$n_cnt]->hotel_cd);
                $a_row[$n_cnt]->hotel_media = $hotelModel->getHotelMedias(['type' => 1]);
            }

            // 必要な情報のみ取得（１ページ分）
            if (!($this->is_empty($aa_offsets['page'] ?? null))) { //null追記でいいか？
                $start = ($aa_offsets['size'] * $aa_offsets['page']) - $aa_offsets['size'];
                $end   = $aa_offsets['size'] * $aa_offsets['page'];

                for ($page_cnt = $start; $page_cnt < $end; $page_cnt++) {
                    if ($this->is_empty($a_row[$page_cnt] ?? null)) { //null追記でいいか？
                        $end = $page_cnt;
                        break;
                    }
                    $a_row[$page_cnt]->total_page  = ceil(count($a_row) / $aa_offsets['size']);
                    $a_row[$page_cnt]->total_count = count($a_row);
                }

                // 全件返す
            } else {
                $start = 0;
                $end   = count($a_row);
            }

            //上記で取得したサイズ分戻すために整形
            $result = []; //初期化
            for ($page_cnt = $start; $page_cnt < $end; $page_cnt++) {
                $result[] = $a_row[$page_cnt];
            }

            return [
                'values'     => $result
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 宿泊体験の検索(投稿日優先検索)　※Coreより移植
    //
    // Core->_s_partner_cd 提携先コード
    // aa_conditions   検索条件を設定
    //   hotel_cd      施設コード
    //   voice_cd      投稿コード
    //   keyword       キーワード
    //   not_limit_dtm true:30ヶ月の有効期限内を取得 false:有効期限外も取得
    //   not_status    true:有効な状態のみを取得 false:削除状態も取得
    //   experience_dtm
    //     after  =       日付 > 日付以降の投稿 YYYY-MM-DD HH:MM:SS
    //     before =       日付 > 日付以前の投稿 YYYY-MM-DD HH:MM:SS
    //   reply_dtm
    //     after  =       日付 > 日付以降の投稿 YYYY-MM-DD HH:MM:SS
    //     before =       日付 > 日付以前の投稿 YYYY-MM-DD HH:MM:SS
    // aa_offsets
    //   page ページ
    //   size レコード数(1から) ページ数を指定した場合必須
    //
    // example
    //   find(array('hotel_cd' => '2000040001'))
    //   find(array('hotel_cd' => '2000040001'), array('page'  => 1, 'size' => 20))
    public function voiceListsPost($aa_conditions, $aa_offsets = null, $as_type = null, $as_sort = null, $as_record_all = null)
    {
        try {
            $coreModel = new Core();

            //コントローラから移植（newした後でないとリセットされるため）問題ないか？
            $coreModel->setPartnerCd('0000000000');

            if ($this->is_empty($coreModel->s_partner_cd)) {
                throw new Exception('提携先コードを設定してください。 partner_cd => xxxxxxxxxx');
            }

            //初期化
            $a_station['select'] = '';
            $a_landmark['select'] = '';
            $s_hotel_cd = '';
            $a_station['where'] = '';
            $a_landmark['where'] = '';
            $s_br_hotel = ''; //定義の方が削除でいいなら削除
            $s_pref_id = '';
            $s_city_id = '';
            $s_ward_id = '';
            $s_voice_cd = '';
            $s_status = '';
            $s_limit_dtm = '';
            $s_after_experience_dtm = '';
            $s_before_experience_dtm = '';
            $s_after_reply_dtm = '';
            $s_before_reply_dtm = '';
            $s_where_add = '';
            $a_keyword['searchtitle']   = '';
            $a_keyword['searchexplain'] = '';
            $a_keyword['searchanswer']  = '';

            //削除でいいか？
            // // 条件を定義
            // // ベストリザーブホテルを制御
            // if (!$this->in_inside()) {
            //   $s_br_hotel = "and hotel_status.hotel_cd >= '2000050000'";
            // }

            // 施設コードを設定
            if (!$this->is_empty($aa_conditions['hotel_cd'])) {
                $s_sql =
                <<< SQL
					select	hotel_relation.relation_cd
					from	hotel_relation,
						(
							select	hotel_cd
							from	hotel_relation
							where	hotel_relation.relation_cd = :hotel_cd1
						) q1
					where	hotel_relation.hotel_cd = q1.hotel_cd
						and	hotel_relation.relation_cd != :hotel_cd2
SQL;
                // データの取得
                $htl_conditions['hotel_cd1'] = $aa_conditions['hotel_cd'];
                $htl_conditions['hotel_cd2'] = $aa_conditions['hotel_cd']; //invaliedparameter対応,a_conditionへの代入ではバッティングするため変数名変更
                $a_row = DB::select($s_sql, $htl_conditions);

                $s_hotel_cd = 'and	hotel_status.hotel_cd in (';

                for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                    $s_hotel_cd .= ':hotel_cd' . $n_cnt . ', ';
                    $a_conditions['hotel_cd' . $n_cnt] = $a_row[$n_cnt]['relation_cd'];
                }
                $s_hotel_cd .= ":hotel_cd)";
                $a_conditions['hotel_cd'] = $aa_conditions['hotel_cd'];
            }

            // 投稿コードを設定
            if (!$this->is_empty($aa_conditions['voice_cd'] ?? null)) { //null追記でいいか？
                $s_voice_cd = '	and	voice_cd = :voice_cd';
                $a_conditions['voice_cd'] = $aa_conditions['voice_cd'];
            }

            // 有効期限を設定
            if ($aa_conditions['not_limit_dtm'] ?? true) { //nvl→??でいいか？
                $s_limit_dtm = '	and	now() < voice_stay.limit_dtm';
            }

            // 状態を設定
            if ($aa_conditions['not_status'] ?? true) { //nvl→??でいいか？
                $s_status = '	and	voice_stay.status = 0';
            }

            // 都道府県IDを設定
            if (!$this->is_empty($aa_conditions['pref_id'] ?? null)) { //null追記でいいか？
                $s_pref_id = '	and	hotel.pref_id = :pref_id';
                $a_conditions['pref_id'] = $aa_conditions['pref_id'];
            }

            // 市IDを設定
            if (!$this->is_empty($aa_conditions['city_id'] ?? null)) { //null追記でいいか？
                $s_city_id = '	and	hotel.city_id = :city_id';
                $a_conditions['city_id'] = $aa_conditions['city_id'];
            }

            // 区IDを設定
            if (!$this->is_empty($aa_conditions['ward_id'] ?? null)) { //null追記でいいか？
                $s_ward_id = '	and	hotel.ward_id = :ward_id';
                $a_conditions['ward_id'] = $aa_conditions['ward_id'];
            }

            // 駅IDを設定
            if (!$this->is_empty($aa_conditions['station_id'] ?? null)) { //null追記でいいか？
                $a_station['where']  = '	and	hotel_status.hotel_cd = hotel_station.hotel_cd';
                $a_station['select'] =
                <<< SQL
						,(
							select	hotel_station.hotel_cd
							from	hotel_station
							where	hotel_station.station_id = :station_id
						) hotel_station
SQL;
                $a_conditions['station_id'] = $aa_conditions['station_id'];

                // 路線IDを設定 （駅が決まっていれば路線を見る必要はない）
            } elseif (!$this->is_empty($aa_conditions['route_id'] ?? null)) { //null追記でいいか？
                $a_station['where']  = '	and	hotel_status.hotel_cd = hotel_station.hotel_cd';
                $a_station['select'] =
                <<< SQL
						,(
							select	hotel_station.hotel_cd
							from	hotel_station,
								(
									select	mast_station.station_id
									from	mast_station
									where	mast_station.route_id = :route_id
								) mast_station
							where	hotel_station.station_id = mast_station.station_id
						) hotel_station
SQL;
                $a_conditions['route_id'] = $aa_conditions['route_id'];
            }

            // ランドマークIDを設定
            if (!$this->is_empty($aa_conditions['landmark_id'] ?? null)) { //null追記でいいか？
                $a_landmark['where']  = '	and	hotel_status.hotel_cd = hotel_landmark.hotel_cd';
                $a_landmark['select'] =
                <<< SQL
						,(
							select	hotel_landmark.hotel_cd
							from	hotel_landmark
							where	hotel_landmark.landmark_id = :landmark_id
						) hotel_landmark
SQL;
                $a_conditions['landmark_id'] = $aa_conditions['landmark_id'];
            }

            // キーワードを取得(スペース、全角スペースで分割)
            $a_keywords = preg_split('/ |　/', $aa_conditions['keywords']);

            // ソート用の値の初期値設定
            $a_keyword['sortanswer']  = '	(';
            $a_keyword['sortexplain'] = '	(';
            // ソート用の値を整形開始（キーワードがあれば必ず通る）
            for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                if (strlen($a_keywords[$n_cnt]) != 0) {
                    $a_keyword['sortexplain'] .= " case when instr( voice_stay.title, :sort" . $n_cnt . '1' . ") + instr(voice_stay.explain, :sort" . $n_cnt  . '2' . ") = 0 then 0 else 1 end ||"; //decodeから書き換え合っているか？（下も）
                    $a_keyword['sortanswer']  .= " case when instr(voice_reply.answer, :sort" . $n_cnt . '3' . ") = 0 then 0 else 1 end ||";

                    $a_conditions['sort' . $n_cnt . '1']   = $a_keywords[$n_cnt];
                    $a_conditions['sort' . $n_cnt . '2']   = $a_keywords[$n_cnt];
                    $a_conditions['sort' . $n_cnt . '3']   = $a_keywords[$n_cnt]; //invalied parameter対策
                    $b_buf = 'キーワードが存在';
                }
            }

            // 投稿日と返答日が空の場合
            if (($this->is_empty($aa_conditions['experience_dtm'] ?? null)) && ($this->is_empty($aa_conditions['reply_dtm'] ?? null))) { // null追記でいいか？
                //  宿泊体験用キーワード整形
                $a_keyword['searchexplain']  = '	or	';
                $a_keyword['searchtitle']  = '	and	(';
                for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                    if (strlen($a_keywords[$n_cnt]) != 0) {
                        $a_keyword['searchtitle']   .= " q6.title like :search" . $n_cnt . '1' . " or";
                        $a_keyword['searchexplain'] .= " q6.explain like :search" . $n_cnt . '2' . " or";

                        $a_conditions['search' . $n_cnt . '1'] = '%' . $a_keywords[$n_cnt] . '%';
                        $a_conditions['search' . $n_cnt . '2'] = '%' . $a_keywords[$n_cnt] . '%';
                    }
                }

                //  宿泊体験返答用キーワード整形
                $a_keyword['searchanswer']  = '	or	';
                for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                    if (strlen($a_keywords[$n_cnt]) != 0) {
                        $a_keyword['searchanswer'] .= " voice_reply.answer like :search" . $n_cnt . '3' . " or";

                        $a_conditions['search' . $n_cnt . '3'] = '%' . $a_keywords[$n_cnt] . '%';
                    }
                }

                // 投稿日若しくは返答日が引数で渡されていなければ
            } else {
                // 投稿日が存在すれば検索用キーワード整形開始
                if ((!$this->is_empty($aa_conditions['experience_dtm'] ?? null))) { //null追記でいいか
                    //  宿泊体験用キーワード整形
                    $a_keyword['searchexplain'] = '	or	';
                    $a_keyword['searchtitle']   = '	and	(';
                    for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                        if (strlen($a_keywords[$n_cnt]) != 0) {
                            $a_keyword['searchtitle']   .= " q6.title like :search" . $n_cnt . '1' . " or";
                            $a_keyword['searchexplain'] .= " q6.explain like :search" . $n_cnt . '2' . " or";

                            $a_conditions['search' . $n_cnt . '1'] = '%' . $a_keywords[$n_cnt] . '%';
                            $a_conditions['search' . $n_cnt . '2'] = '%' . $a_keywords[$n_cnt] . '%';
                        }
                    }
                }

                // 返答日が存在すれば検索用キーワード整形開始
                if ((!$this->is_empty($aa_conditions['reply_dtm'] ?? null))) { //null追記でいいか
                    //  宿泊体験用キーワードが存在しなければ
                    if ($this->is_empty($a_keyword['searchexplain'])) {
                        $a_keyword['searchanswer']  = '	and	(';
                    } else {
                        $a_keyword['searchanswer']  = '	or	';
                    }

                    //  宿泊体験返答用キーワード整形
                    for ($n_cnt = 0; $n_cnt < count($a_keywords); $n_cnt++) {
                        if (strlen($a_keywords[$n_cnt]) != 0) {
                            $a_keyword['searchanswer'] .= " voice_reply.answer like :search" . $n_cnt . '3' . " or";

                            $a_conditions['search' . $n_cnt . '3'] = '%' . $a_keywords[$n_cnt] . '%';
                        }
                    }
                }
            }

            // キーワードが存在しなければ
            if ($this->is_empty($b_buf ?? null)) { //null追記でいいか？
                // 投稿日、返答日共にキーワード検索が存在しないのでnullを代入
                $a_keyword['searchtitle']   = null;
                $a_keyword['searchexplain'] = null;
                $a_keyword['searchanswer']  = null;

                // ソートのデフォルト値代入
                $a_keyword['sortexplain']   = 'null as explain_order_no,';
                $a_keyword['sortanswer']    = 'null as answer_order_no,';

                // キーワードが存在すれば閉じ括弧の整形を行う
            } else {
                // 閉じ括弧の整形
                // 投稿日のキーワード検索が存在すれば整形終了
                if ($this->is_empty($a_keyword['searchanswer'])) {
                    $a_keyword['searchtitle']   = substr($a_keyword['searchtitle'], 0, -2);
                    $a_keyword['searchexplain'] = substr($a_keyword['searchexplain'], 0, -2) . ')';

                    // 返答日のキーワード検索が存在すれば整形終了
                } elseif ($this->is_empty($a_keyword['searchexplain'])) {
                    $a_keyword['searchanswer']  = substr($a_keyword['searchanswer'], 0, -2) . ')';

                    // 投稿日、返答日共にキーワード検索が存在すれば整形終了
                } else {
                    $a_keyword['searchtitle']   = substr($a_keyword['searchtitle'], 0, -2);
                    $a_keyword['searchexplain'] = substr($a_keyword['searchexplain'], 0, -2);
                    $a_keyword['searchanswer']  = substr($a_keyword['searchanswer'], 0, -2) . ')';
                }

                // キーワードが存在する場合はソートの整形終了
                $a_keyword['sortexplain']   = substr($a_keyword['sortexplain'], 0, -2) . ') as explain_order_no,';
                $a_keyword['sortanswer']    = substr($a_keyword['sortanswer'], 0, -2) . ') as answer_order_no,';
            }


            // 投稿日時を設定
            if (!$this->is_empty($aa_conditions['experience_dtm']['after'] ?? null)) { //null追記でいいか
                $s_after_experience_dtm = "	and	voice_stay.experience_dtm >= date_format(:after_experience_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['after_experience_dtm'] = $aa_conditions['experience_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['experience_dtm']['before'] ?? null)) { //null追記でいいか
                $s_before_experience_dtm = "	and	voice_stay.experience_dtm <= date_format(:before_experience_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['before_experience_dtm'] = $aa_conditions['experience_dtm']['before'];
            }

            // 返答日時を設定
            if (!$this->is_empty($aa_conditions['reply_dtm']['after'] ?? null)) { //null追記でいいか
                $s_after_reply_dtm = "	and	voice_reply.reply_dtm >= date_format(:after_reply_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['after_reply_dtm'] = $aa_conditions['reply_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['reply_dtm']['before'] ?? null)) { //null追記でいいか
                $s_before_reply_dtm = "	and	voice_reply.reply_dtm <= date_format(:before_reply_dtm, '%Y-%m-%d %H:%i:%s')";
                $a_conditions['before_reply_dtm'] = $aa_conditions['reply_dtm']['before'];
            }

            // 提携先コードを設定
            $a_conditions['partner_cd'] = $coreModel->s_partner_cd; //this→CoreModelへ変更でいいか？

            // 部屋名称のサイズ拡張に伴い、互換維持のため
            // インクエリ、外部連携は、短縮版を使用する。
            // if (in_array($this->box->info->env->module, array('inq', 'pol'))) {
            if (in_array('', ['inq', 'pol'])) { //TODO 書き換えどうすればいい？
                $s_clm_room_nm = 'room_nm';
            } else {
                $s_clm_room_nm = 'room_nl as room_nm';
            }

            // ソート方法
            // 投稿者の評価（総合）、世代
            if ($as_sort === 'aa') {
                // 世代昇順
                $s_order_by = ' order by q9.voice_age asc, (answer_order_no + q9.explain_order_no) desc, ifNull(q9.reply_dtm, q9.experience_dtm) desc ';
            } elseif ($as_sort === 'ad') {
                // 世代降順
                $s_order_by = ' order by q9.voice_age desc, (answer_order_no + q9.explain_order_no) desc, ifNull(q9.reply_dtm, q9.experience_dtm) desc ';
            } elseif ($as_sort === 'rd') {
                // 評価（総合）降順
                $s_order_by = ' order by ifNull(q9.review_total, 0.0) desc, (answer_order_no + q9.explain_order_no) desc, ifNull(q9.reply_dtm, q9.experience_dtm) desc ';
            } else {
                // デフォルト
                $s_order_by = ' order by (answer_order_no + q9.explain_order_no) desc, ifNull(q9.reply_dtm, q9.experience_dtm) desc ';
            }

            // 絞込み
            if ($as_type === 'gm') {
                // 男性の投稿のみ表示
                $s_where_add = " and q9.voice_gender = 'm' ";
            } elseif ($as_type === 'gf') {
                // 女性の投稿のみ表示
                $s_where_add = " and q9.voice_gender = 'f' ";
            }

            $s_sql =
            <<< SQL
select	q9.hotel_cd,
		q9.hotel_nm,
		q9.hotel_old_nm,
		q9.check_in,
		q9.check_out,
		q9.accept_status,
		q9.pref_id,
		q9.city_id,
		q9.ward_id,
		q9.address,
		q9.check_in_end,
		q9.pref_nm,
		q9.city_cd,
		q9.city_nm,
		q9.pref_city_nm,
		q9.ward_nm,
		q9.ward_cd,
		q9.city_ward_nm,
		q9.pref_city_ward_nm,
		q9.voice_cd,
		q9.reserve_cd,
		q9.member_cd,
		q9.title,
		q9.explain,
		q9.experience_dtm as experience_dtm, -- 書き換えあっているか？
		q9.limit_dtm as limit_dtm, -- 書き換えあっているか？
		q9.voice_status,
		q9.voice_age,
		q9.voice_gender,
		q9.explain_order_no,
		q9.reply_type,
		q9.answer,
		q9.answer_order_no,
		q9.reply_dtm as reply_dtm, -- 書き換えあっているか？
		q9.review_total,
		q9.room_cd,
		q9.room_id,
		q9.room_nm,
		q9.room_type,
		q9.floorage_min,
		q9.floorage_max,
		q9.floor_unit,
		q9.plan_cd,
		q9.plan_id,
		q9.plan_nm,
		q9.capacity,
		q9.birth_ymd as birth_ymd, -- 書き換えあっているか？
		q9.gender,
		min(reserve_charge.sales_charge) as min_sales_charge,
		max(reserve_charge.sales_charge) as max_sales_charge,
		min(reserve_charge.date_ymd) as min_date_ymd, -- 書き換えあっているか？
		max(reserve_charge.date_ymd) as max_date_ymd -- 書き換えあっているか？
from	reserve_charge,
	(
		select	q8.hotel_cd,
				q8.hotel_nm,
				q8.hotel_old_nm,
				q8.check_in,
				q8.check_out,
				q8.accept_status,
				q8.pref_id,
				q8.city_id,
				q8.ward_id,
				q8.address,
				q8.check_in_end,
				q8.pref_nm,
				q8.city_cd,
				q8.city_nm,
				q8.pref_city_nm,
				q8.ward_nm,
				q8.ward_cd,
				q8.city_ward_nm,
				q8.pref_city_ward_nm,
				q8.voice_cd,
				q8.reserve_cd,
				q8.member_cd,
				q8.title,
				q8.explain,
				q8.experience_dtm,
				q8.limit_dtm,
				q8.voice_status,
				q8.voice_age,
				q8.voice_gender,
				q8.explain_order_no,
				q8.reply_type,
				q8.answer,
				q8.answer_order_no,
				q8.reply_dtm,
				q8.review_total,
				q8.room_cd,
				q8.room_id,
				q8.room_nm,
				q8.room_type,
				q8.floorage_min,
				q8.floorage_max,
				q8.floor_unit,
				q8.plan_cd,
				q8.plan_id,
				q8.plan_nm,
				q8.capacity,
				member_detail.birth_ymd,
				member_detail.gender
		from	member_detail
        right outer join
			(
				select	q7.hotel_cd,
						q7.hotel_nm,
						q7.hotel_old_nm,
						q7.check_in,
						q7.check_out,
						q7.accept_status,
						q7.pref_id,
						q7.city_id,
						q7.ward_id,
						q7.address,
						q7.check_in_end,
						q7.pref_nm,
						q7.city_cd,
						q7.city_nm,
						q7.pref_city_nm,
						q7.ward_nm,
						q7.ward_cd,
						q7.city_ward_nm,
						q7.pref_city_ward_nm,
						q7.voice_cd,
						q7.reserve_cd,
						q7.member_cd,
						q7.title,
						q7.explain,
						q7.experience_dtm,
						q7.limit_dtm,
						q7.voice_status,
						q7.voice_age,
						q7.voice_gender,
						q7.explain_order_no,
						q7.reply_type,
						q7.answer,
						q7.answer_order_no,
						q7.reply_dtm,
						v7.review_total,
						reserve_plan.room_cd,
						reserve_plan.room_id,
						reserve_plan.{$s_clm_room_nm},
						reserve_plan.room_type,
						reserve_plan.floorage_min,
						reserve_plan.floorage_max,
						reserve_plan.floor_unit,
						reserve_plan.plan_nm,
						reserve_plan.plan_cd,
						reserve_plan.plan_id,
						reserve_plan.capacity
				from	reserve_plan,

					(
						select	q6.hotel_cd,
								q6.hotel_nm,
								q6.hotel_old_nm,
								q6.check_in,
								q6.check_out,
								q6.accept_status,
								q6.pref_id,
								q6.city_id,
								q6.ward_id,
								q6.address,
								q6.check_in_end,
								q6.pref_nm,
								q6.city_cd,
								q6.city_nm,
								q6.pref_city_nm,
								q6.ward_nm,
								q6.ward_cd,
								q6.city_ward_nm,
								q6.pref_city_ward_nm,
								q6.voice_cd,
								q6.reserve_cd,
								q6.member_cd,
								q6.title,
								q6.explain,
								q6.experience_dtm,
								q6.limit_dtm,
								q6.voice_status,
								q6.voice_age,
								q6.voice_gender,
								q6.explain_order_no,
								voice_reply.reply_type,
								voice_reply.answer,
								{$a_keyword['sortanswer']}
								voice_reply.reply_dtm
						from	voice_reply
                            right outer join
							(
								select	voice_stay.voice_cd,
										voice_stay.reserve_cd,
										voice_stay.member_cd,
										voice_stay.title,
										voice_stay.explain,
										voice_stay.experience_dtm,
										voice_stay.limit_dtm,
										voice_stay.status as voice_status,
										voice_stay.age as voice_age,
										voice_stay.gender as voice_gender,
										{$a_keyword['sortexplain']}
										q5.hotel_cd,
										q5.hotel_nm,
										q5.hotel_old_nm,
										q5.check_in,
										q5.check_out,
										q5.accept_status,
										q5.pref_id,
										q5.city_id,
										q5.ward_id,
										q5.address,
										q5.check_in_end,
										q5.pref_nm,
										q5.city_cd,
										q5.city_nm,
										q5.pref_city_nm,
										q5.ward_nm,
										q5.ward_cd,
										q5.city_ward_nm,
										q5.pref_city_ward_nm
								from	voice_stay,
									(
										select	q4.hotel_cd,
												q4.hotel_nm,
												q4.hotel_old_nm,
												q4.check_in,
												q4.check_out,
												q4.accept_status,
												q4.pref_id,
												q4.city_id,
												q4.ward_id,
												q4.address,
												q4.check_in_end,
												q4.pref_nm,
												q4.city_cd,
												q4.city_nm,
												q4.pref_city_nm,
												mast_ward.ward_nm,
												mast_ward.ward_cd,
												mast_ward.city_ward_nm,
												mast_ward.pref_city_ward_nm
										from	mast_ward
                                        right outer join
											(
												select	q3.hotel_cd,
														q3.hotel_nm,
														q3.hotel_old_nm,
														q3.check_in,
														q3.check_out,
														q3.accept_status,
														q3.pref_id,
														q3.city_id,
														q3.ward_id,
														q3.address,
														q3.check_in_end,
														q3.pref_nm,
														mast_city.city_cd,
														mast_city.city_nm,
														mast_city.pref_city_nm
												from	mast_city,
													(
														select	q2.hotel_cd,
																q2.hotel_nm,
																q2.hotel_old_nm,
																q2.check_in,
																q2.check_out,
																q2.accept_status,
																q2.pref_id,
																q2.city_id,
																q2.ward_id,
																q2.address,
																q2.check_in_end,
																mast_pref.pref_nm
														from	mast_pref,
															(
																select	hotel.hotel_cd,
																		hotel.hotel_nm,
																		hotel.hotel_old_nm,
																		hotel.check_in,
																		hotel.check_out,
																		hotel.accept_status,
																		hotel.pref_id,
																		hotel.city_id,
																		hotel.ward_id,
																		hotel.address,
																		hotel.check_in_end
																from	hotel,
																	(
																		select	hotel_status.hotel_cd
																		from	hotel_status
																			{$a_station['select']}
																			{$a_landmark['select']}
																		where	null is null
																			{$s_hotel_cd}
																			{$a_station['where']}
																			{$a_landmark['where']}
																			and	hotel_status.hotel_cd not in (
																									select	deny_list.hotel_cd
																									from	deny_list
																									where	deny_list.partner_cd = :partner_cd
																								)
																			and	hotel_status.entry_status = 0
																			{$s_br_hotel}
																	) q1
																where	null is null
																	and	hotel.hotel_cd = q1.hotel_cd
																	{$s_pref_id}
																	{$s_city_id}
																	{$s_ward_id}
															) q2
														where	mast_pref.pref_id = q2.pref_id
													) q3
												where	mast_city.city_id = q3.city_id
											) q4
										-- where	mast_ward.ward_id(+) = q4.ward_id -- 書き換えあっているか？
                                        on mast_ward.ward_id = q4.ward_id
									) q5
								where	voice_stay.hotel_cd = q5.hotel_cd
								{$s_voice_cd}
								{$s_status}
								{$s_limit_dtm}
								{$s_after_experience_dtm}
								{$s_before_experience_dtm}
							) q6
                        -- where	voice_reply.hotel_cd(+) = q6.hotel_cd -- 書き換えあっているか
						-- 	and	voice_reply.voice_cd(+) = q6.voice_cd
                         on voice_reply.hotel_cd = q6.hotel_cd
						and	voice_reply.voice_cd = q6.voice_cd
                        where null is null -- これより下はjoinの条件ではないので追記
						{$s_after_reply_dtm}
						{$s_before_reply_dtm}
						{$a_keyword['searchtitle']}
						{$a_keyword['searchexplain']}
						{$a_keyword['searchanswer']}
					) q7
                    left outer join
					(
						select	reserve_cd,
								review_cnt as review_total
						from	voice_review
						where	review_id = 0
					) v7
                    on  q7.reserve_cd = v7.reserve_cd
				where   reserve_plan.reserve_cd = q7.reserve_cd				
			) q8
		-- where	member_detail.member_cd(+) = q8.member_cd -- 書き換えあっているか
        on member_detail.member_cd = q8.member_cd
	) q9
where	reserve_charge.reserve_cd = q9.reserve_cd
{$s_where_add}
group by q9.hotel_cd,
		q9.hotel_nm,
		q9.hotel_old_nm,
		q9.check_in,
		q9.check_out,
		q9.accept_status,
		q9.pref_id,
		q9.city_id,
		q9.ward_id,
		q9.address,
		q9.check_in_end,
		q9.pref_nm,
		q9.city_cd,
		q9.city_nm,
		q9.pref_city_nm,
		q9.ward_nm,
		q9.ward_cd,
		q9.city_ward_nm,
		q9.pref_city_ward_nm,
		q9.voice_cd,
		q9.reserve_cd,
		q9.member_cd,
		q9.title,
		q9.explain,
		q9.experience_dtm,
		q9.limit_dtm,
		q9.voice_status,
		q9.voice_age,
		q9.voice_gender,
		q9.explain_order_no,
		q9.reply_type,
		q9.answer,
		q9.answer_order_no,
		q9.reply_dtm,
		q9.review_total,
		q9.room_cd,
		q9.room_id,
		q9.room_nm,
		q9.room_type,
		q9.floorage_min,
		q9.floorage_max,
		q9.floor_unit,
		q9.plan_cd,
		q9.plan_id,
		q9.plan_nm,
		q9.capacity,
		q9.birth_ymd,
		q9.gender
		{$s_order_by}
SQL;
            // データの取得
            if ($this->is_empty($as_record_all)) {
                $a_row = DB::select($s_sql, $a_conditions, $aa_offsets);
            } else {
                $a_row = DB::select($s_sql, $a_conditions);
            }

            $hotelModel = new Hotel();
            for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                $hotelModel->setHotelCd($a_row[$n_cnt]->hotel_cd);
                $a_row[$n_cnt]->hotel_media  = $hotelModel->getHotelMedias(['type' => 1]);
                $a_row[$n_cnt]->voice_review = $this->getVoiceReviewLists($a_row[$n_cnt]->member_cd, $a_row[$n_cnt]->reserve_cd);
            }

            // 必要な情報のみ取得（１ページ分）
            if (!($this->is_empty($aa_offsets['page'] ?? null))) { //null追記でいいか？
                $start = ($aa_offsets['size'] * $aa_offsets['page']) - $aa_offsets['size'];
                $end   = $aa_offsets['size'] * $aa_offsets['page'];

                for ($page_cnt = $start; $page_cnt < $end; $page_cnt++) {
                    if ($this->is_empty($a_row[$page_cnt] ?? null)) { //null追記でいいか？
                        $end = $page_cnt;
                        break;
                    }
                    $a_row[$page_cnt]->total_page  = ceil(count($a_row) / $aa_offsets['size']);
                    $a_row[$page_cnt]->total_count = count($a_row);
                }

                // 全件返す
            } else {
                $start = 0;
                $end   = count($a_row);
            }

            //上記で取得したサイズ分戻すために整形
            $result = []; //初期化
            for ($page_cnt = $start; $page_cnt < $end; $page_cnt++) {
                $result[] = $a_row[$page_cnt];
            }

            return [
                'values'     => $result
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // クチコミ評点の取得
    //
    //   as_member_cd  会員コード
    //   as_reserve_cd 予約コード
    public function getVoiceReviewLists($as_member_cd, $as_reserve_cd)
    {
        try {
            $s_sql =
                <<< SQL
					select	voice_review.review_id,
							mast_review.review_nm,
							voice_review.review_cnt
					from	voice_review
							-- mast_review
                    left outer join mast_review
                        on voice_review.review_id = mast_review.review_id
					where	voice_review.member_cd = :member_cd
						and	voice_review.reserve_cd = :reserve_cd
						-- and	voice_review.review_id = mast_review.review_id(+) -- 上に移動、書き換えあっているか？
					order by mast_review.order_no
SQL;

            // データの取得
            $a_row = DB::select($s_sql, ['member_cd' => $as_member_cd, 'reserve_cd' => $as_reserve_cd]);
            $a_reviews = []; //初期化
            for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++) {
                $a_reviews[$a_row[$n_cnt]['review_id']] = [
                    'review_id'  => $a_row[$n_cnt]['review_id'],
                    'review_nm'  => $a_row[$n_cnt]['review_nm'],
                    'review_cnt' => $a_row[$n_cnt]['review_cnt']
                ];
            }

            return [
                'values'    => $a_reviews
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
