<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\rsv\_commonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Log;
use App\Common\Traits;
use App\Models\Contact;
use App\Http\Requests\CustomerContactRequest;
use App\Http\Requests\HotelContactRequest;
use App\Models\MastPref;
use Illuminate\Support\Facades\Validator;

class ContactController extends _commonController
{
    use Traits;

    //※全体的に※スタイルシート制御は使用しなそうなので元ソースから移行時に削除（何かあれば追って追加）

    /**
     * 問い合わせ（ユーザ）
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function customer(Request $request)
    {

        $contactModel = new Contact();

        // 区分設定
        $division = $contactModel->setCustomerCategorys();

        // エラーメッセージがあれば取得し、バリデーションエラーとして追加
        $errors = $request->session()->get('errors', []);
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('errors', $errors);

        // エラー戻りであればフォームでの送信された値を適用する
        if ($request->input('form_data')) {
            $param = $request->input('form_data');
        } else {
            // パラメータ設定
            $params = $contactModel->setParamsCustomer($request);
            $param = $params['param'];
        }

        // // 入力された値の確認
        // if (!$this->is_empty($this->params('category'))) {
        //     $this->valid_customer();
        // }
        //最初のページ表示時点ではバリデーションはいらないのでは？削除でいい？

        // ビューを表示
            // 値がない時は空を渡す
        return view('rsv.contact.customer', [
            'category' => $param['category'] ?? '',
            'category_nm' => $division['category_nm'] ?? '',
            'categorys' => $division['categorys'] ?? [],
            'full_nm' => $param['full_nm'] ?? '',
            'account_id' => $param['account_id'] ?? '',
            'email' => $param['email'] ?? '',
            'rsv_cd' => $param['rsv_cd'] ?? '',
            'hotel_nm' => $param['hotel_nm'] ?? '',
            'date_ymd' => $param['date_ymd'] ?? '',
            'guest_nm' => $param['guest_nm'] ?? '',
            'note' => $param['note'] ?? ''
        ]);
    }

    /**
     * 問い合わせへ確認画面
     *
     * @param App\Http\Requests\CustomerContactRequest; $request
     * @return \Illuminate\Http\Response
     */
    public function customerConfirm(CustomerContactRequest $request)
    {
        $contactModel = new Contact();

        // 区分設定
        $division = $contactModel->setCustomerCategorys();

        // パラメータ設定
        $params = $contactModel->setParamsCustomer($request);
        $param = $params['param'];

        // // 入力された値の確認
        // $this->valid_customer('customer');
        // if ($this->box->item->error->has()) {
        //     return $this->_forward('customer');
        // }
        //独自バリデーションにも見えるが、再現できそうなのでFormRequest利用でいいか？

        //独自バリデーション内で以下だけ実装できていない
        // // 問い合わせ区分
        // if (!isset($this->_assign->categorys[$this->params('category')])) {
        //     $this->box->item->error->add($this->_assign->category_nm .'を選択してください。');
        // }
        //formリクエスト側での作成はできないため、↑は↓で実装
        if (!isset($division['categorys'][$request->input('category')])) {
            $errors[] = $division['category_nm'] . 'を選択してください。';
            $form_data = $request->all();
            return redirect()->route('rsv.contact.customer', [
                'form_data' => $form_data
            ])->with([
                'errors' => $errors
            ]);
        }

        // ビューを表示
            // 値がない時は空を渡す
            return view('rsv.contact.customerConfirm', [
                'category' => $param['category'] ?? '',
                'category_nm' => $division['category_nm'] ?? '',
                'categorys' => $division['categorys'] ?? [],
                'full_nm' => $param['full_nm'] ?? '',
                'account_id' => $param['account_id'] ?? '',
                'email' => $param['email'] ?? '',
                'rsv_cd' => $param['rsv_cd'] ?? '',
                'hotel_nm' => $param['hotel_nm'] ?? '',
                'date_ymd' => $param['date_ymd'] ?? '',
                'guest_nm' => $param['guest_nm'] ?? '',
                'note' => $param['note'] ?? ''
            ]);
    }


    /**
     * 問い合わせ完了
     *
     * @param App\Http\Requests\CustomerContactRequest; $request
     * @return \Illuminate\Http\Response
     */
    public function customercomplete(CustomerContactRequest $request)
    {

        $contactModel = new Contact();

        // 区分設定
        $division = $contactModel->setCustomerCategorys();

        // パラメータ設定
        $params = $contactModel->setParamsCustomer($request);
        $param = $params['param'];

        // // 入力された値の確認
        // $this->valid_customer();
        // if ($this->box->item->error->has()) {
        //     return $this->_forward('customer');
        // }
        //独自バリデーションにも見えるが、再現できそうなのでFormRequest利用でいいか？

        //独自バリデーション内で以下だけ実装できていない
        // // 問い合わせ区分
        // if (!isset($this->_assign->categorys[$this->params('category')])) {
        //     $this->box->item->error->add($this->_assign->category_nm .'を選択してください。');
        // }
        //formリクエスト側での作成はできないため、↑は↓で実装
        if (!isset($division['categorys'][$request->input('category')])) {
            $errors[] = $division['category_nm'] . 'を選択してください。';
            $form_data = $request->all();
            return redirect()->route('rsv.contact.customer', [
                'form_data' => $form_data
            ])->with([
                'errors' => $errors
            ]);
        }


        //TODO メール機能は追って実装する
        // // models_SendMail モデルの生成
        // $mail = new models_SendMail();

        // // アサイン登録
        // $this->box->item->assign = $this->_assign;

        // // トランザクション開始
        // $this->oracle->beginTransaction();

        // // 送信
        // $mail->contactCustomer($a_attributes);
        // $this->oracle->commit();

        // ビューを表示
            // 値がない時は空を渡す
        return view('rsv.contact.customerComplete', [
            'category' => $param['category'] ?? '',
            'category_nm' => $division['category_nm'] ?? '',
            'categorys' => $division['categorys'] ?? [],
            'full_nm' => $param['full_nm'] ?? '',
            'account_id' => $param['account_id'] ?? '',
            'email' => $param['email'] ?? '',
            'rsv_cd' => $param['rsv_cd'] ?? '',
            'hotel_nm' => $param['hotel_nm'] ?? '',
            'date_ymd' => $param['date_ymd'] ?? '',
            'guest_nm' => $param['guest_nm'] ?? '',
            'note' => $param['note'] ?? '',
        ]);
    }

    /**
     * ご意見・ご要望（ユーザ）
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function customerVoice(Request $request)
    {

        $contactModel = new Contact();

        // 区分設定
        $division = $contactModel->setCustomerVoiceCategorys();

        // エラーメッセージがあれば取得し、バリデーションエラーとして追加
        $errors = $request->session()->get('errors', []);
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('errors', $errors);

        // エラー戻りであればフォームでの送信された値を適用する
        if ($request->input('form_data')) {
            $param = $request->input('form_data');
        } else {
            // パラメータ設定
            $params = $contactModel->setParamsCustomer($request);
            $param = $params['param'];
        }

        // // 入力された値の確認
        // if (!$this->is_empty($this->params('category'))) {
        //     $this->valid_customer();
        // }
        //最初のページ表示時点ではバリデーションはいらないのでは？削除でいい？

        // ビューを表示
            // 値がない時は空を渡す
            return view('rsv.contact.customerVoice', [
                'category' => $param['category'] ?? '',
                'category_nm' => $division['category_nm'] ?? '',
                'categorys' => $division['categorys'] ?? [],
                'full_nm' => $param['full_nm'] ?? '',
                'account_id' => $param['account_id'] ?? '',
                'email' => $param['email'] ?? '',
                'rsv_cd' => $param['rsv_cd'] ?? '',
                'hotel_nm' => $param['hotel_nm'] ?? '',
                'date_ymd' => $param['date_ymd'] ?? '',
                'guest_nm' => $param['guest_nm'] ?? '',
                'note' => $param['note'] ?? ''
            ]);
    }

    /**
     * ご意見・ご要望へ確認画面
     *
     * @param App\Http\Requests\CustomerContactRequest; $request
     * @return \Illuminate\Http\Response
     */
    public function customerVoiceConfirm(CustomerContactRequest $request)
    {

        $contactModel = new Contact();

        // 区分設定
        $division = $contactModel->setCustomerVoiceCategorys();

        // パラメータ設定
        $params = $contactModel->setParamsCustomer($request);
        $param = $params['param'];

        // // 入力された値の確認
        // $this->valid_customer('customervoice');
        // if ($this->box->item->error->has()) {
        //     return $this->_forward('customervoice');
        // }
        //独自バリデーションにも見えるが、再現できそうなのでFormRequest利用でいいか？

        //独自バリデーション内で以下だけ実装できていない
        // // 問い合わせ区分
        // if (!isset($this->_assign->categorys[$this->params('category')])) {
        //     $this->box->item->error->add($this->_assign->category_nm .'を選択してください。');
        // }
        //formリクエスト側での作成はできないため、↑は↓で実装
        if (!isset($division['categorys'][$request->input('category')])) {
            $errors[] = $division['category_nm'] . 'を選択してください。';
            $form_data = $request->all();
            return redirect()->route('rsv.contact.customerVoice', [
                'form_data' => $form_data
            ])->with([
                'errors' => $errors
            ]);
        }

        // ビューを表示
            // 値がない時は空を渡す
            return view('rsv.contact.customerVoiceConfirm', [
                'category' => $param['category'] ?? '',
                'category_nm' => $division['category_nm'] ?? '',
                'categorys' => $division['categorys'] ?? [],
                'full_nm' => $param['full_nm'] ?? '',
                'account_id' => $param['account_id'] ?? '',
                'email' => $param['email'] ?? '',
                'rsv_cd' => $param['rsv_cd'] ?? '',
                'hotel_nm' => $param['hotel_nm'] ?? '',
                'date_ymd' => $param['date_ymd'] ?? '',
                'guest_nm' => $param['guest_nm'] ?? '',
                'note' => $param['note'] ?? ''
            ]);
    }

    /**
     * ご意見・ご要望完了
     *
     * @param App\Http\Requests\CustomerContactRequest; $request
     * @return \Illuminate\Http\Response
     */
    public function customerVoiceComplete(CustomerContactRequest $request)
    {
        $contactModel = new Contact();

        // 区分設定
        $division = $contactModel->setCustomerVoiceCategorys();

        // パラメータ設定
        $params = $contactModel->setParamsCustomer($request);
        $param = $params['param'];

        // // 入力された値の確認
        // $this->valid_customer();
        // if ($this->box->item->error->has()) {
        //     return $this->_forward('customervoice');
        // }
        //独自バリデーションにも見えるが、再現できそうなのでFormRequest利用でいいか？

        //独自バリデーション内で以下だけ実装できていない
        // // 問い合わせ区分
        // if (!isset($this->_assign->categorys[$this->params('category')])) {
        //     $this->box->item->error->add($this->_assign->category_nm .'を選択してください。');
        // }
        //formリクエスト側での作成はできないため、↑は↓で実装
        if (!isset($division['categorys'][$request->input('category')])) {
            $errors[] = $division['category_nm'] . 'を選択してください。';
            $form_data = $request->all();
            return redirect()->route('rsv.contact.customer', [
                'form_data' => $form_data
            ])->with([
                'errors' => $errors
            ]);
        }

        //TODO メール機能は追って実装する

        // // models_SendMail モデルの生成
        // $mail = new models_SendMail();

        // // アサイン登録
        // $this->box->item->assign = $this->_assign;

        // // トランザクション開始
        // $this->oracle->beginTransaction();

        // // 送信
        // $mail->contactCustomerVoice($a_attributes);
        // $this->oracle->commit();

        // // コミット
        // $this->set_assign();

        // ビューを表示
        // 値がない時は空を渡す
        return view('rsv.contact.customerVoiceComplete', [
            'category' => $param['category'] ?? '',
            'category_nm' => $division['category_nm'] ?? '',
            'categorys' => $division['categorys'] ?? [],
            'full_nm' => $param['full_nm'] ?? '',
            'account_id' => $param['account_id'] ?? '',
            'email' => $param['email'] ?? '',
            'rsv_cd' => $param['rsv_cd'] ?? '',
            'hotel_nm' => $param['hotel_nm'] ?? '',
            'date_ymd' => $param['date_ymd'] ?? '',
            'guest_nm' => $param['guest_nm'] ?? '',
            'note' => $param['note'] ?? '',
        ]);
    }

    // 都道府県の取得時に省く一覧
    private $notInByPrefId = [48];

    /**
     * ホテル関係者の方へ
     *
     * @return \Illuminate\Http\Response
     */
    public function hotel()
    {

        // // リクエストの取得
        // $a_params = $this->_request->getParams();
        //とっても渡していないようなので削除していいか？

        // インスタンスの設定
        $mastPrefModel = new MastPref(); //Models_Mast→MastPrefでいいか？

        // 海外を省く都道府県の一覧の取得
        $a_conditions['not_in_by_pref_id'] = $this->notInByPrefId;

        // 都道府県の一覧データを配列で取得
        $a_pref_data = $mastPrefModel->getMastPrefs($a_conditions);

        // ビューを表示
        return view('rsv.contact.hotel', [
            'pref_data' => $a_pref_data
        ]);
    }

    /**
     * ホテル関係者の方へ確認画面
     *
     * @param App\Http\Requests\HotelContactRequest $request
     * @return \Illuminate\Http\Response
     */
    public function hotelConfirm(HotelContactRequest $request)
    {
        // // 半角カナを全角カナに変換（配列はないので考慮しない）
        // foreach ($request->all() as $key => $value) {
        //     $this->_request->setParam($key, mb_convert_kana($value, 'KV'));

        //     // 全角を半角に変換
        //     if (
        //         $key == 'postal_cd' or $key == 'postal_cd2' or
        //         $key == 'tel'       or $key == 'tel2'       or
        //         $key == 'fax'       or
        //         $key == 'email'     or $key == 'email2'     or
        //         $key == 'url'
        //     ) {
        //         $this->_request->setParam($key, mb_convert_kana($value, 'a'));
        //     }
        // }

        // // 入力チェック
        // $this->valid_hotel();

        // // バリデート結果 （エラーがあった場合
        // if ($this->box->item->error->has()) {
        //     // entry アクションに転送します
        //     return $this->_forward('hotel');
        // }

        // MastPrefモデルの取得
        $mastPrefModel = new MastPref();

        // 都道府県の取得
        // 都道府県の取得
        $a_pref = $mastPrefModel->selectByKey($request->input('pref_id')); //find→selectByKeyでいいか？
        $a_pref2 = $mastPrefModel->selectByKey($request->input('pref_id2')); //find→selectByKeyでいいか？

        // // テンプレートにアサイン
        // $this->box->item->assign->hotel_nm         = $this->correct_params('hotel_nm');
        // $this->box->item->assign->person_post      = $this->correct_params('person_post');
        // $this->box->item->assign->person_nm        = $this->correct_params('person_nm');
        // $this->box->item->assign->person_nm_kana   = $this->correct_params('person_nm_kana');
        // $this->box->item->assign->postal_cd        = $this->correct_params('postal_cd');
        // $this->box->item->assign->pref_id          = $this->correct_params('pref_id');
        // $this->box->item->assign->pref_nm          = $a_pref['pref_nm'];
        // $this->box->item->assign->address          = $this->correct_params('address');
        // $this->box->item->assign->tel              = $this->correct_params('tel');
        // $this->box->item->assign->fax              = $this->correct_params('fax');
        // $this->box->item->assign->email            = $this->correct_params('email');
        // $this->box->item->assign->url              = $this->correct_params('url');
        // $this->box->item->assign->travel_trade     = $this->correct_params('travel_trade');
        // $this->box->item->assign->estimate_dtm     = $this->correct_params('estimate_dtm');
        // $this->box->item->assign->send_status      = $this->correct_params('send_status');
        // $this->box->item->assign->postal_cd2       = $this->correct_params('postal_cd2');
        // $this->box->item->assign->pref_id2         = $this->correct_params('pref_id2');
        // $this->box->item->assign->pref_nm2         = $a_pref2['pref_nm'];
        // $this->box->item->assign->address2         = $this->correct_params('address2');
        // $this->box->item->assign->hotel_nm2        = $this->correct_params('hotel_nm2');
        // $this->box->item->assign->person_post2     = $this->correct_params('person_post2');
        // $this->box->item->assign->person_nm2       = $this->correct_params('person_nm2');
        // $this->box->item->assign->person_nm_kana2  = $this->correct_params('person_nm_kana2');
        // $this->box->item->assign->tel2             = $this->correct_params('tel2');
        // $this->box->item->assign->email2           = $this->correct_params('email2');
        // $this->box->item->assign->note             = $this->correct_params('note');


        // ビューを表示
        return view('rsv.contact.hotelConfirm', [
            'hotel_nm' => $request->input('hotel_nm'),
            'person_post' => $request->input('person_post'),
            'person_nm' => $request->input('person_nm'),
            'person_nm_kana' => $request->input('person_nm_kana'),
            'postal_cd' => $request->input('postal_cd'),
            'pref_id' => $request->input('pref_id'),
            'pref_nm' => $a_pref['pref_nm'],
            'address' => $request->input('address'),
            'tel' => $request->input('tel'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'url' => $request->input('url'),
            'travel_trade' => $request->input('travel_trade'),
            'estimate_dtm' => $request->input('estimate_dtm'),
            'send_status' => $request->input('send_status'),
            'postal_cd2' => $request->input('postal_cd2'),
            'pref_id2' => $request->input('pref_id2'),
            'pref_nm2' => $a_pref2['pref_nm'],
            'address2' => $request->input('address2'),
            'hotel_nm2' => $request->input('hotel_nm2'),
            'person_post2' => $request->input('person_post2'),
            'person_nm2' => $request->input('person_nm2'),
            'person_nm_kana2' => $request->input('person_nm_kana2'),
            'tel2' => $request->input('tel2'),
            'email2' => $request->input('email2'),
            'note' => $request->input('note')
        ]);
    }
}
