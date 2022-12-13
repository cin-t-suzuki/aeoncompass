<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreHotelRequest,
    StoreManagementRequest,
    StoreStateRequest,
};
use App\Models\{
    Hotel,
    HotelAccount,
    HotelControl,
    HotelNotify,
    HotelPerson,
    HotelStatus,
    MastCity,
    MastPref,
    MastWard,
};
use App\Services\BrHotelRegisterService as Service;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;

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
     * @param StoreHotelRequest $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function create(StoreHotelRequest $request, Service $service)
    {
        $inputHotel         = $request->input('Hotel');
        $inputHotelControl  = $request->input('Hotel_Control');

        /* データ整形 */
        // hotel_cd 採番
        $hotelCd = $service->getHotelCd($inputHotel['pref_id']);

        $hotel                  = $service->makeHotelData($hotelCd, $inputHotel);
        $hotelInsuranceWeather  = $service->makeHotelInsuranceWeatherData($hotelCd, $hotel);

        // MEMO: イオンコンパスでは、リリース時は不要。
        // $denyLists              = $service->makeDenyListsData($hotelCd);
        $denyLists = [];

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

    /**
     * 施設管理情報登録
     *
     * @param StoreManagementRequest $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function createManagement(StoreManagementRequest $request, Service $service)
    {
        $hotelCd            = $request->input('target_cd');
        $inputHotelAccount  = $request->input('Hotel_Account');
        $inputHotelPerson   = $request->input('Hotel_Person');
        $inputHotelStatus   = $request->input('Hotel_Status');

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

        return view('ctl.brhotel.state', [
            'target_cd'     => $hotelCd,

            'hotel_notify'  => $hotel_notify,
            'hotel_control' => $a_hotel_control,
            'notify_device' => $notify_device,

            // MEMO: 移植元では、更新の場合のみ設定されている値。
            // 未定義だと動作しないため、干渉しない値であろうを設定している。
            'hotel_status'  => (object)['entry_status' => null],
        ]);
    }

    /**
     * 施設状態情報登録
     *
     * @param StoreStateRequest $request
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function createState(StoreStateRequest $request, Service $service)
    {
        $hotelCd                = $request->input('target_cd');
        $inputHotelNotify       = $request->input('Hotel_Notify');
        $inputHotelControl      = $request->input('Hotel_Control');
        $inputNotifyDevices     = $request->input('notify_device', []);

        /* データ整形 */
        $hotelNotify        = $service->makeHotelNotifyData($hotelCd, $inputHotelNotify, $inputNotifyDevices);
        $hotelControl       = $service->makeHotelControlData($hotelCd, $inputHotelControl);
        $hotelSystemVersion = $service->makeHotelSystemVersionData($hotelCd);

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

        // メールアドレス 復号
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $displayHotelNotify->notify_email = $cipher->decrypt($displayHotelNotify->notify_email);

        $notifyDevices  = $service->divideNotifyDeviceValueToArray($displayHotelNotify->notify_device);

        return view('ctl.brhotel.create-state', [
            'guides'        => ['施設情報の登録が完了いたしました。'],
            'target_cd'     => $hotelCd,

            'hotel_notify'  => $displayHotelNotify,
            'hotel_control' => $displayHotelControl,
            'notify_device' => $notifyDevices,
        ]);
    }
}
