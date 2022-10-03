<?php

namespace App\Http\Controllers\ctl;

class BrPartnerCustomerController extends _commonController
{
    public function search()
    {
        // オブジェクトの取得
        // TODO:


        $v = new \stdClass;
        $v->assign = new \stdClass;
        $v->assign->search_params = [
            'key1' => 'item1',
            'key2' => 'item2',
            'customer_id' => 'non_output',
        ];
        $v->assign->customers = [
            [
                'customer_id' => '1',
                'customer_nm' => '株式会社日本旅行',
                'person_post' => '', // 空も用
                'person_nm' => '辻井　康一', // 空も用意
                'mail_send' => '1', // 0も用意
                'tel' => '0357193741',
                'fax' => '',
                'email_decrypt' => 'hideyuki_akiyama@nta.co.jp,coichi_tsujii@nta.co.jp',
                'billpay_day' => '10',
                'billpay_required_month' => '111111111111', // 月ごとに 0/1
                'billpay_charge_min' => 50000,
                'site_cd' => 'site_cd_val', // 空も用意
                'site_nm' => 'BestReserve',
                'partner_cd' => '0000000000', // 空も用意
                'affiliate_cd' => 'affiliate_cd_val', // 空も用意
                'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
                'stock_cnt' => '716',
            ],
            [
                'customer_id' => '3',
                'customer_nm' => '株式会社ベストリザーブ',
                'person_post' => '企画・開発部', // 空も用
                'person_nm' => '嶋田　至', // 空も用意
                'mail_send' => '0', // 0も用意
                'tel' => '06-6253-3800',
                'fax' => '06-6253-3801',
                'email_decrypt' => 'dev@bestrsv.com',
                'billpay_day' => '8',
                'billpay_required_month' => '100100001000', // 月ごとに 0/1
                'billpay_charge_min' => 50000,
                'site_cd' => '1', // 空も用意
                'site_nm' => 'BestReserve',
                'partner_cd' => '0000000000', // 空も用意
                'affiliate_cd' => 'affiliate_cd_val', // 空も用意
                'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
                'stock_cnt' => '0',
            ],
            [
                'customer_id' => '405',
                'customer_nm' => '株式会社カカクコム',
                'person_post' => 'サービス事業本部　サービスマーケティング1部', // 空も用
                'person_nm' => '登坂温美', // 空も用意
                'mail_send' => '0', // 0も用意
                'tel' => '03-4530-6412',
                'fax' => '',
                'email_decrypt' => 'koichi.ami@bestrsv.com',
                'billpay_day' => '8',
                'billpay_required_month' => '111111111111', // 月ごとに 0/1
                'billpay_charge_min' => 0,
                'site_cd' => 'a', // 空も用意
                'site_nm' => '旅行の口コミサイト「フォートラベル」',
                'partner_cd' => '', // 空も用意
                'affiliate_cd' => '0A30000003', // 空も用意
                'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
                'stock_cnt' => '0',
            ],
            [
                'customer_id' => 'customer_id_val',
                'customer_nm' => 'customer_nm_val',
                'person_post' => 'person_post_val', // 空も用
                'person_nm' => 'person_nm_val', // 空も用意
                'mail_send' => '1', // 0も用意
                'tel' => '080-1234-5678',
                'fax' => '080-9876-5432',
                'email_decrypt' => 'abc@sample.com,def@sample.co.jp',
                'billpay_day' => '25',
                'billpay_required_month' => '000000000000', // 月ごとに 0/1
                'billpay_charge_min' => 10000,
                'site_cd' => 'site_cd_val', // 空も用意
                'site_nm' => 'site_num_val',
                'partner_cd' => 'partner_cd_val', // 空も用意
                'affiliate_cd' => 'affiliate_cd_val', // 空も用意
                'sales_cnt' => '1', // DB から取得するとしたら、文字列型になりそう
                'stock_cnt' => '3',
            ],
            [
                'customer_id' => 'customer_id_val2',
                'customer_nm' => 'customer_nm_val2',
                'person_post' => '', // 空も用
                'person_nm' => '', // 空も用意
                'mail_send' => '0', // 0も用意
                'tel' => '080-1234-5611',
                'fax' => '080-9876-5422',
                'email_decrypt' => 'abc2@sample.com,def2@sample.co.jp',
                'billpay_day' => '10',
                'billpay_required_month' => '110100011100', // 月ごとに 0/1
                'billpay_charge_min' => 20000,
                'site_cd' => '', // 空も用意
                'site_nm' => 'site_num_val2',
                'partner_cd' => '', // 空も用意
                'affiliate_cd' => '', // 空も用意
                'sales_cnt' => 5,
                'stock_cnt' => 8,
            ],
        ];
        $v->assign->search_params = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $v->user = new \stdClass;
        $v->user->operator = new \stdClass;
        $v->user->operator->is_login = true;
        $v->user->operator->is_staff = true;
        $v->user->operator->staff_nm = 'staff_name_val';
        $v->env = [
            'controller' => "brtop",
            'action' => "index",
            'source_path' => 'source_path_val',
            'module' => 'module_val',
            'path_base_module' => 'ctl/statics',
        ];
        $v->config = new \stdClass;
        $v->config->environment = new \stdClass;
        $v->config->environment->status = 'test';

        return view('ctl.brPartnerCustomer.search', ['v' => $v]);
    }
}
