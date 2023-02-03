<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Hotel;
use App\Models\HotelCancelPolicy;
use App\Models\HotelCancelRate;
use App\Http\Requests\HtlHotelCancelRequest;
use App\Http\Requests\HtlHotelCancelRateRequest;

class HtlHotelCancelController extends _commonController
{
    public function index(Request $request)
    {
        $s_target_cd = $request->input('target_cd');

        // バリデーションエラー時は入力値
        $old_target_cd = $request->old('target_cd');
        if (!is_null($old_target_cd)) {
            $s_target_cd = $old_target_cd;
        }

        // TODO 販売延長作業を行っているかをチェックしリダイレクト
        // MEMO(2/1) :このコントローラ作成時に遷移先未作成
        $lock_status = $this->isLocked($s_target_cd);
        if ($lock_status) {
            return redirect()
                ->route('ctl.htlroomplan.index', ['target_cd' => $s_target_cd])
                ->with(['errors' => ['ただいま、ご登録されている内容にて販売延長作業を行っております。もうしばらくしてから再度実行してください。']]);
        }

        $days = $request->input('days');
        $disp = $request->input('disp');
        $dispcondition = $request->input('dispcondition');
        $plan_cd = $request->input('plan_cd');
        $room_cd = $request->input('room_cd');
        $partner_group_id = $request->input('partner_group_id');
        $display_status = $request->input('display_status');

        try {
            $o_hotel = new Hotel();
            $o_hotel_cancel_policy = new HotelCancelPolicy();

            // 施設情報
            $a_hotel = $o_hotel->where(['hotel_cd' => $s_target_cd])->first();

            // 施設キャンセルポリシー情報
            $a_hotel_cancel_policy = $o_hotel_cancel_policy->where(['hotel_cd' => $s_target_cd])->first();

            // キャンセル料率の一覧を取得
            // $a_cancel_rates = $this->getHotelCancelRates($s_target_cd);
            $hotel_cancel_rate_model = new HotelCancelRate();
            $a_cancel_rates['values'] = $hotel_cancel_rate_model->where([
                'hotel_cd' => $s_target_cd
            ])->orderBy('days', 'asc')->get();

            // キャンセル料率の一覧が存在する場合
            if (!empty($a_cancel_rates['values'])) {
                // キャンセル料率の一覧から情報を取得
                foreach ($a_cancel_rates['values'] as $value) {
                    // 現在のステータスをチェック
                    if ($value->policy_status == 0) {
                        // 0:適用外 1:適用中 施設単位に設定されます。
                        $n_policy_status = 0;
                        break;
                    } elseif ($value->policy_status == 1) {
                        // 0:適用外 1:適用中 施設単位に設定されます。
                        $n_policy_status = 1;
                        break;
                    }
                }
                // 一覧が存在しない場合は適用外へ
            } else {
                // 0:適用外 1:適用中 施設単位に設定されます。
                $n_policy_status = 0;
            }
            // キャンセルポリシーが存在するかの判断
            $b_is_cancel_policy = false;

            // キャンセルポリシーが存在しない場合
            if (empty($a_hotel_cancel_policy)) {
                // デフォルトの値をconfigから取得
                // 旅館の場合
                // MEMO；旧ソースは「$this->box->user」のhotel['hotel_category']で判断しているが、target_cdをキーにhotelTBLから取得するのと同義だと思われるため、そのように記述。
                if (isset($a_hotel) && $a_hotel['hotel_category'] === 'j') {
                    $a_hotel_cancel_policy = ['cancel_policy' => config('default_cancel_policy.cancel_policy_j.policy')]; // MEMO；旧ソースはconfigからxml読み込み。
                } else {
                    $a_hotel_cancel_policy = ['cancel_policy' => config('default_cancel_policy.cancel_policy.policy')];
                }
            } else {
                // キャンセルポリシーが存在する場合
                // 存在するのでtrueへ　※削除用の判断
                $b_is_cancel_policy = true;
            }

            // 料率が無ければ
            // 旅館の場合
            if (isset($a_hotel) && $a_hotel['hotel_category'] === 'j') {
                foreach (config('default_cancel_policy.cancel_policy_j.rate') as $value) {
                    // デフォルトの値をconfigから取得
                    // 一旦keyを日付に設定
                    $a_default_cancel_rates['values'][$value['days']] = ['days' => $value['days'], 'cancel_rate' => $value['rate']];
                }
            } else {
                foreach (config('default_cancel_policy.cancel_policy.rate') as $value) {
                    // デフォルトの値をconfigから取得
                    // 一旦keyを日付に設定
                    $a_default_cancel_rates['values'][$value['days']] = ['days' => $value['days'], 'cancel_rate' => $value['rate']];
                }
            }

            // 並び変え
            sort($a_default_cancel_rates['values']);

            // bladeで新規登録行を生成するためにキーを1追加
            $a_cancel_rates['values'][] = [
                'hotel_cd'  => null,
                'days' => null,
                'cancel_rate' => null,
                'policy_status' => null,
            ];

            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            // 登録・更新完了時はガイドメッセージ取得
            $guides = $request->session()->get('guides', []);

            return view('ctl.htlhotelcancel.index', [
                'hotel_cancel_policy'   => $a_hotel_cancel_policy,
                'cancel_rates'          => $a_cancel_rates,
                'default_cancel_rates'  => $a_default_cancel_rates,
                'policy_status'         => $n_policy_status,
                'is_cancel_policy'      => $b_is_cancel_policy,
                'days'                  => $days,
                'disp'                  => $disp,
                'dispcondition'         => $dispcondition,
                'plan_cd'               => $plan_cd,
                'room_cd'               => $room_cd,
                'target_cd'             => $s_target_cd,
                'partner_group_id'      => $partner_group_id,
                'display_status'        => $display_status,
                'errors'                => $errors,
                'guides'                => $guides
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // キャンセルポリシーの更新
    public function cancelpolicy(HtlHotelCancelRequest $request)
    {
        $s_target_cd = $request->input('target_cd');
        $tmp_cancel_policy = str_replace("<", "＜", $request->input('cancel_policy'));
        $s_cancel_policy = str_replace(">", "＞", $tmp_cancel_policy);

        try {
            // トランザクション開始
            DB::beginTransaction();

            // インスタンスの取得
            $o_hotel_cancel_policy = new HotelCancelPolicy();

            // 情報があればセット
            $a_hotel_cancel_policy = $o_hotel_cancel_policy->where(['hotel_cd' => $s_target_cd])->first();

            $a_attributes['hotel_cd'] = $s_target_cd;
            $a_attributes['entry_cd'] = 'entry_cd';     // TODO $this->box->info->env->action_cd;
            $a_attributes['entry_ts'] = now();
            $a_attributes['modify_cd'] = 'modify_cd';   // TODO $this->box->info->env->action_cd;
            $a_attributes['modify_ts'] = now();

            // 情報があれば更新
            if (!empty($a_hotel_cancel_policy)) {
                // 更新処理
                $hotel_cancel_policy_update = $o_hotel_cancel_policy->where([
                    'hotel_cd' => $s_target_cd
                ])->update([
                    'cancel_policy' => $s_cancel_policy,
                    'modify_cd'     => $a_attributes['modify_cd'],
                    'modify_ts'     => $a_attributes['modify_ts']
                ]);
                // 登録後失敗した場合はindexへ
                if (!$hotel_cancel_policy_update) {
                    DB::rollback();
                    return $this->index($request, [
                        'target_cd' => $s_target_cd,
                    ])->with(['errors' => ['データを更新できませんでした。']]);
                };

                // 情報が無ければ登録
            } else {
                // 登録処理
                $hotel_cancel_policy_create = $o_hotel_cancel_policy->create([
                    'hotel_cd' => $s_target_cd,
                    'entry_cd'  => $a_attributes['entry_cd'],
                    'entry_ts'  => $a_attributes['entry_ts'],
                    'cancel_policy' => $s_cancel_policy,
                    'modify_cd'     => $a_attributes['modify_cd'],
                    'modify_ts'     => $a_attributes['modify_ts']
                ]);

                // 登録後失敗した場合はindexへ
                if (!$hotel_cancel_policy_create) {
                    DB::rollback();
                    return $this->index($request, [
                        'target_cd' => $s_target_cd,
                    ])->with(['errors' => ['データを更新できませんでした。']]);
                };
            }

            // 施設情報ページを更新に設定
            $o_hotel_cancel_policy->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            return redirect()
                ->route('ctl.htl_hotel_cancel.index', ['target_cd' => $s_target_cd])
                ->with(['guides' => ['キャンセルポリシーの更新が完了しました。']]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // キャンセルポリシーの削除
    public function deletecancelpolicy(Request $request)
    {
        try {
            $s_target_cd = $request->input('target_cd');
            $s_cancel_policy = $request->input('cancel_policy');

            // トランザクション開始
            DB::beginTransaction();

            // インスタンスの取得
            $o_hotel_cancel_policy = new HotelCancelPolicy();

            // キャンセルポリシーの削除
            $a_hotel_cancel_policy = $o_hotel_cancel_policy->where(['hotel_cd' => $s_target_cd])->delete();

            // 施設情報ページを更新に設定
            $a_attributes = [];
            $a_attributes['hotel_cd']    = $s_target_cd;
            $a_attributes['entry_cd']    = 'entry_cd';  // TODO $this->box->info->env->action_cd
            $a_attributes['entry_ts']    = now();
            $a_attributes['modify_cd']   = 'modify_cd'; // TODO $this->box->info->env->action_cd
            $a_attributes['modify_ts']   = now();

            $o_hotel_cancel_policy->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            return redirect()
                ->route('ctl.htl_hotel_cancel.index', ['target_cd' => $s_target_cd])
                ->with(['guides' => ['標準のキャンセルポリシーへ戻しました。']]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // キャンセル料率の登録
    public function create(HtlHotelCancelRateRequest $request)
    {
        try {
            $s_target_cd = $request->input('target_cd');
            $a_index = $request->input('index');
            $a_days = $request->input('days');
            $a_cancel_rate = $request->input('cancel_rate');

            // バリデーションエラー時は入力値
            if (is_null($s_target_cd)) {
                $s_target_cd = $request->old('target_cd');
                $a_index = $request->old('index');
                $a_days = $request->old('days');
                $a_cancel_rate = $request->old('cancel_rate');
            }

            // トランザクション開始
            DB::beginTransaction();

            // インスタンスの取得
            $o_hotel_cancel_rate = new HotelCancelRate();

            // 更新時のみold_daysがパラメーターとして来る
            $old_days = $request->input('old_days');
            if (!empty($old_days)) {
                // 更新対象の取得処理
                $a_hotel_cancel_rate = $o_hotel_cancel_rate->where(array(
                    'hotel_cd'   => $s_target_cd,
                    'days'       => $old_days
                ))->first();

                // 存在すれば削除
                if (!empty($a_hotel_cancel_rate)) {
                    // 先に削除処理
                    $o_hotel_cancel_rate->where([
                        'hotel_cd'   => $a_hotel_cancel_rate['hotel_cd'],
                        'days'       => $a_hotel_cancel_rate['days']
                    ])->delete();
                }
            }

            // モデルを取得
            $o_models_hotel = new Hotel();

            // キャンセル料率の一覧を取得
            $hotel_cancel_rate_model = new HotelCancelRate();
            $a_hotel_cancel_rates['values'] = $hotel_cancel_rate_model->where([
                'hotel_cd' => $s_target_cd
            ])->orderBy('days', 'asc')->get();

            // tplに存在する日数分だけループを行う
            foreach ($a_index as $key => $value) {
                // 重複チェック
                $duplicate_chk = $o_hotel_cancel_rate->where([
                    'hotel_cd'      => $s_target_cd,
                    'days'          => $a_days[$key]
                ])->exists();

                if ($duplicate_chk) {
                    DB::rollback();
                    return redirect()
                        ->route('ctl.htl_hotel_cancel.index', ['target_cd' => $s_target_cd])
                        ->with(['errors' => ['同じ日数が既に存在します。']]);
                }

                $a_attributes = [];
                $a_attributes['hotel_cd']      = $s_target_cd;
                $a_attributes['days']          = $a_days[$key];
                $a_attributes['cancel_rate']   = $a_cancel_rate[$key];
                $a_attributes['policy_status'] = $request->input('policy_status');
                $a_attributes['entry_cd']      = 'entry_cd'; // TODO $this->box->info->env->action_cd
                $a_attributes['entry_ts']      = now();
                $a_attributes['modify_cd']     = 'modify_cd'; // TODO $this->box->info->env->action_cd
                $a_attributes['modify_ts']     = now();

                // データ登録＆施設情報ページを更新に設定
                $hotel_cancel_rate_create = $o_hotel_cancel_rate->saveAction($a_attributes);

                if (!$hotel_cancel_rate_create) {
                    DB::rollback();
                    return $this->index($request, [
                        'target_cd' => $s_target_cd,
                    ])->with(['errors' => ['データを更新できませんでした。']]);
                };
            }

            // 施設キャンセル料率の「無断不泊」分を生成
            if (!$this->createDefaultCancelRate($request->all())) {
                DB::rollback();
                return $this->index($request, [
                    'target_cd' => $s_target_cd,
                ])->with(['errors' => ['データを更新できませんでした。']]);
            }

            // コミット
            DB::commit();

            return redirect()
                ->route('ctl.htl_hotel_cancel.index', ['target_cd' => $s_target_cd])
                ->with(['guides' => ['キャンセル料率の更新が完了しました。']]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // キャンセル料率の削除
    public function delete(Request $request)
    {
        try {
            $s_target_cd = $request->input('target_cd');
            $days = $request->input('days');

            // トランザクション開始
            DB::beginTransaction();

            // モデルを取得
            $o_models_hotel = new Hotel();

            // インスタンスの取得
            $o_hotel_cancel_rate = new HotelCancelRate();

            // 先に削除処理
            $o_hotel_cancel_rate->where(array(
                'hotel_cd'   => $s_target_cd,
                'days'       => $days
            ))->delete();

            // 施設情報ページを更新に設定
            $a_attributes = [];
            $a_attributes['hotel_cd']      = $s_target_cd;
            $a_attributes['entry_cd']      = 'entry_cd'; // TODO $this->box->info->env->action_cd
            $a_attributes['entry_ts']      = now();
            $a_attributes['modify_cd']     = 'modify_cd'; // TODO $this->box->info->env->action_cd
            $a_attributes['modify_ts']     = now();

            $o_hotel_cancel_rate->hotel_modify($a_attributes);

            // コミット
            DB::commit();

            return redirect()
                ->route('ctl.htl_hotel_cancel.index', ['target_cd' => $request->input('target_cd')])
                ->with(['guides' => ['キャンセル料率の削除が完了しました。']]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // キャンセル料率の適用切り替え
    public function switch(Request $request)
    {
        try {
            $s_target_cd = $request->input('target_cd');
            $policy_status = $request->input('policy_status');
            // トランザクション開始
            DB::beginTransaction();

            // モデルを取得
            $o_models_hotel = new Hotel();

            // キャンセル料率の一覧を取得
            $hotel_cancel_rate_model = new HotelCancelRate();
            $a_hotel_cancel_rates['values'] = $hotel_cancel_rate_model->where([
                'hotel_cd' => $s_target_cd
            ])->orderBy('days', 'asc')->get();

            $a_attributes = [];
            $a_attributes['hotel_cd']      = $s_target_cd;
            $a_attributes['entry_cd']      = 'entry_cd'; // TODO $this->box->info->env->action_cd
            $a_attributes['entry_ts']      = now();
            $a_attributes['modify_cd']     = 'modify_cd'; // TODO $this->box->info->env->action_cd
            $a_attributes['modify_ts']     = now();

            // 施設任意登録キャンセルポリシーを選択して、キャンセル料率の一覧が存在しない場合は無断不泊料金レコードを追加する
            if (empty($a_hotel_cancel_rates['values']) && $policy_status == '1') {
                // インスタンスの取得
                $o_hotel_cancel_rate = new HotelCancelRate();

                // 登録処理
                $o_hotel_cancel_rate->create([
                    'hotel_cd'      => $s_target_cd,
                    'days'          => -1,
                    'cancel_rate'   => 100,
                    'policy_status' => $policy_status,
                    'entry_cd'      => $a_attributes['entry_cd'],
                    'entry_ts'      => $a_attributes['entry_ts'],
                    'modify_cd'     => $a_attributes['modify_cd'],
                    'modify_ts'     => $a_attributes['modify_ts'],
                ]);
            }

            // 対象レコードの適用切り替えを行う
            foreach ($a_hotel_cancel_rates['values'] as $value) {
                // インスタンスの取得
                $o_hotel_cancel_rate = new HotelCancelRate();

                // 情報の取得
                $o_hotel_cancel_rate->where([
                    'hotel_cd'   => $value['hotel_cd'],
                    'days'       => $value['days']
                ])->update([
                    'policy_status' => $policy_status,
                    'modify_cd'     => $a_attributes['modify_cd'],
                    'modify_ts'     => $a_attributes['modify_ts']
                ]);
            }

            // 施設情報ページを更新に設定
            $o_hotel_cancel_rate->hotel_modify($a_attributes);

            // コミット
            DB::commit();


            // 0:適用外 1:適用中 施設単位に設定されます。
            if ($policy_status == 1) {
                $guides = '「施設任意登録キャンセルポリシー」を適用中に変更しました。';
            } else {
                $guides = '「標準約款キャンセルポリシー」を適用中に変更しました。';
            }
            return redirect()
                ->route('ctl.htl_hotel_cancel.index', ['target_cd' => $request->input('target_cd')])
                ->with(['guides' => [$guides]]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 施設キャンセル料率・プランキャンセル料率の「無断不泊」分を生成
    //======================================================================
    private function createDefaultCancelRate($a_form_params)
    {
        try {
            // インスタンス生成
            $o_hotel_cancel_rate     = new HotelCancelRate();

            // 施設キャンセル料率「無断不泊」が存在するかチェックし、無ければ作成
            $a_find_hotel_cancel_rate = $o_hotel_cancel_rate->where(['hotel_cd' => $a_form_params['target_cd'], 'days' => -1])->first();
            if (empty($a_find_hotel_cancel_rate)) {
                // 登録値設定
                $o_hotel_cancel_rate_create = $o_hotel_cancel_rate->create(
                    [
                        'hotel_cd'      => $a_form_params['target_cd'],
                        'days'          => -1,
                        'cancel_rate'   => 100,
                        'policy_status' => $a_form_params['policy_status'],
                        'entry_cd'      => 'entry_cd',  // TODO $this->box->info->env->action_cd,
                        'entry_ts'      => now(),
                        'modify_cd'     => 'modify_cd', // TODO $this->box->info->env->action_cd,
                        'modify_ts'     => now()
                    ]
                );

                if (!$o_hotel_cancel_rate_create) {
                    return false;
                }
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 自動延長ロック中であるか？
    //
    // example
    //  >> true  : ロック中
    //  >> false : ロック中でない
    public function isLocked($s_target_cd)
    {
        try {
            $s_sql =
                <<<SQL
					select	lock_status
					from	extend_switch
					where	hotel_cd = :hotel_cd
SQL;

            $a_extend_switch = DB::select($s_sql, ['hotel_cd' => $s_target_cd]);

            return $a_extend_switch[0]->lock_status === 0;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
