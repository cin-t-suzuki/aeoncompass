<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelStatus;
use App\Models\MastCity;
use App\Models\MastPref;
use App\Models\MastWard;
use App\Services\BrHotelRegisterService as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

// MEMO: 移植元では、 BrhotelController に一緒くたにされていた。
class BrHotelRegisterController extends Controller
{
    /**
     * 施設情報入力
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function new(Request $request)
    {
        $guides = ['施設登録の際はウィザードに添ってSTEP 6/6 まで必ず完了してください。'];

        /* 初期表示値 or 戻りの入力値 を取得 */
        // 登録処理からの戻りで session にデータがある場合、 old を使って取得
        // そうでない場合、初期表示（第2引数でデフォルト値を指定）
        // HACK: （工数次第）画面側で old ヘルパ関数を使うほうが一般的と思われる（更新処理と合わせての修正が必要）
        $displayHotel = $request->old('Hotel', [
            'hotel_cd'          => null,
            'hotel_category'    => null,
            'hotel_nm'          => null,
            'hotel_kn'          => null,
            'hotel_old_nm'      => null,
            'postal_cd'         => null,
            'pref_id'           => null,
            'city_id'           => null,
            'ward_id'           => null,
            'address'           => null,
            'tel'               => null,
            'fax'               => null,
            'room_count'        => null,
            'check_in'          => null,
            'check_in_end'      => null,
            'check_in_info'     => null,
            'check_out'         => null,
            'midnight_status'   => null,
        ]);
        $displayHotelControl = $request->old('Hotel_Control', [
            'stock_type' => null,
        ]);

        if (!array_key_exists('city_id', $displayHotel)) {
            $displayHotel['city_id'] = null;
        }
        if (!array_key_exists('ward_id', $displayHotel)) {
            $displayHotel['ward_id'] = null;
        }

        // 都道府県
        $mastPrefs = (new MastPref())->getMastPrefs();
        // 市
        $mastCities = ['values' => []];
        if (!is_null($displayHotel['pref_id'])) {
            $mastCities = (new MastCity())->getMastCities($displayHotel['pref_id']);
        }
        // 区
        $mastWards = null;
        if (!is_null($displayHotel['city_id'])) {
            $mastWards = (new MastWard())->getMastWards($displayHotel['city_id']);
        }

        $errors = $request->session()->get('errors', []);

        return view('ctl.brhotel.new', [
            'action'        => 'new',
            'guides'        => $guides,
            'errors'        => $errors,

            'mast_prefs'    => $mastPrefs,
            'mast_cities'   => $mastCities,
            'mast_wards'    => $mastWards,

            'hotel'         => $displayHotel,
            'hotel_control' => $displayHotelControl,
            'target_cd'     => $request->input('target_cd'),
        ]);
    }

    /**
     * 施設情報登録
     *
     * @param Request $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Service $service)
    {
        $inputHotel         = $request->input('Hotel');
        $inputHotelControl  = $request->input('Hotel_Control');

        /* validation */
        // TODO: カナ関連のをカスタムバリデーション
        $rules = [
            // 'Hotel.hotel_cd'          => ['required', 'regex:/\A[^ｦ-ﾟ]*\z/', 'between:0,10'],
            'Hotel.hotel_category'    => ['regex:/\A(a|b|c|j)\z/'],
            'Hotel.hotel_nm'          => [
                'required',
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,50'
            ],
            'Hotel.hotel_kn'          => [
                'required',
                // 'regex:/\A[ァ-ヶ　ー ]*\z/',
                'between:0,150'
            ],
            'Hotel.hotel_old_nm'      => [
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,50'
            ],
            'Hotel.postal_cd'         => ['required', 'regex:/\A\d{3}[-]\d{4}\z/'],
            'Hotel.pref_id'           => ['required', 'integer', 'numeric', 'digits_between:0,2'],
            'Hotel.city_id'           => ['required', 'integer', 'numeric', 'digits_between:0,20'],
            'Hotel.ward_id'           => ['integer', 'numeric', 'digits_between:0,20'],
            'Hotel.address'           => [
                'required',
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,100'
            ],

            // custom validation TODO: 電話番号、郵便番号
            'Hotel.tel'               => ['required', 'regex:/(\A0\d{1,4}?-\d{1,4}?-\d{1,4}\z|\A\d{9,12}\z)/'],
            'Hotel.fax'               => ['regex:/(\A0\d{1,4}?-\d{1,4}?-\d{1,4}\z|\A\d{9,12}\z)/'],
            'Hotel.room_count'        => ['integer', 'numeric', 'between:0,9999'],
            'Hotel.check_in'          => ['required', 'regex:/\A\d{2}:\d{2}\z/'],
            'Hotel.check_in_end'      => ['regex:/\A\d{2}:\d{2}\z/'], // TODO: 独自チェック check_in より後であることをバリデーション
            'Hotel.check_in_info'     => [
                // 'regex:/\A[^ｦ-ﾟ]*\z/',
                'between:0,75'
            ],
            'Hotel.check_out'         => ['required', 'regex:/\A\d{2}:\d{2}\z/'],
            'Hotel.midnight_status'   => ['required', 'regex:/\A(0|1)\z/'],

            'Hotel_Control.stock_type'  => [],
        ];

        // TODO:
        $messages = [
            'regex' => '[:attribute] に正規表現違反があります。',
        ];
        $attributes = [
            'Hotel.hotel_cd'        => '施設コード',
            'Hotel.order_no'        => '表示順序',
            'Hotel.hotel_category'  => '施設区分',
            'Hotel.hotel_nm'        => '施設名称',
            'Hotel.hotel_kn'        => '施設名称かな',
            'Hotel.hotel_old_nm'    => '旧施設名称',
            'Hotel.postal_cd'       => '郵便番号',
            'Hotel.pref_id'         => '都道府県',
            'Hotel.city_id'         => '市',
            'Hotel.ward_id'         => '区',
            'Hotel.address'         => '住所',
            'Hotel.tel'             => '電話番号',
            'Hotel.fax'             => 'ＦＡＸ番号',
            'Hotel.room_count'      => '保有部屋数',
            'Hotel.check_in'        => 'チェックイン開始時刻',
            'Hotel.check_in_end'    => 'チェックイン終了時刻',
            'Hotel.check_in_info'   => 'チェックイン時刻コメント',
            'Hotel.check_out'       => 'チェックアウト時刻',
            'Hotel.midnight_status' => '深夜受付状態',
            'Hotel.accept_status'   => '予約受付状態',
            'Hotel.accept_auto'     => '予約受付状態自動更新有無',
            'Hotel.accept_dtm'      => '予約受付状態更新日時',
            'Hotel_Control.stock_type' => '仕入タイプ',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $validator->errors()->all(),
                ]);
        }

        /* データ整形 */
        // hotel_cd 採番
        // TODO: AC 用にカスタマイズが必要か。
        if ($inputHotelControl['stock_type'] == HotelControl::STOCK_TYPE_SANPU) {
            // 三普用の施設の場合
            $hotelCd = $service->getHotelCdSanpu();
        } else {
            // 受託販売  買取販売の場合
            $hotelCd = $service->getHotelCd();
        }

        $hotel                  = $service->makeHotelData($hotelCd, $inputHotel);
        $hotelInsuranceWeather  = $service->makeHotelInsuranceWeatherData($hotelCd, $hotel);
        $denyLists              = $service->makeDenyListsData($hotelCd);

        /* DB登録処理 */
        $errorMessages = $service->store($hotel, $hotelInsuranceWeather, $denyLists);
        if (count($errorMessages) > 0) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $errorMessages,
                ]);
        }

        /* 結果表示用データ取得 */
        $registeredHotel = (new Hotel())->selectByKey($hotelCd);

        // 都道府県
        $mastPrefs = null;
        $mastPrefs = (new MastPref())->selectByKey($registeredHotel['pref_id']);

        // 市
        $mastCities = null;
        if (array_key_exists('city_id', $registeredHotel)) {
            $mastCities = (new MastCity())->selectByKey($registeredHotel['city_id']);
        }

        // 区
        $mastWards = null;
        if (array_key_exists('ward_id', $registeredHotel)) {
            $mastWards = (new MastWard())->selectByKey($registeredHotel['ward_id']);
        }

        return view('ctl.brhotel.create', [
            'hotel'             => $registeredHotel,
            'a_mast_pref'       => $mastPrefs,
            'a_mast_city'       => $mastCities,
            'a_mast_ward'       => $mastWards,
            'target_cd'         => $request->input('target_cd'),
            'target_stock_type' => $inputHotelControl['stock_type'],
        ]);
    }

    /**
     * 施設管理情報入力 (STEP 3/6)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function management(Request $request)
    {
        $hotelCd = $request->input('target_cd');
        $a_hotel = Hotel::find($hotelCd);

        /* 初期表示値 or 戻りの入力値 を取得 */
        // 登録処理からの戻りで session にデータがある場合、 old を使って取得
        // そうでない場合、初期表示（第2引数でデフォルト値を指定）
        // HACK: （工数次第）画面側で old ヘルパ関数を使うほうが一般的と思われる（更新処理と合わせての修正が必要）
        $hotelAccount = (object)$request->old('Hotel_Account', [
            'account_id_begin'  => null,
            'password'          => null,
            'accept_status'     => null,
        ]);
        $hotelPerson = (object)$request->old('Hotel_Person', [
            'person_post'   => null,
            'person_nm'     => null,
            'person_tel'    => null,
            'person_fax'    => null,
            'person_email'  => null,
            'accept_status' => null,
        ]);
        $hotelStatus = (object)$request->old('Hotel_Status', [
            'contract_ymd'  => null,
            'open_ymd'      => null,
        ]);

        return view('ctl.brhotel.management', [
            'guides' => ['表示されています。'], // TODO: to be deleted

            // tpl新規時判断用
            'status'         => 'new',

            'hotel'          => $a_hotel,
            'hotel_account'  => $hotelAccount,
            'hotel_person'   => $hotelPerson,
            'hotel_status'   => $hotelStatus,
            'target_cd'      => $hotelCd,
            'target_stock_type' => $request->input('target_stock_type'),

            // MEMO: 移植元では、更新の場合のみ設定されている値。
            // 未定義だと動作しないため、干渉しない値であろうを設定している。
            'disp' => null,
        ]);
    }

    public function createManagement(Request $request, Service $service)
    {
        $hotelCd            = $request->input('target_cd');
        $inputHotelAccount  = $request->input('Hotel_Account');
        $inputHotelPerson   = $request->input('Hotel_Person');
        $inputHotelStatus   = $request->input('Hotel_Status');

        /* validation */
        // TODO: バリデーションルール記述
        $rules = [
            // TODO: 独自バリデーション (password と異なる、 hotel_account.account_id で一意)
            'Hotel_Account.account_id_begin'    => ['required', 'between:0,10'],

            // TODO: 独自バリデーション(ID と password が一致の場合エラー)
            'Hotel_Account.password'            => ['required', 'regex:/\A[A-Z0-9]{1,10}\z/'],

            'Hotel_Account.accept_status'       => ['required'],

            // TODO: hotel_person すべて、半角カナ禁止バリデーション
            'Hotel_Person.person_post'  => ['between:0,32'],
            'Hotel_Person.person_nm'    => ['required', 'between:0,32'],
            'Hotel_Person.person_tel'   => ['required', 'between:0,15'], // TODO: 電話番号バリデーション, between 不要か。
            'Hotel_Person.person_fax'   => ['between:0,15'], // TODO: 電話番号バリデーション, between 不要か。
            'Hotel_Person.person_email' => ['between:0,128'], // TODO: メールアドレスバリデーション, between 不要か。

            'Hotel_Status.entry_status' => ['required', Rule::in([0, 1, 2])], // hidden で 1 (登録作業中) 固定
            'Hotel_Status.contract_ymd' => [],
            'Hotel_Status.open_ymd'     => [],
            'Hotel_Status.close_dtm'    => [], // 入力値ではない
        ];
        $messages = [];
        $attributes = [
            // 'Hotel_Account.hotel_cd'         => '施設コード',
            'Hotel_Account.account_id_begin' => '入力アカウントID',
            // 'Hotel_Account.account_id'       => 'アカウントID',
            'Hotel_Account.password'         => 'パスワード',
            'Hotel_Account.accept_status'    => 'ステータス',

            // 'Hotel_Person.hotel_cd'     => '施設コード',
            'Hotel_Person.person_post'  => '担当者役職',
            'Hotel_Person.person_nm'    => '担当者名称',
            'Hotel_Person.person_tel'   => '担当者電話番号',
            'Hotel_Person.person_fax'   => '担当者ファックス番号',
            'Hotel_Person.person_email' => '担当者電子メールアドレス',

            // 'Hotel_Status.hotel_cd'     => '施設コード',
            // 'Hotel_Status.entry_status' => '登録状態',
            'Hotel_Status.contract_ymd' => '契約日',
            'Hotel_Status.open_ymd'     => '公開日',
            // 'Hotel_Status.close_dtm'    => '解約日時',
        ];
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $validator->errors()->all(),
                ]);
        }

        /* データ整形 */
        $hotelAccount   = $service->makeHotelAccountData($hotelCd, $inputHotelAccount);
        $hotelPerson    = $service->makeHotelPersonData($hotelCd, $inputHotelPerson);
        $hotelStatus    = $service->makeHotelStatusData($hotelCd, $inputHotelStatus);

        /* DB登録処理 */
        $errorMessages = $service->storeManagement($hotelAccount, $hotelPerson, $hotelStatus);
        if (count($errorMessages) > 0) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $errorMessages,
                ]);
        }

        /* 結果表示用データ取得 */
        $registeredHotelAccount = HotelAccount::find($hotelCd);
        $registeredHotelPerson  = HotelPerson::find($hotelCd);
        $registeredHotelStatus  = HotelStatus::find($hotelCd);

        // 日付の整形
        if (!is_null($registeredHotelStatus->contract_ymd)) {
            $registeredHotelStatus->contract_ymd = date('Y-m-d', strtotime($registeredHotelStatus->contract_ymd));
        }
        if (!is_null($registeredHotelStatus->open_ymd)) {
            $registeredHotelStatus->open_ymd = date('Y-m-d', strtotime($registeredHotelStatus->open_ymd));
        }
        if (!is_null($registeredHotelStatus->close_dtm)) {
            $registeredHotelStatus->close_dtm = date('Y-m-d H:i:s', strtotime($registeredHotelStatus->close_dtm));
        }

        // Notifyのインスタンスを取得 (次画面判断用)
        $existsHotelNotify = HotelNotify::where('hotel_cd', $hotelCd)->exists();

        return view('ctl.brhotel.create-management', [
            'target_cd'         => $hotelCd,
            'target_stock_type' => $request->input('target_stock_type'),

            'hotel_account'  => $registeredHotelAccount,
            'hotel_person'   => $registeredHotelPerson,
            'hotel_status'   => $registeredHotelStatus,

            'existsHotelNotify' => $existsHotelNotify,
        ]);
        return 'controller createManagement called!!';
    }
}
