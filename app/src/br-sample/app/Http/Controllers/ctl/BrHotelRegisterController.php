<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelAccount;
use App\Models\HotelControl;
use App\Models\HotelNotify;
use App\Models\HotelPerson;
use App\Models\HotelStatus;
use App\Models\HotelSystemVersion;
use App\Models\MastCity;
use App\Models\MastPref;
use App\Models\MastWard;
use App\Rules\EmailMultiple;
use App\Rules\EmailSingle;
use App\Rules\OnlyFullWidthKatakana;
use App\Rules\PhoneNumber;
use App\Rules\PostalCode;
use App\Rules\WithoutHalfWidthKatakana;
use App\Services\BrHotelRegisterService as Service;
use App\Util\Models_Cipher;
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
     * HACK: (工数次第) validation は FormRequest オブジェクトで実装する
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
        $rules = [
            // 'Hotel.hotel_cd'          => ['required', new WithoutHalfWidthKatakana(), 'between:0,10'],
            'Hotel.hotel_category'    => ['regex:/\A(a|b|c|j)\z/'],
            'Hotel.hotel_nm'          => ['required', new WithoutHalfWidthKatakana(), 'between:0,50'],
            'Hotel.hotel_kn'          => ['required', new OnlyFullWidthKatakana(), 'between:0,150'],
            'Hotel.hotel_old_nm'      => [new WithoutHalfWidthKatakana(), 'between:0,50'],
            'Hotel.postal_cd'         => ['required', new PostalCode()],
            'Hotel.pref_id'           => ['required', 'integer', 'numeric', 'digits_between:0,2'],
            'Hotel.city_id'           => ['required', 'integer', 'numeric', 'digits_between:0,20'],
            'Hotel.ward_id'           => ['integer', 'numeric', 'digits_between:0,20'],
            'Hotel.address'           => ['required', new WithoutHalfWidthKatakana(), 'between:0,100'],

            'Hotel.tel'               => ['required', new PhoneNumber()],
            'Hotel.fax'               => [new PhoneNumber()],
            'Hotel.room_count'        => ['integer', 'numeric', 'between:0,9999'],
            'Hotel.check_in'          => ['required', 'regex:/\A\d{2}:\d{2}\z/'], // TODO: 時刻チェック
            'Hotel.check_in_end'      => ['regex:/\A\d{2}:\d{2}\z/'], // TODO: 独自チェック check_in より後であることをバリデーション
            'Hotel.check_in_info'     => [new WithoutHalfWidthKatakana(), 'between:0,75'], // TODO: 時刻チェック
            'Hotel.check_out'         => ['required', 'regex:/\A\d{2}:\d{2}\z/'], // TODO: 時刻チェック
            'Hotel.midnight_status'   => ['required', 'regex:/\A(0|1)\z/'], // TODO: radio button

            'Hotel_Control.stock_type'  => [], // TODO: radio button
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

    // HACK: (工数次第) validation は FormRequest オブジェクトで実装する
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

            'Hotel_Account.accept_status'       => ['required'], // TODO: radio button

            // hotel_person すべて、半角カナ禁止バリデーション
            'Hotel_Person.person_post'  => [new WithoutHalfWidthKatakana(), 'between:0,32'],
            'Hotel_Person.person_nm'    => [new WithoutHalfWidthKatakana(), 'required', 'between:0,32'],
            'Hotel_Person.person_tel'   => [new WithoutHalfWidthKatakana(), 'required', new PhoneNumber()], // TODO:PhoneNumber に通れば半角チェックは不要
            'Hotel_Person.person_fax'   => [new WithoutHalfWidthKatakana(), new PhoneNumber()], // TODO:PhoneNumber に通れば半角チェックは不要
            'Hotel_Person.person_email' => [new WithoutHalfWidthKatakana(), new EmailSingle(), 'between:0,128'], // TODO: between 不要か。

            'Hotel_Status.entry_status' => ['required', Rule::in([0, 1, 2])], // hidden で 1 (登録作業中) 固定 TODO: radio button
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

        // メールアドレス 復号
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $registeredHotelPerson->person_email = $cipher->decrypt($registeredHotelPerson->person_email);

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
    }

    public function state(Request $request)
    {
        $hotelCd     = $request->input('target_cd');
        $targetStockType = $request->input('target_stock_type');

        /* 戻りの入力値を取得、なければ初期表示値を設定 */
        // 登録処理からの戻りで session にデータがある場合、 old を使って取得
        // そうでない場合、初期表示（第2引数でデフォルト値を指定）
        // HACK: （工数次第）画面側で old ヘルパ関数を使うほうが一般的と思われる（更新処理と合わせての修正が必要）
        $hotel_notify  = (object)$request->old('Hotel_Notify', [
            'neppan_status' => null,
            'notify_status' => null,
            'faxpr_status'  => null,
            'notify_email'  => null,
            'notify_fax'    => null,
        ]);
        $a_hotel_control = (object)$request->old('Hotel_Control', [
            'stock_type'        => null,
            'checksheet_send'   => null,
            'charge_round'      => null,
            'stay_cap'          => null,
            'management_status' => null,
            'akafu_status'      => null,
        ]);
        if (is_null($a_hotel_control->stock_type)) {
            // 初回のみ施設情報画面で設定した値を取得する
            $a_hotel_control->stock_type = $targetStockType;
        }
        $notify_device = $request->old('notify_device', []);
        $version = $request->old('version', []);

        return view('ctl.brhotel.state', [
            'target_cd'     => $hotelCd,
            // 'target_stock_type' => $targetStockType,

            'hotel_notify'  => $hotel_notify,
            'hotel_control' => $a_hotel_control,
            'notify_device' => $notify_device,
            'version'       => $version,

            // MEMO: 移植元では、更新の場合のみ設定されている値。
            // 未定義だと動作しないため、干渉しない値であろうを設定している。
            // TODO: 確認
            'hotel_status'  => (object)['entry_status' => null],
        ]);
    }

    // HACK: (工数次第) validation は FormRequest オブジェクトで実装する
    public function createState(Request $request, Service $service)
    {
        $hotelCd                = $request->input('target_cd');
        $inputHotelNotify       = $request->input('Hotel_Notify');
        $inputHotelControl      = $request->input('Hotel_Control');
        $inputNotifyDevices     = $request->input('notify_device');
        $checkedSystemVersions  = $request->input('version');

        /* validation */
        // TODO:
        $rules = [
            // 必須入力チェック, 数字：数値チェック, 独自チェック
            // TODO: DB 側で validation するときは、配列から数値に変換した値で判定している。
            // ここで validation するときは、1, 2, 4, 8 いずれかであることをチェックする。
            // TODO: check box
            'notify_device' => ['required', 'integer', 'numeric', 'TODO:custom_notify_device_validate'],

            'Hotel_Notify.neppan_status'    => ['regex:/\A(0|1)\z/'], // パターンチェック TODO: radio button
            'Hotel_Notify.notify_status'    => ['required', 'regex:/\A(0|1)\z/'], // パターンチェック TODO: radio button
            // 'Hotel_Notify.notify_no'        => ['integer', 'numeric', 'between:0,10'], // 数字：数値チェック, 長さチェック
            'Hotel_Notify.notify_email'     => [new WithoutHalfWidthKatakana(), new EmailSingle(), 'between:0,500', 'TODO:custom_notify_email_validate'], // 独自チェック
            'Hotel_Notify.notify_fax'       => [new WithoutHalfWidthKatakana(), new PhoneNumber(), 'between:0,15', 'TODO:custom_notify_fax_validate'], // 独自チェック
            'Hotel_Notify.faxpr_status'     => ['required', 'regex:/\A(0|1)\z/'], // パターンチェック TODO: radio button

            'Hotel_Control.stock_type'          => ['required', 'regex:/\A[0-3]\z/'], // パターンチェック TODO: radio button
            'Hotel_Control.checksheet_send'     => ['required', 'regex:/\A[0-1]\z/'], // パターンチェック TODO: radio button
            'Hotel_Control.charge_round'        => ['integer', 'numeric', 'digits_between:0,3'], // 数字：数値チェック, 長さチェック TODO: radio button
            'Hotel_Control.stay_cap'            => ['integer', 'numeric', 'digits_between:0,2', 'TODO:custom_stay_cap_validate'], // 数字：数値チェック, 長さチェック, 独自チェック
            'Hotel_Control.management_status'   => ['required', 'regex:/\A[1-3]\z/'], // パターンチェック TODO: radio button
            'Hotel_Control.akafu_status'        => [], // TODO: 必要ない？ TODO: radio button

            'version' => [], // TODO: check box

            // 'target_cd' => [],
        ];
        $messages = [];
        $attributes = [
            'notify_device' => '通知媒体',

            'Hotel_Notify.neppan_status'    => 'ねっぱん通知ステータス',
            'Hotel_Notify.notify_status'    => '通知ステータス',
            // 'Hotel_Notify.notify_no'        => '通知No',
            'Hotel_Notify.notify_email'     => '通知電子メールアドレス',
            'Hotel_Notify.notify_fax'       => '通知ファックス番号',
            'Hotel_Notify.faxpr_status'     => 'FAXPR可否',

            'Hotel_Control.stock_type'          => '仕入形態',
            'Hotel_Control.checksheet_send'     => '送客リスト送付可否',
            'Hotel_Control.charge_round'        => '金額切り捨て桁',
            'Hotel_Control.stay_cap'            => '連泊限界数',
            'Hotel_Control.management_status'   => '利用方法',
            'Hotel_Control.akafu_status'        => '日本旅行在庫連携',

            'version' => '管理システムバージョン',
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
        $hotelNotify        = $service->makeHotelNotifyData($hotelCd, $inputHotelNotify, $inputNotifyDevices);
        $hotelControl       = $service->makeHotelControlData($hotelCd, $inputHotelControl);
        $hotelSystemVersion = $service->makeHotelSystemVersionData($hotelCd, $checkedSystemVersions);

        /* DB登録処理 */
        $errorMessages = $service->storeStatus($hotelNotify, $hotelControl, $hotelSystemVersion);
        if (count($errorMessages) > 0) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'errors' => $errorMessages,
                ]);
        }

        /* 結果表示用データ取得 */
        $displayHotelNotify         = HotelNotify::find($hotelCd);
        $displayHotelControl        = HotelControl::find($hotelCd);
        $displayHotelSystemVersion  = HotelSystemVersion::where('hotel_cd', $hotelCd)
            ->where('system_type', HotelSystemVersion::SYSTEM_TYPE_PLAN)
            ->first();

        // メールアドレス 復号
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $displayHotelNotify->notify_email = $cipher->decrypt($displayHotelNotify->notify_email);

        $notifyDevices  = $service->divideNotifyDeviceValueToArray($displayHotelNotify->notify_device);
        $versions       = $service->divideSystemVersionValueToArray($displayHotelSystemVersion->version);

        return view('ctl.brhotel.create-state', [
            'guides'        => ['施設情報の登録が完了いたしました。'],
            'target_cd'     => $hotelCd,

            'hotel_notify'  => $displayHotelNotify,
            'hotel_control' => $displayHotelControl,
            'notify_device' => $notifyDevices,

            'version'       => $versions,
        ]);
    }
}
