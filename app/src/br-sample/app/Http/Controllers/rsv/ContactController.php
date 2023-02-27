<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\rsv\_commonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Log;
use App\Common\Traits;
use App\Models\Contact;
use App\Http\Requests\CustomerContactRequest;
use Illuminate\Support\Facades\Validator;

class ContactController extends _commonController
{
    use Traits;

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
}
