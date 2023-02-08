USE ac_travel;

-- 
CREATE TABLE `additional_zengin` (
    `zengin_ym` VARCHAR(6) BINARY COMMENT '1;処理年月コード;',
    `branch_id` BIGINT COMMENT '2;連番ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '4;施設名称;',
    `customer_id` BIGINT COMMENT '5;精算先ID;連番、シーケンスは使用しない',
    `customer_nm` VARCHAR(150) BINARY COMMENT '6;精算先名称;',
    `billpay_ymd` DATETIME COMMENT '7;請求支払処理年月日;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `factoring_bank_cd` VARCHAR(4) BINARY COMMENT '8;引落銀行コード;',
    `factoring_bank_branch_cd` VARCHAR(3) BINARY COMMENT '9;引落支店コード;',
    `factoring_bank_account_type` TINYINT COMMENT '10;引落口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `factoring_bank_account_no` VARCHAR(7) BINARY COMMENT '11;引落口座番号;数字7文字',
    `factoring_bank_account_kn` VARCHAR(90) BINARY COMMENT '12;引落口座名義（カナ）;半角カタカナ30文字',
    `factoring_cd` VARCHAR(12) BINARY COMMENT '13;引落顧客コード;',
    `reason` VARCHAR(1000) BINARY COMMENT '14;理由;',
    `reason_internal` VARCHAR(1000) BINARY COMMENT '15;備考（内部のみ）;',
    `additional_charge` BIGINT COMMENT '16;追加金額;',
    `staff_id` INT COMMENT '17;スタッフID;',
    `notactive_flg` VARCHAR(1) BINARY COMMENT '18;削除フラグ;0:有効 1:無効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '19;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '20;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '22;更新日時;'
);

ALTER TABLE
    `additional_zengin` COMMENT '引落追加額データ;口座振替の引落額に、前月の積残し分などの追加額を上乗せする';

-- 
CREATE TABLE `affiliater` (
    `affiliater_cd` VARCHAR(10) BINARY COMMENT '1;アフィリエイターコード;YYYYNNNNNN',
    `affiliater_nm` VARCHAR(192) BINARY COMMENT '2;アフィリエイター名称;',
    `url` VARCHAR(128) BINARY COMMENT '3;ウェブサイトアドレス;',
    `account_id` VARCHAR(32) BINARY COMMENT '4;アフィリエイトログインID;アフィリエイトサイト管理画面ログインID',
    `password` VARCHAR(64) BINARY COMMENT '5;アフィリエイトログインパスワード;アフィリエイトサイトログインパスワード',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `address` VARCHAR(300) BINARY COMMENT '7;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '8;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '9;ファックス番号;ハイフン含む',
    `person_post` VARCHAR(96) BINARY COMMENT '10;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '11;担当者名称;',
    `person_kn` VARCHAR(192) BINARY COMMENT '12;担当者かな名称;',
    `person_email` VARCHAR(128) BINARY COMMENT '13;担当者電子メールアドレス;',
    `open_ymd` DATETIME COMMENT '14;サービス開始日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `affiliater` COMMENT 'アフィリエイター;';

--   *** ------------------------------------
--  *** FILIATE_PROGRAM
--   *** ------------------------------------
-- 
CREATE TABLE `affiliate_program` (
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '1;アフィリエイトコード;YYYYNNNNNN',
    `reserve_system` VARCHAR(12) BINARY COMMENT '2;アフィリエイト予約システム;reserve:ベストリザーブ biztrip:livedoor出張',
    `affiliater_cd` VARCHAR(10) BINARY COMMENT '3;アフィリエイターコード;YYYYNNNNNN',
    `program_nm` VARCHAR(192) BINARY COMMENT '4;プログラム名称;',
    `limit_cookie` SMALLINT COMMENT '5;COOKIE有効期限;',
    `tag` VARCHAR(2000) BINARY COMMENT '6;HTMLタグ;',
    `redirect` VARCHAR(512) BINARY COMMENT '7;リダイレクト先;デフォルトのリダイレクト先',
    `overwrite_status` TINYINT COMMENT '8;COOKIE上書き可否;0:否 1:可',
    `accept_s_dtm` DATETIME COMMENT '9;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '10;終了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `password` VARCHAR(64) BINARY,
    `rate` SMALLINT,
    `report_layout_version` TINYINT DEFAULT 1,
    `affiliate_cd_type` TINYINT
);

ALTER TABLE
    `affiliate_program` COMMENT 'アフィリエイトプログラム;';

--   *** ------------------------------------
--  *** AFU_CANCEL_QUEUE
--   *** ------------------------------------
-- 
CREATE TABLE `akafu_cancel_queue` (
    `id` INT COMMENT '1;ID;',
    `send_status` TINYINT COMMENT '2;送信状態;0:未送信 1:送信済み',
    `entry_dtm` DATETIME COMMENT '3;データ作成日時;',
    `send_dtm` DATETIME COMMENT '4;送信完了日時;',
    `area_cd` VARCHAR(4) BINARY COMMENT '5;地区コード;',
    `institution_cd` VARCHAR(3) BINARY COMMENT '6;施設コード（赤風）;',
    `use_dt` DATETIME COMMENT '7;対象年月日;チェックイン日 YYYYMMDD',
    `stock_type_cd` VARCHAR(6) BINARY COMMENT '8;部屋タイプコード（在庫タイプ）;',
    `frame_no` VARCHAR(2) BINARY COMMENT '9;赤い風船枠番号;',
    `cancel_count` TINYINT COMMENT '10;戻し処理数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `akafu_cancel_queue` COMMENT '赤い風船在庫戻しリカバリ;予約キャンセル時失敗データをキューにためて再処理を行う。';

--   *** ------------------------------------
--  *** AFU_STOCK_FRAME_NO
--   *** ------------------------------------
-- 
CREATE TABLE `akafu_stock_frame_no` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '4;部屋タイプコード;',
    `frame_no` VARCHAR(2) BINARY COMMENT '5;赤い風船枠番号;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `akafu_stock_frame_no` COMMENT '赤い風船在庫枠番号;';

--   *** ------------------------------------
--  *** F_STOCK_FRAME_NO
--   *** ------------------------------------
-- 
CREATE TABLE `akf_stock_frame_no` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '4;部屋タイプコード;',
    `frame_no` VARCHAR(2) BINARY COMMENT '5;赤い風船枠番号;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `akf_stock_frame_no` COMMENT '赤い風船在庫枠番号;';

--   *** ------------------------------------
--  *** ERT_MAIL_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `alert_mail_hotel` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `alert_system_cd` VARCHAR(6) BINARY COMMENT '3;アラートシステムコード;vacant:空室アラート voice:宿泊体験 group:団体予約',
    `email` VARCHAR(128) BINARY COMMENT '4;電子メールアドレス;',
    `email_type` TINYINT COMMENT '5;電子メールタイプ;0:パソコン用レイアウト 1:携帯端末用レイアウト',
    `email_notify` TINYINT COMMENT '6;電子メール通知可否;0:否通知 1:通知',
    `note` VARCHAR(3000) BINARY COMMENT '7;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `alert_mail_hotel` COMMENT 'アラートメール施設;社内への通知メール情報を保持します';

--   *** ------------------------------------
--  *** ERT_MAIL_OPC
--   *** ------------------------------------
-- 
CREATE TABLE `alert_mail_opc` (
    `person_cd` VARCHAR(3) BINARY COMMENT '1;担当者コード;',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `alert_system_cd` VARCHAR(6) BINARY COMMENT '3;アラートシステムコード;vacant:空室アラート voice:宿泊体験 group:団体予約',
    `email` VARCHAR(128) BINARY COMMENT '4;電子メールアドレス;',
    `email_type` TINYINT COMMENT '5;電子メールタイプ;0:パソコン用レイアウト 1:携帯端末用レイアウト',
    `email_notify` TINYINT COMMENT '6;電子メール通知可否;0:否通知 1:通知',
    `note` VARCHAR(3000) BINARY COMMENT '7;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `alert_mail_opc` COMMENT 'アラートメールOPC;社内への通知メール情報を保持します。';

--   *** ------------------------------------
--  *** ERT_POST
--   *** ------------------------------------
-- 
CREATE TABLE `alert_post` (
    `person_cd` VARCHAR(3) BINARY COMMENT '1;担当者コード;',
    `person_nm` VARCHAR(96) BINARY COMMENT '2;担当者名称;',
    `note` VARCHAR(3000) BINARY COMMENT '3;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `alert_post` COMMENT 'アラート担当者;社内での担当者情報を保持します';

--   *** ------------------------------------
--  *** ERT_SYSTEM
--   *** ------------------------------------
-- 
CREATE TABLE `alert_system` (
    `alert_system_cd` VARCHAR(6) BINARY COMMENT '1;アラートシステムコード;vacant:空室アラート voice:宿泊体験 group:団体予約',
    `alert_system_nm` VARCHAR(54) BINARY COMMENT '2;アラートシステム名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `alert_system` COMMENT 'アラートシステム;アラート機能を必要とするシステムを定義';

--   *** ------------------------------------
--  *** ERT_VACANT
--   *** ------------------------------------
-- 
CREATE TABLE `alert_vacant` (
    `person_cd` VARCHAR(3) BINARY COMMENT '1;担当者コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `vacant_rooms` SMALLINT COMMENT '3;警告空室数;社内へメールを通知するタイミングにあたる空室数',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `alert_vacant` COMMENT '空室アラート;';

--   *** ------------------------------------
--  *** EA_YDP_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `area_ydp_match` (
    `ydp_area_cd` VARCHAR(10) BINARY COMMENT '1;宿ぷらざ地域コード;',
    `pref_id` TINYINT COMMENT '2;都道府県ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `area_ydp_match` COMMENT '地域リダイレクトマッチ;';

--   *** ------------------------------------
--  *** LLPAYED_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_credit` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `card_charge_sales_real` INT COMMENT '6;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real` INT COMMENT '7;カード決済金額キャンセル（実態）;税込',
    `card_rate_real` DECIMAL(5, 2) COMMENT '8;カード決済手数料率（実態）;1.05 固定',
    `card_fee_real` INT COMMENT '9;カード決済手数料（実態）;税別 切捨て',
    `card_charge_sales` INT COMMENT '10;カード決済金額宿泊（施設向け）;税込',
    `card_charge_cancel` INT COMMENT '11;カード決済金額キャンセル（施設向け）;税込',
    `card_rate` TINYINT COMMENT '12;カード決済手数料率（施設向け）;現在 2%で固定です。',
    `card_fee` INT COMMENT '13;カード決済手数料（施設向け）;税別 切捨て',
    `card_charge_sales_real_diff` INT COMMENT '14;カード決済金額宿泊（実態の差）;',
    `card_charge_cancel_real_diff` INT COMMENT '15;カード決済金額キャンセル（実態の差）;',
    `card_fee_real_diff` INT COMMENT '16;カード決済手数料（実態の差）;',
    `card_charge_sales_diff` INT COMMENT '17;カード決済金額宿泊（施設向けの差）;税込',
    `card_charge_cancel_diff` INT COMMENT '18;カード決済金額キャンセル（施設向けの差）;',
    `card_fee_diff` INT COMMENT '19;カード決済手数料（施設向けの差）;',
    `authori_count` SMALLINT COMMENT '20;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '21;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;',
    `card_fee_tax` INT,
    `card_fee_tax_diff` INT,
    `card_fee_real_tax` INT,
    `card_fee_real_tax_diff` INT
);

ALTER TABLE
    `billpayed_credit` COMMENT '宿泊別請求支払赤伝票（カード決済）;';

--   *** ------------------------------------
--  *** LLPAYED_CREDIT_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_credit_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `card_charge_sales_real` INT COMMENT '6;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real` INT COMMENT '7;カード決済金額キャンセル（実態）;税込',
    `card_rate_real` DECIMAL(5, 2) COMMENT '8;カード決済手数料率（実態）;1.05 固定',
    `card_fee_real` INT COMMENT '9;カード決済手数料（実態）;税別 切捨て',
    `card_fee_real_tax` INT COMMENT '10;カード決済手数料消費税（実態）;',
    `card_charge_sales` INT COMMENT '11;カード決済金額宿泊（ポイント分含む）;税込',
    `card_charge_cancel` INT COMMENT '12;カード決済金額キャンセル（ポイント分含む）;税込',
    `card_rate` TINYINT COMMENT '13;カード決済手数料率（施設負担分）;現在 2%で固定です。',
    `card_fee` INT COMMENT '14;カード決済手数料（施設負担分）;税別 切捨て',
    `card_fee_tax` INT COMMENT '15;カード決済手数料消費税（施設負担分）;',
    `card_charge_sales_real_diff` INT COMMENT '16;カード決済金額宿泊（実態の差）;',
    `card_charge_cancel_real_diff` INT COMMENT '17;カード決済金額キャンセル（実態の差）;',
    `card_fee_real_diff` INT COMMENT '18;カード決済手数料（実態の差）;',
    `card_fee_real_tax_diff` INT COMMENT '19;カード決済手数料消費税（実態の差）;2014年09月精算分から１部屋１日単位で消費税を算出、以前は月単位でヌル値',
    `card_charge_sales_diff` INT COMMENT '20;カード決済金額宿泊（ポイント分含むの差）;税込',
    `card_charge_cancel_diff` INT COMMENT '21;カード決済金額キャンセル（ポイント分含むの差）;',
    `card_fee_diff` INT COMMENT '22;カード決済手数料（施設負担分の差）;',
    `card_fee_tax_diff` INT COMMENT '23;カード決済手数料消費税（施設負担分の差）;2014年09月精算分から１部屋１日単位で消費税を算出、以前は月単位でヌル値',
    `authori_count` SMALLINT COMMENT '24;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '25;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `entry_cd` VARCHAR(64) BINARY COMMENT '26;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '27;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '28;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '29;更新日時;'
);

ALTER TABLE
    `billpayed_credit_9xg` COMMENT '宿泊別請求支払赤伝票（カード決済）_テスト用;宿泊別請求支払赤伝票（カード決済）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_FEE
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_fee` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `payment_way` TINYINT COMMENT '6;決済方法;1:事前カード決済 2:現地決済',
    `bill_type` TINYINT COMMENT '7;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '8;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '9;請求対象消費税;',
    `system_rate` SMALLINT COMMENT '10;システム利用料率;5%のときは5',
    `system_fee` INT COMMENT '11;システム利用料;税別 小数点以下切捨て',
    `bill_charge_diff` INT COMMENT '12;請求対象金額（差）;税サ込',
    `bill_charge_tax_diff` INT COMMENT '13;請求対象消費税（差）;',
    `system_fee_diff` INT COMMENT '14;システム利用料（差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;',
    `system_fee_tax` INT,
    `system_fee_tax_diff` INT,
    `affiliate_rate` DECIMAL(4, 2) COMMENT '21;アフィリエイト手数料率;提携先・アフィリエイト広告宣伝料率',
    `affiliate_fee` BIGINT COMMENT '22;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `affiliate_fee_tax` BIGINT COMMENT '23;アフィリエイト料消費税;提携先・アフィリエイト広告宣伝料消費税',
    `affiliate_fee_diff` BIGINT COMMENT '24;アフィリエイト料(差);',
    `affiliate_fee_tax_diff` BIGINT COMMENT '25;アフィリエイト料消費税(差);'
);

ALTER TABLE
    `billpayed_fee` COMMENT '宿泊別請求支払赤伝票（システム利用料）;';

--   *** ------------------------------------
--  *** LLPAYED_FEE_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_fee_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `payment_way` TINYINT COMMENT '6;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `bill_type` TINYINT COMMENT '7;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '8;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '9;請求対象消費税;',
    `system_rate` SMALLINT COMMENT '10;システム利用料率;5%のときは5',
    `system_fee` INT COMMENT '11;システム利用料;税別 小数点以下切捨て',
    `system_fee_tax` BIGINT COMMENT '12;システム利用料消費税;',
    `bill_charge_diff` INT COMMENT '13;請求対象金額（差）;税サ込',
    `bill_charge_tax_diff` INT COMMENT '14;請求対象消費税（差）;',
    `system_fee_diff` INT COMMENT '15;システム利用料（差）;',
    `system_fee_tax_diff` INT COMMENT '16;システム利用料消費税（差）;赤伝の場合は、１部屋１室単位で消費税を算出する。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '17;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '18;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '19;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '20;更新日時;',
    `affiliate_rate` DECIMAL(4, 2) COMMENT '21;アフィリエイト手数料率;提携先・アフィリエイト広告宣伝料率',
    `affiliate_fee` BIGINT COMMENT '22;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `affiliate_fee_tax` BIGINT COMMENT '23;アフィリエイト料消費税;提携先・アフィリエイト広告宣伝料消費税',
    `affiliate_fee_diff` BIGINT COMMENT '24;アフィリエイト料(差);',
    `affiliate_fee_tax_diff` BIGINT COMMENT '25;アフィリエイト料消費税(差);',
    `reserve_dtm` DATETIME COMMENT '26;予約受付日時;'
);

ALTER TABLE
    `billpayed_fee_9xg` COMMENT '宿泊別請求支払赤伝票（システム利用料）_テスト用;宿泊別請求支払赤伝票（システム利用料）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '3;施設名称;',
    `billpay_hotel_status` TINYINT COMMENT '4;請求支払状態;0：請求・支払未処理 1:請求・支払済み',
    `billpay_cd` VARCHAR(13) BINARY COMMENT '5;請求支払コード;請求 ： YYYYMM + 請求先・支払先ID(4桁) + 「A」 支払 ： YYYYMM + 請求先・支払先ID(4桁) + 「P」',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `billpayed_hotel` COMMENT '施設別請求支払赤伝票;';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '3;施設名称;',
    `billpay_hotel_status` TINYINT COMMENT '4;請求支払状態;0：請求・支払未処理 1:請求・支払確定済み 2:請求・支払繰り越し',
    `billpay_cd` VARCHAR(12) BINARY COMMENT '5;請求支払コード;請求（振込・引落）： YYYYMM + 精算先ID(4 or 5桁) + 「A」 支払 ： YYYYMM + 精算先ID(4 or 5桁) + 「P」 特別： YYYYMM + 精算先ID(4 or 5桁) + 「S」',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `billpayed_hotel_9xg` COMMENT '施設別請求支払赤伝票_テスト用;施設別請求支払赤伝票本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_credit` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `card_charge_sales_real_diff` BIGINT COMMENT '3;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real_diff` BIGINT COMMENT '4;カード決済金額キャンセル（実態）;税込',
    `card_charge_sales_diff` BIGINT COMMENT '5;カード決済金額宿泊（施設向け）;税込',
    `card_charge_cancel_diff` BIGINT COMMENT '6;カード決済金額キャンセル（施設向け）;税込',
    `card_fee_real_diff` BIGINT COMMENT '7;カード決済手数料（実態）;税別 切捨て',
    `card_fee_tax_real_diff` BIGINT COMMENT '8;カード決済手数料消費税（実態）;',
    `card_fee_diff` BIGINT COMMENT '9;カード決済手数料（施設向け）;税別 切捨て',
    `card_fee_tax_diff` BIGINT COMMENT '10;カード決済手数料消費税（施設向け）;',
    `authori_count` SMALLINT COMMENT '11;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` BIGINT COMMENT '12;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `authori_fee_tax` BIGINT COMMENT '13;カードオーソリ手数料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;',
    `card_work_fee_diff` INT,
    `card_work_fee_tax_diff` SMALLINT
);

ALTER TABLE
    `billpayed_hotel_credit` COMMENT '施設別請求支払赤伝票（カード決済）;';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_CREDIT_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_credit_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `card_charge_sales_real_diff` INT COMMENT '3;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real_diff` INT COMMENT '4;カード決済金額キャンセル（実態）;税込',
    `card_charge_sales_diff` BIGINT COMMENT '5;カード決済金額宿泊（ポイント分含む）;税込',
    `card_charge_cancel_diff` INT COMMENT '6;カード決済金額キャンセル（ポイント分含む）;税込',
    `card_fee_real_diff` INT COMMENT '7;カード決済手数料（実態）;税別 切捨て',
    `card_fee_tax_real_diff` BIGINT COMMENT '8;カード決済手数料消費税（実態）;',
    `card_fee_diff` INT COMMENT '9;カード決済手数料（施設負担分）;税別 切捨て',
    `card_fee_tax_diff` BIGINT COMMENT '10;カード決済手数料消費税（施設負担分）;',
    `card_work_fee_diff` INT COMMENT '11;カード事務手数料;税別',
    `card_work_fee_tax_diff` SMALLINT COMMENT '12;カード事務手数料消費税;',
    `authori_count` SMALLINT COMMENT '13;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '14;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `authori_fee_tax` BIGINT COMMENT '15;カードオーソリ手数料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `billpayed_hotel_credit_9xg` COMMENT '施設別請求支払赤伝票（カード決済）_テスト用;施設別請求支払赤伝票（カード決済）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_FEE
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_fee` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `bill_charge_diff` BIGINT COMMENT '3;宿泊料金;税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '4;料金消費税;',
    `system_rate` SMALLINT COMMENT '5;システム利用料率;5%のときは5',
    `system_fee_diff` BIGINT COMMENT '6;システム利用料;税別 小数点以下切捨て',
    `system_fee_tax_diff` BIGINT COMMENT '7;システム利用料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `affiliate_fee_diff` BIGINT COMMENT '11;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `affiliate_fee_tax_diff` BIGINT COMMENT '12;アフィリエイト料(差);'
);

ALTER TABLE
    `billpayed_hotel_fee` COMMENT '施設別請求支払赤伝票（システム利用料）;';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_FEE_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_fee_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `bill_charge_diff` BIGINT COMMENT '3;請求対象金額（差）;税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '4;請求対象消費税（差）;',
    `system_rate` SMALLINT COMMENT '5;システム利用料率;税別 小数点以下切捨て',
    `system_fee_diff` BIGINT COMMENT '6;システム利用料（差）;',
    `system_fee_tax_diff` BIGINT COMMENT '7;システム利用料消費税（差）;赤伝の場合は、１部屋１室単位で消費税を算出する。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `affiliate_fee` BIGINT COMMENT '12;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `affiliate_fee_diff` BIGINT COMMENT '13;アフィリエイト料(差);',
    `affiliate_fee_tax_diff` BIGINT COMMENT '14;アフィリエイト料消費税(差);'
);

ALTER TABLE
    `billpayed_hotel_fee_9xg` COMMENT '施設別請求支払赤伝票（システム利用料）_テスト用;施設別請求支払赤伝票（システム利用料）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_grants` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_grants_diff` BIGINT COMMENT '3;補助金（差）;',
    `use_grants_real_diff` BIGINT COMMENT '4;補助金額(実態の差);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `use_coupon_diff` BIGINT,
    `use_coupon_real_diff` BIGINT
);

ALTER TABLE
    `billpayed_hotel_grants` COMMENT '施設別請求支払赤伝票(補助金);';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_grants_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_grants_diff` BIGINT COMMENT '3;補助金（差）;',
    `use_grants_real_diff` BIGINT COMMENT '4;補助金額(実態の差);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `use_coupon_diff` BIGINT,
    `use_coupon_real_diff` BIGINT
);

ALTER TABLE
    `billpayed_hotel_grants_9xg` COMMENT '施設別請求支払赤伝票(補助金)_テスト;';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_rsv` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_br_point_charge_real_diff` BIGINT COMMENT '3;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge_diff` BIGINT COMMENT '4;消費ＢＲポイント割引料金（施設向け）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `get_br_point_charge_diff` BIGINT,
    `get_br_point_charge_hotel_diff` BIGINT
);

ALTER TABLE
    `billpayed_hotel_rsv` COMMENT '施設別請求支払赤伝票（リザーブ）;';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_RSV_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_rsv_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_br_point_charge_diff` BIGINT COMMENT '3;獲得ＢＲポイント;1ポイント１円 税込',
    `get_br_point_charge_hotel_diff` BIGINT COMMENT '4;獲得ＢＲポイント（施設負担分）;',
    `use_br_point_charge_real_diff` BIGINT COMMENT '5;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge_diff` BIGINT COMMENT '6;消費ＢＲポイント割引料金（カード分含まない）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `billpayed_hotel_rsv_9xg` COMMENT '施設別請求支払赤伝票（リザーブ）_テスト用;施設別請求支払赤伝票（リザーブ）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_yahoo` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_yahoo_point_diff` BIGINT COMMENT '3;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point_real_diff` BIGINT COMMENT '4;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point_diff` BIGINT COMMENT '5;消費ヤフーポイント（施設向け）;１ポイント１円 税込',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `get_yahoo_point_hotel_diff` BIGINT
);

ALTER TABLE
    `billpayed_hotel_yahoo` COMMENT '施設別請求支払赤伝票（ヤフー）;';

--   *** ------------------------------------
--  *** LLPAYED_HOTEL_YAHOO_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hotel_yahoo_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_yahoo_point_diff` BIGINT COMMENT '3;獲得ヤフーポイント;1ポイント１円 税込',
    `get_yahoo_point_hotel_diff` BIGINT COMMENT '4;獲得ヤフーポイント（施設負担分）;',
    `use_yahoo_point_real_diff` BIGINT COMMENT '5;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point_diff` BIGINT COMMENT '6;消費ヤフーポイント（カード分含まない）;現地決済の時は実態のポイント、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `billpayed_hotel_yahoo_9xg` COMMENT '施設別請求支払赤伝票（ヤフー）_テスト用;施設別請求支払赤伝票（ヤフー）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_HR_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hr_grants` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `welfare_grants_id` BIGINT COMMENT '4;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '5;福利厚生補助金履歴ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `billpay_ym` DATETIME COMMENT '10;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `bill_type` TINYINT COMMENT '11;請求対象タイプ;0:宿泊 1:キャンセル',
    `use_grants` BIGINT DEFAULT 0 COMMENT '12;補助金額;税込み',
    `use_grants_real` BIGINT COMMENT '13;補助金額(実態);',
    `use_grants_diff` BIGINT COMMENT '14;補助金（差）;',
    `use_grants_real_diff` BIGINT COMMENT '15;補助金額(実態の差);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `billpayed_hr_grants` COMMENT '宿泊別請求支払赤伝票(補助金);補助金適用先への精算用';

--   *** ------------------------------------
--  *** LLPAYED_HR_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_hr_grants_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `welfare_grants_id` BIGINT COMMENT '4;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '5;福利厚生補助金履歴ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `billpay_ym` DATETIME COMMENT '10;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `bill_type` TINYINT COMMENT '11;請求対象タイプ;0:宿泊 1:キャンセル',
    `use_grants` BIGINT DEFAULT 0 COMMENT '12;補助金額;税込み',
    `use_grants_real` BIGINT COMMENT '13;補助金額(実態);',
    `use_grants_diff` BIGINT COMMENT '14;補助金（差）;',
    `use_grants_real_diff` BIGINT COMMENT '15;補助金額(実態の差);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `billpayed_hr_grants_9xg` COMMENT '宿泊別請求支払赤伝票(補助金)_テスト;';

--   *** ------------------------------------
--  *** LLPAYED_PR_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_pr_grants` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `operation_ymd` DATETIME COMMENT '4;操作日付;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `welfare_grants_id` BIGINT COMMENT '10;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '11;福利厚生補助金情報履歴ID;',
    `payment_way` TINYINT COMMENT '12;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `bill_type` TINYINT COMMENT '13;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '14;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '15;請求対象消費税;',
    `order_cd` VARCHAR(15) BINARY COMMENT '16;予約申込コード;B99999999-NNN （  B+８桁数値-年（桁）月の１６進 ）',
    `use_grants` BIGINT DEFAULT 0 COMMENT '17;補助金額;税込み',
    `bill_type_diff` TINYINT COMMENT '18;請求対象タイプ（差）;0:変更なし1:宿泊からキャンセルへ変更  -1:キャンセルから宿泊へ変更 （後払いの宿泊料・取消料の振り分けに使用する。）',
    `bill_charge_diff` INT COMMENT '19;請求対象金額（差）;税サ込',
    `use_grants_diff` BIGINT COMMENT '20;補助金（差）;',
    `bill_charge_tax_diff` INT COMMENT '21;請求対象消費税（差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;'
);

ALTER TABLE
    `billpayed_pr_grants` COMMENT '宿泊別赤伝(補助金);';

--   *** ------------------------------------
--  *** LLPAYED_PTN
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `fee_type` TINYINT COMMENT '3;手数料タイプ;1:販売 2:在庫',
    `site_nm` VARCHAR(196) BINARY COMMENT '4;提携サイト名称;',
    `billpay_ptn_cd` VARCHAR(29) BINARY COMMENT '5;請求支払コード（提携先）;請求（振込・引落）： 「P」 + YYYYMM + 精算先ID(4 or 5桁) + 「A」+ 「D」 支払 ： 「P」 + YYYYMM + 精算先ID(4 or 5桁) + 「P」+ 「D」',
    `billpay_ptn_status` TINYINT COMMENT '6;請求支払状態（提携先）;0：請求・支払未処理 1:請求・支払済み',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `billpayed_ptn` COMMENT '提携先別赤伝;';

--   *** ------------------------------------
--  *** LLPAYED_PTN_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn_grants` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `bill_sales_count` INT COMMENT '3;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '4;精算対象キャンセル室数;',
    `bill_charge_diff` BIGINT COMMENT '5;精算対象宿泊料金(差);税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '6;料金消費税(差);',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `use_grants_diff` BIGINT COMMENT '10;補助金額差(キャンセル充当分含まない);',
    `use_grants_apply_cancel_diff` BIGINT COMMENT '11;補助金額差(キャンセル充当分);',
    `use_grants_total_diff` BIGINT COMMENT '12;補助金額合計差;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;'
);

ALTER TABLE
    `billpayed_ptn_grants` COMMENT '提携先別赤伝票(補助金);';

--   *** ------------------------------------
--  *** LLPAYED_PTN_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn_sales` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `sales_rate` DECIMAL(4, 2) COMMENT '3;販売手数料率;',
    `bill_sales_count` INT COMMENT '4;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '5;精算対象キャンセル室数;',
    `bill_charge_diff` BIGINT COMMENT '6;精算対象宿泊料金（差）;税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '7;料金消費税（差）;',
    `sales_fee_diff` BIGINT COMMENT '8;販売手数料（差）;',
    `sales_fee_tax_diff` BIGINT COMMENT '9;販売手数料消費税（差）;消費税単位が サイト単位の提携先は、サイト単位で集計下手数料から消費税を算出する。',
    `later_sales_count` INT COMMENT '10;宿泊室数（後払分）;',
    `later_cancel_count` INT COMMENT '11;キャンセル室数（後払分）;',
    `later_sales_charge_diff` BIGINT COMMENT '12;宿泊料金（後払分）（差）;後払分の宿泊料金（税さ込）',
    `later_cancel_charge_diff` BIGINT COMMENT '13;キャンセル料金（後払分）（差）;後払分のキャンセル料金',
    `partner_cd` VARCHAR(10) BINARY COMMENT '14;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '15;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `billpayed_ptn_sales` COMMENT '提携先別赤伝票（販売手数料）;';

--   *** ------------------------------------
--  *** LLPAYED_PTN_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn_stock` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `stock_rate` DECIMAL(4, 2) COMMENT '3;在庫手数料率;',
    `bill_sales_count` INT COMMENT '4;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '5;精算対象キャンセル室数;',
    `bill_charge_diff` BIGINT COMMENT '6;精算対象宿泊料金（差）;税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '7;料金消費税（差）;',
    `stock_fee_diff` INT COMMENT '8;在庫手数料（差）;',
    `stock_fee_tax_diff` BIGINT COMMENT '9;在庫手数料消費税（差）;消費税単位が サイト単位の提携先は、サイト単位で集計下手数料から消費税を算出する。',
    `partner_cd` VARCHAR(10) BINARY COMMENT '10;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '11;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `billpayed_ptn_stock` COMMENT '提携先別赤伝票（在庫手数料）;';

--   *** ------------------------------------
--  *** LLPAYED_PTN_TYPE_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn_type_grants` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `bill_sales_count` INT COMMENT '3;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '4;精算対象キャンセル室数;',
    `bill_charge_diff` BIGINT COMMENT '5;精算対象宿泊料金(差);税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '6;料金消費税(差);',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `welfare_grants_id` BIGINT COMMENT '10;福利厚生補助金ID;',
    `use_grants_diff` BIGINT COMMENT '11;補助金額差(キャンセル充当分含まない);',
    `use_grants_apply_cancel_diff` BIGINT COMMENT '12;補助金額差(キャンセル充当分);',
    `use_grants_total_diff` BIGINT COMMENT '13;補助金額合計差;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `billpayed_ptn_type_grants` COMMENT '	提携先補助金種類別赤伝票（補助金）;';

--   *** ------------------------------------
--  *** LLPAYED_PTN_TYPE_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn_type_sales` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `stock_type` TINYINT COMMENT '3;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `sales_rate` DECIMAL(4, 2) COMMENT '4;販売手数料率;',
    `bill_sales_count` INT COMMENT '5;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '6;精算対象キャンセル室数;',
    `bill_charge_diff` BIGINT COMMENT '7;精算対象宿泊料金（差）;税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '8;料金消費税（差）;',
    `sales_fee_diff` INT COMMENT '9;販売手数料（差）;',
    `sales_fee_tax_diff` BIGINT COMMENT '10;販売手数料消費税（差）;消費税単位が サイト・手数料率単位の提携先のみ値設定、 サイト単位の場合はヌル値を設定',
    `later_sales_count` INT COMMENT '11;宿泊室数（後払分）;',
    `later_cancel_count` INT COMMENT '12;キャンセル室数（後払分）;',
    `later_sales_charge_diff` BIGINT COMMENT '13;宿泊料金（後払分）（差）;後払分の宿泊料金（税さ込）',
    `later_cancel_charge_diff` BIGINT COMMENT '14;キャンセル料金（後払分）（差）;後払分のキャンセル料金',
    `partner_cd` VARCHAR(10) BINARY COMMENT '15;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '16;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '17;アフィリエイトコード枝番;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '18;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '19;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '20;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '21;更新日時;'
);

ALTER TABLE
    `billpayed_ptn_type_sales` COMMENT '提携先在庫タイプ赤伝票（販売手数料）;';

--   *** ------------------------------------
--  *** LLPAYED_PTN_TYPE_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_ptn_type_stock` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `stock_type` TINYINT COMMENT '3;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `stock_rate` DECIMAL(4, 2) COMMENT '4;在庫手数料率;',
    `bill_sales_count` INT COMMENT '5;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '6;精算対象キャンセル室数;',
    `bill_charge_diff` BIGINT COMMENT '7;精算対象宿泊料金（差）;税サ込',
    `bill_charge_tax_diff` BIGINT COMMENT '8;料金消費税（差）;',
    `stock_fee_diff` INT COMMENT '9;在庫手数料（差）;',
    `stock_fee_tax_diff` BIGINT COMMENT '10;在庫手数料消費税（差）;消費税単位が サイト・手数料率単位の提携先のみ値設定、 サイト単位の場合はヌル値を設定',
    `partner_cd` VARCHAR(10) BINARY COMMENT '11;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '12;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '13;アフィリエイトコード枝番;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `billpayed_ptn_type_stock` COMMENT '提携先在庫タイプ別赤伝票（在庫手数料）;';

--   *** ------------------------------------
--  *** LLPAYED_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_rsv` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `use_br_point_charge_real` INT COMMENT '6;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge` INT COMMENT '7;消費ＢＲポイント割引料金（施設向け）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `use_br_point_charge_real_diff` INT COMMENT '8;消費ＢＲポイント割引料金（実態の差）;',
    `use_br_point_charge_diff` INT COMMENT '9;消費ＢＲポイント割引料金（施設向けの差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `get_br_point_rate` SMALLINT,
    `get_br_point_rate_our` SMALLINT,
    `get_br_point_charge` INT,
    `get_br_point_charge_hotel` INT,
    `get_br_point_charge_diff` INT,
    `get_br_point_charge_hotel_diff` INT
);

ALTER TABLE
    `billpayed_rsv` COMMENT '宿泊別請求支払赤伝票（リザーブ）;';

--   *** ------------------------------------
--  *** LLPAYED_RSV_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_rsv_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `get_br_point_rate` SMALLINT COMMENT '6;獲得ＢＲポイント率;',
    `get_br_point_rate_our` SMALLINT COMMENT '7;獲得ＢＲポイント当社負担率;',
    `get_br_point_charge` INT COMMENT '8;獲得ＢＲポイント;1ポイント１円 税込',
    `get_br_point_charge_hotel` INT COMMENT '9;獲得ＢＲポイント（施設負担分）;',
    `use_br_point_charge_real` INT COMMENT '10;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge` INT COMMENT '11;消費ＢＲポイント割引料金（ポイント分含む）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `get_br_point_charge_diff` INT COMMENT '12;獲得ＢＲポイント（差）;',
    `get_br_point_charge_hotel_diff` INT COMMENT '13;獲得ＢＲポイント（施設負担分の差）;',
    `use_br_point_charge_real_diff` INT COMMENT '14;消費ＢＲポイント割引料金（実態の差）;',
    `use_br_point_charge_diff` INT COMMENT '15;消費ＢＲポイント割引料金（ポイント分含むの差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `billpayed_rsv_9xg` COMMENT '宿泊別請求支払赤伝票（リザーブ）_テスト用;宿泊別請求支払赤伝票（リザーブ）本番テスト用';

--   *** ------------------------------------
--  *** LLPAYED_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_sales` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `operation_ymd` DATETIME COMMENT '4;操作日付;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `payment_way` TINYINT COMMENT '10;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `later_payment` TINYINT COMMENT '11;後払い状態;0:通常 1:後払い',
    `stock_type` TINYINT COMMENT '12;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `bill_type` TINYINT COMMENT '13;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '14;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '15;請求対象消費税;',
    `sales_rate` DECIMAL(4, 2) COMMENT '16;販売手数料率;',
    `sales_fee` INT COMMENT '17;販売手数料;',
    `bill_charge_diff` INT COMMENT '18;請求対象金額（差）;税サ込',
    `bill_charge_tax_diff` INT COMMENT '19;請求対象消費税（差）;',
    `sales_fee_diff` INT COMMENT '20;販売手数料（差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '21;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '22;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '23;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '24;更新日時;',
    `bill_type_diff` TINYINT,
    `sales_fee_tax` INT,
    `sales_fee_tax_diff` INT
);

ALTER TABLE
    `billpayed_sales` COMMENT '宿泊別赤伝（販売手数料）;';

--   *** ------------------------------------
--  *** LLPAYED_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_stock` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `operation_ymd` DATETIME COMMENT '4;操作日付;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `payment_way` TINYINT COMMENT '10;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `later_payment` TINYINT COMMENT '11;後払い状態;0:通常 1:後払い',
    `stock_type` TINYINT COMMENT '12;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `bill_type` TINYINT COMMENT '13;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '14;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '15;請求対象消費税;',
    `stock_rate` DECIMAL(4, 2) COMMENT '16;在庫手数料率;',
    `stock_fee` INT COMMENT '17;在庫手数料;',
    `bill_charge_diff` INT COMMENT '18;請求対象金額（差）;税サ込',
    `bill_charge_tax_diff` INT COMMENT '19;請求対象消費税（差）;',
    `stock_fee_diff` INT COMMENT '20;在庫手数料（差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '21;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '22;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '23;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '24;更新日時;',
    `bill_type_diff` TINYINT,
    `stock_fee_tax` INT,
    `stock_fee_tax_diff` INT
);

ALTER TABLE
    `billpayed_stock` COMMENT '宿泊別赤伝（在庫手数料）;';

--   *** ------------------------------------
--  *** LLPAYED_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_yahoo` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `get_yahoo_point` INT COMMENT '6;獲得ヤフーポイント;',
    `use_yahoo_point_real` INT COMMENT '7;消費ヤフーポイント（実態）;',
    `use_yahoo_point` INT COMMENT '8;消費ヤフーポイント（施設向け）;現地決済の時は実態のポイント、 カード決済の場合 ０ ポイント',
    `get_yahoo_point_diff` INT COMMENT '9;獲得ヤフーポイント（差）;',
    `use_yahoo_point_real_diff` INT COMMENT '10;消費ヤフーポイント（実態の差）;',
    `use_yahoo_point_diff` INT COMMENT '11;消費ヤフーポイント（施設向けの差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;',
    `get_yahoo_point_hotel` INT,
    `get_yahoo_point_hotel_diff` INT,
    `get_yahoo_point_rate` DECIMAL(5, 2),
    `get_yahoo_point_rate_our` DECIMAL(5, 2)
);

ALTER TABLE
    `billpayed_yahoo` COMMENT '宿泊別請求支払赤伝票（ヤフー）;';

--   *** ------------------------------------
--  *** LLPAYED_YAHOO_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpayed_yahoo_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `operation_ymd` DATETIME COMMENT '3;操作日付;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `get_yahoo_point_rate` DECIMAL(5, 2) COMMENT '6;獲得ヤフーポイント率;',
    `get_yahoo_point_rate_our` DECIMAL(5, 2) COMMENT '7;獲得ヤフーポイント当社負担率;',
    `get_yahoo_point` INT COMMENT '8;獲得ヤフーポイント;1ポイント１円 税込',
    `get_yahoo_point_hotel` INT COMMENT '9;獲得ヤフーポイント（施設負担分）;',
    `use_yahoo_point_real` INT COMMENT '10;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point` INT COMMENT '11;消費ヤフーポイント（ポイント分含む）;現地決済の時は実態のポイント、 カード決済の場合 ０ ポイント',
    `get_yahoo_point_diff` INT COMMENT '12;獲得ヤフーポイント（差）;',
    `get_yahoo_point_hotel_diff` INT COMMENT '13;獲得ヤフーポイント（施設負担分の差）;',
    `use_yahoo_point_real_diff` INT COMMENT '14;消費ヤフーポイント（実態の差）;',
    `use_yahoo_point_diff` INT COMMENT '15;消費ヤフーポイント（ポイント分含むの差）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `billpayed_yahoo_9xg` COMMENT '	宿泊別請求支払赤伝票（ヤフー）_テスト用;	宿泊別請求支払赤伝票（ヤフー）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_book` (
    `billpay_cd` VARCHAR(13) BINARY COMMENT '1;請求支払コード;請求 ： YYYYMM + 請求先・支払先ID(4桁) + 「A」 支払 ： YYYYMM + 請求先・支払先ID(4桁) + 「P」',
    `billpay_branch_no` TINYINT COMMENT '2;請求支払枝番;1以上の場合、請求・支払コードに「-」で結合して表示する （ YYYYMM9999A-9 ）',
    `billpay_type` TINYINT COMMENT '3;請求支払タイプ;0:請求 1:支払',
    `billpay_charge_total` BIGINT COMMENT '4;請求支払総額;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月',
    `customer_id` BIGINT COMMENT '6;請求支払先ID;',
    `bill_ymd` DATETIME COMMENT '7;請求振込期日年月日;',
    `payment_ymd` DATETIME COMMENT '8;支払予定年月日;',
    `book_path` VARCHAR(128) BINARY COMMENT '9;原稿ファイルパス;',
    `billpay_status` TINYINT COMMENT '10;作業状態;0;原稿作成待ち 1:原稿作成済み 10:印刷しない 20:印刷待ち 21:印刷済み',
    `book_create_dtm` DATETIME COMMENT '11;原稿作成日時;',
    `book_print_dtm` DATETIME COMMENT '12;印刷日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `factoring_fee` INT,
    `billpay_condition` VARCHAR(16) BINARY,
    `send_request_dtm` DATETIME,
    `send_accept_dtm` DATETIME,
    `send_result_dtm` DATETIME,
    `factoring_ymd` DATETIME,
    `af_fee_disp_flg` TINYINT COMMENT '23;AF料精算書表示フラグ;1:AF手数料欄を精算書に表示する 0:AF手数料欄を精算書に表示しない'
);

ALTER TABLE
    `billpay_book` COMMENT '請求支払データ;';

--   *** ------------------------------------
--  *** LLPAY_BOOK_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_book_9xg` (
    `billpay_cd` VARCHAR(12) BINARY COMMENT '1;請求支払コード;請求（振込・引落）： YYYYMM + 精算先ID(4 or 5桁) + 「A」 支払 ： YYYYMM + 精算先ID(4 or 5桁) + 「P」 特別： YYYYMM + 精算先ID(4 or 5桁) + 「S」',
    `billpay_branch_no` TINYINT COMMENT '2;請求支払枝番;1以上の場合、請求・支払コードに「-」で結合して表示する （ YYYYMM9999A-9 ）',
    `billpay_type` TINYINT COMMENT '3;請求支払タイプ;0:請求(振込) 1:支払 2:請求（引落）',
    `factoring_fee` INT COMMENT '4;口座引落手数料;',
    `billpay_charge_total` BIGINT COMMENT '5;請求支払総額;',
    `billpay_ym` DATETIME COMMENT '6;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `customer_id` BIGINT COMMENT '7;精算先ID;連番、シーケンスは使用しない',
    `bill_ymd` DATETIME COMMENT '8;請求振込期日年月日;',
    `factoring_ymd` DATETIME COMMENT '9;引落予定年月日;',
    `payment_ymd` DATETIME COMMENT '10;支払予定年月日;',
    `book_path` VARCHAR(128) BINARY COMMENT '11;原稿ファイルパス;',
    `billpay_status` TINYINT COMMENT '12;作業状態;0:原稿作成待ち 1:原稿作成済み 10:印刷しない 20:印刷待ち 21:印刷済み',
    `billpay_condition` VARCHAR(16) BINARY COMMENT '13;作業状況（FAX）;ヌル値: 未処理 request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '14;原稿作成日時;',
    `book_print_dtm` DATETIME COMMENT '15;印刷日時;',
    `send_request_dtm` DATETIME COMMENT '16;送信依頼日時分秒;',
    `send_accept_dtm` DATETIME COMMENT '17;送信受付日時分秒;',
    `send_result_dtm` DATETIME COMMENT '18;送信処理完了日時分秒;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '19;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '20;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '21;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '22;更新日時;',
    `af_fee_disp_flg` TINYINT COMMENT '23;AF料精算書表示フラグ;1:AF手数料欄を精算書に表示する 0:AF手数料欄を精算書に表示しない'
);

ALTER TABLE
    `billpay_book_9xg` COMMENT '請求支払データ（台帳）_テスト用;請求支払データ（台帳）_本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_credit` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `card_charge_sales_real` INT COMMENT '5;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real` INT COMMENT '6;カード決済金額キャンセル（実態）;税込',
    `card_charge_sales` INT COMMENT '7;カード決済金額宿泊（施設向け）;税込',
    `card_charge_cancel` INT COMMENT '8;カード決済金額キャンセル（施設向け）;税込',
    `card_rate_real` DECIMAL(5, 2) COMMENT '9;カード決済手数料率（実態）;1.05 固定',
    `card_fee_real` INT COMMENT '10;カード決済手数料（実態）;税別 切捨て',
    `card_rate` TINYINT COMMENT '11;カード決済手数料率（施設向け）;現在 2%で固定です。',
    `card_fee` INT COMMENT '12;カード決済手数料（施設向け）;税別 切捨て',
    `authori_count` SMALLINT COMMENT '13;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '14;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `billpay_credit` COMMENT '宿泊別請求支払データ（カード決済）;';

--   *** ------------------------------------
--  *** LLPAY_CREDIT_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_credit_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `card_charge_sales_real` INT COMMENT '5;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real` INT COMMENT '6;カード決済金額キャンセル（実態）;税込',
    `card_charge_sales` INT COMMENT '7;カード決済金額宿泊（ポイント分含む）;税込',
    `card_charge_cancel` INT COMMENT '8;	カード決済金額キャンセル（ポイント分含む）;',
    `card_rate_real` DECIMAL(5, 2) COMMENT '9;カード決済手数料率（実態）;1.05 固定',
    `card_fee_real` INT COMMENT '10;カード決済手数料（実態）;税別 切捨て',
    `card_rate` TINYINT COMMENT '11;カード決済手数料率（施設負担分）;現在 2%で固定です。',
    `card_fee` INT COMMENT '12;カード決済手数料（施設負担分）;税別 切捨て',
    `authori_count` SMALLINT COMMENT '13;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '14;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `billpay_credit_9xg` COMMENT '宿泊別請求支払データ（カード決済）_テスト用;宿泊別請求支払データ（カード決済）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_CUSTOMER
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_customer` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `customer_id` BIGINT COMMENT '2;請求支払先ID;連番、シーケンスは使用しない',
    `customer_nm` VARCHAR(150) BINARY COMMENT '3;請求支払先名称;',
    `section_nm` VARCHAR(96) BINARY COMMENT '4;部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '7;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '8;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '9;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '10;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '11;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `bill_bank_nm` VARCHAR(150) BINARY COMMENT '12;請求銀行; 支店名称も含む',
    `bill_bank_account_no` VARCHAR(20) BINARY COMMENT '13;請求口座番号;',
    `payment_bank_cd` VARCHAR(4) BINARY COMMENT '14;支払銀行コード;数字4文字',
    `payment_bank_branch_cd` VARCHAR(3) BINARY COMMENT '15;支払支店コード;数字3文字',
    `payment_bank_account_type` TINYINT COMMENT '16;支払口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `payment_bank_account_no` VARCHAR(7) BINARY COMMENT '17;支払口座番号;数字7文字',
    `payment_bank_account_kn` VARCHAR(90) BINARY COMMENT '18;支払口座名義（カナ）;半角カタカナ15文字',
    `bill_required` TINYINT COMMENT '19;当月請求必須月;0 : 請求しない 1 : 請求する',
    `payment_required` TINYINT COMMENT '20;当月支払必須月;0 : 支払しない 1 : 支払する',
    `bill_charge_min` INT COMMENT '21;請求最低金額;デフォルト: 10000',
    `payment_charge_min` INT COMMENT '22;支払最低金額;デフォルト: 1000',
    `entry_cd` VARCHAR(64) BINARY COMMENT '23;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '24;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '25;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '26;更新日時;',
    `bill_way` TINYINT,
    `factoring_bank_cd` VARCHAR(4) BINARY,
    `factoring_bank_branch_cd` VARCHAR(3) BINARY,
    `factoring_bank_account_type` TINYINT,
    `factoring_bank_account_no` VARCHAR(7) BINARY,
    `factoring_bank_account_kn` VARCHAR(90) BINARY,
    `factoring_cd` VARCHAR(12) BINARY,
    `bill_send` TINYINT,
    `payment_send` TINYINT,
    `factoring_send` TINYINT,
    `fax_recipient_cd` TINYINT,
    `optional_nm` VARCHAR(150) BINARY,
    `optional_section_nm` VARCHAR(76) BINARY,
    `optional_person_nm` VARCHAR(96) BINARY,
    `optional_fax` VARCHAR(15) BINARY,
    `af_fee_disp_flg` TINYINT COMMENT '42;AF料精算書表示フラグ;1:AF手数料欄を精算書に表示する 0:AF手数料欄を精算書に表示しない'
);

ALTER TABLE
    `billpay_customer` COMMENT '請求支払先（請求支払）;';

--   *** ------------------------------------
--  *** LLPAY_CUSTOMER_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_customer_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `customer_id` BIGINT COMMENT '2;精算先ID;連番、シーケンスは使用しない',
    `customer_nm` VARCHAR(150) BINARY COMMENT '3;精算先名称;',
    `section_nm` VARCHAR(76) BINARY COMMENT '4;部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '7;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '8;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '9;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '10;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '11;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `bill_way` TINYINT COMMENT '12;請求方法;0：振込 1:引落',
    `bill_bank_nm` VARCHAR(150) BINARY COMMENT '13;請求銀行; 支店名称も含む',
    `bill_bank_account_no` VARCHAR(20) BINARY COMMENT '14;請求口座番号;',
    `factoring_bank_cd` VARCHAR(4) BINARY COMMENT '15;引落銀行コード;',
    `factoring_bank_branch_cd` VARCHAR(3) BINARY COMMENT '16;引落支店コード;',
    `factoring_bank_account_type` TINYINT COMMENT '17;引落口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `factoring_bank_account_no` VARCHAR(7) BINARY COMMENT '18;引落口座番号;数字7文字',
    `factoring_bank_account_kn` VARCHAR(90) BINARY COMMENT '19;引落口座名義（カナ）;半角カタカナ30文字',
    `factoring_cd` VARCHAR(12) BINARY COMMENT '20;引落顧客コード;',
    `payment_bank_cd` VARCHAR(4) BINARY COMMENT '21;支払銀行コード;数字4文字',
    `payment_bank_branch_cd` VARCHAR(3) BINARY COMMENT '22;支払支店コード;数字3文字',
    `payment_bank_account_type` TINYINT COMMENT '23;支払口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `payment_bank_account_no` VARCHAR(7) BINARY COMMENT '24;支払口座番号;数字7文字',
    `payment_bank_account_kn` VARCHAR(90) BINARY COMMENT '25;支払口座名義（カナ）;半角カタカナ30文字',
    `bill_required` TINYINT COMMENT '26;当月請求必須月;0 : 請求しない 1 : 請求する',
    `payment_required` TINYINT COMMENT '27;当月支払必須月;0 : 支払しない 1 : 支払する',
    `bill_charge_min` INT COMMENT '28;請求最低金額;デフォルト: 10000',
    `payment_charge_min` INT COMMENT '29;支払最低金額;デフォルト: 1000',
    `bill_send` TINYINT COMMENT '30;発送方法（請求書）;0:発送なし（ネット確認のみ） 1:印刷（郵送） 2:FAX 3:両方（印刷・FAX）',
    `payment_send` TINYINT COMMENT '31;発送方法（支払通知書）;0:発送なし（ネット確認のみ） 1:印刷（郵送） 2:FAX 3:両方（印刷・FAX）',
    `factoring_send` TINYINT COMMENT '32;発送方法（引落通知書）;0:発送なし（ネット確認のみ） 1:印刷（郵送） 2:FAX 3:両方（印刷・FAX）',
    `fax_recipient_cd` TINYINT COMMENT '33;FAX通知先;1:精算先 2:任意宛先',
    `optional_nm` VARCHAR(150) BINARY COMMENT '34;任意宛先名称（施設・会社名）;',
    `optional_section_nm` VARCHAR(76) BINARY COMMENT '35;任意役職（部署名）;',
    `optional_person_nm` VARCHAR(96) BINARY COMMENT '36;任意担当者名称;',
    `optional_fax` VARCHAR(15) BINARY COMMENT '37;任意ファックス番号;ハイフン含む',
    `entry_cd` VARCHAR(64) BINARY COMMENT '38;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '39;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '40;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '41;更新日時;',
    `af_fee_disp_flg` TINYINT COMMENT '42;AF料精算書表示フラグ;1:AF手数料欄を精算書に表示する 0:AF手数料欄を精算書に表示しない'
);

ALTER TABLE
    `billpay_customer_9xg` COMMENT '請求支払先（請求支払）_テスト用;請求支払先（請求支払）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_CUSTOMER_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_customer_hotel` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `customer_id` BIGINT COMMENT '3;請求支払先ID;連番、シーケンスは使用しない',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `billpay_customer_hotel` COMMENT '請求支払先関連施設（請求支払）;';

--   *** ------------------------------------
--  *** LLPAY_CUSTOMER_HOTEL_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_customer_hotel_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `customer_hotel_id` VARCHAR(10) BINARY COMMENT '2;精算先施設ID;YYYYMM9999',
    `customer_id` BIGINT COMMENT '3;精算先ID;連番、シーケンスは使用しない',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `date_s_ymd` DATETIME COMMENT '5;開始年月日（宿泊）;ヌル値の場合、過去に向けての制限なし',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `billpay_customer_hotel_9xg` COMMENT '請求支払先関連施設（請求支払）_テスト用;請求支払先関連施設（請求支払）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_FEE
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_fee` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '5;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '6;プランコード;',
    `payment_way` TINYINT COMMENT '7;決済方法;1:事前カード決済 2:現地決済',
    `bill_type` TINYINT COMMENT '8;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '9;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '10;請求対象消費税;',
    `system_rate` TINYINT COMMENT '11;システム利用料率;',
    `system_fee` INT COMMENT '12;システム利用料;税別 小数点以下切捨て',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '13;施設名称;',
    `room_nm` VARCHAR(120) BINARY COMMENT '14;部屋名称;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '15;プラン名称;',
    `guest_nm` VARCHAR(75) BINARY COMMENT '16;宿泊代表者氏名;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '17;宿泊代表者電話番号;',
    `check_in_ymd` DATETIME COMMENT '18;チェックイン日;',
    `stay` SMALLINT COMMENT '19;泊数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;',
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY,
    `partner_cd` VARCHAR(10) BINARY COMMENT '26;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '27;アフィリエイトコード;YYYYNNNNNN',
    `stock_class` TINYINT COMMENT '28;在庫種類;1:一般ネット在庫 2:連動在庫（通常） 3:連動在庫（ヴィジュアル） 4:連動在庫（プレミアム） 5:東横イン在庫',
    `affiliate_rate` DECIMAL(4, 2) COMMENT '29;アフィリエイト手数料率;提携先・アフィリエイト広告宣伝料率',
    `affiliate_fee` BIGINT COMMENT '30;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `reserve_dtm` DATETIME COMMENT '31;予約受付日時;'
);

ALTER TABLE
    `billpay_fee` COMMENT '宿泊別請求支払データ（システム利用料）;';

--   *** ------------------------------------
--  *** LLPAY_FEE_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_fee_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '5;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '6;プランコード;',
    `payment_way` TINYINT COMMENT '7;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `bill_type` TINYINT COMMENT '8;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '9;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '10;請求対象消費税;',
    `system_rate` SMALLINT COMMENT '11;システム利用料率;5%のときは5',
    `system_fee` INT COMMENT '12;システム利用料;税別 小数点以下切捨て',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '13;施設名称;',
    `room_nm` VARCHAR(120) BINARY COMMENT '14;部屋名称;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '15;プラン名称;ベストリザーブは60文字',
    `guest_nm` VARCHAR(75) BINARY COMMENT '16;宿泊代表者氏名;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '17;宿泊代表者電話番号;',
    `check_in_ymd` DATETIME COMMENT '18;チェックイン日;',
    `stay` SMALLINT COMMENT '19;泊数;',
    `room_id` VARCHAR(10) BINARY COMMENT '20;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '21;プランID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '26;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '27;アフィリエイトコード;YYYYNNNNNN',
    `stock_class` TINYINT COMMENT '28;在庫種類;1:一般ネット在庫 2:連動在庫（通常） 3:連動在庫（ヴィジュアル） 4:連動在庫（プレミアム） 5:東横イン在庫',
    `affiliate_rate` DECIMAL(4, 2) COMMENT '29;アフィリエイト手数料率;提携先・アフィリエイト広告宣伝料率',
    `affiliate_fee` BIGINT COMMENT '30;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `reserve_dtm` DATETIME COMMENT '31;予約受付日時;'
);

ALTER TABLE
    `billpay_fee_9xg` COMMENT '宿泊別請求支払データ（システム利用料）_テスト用;宿泊別請求支払データ（システム利用料）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_FEE_DRAFT
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_fee_draft` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月',
    `partner_cd` VARCHAR(10) BINARY COMMENT '4;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '5;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '6;アフィリエイトコード枝番;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '7;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '8;プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '9;部屋ID;',
    `payment_way` TINYINT COMMENT '10;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `later_payment` TINYINT COMMENT '11;後払い状態;0:通常 1:後払い',
    `hotel_type` TINYINT DEFAULT 0 COMMENT '12;ホテルタイプ;0:通用 1:プレミアム 2;ビジュアル 3;東横イン',
    `bill_type` TINYINT COMMENT '13;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '14;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '15;請求対象消費税;',
    `system_rate` SMALLINT COMMENT '16;システム利用料率;5%のときは5',
    `system_fee` INT COMMENT '17;システム利用料;税別 小数点以下切捨て',
    `sales_rate` DECIMAL(4, 2) COMMENT '18;販売手数料率;',
    `sales_fee` INT COMMENT '19;販売手数料;',
    `stock_rate` DECIMAL(4, 2) COMMENT '20;在庫手数料率;',
    `stock_fee` INT COMMENT '21;在庫手数料;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '22;施設名称;',
    `room_nm` VARCHAR(45) BINARY COMMENT '23;部屋名称;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '24;プラン名称;ベストリザーブは60文字',
    `guest_nm` VARCHAR(75) BINARY COMMENT '25;宿泊代表者氏名;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '26;宿泊代表者電話番号;',
    `room_cd` VARCHAR(10) BINARY COMMENT '27;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '28;プランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '29;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '30;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '31;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '32;更新日時;'
);

ALTER TABLE
    `billpay_fee_draft` COMMENT '宿泊別仮締（広告宣伝費）;';

--   *** ------------------------------------
--  *** LLPAY_FIX
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_fix` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `fix_status` TINYINT COMMENT '2;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `billpay_fix` COMMENT '請求支払検実行用;請求支払実行開始時の検収状態を保持し精算処理を行う。';

--   *** ------------------------------------
--  *** LLPAY_FIX_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_fix_9xg` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `fix_status` TINYINT COMMENT '2;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `billpay_fix_9xg` COMMENT '請求支払検収実行用_テスト用;請求支払検収実行用本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '3;施設名称;',
    `billpay_hotel_status` TINYINT COMMENT '4;請求支払状態;0：請求・支払未処理 1:請求・支払済み',
    `billpay_cd` VARCHAR(13) BINARY COMMENT '5;請求支払コード;請求 ： YYYYMM + 請求先・支払先ID(4桁) + 「A」 支払 ： YYYYMM + 請求先・支払先ID(4桁) + 「P」',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `billpay_hotel` COMMENT '請求支払施設データ;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '3;施設名称;',
    `billpay_cd` VARCHAR(12) BINARY COMMENT '4;請求支払コード;請求（振込・引落）： YYYYMM + 精算先ID(4 or 5桁) + 「A」 支払 ： YYYYMM + 精算先ID(4 or 5桁) + 「P」 特別： YYYYMM + 精算先ID(4 or 5桁) + 「S」',
    `billpay_hotel_status` TINYINT COMMENT '5;請求支払状態;0：請求・支払未処理 1:請求・支払確定済み 2:請求・支払繰り越し',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `billpay_hotel_9xg` COMMENT '施設別請求支払データ_テスト用;施設別請求支払データ本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_credit` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `card_charge_sales_real` BIGINT COMMENT '3;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real` BIGINT COMMENT '4;カード決済金額キャンセル（実態）;税込',
    `card_charge_sales` BIGINT COMMENT '5;カード決済金額宿泊（施設向け）;税込',
    `card_charge_cancel` BIGINT COMMENT '6;カード決済金額キャンセル（施設向け）;税込',
    `card_count` INT COMMENT '7;カード決済回数;',
    `card_fee_real` BIGINT COMMENT '8;カード決済手数料（実態）;税別',
    `card_fee_tax_real` BIGINT COMMENT '9;カード決済手数料消費税（実態）;',
    `card_fee` BIGINT COMMENT '10;カード決済手数料（施設向け）;税別',
    `card_fee_tax` BIGINT COMMENT '11;カード決済手数料消費税（施設向け）;',
    `card_work_fee` INT COMMENT '12;カード事務手数料;税別',
    `card_work_fee_tax` SMALLINT COMMENT '13;カード事務手数料消費税;',
    `authori_count` INT COMMENT '14;カードオーソリ回数;',
    `authori_fee` BIGINT COMMENT '15;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `authori_fee_tax` BIGINT COMMENT '16;カードオーソリ手数料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '17;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '18;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '19;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '20;更新日時;'
);

ALTER TABLE
    `billpay_hotel_credit` COMMENT '施設別請求支払データ（カード決済）;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_CREDIT_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_credit_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `card_charge_sales_real` BIGINT COMMENT '3;カード決済金額宿泊（実態）;税込',
    `card_charge_cancel_real` BIGINT COMMENT '4;カード決済金額キャンセル（実態）;税込',
    `card_charge_sales` BIGINT COMMENT '5;カード決済金額宿泊（ポイント分含む）;税込',
    `card_charge_cancel` BIGINT COMMENT '6;カード決済金額キャンセル（ポイント分含む）;税込',
    `card_count` INT COMMENT '7;カード決済回数;',
    `card_fee_real` BIGINT COMMENT '8;カード決済手数料（実態）;税別 切捨て',
    `card_fee_tax_real` BIGINT COMMENT '9;カード決済手数料消費税（実態）;',
    `card_fee` BIGINT COMMENT '10;カード決済手数料（施設負担分）;税別 切捨て',
    `card_fee_tax` BIGINT COMMENT '11;カード決済手数料消費税（施設負担分）;',
    `card_work_fee` INT COMMENT '12;カード事務手数料;税別',
    `card_work_fee_tax` SMALLINT COMMENT '13;カード事務手数料消費税;',
    `authori_count` SMALLINT COMMENT '14;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '15;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `authori_fee_tax` BIGINT COMMENT '16;カードオーソリ手数料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '17;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '18;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '19;	更新者コード;',
    `modify_ts` DATETIME COMMENT '20;更新日時;'
);

ALTER TABLE
    `billpay_hotel_credit_9xg` COMMENT '施設別請求支払データ（カード決済）_テスト用;施設別請求支払データ（カード決済）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_FEE
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_fee` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `sales_count` SMALLINT COMMENT '3;宿泊室数;',
    `cancel_count` SMALLINT COMMENT '4;キャンセル室数;',
    `bill_charge` BIGINT COMMENT '5;宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '6;料金消費税;',
    `system_rate` SMALLINT COMMENT '7;システム利用料率;最大値',
    `system_fee` BIGINT COMMENT '8;システム利用料;税別',
    `system_fee_tax` BIGINT COMMENT '9;システム利用料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `affiliate_fee` BIGINT COMMENT '14;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `affiliate_fee_tax` BIGINT COMMENT '15;アフィリエイト料消費税;提携先・アフィリエイト広告宣伝料消費税'
);

ALTER TABLE
    `billpay_hotel_fee` COMMENT '施設別請求支払データ（システム利用料）;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_FEE_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_fee_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `sales_count` SMALLINT COMMENT '3;宿泊室数;',
    `cancel_count` SMALLINT COMMENT '4;キャンセル室数;',
    `bill_charge` BIGINT COMMENT '5;宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '6;料金消費税;',
    `system_rate` SMALLINT COMMENT '7;システム利用料率;5%のときは5',
    `system_fee` BIGINT COMMENT '8;システム利用料;',
    `system_fee_tax` BIGINT COMMENT '9;システム利用料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `affiliate_fee` BIGINT COMMENT '14;アフィリエイト料;提携先・アフィリエイト広告宣伝料',
    `affiliate_fee_tax` BIGINT COMMENT '15;アフィリエイト料消費税;提携先・アフィリエイト広告宣伝料消費税'
);

ALTER TABLE
    `billpay_hotel_fee_9xg` COMMENT '	施設別請求支払データ（システム利用料）_テスト用;	施設別請求支払データ（システム利用料）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_grants` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_grants_real` BIGINT COMMENT '3;補助金額(実態);',
    `use_grants` BIGINT COMMENT '4;補助金額(カード分含まない);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `use_coupon_real` BIGINT,
    `use_coupon` BIGINT
);

ALTER TABLE
    `billpay_hotel_grants` COMMENT '施設別請求支払データ（補助金）;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_grants_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_grants_real` BIGINT COMMENT '3;補助金額(実態);',
    `use_grants` BIGINT COMMENT '4;補助金額(カード分含まない);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `use_coupon_real` BIGINT,
    `use_coupon` BIGINT
);

ALTER TABLE
    `billpay_hotel_grants_9xg` COMMENT '	施設別請求支払データ（補助金）_テスト用;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_rsv` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_br_point_charge_real` BIGINT COMMENT '3;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge` BIGINT COMMENT '4;消費ＢＲポイント割引料金（施設向け）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `get_br_point_charge` BIGINT,
    `get_br_point_charge_hotel` BIGINT
);

ALTER TABLE
    `billpay_hotel_rsv` COMMENT '施設別請求支払データ（リザーブ）;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_RSV_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_rsv_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_br_point_charge` BIGINT COMMENT '3;獲得ＢＲポイント;1ポイント１円 税込',
    `get_br_point_charge_hotel` BIGINT COMMENT '4;獲得ＢＲポイント（施設負担分）;',
    `use_br_point_charge_real` BIGINT COMMENT '5;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge` BIGINT COMMENT '6;消費ＢＲポイント割引料金（カード分含まない）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `billpay_hotel_rsv_9xg` COMMENT '施設別請求支払データ（リザーブ）_テスト用;施設別請求支払データ（リザーブ）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_yahoo` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_yahoo_point` BIGINT COMMENT '3;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point_real` BIGINT COMMENT '5;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point` BIGINT COMMENT '6;消費ヤフーポイント（カード分含まない）;１ポイント１円 税込',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `get_yahoo_point_hotel` BIGINT COMMENT '4;獲得ヤフーポイント（施設負担分）;'
);

ALTER TABLE
    `billpay_hotel_yahoo` COMMENT '施設別請求支払データ（ヤフー）;';

--   *** ------------------------------------
--  *** LLPAY_HOTEL_YAHOO_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hotel_yahoo_9xg` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_yahoo_point` BIGINT COMMENT '3;獲得ヤフーポイント;1ポイント１円 税込',
    `get_yahoo_point_hotel` BIGINT COMMENT '4;獲得ヤフーポイント（施設負担分）;',
    `use_yahoo_point_real` BIGINT COMMENT '5;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point` BIGINT COMMENT '6;消費ヤフーポイント（カード分含まない）;現地決済の時は実態のポイント、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `billpay_hotel_yahoo_9xg` COMMENT '施設別請求支払データ（ヤフー）_テスト用;施設別請求支払データ（ヤフー）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_HR_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hr_grants` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `welfare_grants_id` BIGINT COMMENT '3;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '4;福利厚生補助金履歴ID;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `bill_type` TINYINT COMMENT '10;請求対象タイプ;0:宿泊 1:キャンセル',
    `use_grants_real` BIGINT COMMENT '11;補助金額(実態);',
    `use_grants` BIGINT COMMENT '12;補助金額(カード分含まない);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `billpay_hr_grants` COMMENT '宿泊別請求支払データ（補助金）;補助金適用先への精算用';

--   *** ------------------------------------
--  *** LLPAY_HR_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_hr_grants_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `welfare_grants_id` BIGINT COMMENT '3;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '4;福利厚生補助金履歴ID;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `bill_type` TINYINT COMMENT '10;請求対象タイプ;0:宿泊 1:キャンセル',
    `use_grants_real` BIGINT COMMENT '11;補助金額(実態);',
    `use_grants` BIGINT COMMENT '12;補助金額(カード分含まない);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `billpay_hr_grants_9xg` COMMENT '宿泊別請求支払データ（補助金）_テスト;';

--   *** ------------------------------------
--  *** LLPAY_PR_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_pr_grants` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `partner_cd` VARCHAR(10) BINARY COMMENT '5;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '6;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '7;アフィリエイトコード枝番;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '8;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '9;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '10;プランID;',
    `payment_way` TINYINT COMMENT '11;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `bill_type` TINYINT COMMENT '12;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '13;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '14;請求対象消費税;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '15;施設名称;',
    `welfare_grants_id` BIGINT COMMENT '16;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '17;福利厚生補助金情報履歴ID;',
    `order_cd` VARCHAR(15) BINARY COMMENT '18;予約申込コード;B99999999-NNN （  B+８桁数値-年（桁）月の１６進 ）',
    `use_grants` BIGINT DEFAULT 0 COMMENT '19;補助金額;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;'
);

ALTER TABLE
    `billpay_pr_grants` COMMENT '宿泊別(補助金);';

--   *** ------------------------------------
--  *** LLPAY_PTN
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `fee_type` TINYINT COMMENT '3;手数料タイプ;1:販売 2:在庫',
    `site_nm` VARCHAR(196) BINARY COMMENT '4;提携サイト名称;',
    `billpay_ptn_cd` VARCHAR(29) BINARY COMMENT '5;請求支払コード（提携先）;請求（振込・引落）：「P」 +  YYYYMM + 精算先ID(4 or 5桁) + 「A」 支払 ： 「P」 + YYYYMM + 精算先ID(4 or 5桁) + 「P」',
    `billpay_ptn_status` TINYINT COMMENT '6;請求支払状態（提携先）;0：請求・支払未処理 1:請求・支払済み',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `billpay_ptn` COMMENT '提携先別;';

--   *** ------------------------------------
--  *** LLPAY_PTN_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_book` (
    `billpay_ptn_cd` VARCHAR(29) BINARY COMMENT '1;請求支払提携先コード;請求（振込・引落）：「P」 +  YYYYMM + 精算先ID(4 or 5桁) + 「A」 支払 ： 「P」 +  YYYYMM + 精算先ID(4 or 5桁) + 「P」',
    `billpay_branch_no` TINYINT COMMENT '2;請求支払枝番;1以上の場合、請求・支払コードに「-」で結合して表示する （ YYYYMM9999A-9 ）',
    `billpay_type` TINYINT COMMENT '3;請求支払タイプ;0:請求(振込) 1:支払 2:請求（引落）',
    `billpay_charge_total` BIGINT COMMENT '4;請求支払総額;',
    `billpay_ym` DATETIME COMMENT '5;請求支払処理年月;請求・支払作成実行年月',
    `customer_id` BIGINT COMMENT '6;支払先ID;連番、シーケンスは使用しない',
    `bill_ymd` DATETIME COMMENT '7;請求振込期日年月日;',
    `book_path` VARCHAR(128) BINARY COMMENT '8;原稿ファイルパス;',
    `billpay_status` TINYINT COMMENT '9;作業状態;0;原稿作成待ち 1:原稿作成済み 10:印刷しない 20:印刷待ち 21:印刷済み',
    `billpay_condition` VARCHAR(16) BINARY COMMENT '10;作業状況（送信）;ヌル値: 未処理 request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '11;原稿作成日時;',
    `book_print_dtm` DATETIME COMMENT '12;印刷日時;',
    `sent_dtm` DATETIME COMMENT '13;送信日時分秒;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `billpay_ptn_book` COMMENT '提携先別台帳;';

--   *** ------------------------------------
--  *** LLPAY_PTN_CSTMR
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_cstmr` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `customer_id` BIGINT COMMENT '2;支払先ID;大文字統一',
    `customer_nm` VARCHAR(150) BINARY COMMENT '3;支払先名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '4;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '5;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '6;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '7;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '8;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '9;チャネル合算支払先;',
    `person_post` VARCHAR(96) BINARY COMMENT '10;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '11;担当者名称;',
    `mail_send` TINYINT COMMENT '12;メール送信可否;0:送付しない 1:送付する',
    `cancel_status` TINYINT COMMENT '13;精算キャンセル対象状態;0: 予約のみ 1:キャンセル含む',
    `tax_unit` BIGINT COMMENT '14;消費税単位;1:サイト単位 2:手数料率単位（NTA精算用）',
    `document_type` TINYINT COMMENT '15;精算書タイプ;1: 請求のみ 2:支払のみ 3:両方',
    `detail_status` TINYINT COMMENT '16;明細書有無;0:明細なし 1:明細あり',
    `billpay_day` TINYINT COMMENT '17;精算日;1: 仮締日（毎月１日）  8:本締日（毎月8日）',
    `billpay_required` TINYINT COMMENT '18;精算必須月;0 : 請求しない 1 : 請求する',
    `billpay_charge_min` INT COMMENT '19;精算最低金額;デフォルト: 50000',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;'
);

ALTER TABLE
    `billpay_ptn_cstmr` COMMENT '精算先（提携先）;';

--   *** ------------------------------------
--  *** LLPAY_PTN_CSTMRSITE
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_cstmrsite` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `customer_id` BIGINT COMMENT '2;支払先ID;大文字統一',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `fee_type` TINYINT COMMENT '4;手数料タイプ;1:販売 2:在庫',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `billpay_ptn_cstmrsite` COMMENT '精算先関連サイト（提携先）;';

--   *** ------------------------------------
--  *** LLPAY_PTN_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_grants` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `bill_sales_count` INT COMMENT '3;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '4;精算対象キャンセル室数;',
    `bill_charge` BIGINT COMMENT '5;精算対象宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '6;料金消費税;',
    `use_grants` BIGINT COMMENT '7;補助金額(キャンセル充当分含まない);',
    `use_grants_apply_cancel` BIGINT COMMENT '8;補助金額(キャンセル充当分);',
    `use_grants_total` BIGINT COMMENT '9;補助金額合計;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '10;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '11;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `billpay_ptn_grants` COMMENT '提携先別(補助金);';

--   *** ------------------------------------
--  *** LLPAY_PTN_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_sales` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `sales_rate` DECIMAL(4, 2) COMMENT '3;販売手数料率;',
    `bill_sales_count` INT COMMENT '4;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '5;精算対象キャンセル室数;',
    `bill_charge` BIGINT COMMENT '6;精算対象宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '7;精算対象宿泊料金消費税;',
    `sales_fee` BIGINT COMMENT '8;販売手数料;消費税単位が サイト単位の提携先は、サイト単位で集計下手数料から消費税を算出する。',
    `sales_fee_tax` BIGINT COMMENT '9;販売手数料消費税;消費税単位が サイト単位の提携先は、サイト単位で集計下手数料から消費税を算出する。',
    `later_sales_count` INT COMMENT '10;宿泊室数（後払分）;',
    `later_cancel_count` INT COMMENT '11;キャンセル室数（後払分）;',
    `later_sales_charge` BIGINT COMMENT '12;宿泊料金（後払分）;後払分の宿泊料金（税さ込）',
    `later_cancel_charge` BIGINT COMMENT '13;キャンセル料金（後払分）;後払分のキャンセル料金',
    `partner_cd` VARCHAR(10) BINARY COMMENT '14;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '15;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `billpay_ptn_sales` COMMENT '提携先別（販売手数料）;';

--   *** ------------------------------------
--  *** LLPAY_PTN_SITE
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_site` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `site_nm` VARCHAR(196) BINARY COMMENT '3;提携先サイト名称;',
    `email` VARCHAR(200) BINARY COMMENT '4;チャネル別支払先;',
    `person_post` VARCHAR(96) BINARY COMMENT '5;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '6;担当者名称;',
    `mail_send` TINYINT COMMENT '7;メール送信可否;0:送付しない 1:送付する',
    `partner_cd` VARCHAR(10) BINARY COMMENT '8;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '9;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `billpay_ptn_site` COMMENT '提携サイト;';

--   *** ------------------------------------
--  *** LLPAY_PTN_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_stock` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `stock_rate` DECIMAL(4, 2) COMMENT '3;在庫手数料率;',
    `bill_sales_count` INT COMMENT '4;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '5;精算対象キャンセル室数;',
    `bill_charge` BIGINT COMMENT '6;精算対象宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '7;料金消費税;',
    `stock_fee` INT COMMENT '8;在庫手数料;',
    `stock_fee_tax` BIGINT COMMENT '9;在庫手数料消費税;消費税単位が サイト単位の提携先は、サイト単位で集計下手数料から消費税を算出する。',
    `partner_cd` VARCHAR(10) BINARY COMMENT '10;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '11;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `billpay_ptn_stock` COMMENT '提携先別（在庫手数料）;';

--   *** ------------------------------------
--  *** LLPAY_PTN_TYPE_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_type_grants` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `bill_sales_count` INT COMMENT '3;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '4;精算対象キャンセル室数;',
    `bill_charge` BIGINT COMMENT '5;精算対象宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '6;料金消費税;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '9;アフィリエイトコード枝番;',
    `welfare_grants_id` BIGINT COMMENT '10;福利厚生補助金ID;',
    `use_grants` BIGINT COMMENT '11;補助金額(キャンセル充当分含まない);',
    `use_grants_apply_cancel` BIGINT COMMENT '12;補助金額(キャンセル充当分);',
    `use_grants_total` BIGINT COMMENT '13;補助金額合計;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `billpay_ptn_type_grants` COMMENT '提携先補助金種類別(補助金);';

--   *** ------------------------------------
--  *** LLPAY_PTN_TYPE_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_type_sales` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `stock_type` TINYINT COMMENT '3;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `sales_rate` DECIMAL(4, 2) COMMENT '4;販売手数料率;',
    `bill_sales_count` INT COMMENT '5;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '6;精算対象キャンセル室数;',
    `bill_charge` BIGINT COMMENT '7;精算対象宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '8;料金消費税;',
    `sales_fee` INT COMMENT '9;販売手数料;',
    `sales_fee_tax` BIGINT COMMENT '10;販売手数料消費税;消費税単位が サイト・手数料率単位の提携先のみ値設定、 サイト単位の場合はヌル値を設定',
    `later_sales_count` INT COMMENT '11;宿泊室数（後払分）;',
    `later_cancel_count` INT COMMENT '12;キャンセル室数（後払分）;',
    `later_sales_charge` BIGINT COMMENT '13;宿泊料金（後払分）;後払分の宿泊料金（税さ込）',
    `later_cancel_charge` BIGINT COMMENT '14;キャンセル料金（後払分）;後払分のキャンセル料金',
    `partner_cd` VARCHAR(10) BINARY COMMENT '15;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '16;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '17;アフィリエイトコード枝番;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '18;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '19;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '20;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '21;更新日時;'
);

ALTER TABLE
    `billpay_ptn_type_sales` COMMENT '提携先在庫タイプ別（販売手数料）;';

--   *** ------------------------------------
--  *** LLPAY_PTN_TYPE_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_ptn_type_stock` (
    `billpay_ym` DATETIME COMMENT '1;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `stock_type` TINYINT COMMENT '3;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `stock_rate` DECIMAL(4, 2) COMMENT '4;在庫手数料率;',
    `bill_sales_count` INT COMMENT '5;精算対象宿泊室数;',
    `bill_cancel_count` INT COMMENT '6;精算対象キャンセル室数;',
    `bill_charge` BIGINT COMMENT '7;精算対象宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '8;料金消費税;',
    `stock_fee` INT COMMENT '9;在庫手数料;',
    `stock_fee_tax` BIGINT COMMENT '10;在庫手数料消費税;消費税単位が サイト・手数料率単位の提携先のみ値設定、 サイト単位の場合はヌル値を設定',
    `partner_cd` VARCHAR(10) BINARY COMMENT '11;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '12;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '13;アフィリエイトコード枝番;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `billpay_ptn_type_stock` COMMENT '提携先在庫タイプ別（在庫手数料）;';

--   *** ------------------------------------
--  *** LLPAY_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_rsv` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `use_br_point_charge_real` INT COMMENT '5;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge` INT COMMENT '6;消費ＢＲポイント割引料金（施設向け）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `get_br_point_rate` SMALLINT,
    `get_br_point_rate_our` SMALLINT,
    `get_br_point_charge` INT,
    `get_br_point_charge_hotel` INT
);

ALTER TABLE
    `billpay_rsv` COMMENT '宿泊別請求支払データ（リザーブ）;';

--   *** ------------------------------------
--  *** LLPAY_RSV_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_rsv_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `get_br_point_rate` SMALLINT COMMENT '5;獲得ＢＲポイント率;',
    `get_br_point_rate_our` SMALLINT COMMENT '6;獲得ＢＲポイント当社負担率;',
    `get_br_point_charge` INT COMMENT '7;獲得ＢＲポイント;1ポイント１円 税込',
    `get_br_point_charge_hotel` INT COMMENT '8;獲得ＢＲポイント（施設負担分）;',
    `use_br_point_charge_real` INT COMMENT '9;消費ＢＲポイント割引料金（実態）;',
    `use_br_point_charge` INT COMMENT '10;消費ＢＲポイント割引料金（カード分含まない）;現地決済の時は実態の割引料金、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `billpay_rsv_9xg` COMMENT '宿泊別請求支払データ（リザーブ）_テスト用;宿泊別請求支払データ（リザーブ）本番テスト用';

--   *** ------------------------------------
--  *** LLPAY_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_sales` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月',
    `partner_cd` VARCHAR(10) BINARY COMMENT '5;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '6;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '7;アフィリエイトコード枝番;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '8;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '9;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '10;プランID;',
    `payment_way` TINYINT COMMENT '11;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `later_payment` TINYINT COMMENT '12;後払い状態;0:通常 1:後払い',
    `hotel_type` TINYINT DEFAULT 0 COMMENT '13;ホテルタイプ;0:通用 1:プレミアム 2;ビジュアル 3;東横イン',
    `stock_type` TINYINT COMMENT '14;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `bill_type` TINYINT COMMENT '15;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '16;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '17;請求対象消費税;',
    `sales_rate` DECIMAL(4, 2) COMMENT '18;販売手数料率;',
    `sales_fee` INT COMMENT '19;販売手数料;',
    `room_cd` VARCHAR(10) BINARY COMMENT '25;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '26;プランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '27;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '28;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '29;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '30;更新日時;'
);

ALTER TABLE
    `billpay_sales` COMMENT '宿泊別（販売手数料）;';

--   *** ------------------------------------
--  *** LLPAY_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_stock` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `site_cd` VARCHAR(10) BINARY COMMENT '3;サイトコード;',
    `billpay_ym` DATETIME COMMENT '4;請求支払処理年月;請求・支払作成実行年月',
    `partner_cd` VARCHAR(10) BINARY COMMENT '5;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '6;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '7;アフィリエイトコード枝番;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '8;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '9;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '10;プランID;',
    `payment_way` TINYINT COMMENT '11;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `later_payment` TINYINT COMMENT '12;後払い状態;0:通常 1:後払い',
    `hotel_type` TINYINT DEFAULT 0 COMMENT '13;ホテルタイプ;0:通用 1:プレミアム 2;ビジュアル 3;東横イン',
    `stock_type` TINYINT COMMENT '14;在庫タイプ;1:一般ネット在庫 2:連動在庫 3:東横イン在庫',
    `bill_type` TINYINT COMMENT '15;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '16;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '17;請求対象消費税;',
    `stock_rate` DECIMAL(4, 2) COMMENT '18;在庫手数料率;',
    `stock_fee` INT COMMENT '19;在庫手数料;',
    `room_cd` VARCHAR(10) BINARY COMMENT '25;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '26;プランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '27;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '28;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '29;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '30;更新日時;'
);

ALTER TABLE
    `billpay_stock` COMMENT '宿泊別（在庫手数料）;';

--   *** ------------------------------------
--  *** LLPAY_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_yahoo` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `get_yahoo_point` INT COMMENT '5;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point_real` INT COMMENT '6;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point` INT COMMENT '7;消費ヤフーポイント（施設向け）;現地決済の時は実態のポイント、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `get_yahoo_point_hotel` INT,
    `get_yahoo_point_rate` DECIMAL(5, 2),
    `get_yahoo_point_rate_our` DECIMAL(5, 2)
);

ALTER TABLE
    `billpay_yahoo` COMMENT '宿泊別請求支払データ（ヤフー）;';

--   *** ------------------------------------
--  *** LLPAY_YAHOO_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `billpay_yahoo_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月  通常 1日（例 2015-01-01） が設定されます。  特別対応の場合のみ2日（例 2015-01-02）が設定されます。',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `get_yahoo_point_rate` DECIMAL(5, 2) COMMENT '5;獲得ヤフーポイント率;',
    `get_yahoo_point_rate_our` DECIMAL(5, 2) COMMENT '6;獲得ヤフーポイント当社負担率;',
    `get_yahoo_point` INT COMMENT '7;獲得ヤフーポイント;1ポイント１円 税込',
    `get_yahoo_point_hotel` INT COMMENT '8;獲得ヤフーポイント（施設負担分）;',
    `use_yahoo_point_real` INT COMMENT '9;消費ヤフーポイント（実態）;ヤフーへの請求用',
    `use_yahoo_point` INT COMMENT '10;消費ヤフーポイント（カード分含まない）;現地決済の時は実態のポイント、 カード決済の場合 ０ ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `billpay_yahoo_9xg` COMMENT '宿泊別請求支払データ（ヤフー）_テスト用;宿泊別請求支払データ（ヤフー）本番テスト用';

--   *** ------------------------------------
--  *** OADCAST_MESSAGE
--   *** ------------------------------------
-- 
CREATE TABLE `broadcast_message` (
    `id` INT COMMENT '1;ID;',
    `title` VARCHAR(240) BINARY COMMENT '2;お知らせタイトル;',
    `description` VARCHAR(4000) BINARY COMMENT '3;詳細;',
    `accept_s_dtm` DATETIME COMMENT '4;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '5;終了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `header_message` VARCHAR(1800) BINARY COMMENT '10;ヘッダー表示文言;施設お知らせ管理画面のヘッダー部に表示する文言',
    `accept_header_s_dtm` DATETIME COMMENT '11;ヘッダー表示開始日時;施設お知らせ管理画面のヘッダー部に表示する文言の表示期間開始日',
    `accept_header_e_dtm` DATETIME COMMENT '12;ヘッダー表示終了日時;施設お知らせ管理画面のヘッダー部に表示する文言の表示期間終了日',
    `target_select_sql` LONGTEXT COMMENT '13;抽出条件SQL;お知らせ情報表示対象施設抽出SQL'
);

ALTER TABLE
    `broadcast_message` COMMENT '管理画面お知らせ;';

--   *** ------------------------------------
--  *** OADCAST_MESSAGES_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `broadcast_messages_hotel` (
    `broadcast_messages_hotel_id` BIGINT COMMENT '1;お知らせ施設ID;',
    `broadcast_messages_id` INT COMMENT '2;お知らせID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `order_number` INT COMMENT '4;並び順;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;	登録者コード;',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;	更新者コード;',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `broadcast_messages_hotel` COMMENT '管理画面お知らせ施設;';

--   *** ------------------------------------
--  *** _EKI_ROUTE_WK
--   *** ------------------------------------
-- 
CREATE TABLE `br_eki_route_wk` (
    `route_id` BIGINT,
    `route_cd` VARCHAR(7) BINARY,
    `order_no` TINYINT
);

--   *** ------------------------------------
--  *** _POINT_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_book` (
    `br_point_cd` VARCHAR(16) BINARY COMMENT '1;BRポイントコード;YYYYMMNNNNNNNNNN',
    `relation_cd` VARCHAR(16) BINARY COMMENT '2;関連BRポイントコード;YYYYMMNNNNNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '3;会員コード;ベストリザーブ会員は20バイト',
    `br_point_type` TINYINT COMMENT '4;BRポイント種別;',
    `get_br_point` INT COMMENT '5;獲得ポイント;',
    `use_br_point` INT COMMENT '6;消費ポイント;',
    `applied_ymd` DATETIME COMMENT '7;適用年月日;',
    `lost_ymd` DATETIME COMMENT '8;失効年月日;ヌル値は無制限',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '9;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '10;宿泊日;',
    `gift_order_cd` VARCHAR(10) BINARY COMMENT '11;ギフト交換コード;YYYYNNNNNN',
    `service_cd` VARCHAR(6) BINARY COMMENT '12;サービスコード;YYYYNN',
    `various_cd` VARCHAR(64) BINARY COMMENT '13;汎用コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `br_point_book` COMMENT 'BRポイント台帳;';

--   *** ------------------------------------
--  *** _POINT_BOOK_V3
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_book_v3` (
    `br_point_cd` VARCHAR(16) BINARY,
    `relation_cd` VARCHAR(16) BINARY,
    `member_cd` VARCHAR(128) BINARY,
    `br_point_type` TINYINT,
    `applied_ymd` DATETIME,
    `br_point_condition` TINYINT,
    `get_br_point` BIGINT,
    `use_br_point` BIGINT,
    `note` VARCHAR(128) BINARY,
    `shifting_ymd` DATETIME,
    `service_cd` VARCHAR(6) BINARY,
    `service_sub_cd` VARCHAR(64) BINARY,
    `transaction_cd` VARCHAR(14) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** _POINT_BOOK_V4
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_book_v4` (
    `br_point_cd` VARCHAR(16) BINARY COMMENT '1;ＢＲポイントコード;YYYYMMNNNNNNNNNN',
    `relation_cd` VARCHAR(16) BINARY COMMENT '2;関連BRポイントコード;YYYYMMNNNNNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '3;会員コード;ベストリザーブ会員は20バイト',
    `br_point_type` TINYINT COMMENT '4;ポイント種類;1:獲得 -1;消費',
    `applied_ymd` DATETIME COMMENT '5;適用年月日;',
    `br_point_condition` TINYINT COMMENT '6;適用状態;1: 登録 -1: キャンセル 0;失効',
    `get_br_point` BIGINT COMMENT '7;獲得ポイント;',
    `use_br_point` BIGINT COMMENT '8;消費ポイント;',
    `note` VARCHAR(600) BINARY COMMENT '9;内容;',
    `shifting_ymd` DATETIME COMMENT '10;確定予定年月日;ユーザ画面への確定予定時期紹介用（ 実際の確定日ではない）',
    `service_cd` VARCHAR(6) BINARY COMMENT '11;サービスコード;YYYYNN（内容は、br_point_service を参照）',
    `service_sub_cd` VARCHAR(64) BINARY COMMENT '12;サービス補助コード;各サービスの仕様に従う（ 予約コード＋宿泊年月日、 申込コードなど）',
    `transaction_cd` VARCHAR(14) BINARY COMMENT '13;トランザクションコード;年月日時分秒 （yyyymmddhh24miss)',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;',
    `expire_ymd` DATETIME COMMENT '14;有効期限;',
    `lost_flag` TINYINT COMMENT '15;失効処理済フラグ;0:未処理 1: 処理済み'
);

ALTER TABLE
    `br_point_book_v4` COMMENT 'ＢＲポイント台帳（バージョン４）;確定ポイント保存テーブル';

--   *** ------------------------------------
--  *** _POINT_BOOK_V4_DRAFT
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_book_v4_draft` (
    `br_point_cd` VARCHAR(16) BINARY COMMENT '1;ＢＲポイントコード;YYYYMMNNNNNNNNNN シーケンスは br_point_book_v4_seq を使用',
    `relation_cd` VARCHAR(16) BINARY COMMENT '2;関連BRポイントコード;YYYYMMNNNNNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '3;会員コード;ベストリザーブ会員は20バイト',
    `br_point_type` TINYINT COMMENT '4;ポイント種類;1:獲得 -1;消費',
    `applied_ymd` DATETIME COMMENT '5;適用年月日;',
    `br_point_condition` TINYINT COMMENT '6;適用状態;1: 登録 -1: キャンセル 0;確定',
    `get_br_point` BIGINT COMMENT '7;獲得ポイント;',
    `use_br_point` BIGINT COMMENT '8;消費ポイント;',
    `note` VARCHAR(600) BINARY COMMENT '9;内容;',
    `shifting_ymd` DATETIME COMMENT '10;確定予定年月日;',
    `service_cd` VARCHAR(6) BINARY COMMENT '11;サービスコード;YYYYNN',
    `service_sub_cd` VARCHAR(64) BINARY COMMENT '12;サービス補助コード;各サービスの仕様に従う（ 予約コード＋宿泊年月日、 申込コードなど）',
    `transaction_cd` VARCHAR(14) BINARY COMMENT '13;トランザクションコード;年月日時分秒 （yyyymmddhh24miss)',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;',
    `expire_ymd` DATETIME COMMENT '14;有効期限;',
    `lost_flag` TINYINT COMMENT '15;失効処理済フラグ;0:未処理 1: 処理済み'
);

ALTER TABLE
    `br_point_book_v4_draft` COMMENT 'ＢＲポイント仮台帳（バージョン４）;仮ポイント保存テーブル';

--   *** ------------------------------------
--  *** _POINT_BOOK_V4_LOG
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_book_v4_log` (
    `br_point_cd` VARCHAR(16) BINARY COMMENT '1;ＢＲポイントコード;YYYYMMNNNNNNNNNN',
    `relation_cd` VARCHAR(16) BINARY COMMENT '2;関連BRポイントコード;YYYYMMNNNNNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '3;会員コード;ベストリザーブ会員は20バイト',
    `br_point_type` TINYINT COMMENT '4;ポイント種類;1:獲得 -1;消費 0;失効',
    `applied_ymd` DATETIME COMMENT '5;適用年月日;',
    `br_point_condition` TINYINT COMMENT '6;適用状態;1: 登録 -1: キャンセル',
    `get_br_point` BIGINT COMMENT '7;獲得ポイント;',
    `use_br_point` BIGINT COMMENT '8;消費ポイント;',
    `note` VARCHAR(128) BINARY COMMENT '9;内容;',
    `shifting_ymd` DATETIME COMMENT '10;確定予定年月日;ユーザ画面への確定予定時期紹介用（ 実際の確定日ではない）',
    `service_cd` VARCHAR(6) BINARY COMMENT '11;サービスコード;YYYYNN（内容は、\\office-net\fs\開発\限定\Project\Reserve\BRポイントV4仕様.xls を参照）',
    `service_sub_cd` VARCHAR(64) BINARY COMMENT '12;サービス補助コード;各サービスの仕様に従う（ 予約コード＋宿泊年月日、 申込コードなど）',
    `transaction_cd` VARCHAR(14) BINARY COMMENT '13;トランザクションコード;年月日時分秒 （yyyymmddhh24miss)',
    `expire_ymd` DATETIME COMMENT '14;有効期限;',
    `lost_flag` TINYINT COMMENT '15;失効処理済フラグ;0:未処理 1: 処理済み',
    `org_entry_cd` VARCHAR(64) BINARY COMMENT '16;元登録者コード;ＢＲポイント台帳（バージョン４）の/controller/action.(user_id) または 更新者メールアドレス',
    `org_entry_ts` DATETIME COMMENT '17;元登録日時;ＢＲポイント台帳（バージョン４）の登録日時',
    `org_modify_cd` VARCHAR(64) BINARY COMMENT '18;元更新者コード;ＢＲポイント台帳（バージョン４）の/controller/action.(user_id) または 更新者メールアドレス',
    `org_modify_ts` DATETIME COMMENT '19;元更新日時;ＢＲポイント台帳（バージョン４）の更新日時',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;'
);

ALTER TABLE
    `br_point_book_v4_log` COMMENT 'ＢＲポイント台帳（バージョン４）履歴;確定ポイント保存テーブル履歴';

--   *** ------------------------------------
--  *** _POINT_BOOK_V4_WK
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_book_v4_wk` (
    `br_point_cd` VARCHAR(16) BINARY,
    `relation_cd` VARCHAR(16) BINARY,
    `member_cd` VARCHAR(128) BINARY,
    `br_point_type` TINYINT,
    `applied_ymd` DATETIME,
    `br_point_condition` TINYINT,
    `get_br_point` BIGINT,
    `use_br_point` BIGINT,
    `note` VARCHAR(128) BINARY,
    `shifting_ymd` DATETIME,
    `service_cd` VARCHAR(6) BINARY,
    `service_sub_cd` VARCHAR(64) BINARY,
    `transaction_cd` VARCHAR(14) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** _POINT_GIFT_TICKET
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_gift_ticket` (
    `br_point_gift_id` VARCHAR(10) BINARY COMMENT '1;ＢＲポイント交換コード;YYYYNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `ticket_status` TINYINT DEFAULT 1 COMMENT '3;使用状態;0:未使用 1:使用済み',
    `ticket_charge` INT DEFAULT 0 COMMENT '4;金券額;',
    `issue_dtm` DATETIME COMMENT '5;発行日時;',
    `lost_ymd` DATETIME COMMENT '6;失効年月日;( 発行日時 + 3ヶ月 + 翌月の１日）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `br_point_gift_ticket` COMMENT 'ＢＲポイントギフト交換宿泊割引券;';

--   *** ------------------------------------
--  *** _POINT_PLUS_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_plus_hotel` (
    `point_plus_id` BIGINT COMMENT '1;ポイント加算情報ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `target_status` TINYINT COMMENT '3;対象状態;0:加算対象外(削除扱い) 1:加算対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `br_point_plus_hotel` COMMENT 'BRポイント加算対象施設;';

--   *** ------------------------------------
--  *** _POINT_PLUS_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_plus_info` (
    `point_plus_id` BIGINT COMMENT '1;ポイント加算情報ID;',
    `point_plus_nm` VARCHAR(96) BINARY COMMENT '2;ポイント加算情報名称;',
    `description` VARCHAR(4000) BINARY COMMENT '3;ポイント加算情報説明;',
    `point_plus_user_nm` VARCHAR(96) BINARY COMMENT '4;ポイント加算情報ユーザー向け名称;',
    `description_to_hotel` VARCHAR(4000) BINARY COMMENT '5;ポイント加算情報施設向け説明;',
    `target_rsv_s_ymd` DATETIME COMMENT '6;対象予約期間開始日;',
    `target_rsv_e_ymd` DATETIME COMMENT '7;対象予約期間終了日;',
    `target_stay_s_ymd` DATETIME COMMENT '8;対象宿泊期間開始日;',
    `target_stay_e_ymd` DATETIME COMMENT '9;対象宿泊期間終了日;',
    `plus_point_rate` DECIMAL(5, 2) COMMENT '10;加算BRポイント率;',
    `plus_target_type` TINYINT COMMENT '11;加算対象;1:全施設 2:特定施設 3:特定プラン',
    `display_status` TINYINT COMMENT '12;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `pr_message` VARCHAR(4000) BINARY,
    `target_stay_wday` SMALLINT,
    `expire_flag` TINYINT COMMENT '19;失効処理フラグ;0:設定なし 1: 設定あり',
    `expire_ymd` DATETIME COMMENT '20;有効期限;'
);

ALTER TABLE
    `br_point_plus_info` COMMENT 'BRポイント加算情報;施設様設定の付与ポイント率に対しBR側で加算するポイント率の履歴情報';

--   *** ------------------------------------
--  *** _POINT_PLUS_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_plus_plan` (
    `point_plus_id` BIGINT COMMENT '1;ポイント加算情報ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `target_status` TINYINT COMMENT '3;対象状態;0:加算対象外(削除扱い) 1:加算対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `br_point_plus_plan` COMMENT 'BRポイント加算対象プラン;';

--   *** ------------------------------------
--  *** _POINT_SERVICE
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_service` (
    `service_cd` VARCHAR(6) BINARY COMMENT '1;サービスコード;YYYYNN',
    `service_nm` VARCHAR(50) BINARY COMMENT '2;サービス名称;',
    `service_start_ymd` VARCHAR(50) BINARY COMMENT '3;サービス開始年月日;',
    `service_end_ymd` VARCHAR(50) BINARY COMMENT '4;サービス終了年月日;終了期日が無い場合は、ヌル地を登録する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `br_point_service` COMMENT 'BRポイントサービス一覧;';

--   *** ------------------------------------
--  *** _POINT_SHORT_TERM
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_short_term` (
    `short_term_id` VARCHAR(8) BINARY COMMENT '1;短期ポイントID;YYYYMMNN （ ポイント台帳のサービスサブコードとしても使用する ）',
    `issue_dtm` DATETIME COMMENT '2;発行日;',
    `lost_dtm` DATETIME COMMENT '3;失効日;消費ポイントの失効日は、失効処理時に対応する消費ポイントに対して更新する。',
    `issue_point` BIGINT COMMENT '4;発行ポイント;',
    `note` VARCHAR(128) BINARY COMMENT '5;内容;失効時の文言は、先頭に「【失効】」の文字が追加される。',
    `member_type` TINYINT COMMENT '6;対象会員区分;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `br_point_short_term` COMMENT '短期ポイント設定;短期ポイントのService_cdは「201203」です。';

--   *** ------------------------------------
--  *** _POINT_SHORT_TERM_COND
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_short_term_cond` (
    `member_type` TINYINT COMMENT '1;対象会員区分;',
    `note` VARCHAR(128) BINARY COMMENT '2;送信対象名称;',
    `member_sql` VARCHAR(2737) BINARY COMMENT '3;送信対象者のSQL;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `br_point_short_term_cond` COMMENT '短期ポイント対象区分;';

--   *** ------------------------------------
--  *** _POINT_SPECIAL_MEMBER
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_special_member` (`member_cd` VARCHAR(20) BINARY);

--   *** ------------------------------------
--  *** _POINT_STAY_COUNT
--   *** ------------------------------------
-- 
CREATE TABLE `br_point_stay_count` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `stay_count` INT COMMENT '2;宿泊数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `br_point_stay_count` COMMENT 'BRポイント宿泊カウント;';

--   *** ------------------------------------
--  *** _SS_IMPORT
--   *** ------------------------------------
-- 
CREATE TABLE `br_ss_import` (
    `br_ss_import_id` DECIMAL(22, 0),
    `br_ss_import_nm` VARCHAR(60) BINARY,
    `member_cd` VARCHAR(128) BINARY,
    `send_1st_mail_dtm` DATETIME,
    `confirm_page_url` VARCHAR(255) BINARY,
    `ss_account_id` VARCHAR(240) BINARY,
    `ss_passwd` VARCHAR(32) BINARY,
    `confirm_end_dtm` DATETIME,
    `confirm_dtm` DATETIME,
    `import_dtm` DATETIME,
    `ss_open_dtm` DATETIME,
    `send_present_dtm` DATETIME,
    `family_nm` VARCHAR(30) BINARY,
    `given_nm` VARCHAR(60) BINARY,
    `family_kn` VARCHAR(60) BINARY,
    `given_kn` VARCHAR(60) BINARY,
    `gender` VARCHAR(1) BINARY,
    `birth_ymd` DATETIME,
    `contact_type` TINYINT,
    `postal_cd` VARCHAR(12) BINARY,
    `pref_id` TINYINT,
    `address1` VARCHAR(300) BINARY,
    `address2` VARCHAR(300) BINARY,
    `tel` VARCHAR(17) BINARY,
    `email` VARCHAR(200) BINARY,
    `email_row` VARCHAR(200) BINARY,
    `member_group` VARCHAR(150) BINARY,
    `optional_tel` VARCHAR(15) BINARY,
    `md_modify_ts` VARCHAR(50) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME,
    `note` VARCHAR(300) BINARY,
    `address3` VARCHAR(300) BINARY
);

--   *** ------------------------------------
--  *** RD_PAYMENT_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `card_payment_credit` (
    `card_payment_id` VARCHAR(14) BINARY COMMENT '1;カード売上ID;',
    `payment_system` TINYINT COMMENT '2;決済システム;1:orico',
    `demand_dtm` DATETIME COMMENT '3;売上日時;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '4;予約コード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `demand_charge` INT COMMENT '6;売上料金;',
    `card_company_id` TINYINT COMMENT '7;カード会社;1: orico 2: uc 3:ダイナース 4:JCB',
    `card_id` TINYINT COMMENT '8;カードID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `card_payment_credit` COMMENT 'クレジットカード決済データ（宿泊予約：一般）;';

--   *** ------------------------------------
--  *** RD_PAYMENT_GBY
--   *** ------------------------------------
-- 
CREATE TABLE `card_payment_gby` (
    `card_payment_id` VARCHAR(14) BINARY COMMENT '1;カード売上ID;',
    `payment_system` TINYINT COMMENT '2;決済システム;1:orico',
    `demand_dtm` DATETIME COMMENT '3;売上日時;',
    `order_id` VARCHAR(12) BINARY COMMENT '4;共同購入注文ID;',
    `demand_charge` INT COMMENT '5;売上料金;',
    `card_company_id` TINYINT COMMENT '6;カード会社;1: orico 2: uc 3:ダイナース 4:JCB',
    `card_id` TINYINT COMMENT '7;カードID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `card_payment_gby` COMMENT 'クレジットカード決済データ（共同販売：ベストク）;';

--   *** ------------------------------------
--  *** RD_PAYMENT_POWER
--   *** ------------------------------------
-- 
CREATE TABLE `card_payment_power` (
    `card_payment_id` VARCHAR(14) BINARY COMMENT '1;カード売上ID;',
    `payment_system` TINYINT COMMENT '2;決済システム;1:orico',
    `demand_dtm` DATETIME COMMENT '3;売上日時;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '4;予約コード;YYYYMMNNNNNNNN',
    `demand_charge` INT COMMENT '5;売上料金;',
    `card_company_id` TINYINT COMMENT '6;カード会社;1: orico 2: uc 3:ダイナース 4:JCB',
    `card_id` TINYINT COMMENT '7;カードID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `card_payment_power` COMMENT 'クレジットカード決済データ（宿泊予約：ハイランク）;';

--   *** ------------------------------------
--  *** ARGE
--   *** ------------------------------------
-- 
CREATE TABLE `charge` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `capacity` TINYINT COMMENT '5;人数;',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `usual_charge` INT COMMENT '7;大人一人通常料金;',
    `usual_charge_revise` TINYINT COMMENT '8;大人一人通常料金補正値;',
    `sales_charge` INT COMMENT '9;大人一人販売料金;',
    `sales_charge_revise` TINYINT COMMENT '10;大人一人販売料金補正地;',
    `accept_status` TINYINT COMMENT '11;予約受付状態;0:停止中 1:受付中',
    `accept_s_dtm` DATETIME COMMENT '12;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '13;終了日時;',
    `low_price_status` TINYINT COMMENT '14;最安値宣言ステータス;0:宣言しない 1:宣言する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `charge` COMMENT '料金;';

--   *** ------------------------------------
--  *** ARGE_CONDITION
--   *** ------------------------------------
-- 
CREATE TABLE `charge_condition` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;',
    `capacity` TINYINT COMMENT '4;利用人数;',
    `login_condition` TINYINT COMMENT '5;ログイン状態;非会員：-1 非会員・会員：0 会員：1',
    `sales_charge_min` BIGINT COMMENT '6;販売料金（最少）;大人一人最低料金（販売料金 + 税 - 割引料金）',
    `sales_charge_max` BIGINT COMMENT '7;販売料金（最大）;大人一人最低料金（販売料金 + 税 - 割引料金）',
    `rate` SMALLINT COMMENT '8;割引率;最大の割引利率',
    `sales_ym` DATETIME COMMENT '9;販売月（開始年月）;販売月 の 最初の月',
    `sales_term` VARCHAR(13) BINARY COMMENT '10;販売月;販売あり:2  満室:1 販売なし:0  の値を[販売月（開始年月）]から13か月分結合する （ 1120000000000 ）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `vacant_min` SMALLINT,
    `vacant_max` SMALLINT,
    `sales_excluding_tax_charge_min` BIGINT,
    `sales_excluding_tax_charge_max` BIGINT,
    `date_s_ymd` DATETIME,
    `date_e_ymd` DATETIME,
    `accept_s_ymd` DATETIME
);

ALTER TABLE
    `charge_condition` COMMENT '料金登録状況;';

--   *** ------------------------------------
--  *** ARGE_EARLY
--   *** ------------------------------------
-- 
CREATE TABLE `charge_early` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `capacity` TINYINT COMMENT '5;人数;',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `accept_e_ymd` DATETIME COMMENT '7;終了日;',
    `unit` TINYINT COMMENT '8;早割引単位;0:率 1;金額 2:差額',
    `discount_rate` SMALLINT COMMENT '9;早割引率;',
    `discount_charge` INT COMMENT '10;早割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `charge_early` COMMENT '早割料金情報;';

--   *** ------------------------------------
--  *** ARGE_INITIAL
--   *** ------------------------------------
-- 
CREATE TABLE `charge_initial` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `capacity` TINYINT COMMENT '5;人数;',
    `usual_charge_sun` INT COMMENT '6;日曜大人一人通常料金;',
    `usual_charge_mon` INT COMMENT '7;月曜大人一人通常料金;',
    `usual_charge_tue` INT COMMENT '8;火曜大人一人標準料金;',
    `usual_charge_wed` INT COMMENT '9;水曜大人一人通常料金;',
    `usual_charge_thu` INT COMMENT '10;木曜大人一人通常料金;',
    `usual_charge_fri` INT COMMENT '11;金曜大人一人通常料金;',
    `usual_charge_sat` INT COMMENT '12;土曜大人一人通常料金;',
    `usual_charge_hol` INT COMMENT '13;祝日大人一人通常料金;',
    `usual_charge_bfo` INT COMMENT '14;休前日大人一人通常料金;土曜は必ず土曜日です。',
    `usual_charge_revise_sun` TINYINT COMMENT '15;日曜大人一人通常料金補正値;',
    `usual_charge_revise_mon` TINYINT COMMENT '16;月曜大人一人通常料金補正値;',
    `usual_charge_revise_tue` TINYINT COMMENT '17;火曜大人一人通常料金補正値;',
    `usual_charge_revise_wed` TINYINT COMMENT '18;水曜大人一人通常料金補正値;',
    `usual_charge_revise_thu` TINYINT COMMENT '19;木曜大人一人通常料金補正値;',
    `usual_charge_revise_fri` TINYINT COMMENT '20;金曜大人一人通常料金補正値;',
    `usual_charge_revise_sat` TINYINT COMMENT '21;土曜大人一人通常料金補正値;',
    `usual_charge_revise_hol` TINYINT COMMENT '22;祝日大人一人通常料金補正値;',
    `usual_charge_revise_bfo` TINYINT COMMENT '23;休前日大人一人通常料金補正値;',
    `sales_charge_sun` INT COMMENT '24;日曜大人一人販売料金;',
    `sales_charge_mon` INT COMMENT '25;月曜大人一人販売料金;',
    `sales_charge_tue` INT COMMENT '26;火曜大人一人販売料金;',
    `sales_charge_wed` INT COMMENT '27;水曜大人一人販売料金;',
    `sales_charge_thu` INT COMMENT '28;木曜大人一人販売料金;',
    `sales_charge_fri` INT COMMENT '29;金曜大人一人販売料金;',
    `sales_charge_sat` INT COMMENT '30;土曜大人一人販売料金;',
    `sales_charge_hol` INT COMMENT '31;祝日大人一人販売料金;',
    `sales_charge_bfo` INT COMMENT '32;休前日大人一人販売料金;土曜は必ず土曜日です。',
    `sales_charge_revise_sun` TINYINT COMMENT '33;日曜大人一人販売料金補正値;',
    `sales_charge_revise_mon` TINYINT COMMENT '34;月曜大人一人販売料金補正値;',
    `sales_charge_revise_tue` TINYINT COMMENT '35;火曜大人一人販売料金補正値;',
    `sales_charge_revise_wed` TINYINT COMMENT '36;水曜大人一人販売料金補正値;',
    `sales_charge_revise_thu` TINYINT COMMENT '37;木曜大人一人販売料金補正値;',
    `sales_charge_revise_fri` TINYINT COMMENT '38;金曜大人一人販売料金補正値;',
    `sales_charge_revise_sat` TINYINT COMMENT '39;土曜大人一人販売料金補正値;',
    `sales_charge_revise_hol` TINYINT COMMENT '40;祝日大人一人販売料金補正値;',
    `sales_charge_revise_bfo` TINYINT COMMENT '41;休前日大人一人販売料金補正値;',
    `low_price_status` TINYINT COMMENT '42;最安値宣言ステータス;0:宣言しない 1:宣言する',
    `accept_s_day` TINYINT COMMENT '43;販売開始日;null:すぐ販売',
    `accept_s_hour` VARCHAR(5) BINARY COMMENT '44;販売開始時間;',
    `accept_e_day` TINYINT COMMENT '45;販売終了日;null:手仕舞いなし',
    `accept_e_hour` VARCHAR(5) BINARY COMMENT '46;販売終了時間;',
    `early_day` SMALLINT COMMENT '47;早期割引日;null:早割りなし',
    `unit` TINYINT COMMENT '48;早割引単位;0:率 1;金額 2:差額',
    `discount_rate` SMALLINT COMMENT '49;早割引率;',
    `discount_charge` INT COMMENT '50;早割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '51;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '52;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '53;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '54;更新日時;'
);

ALTER TABLE
    `charge_initial` COMMENT '料金基本情報;';

--   *** ------------------------------------
--  *** ARGE_REMIND
--   *** ------------------------------------
-- 
CREATE TABLE `charge_remind` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `capacity` SMALLINT COMMENT '5;人数;',
    `usual_charge_sun` INT COMMENT '6;日曜通常料金;',
    `usual_charge_mon` INT COMMENT '7;月曜通常料金;',
    `usual_charge_tue` INT COMMENT '8;火曜通常料金;',
    `usual_charge_wed` INT COMMENT '9;水曜通常料金;',
    `usual_charge_thu` INT COMMENT '10;木曜通常料金;',
    `usual_charge_fri` INT COMMENT '11;金曜通常料金;',
    `usual_charge_sat` INT COMMENT '12;土曜通常料金;',
    `usual_charge_hol` INT COMMENT '13;祝日通常料金;',
    `usual_charge_bfo` INT COMMENT '14;休前日通常料金;土曜は必ず土曜日です。',
    `sales_charge_sun` INT COMMENT '15;日曜販売料金;',
    `sales_charge_mon` INT COMMENT '16;月曜販売料金;',
    `sales_charge_tue` INT COMMENT '17;火曜販売料金;',
    `sales_charge_wed` INT COMMENT '18;水曜販売料金;',
    `sales_charge_thu` INT COMMENT '19;木曜販売料金;',
    `sales_charge_fri` INT COMMENT '20;金曜販売料金;',
    `sales_charge_sat` INT COMMENT '21;土曜販売料金;',
    `sales_charge_hol` INT COMMENT '22;祝日販売料金;',
    `sales_charge_bfo` INT COMMENT '23;休前日販売料金;土曜は必ず土曜日です。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '24;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '25;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '26;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '27;更新日時;'
);

ALTER TABLE
    `charge_remind` COMMENT '料金入力補助;';

--   *** ------------------------------------
--  *** ARGE_TODAY
--   *** ------------------------------------
-- 
CREATE TABLE `charge_today` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `capacity` TINYINT COMMENT '5;人数;',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `timetable` DATETIME COMMENT '7;当日割引設定時間;',
    `unit` TINYINT COMMENT '8;当日割引単位;0:率 1;金額 2:差額',
    `discount_rate` SMALLINT COMMENT '9;当日割引率;',
    `discount_charge` INT COMMENT '10;当日割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `charge_today` COMMENT '当日料金情報;';

--   *** ------------------------------------
--  *** ECKSHEET_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_book` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '3;施設名称;',
    `section_nm` VARCHAR(96) BINARY COMMENT '4;部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `fax` VARCHAR(15) BINARY COMMENT '6;ファックス番号;ハイフン含む',
    `customer_id` BIGINT COMMENT '7;請求支払先ID;連番、シーケンスは使用しない',
    `book_path` VARCHAR(128) BINARY COMMENT '8;原稿ファイルパス;',
    `book_page_count` SMALLINT COMMENT '9;原稿ページ数;',
    `checksheet_condition` VARCHAR(16) BINARY COMMENT '10;作業状況;ヌル値: 未処理 nodata:データなし create:原稿作成済み request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '11;原稿作成日時;',
    `send_request_dtm` DATETIME COMMENT '12;送信依頼日時分秒;',
    `send_accept_dtm` DATETIME COMMENT '13;送信受付日時分秒;',
    `send_result_dtm` DATETIME COMMENT '14;送信処理完了日時分秒;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `checksheet_book` COMMENT '送客データ（台帳）;';

--   *** ------------------------------------
--  *** ECKSHEET_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_credit` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `checksheet_ym` DATETIME COMMENT '3;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `card_charge_sales` INT COMMENT '5;カード決済金額宿泊（施設向け）;税込',
    `card_charge_cancel` INT COMMENT '6;カード決済金額キャンセル（施設向け）;税込',
    `card_rate` TINYINT COMMENT '7;カード決済手数料率（施設向け）;現在 2%で固定です。',
    `card_fee` INT COMMENT '8;カード決済手数料（施設向け）;税別 切捨て',
    `card_rate_real` DECIMAL(5, 2) COMMENT '9;カード決済手数料率（実態）;1.05 固定',
    `card_fee_real` INT COMMENT '10;カード決済手数料（実態）;税別 切捨て',
    `authori_count` SMALLINT COMMENT '11;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` INT COMMENT '12;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `checksheet_credit` COMMENT '宿泊別送客データ（カード決済）;';

--   *** ------------------------------------
--  *** ECKSHEET_CUSTOMER
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_customer` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `customer_id` BIGINT COMMENT '2;請求支払先ID;連番、シーケンスは使用しない',
    `customer_nm` VARCHAR(150) BINARY COMMENT '3;請求支払先名称;',
    `section_nm` VARCHAR(96) BINARY COMMENT '4;部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '7;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '8;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '9;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '10;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '11;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `bill_bank_nm` VARCHAR(150) BINARY COMMENT '12;請求銀行; 支店名称も含む',
    `bill_bank_account_no` VARCHAR(20) BINARY COMMENT '13;請求口座番号;',
    `payment_bank_cd` VARCHAR(4) BINARY COMMENT '14;支払銀行コード;数字4文字',
    `payment_bank_branch_cd` VARCHAR(3) BINARY COMMENT '15;支払支店コード;数字3文字',
    `payment_bank_account_type` TINYINT COMMENT '16;支払口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `payment_bank_account_no` VARCHAR(7) BINARY COMMENT '17;支払口座番号;数字7文字',
    `payment_bank_account_kn` VARCHAR(90) BINARY COMMENT '18;支払口座名義（カナ）;半角カタカナ15文字',
    `bill_required` TINYINT COMMENT '19;当月請求必須月;0 : 請求しない 1 : 請求する',
    `payment_required` TINYINT COMMENT '20;当月支払必須月;0 : 支払しない 1 : 支払する',
    `bill_charge_min` INT COMMENT '21;請求最低金額;デフォルト: 10000',
    `payment_charge_min` INT COMMENT '22;支払最低金額;デフォルト: 1000',
    `entry_cd` VARCHAR(64) BINARY COMMENT '23;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '24;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '25;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '26;更新日時;'
);

ALTER TABLE
    `checksheet_customer` COMMENT '請求支払先（送客）;';

--   *** ------------------------------------
--  *** ECKSHEET_CUSTOMER_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_customer_9xg` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `customer_id` BIGINT COMMENT '2;精算先ID;連番、シーケンスは使用しない',
    `customer_nm` VARCHAR(150) BINARY COMMENT '3;精算先名称;',
    `section_nm` VARCHAR(76) BINARY COMMENT '4;部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '7;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '8;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '9;	電話番号;',
    `fax` VARCHAR(15) BINARY COMMENT '10;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '11;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `bill_bank_nm` VARCHAR(150) BINARY COMMENT '12;請求銀行; 支店名称も含む',
    `bill_bank_account_no` VARCHAR(20) BINARY COMMENT '13;請求口座番号;',
    `factoring_bank_cd` VARCHAR(4) BINARY COMMENT '14;引落銀行コード;',
    `factoring_bank_branch_cd` VARCHAR(3) BINARY COMMENT '15;引落支店コード;',
    `factoring_bank_account_type` TINYINT COMMENT '16;引落口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `factoring_bank_account_no` VARCHAR(7) BINARY COMMENT '17;引落口座番号;数字7文字',
    `factoring_bank_account_kn` VARCHAR(90) BINARY COMMENT '18;引落口座名義（カナ）;半角カタカナ30文字',
    `payment_bank_cd` VARCHAR(4) BINARY COMMENT '19;支払銀行コード;数字4文字',
    `payment_bank_branch_cd` VARCHAR(3) BINARY COMMENT '20;支払支店コード;数字3文字',
    `payment_bank_account_type` TINYINT COMMENT '21;支払口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `payment_bank_account_no` VARCHAR(7) BINARY COMMENT '22;支払口座番号;数字7文字',
    `payment_bank_account_kn` VARCHAR(90) BINARY COMMENT '23;支払口座名義（カナ）;半角カタカナ30文字',
    `bill_required` TINYINT COMMENT '24;当月請求必須月;0 : 請求しない 1 : 請求する',
    `payment_required` TINYINT COMMENT '25;当月支払必須月;0 : 支払しない 1 : 支払する',
    `bill_charge_min` INT COMMENT '26;請求最低金額;デフォルト: 10000',
    `payment_charge_min` INT COMMENT '27;支払最低金額;デフォルト: 1000',
    `entry_cd` VARCHAR(64) BINARY COMMENT '28;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '29;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '30;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '31;更新日時;'
);

ALTER TABLE
    `checksheet_customer_9xg` COMMENT '精算先（送客）_テスト用;精算先（送客）本番テスト用';

--   *** ------------------------------------
--  *** ECKSHEET_FEE
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_fee` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `checksheet_ym` DATETIME COMMENT '3;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '5;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '6;プランコード;',
    `payment_way` TINYINT COMMENT '7;決済方法;1:事前カード決済 2:現地決済',
    `bill_type` TINYINT COMMENT '8;請求対象タイプ;0:宿泊 1:キャンセル',
    `bill_charge` INT COMMENT '9;請求対象金額;税サ込',
    `bill_charge_tax` INT COMMENT '10;請求対象消費税;',
    `system_rate` TINYINT COMMENT '11;システム利用料率;',
    `system_fee` INT COMMENT '12;システム利用料;税別 小数点以下切捨て',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '13;施設名称;',
    `room_nm` VARCHAR(120) BINARY COMMENT '14;部屋名称;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '15;プラン名称;',
    `guest_nm` VARCHAR(75) BINARY COMMENT '16;宿泊代表者氏名;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '17;宿泊代表者電話番号;',
    `check_in_ymd` DATETIME COMMENT '18;チェックイン日;',
    `stay` SMALLINT COMMENT '19;泊数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;',
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `checksheet_fee` COMMENT '宿泊別送客データ（システム利用料）;';

--   *** ------------------------------------
--  *** ECKSHEET_FIX
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_fix` (
    `checksheet_ym` DATETIME COMMENT '1;処理年月;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `fix_status` TINYINT COMMENT '3;確定テータス;0:未確定 1:確定',
    `fix_dtm` DATETIME COMMENT '4;確定日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `fixed_dtm` DATETIME COMMENT '5;検収確定日;請求書発行処理開始日（請求書発行処理後、未確定(0)へ変更されたことを特定する fix_dtm >= fixed_dtm）'
);

ALTER TABLE
    `checksheet_fix` COMMENT '送客リスト確定テーブル;';

--   *** ------------------------------------
--  *** ECKSHEET_FIX_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_fix_9xg` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `fix_status` TINYINT COMMENT '3;確定テータス;0:未確定 1:確定',
    `fix_dtm` DATETIME COMMENT '4;確定日時;検収確定日時',
    `fixed_dtm` DATETIME COMMENT '5;検収確定日;請求書発行処理開始日（請求書発行処理後、未確定(0)へ変更されたことを特定する fix_dtm >= fixed_dtm）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `checksheet_fix_9xg` COMMENT '送客リスト検収テーブル_テスト用;送客リスト検収テーブル本番テスト用';

--   *** ------------------------------------
--  *** ECKSHEET_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_grants` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `welfare_grants_id` BIGINT COMMENT '3;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '4;福利厚生補助金履歴ID;',
    `checksheet_ym` DATETIME COMMENT '5;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `bill_type` TINYINT COMMENT '7;請求対象タイプ;0:宿泊 1:キャンセル',
    `use_grants` BIGINT DEFAULT 0 COMMENT '8;補助金額;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `checksheet_grants` COMMENT '宿泊別送客データ（補助金）;';

--   *** ------------------------------------
--  *** ECKSHEET_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_grants_9xg` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `welfare_grants_id` BIGINT COMMENT '3;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '4;福利厚生補助金履歴ID;',
    `checksheet_ym` DATETIME COMMENT '5;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `bill_type` TINYINT COMMENT '7;請求対象タイプ;0:宿泊 1:キャンセル',
    `use_grants` BIGINT DEFAULT 0 COMMENT '8;補助金額;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `checksheet_grants_9xg` COMMENT '宿泊別送客データ（補助金）_テスト;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_credit` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `card_charge_sales` BIGINT COMMENT '3;カード決済金額宿泊（施設向け）;税込',
    `card_charge_cancel` BIGINT COMMENT '4;カード決済金額キャンセル（施設向け）;税込',
    `card_count` INT COMMENT '5;カード決済回数;',
    `card_fee` BIGINT COMMENT '6;カード決済手数料（施設向け）;税別',
    `card_fee_tax` BIGINT COMMENT '7;カード決済手数料消費税（施設向け）;',
    `card_fee_real` BIGINT COMMENT '8;カード決済手数料（実態）;税別',
    `card_fee_tax_real` BIGINT COMMENT '9;カード決済手数料消費税（実態）;',
    `card_work_fee` INT COMMENT '10;カード事務手数料;税別',
    `card_work_fee_tax` SMALLINT COMMENT '11;カード事務手数料消費税;集計された値に対して消費税率をかけた値',
    `authori_count` INT COMMENT '12;カードオーソリ回数;成功したオーソリの回数',
    `authori_fee` BIGINT COMMENT '13;カードオーソリ手数料;税別 カードオーソリ回数 * 13円',
    `authori_fee_tax` BIGINT COMMENT '14;カードオーソリ手数料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `checksheet_hotel_credit` COMMENT '施設別送客データ（カード決済）;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_FEE
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_fee` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `sales_count` SMALLINT COMMENT '3;宿泊室数;',
    `cancel_count` SMALLINT COMMENT '4;キャンセル室数;',
    `bill_charge` BIGINT COMMENT '5;宿泊料金;税サ込',
    `bill_charge_tax` BIGINT COMMENT '6;料金消費税;',
    `system_rate` TINYINT COMMENT '7;システム利用料率;最大値',
    `system_fee` BIGINT COMMENT '8;システム利用料;税別 小数点以下切捨て',
    `system_fee_tax` BIGINT COMMENT '9;システム利用料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `checksheet_hotel_fee` COMMENT '施設送客データ（システム利用料）;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_FEE_BASE
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_fee_base` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `system_rate` TINYINT COMMENT '3;システム利用料率;5% 固定',
    `system_fee` BIGINT COMMENT '4;システム利用料;税別 小数点以下切捨て',
    `system_fee_tax` BIGINT COMMENT '5;システム利用料消費税;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `checksheet_hotel_fee_base` COMMENT '施設別送客データ（システム利用料）基準;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_FEE_BASE2
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_fee_base2` (
    `checksheet_ym` DATETIME,
    `hotel_cd` VARCHAR(10) BINARY,
    `system_rate` TINYINT,
    `system_fee` BIGINT,
    `system_fee_tax` BIGINT,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_grants` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_grants` BIGINT DEFAULT 0 COMMENT '3;補助金額;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `use_coupon` BIGINT
);

ALTER TABLE
    `checksheet_hotel_grants` COMMENT '施設別送客データ（補助金）;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_grants_9xg` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_grants` BIGINT DEFAULT 0 COMMENT '3;補助金額;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `use_coupon` BIGINT
);

ALTER TABLE
    `checksheet_hotel_grants_9xg` COMMENT '施設別送客データ（補助金）_テスト;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_rsv` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `use_br_point_charge` INT COMMENT '3;消費ＢＲポイント割引料金;ＢＲポイントの金券の割引料金',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `get_br_point_charge` INT,
    `get_br_point_charge_hotel` INT
);

ALTER TABLE
    `checksheet_hotel_rsv` COMMENT '施設別送客データ（リザーブ）;';

--   *** ------------------------------------
--  *** ECKSHEET_HOTEL_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_hotel_yahoo` (
    `checksheet_ym` DATETIME COMMENT '1;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `get_yahoo_point` BIGINT COMMENT '3;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point` BIGINT COMMENT '4;消費ヤフーポイント;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `get_yahoo_point_hotel` BIGINT
);

ALTER TABLE
    `checksheet_hotel_yahoo` COMMENT '送客施設データ（ヤフー）;';

--   *** ------------------------------------
--  *** ECKSHEET_ORDER
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_order` (
    `order_no` INT COMMENT '1;表示順位;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '3;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '4;更新日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;'
);

ALTER TABLE
    `checksheet_order` COMMENT '送客リスト送付優先順序;';

--   *** ------------------------------------
--  *** ECKSHEET_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_rsv` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `checksheet_ym` DATETIME COMMENT '3;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `use_br_point_charge` BIGINT DEFAULT 0 COMMENT '5;消費ＢＲポイント割引料金;ＢＲポイントの金券の割引料金',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `get_br_point_rate` SMALLINT,
    `get_br_point_rate_our` SMALLINT,
    `get_br_point_charge` INT,
    `get_br_point_charge_hotel` INT
);

ALTER TABLE
    `checksheet_rsv` COMMENT '宿泊別送客データ（リザーブ）;';

--   *** ------------------------------------
--  *** ECKSHEET_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `checksheet_yahoo` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `checksheet_ym` DATETIME COMMENT '3;送客処理年月;送客データ作成実行年月',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `get_yahoo_point` INT COMMENT '5;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point` INT COMMENT '6;消費ヤフーポイント;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `get_yahoo_point_hotel` INT,
    `get_yahoo_point_rate` DECIMAL(5, 2),
    `get_yahoo_point_rate_our` DECIMAL(5, 2)
);

ALTER TABLE
    `checksheet_yahoo` COMMENT '送客データ（ヤフー）;';

--   *** ------------------------------------
--  *** NFIRM_HOTEL_PERSON
--   *** ------------------------------------
-- 
CREATE TABLE `confirm_hotel_person` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `confirm_dtm` DATETIME COMMENT '2;確認日時;',
    `hotel_person_email_check` TINYINT COMMENT '3;施設担当者メール確認;0:正常 1:確認必要',
    `customer_email_check` TINYINT COMMENT '4;精算先担当者メール確認;0:正常 1:確認必要',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `confirm_hotel_person` COMMENT '担当者情報更新確認;施設担当者・精算担当者の情報を定期的に確認する。';

--   *** ------------------------------------
--  *** NTACT_SENDBOX
--   *** ------------------------------------
-- 
CREATE TABLE `contact_sendbox` (
    `sendbox_cd` VARCHAR(12) BINARY COMMENT '1;メール送信コード;YYYYMMNNNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '3;予約コード;YYYYMMNNNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '4;会員コード;ベストリザーブ会員は20バイト',
    `auth_type` VARCHAR(12) BINARY COMMENT '5;認証タイプ;free:非会員認証 bestreserve:会員認証 partner:提携先会員認証',
    `subject` VARCHAR(384) BINARY COMMENT '6;件名;メールマガジンのタイトル',
    `person_nm` VARCHAR(96) BINARY COMMENT '7;担当者名称;',
    `person_email` VARCHAR(200) BINARY COMMENT '8;担当者EMAIL;',
    `member_nm` VARCHAR(78) BINARY COMMENT '9;宛先氏名;',
    `contents` LONGTEXT COMMENT '10;本文;',
    `send_dtm` DATETIME COMMENT '11;送信完了日時;',
    `delete_dtm` DATETIME COMMENT '12;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `contact_sendbox` COMMENT '施設から会員へのメール送信;';

--   *** ------------------------------------
--  *** STOMER
--   *** ------------------------------------
-- 
CREATE TABLE `customer` (
    `customer_id` BIGINT COMMENT '1;請求支払先ID;連番、シーケンスは使用しない',
    `customer_nm` VARCHAR(150) BINARY COMMENT '2;請求支払先名称;',
    `section_nm` VARCHAR(75) BINARY COMMENT '3;部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '4;担当者名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '5;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '6;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '7;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '8;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '9;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '10;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `bill_bank_nm` VARCHAR(150) BINARY COMMENT '11;請求銀行; 支店名称も含む',
    `bill_bank_account_no` VARCHAR(20) BINARY COMMENT '12;請求口座番号;',
    `payment_bank_cd` VARCHAR(4) BINARY COMMENT '13;支払銀行コード;数字4文字',
    `payment_bank_branch_cd` VARCHAR(3) BINARY COMMENT '14;支払支店コード;数字3文字',
    `payment_bank_account_type` TINYINT COMMENT '15;支払口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `payment_bank_account_no` VARCHAR(7) BINARY COMMENT '16;支払口座番号;数字7文字',
    `payment_bank_account_kn` VARCHAR(90) BINARY COMMENT '17;支払口座名義（カナ）;半角カタカナ30文字',
    `bill_required_month` VARCHAR(12) BINARY COMMENT '18;請求必須月;1月から１２月分 の１２桁の01の文字列、1が立ってる桁が請求月になります。（例 ４月請求 = 000100000000)',
    `payment_required_month` VARCHAR(12) BINARY COMMENT '19;支払必須月;1月から１２月分 の１２桁の01の文字列、1が立ってる桁が支払月になります。（例 ４月支払 = 000100000000)',
    `bill_charge_min` INT COMMENT '20;請求最低金額;デフォルト: 10000',
    `payment_charge_min` INT COMMENT '21;支払最低金額;デフォルト: 1000',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;',
    `bill_way` TINYINT,
    `factoring_bank_cd` VARCHAR(4) BINARY,
    `factoring_bank_account_type` TINYINT,
    `factoring_bank_account_no` VARCHAR(7) BINARY,
    `factoring_bank_account_kn` VARCHAR(90) BINARY,
    `factoring_bank_branch_cd` VARCHAR(3) BINARY,
    `factoring_cd` VARCHAR(12) BINARY,
    `bill_send` TINYINT,
    `payment_send` TINYINT,
    `factoring_send` TINYINT,
    `fax_recipient_cd` TINYINT,
    `optional_nm` VARCHAR(150) BINARY,
    `optional_section_nm` VARCHAR(76) BINARY,
    `optional_person_nm` VARCHAR(96) BINARY,
    `optional_fax` VARCHAR(15) BINARY,
    `bill_add_month` TINYINT,
    `bill_day` TINYINT,
    `person_post` VARCHAR(90) BINARY
);

ALTER TABLE
    `customer` COMMENT '請求支払先;';

--   *** ------------------------------------
--  *** STOMER_HIKARI
--   *** ------------------------------------
-- 
CREATE TABLE `customer_hikari` (
    `customer_id` BIGINT,
    `customer_nm` VARCHAR(150) BINARY,
    `section_nm` VARCHAR(75) BINARY,
    `person_nm` VARCHAR(96) BINARY,
    `postal_cd` VARCHAR(8) BINARY,
    `pref_id` TINYINT,
    `address` VARCHAR(300) BINARY,
    `tel` VARCHAR(15) BINARY,
    `fax` VARCHAR(15) BINARY,
    `email` VARCHAR(200) BINARY,
    `bill_bank_nm` VARCHAR(150) BINARY,
    `bill_bank_account_no` VARCHAR(20) BINARY,
    `payment_bank_cd` VARCHAR(4) BINARY,
    `payment_bank_branch_cd` VARCHAR(3) BINARY,
    `payment_bank_account_type` TINYINT,
    `payment_bank_account_no` VARCHAR(7) BINARY,
    `payment_bank_account_kn` VARCHAR(90) BINARY,
    `bill_required_month` VARCHAR(12) BINARY,
    `payment_required_month` VARCHAR(12) BINARY,
    `bill_charge_min` INT,
    `payment_charge_min` INT,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** STOMER_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `customer_hotel` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `customer_id` BIGINT COMMENT '2;請求先・支払先ID;連番、シーケンスは使用しない',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `customer_hotel` COMMENT '請求先・支払先関連施設;';

--   *** ------------------------------------
--  *** NY_LIST
--   *** ------------------------------------
-- 
CREATE TABLE `deny_list` (
    `deny_cd` VARCHAR(9) BINARY COMMENT '1;拒否コード;YYYYNNNNN',
    `partner_cd` VARCHAR(10) BINARY COMMENT '2;提携先コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `deny_type` TINYINT COMMENT '4;拒否者;0:施設 1:提携先 2:運用',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `deny_list` COMMENT '提携先・施設拒否関連;提携サイトに表示したくない施設を設定する';

--   *** ------------------------------------
--  *** NY_LIST_RETURN
--   *** ------------------------------------
-- 
CREATE TABLE `deny_list_return` (
    `deny_cd` VARCHAR(9) BINARY COMMENT '1;拒否コード;YYYYNNNNN',
    `partner_cd` VARCHAR(10) BINARY COMMENT '2;提携先コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `deny_type` TINYINT COMMENT '4;拒否者;0:施設 1:提携先 2:運用',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `deny_list_return` COMMENT '提携先・施設拒否復帰一覧;';

--   *** ------------------------------------
--  *** SPOSE_VOUCHER
--   *** ------------------------------------
-- 
CREATE TABLE `dispose_voucher` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `operation_ymd` DATETIME COMMENT '2;処理日付;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '3;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '4;宿泊日;',
    `before_sales_charge` INT COMMENT '5;元販売料金;',
    `before_credit_charge` INT COMMENT '6;元クレジット料金;',
    `before_discount_charge` INT COMMENT '7;元割引料金;',
    `sales_charge` INT COMMENT '8;変更後販売料金;',
    `credit_charge` INT COMMENT '9;変更後クレジット料金;',
    `discount_charge` INT COMMENT '10;変更後割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `dispose_voucher` COMMENT '赤伝票;';

--   *** ------------------------------------
--  *** QUETE_6315
--   *** ------------------------------------
-- 
CREATE TABLE `enquete_6315` (
    `id` TINYINT COMMENT '1;アンケートID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;',
    `entry_dtm` DATETIME COMMENT '3;投稿日;',
    `q01` VARCHAR(10) BINARY COMMENT '4;Q01;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q02` VARCHAR(10) BINARY COMMENT '5;Q02;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q03` VARCHAR(10) BINARY COMMENT '6;Q03;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q04` VARCHAR(10) BINARY COMMENT '7;Q04;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q05` VARCHAR(10) BINARY COMMENT '8;Q05;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q06` VARCHAR(10) BINARY COMMENT '9;Q06;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q07` VARCHAR(10) BINARY COMMENT '10;Q07;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q08` VARCHAR(10) BINARY COMMENT '11;Q08;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q09` VARCHAR(10) BINARY COMMENT '12;Q09;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q10` VARCHAR(10) BINARY COMMENT '13;Q10;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `q11` VARCHAR(10) BINARY COMMENT '14;Q11;「0」、「1」の文字列（0:チェックなし、1:チェック有） 複数回答の場合は、左から結合した文字列',
    `agreement` VARCHAR(1) BINARY COMMENT '15;同意（プライバシーポリシー等）;1:同意する　0:同意しない',
    `tel` VARCHAR(15) BINARY COMMENT '16;電話番号;ハイフン含む',
    `member_kn` VARCHAR(120) BINARY COMMENT '17;氏名（カナ）;全角カナ文字',
    `pref_id` TINYINT COMMENT '18;都道府県ID;',
    `age` TINYINT COMMENT '19;年齢;1:19歳以下　2:20-29歳　3:30-39歳　4:40-49歳　5:50-59歳　6:60歳以上',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;'
);

ALTER TABLE
    `enquete_6315` COMMENT 'ベストリザーブアンケート（タスク6315）;';

--   *** ------------------------------------
--  *** ARK_ACCESS_TOKEN
--   *** ------------------------------------
-- 
CREATE TABLE `epark_access_token` (
    `epark_id` VARCHAR(30) BINARY COMMENT '1;EPARK会員ID;',
    `access_token` VARCHAR(128) BINARY COMMENT '2;アクセストークン;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `epark_access_token` COMMENT 'EPARK連携Access_Token;EPARK連携時のAccess_Tokenを保持する';

--   *** ------------------------------------
--  *** ARK_REFRESH_TOKEN
--   *** ------------------------------------
-- 
CREATE TABLE `epark_refresh_token` (
    `epark_id` VARCHAR(30) BINARY COMMENT '1;EPARK会員ID;',
    `refresh_token` VARCHAR(128) BINARY COMMENT '2;リフレッシュトークン;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `epark_refresh_token` COMMENT 'EPARK連携Refresh_Token;EPARK連携時のRefresh_Tokenを保持する';

--   *** ------------------------------------
--  *** ARK_STATE_TOKEN
--   *** ------------------------------------
-- 
CREATE TABLE `epark_state_token` (
    `state_token_key` VARCHAR(128) BINARY COMMENT '1;STATEトークンキー;uniqueID+セッションIDクッキー',
    `state_token` VARCHAR(256) BINARY COMMENT '2;STATEトークン;uniqueID+30文字のランダム文字列のハッシュ',
    `member_cd` VARCHAR(128) BINARY COMMENT '3;会員コード;ベストリザーブ会員は20バイト',
    `redirect_path` VARCHAR(2048) BINARY COMMENT '4;リダイレクト先URL;ログイン後戻り先URLのパス',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `epark_state_token` COMMENT 'EPARK連携State_Token;EPARK連携時のstate_tokenを保持する';

--   *** ------------------------------------
--  *** TEND_SETTING
--   *** ------------------------------------
-- 
CREATE TABLE `extend_setting` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `after_months` TINYINT COMMENT '2;ターゲット月;',
    `email` VARCHAR(128) BINARY COMMENT '3;電子メールアドレス;',
    `email_type` TINYINT COMMENT '4;電子メールタイプ;0:パソコン用レイアウト 1:携帯端末用レイアウト',
    `email_notify` TINYINT COMMENT '5;電子メール通知可否;0:否通知 1:通知',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `extend_setting` COMMENT '自動登録管理テーブル;';

--   *** ------------------------------------
--  *** TEND_SETTING_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `extend_setting_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `extend_status` TINYINT COMMENT '3;自動延長状態;0:停止中 1受付中:',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `extend_setting_plan` COMMENT 'プランの自動延長設定;自動延長のON/OFF設定（プラン単位）';

--   *** ------------------------------------
--  *** TEND_SETTING_ROOM
--   *** ------------------------------------
-- 
CREATE TABLE `extend_setting_room` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `extend_status` TINYINT COMMENT '3;自動延長状態;0:停止中 1受付中:',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `extend_setting_room` COMMENT '部屋の自動延長設定;自動延長のON/OFF設定';

--   *** ------------------------------------
--  *** TEND_SWITCH
--   *** ------------------------------------
-- 
CREATE TABLE `extend_switch` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `extend_status` TINYINT COMMENT '2;自動延長状態;0:停止中 1受付中:',
    `extend_dtm` DATETIME COMMENT '3;自動延長状態変更日時;',
    `lock_status` TINYINT COMMENT '4;ロックステータス;0:ロック中 1:ロック解除中',
    `lock_dtm` DATETIME COMMENT '5;ロック変更日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `extend_switch` COMMENT '自動登録状態管理テーブル;';

--   *** ------------------------------------
--  *** TEND_SWITCH_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `extend_switch_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `extend_status` TINYINT COMMENT '4;自動延長状態;0:停止中 1受付中:',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `extend_switch_plan` COMMENT 'プラン自動延長状態;';

--   *** ------------------------------------
--  *** TEND_SWITCH_PLAN2
--   *** ------------------------------------
-- 
CREATE TABLE `extend_switch_plan2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `extend_status` TINYINT COMMENT '4;自動延長状態;0:停止中 1受付中:',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `extend_switch_plan2` COMMENT 'プラン自動延長状態;';

--   *** ------------------------------------
--  *** TEND_TASK
--   *** ------------------------------------
-- 
CREATE TABLE `extend_task` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `target_ym` DATETIME COMMENT '2;対象年月;',
    `type` TINYINT COMMENT '3;延長処理タイプ;0:一括登録 1:即時販売開始',
    `action_status` TINYINT COMMENT '4;処理状態;0:未処理 1:処理中 2:処理完了',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `extend_task` COMMENT '手動延長処理管理テーブル;手動延長処理のタスクを管理する';

--   *** ------------------------------------
--  *** CTORING_ZENGIN_REQUEST
--   *** ------------------------------------
-- 
CREATE TABLE `factoring_zengin_request` (
    `factoring_cd` VARCHAR(12) BINARY COMMENT '1;引落顧客コード;',
    `bank_cd` VARCHAR(4) BINARY COMMENT '2;銀行コード;数字4文字',
    `bank_branch_cd` VARCHAR(3) BINARY COMMENT '3;支店コード;数字3文字',
    `bank_account_type` TINYINT COMMENT '4;口座種別;1:普通 2:当座（4:貯蓄 9:その他）',
    `bank_account_no` VARCHAR(7) BINARY COMMENT '5;口座番号;',
    `status` TINYINT COMMENT '6;リクエスト状況;0: 2回目以降の請求の場合、 2:金融機関からの口座情報変更通知（弊社経由）に基づき、口座情報を変更した場合 （レコードがない場合は新規コードの値は「1」で処理する。）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `factoring_zengin_request` COMMENT '引落顧客リクエスト状況;';

--   *** ------------------------------------
--  *** X_PR
--   *** ------------------------------------
-- 
CREATE TABLE `fax_pr` (
    `fax_pr_id` INT COMMENT '1;FAX掲載広告文章ID;アクティブレコードを利用するために付与、実際は１レコード（ID:1）しか存在しません。',
    `title` VARCHAR(45) BINARY COMMENT '2;タイトル;',
    `note` VARCHAR(1200) BINARY COMMENT '3;広告文章;',
    `frame_type` TINYINT COMMENT '4;枠;0:枠なし 1:枠あり',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `fax_pr` COMMENT '予約通知FAX掲載広告文章;予約通知FAXに記載する広告文章を登録します';

--   *** ------------------------------------
--  *** _BASE_TIME
--   *** ------------------------------------
-- 
CREATE TABLE `fd_base_time` (
    `affiliate_id` VARCHAR(10) BINARY COMMENT '1;アフィリエイトID;',
    `cooperation_cd` VARCHAR(4) BINARY COMMENT '2;連携情報種別;',
    `base_tm` DATETIME COMMENT '3;基準時間;',
    `time_range` SMALLINT COMMENT '4;連携時間範囲;',
    `activ_fg` VARCHAR(1) BINARY COMMENT '5;有効フラグ;0:無効 1:有効',
    `upd_id` VARCHAR(10) BINARY COMMENT '6;更新者ID;',
    `upd_dt` DATETIME COMMENT '7;最終更新時刻;',
    `stock_fg` VARCHAR(1) BINARY DEFAULT '0' Comment '8;在庫譲渡範囲フラグ;0:全て 1:特別レート在庫のみ',
    `max_cnt` INT COMMENT '9;最大取得件数;',
    `day_range` SMALLINT COMMENT '10;取得日数;',
    `cooperation_type_cd` TINYINT DEFAULT 0 COMMENT '11;連携対象コード;0:連携なし 1:国内システム在庫連携有り',
    `timeout` SMALLINT DEFAULT 0 COMMENT '12;タイムアウト値;'
);

ALTER TABLE
    `fd_base_time` COMMENT '外部連携基準時間;';

--   *** ------------------------------------
--  *** ATURE
--   *** ------------------------------------
-- 
CREATE TABLE `feature` (
    `feature_id` VARCHAR(8) BINARY COMMENT '1;特集ID;YYYY+数値4桁 （年単位で連番：シーケンスは使用しない）',
    `feature_nm` VARCHAR(100) BINARY COMMENT '2;特集名称;',
    `disp_s_ymd` DATETIME COMMENT '3;掲載開始日時;',
    `disp_e_ymd` DATETIME COMMENT '4;掲載終了日時;',
    `disp_cnt` TINYINT DEFAULT 2 COMMENT '5;一行あたりの表示件数;2列or3列',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `feature` COMMENT '特集;特集ページ毎の情報を保持する';

--   *** ------------------------------------
--  *** ATURE_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `feature_detail` (
    `feature_id` VARCHAR(8) BINARY COMMENT '1;特集ID;YYYY+数値4桁 （年単位で連番：シーケンスは使用しない）',
    `feature_detail_id` VARCHAR(5) BINARY COMMENT '2;特集詳細ID;先頭ゼロ埋めで連番(シーケンスは使用しない)',
    `feature_group_id` VARCHAR(3) BINARY COMMENT '3;特集グループID;特集内に表示する施設・プラン情報の区分けをする',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '5;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '6;プランコード;',
    `order_no` INT COMMENT '7;掲載順序;',
    `disp_status` TINYINT DEFAULT 0 COMMENT '8;掲載状態;0:無効  1:有効',
    `soldout_disp` TINYINT DEFAULT 1 COMMENT '9;完売表示;0:無効  1:有効',
    `premium_disp` TINYINT DEFAULT 0 COMMENT '10;プレミアム表示;0:無効  1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `feature_detail` COMMENT '特集詳細;特集ページに表示する施設、プラン情報を保持する。';

--   *** ------------------------------------
--  *** _AFFILIATE
--   *** ------------------------------------
-- 
CREATE TABLE `fm_affiliate` (
    `affiliate_id` VARCHAR(10) BINARY COMMENT '1;アフィリエイトID;',
    `affiliate_nm` VARCHAR(100) BINARY COMMENT '2;アフィリエイト名;',
    `passwd` VARCHAR(10) BINARY COMMENT '3;パスワード;',
    `status` VARCHAR(2) BINARY COMMENT '4;契約状態;0:不明 1:契約準備中 2:契約中 3:契約終了 4:削除',
    `admission_dt` DATETIME COMMENT '5;入会日;',
    `leave_dt` DATETIME COMMENT '6;退会日;',
    `feeratio_rt` DECIMAL(4, 1) COMMENT '7;アフィリエイト手数料率;',
    `zip1` VARCHAR(3) BINARY COMMENT '8;郵便番号1;',
    `zip2` VARCHAR(4) BINARY COMMENT '9;郵便番号2;',
    `prefecture_cd` VARCHAR(2) BINARY COMMENT '10;都道府県コード;',
    `address` VARCHAR(100) BINARY COMMENT '11;住所;',
    `tel` VARCHAR(15) BINARY COMMENT '12;電話番号;',
    `fax` VARCHAR(15) BINARY COMMENT '13;FAX番号;',
    `division` VARCHAR(60) BINARY COMMENT '14;担当者部署;',
    `title` VARCHAR(60) BINARY COMMENT '15;担当者役職;',
    `contact_nm` VARCHAR(30) BINARY COMMENT '16;担当者名;',
    `email` VARCHAR(100) BINARY COMMENT '17;メールアドレス;',
    `url` VARCHAR(256) BINARY COMMENT '18;URL;',
    `bank_nm` VARCHAR(60) BINARY COMMENT '19;金融機関名;',
    `bank_cd` VARCHAR(10) BINARY COMMENT '20;金融機関コード;',
    `branch_nm` VARCHAR(60) BINARY COMMENT '21;支店名;',
    `branch_cd` VARCHAR(10) BINARY COMMENT '22;支店コード;',
    `accounttype_cd` VARCHAR(1) BINARY COMMENT '23;預金区分;1:普通 2:当座',
    `account_nm` VARCHAR(60) BINARY COMMENT '24;口座名義人;全角カタカナ',
    `account_no` VARCHAR(10) BINARY COMMENT '25;口座番号;',
    `start_dt` DATETIME COMMENT '26;精算期間初め;',
    `end_dt` DATETIME COMMENT '27;精算期間終り;',
    `nta_contact_cd` VARCHAR(5) BINARY COMMENT '28;担当支店コード;',
    `nta_contact_nm` VARCHAR(30) BINARY COMMENT '29;日本旅行担当者名;',
    `site_nm` VARCHAR(100) BINARY COMMENT '30;サイト名;',
    `upd_id` VARCHAR(10) BINARY COMMENT '31;更新者ID;',
    `upd_dt` DATETIME COMMENT '32;最終更新時刻;',
    `site_type` VARCHAR(1) BINARY COMMENT '33;アフィリエイトタイプ;A:Aタイプ(一般) B:Bタイプ(企業利用)',
    `rsv_email` VARCHAR(100) BINARY COMMENT '34;予約通知受付メールアドレス;',
    `limited_pwd` VARCHAR(10) BINARY COMMENT '35;制限ユーザパスワード;',
    `cid_fg` VARCHAR(1) BINARY COMMENT '36;カスタムID利用フラグ;1:利用する 0:利用しない',
    `cid_desc` VARCHAR(30) BINARY COMMENT '37;カスタムID名称;',
    `ad_point` SMALLINT COMMENT '38;入会時獲得ポイント;',
    `stay_point_rt` DECIMAL(3, 1) COMMENT '39;宿泊時獲得ポイント率;',
    `note` VARCHAR(300) BINARY COMMENT '40;連絡事項;',
    `support_email` VARCHAR(100) BINARY COMMENT '41;サポートメールアドレス;',
    `mypage_url` VARCHAR(256) BINARY COMMENT '42;マイページURL;',
    `link_info` VARCHAR(300) BINARY COMMENT '43;リンク情報;',
    `member_type` VARCHAR(1) BINARY COMMENT '44;会員区分;1:会員扱いする 0:会員扱いしない',
    `pointuse_fg` VARCHAR(1) BINARY COMMENT '45;ポイント利用フラグ;1:利用する 0:利用しない',
    `site_nm_mail` VARCHAR(100) BINARY COMMENT '46;サイト名(メール用);',
    `coloruse_fg` VARCHAR(1) BINARY COMMENT '47;色設定フラグ;',
    `sprate_fg` VARCHAR(1) BINARY COMMENT '48;特別レート利用フラグ;',
    `sprate_nm` VARCHAR(40) BINARY COMMENT '49;特別レート利用時企業名;',
    `login_url` VARCHAR(256) BINARY COMMENT '50;ログインURL;',
    `domain_nm` VARCHAR(128) BINARY COMMENT '51;ドメイン名;',
    `mypageshow_fg` VARCHAR(1) BINARY COMMENT '52;旅関連myページ内表示フラグ;',
    `reserve_cid_fg` VARCHAR(1) BINARY COMMENT '53;;',
    `reserve_cid_desc` VARCHAR(30) BINARY COMMENT '54;;',
    `reserve_cid_comment` VARCHAR(800) BINARY COMMENT '55;;',
    `screen_type_cd` VARCHAR(1) BINARY DEFAULT '0' Comment '56;サイト種別;0:PC・携帯共用 1:PC 2:携帯',
    `payment_cd` VARCHAR(1) BINARY DEFAULT '0' Comment '57;支払いコード;0:現地決済 1:後払い',
    `affiliate_kbn` VARCHAR(1) BINARY COMMENT '58;アフィリエイト区分;0:宿アフィリエイト 1:バスアフィリエイト'
);

ALTER TABLE
    `fm_affiliate` COMMENT 'アフィリエイトマスタ;';

--   *** ------------------------------------
--  DDL for Table GIFT
--   *** ------------------------------------
-- 
CREATE TABLE `gift` (
    `gift_id` BIGINT COMMENT '1;ギフトID;',
    `gift_supplier_id` BIGINT COMMENT '2;ギフト提供先ID;',
    `product_cd` VARCHAR(20) BINARY COMMENT '3;提携先商品コード;',
    `gift_nm` VARCHAR(384) BINARY COMMENT '4;ギフト名称;',
    `location` VARCHAR(128) BINARY COMMENT '5;ギフト画像パス;',
    `width` SMALLINT COMMENT '6;幅;',
    `height` SMALLINT COMMENT '7;高さ;',
    `gift_explain` VARCHAR(3000) BINARY COMMENT '8;ギフト説明;',
    `point` BIGINT COMMENT '9;ポイント;',
    `order_cnt` BIGINT COMMENT '10;申込み・発送件数;',
    `stock` BIGINT COMMENT '11;ギフト在庫数;',
    `accept_s_dtm` DATETIME COMMENT '12;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '13;終了日時;',
    `delete_dtm` DATETIME COMMENT '14;削除日時;',
    `order_no` BIGINT COMMENT '15;ギフト表示順序;',
    `gift_price` INT COMMENT '16;ギフト料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '18;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '19;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '20;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '21;更新日時;',
    `delivery_status` TINYINT COMMENT '17;発送方法;0;配送 1:電子取引',
    `ticket_status` TINYINT
);

ALTER TABLE
    `gift` COMMENT 'ギフトデータ;';

--   *** ------------------------------------
--  *** FT_ORDER
--   *** ------------------------------------
-- 
CREATE TABLE `gift_order` (
    `gift_order_cd` VARCHAR(10) BINARY COMMENT '1;\?R[h;YYYYNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;・[h;xXgU[u・20oCg',
    `gift_id` BIGINT COMMENT '3;MtgID;',
    `product_cd` VARCHAR(20) BINARY COMMENT '4;???iR[h;??謔ﾌibc',
    `gift_nm` VARCHAR(384) BINARY COMMENT '5;Mtgﾌ;',
    `gift_supplier_id` BIGINT COMMENT '6;Mtg??覈d;',
    `gift_supplier_nm` VARCHAR(150) BINARY COMMENT '7;??於ﾌ;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '8;t譌X??;',
    `address` VARCHAR(255) BINARY COMMENT '9;t謠Z;',
    `tel` VARCHAR(15) BINARY COMMENT '10;t謫db?;',
    `order_person` VARCHAR(75) BINARY COMMENT '11;t於ﾌ;',
    `exchange_point` BIGINT COMMENT '12;?|Cg;?髀ﾁ・R|Cg',
    `delivery_cd` VARCHAR(50) BINARY COMMENT '13;R[h;',
    `gift_price` INT COMMENT '14;Mtg・',
    `note` VARCHAR(3000) BINARY COMMENT '15;l;',
    `condition` VARCHAR(12) BINARY COMMENT '16;\?ﾔ;order:\ﾝ cancel:LZ delivery:?ﾝ check:綠ﾏﾝ',
    `order_dtm` DATETIME COMMENT '17;t厲・',
    `cancel_dtm` DATETIME COMMENT '18;LZ厲・',
    `delivery_dtm` DATETIME COMMENT '19;厲・',
    `check_dtm` DATETIME COMMENT '20;厲・',
    `entry_cd` VARCHAR(64) BINARY COMMENT '21;o^?R[h;/controller/action.(user_id) ?ﾍ XV?[AhX',
    `entry_ts` DATETIME COMMENT '22;o^厲・',
    `modify_cd` VARCHAR(64) BINARY COMMENT '23;XV?R[h;/controller/action.(user_id) ?ﾍ XV?[AhX',
    `modify_ts` DATETIME COMMENT '24;XV厲・'
);

ALTER TABLE
    `gift_order` COMMENT 'Mtg\f[^igift_track_goods ??sj;';

--   *** ------------------------------------
--  *** FT_SUPPLIER
--   *** ------------------------------------
-- 
CREATE TABLE `gift_supplier` (
    `gift_supplier_id` BIGINT COMMENT '1;ギフト提供先コード;',
    `gift_supplier_nm` VARCHAR(150) BINARY COMMENT '2;ギフト提供先名称;',
    `person_post` VARCHAR(96) BINARY COMMENT '3;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '4;担当者名称;',
    `tel` VARCHAR(15) BINARY COMMENT '5;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '6;ファックス番号;ハイフン含む',
    `email` VARCHAR(128) BINARY COMMENT '7;電子メールアドレス;',
    `delivery_email` VARCHAR(128) BINARY COMMENT '8;発送依頼メールアドレス;',
    `delete_dtm` DATETIME COMMENT '9;削除日時;',
    `delivery_unit` VARCHAR(12) BINARY COMMENT '10;発送依頼単位;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `gift_supplier` COMMENT 'ギフト提携先;';

--   *** ------------------------------------
--  *** UDA_AR_TEST
--   *** ------------------------------------
-- 
CREATE TABLE `gouda_ar_test` (
    `id` INT COMMENT '1;ID;',
    `cd` VARCHAR(50) BINARY COMMENT '2;コード;',
    `date_ymd` DATETIME COMMENT '3;日付;',
    `date_dtm` DATETIME COMMENT '4;日時;',
    `intw` INT,
    `name` VARCHAR(50) BINARY COMMENT '6;文字列;',
    `note` LONGTEXT COMMENT '7;clob;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `gouda_ar_test` COMMENT 'テスト;';

--   *** ------------------------------------
--  *** OUP_BUYING
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying` (
    `deal_id` VARCHAR(10) BINARY COMMENT '1;商品ID;',
    `coupon_goal` INT COMMENT '2;成立枚数;',
    `coupon_max` INT COMMENT '3;最大枚数;',
    `coupon_count` INT COMMENT '4;注文枚数;',
    `order_count` INT COMMENT '5;注文件数;',
    `goal_dtm` DATETIME COMMENT '6;成立日時;',
    `status` TINYINT DEFAULT 0 COMMENT '7;状態;0:未成立  1:成立',
    `supplier_cd` VARCHAR(10) BINARY COMMENT '8;提供先コード;YYYYMM99',
    `supplier_deal_id` VARCHAR(10) BINARY COMMENT '9;提供先商品ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `soldout_dtm` DATETIME,
    `active_status` TINYINT
);

ALTER TABLE
    `group_buying` COMMENT '共同購入商品;';

--   *** ------------------------------------
--  *** OUP_BUYING_5273
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_5273` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `email` VARCHAR(200) BINARY COMMENT '2;会員登録メールアドレス;',
    `transaction_cd` VARCHAR(14) BINARY COMMENT '3;トランザクションコード;最初の予約コードを設定（複数部屋の予約について）',
    `status` TINYINT COMMENT '4;適用状態;0:無効 1:有効',
    `used_status` TINYINT COMMENT '5;使用状態;0:未使用 1:使用',
    `limit_dtm` DATETIME COMMENT '6;有効期限;',
    `discount_charge` INT DEFAULT 0 COMMENT '7;割引料金;税込み',
    `discount_charge_real` INT COMMENT '8;実値引き額;',
    `order_id` VARCHAR(14) BINARY COMMENT '9;共同購入注文ID;YYYYMM999999',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `member_cd2` VARCHAR(128) BINARY,
    `type` TINYINT
);

ALTER TABLE
    `group_buying_5273` COMMENT '共同購入キャンペーン（タスク5273）;';

--   *** ------------------------------------
--  *** OUP_BUYING_AUTHORI
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_authori` (
    `order_id` VARCHAR(14) BINARY COMMENT '1;共同購入注文ID;deal_id(YYYYMM9999) + 連番（4桁先頭0埋）',
    `authori_status` TINYINT COMMENT '2;オーソリステータス;0:オーソリ 1:料金変更 2:キャンセル（料金０円）',
    `sales_status` TINYINT COMMENT '3;売り上げステータス;0:未売り上げ 1:売り上げ済み',
    `sales_dtm` DATETIME COMMENT '4;売上日時;オーソリステータス「2」の場合はNULLのまま',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '5;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '6;カード有効期限;',
    `demand_charge` INT COMMENT '7;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '8;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '9;端末コード;03232:パワーホテル 05825:受託クレジット',
    `authori_dtm` DATETIME COMMENT '10;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(5) BINARY COMMENT '11;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '12;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `card_id` TINYINT
);

ALTER TABLE
    `group_buying_authori` COMMENT '共同購入商品カード決済オーソリ;';

--   *** ------------------------------------
--  *** OUP_BUYING_AUTHORI_DEV
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_authori_dev` (
    `order_id` VARCHAR(14) BINARY,
    `authori_status` TINYINT,
    `sales_status` TINYINT,
    `sales_dtm` DATETIME,
    `card_company_cd` VARCHAR(4) BINARY,
    `card_id` TINYINT,
    `card_limit_ym` DATETIME,
    `demand_charge` INT,
    `mall_cd` VARCHAR(7) BINARY,
    `terminal_cd` VARCHAR(5) BINARY,
    `authori_dtm` DATETIME,
    `voucher_no` VARCHAR(5) BINARY,
    `approval_no` VARCHAR(7) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** OUP_BUYING_CARD
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_card` (
    `order_id` VARCHAR(14) BINARY COMMENT '1;共同購入注文ID;deal_id(YYYYMM9999) + 連番（4桁先頭0埋）',
    `card_no` VARCHAR(32) BINARY COMMENT '2;口座番号;',
    `card_limit_ym` DATETIME COMMENT '3;クレジットカード有効期限;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `group_buying_card` COMMENT '共同購入商品カード;';

--   *** ------------------------------------
--  *** OUP_BUYING_COUPON
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_coupon` (
    `order_id` VARCHAR(14) BINARY COMMENT '1;共同購入注文ID;deal_id(YYYYMM9999) + 連番（4桁先頭0埋）',
    `coupon_id` VARCHAR(10) BINARY COMMENT '2;クーポンID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;',
    `use_s_ymd` DATETIME COMMENT '4;有効期間（開始）;',
    `use_e_ymd` DATETIME COMMENT '5;有効期間（終了）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `supplier_user_id` VARCHAR(12) BINARY,
    `qrcd_url` VARCHAR(256) BINARY,
    `supplier_order_process_id` VARCHAR(32) BINARY
);

ALTER TABLE
    `group_buying_coupon` COMMENT '共同購入商品注文クーポン;';

--   *** ------------------------------------
--  *** OUP_BUYING_DELIVERY
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_delivery` (
    `order_id` VARCHAR(14) BINARY COMMENT '1;共同購入注文ID;deal_id(YYYYMM9999) + 連番（4桁先頭0埋）',
    `postal_cd` VARCHAR(10) BINARY COMMENT '2;宛先郵便番号;',
    `pref_id` TINYINT COMMENT '3;宛先都道府県コード;',
    `city` VARCHAR(375) BINARY COMMENT '4;宛先市区町村;',
    `street` VARCHAR(375) BINARY COMMENT '5;宛先番地;',
    `person_family` VARCHAR(51) BINARY COMMENT '6;宛先氏;',
    `person_given` VARCHAR(96) BINARY COMMENT '7;宛先名;',
    `note` VARCHAR(375) BINARY COMMENT '8;指定日時等;',
    `tel` VARCHAR(32) BINARY COMMENT '9;連絡先電話番号;9999-9999-9999',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `group_buying_delivery` COMMENT '共同購入商品発送先;発送先情報';

--   *** ------------------------------------
--  *** OUP_BUYING_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_detail` (
    `deal_id` VARCHAR(10) BINARY COMMENT '1;商品ID;',
    `genre_cd` VARCHAR(16) BINARY COMMENT '2;ジャンルコード;',
    `deal_nm` VARCHAR(768) BINARY COMMENT '3;商品タイトル;',
    `deal_sub_nm` VARCHAR(768) BINARY COMMENT '4;商品サブタイトル;',
    `usual_charge` INT COMMENT '5;通常料金;',
    `deal_charge` INT COMMENT '6;商品料金;税込み',
    `order_limit` SMALLINT COMMENT '7;注文件数の上限;null : 無制限、 ｎ : ｎ件',
    `coupon_limit` SMALLINT COMMENT '8;注文枚数の上限;null : 無制限、 ｎ : ｎ枚',
    `open_dtm` DATETIME COMMENT '9;公開日時;指定日時から   >= YYYY/MM/DD HH24:00:00',
    `limit_dtm` DATETIME COMMENT '10;締め切り日時;入力時間まで有効 <= YYYY/MM/DD HH24:59:59',
    `use_s_ymd` DATETIME COMMENT '11;有効期間（開始）;',
    `use_e_ymd` DATETIME COMMENT '12;有効期間（終了）;',
    `use_option_note` LONGTEXT COMMENT '13;利用説明;',
    `etc_note` LONGTEXT COMMENT '14;補足説明;',
    `push_info` LONGTEXT COMMENT '15;おすすめ文;',
    `delivery_status` TINYINT COMMENT '16;配送状態;0:発送しない 1:発送する',
    `area_nm` VARCHAR(48) BINARY COMMENT '17;エリア名称;都道府県名など',
    `shop_nm` VARCHAR(192) BINARY COMMENT '18;店舗名;',
    `shop_postal_cd` VARCHAR(75) BINARY COMMENT '19;店舗郵便番号;',
    `shop_pref_id` TINYINT COMMENT '20;店舗都道府県ＩＤ;',
    `shop_address` VARCHAR(600) BINARY COMMENT '21;店舗住所;都道府県名称含む',
    `shop_tel` VARCHAR(54) BINARY COMMENT '22;店舗電話番号;',
    `shop_station` LONGTEXT COMMENT '23;店舗最寄り駅;',
    `shop_open_hours` VARCHAR(768) BINARY COMMENT '24;店舗営業時間;',
    `shop_holiday` VARCHAR(768) BINARY COMMENT '25;店舗定休日;',
    `shop_note` LONGTEXT COMMENT '26;店舗補足事項;',
    `shop_url` VARCHAR(256) BINARY COMMENT '27;店舗URL;',
    `shop_map_url` VARCHAR(1000) BINARY COMMENT '28;店舗GoogleMapのURL;',
    `shop_img` VARCHAR(128) BINARY COMMENT '29;店舗画像メイン（PC);',
    `shop_img1` VARCHAR(128) BINARY COMMENT '30;店舗画像1（PC);',
    `shop_img2` VARCHAR(128) BINARY COMMENT '31;店舗画像2（PC);',
    `shop_img3` VARCHAR(256) BINARY COMMENT '32;店舗画像（MOBILE);',
    `special_item1` VARCHAR(90) BINARY COMMENT '33;特殊入力項目１;',
    `special_item2` VARCHAR(90) BINARY COMMENT '34;特殊入力項目２;',
    `special_item3` VARCHAR(90) BINARY COMMENT '35;特殊入力項目３;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '36;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '37;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '38;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '39;更新日時;',
    `genre_id` SMALLINT,
    `shop_city_id` INT,
    `shop_ward_id` INT,
    `shop_area_nm` VARCHAR(150) BINARY,
    `deal_rate` TINYINT,
    `br_point_rate` SMALLINT
);

ALTER TABLE
    `group_buying_detail` COMMENT '共同購入商品詳細;';

--   *** ------------------------------------
--  *** OUP_BUYING_GENRE
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_genre` (
    `genre_id` SMALLINT COMMENT '1;ジャンルID;連番',
    `genre_nm` VARCHAR(30) BINARY COMMENT '2;ジャンル名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;',
    `genre_type` VARCHAR(16) BINARY
);

ALTER TABLE
    `group_buying_genre` COMMENT '共同購入ジャンル;';

--   *** ------------------------------------
--  *** OUP_BUYING_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_hotel` (
    `deal_id` VARCHAR(10) BINARY COMMENT '1;商品ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `group_buying_hotel` COMMENT '共同購入商品施設マッチングデータ;';

--   *** ------------------------------------
--  *** OUP_BUYING_ORDER
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_order` (
    `order_id` VARCHAR(14) BINARY COMMENT '1;共同購入注文ID;deal_id(YYYYMM9999) + 連番（4桁先頭0埋）',
    `supplier_cd` VARCHAR(10) BINARY COMMENT '2;提供先コード;YYYYMM9999',
    `supplier_order_id` VARCHAR(32) BINARY COMMENT '3;提供先注文ID;',
    `deal_id` VARCHAR(10) BINARY COMMENT '4;商品ID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '5;会員コード;ベストリザーブ会員は20バイト',
    `order_dtm` DATETIME COMMENT '6;注文日時;',
    `coupon_count` SMALLINT COMMENT '7;注文枚数;',
    `deal_charge` INT DEFAULT 0 COMMENT '8;商品料金;税込み',
    `email` VARCHAR(200) BINARY COMMENT '9;連絡先電子メールアドレス;',
    `special_item1` VARCHAR(90) BINARY COMMENT '10;特殊入力項目１;',
    `special_item2` VARCHAR(90) BINARY COMMENT '11;特殊入力項目２;',
    `special_item3` VARCHAR(90) BINARY COMMENT '12;特殊入力項目３;',
    `order_status` TINYINT DEFAULT 1 COMMENT '13;申込状態;0:無効、1:有効',
    `valid_status` TINYINT COMMENT '14;成立状態;0:未成立 1:成立 （クーポンが発券されたら成立とする）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;',
    `valid_mail_cd` VARCHAR(16) BINARY,
    `discount_charge` INT,
    `affiliate_cd` VARCHAR(10) BINARY,
    `affiliate_cd_sub` VARCHAR(20) BINARY,
    `br_point_rate` SMALLINT,
    `use_br_point` BIGINT,
    `valid_dtm` DATETIME,
    `cancel_dtm` DATETIME
);

ALTER TABLE
    `group_buying_order` COMMENT '共同購入商品注文;';

--   *** ------------------------------------
--  *** OUP_BUYING_SUPPLIER
--   *** ------------------------------------
-- 
CREATE TABLE `group_buying_supplier` (
    `supplier_cd` VARCHAR(10) BINARY COMMENT '1;提供先コード;YYYYMM9999',
    `supplier_nm` VARCHAR(150) BINARY COMMENT '2;提供先名称;',
    `person_post` VARCHAR(96) BINARY COMMENT '3;提供先所属;',
    `person_nm` VARCHAR(96) BINARY COMMENT '4;提供先担当者;',
    `tel` VARCHAR(15) BINARY COMMENT '5;提供先電話番号;',
    `fax` VARCHAR(15) BINARY COMMENT '6;提供先ファックス番号;',
    `email` VARCHAR(200) BINARY COMMENT '7;提供先電子メールアドレス;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `active_status` TINYINT
);

ALTER TABLE
    `group_buying_supplier` COMMENT '共同購入提供先;';

--   *** ------------------------------------
--  *** KARI_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `hikari_account` (
    `id` INT COMMENT '1;ID;',
    `account_id` VARCHAR(20) BINARY COMMENT '2;アカウントID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;暗号化した値',
    `accept_status` TINYINT COMMENT '4;ステータス;0:利用不可 1:利用可',
    `note` VARCHAR(3000) BINARY COMMENT '5;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hikari_account` COMMENT '光通信ログイン情報;';

--   *** ------------------------------------
--  *** TEL
--   *** ------------------------------------
-- 
CREATE TABLE `hotel` (
    `hotel_cd` VARCHAR(10) BINARY,
    `order_no` BIGINT,
    `hotel_category` VARCHAR(1) BINARY,
    `hotel_nm` VARCHAR(150) BINARY,
    `hotel_kn` VARCHAR(300) BINARY,
    `hotel_old_nm` VARCHAR(150) BINARY,
    `postal_cd` VARCHAR(8) BINARY,
    `pref_id` TINYINT,
    `city_id` DECIMAL(20, 0),
    `ward_id` DECIMAL(20, 0),
    `address` VARCHAR(300) BINARY,
    `tel` VARCHAR(15) BINARY,
    `fax` VARCHAR(15) BINARY,
    `room_count` SMALLINT,
    `check_in` VARCHAR(5) BINARY,
    `check_in_end` VARCHAR(5) BINARY,
    `check_in_info` VARCHAR(225) BINARY,
    `check_out` VARCHAR(5) BINARY,
    `midnight_status` TINYINT,
    `accept_status` TINYINT,
    `accept_auto` TINYINT,
    `accept_dtm` DATETIME,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** TEL_ACCEPT_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_accept_history` (
    `hotel_accept_id` DECIMAL(22, 0) COMMENT '1;受付状態変更ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `accept_status` TINYINT COMMENT '3;予約受付状態;0:停止中 1:受付中',
    `staff_flag` VARCHAR(1) BINARY COMMENT '4;スタッフフラグ;0:施設 1:BR社内 2:バッチ処理',
    `staff_id` INT COMMENT '5;スタッフID;',
    `update_nm` VARCHAR(96) BINARY COMMENT '6;更新者名称;staff_flag が0 の場合、更新時の施設担当者名称を保存。 1 の場合、更新時のスタッフ名称を保存。 2 の場合、バッチ名称を保存。',
    `update_dtm` DATETIME COMMENT '7;変更日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `hotel_accept_history` COMMENT '予約受付状態履歴テーブル;予約受付状態の受付/停止の変更履歴を管理する。';

--   *** ------------------------------------
--  *** TEL_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_account` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `account_id` VARCHAR(20) BINARY COMMENT '2;アカウントID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;暗号化した値',
    `accept_status` TINYINT COMMENT '4;ステータス;0:利用不可 1:利用可',
    `remember_token` VARCHAR(100) COMMENT 'ログイン状態保持トークン',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `account_id_begin` VARCHAR(20) BINARY
);

ALTER TABLE
    `hotel_account` COMMENT '施設認証;';

--   *** ------------------------------------
--  *** TEL_ADVERT_2009000400
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_advert_2009000400` (
    `record_id` INT COMMENT '1;広告掲載ID;YYYY+数値4桁 （年単位で連番：シーケンスは使用しない）',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `advert_s_ymd` DATETIME COMMENT '3;掲載開始年月日;',
    `advert_e_ymd` DATETIME COMMENT '4;掲載最終年月日;',
    `advert_order` INT COMMENT '5;掲載順序;初期値：広告掲載ID',
    `advert_charge` INT COMMENT '6;掲載金額;税サ込',
    `advert_status` TINYINT DEFAULT 0 COMMENT '7;掲載状態;0:無効  1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `hotel_advert_2009000400` COMMENT '広告掲載施設;「迷わずこお！」の広告用テーブル（アフリエイトコード ：2009000400 ）';

--   *** ------------------------------------
--  *** TEL_AMENITY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_amenity` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `element_id` SMALLINT COMMENT '2;要素ID;',
    `element_value_id` TINYINT COMMENT '3;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_amenity` COMMENT '施設アメニティ;施設アイテムマスタより';

--   *** ------------------------------------
--  *** TEL_AREA
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_area` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `entry_no` TINYINT COMMENT '2;登録番号;施設単位にエリアIDを集約するための項目「連番（1〜）」',
    `area_id` SMALLINT COMMENT '3;地域ID;',
    `area_type` TINYINT COMMENT '4;地域タイプ;0:日本全域 1:大エリア 2:都道府県 3:中エリア 4:小エリア',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_area` COMMENT '施設エリア;';

--   *** ------------------------------------
--  *** TEL_BATH_TAX
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_bath_tax` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `bath_tax_status` TINYINT COMMENT '2;入湯税設定状態;0:設定しない 1:設定する',
    `bath_tax_charge` INT COMMENT '3;入湯税金額（大人）;',
    `bath_tax_charge_child` INT COMMENT '4;入湯税金額（子供）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_bath_tax` COMMENT '施設入湯税;';

--   *** ------------------------------------
--  *** TEL_CAMP
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_camp` (
    `camp_cd` VARCHAR(10) BINARY COMMENT '1;キャンペーンID;YYYYMMNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `camp_nm` VARCHAR(96) BINARY COMMENT '3;キャンペーン名称;',
    `display_status` TINYINT COMMENT '4;表示ステータス;0:非表示 1:表示',
    `accept_s_ymd` DATETIME COMMENT '5;開始日;',
    `accept_e_ymd` DATETIME COMMENT '6;終了日;',
    `target_s_ymd` DATETIME COMMENT '7;対象宿泊期間開始日付;',
    `target_e_ymd` DATETIME COMMENT '8;対象宿泊期間終了日付;',
    `content_type` TINYINT COMMENT '9;コンテンツタイプ;0:text/plain 1:text/html',
    `description` VARCHAR(4000) BINARY COMMENT '10;詳細;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `hotel_camp` COMMENT '施設キャンペーン;';

--   *** ------------------------------------
--  *** TEL_CAMP_GOTO
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_camp_goto` (
    `camp_cd` VARCHAR(12) BINARY COMMENT '1;キャンペーンコード;YYYYMMNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `camp_nm` VARCHAR(96) BINARY COMMENT '3;キャンペーン名称;',
    `display_status` TINYINT COMMENT '4;表示ステータス;0:非表示 1:表示',
    `accept_s_ymd` DATETIME COMMENT '5;開始日;',
    `accept_e_ymd` DATETIME COMMENT '6;終了日;',
    `target_s_ymd` DATETIME COMMENT '7;対象宿泊期間開始日付;',
    `target_e_ymd` DATETIME COMMENT '8;対象宿泊期間終了日付;',
    `content_type` TINYINT COMMENT '9;コンテンツタイプ;0:text/plain 1:text/html',
    `description` VARCHAR(4000) BINARY COMMENT '10;詳細;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `paper_use_flg` TINYINT
);

ALTER TABLE
    `hotel_camp_goto` COMMENT '施設キャンペーンGoTo;施設キャンペーンGoTo専用';

--   *** ------------------------------------
--  *** TEL_CAMP_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_camp_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `camp_cd` VARCHAR(10) BINARY COMMENT '4;キャンペーンID;YYYYMMNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_camp_plan` COMMENT '施設キャンペーン部屋プラン;';

--   *** ------------------------------------
--  *** TEL_CAMP_PLAN2
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_camp_plan2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;YYYYNNNNNN',
    `camp_cd` VARCHAR(10) BINARY COMMENT '4;キャンペーンコード;YYYYMMNNNN',
    `room_cd` VARCHAR(10) BINARY COMMENT '5;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '6;プランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `hotel_camp_plan2` COMMENT '施設キャンペーンプラン HTL121;';

--   *** ------------------------------------
--  *** TEL_CAMP_PLAN2_GOTO
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_camp_plan2_goto` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `camp_cd` VARCHAR(12) BINARY COMMENT '3;キャンペーンコード;YYYYMMNNNN',
    `display_status` TINYINT COMMENT '4;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_camp_plan2_goto` COMMENT '施設キャンペーンプランGoTo;施設キャンペーンプランGoTo用';

--   *** ------------------------------------
--  *** TEL_CAMP_PLAN_GOTO
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_camp_plan_goto` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `camp_cd` VARCHAR(12) BINARY COMMENT '4;キャンペーンコード;YYYYMMNNNN',
    `display_status` TINYINT COMMENT '5;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hotel_camp_plan_goto` COMMENT '施設キャンペーン部屋プランGoTo;施設キャンペーン部屋プランGoTo用';

--   *** ------------------------------------
--  *** TEL_CANCEL_POLICY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_cancel_policy` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `cancel_policy` VARCHAR(2850) BINARY COMMENT '2;キャンセルポリシー;200文字',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_cancel_policy` COMMENT '施設キャンセルポリシー;';

--   *** ------------------------------------
--  *** TEL_CANCEL_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_cancel_rate` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `days` SMALLINT COMMENT '2;宿泊日からの日数;',
    `cancel_rate` SMALLINT COMMENT '3;キャンセル料率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `policy_status` TINYINT
);

ALTER TABLE
    `hotel_cancel_rate` COMMENT '施設キャンセル料率;';

--   *** ------------------------------------
--  *** TEL_CARD
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_card` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `card_id` TINYINT COMMENT '2;カードID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_card` COMMENT '施設利用可能カード;';

--   *** ------------------------------------
--  *** TEL_CONTROL
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_control` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `stock_type` TINYINT COMMENT '2;仕入タイプ;0:受託販売 1:買取販売',
    `achievements_fax` TINYINT COMMENT '3;送客実績ファイル送信可否;0:非送信 1:表示',
    `achievements_order` BIGINT COMMENT '4;送客実績ファイル送信順序;設定なし:NULL',
    `charge_round` SMALLINT COMMENT '5;金額切り捨て桁;10:10の位以下切り捨て、100:100の位以下で切り捨て',
    `stay_cap` TINYINT COMMENT '6;連泊限界数;最大泊数が null か 予約泊数以下の時予約可能',
    `management_status` TINYINT COMMENT '7;利用方法;1:ファックス管理 2:インターネット管理 3:ファックス管理＋インターネット管理',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `checksheet_send` TINYINT,
    `akafu_status` TINYINT
);

ALTER TABLE
    `hotel_control` COMMENT '施設管理;';

--   *** ------------------------------------
--  *** TEL_CONTROL_NOTE
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_control_note` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `display_status` TINYINT COMMENT '2;表示ステータス;0:非表示 1:表示',
    `note_info` VARCHAR(90) BINARY COMMENT '3;備考入力内容;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_control_note` COMMENT '施設宿泊時備考設定;';

--   *** ------------------------------------
--  *** TEL_ELEMENT_REMOVED
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_element_removed` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `table_name` VARCHAR(30) BINARY COMMENT '2;テーブル名称;',
    `destroy_dtm` DATETIME COMMENT '3;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_element_removed` COMMENT '施設要素削除情報テーブル;';

--   *** ------------------------------------
--  *** TEL_FACILITY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_facility` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `element_id` SMALLINT COMMENT '2;要素ID;',
    `element_value_id` TINYINT COMMENT '3;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_facility` COMMENT '施設設備;施設アイテムマスタより';

--   *** ------------------------------------
--  *** TEL_FACILITY_ROOM
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_facility_room` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `element_id` SMALLINT COMMENT '2;要素ID;',
    `element_value_id` TINYINT COMMENT '3;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_facility_room` COMMENT '施設部屋設備;施設アイテムマスタより';

--   *** ------------------------------------
--  *** TEL_GOTO_EXCEL
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_goto_excel` (
    `hotel_goto_excel_id` DECIMAL(22, 0) COMMENT '1;GOTOエクセルID;シーケンスより取得',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `report_ym` DATETIME COMMENT '3;報告対象年月;',
    `filename` VARCHAR(64) BINARY COMMENT '4;ファイル名;施設コード+報告対象年月のYYYYMM',
    `org_filename` VARCHAR(256) BINARY COMMENT '5;元のファイル名;施設がファイルにつけていたファイル名',
    `display_status` TINYINT COMMENT '6;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;	登録者コード;',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `hotel_goto_excel` COMMENT 'GotoキャンペーンEXCELupload状況;Gotoキャンペーンの地域共通クーポンの紐付けEXCELのupload状況を管理する';

--   *** ------------------------------------
--  *** TEL_GOTO_REGIST
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_goto_regist` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `regist_status` TINYINT COMMENT '2;登録状況;1:完了 2:確認中 3:不参加',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_goto_regist` COMMENT 'Gotoキャンペーン事業者登録回答状況;Gotoキャンペーン事業者登録回答状況を保管する';

--   *** ------------------------------------
--  *** TEL_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_info` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `parking_info` VARCHAR(450) BINARY COMMENT '2;駐車場詳細;',
    `card_info` VARCHAR(225) BINARY COMMENT '3;カード利用条件;',
    `info` VARCHAR(3000) BINARY COMMENT '4;特色;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_info` COMMENT '施設情報;';

--   *** ------------------------------------
--  *** TEL_INFORM
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_inform` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `inform_type` TINYINT COMMENT '3;連絡タイプ;0:注意事項 1:その他記入欄',
    `inform` VARCHAR(2400) BINARY COMMENT '4;連絡事項;',
    `order_no` TINYINT COMMENT '5;連絡事項表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hotel_inform` COMMENT '施設連絡事項;';

--   *** ------------------------------------
--  *** TEL_INSURANCE_WEATHER
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_insurance_weather` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `insurance_status` TINYINT COMMENT '2;適用状態;-1:加入停止（ずっと） 0:加入停止（一時） 1:加入可',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;',
    `amedas_cd` VARCHAR(5) BINARY
);

ALTER TABLE
    `hotel_insurance_weather` COMMENT '施設お天気保険サービス管理;';

--   *** ------------------------------------
--  *** TEL_JR_ENTRY_STATUS
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_jr_entry_status` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `nta_search_status` TINYINT COMMENT '2;NTA検索ステータス;',
    `judge_status` TINYINT COMMENT '3;施設審査ステータス;',
    `last_modify_dtm` DATETIME COMMENT '4;施設データ連携項目最終更新日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_jr_entry_status` COMMENT '施設JRセット参画状態;施設JRセットプラン参画状態';

--   *** ------------------------------------
--  *** TEL_LANDMARK
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_landmark` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `landmark_id` INT COMMENT '2;ランドマークID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;',
    `display_status` TINYINT
);

ALTER TABLE
    `hotel_landmark` COMMENT '施設ランドマーク;';

--   *** ------------------------------------
--  *** TEL_LANDMARK_SURVEY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_landmark_survey` (
    `landmark_id` INT COMMENT '1;ランドマークID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_landmark_survey` COMMENT 'ランドマーク付近施設;';

--   *** ------------------------------------
--  *** TEL_LINK
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_link` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `type` TINYINT COMMENT '3;ウェブサイトタイプ;1:施設トップページ 2:携帯トップページ 3:その他ページ',
    `title` VARCHAR(300) BINARY COMMENT '4;ウェブサイトタイトル;',
    `url` VARCHAR(128) BINARY COMMENT '5;ウェブサイトアドレス;',
    `order_no` TINYINT COMMENT '6;リンク表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `hotel_link` COMMENT '施設リンク;';

--   *** ------------------------------------
--  *** TEL_MEDIA
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_media` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `type` TINYINT COMMENT '2;画像タイプ;1:施設 2:地図 3:その他',
    `media_no` SMALLINT COMMENT '3;メディアNo;',
    `order_no` SMALLINT COMMENT '4;画像表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_media` COMMENT '施設メディア;';

--   *** ------------------------------------
--  *** TEL_MODIFY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_modify` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `modify_status` TINYINT COMMENT '2;更新ステータス;0:更新対象外 1:更新対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_modify` COMMENT '施設情報ページ更新対象テーブル;';

--   *** ------------------------------------
--  *** TEL_MSC
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_msc` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `msc_type` TINYINT COMMENT '2;マルチサイトコントローラタイプ;0:利用しない 1:手間いらず 2:らくじゃん、らく通 3:リンカーン 4:宿研 5:ねっぱん',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_msc` COMMENT '施設マルチサイトコントローラ利用状況;';

--   *** ------------------------------------
--  *** TEL_MSC_LOGIN
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_msc_login` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `msc_type` TINYINT COMMENT '2;マルチサイトコントローラタイプ;0:利用しない 1:手間いらず 2:らくじゃん、らく通 3:リンカーン 4:宿研 5:ねっぱん',
    `login_dtm` DATETIME COMMENT '3;最終ログイン日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_msc_login` COMMENT '施設マルチサイトコントローラログイン状況（将来実装）;リンカーン、ねっぱんでログインされたときnotifyを自動生成してもよいのかあかんのか';

--   *** ------------------------------------
--  *** TEL_NEARBY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_nearby` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `element_id` SMALLINT COMMENT '2;要素ID;',
    `element_value_id` TINYINT COMMENT '3;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_nearby` COMMENT '施設周辺情報;施設アイテムマスタより';

--   *** ------------------------------------
--  *** TEL_NOTIFY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_notify` (
    `hotel_cd` VARCHAR(10) BINARY,
    `notify_status` TINYINT,
    `notify_device` TINYINT,
    `notify_no` BIGINT,
    `notify_email` VARCHAR(500) BINARY,
    `notify_fax` VARCHAR(15) BINARY,
    `faxpr_status` TINYINT,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME,
    `neppan_status` TINYINT
);

--   *** ------------------------------------
--  *** TEL_PAY_PER_CALL
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_pay_per_call` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '2;提携先コード;',
    `tel` VARCHAR(15) BINARY COMMENT '3;ペイパーコール用電話番号;ハイフン含む',
    `status` TINYINT DEFAULT 0 COMMENT '4;状態;0:無効 1:有効',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス'
);

ALTER TABLE
    `hotel_pay_per_call` COMMENT 'Pay Per Call用テーブル;';

--   *** ------------------------------------
--  *** TEL_PERSON
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_person` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `person_post` VARCHAR(96) BINARY COMMENT '2;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '3;担当者名称;',
    `person_tel` VARCHAR(15) BINARY COMMENT '4;担当者電話番号;ハイフン含む',
    `person_fax` VARCHAR(15) BINARY COMMENT '5;担当者ファックス番号;ハイフン含む',
    `person_email` VARCHAR(128) BINARY COMMENT '6;担当者電子メールアドレス;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `hotel_person` COMMENT '施設管理サイト担当者;';

--   *** ------------------------------------
--  *** TEL_POWERDOWN
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_powerdown` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `powerdown_seq` INT COMMENT '2;パワーダウン連番;',
    `target_s_ymd` DATETIME COMMENT '3;対象宿泊期間開始日付;予約日単位から宿泊日単位に変更',
    `powerdown_charge` INT COMMENT '4;パワーダウン料金;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_powerdown` COMMENT '施設パワーダウン;パーホテルの割引情報';

--   *** ------------------------------------
--  *** TEL_POWERDOWN_S
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_powerdown_s` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `powerdown_seq` INT COMMENT '2;パワーダウン連番;',
    `powerdown_s_dtm` DATETIME COMMENT '3;パワーダウン開始日時;',
    `powerdown_e_dtm` DATETIME COMMENT '4;パワーダウン終了日時;',
    `target_s_ymd` DATETIME COMMENT '5;対象宿泊期間開始日付;',
    `target_e_ymd` DATETIME COMMENT '6;対象宿泊期間終了日付;',
    `powerdown_charge` INT COMMENT '7;パワーダウン料金;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `hotel_powerdown_s` COMMENT '施設パワーダウン（特別）;パワーホテルの割引情報';

--   *** ------------------------------------
--  *** TEL_PREMIUM
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_premium` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `open_ymd` DATETIME COMMENT '2;開始年月日;',
    `close_ymd` DATETIME COMMENT '3;最終年月日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `display_status` TINYINT,
    `area_cd` TINYINT,
    `order_no` INT,
    `plan_id` VARCHAR(10) BINARY,
    `room_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `hotel_premium` COMMENT 'プレミアム施設;';

--   *** ------------------------------------
--  *** TEL_PRIORITY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_priority` (
    `ctrl_no` INT COMMENT '1;管理番号;',
    `pref_id` TINYINT COMMENT '2;都道府県ID;',
    `accept_s_ymd` DATETIME COMMENT '3;開始日;',
    `accept_e_ymd` DATETIME COMMENT '4;終了日;',
    `priority` SMALLINT COMMENT '5;重点表示順位;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `display_status` TINYINT COMMENT '7;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `hotel_priority` COMMENT '重点表示ホテル;重点表示ホテルを保持します。';

--   *** ------------------------------------
--  *** TEL_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_rate` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `accept_s_ymd` DATETIME COMMENT '3;開始日;',
    `system_rate` SMALLINT COMMENT '4;システム利用料率;5%のときは5',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `system_rate_out` SMALLINT
);

ALTER TABLE
    `hotel_rate` COMMENT 'システム利用料率マスタ;';

--   *** ------------------------------------
--  *** TEL_RECEIPT
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_receipt` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `receipt_policy` TINYINT COMMENT '2;領収書発行ポリシー;1:実際に徴収された宿泊料金の合計 2:宿泊料金の合計 4:宿泊料金の合計とポイント内訳',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_receipt` COMMENT '施設レシート;';

--   *** ------------------------------------
--  *** TEL_RECOMMEND
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_recommend` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `recommend_id` TINYINT COMMENT '3;おすすめID;1:駅近、2:周辺施設、3:部屋、4:接客、5:サービス、6:料金、7:朝食、8:浴場、9:ネット',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `frequency` TINYINT COMMENT '5;おすすめ度数;すべて３ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hotel_recommend` COMMENT 'ホテルおすすめ情報;';

--   *** ------------------------------------
--  *** TEL_RECOMMEND_RESULT
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_recommend_result` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `recommend_id` TINYINT COMMENT '2;おすすめID;1:駅近、2:周辺施設、3:部屋、4:接客、5:サービス、6:料金、7:朝食、8:浴場、9:ネット',
    `frequency` BIGINT COMMENT '3;おすすめ度数合計;すべて３ポイント',
    `ranking` TINYINT COMMENT '4;ランキング;',
    `continue` TINYINT COMMENT '5;ランキング連番;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hotel_recommend_result` COMMENT 'ホテルおすすめ情報結果;';

--   *** ------------------------------------
--  *** TEL_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_relation` (
    `hotel_relation_cd` VARCHAR(8) BINARY COMMENT '1;施設関連付ID;YYYYNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `relation_cd` VARCHAR(10) BINARY COMMENT '3;関連施設コード;必ず同一の施設コードが関連施設コードに存在します。',
    `note` VARCHAR(3000) BINARY COMMENT '4;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_relation` COMMENT '施設関連付け;ブロック、パワー、バルクの関連付けるテーブル';

--   *** ------------------------------------
--  *** TEL_REVIEW
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_review` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `review_type` BIGINT COMMENT '2;クチコミ種類;0 = 会員 1 = 補正値（会員評価に加算する：最大５）',
    `review_id` TINYINT COMMENT '3;クチコミ項目;部屋:１ ばす・トイレ：2 食事（朝食・夕食）:3 接客・サービス:4 料金:5 立地:6 総合:0',
    `review_cnt` DECIMAL(2, 1) COMMENT '4;クチコミ評点;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_review` COMMENT '施設クチコミ評価;Voice_review の平均値 ＋ 補正値 で評価点を求める（最大５）';

--   *** ------------------------------------
--  *** TEL_SEARCH_WORDS
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_search_words` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '2;施設名称;',
    `hotel_kn` VARCHAR(300) BINARY COMMENT '3;施設名称かな;',
    `hotel_old_nm` VARCHAR(150) BINARY COMMENT '4;旧施設名称;',
    `info` VARCHAR(3000) BINARY COMMENT '5;特色;',
    `address` VARCHAR(330) BINARY COMMENT '6;検索用住所;都道府県以下',
    `tel` VARCHAR(15) BINARY COMMENT '7;電話番号;ハイフン含む',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `hotel_search_words` COMMENT '施設検索用データ;';

--   *** ------------------------------------
--  *** TEL_SERVICE
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_service` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `element_id` SMALLINT COMMENT '2;要素ID;',
    `element_value_id` TINYINT COMMENT '3;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_service` COMMENT '施設サービス;施設アイテムマスタより';

--   *** ------------------------------------
--  *** TEL_SPOT
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_spot` (
    `spot_id` BIGINT COMMENT '1;スポットID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_spot` COMMENT 'スポット施設;';

--   *** ------------------------------------
--  *** TEL_STAFF_NOTE
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_staff_note` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `staff_note` VARCHAR(3000) BINARY COMMENT '2;スタッフノート;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_staff_note` COMMENT '施設スタッフノート;営業用の備考';

--   *** ------------------------------------
--  *** TEL_STATION
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_station` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `station_id` INT COMMENT '2;駅ID;',
    `traffic_way` TINYINT COMMENT '3;交通手段;0:徒歩 1:車',
    `minute` SMALLINT COMMENT '4;分;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `order_no` TINYINT
);

ALTER TABLE
    `hotel_station` COMMENT '施設最寄駅;';

--   *** ------------------------------------
--  *** TEL_STATIONS
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_stations` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `station_id` VARCHAR(7) BINARY COMMENT '2;駅ID;先頭文字「B」は、ベストリザーブオリジナル駅、 その他「０〜9｣は駅データの駅です。',
    `traffic_way` TINYINT COMMENT '3;交通手段;0:徒歩 1:車',
    `order_no` TINYINT COMMENT '4;最寄駅表示順序;',
    `minute` SMALLINT COMMENT '5;分;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hotel_stations` COMMENT '施設の最寄り駅;';

--   *** ------------------------------------
--  *** TEL_STATIONS_SURVEY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_stations_survey` (
    `station_id` VARCHAR(7) BINARY COMMENT '1;駅ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_stations_survey` COMMENT '駅付近施設;station_cdからstation_idに変更したバージョン';

--   *** ------------------------------------
--  *** TEL_STATION_SURVEY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_station_survey` (
    `station_cd` VARCHAR(7) BINARY COMMENT '1;駅コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `station_group_id` VARCHAR(7) BINARY COMMENT '3;駅グループコード;駅グループコードが示す駅コードがターミナル駅となります。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_station_survey` COMMENT '駅付近施設;';

--   *** ------------------------------------
--  *** TEL_STATION_WK
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_station_wk` (
    `hotel_cd` VARCHAR(10) BINARY,
    `station_id` INT,
    `traffic_way` TINYINT,
    `order_no` TINYINT,
    `minute` SMALLINT,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** TEL_STATUS
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_status` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `entry_status` TINYINT COMMENT '2;登録状態;0:公開中 1:登録作業中 2:解約',
    `contract_ymd` DATETIME COMMENT '3;契約日;',
    `open_ymd` DATETIME COMMENT '4;公開日;',
    `close_dtm` DATETIME COMMENT '5;解約日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `hotel_status` COMMENT '施設状況;';

--   *** ------------------------------------
--  *** TEL_STATUS_JR
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_status_jr` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `active_status` TINYINT COMMENT '2;システム取扱状態;',
    `judge_status` TINYINT COMMENT '3;施設審査ステータス;0:審査中 1:審査OK 2:審査NG',
    `last_modify_dtm` DATETIME COMMENT '4;施設データ連携項目最終更新日時;特定項目のいずれかが更新された日時のうちの最終更新日時（施設コード・施設名称・施設名称カナ・郵便番号・住所・電話番号・FAX番号）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `judge_s_dtm` DATETIME,
    `judge_dtm` DATETIME
);

ALTER TABLE
    `hotel_status_jr` COMMENT '施設JRセット参画状態;施設JRセットプラン参画状態';

--   *** ------------------------------------
--  *** TEL_SUPERVISOR
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_supervisor` (
    `supervisor_cd` VARCHAR(10) BINARY COMMENT '1;施設統括コード;',
    `supervisor_nm` VARCHAR(128) BINARY COMMENT '2;施設統括名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_supervisor` COMMENT '施設統括;';

--   *** ------------------------------------
--  *** TEL_SUPERVISOR_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_supervisor_account` (
    `supervisor_cd` VARCHAR(10) BINARY COMMENT '1;施設統括コード;',
    `account_id` VARCHAR(20) BINARY COMMENT '2;アカウントID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;暗号化した値',
    `accept_status` TINYINT COMMENT '4;ステータス;0:利用不可 1:利用可',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `hotel_supervisor_account` COMMENT '施設統括認証;';

--   *** ------------------------------------
--  *** TEL_SUPERVISOR_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_supervisor_hotel` (
    `id` INT COMMENT '1;ID;',
    `supervisor_cd` VARCHAR(10) BINARY COMMENT '2;施設統括コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `order_number` INT DEFAULT NULL COMMENT '4;並び順;'
);

ALTER TABLE
    `hotel_supervisor_hotel` COMMENT '施設統括施設;';

--   *** ------------------------------------
--  *** TEL_SURVEY
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_survey` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `wgs_lat` VARCHAR(16) BINARY COMMENT '2;世界測地系-度分秒-緯度;JGD2000と同様',
    `wgs_lng` VARCHAR(16) BINARY COMMENT '3;世界測地系-度分秒-経度;JGD2000と同様',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '4;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '5;世界測地系-度-経度;JGD2000と同様',
    `td_lat` VARCHAR(16) BINARY COMMENT '6;東京測地系-度分秒-緯度;',
    `td_lng` VARCHAR(16) BINARY COMMENT '7;東京測地系-度分秒-経度;',
    `td_lat_d` VARCHAR(16) BINARY COMMENT '8;東京測地系-度-緯度;',
    `td_lng_d` VARCHAR(16) BINARY COMMENT '9;東京測地系-度-経度;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `hotel_survey` COMMENT '施設測地;東京測地系と世界測地系の緯度経度を保持（計算式に使用する場合は、度表記を使用）';

--   *** ------------------------------------
--  *** TEL_SYSTEM_VERSION
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_system_version` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `system_type` VARCHAR(12) BINARY COMMENT '2;システムページタイプ;plan:プランメンテナンス',
    `version` BIGINT COMMENT '3;システムバージョン;system_typeで16進数で管理（1:Ver1.0 2:Ver2.0）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_system_version` COMMENT '管理システム利用バージョン;';

--   *** ------------------------------------
--  *** TEL_TYK
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_tyk` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `tyk_hotel_cd` VARCHAR(5) BINARY COMMENT '2;東横イン施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;',
    `active_status` TINYINT
);

ALTER TABLE
    `hotel_tyk` COMMENT '東横イン施設関連付けテーブル;';

--   *** ------------------------------------
--  *** TEL_TYPE_20170101
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_type_20170101` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `hotel_type` TINYINT COMMENT '2;施設区分;1:BR施設 2:NTA契約 3:アパホテル 4:大和リゾート 5:東横イン',
    `revision_status` TINYINT COMMENT '3;改定ステータス;0:改定の対応を実施しない 1:改定の対応を実施',
    `disp_s_ymd` DATETIME COMMENT '4;表示開始日;改定に関する項目の画面への表示等に利用',
    `disp_e_ymd` DATETIME COMMENT '5;表示終了日;改定に関する項目の画面への表示等に利用',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `revision_condition` TINYINT COMMENT '10;改定対応状況;0:改定未対応(未対象)施設 1:改定対応中施設 2:改定対応済施設',
    `revision_timing` TINYINT COMMENT '11;改定タイミング;0：改定無 1：2017年1月1日から 2：2017年3月1日から'
);

ALTER TABLE
    `hotel_type_20170101` COMMENT '施設区分_20170101_料率付与率改定用;20170101からのシステム料率並びにポイント料率改定のための施設区分判定用テーブル';

--   *** ------------------------------------
--  *** TEL_VISUAL
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_visual` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `open_ymd` DATETIME COMMENT '2;開始年月日;',
    `close_ymd` DATETIME COMMENT '3;最終年月日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_visual` COMMENT '施設ヴィジュアルパッケージ;';

--   *** ------------------------------------
--  *** TEL_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_yahoo` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `relation_id` TINYINT COMMENT '2;ヤフー施設関連ID;',
    `yahoo_hotel_cd` VARCHAR(7) BINARY COMMENT '3;ヤフー施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_yahoo` COMMENT 'Yahoo施設関連付け;複数施設の取り扱いテーブル';

--   *** ------------------------------------
--  *** TEL_YDK
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydk` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `hotel_cd_ydk` VARCHAR(10) BINARY COMMENT '2;YDK施設コード;',
    `status` TINYINT COMMENT '3;処理内容;0:全プラン削除の上追加、1:そのまま追加',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_ydk` COMMENT '宿研施設関連付けテーブル;';

--   *** ------------------------------------
--  *** TEL_YDP
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydp` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `ydp_hotel_cd` VARCHAR(10) BINARY COMMENT '2;YDP施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_ydp` COMMENT '日本旅行_施設関連付け;';

--   *** ------------------------------------
--  *** TEL_YDP2
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydp2` (
    `ydp_hotel_cd` VARCHAR(10) BINARY COMMENT '1;YDP施設コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;更新施設コード;NULL:新規登録 NOT NULL:更新対象施設',
    `target_status` BIGINT COMMENT '3;処理対象;0:否処理対象 1:移行プログラム処理対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_ydp2` COMMENT '日本旅行施設移行対応;';

--   *** ------------------------------------
--  *** TEL_YDP2_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydp2_yahoo` (
    `ydp_hotel_cd` VARCHAR(10) BINARY COMMENT '1;YDP施設コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;更新施設コード;NULL:新規登録 NOT NULL:更新対象施設',
    `target_status` BIGINT COMMENT '3;処理対象;0:否処理対象 1:移行プログラム処理対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_ydp2_yahoo` COMMENT '日本旅行施設移行対応（ヤフーデータ提供）;';

--   *** ------------------------------------
--  *** TEL_YDP_BR
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydp_br` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `hotel_cd_ydp` VARCHAR(10) BINARY COMMENT '2;宿ぷらざ施設コード;',
    `trans_type` TINYINT COMMENT '3;移行タイプ;0:施設移行 1:プラン移行',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `hotel_ydp_br` COMMENT '宿ぷらざ施設関連テーブル;';

--   *** ------------------------------------
--  *** TEL_YDP_FACTORING
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydp_factoring` (
    `ydp_hotel_cd` VARCHAR(10) BINARY COMMENT '1;YDP施設コード;',
    `ydp_trust_no` VARCHAR(8) BINARY COMMENT '2;委託者番号;',
    `ydp_customer_no` VARCHAR(10) BINARY COMMENT '3;顧客番号;',
    `ydp_customer_nm` VARCHAR(256) BINARY COMMENT '4;顧客名（漢字）;',
    `ydp_customer_kn` VARCHAR(256) BINARY COMMENT '5;顧客名（カナ）;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `tel` VARCHAR(15) BINARY COMMENT '7;電話番号;ハイフン含む',
    `address` VARCHAR(300) BINARY COMMENT '8;住所;市区町村以下',
    `bank_account_kn` VARCHAR(90) BINARY COMMENT '9;口座名（カナ）;',
    `bank_cd` VARCHAR(4) BINARY COMMENT '10;銀行番号;',
    `bank_branch_cd` VARCHAR(3) BINARY COMMENT '11;支店番号;',
    `bank_account_type` TINYINT COMMENT '12;預金区分;1:普通 2:当座（4:貯蓄 9:その他）',
    `bank_account_no` VARCHAR(7) BINARY COMMENT '13;口座番号;',
    `new_cd` TINYINT COMMENT '14;新規コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '15;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `hotel_ydp_factoring` COMMENT '日本旅行引落口座一覧;';

--   *** ------------------------------------
--  *** TEL_YDP_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `hotel_ydp_match` (
    `ydp_hotel_cd` VARCHAR(10) BINARY COMMENT '1;YDP施設コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `hotel_ydp_match` COMMENT '宿ぷらざリダイレクト設定（施設）;';

--   *** ------------------------------------
--  *** URNAL
--   *** ------------------------------------
-- 
CREATE TABLE `journal` (
    `journal_cd` VARCHAR(18) BINARY COMMENT '1;ジャーナルコード;YYYYMMDDNNNNNNNNNN',
    `table_name` VARCHAR(30) BINARY COMMENT '2;テーブル名称;',
    `type` TINYINT COMMENT '3;処理タイプ;0:追加 1:更新 2:削除',
    `entry_dtm` DATETIME COMMENT '4;データ登録日時;',
    `data_xml` LONGTEXT COMMENT '5;データXML;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `journal` COMMENT 'ジャーナル;データ更新情報';

--   *** ------------------------------------
--  *** YWORDS_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `keywords_hotel` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `field_nm` VARCHAR(30) BINARY COMMENT '2;フィールド名称;カラム名(hotel_nm等）',
    `keyword` VARCHAR(4000) BINARY COMMENT '3;キーワード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `keywords_hotel` COMMENT '施設キーワード;';

--   *** ------------------------------------
--  *** YWORDS_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `keywords_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `field_nm` VARCHAR(30) BINARY COMMENT '3;フィールド名称;カラム名(hotel_nm等）',
    `keyword` VARCHAR(4000) BINARY COMMENT '4;キーワード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `keywords_plan` COMMENT 'プランキーワード;';

--   *** ------------------------------------
--  *** NDMARKS
--   *** ------------------------------------
-- 
CREATE TABLE `landmarks` (
    `landmark_id` INT COMMENT '1;ランドマークID;',
    `landmark_nm` VARCHAR(96) BINARY COMMENT '2;ランドマーク名称;',
    `landmark_kn` VARCHAR(300) BINARY COMMENT '3;ランドマークカナ名称;',
    `keyword` VARCHAR(300) BINARY COMMENT '4;キーワード;',
    `display_status` TINYINT COMMENT '5;表示ステータス;0:非表示 1:表示',
    `active_status` VARCHAR(1) BINARY COMMENT '6;削除ステータス;0:無効 1:有効',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '7;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '8;世界測地系-度-経度;JGD2000と同様',
    `distance` SMALLINT COMMENT '9;施設検索範囲;km 施設初期登録用',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `landmarks` COMMENT 'ランドマーク情報;新仕様。旧(mast_landmark)は将来廃棄予定';

--   *** ------------------------------------
--  *** NDMARK_BASIC_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `landmark_basic_info` (
    `landmark_id` INT COMMENT '1;ランドマークID;',
    `item_id` INT COMMENT '2;項目ID;',
    `info_detail` VARCHAR(999) BINARY COMMENT '3;詳細情報;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `landmark_basic_info` COMMENT 'ランドマーク基本情報;';

--   *** ------------------------------------
--  *** NDMARK_CAMPAIGN
--   *** ------------------------------------
-- 
CREATE TABLE `landmark_campaign` (
    `campaign_id` INT COMMENT '1;キャンペーンID;',
    `pref_id` TINYINT COMMENT '2;都道府県id;',
    `category_2nd_id` INT COMMENT '3;中カテゴリid;',
    `title` VARCHAR(45) BINARY COMMENT '4;タイトル;',
    `note` VARCHAR(150) BINARY COMMENT '5;コメント;',
    `start_date` DATETIME COMMENT '6;掲示開始年月日;',
    `end_date` DATETIME COMMENT '7;掲示終了年月日;',
    `url` VARCHAR(300) BINARY COMMENT '8;URL;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `landmark_campaign` COMMENT 'ランドマークキャンペーン情報;';

--   *** ------------------------------------
--  *** NDMARK_CATEGORY_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `landmark_category_match` (
    `landmark_id` INT COMMENT '1;ランドマークID;',
    `category_2nd_id` INT COMMENT '2;カテゴリID;',
    `order_no` BIGINT COMMENT '3;表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `landmark_category_match` COMMENT 'ランドマークカテゴリマッチ;';

--   *** ------------------------------------
--  *** NDMARK_PREF_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `landmark_pref_match` (
    `landmark_id` INT COMMENT '1;ランドマークID;',
    `pref_id` TINYINT COMMENT '2;都道府県ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `landmark_pref_match` COMMENT 'ランドマーク都道府県マッチ;';

--   *** ------------------------------------
--  *** G_ALERT_STOCK
--   *** ------------------------------------
-- 
CREATE TABLE `log_alert_stock` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '4;予約コード;YYYYMMNNNNNNNN',
    `rooms` SMALLINT COMMENT '5;部屋数;',
    `reserve_rooms` SMALLINT COMMENT '6;予約部屋数;',
    `vacant_rooms` SMALLINT COMMENT '7;在庫数;',
    `confirm_dtm` DATETIME COMMENT '8;確認日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `log_alert_stock` COMMENT '在庫アラートログ;';

--   *** ------------------------------------
--  *** G_AUTHORI
--   *** ------------------------------------
-- 
CREATE TABLE `log_authori` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '3;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '4;カード有効期限;',
    `demand_charge` INT COMMENT '5;売上料金;',
    `authori_dtm` DATETIME COMMENT '6;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(16) BINARY COMMENT '7;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '8;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `log_authori` COMMENT 'オーソリログ;オーソリ時に作成されます。';

--   *** ------------------------------------
--  *** G_BOUNCED_MAIL
--   *** ------------------------------------
-- 
CREATE TABLE `log_bounced_mail` (
    `log_bounced_mail_id` BIGINT COMMENT '1;送信失敗メールID;',
    `plain_mail` VARCHAR(300) BINARY COMMENT '2;送信失敗メールアドレス;',
    `status_flg` TINYINT COMMENT '3;処理フラグ;0:未処理 1:処理済み 9:対象無し',
    `target_table` VARCHAR(200) BINARY COMMENT '4;対象テーブル;',
    `target_column` VARCHAR(200) BINARY COMMENT '5;対象カラム;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `log_bounced_mail` COMMENT '送信失敗メールリスト;';

--   *** ------------------------------------
--  *** G_CANCEL
--   *** ------------------------------------
-- 
CREATE TABLE `log_cancel` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `cancel_dtm` DATETIME COMMENT '3;キャンセル日時;',
    `account_class` VARCHAR(20) BINARY COMMENT '4;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `partner_cd` VARCHAR(10) BINARY COMMENT '5;提携先コード;キャンセル処理をした提携先コード',
    `module_nm` VARCHAR(12) BINARY COMMENT '6;モジュール名称;',
    `action_nm` VARCHAR(16) BINARY COMMENT '7;手続き;cancel:キャンセル short：日程短縮 cancel_force:強制キャンセル（電話キャンセル）・無断不泊',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `log_cancel` COMMENT 'キャンセルログ;';

--   *** ------------------------------------
--  *** G_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `log_credit` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `branch_no` TINYINT COMMENT '3;枝番;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '4;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '5;カード有効期限;',
    `demand_charge` INT COMMENT '6;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '7;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '8;端末コード;03232:パワーホテル',
    `authori_dtm` DATETIME COMMENT '9;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(5) BINARY COMMENT '10;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '11;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `log_credit` COMMENT 'クレジットカード決済オーソリログ;';

--   *** ------------------------------------
--  *** G_CUSTOMER
--   *** ------------------------------------
-- 
CREATE TABLE `log_customer` (
    `customer_id` BIGINT COMMENT '1;精算先ID;連番、シーケンスは使用しない',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `section_nm` VARCHAR(76) BINARY COMMENT '3;部署名（請求書宛名）;',
    `person_post` VARCHAR(90) BINARY COMMENT '4;担当者部署名;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `tel` VARCHAR(15) BINARY COMMENT '6;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '7;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '8;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `log_customer` COMMENT '精算担当者情報履歴;';

--   *** ------------------------------------
--  *** G_EXTEND
--   *** ------------------------------------
-- 
CREATE TABLE `log_extend` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `after_ym` DATETIME COMMENT '2;ターゲット年月;',
    `start_dtm` DATETIME COMMENT '3;処理開始日時;',
    `end_dtm` DATETIME COMMENT '4;処理完了日時;',
    `after_months` TINYINT COMMENT '5;ターゲット月;',
    `email` VARCHAR(128) BINARY COMMENT '6;電子メールアドレス;',
    `email_type` TINYINT COMMENT '7;電子メールタイプ;0:パソコン用レイアウト 1:携帯端末用レイアウト',
    `email_notify` TINYINT COMMENT '8;電子メール通知可否;0:否通知 1:通知',
    `note` VARCHAR(3000) BINARY COMMENT '9;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_extend` COMMENT '自動登録ログ;';

--   *** ------------------------------------
--  *** G_EXTEND_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `log_extend_detail` (
    `after_ym` DATETIME COMMENT '1;ターゲット年月;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `partner_group_id` BIGINT COMMENT '5;提携先グループID;',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `action_dtm` DATETIME COMMENT '7;アクション日時;',
    `extend_condition` VARCHAR(12) BINARY COMMENT '8;自動延長状態;valid:正常 invalid:失敗 information:情報',
    `note` VARCHAR(3000) BINARY COMMENT '9;備考;',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `log_extend_detail` COMMENT '販売自動延長詳細ログ;';

--   *** ------------------------------------
--  *** G_EXTEND_DETAIL2
--   *** ------------------------------------
-- 
CREATE TABLE `log_extend_detail2` (
    `after_ym` DATETIME COMMENT '1;ターゲット年月;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '4;プランID;',
    `partner_group_id` BIGINT COMMENT '5;提携先グループID;',
    `capacity` SMALLINT COMMENT '6;人数;',
    `date_ymd` DATETIME COMMENT '7;宿泊日;',
    `action_dtm` DATETIME COMMENT '8;アクション日時;',
    `extend_condition` VARCHAR(12) BINARY COMMENT '9;自動延長状態;valid:正常 invalid:失敗 information:情報',
    `note` VARCHAR(3000) BINARY COMMENT '10;備考;',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス'
);

ALTER TABLE
    `log_extend_detail2` COMMENT '販売自動延長詳細ログ2;';

--   *** ------------------------------------
--  *** G_GROUP_BUYING
--   *** ------------------------------------
-- 
CREATE TABLE `log_group_buying` (
    `order_id` VARCHAR(14) BINARY COMMENT '1;共同購入注文ID;deal_id(YYYYMM9999) + 連番（4桁先頭0埋）',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '3;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '4;カード有効期限;',
    `demand_charge` INT COMMENT '5;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '6;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '7;端末コード;03232:パワーホテル 05825:受託クレジット',
    `authori_dtm` DATETIME COMMENT '8;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(5) BINARY COMMENT '9;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '10;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス'
);

ALTER TABLE
    `log_group_buying` COMMENT '共同購入オーソリログ;';

--   *** ------------------------------------
--  *** G_HOTEL_PERSON
--   *** ------------------------------------
-- 
CREATE TABLE `log_hotel_person` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `person_post` VARCHAR(96) BINARY COMMENT '3;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '4;担当者名称;',
    `person_tel` VARCHAR(15) BINARY COMMENT '5;担当者電話番号;ハイフン含む',
    `person_fax` VARCHAR(15) BINARY COMMENT '6;担当者ファックス番号;ハイフン含む',
    `person_email` VARCHAR(200) BINARY COMMENT '7;担当者電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `log_hotel_person` COMMENT '施設担当者情報履歴;';

--   *** ------------------------------------
--  *** G_NOTIFY
--   *** ------------------------------------
-- 
CREATE TABLE `log_notify` (
    `request_id` BIGINT COMMENT '1;リクエストID;',
    `notify_cd` VARCHAR(21) BINARY COMMENT '2;通知コード;予約通知FAXの原稿のヘッダー箇所の番号 （NNNNNNNNNN-NNNNNNNNNN:施設コード-通知No）',
    `notify_device` TINYINT DEFAULT 1 COMMENT '3;通知媒体;1:ファックス 2:電子メール',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `notify_condition` VARCHAR(16) BINARY COMMENT '5;通知状況;create:原稿作成済み request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '6;原稿作成日時;',
    `send_request_dtm` DATETIME COMMENT '7;送信依頼日時;',
    `send_accept_dtm` DATETIME COMMENT '8;送信受付日時;',
    `send_result_dtm` DATETIME COMMENT '9;送信処理完了日時;',
    `book_path` VARCHAR(128) BINARY COMMENT '10;原稿ファイルパス;',
    `notify_email` VARCHAR(500) BINARY COMMENT '11;通知電子メールアドレス;カンマ区切りで複数可',
    `notify_fax` VARCHAR(15) BINARY COMMENT '12;通知ファックス番号;ハイフン含む',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `log_notify` COMMENT '通知ログ;';

--   *** ------------------------------------
--  *** G_PLAN_STATUS_POOL2
--   *** ------------------------------------
-- 
CREATE TABLE `log_plan_status_pool2` (
    `id` BIGINT COMMENT '1;ID;YYYYMMDD＋6桁',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '4;部屋ID;',
    `partner_group_id` BIGINT COMMENT '5;提携先グループID;',
    `last_modify_dtm` DATETIME COMMENT '6;審査関連情報最終更新日時;',
    `judge_status` TINYINT COMMENT '7;審査状態;0:審査中 1:審査OK 2:審査NG 3:停止',
    `judge_message` VARCHAR(4000) BINARY COMMENT '8;審査メッセージ;',
    `judge_word` VARCHAR(1800) BINARY COMMENT '9;審査ワード;タブ区切り',
    `judge_condition` TINYINT COMMENT '10;審査区分;null : 審査中 0:システムNG 1:NGワード 2:NG表記（遠回しな言い方）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `judge_dtm` DATETIME,
    `judge_s_dtm` DATETIME
);

ALTER TABLE
    `log_plan_status_pool2` COMMENT 'プラン審査履歴;';

--   *** ------------------------------------
--  *** G_POWER
--   *** ------------------------------------
-- 
CREATE TABLE `log_power` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '3;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '4;カード有効期限;',
    `demand_charge` INT COMMENT '5;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '6;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '7;端末コード;03232:パワーホテル',
    `authori_dtm` DATETIME COMMENT '8;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(16) BINARY COMMENT '9;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '10;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `log_power` COMMENT 'パワーオーソリログ;オーソリ時に作成されます。';

--   *** ------------------------------------
--  *** G_RIZAPULI_NOTIFY
--   *** ------------------------------------
-- 
CREATE TABLE `log_rizapuli_notify` (
    `rizapuli_request_id` BIGINT COMMENT '1;リザプリリクエストID;',
    `notify_rizapuli_cd` VARCHAR(21) BINARY COMMENT '2;リザプリ通知コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `notify_condition` VARCHAR(16) BINARY COMMENT '4;通知状況;create:原稿作成済み request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '5;原稿作成日時;',
    `send_request_dtm` DATETIME COMMENT '6;送信依頼日時;',
    `send_accept_dtm` DATETIME COMMENT '7;送信受付日時;',
    `send_result_dtm` DATETIME COMMENT '8;送信処理完了日時;',
    `book_path` VARCHAR(128) BINARY COMMENT '9;原稿ファイルパス;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_rizapuli_notify` COMMENT '通知ログ（リザプリ）;リザプリ用通知ログ';

--   *** ------------------------------------
--  *** G_SECURITY_01
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_01` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_01` COMMENT 'セキュリティログ１月;';

--   *** ------------------------------------
--  *** G_SECURITY_02
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_02` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_02` COMMENT 'セキュリティログ２月;';

--   *** ------------------------------------
--  *** G_SECURITY_03
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_03` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_03` COMMENT 'セキュリティログ３月;';

--   *** ------------------------------------
--  *** G_SECURITY_04
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_04` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_04` COMMENT 'セキュリティログ４月;';

--   *** ------------------------------------
--  *** G_SECURITY_05
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_05` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_05` COMMENT 'セキュリティログ５月;';

--   *** ------------------------------------
--  *** G_SECURITY_06
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_06` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_06` COMMENT 'セキュリティログ６月;';

--   *** ------------------------------------
--  *** G_SECURITY_07
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_07` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_07` COMMENT 'セキュリティログ７月;';

--   *** ------------------------------------
--  *** G_SECURITY_08
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_08` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_08` COMMENT 'セキュリティログ８月;';

--   *** ------------------------------------
--  *** G_SECURITY_09
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_09` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_09` COMMENT 'セキュリティログ９月;';

--   *** ------------------------------------
--  *** G_SECURITY_10
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_10` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_10` COMMENT 'セキュリティログ１０月;';

--   *** ------------------------------------
--  *** G_SECURITY_11
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_11` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_11` COMMENT 'セキュリティログ１１月;';

--   *** ------------------------------------
--  *** G_SECURITY_12
--   *** ------------------------------------
-- 
CREATE TABLE `log_security_12` (
    `security_cd` VARCHAR(22) BINARY COMMENT '1;セキュリティログコード;YYYYMMDDNNNNNNNNNNNNNN',
    `session_id` VARCHAR(64) BINARY COMMENT '2;セッションID;',
    `tracking_id` VARCHAR(64) BINARY COMMENT '3;トラッキングID;',
    `request_dtm` DATETIME COMMENT '4;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `account_class` VARCHAR(20) BINARY COMMENT '5;アカウントクラス;staff:スタッフ hotel:施設 partner:提携先 supervisor:施設統括 member:会員 member_free:非会員',
    `account_key` VARCHAR(128) BINARY COMMENT '6;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `ip_address` VARCHAR(64) BINARY COMMENT '7;IPアドレス;社内からのアクセスも記録します。',
    `uri` VARCHAR(200) BINARY COMMENT '8;リクエストURI;',
    `parameter` LONGTEXT COMMENT '9;パラメータ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `log_security_12` COMMENT 'セキュリティログ１２月;';

--   *** ------------------------------------
--  *** ILMAG_V2_SET
--   *** ------------------------------------
-- 
CREATE TABLE `mailmag_v2_set` (
    `mailmag_v2_set_id` DECIMAL(22, 0) COMMENT '1;メールマガジン設定ID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `v2_flag` TINYINT COMMENT '3;毎日送信フラグ;0:送信しない 1:送信する',
    `v2_week_flag` TINYINT COMMENT '4;週一送信フラグ;0:送信しない 1:送信する',
    `input_type` TINYINT COMMENT '5;入力タイプ;1:予約画面 2:受信状態画面',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '6;予約コード;YYYYMMNNNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `mailmag_v2_set` COMMENT 'メールマガジン（バージョン2）送信設定;メールマガジン（バージョン2）の送信状態保存テーブル';

--   *** ------------------------------------
--  *** IL_MAGAZINE
--   *** ------------------------------------
-- 
CREATE TABLE `mail_magazine` (
    `mail_magazine_id` BIGINT COMMENT '1;メールマガジンID;',
    `send_system` VARCHAR(12) BINARY COMMENT '2;発行システム;reserve:ベストリザーブ dash:ベストリザーブ ダッシュ',
    `send_dtm` DATETIME COMMENT '3;送信日時;',
    `magazine_no` INT COMMENT '4;メールマガジン番号;メールマガジンの番号',
    `subject` VARCHAR(384) BINARY COMMENT '5;件名;メールマガジンのタイトル',
    `contents` LONGTEXT COMMENT '6;本文;メールマガジンの本文',
    `issue_ymd` DATETIME COMMENT '7;発行日;',
    `issue_status` TINYINT COMMENT '8;発行状態;0:発行待ち 1:作成発行中 2:発行済み',
    `issue_estimate` INT COMMENT '9;発行予定数;',
    `issue_cnt` INT COMMENT '10;発行数;',
    `start_dtm` DATETIME COMMENT '11;処理開始日時;',
    `end_dtm` DATETIME COMMENT '12;処理終了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `mail_magazine` COMMENT 'メールマガジン;メールマガジンのデータです。';

--   *** ------------------------------------
--  *** IL_MAGAZINE_BACK_NUMBER
--   *** ------------------------------------
-- 
CREATE TABLE `mail_magazine_back_number` (
    `magazine_no` INT COMMENT '1;メールマガジン番号;メールマガジンの番号',
    `subject` VARCHAR(384) BINARY COMMENT '2;件名;メールマガジンのタイトル',
    `contents` LONGTEXT COMMENT '3;本文;',
    `issue_ymd` DATETIME COMMENT '4;発行日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mail_magazine_back_number` COMMENT 'メールマガジンバックナンバー;バックナンバー';

--   *** ------------------------------------
--  *** IL_MAGAZINE_SIMPLE
--   *** ------------------------------------
-- 
CREATE TABLE `mail_magazine_simple` (
    `mail_magazine_simple_id` BIGINT COMMENT '1;汎用メールマガジンID;',
    `send_dtm` DATETIME COMMENT '2;送信日時;送信処理が開始される日時（実際の起動はCronのサイクルによる）',
    `magazine_no` BIGINT COMMENT '3;メールマガジン番号;メールマガジンの番号',
    `subject` VARCHAR(384) BINARY COMMENT '4;件名;メールマガジンのタイトル',
    `contents` LONGTEXT COMMENT '5;本文;',
    `from_mail` VARCHAR(200) BINARY COMMENT '6;送信元アドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `from_nm` VARCHAR(300) BINARY COMMENT '7;送信元名称;',
    `to_mail` VARCHAR(200) BINARY COMMENT '8;宛先アドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `to_nm` VARCHAR(300) BINARY COMMENT '9;宛先名称;',
    `issue_status` TINYINT COMMENT '10;発行状態;0:発行待ち 1:作成発行中 2:発行済み',
    `issue_estimate` INT COMMENT '11;発行予定数;',
    `issue_cnt` INT COMMENT '12;発行数;メルマガ送信前にメルマガ受付否に設定された場合、発行予定数よりも少なくなります。',
    `start_dtm` DATETIME COMMENT '13;処理開始日時;',
    `end_dtm` DATETIME COMMENT '14;処理終了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;',
    `send_target_type` TINYINT
);

ALTER TABLE
    `mail_magazine_simple` COMMENT '汎用メールマガジン;差し込みデータなし＆BCCでの配信';

--   *** ------------------------------------
--  *** IL_MAGAZINE_SIMPLE_BCC
--   *** ------------------------------------
-- 
CREATE TABLE `mail_magazine_simple_bcc` (
    `mail_magazine_simple_id` BIGINT COMMENT '1;汎用メールマガジンID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `email` VARCHAR(200) BINARY COMMENT '3;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `create_status` TINYINT COMMENT '4;メルマガ原稿作成状態;0:作成待ち 1:作成済み',
    `create_dtm` DATETIME COMMENT '5;メルマガ原稿作成日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mail_magazine_simple_bcc` COMMENT '汎用メールマガジン宛先;汎用メールマガジン配信先';

--   *** ------------------------------------
--  *** IL_MAGAZINE_SIMPLE_COND
--   *** ------------------------------------
-- 
CREATE TABLE `mail_magazine_simple_cond` (
    `mail_magazine_simple_id` BIGINT COMMENT '1;汎用メールマガジンID;',
    `selected_gender` VARCHAR(2) BINARY DEFAULT '11' Comment '2;対象性別;送信対象となる性別(0：対象外 1：対象)　1文字目：男性 2文字目：女性',
    `selected_age` VARCHAR(8) BINARY DEFAULT '11111111' Comment '3;対象世代;送信対象となる世代（0：対象外 1：対象） 左から1文字目1文字目が10代',
    `selected_location` VARCHAR(47) BINARY DEFAULT '11111111111111111111111111111111111111111111111' Comment '4;対象地域;送信対象となる都道府県（0：対象外 1：対象） 桁数がpref_idとなっている（例：左から1文字目が北海道の状態）',
    `where_string` VARCHAR(3000) BINARY COMMENT '5;対象を抽出するWHERE句;対象の一覧を抽出するWHERE句',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mail_magazine_simple_cond` COMMENT '汎用メールマガジン送信対象;汎用メールマガジン送信対象の条件';

--   *** ------------------------------------
--  *** ST_AMEDAS
--   *** ------------------------------------
-- 
CREATE TABLE `mast_amedas` (
    `jbr_id` VARCHAR(5) BINARY COMMENT '1;JBR ID;',
    `pref_nm` VARCHAR(50) BINARY COMMENT '2;都道府県名称;',
    `city_nm` VARCHAR(50) BINARY COMMENT '3;郡名称;',
    `ward_nm` VARCHAR(50) BINARY COMMENT '4;市区町村名称;',
    `amedas_cd` VARCHAR(5) BINARY COMMENT '5;観測所番号;',
    `amedas_nm` VARCHAR(50) BINARY COMMENT '6;アメダス観測所名称;',
    `pref_id` TINYINT COMMENT '7;都道府県ID;',
    `city_id` DECIMAL(20, 0) COMMENT '8;市ID;',
    `ward_id` DECIMAL(20, 0) COMMENT '9;区ID;',
    `town_nm` VARCHAR(50) BINARY COMMENT '10;町村名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `mast_amedas` COMMENT 'アメダス設置位置;JBR提供のアメダス設置位置一覧より展開';

--   *** ------------------------------------
--  *** ST_AREA
--   *** ------------------------------------
-- 
CREATE TABLE `mast_area` (
    `area_id` SMALLINT COMMENT '1;地域ID;',
    `parent_area_id` SMALLINT COMMENT '2;親地域ID;',
    `area_nm` VARCHAR(128) BINARY COMMENT '3;地域名称;23区中央とか',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `area_type` TINYINT COMMENT '5;地域タイプ;0:日本全域 1:大エリア 2:中エリア 3:小エリア',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mast_area` COMMENT '地域;';

--   *** ------------------------------------
--  *** ST_AREA_LANDMARK
--   *** ------------------------------------
-- 
CREATE TABLE `mast_area_landmark` (
    `id` INT COMMENT '1;id;',
    `landmark_id` INT COMMENT '2;ランドマークID;',
    `apcw_type` TINYINT COMMENT '3;IDタイプ;0：地域ID 1：都道府県ID 2：市ID 3：区ID',
    `various_id` DECIMAL(20, 0) COMMENT '4;汎用ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_area_landmark` COMMENT '地域別ランドマーク;';

--   *** ------------------------------------
--  *** ST_AREA_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `mast_area_match` (
    `id` INT COMMENT '1;ID;',
    `area_id` DECIMAL(20, 0) COMMENT '2;地域ID;',
    `pref_id` TINYINT COMMENT '3;都道府県ID;',
    `city_id` DECIMAL(20, 0) COMMENT '4;市ID;',
    `ward_id` DECIMAL(20, 0) COMMENT '5;区ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mast_area_match` COMMENT '地域市区マッチ;';

--   *** ------------------------------------
--  *** ST_AREA_NEARBY
--   *** ------------------------------------
-- 
CREATE TABLE `mast_area_nearby` (
    `id` INT COMMENT '1;ID;',
    `area_id` SMALLINT COMMENT '2;地域ID;',
    `nearby_area_id` SMALLINT COMMENT '3;近隣地域ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_area_nearby` COMMENT '近隣エリアマッチ;';

--   *** ------------------------------------
--  *** ST_AREA_STATION
--   *** ------------------------------------
-- 
CREATE TABLE `mast_area_station` (
    `id` INT COMMENT '1;ID;',
    `station_group_id` VARCHAR(7) BINARY COMMENT '2;駅グループID;駅グループコードが示す駅コードがターミナル駅となります。',
    `apcw_type` TINYINT COMMENT '3;IDタイプ;0：地域ID 1：都道府県ID 2：市ID 3：区ID',
    `various_id` DECIMAL(20, 0) COMMENT '4;汎用ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_area_station` COMMENT '地域別駅;';

--   *** ------------------------------------
--  *** ST_AREA_SURVEY
--   *** ------------------------------------
-- 
CREATE TABLE `mast_area_survey` (
    `survey_class` VARCHAR(12) BINARY COMMENT '1;測量区分;area:地域 pref:都道府県 city:市 ward:区',
    `survey_cd` INT COMMENT '2;測量コード;測量区分によって地域、都道府県、市、区のコードが割り当てられています。',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '3;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '4;世界測地系-度-経度;JGD2000と同様',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_area_survey` COMMENT '地域ごとの緯度経度;';

--   *** ------------------------------------
--  *** ST_BANK
--   *** ------------------------------------
-- 
CREATE TABLE `mast_bank` (
    `bank_cd` VARCHAR(4) BINARY COMMENT '1;銀行コード;数字4文字',
    `bank_nm` VARCHAR(150) BINARY COMMENT '2;銀行名称;',
    `bank_kn` VARCHAR(45) BINARY COMMENT '3;銀行名称（カナ）;半角カタカナ15文字',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_bank` COMMENT '銀行マスタ;';

--   *** ------------------------------------
--  *** ST_BANK_BRANCH
--   *** ------------------------------------
-- 
CREATE TABLE `mast_bank_branch` (
    `bank_cd` VARCHAR(4) BINARY COMMENT '1;銀行コード;数字4文字',
    `bank_branch_cd` VARCHAR(3) BINARY COMMENT '2;支店コード;数字3文字',
    `bank_branch_nm` VARCHAR(150) BINARY COMMENT '3;支店名称;',
    `bank_branch_kn` VARCHAR(45) BINARY COMMENT '4;支店名称（カナ）;半角カタカナ15文字',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_bank_branch` COMMENT '銀行支店マスタ;';

--   *** ------------------------------------
--  *** ST_CALENDAR
--   *** ------------------------------------
-- 
CREATE TABLE `mast_calendar` (
    `date_ymd` DATETIME COMMENT '1;日付;',
    `holiday_nm` VARCHAR(192) BINARY COMMENT '2;祝祭日名称;',
    `ymd` VARCHAR(17) BINARY COMMENT '3;年月日;YYYY年MM月DD日',
    `ym` VARCHAR(12) BINARY COMMENT '4;年月;YYYY年MM月',
    `md` VARCHAR(10) BINARY COMMENT '5;月日;MM月DD日',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mast_calendar` COMMENT 'カレンダーマスタ;';

--   *** ------------------------------------
--  *** ST_CARD
--   *** ------------------------------------
-- 
CREATE TABLE `mast_card` (
    `card_id` TINYINT COMMENT '1;カードID;',
    `card_type` VARCHAR(20) BINARY COMMENT '2;カードタイプ;credit:クレジットカード prepaid:プリペイド（デビッド） e-money:電子マネー（suica)',
    `card_nm` VARCHAR(45) BINARY COMMENT '3;カード名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_card` COMMENT 'カードマスタ;';

--   *** ------------------------------------
--  *** ST_CITY
--   *** ------------------------------------
-- 
CREATE TABLE `mast_city` (
    `city_id` DECIMAL(20, 0) COMMENT '1;市ID;',
    `pref_id` TINYINT COMMENT '2;都道府県ID;',
    `city_nm` VARCHAR(60) BINARY COMMENT '3;市名称;',
    `pref_city_nm` VARCHAR(75) BINARY COMMENT '4;都道府県市名称;',
    `order_no` INT COMMENT '5;市表示順序;',
    `city_cd` VARCHAR(20) BINARY COMMENT '6;市コード;JIS X 0402',
    `delete_ymd` DATETIME COMMENT '7;削除日;削除日以降は管理画面上に表示されない。（注意を促す？）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `mast_city` COMMENT '市マスタ;';

--   *** ------------------------------------
--  *** ST_HOLIDAY
--   *** ------------------------------------
-- 
CREATE TABLE `mast_holiday` (
    `holiday` DATETIME COMMENT '1;祝祭日;',
    `holiday_nm` VARCHAR(192) BINARY COMMENT '2;祝祭日名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mast_holiday` COMMENT '祝祭日マスタ;';

--   *** ------------------------------------
--  *** ST_HOTEL_ELEMENT
--   *** ------------------------------------
-- 
CREATE TABLE `mast_hotel_element` (
    `element_id` SMALLINT COMMENT '1;要素ID;',
    `element_type` VARCHAR(10) BINARY COMMENT '2;要素タイプ;hotel:施設関係 room:部屋関係 amenity:アメニティ service:サービス vicinity:周辺',
    `element_nm` VARCHAR(144) BINARY COMMENT '3;要素名称;',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `note` VARCHAR(150) BINARY COMMENT '5;コメント;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mast_hotel_element` COMMENT '施設要素マスタ;';

--   *** ------------------------------------
--  *** ST_HOTEL_ELEMENT_VALUE
--   *** ------------------------------------
-- 
CREATE TABLE `mast_hotel_element_value` (
    `element_id` SMALLINT COMMENT '1;要素ID;',
    `element_value_id` TINYINT COMMENT '2;値ID;',
    `element_value_text` VARCHAR(36) BINARY COMMENT '3;値名称;',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_hotel_element_value` COMMENT '施設値選択マスタ;';

--   *** ------------------------------------
--  *** ST_KEYWORDS
--   *** ------------------------------------
-- 
CREATE TABLE `mast_keywords` (
    `keyword_id` INT COMMENT '1;キーワードID;',
    `keyword` VARCHAR(45) BINARY COMMENT '2;キーワード;',
    `keyword_status` TINYINT COMMENT '3;キーワードステータス;0:無効 1:有効',
    `action_status` TINYINT COMMENT '4;処理状態;0:未処理 1:処理対象 2:処理完了',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_keywords` COMMENT 'キーワード辞書マスタ;プランに含まれるキーワード一覧';

--   *** ------------------------------------
--  *** ST_KEYWORD_GROUP
--   *** ------------------------------------
-- 
CREATE TABLE `mast_keyword_group` (
    `keyword_group_id` INT COMMENT '1;キーワードグループID;',
    `keyword_group_nm` VARCHAR(45) BINARY COMMENT '2;キーワードグループ名称;',
    `display_type` TINYINT COMMENT '3;表示タイプ;0:提携先管理画面に表示しない 1:提携先管理画面に表示する',
    `keyword_group_status` TINYINT COMMENT '4;キーワードグループステータス;0:無効 1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_keyword_group` COMMENT 'キーワードグループマスタ;';

--   *** ------------------------------------
--  *** ST_KEYWORD_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `mast_keyword_match` (
    `keyword_group_id` INT COMMENT '1;キーワードグループID;',
    `keyword_id` INT COMMENT '2;キーワードID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mast_keyword_match` COMMENT 'キーワードグループマッチ;';

--   *** ------------------------------------
--  *** ST_LANDMARK
--   *** ------------------------------------
-- 
CREATE TABLE `mast_landmark` (
    `landmark_id` INT COMMENT '1;ランドマークID;',
    `landmark_nm` VARCHAR(96) BINARY COMMENT '2;ランドマーク名称;',
    `landmark_type` INT COMMENT '5;ランドマークタイプ;1:テーマパーク 2:温泉 4:官公庁 8:球場・スタジアム 16:空港 32:劇場・ホール 64:城・庭園 128:神社 256:水族館 512:東京ホットスポット 1024:動物園 2048:複合施設 4096:歴史建造物',
    `order_no` SMALLINT COMMENT '6;ランドマーク表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '9;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '10;世界測地系-度-経度;JGD2000と同様',
    `display_status` TINYINT COMMENT '7;表示ステータス;0:非表示 1:表示',
    `landmark_kn` VARCHAR(300) BINARY COMMENT '3;ランドマークカナ名称;',
    `use_type` TINYINT COMMENT '8;利用タイプ;0:ピックアップ 1:一覧 2:キーワード検索のみ',
    `keyword` VARCHAR(300) BINARY COMMENT '4;キーワード;'
);

ALTER TABLE
    `mast_landmark` COMMENT 'ランドマークマスタ;';

--   *** ------------------------------------
--  *** ST_LANDMARK_BASIC
--   *** ------------------------------------
-- 
CREATE TABLE `mast_landmark_basic` (
    `item_id` INT COMMENT '1;項目ID;',
    `item_nm` VARCHAR(96) BINARY COMMENT '2;項目名称;',
    `input_type` SMALLINT COMMENT '3;入力種別;0:テキスト入力(汎用)　1:地域選択',
    `max_length` INT COMMENT '4;最大文字数;',
    `order_no` BIGINT COMMENT '5;表示順序;',
    `necessary_status` TINYINT COMMENT '6;必須入力ステータス;1:必須入力 0:空欄可',
    `notactive_status` VARCHAR(1) BINARY COMMENT '7;削除ステータス;0:有効 1:無効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `mast_landmark_basic` COMMENT 'ランドマーク基本情報項目マスタ;';

--   *** ------------------------------------
--  *** ST_LANDMARK_CATEGORY_1ST
--   *** ------------------------------------
-- 
CREATE TABLE `mast_landmark_category_1st` (
    `category_1st_id` INT COMMENT '1;大カテゴリID;',
    `category_1st_nm` VARCHAR(96) BINARY COMMENT '2;大カテゴリ名称;',
    `order_no` BIGINT COMMENT '3;表示順序;',
    `active_status` VARCHAR(1) BINARY COMMENT '4;削除ステータス;0:無効 1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_landmark_category_1st` COMMENT 'ランドマーク大カテゴリマスタ;';

--   *** ------------------------------------
--  *** ST_LANDMARK_CATEGORY_2ND
--   *** ------------------------------------
-- 
CREATE TABLE `mast_landmark_category_2nd` (
    `category_2nd_id` INT COMMENT '1;中カテゴリID;',
    `category_2nd_nm` VARCHAR(96) BINARY COMMENT '2;中カテゴリ名称;',
    `category_1st_id` INT COMMENT '3;大カテゴリID;',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `active_status` VARCHAR(1) BINARY COMMENT '5;削除ステータス;0:無効 1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mast_landmark_category_2nd` COMMENT 'ランドマーク中カテゴリマスタ;';

--   *** ------------------------------------
--  *** ST_MONEY_SCHEDULE
--   *** ------------------------------------
-- 
CREATE TABLE `mast_money_schedule` (
    `id` INT COMMENT '1;ID;',
    `schedule_nm` VARCHAR(32) BINARY COMMENT '2;スケジュール名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mast_money_schedule` COMMENT '経理関係スケジュールマスタ;';

--   *** ------------------------------------
--  *** ST_PLAN_ELEMENT
--   *** ------------------------------------
-- 
CREATE TABLE `mast_plan_element` (
    `element_id` SMALLINT COMMENT '1;要素ID;',
    `element_type` VARCHAR(10) BINARY COMMENT '2;要素タイプ;room:部屋 plan:プラン',
    `element_nm` VARCHAR(144) BINARY COMMENT '3;要素名称;',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `note` VARCHAR(150) BINARY COMMENT '5;コメント;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mast_plan_element` COMMENT 'プラン要素マスタ;';

--   *** ------------------------------------
--  *** ST_PLAN_ELEMENT_VALUE
--   *** ------------------------------------
-- 
CREATE TABLE `mast_plan_element_value` (
    `element_id` SMALLINT COMMENT '1;要素ID;',
    `element_value_id` TINYINT COMMENT '2;値ID;',
    `element_value_text` VARCHAR(36) BINARY COMMENT '3;値名称;',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `mast_plan_element_value` COMMENT 'プラン値選択マスタ;';

--   *** ------------------------------------
--  *** ST_PREF
--   *** ------------------------------------
-- 
CREATE TABLE `mast_pref` (
    `pref_id` TINYINT COMMENT '1;都道府県ID;',
    `region_id` TINYINT COMMENT '2;地方ID;',
    `pref_nm` VARCHAR(15) BINARY COMMENT '3;都道府県名称;',
    `pref_ns` VARCHAR(10) BINARY COMMENT '4;都道府県略称;',
    `order_no` TINYINT COMMENT '5;都道府県表示順序;',
    `pref_cd` VARCHAR(2) BINARY COMMENT '6;都道府県コード;JIS X 0401',
    `delete_ymd` DATETIME COMMENT '7;削除日;削除日以降は管理画面上に表示されない。（注意を促す？）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `mast_pref` COMMENT '都道府県マスタ;';

--   *** ------------------------------------
--  *** ST_RECOMMEND
--   *** ------------------------------------
-- 
CREATE TABLE `mast_recommend` (
    `recommend_id` TINYINT,
    `recommend_nm` VARCHAR(30) BINARY,
    `order_no` BIGINT,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** ST_REGION
--   *** ------------------------------------
-- 
CREATE TABLE `mast_region` (
    `region_id` TINYINT COMMENT '1;地方ID;',
    `region_nm` VARCHAR(75) BINARY COMMENT '2;地方名称;',
    `order_no` TINYINT COMMENT '3;地方表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_region` COMMENT '地方マスタ;';

--   *** ------------------------------------
--  *** ST_REVIEW
--   *** ------------------------------------
-- 
CREATE TABLE `mast_review` (
    `review_id` TINYINT COMMENT '1;クチコミ項目;部屋:１ バス・トイレ：2 食事（朝食・夕食）:3 接客・サービス:4 料金:5 立地:6 総合:0',
    `review_nm` VARCHAR(50) BINARY COMMENT '2;クチコミ名称;',
    `order_no` TINYINT COMMENT '3;表示順;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_review` COMMENT 'クチコミ項目;';

--   *** ------------------------------------
--  *** ST_ROUTE
--   *** ------------------------------------
-- 
CREATE TABLE `mast_route` (
    `route_id` INT COMMENT '1;路線ID;',
    `route_nm` VARCHAR(75) BINARY COMMENT '2;路線名称;',
    `railway_ns` VARCHAR(36) BINARY COMMENT '3;鉄道会社短縮名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_route` COMMENT '路線マスタ;';

--   *** ------------------------------------
--  *** ST_ROUTES
--   *** ------------------------------------
-- 
CREATE TABLE `mast_routes` (
    `route_id` VARCHAR(5) BINARY COMMENT '1;路線ID;先頭文字「B」は、ベストリザーブオリジナル路線、 その他「０〜9｣は駅データの路線です。',
    `route_nm` VARCHAR(240) BINARY COMMENT '2;路線名称;',
    `order_no` BIGINT COMMENT '6;路線表示順序;「並び順」＋「路線コード」で昇順',
    `railway_nm` VARCHAR(96) BINARY COMMENT '19;鉄道概要名称;未使用',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;',
    `route_kn` VARCHAR(240) BINARY COMMENT '3;路線カナ名称;',
    `route_tn` VARCHAR(240) BINARY COMMENT '4;路線正式名称;',
    `company_cd` BIGINT COMMENT '5;事業者コード;',
    `display_status` TINYINT COMMENT '7;表示ステータス;0:非表示 1:表示',
    `route_status` TINYINT COMMENT '8;路線状態;0:運用中 1:運用前 2:廃止',
    `route_color_cd` VARCHAR(6) BINARY COMMENT '9;路線カラーコード;',
    `route_color_nm` VARCHAR(30) BINARY COMMENT '10;路線カラー名称;',
    `route_type` TINYINT COMMENT '11;路線タイプ;0:その他 1:新幹線 2:一般 3:地下鉄 4:市電・路面電車 5:モノレール・新交通',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '12;路線表示時の中央緯度;',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '13;路線表示時の中央経度;',
    `zoom` SMALLINT COMMENT '14;路線表示時の倍率;GoogleMap基準'
);

ALTER TABLE
    `mast_routes` COMMENT '路線マスタ;ver2';

--   *** ------------------------------------
--  *** ST_STATION
--   *** ------------------------------------
-- 
CREATE TABLE `mast_station` (
    `station_id` INT COMMENT '1;駅ID;',
    `station_nm` VARCHAR(75) BINARY COMMENT '2;駅名称;',
    `station_kn` VARCHAR(96) BINARY COMMENT '3;駅名称かな;',
    `pref_id` TINYINT COMMENT '4;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '5;住所;市区町村以下',
    `wgs_lat` VARCHAR(16) BINARY COMMENT '6;世界測地系-度分秒-緯度;JGD2000と同様',
    `wgs_lng` VARCHAR(16) BINARY COMMENT '7;世界測地系-度分秒-経度;JGD2000と同様',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '8;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '9;世界測地系-度-経度;JGD2000と同様',
    `route_id` INT COMMENT '10;路線ID;',
    `junction_id` INT COMMENT '11;ジャンクションID;',
    `order_no` INT COMMENT '12;駅順序;路線内での順序',
    `delete_ymd` DATETIME COMMENT '13;削除日;削除日以降は管理画面上に表示されない。（注意を促す？）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `mast_station` COMMENT '駅マスタ;';

--   *** ------------------------------------
--  *** ST_STATIONS
--   *** ------------------------------------
-- 
CREATE TABLE `mast_stations` (
    `station_id` VARCHAR(7) BINARY COMMENT '1;駅ID;先頭文字「B」は、ベストリザーブオリジナル駅、 その他「０〜9｣は駅データの駅です。',
    `station_nm` VARCHAR(240) BINARY COMMENT '4;駅名称;',
    `order_no` BIGINT COMMENT '9;表示順序;',
    `display_status` TINYINT COMMENT '10;表示ステータス;0:非表示 1:表示',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '15;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '16;世界測地系-度-経度;JGD2000と同様',
    `station_type` TINYINT COMMENT '8;駅タイプ;1:通常駅 2:地下鉄駅 9:空港',
    `pref_id` TINYINT COMMENT '13;都道府県ID;',
    `station_group_id` VARCHAR(7) BINARY COMMENT '2;駅グループID;駅グループコードが示す駅コードがターミナル駅となります。',
    `route_id` VARCHAR(5) BINARY COMMENT '3;路線ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '21;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '22;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '23;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '24;更新日時;',
    `station_kn` VARCHAR(240) BINARY COMMENT '5;駅名称カナ;カタカナ',
    `station_rn` VARCHAR(200) BINARY COMMENT '6;駅名称ローマ字;',
    `station_status` TINYINT COMMENT '11;駅状態;0:運用中 1:運用前 2:廃止',
    `postal_cd` VARCHAR(8) BINARY COMMENT '12;郵便番号;ハイフン含む',
    `address` VARCHAR(300) BINARY COMMENT '14;住所;市区町村以下',
    `altitube` SMALLINT COMMENT '17;駅の座標の標高;メートル',
    `altitube_home` SMALLINT COMMENT '18;駅のホームの標高;メートル',
    `open_ymd` DATETIME COMMENT '19;開業年月日;',
    `close_ymd` DATETIME COMMENT '20;廃止年月日;',
    `keyword` VARCHAR(240) BINARY COMMENT '7;キーワード;'
);

ALTER TABLE
    `mast_stations` COMMENT '駅マスタ;ver2';

--   *** ------------------------------------
--  *** ST_STATION_COMPANY
--   *** ------------------------------------
-- 
CREATE TABLE `mast_station_company` (
    `company_cd` BIGINT COMMENT '1;事業者コード;',
    `railway_cd` TINYINT COMMENT '2;鉄道コード;1:JR 2:東武 3:西武 4:京成 5:京王 6:小田急 7:東急 8:京急 9:メトロ 10:相鉄 11:名鉄 12:近鉄 13:南海 14:京阪 15:阪急 16:阪神 17:西鉄 18:その他',
    `company_nm` VARCHAR(240) BINARY COMMENT '3;事業者名称;',
    `company_kn` VARCHAR(240) BINARY COMMENT '4;事業者名称カナ;',
    `company_tn` VARCHAR(240) BINARY COMMENT '5;事業者正式名称;',
    `company_sn` VARCHAR(240) BINARY COMMENT '6;事業者略称;',
    `company_url` VARCHAR(100) BINARY COMMENT '7;Webサイト;',
    `company_type` TINYINT COMMENT '8;事業者区分;0:その他 1:JR 2:大手私鉄 3:準大手私鉄',
    `company_status` TINYINT COMMENT '9;事業者状態;0:運用中 1:運用前 2:廃止',
    `order_no` BIGINT COMMENT '10;表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `mast_station_company` COMMENT '駅事業者マスタ;';

--   *** ------------------------------------
--  *** ST_STATION_JUNCTION
--   *** ------------------------------------
-- 
CREATE TABLE `mast_station_junction` (
    `junction_id` INT COMMENT '1;ジャンクションID;',
    `junction_nm` VARCHAR(75) BINARY COMMENT '2;ジャンクション名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mast_station_junction` COMMENT '駅ジャンクション;';

--   *** ------------------------------------
--  *** ST_TAX
--   *** ------------------------------------
-- 
CREATE TABLE `mast_tax` (
    `tax_id` INT COMMENT '1;消費税率ID;',
    `accept_s_ymd` DATETIME COMMENT '2;開始日;',
    `tax` TINYINT COMMENT '3;消費税率;5%の場合は5',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_tax` COMMENT '消費税率マスタ;';

--   *** ------------------------------------
--  *** ST_VR_HOTEL_CATEGORY
--   *** ------------------------------------
-- 
CREATE TABLE `mast_vr_hotel_category` (
    `category_cd` VARCHAR(10) BINARY COMMENT '1;カテゴリコード;',
    `element_id` BIGINT COMMENT '2;要素ID;',
    `element_nm` VARCHAR(255) BINARY COMMENT '3;要素名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_vr_hotel_category` COMMENT 'ベンチャーリパブリック連携施設区分マスタ;ベンチャーリパブリックとのデータ連携用の施設区分マスタ';

--   *** ------------------------------------
--  *** ST_VR_ITEM
--   *** ------------------------------------
-- 
CREATE TABLE `mast_vr_item` (
    `item_id` BIGINT COMMENT '1;用途ID;',
    `table_id` BIGINT COMMENT '2;対象テーブルID;1:mast_hotel_element、2:mast_plan_element、3:mast_card',
    `category_id` BIGINT COMMENT '3;カテゴリID;',
    `category_value_id` BIGINT COMMENT '4;値ID;',
    `element_id` BIGINT COMMENT '5;要素ID;',
    `element_nm` VARCHAR(255) BINARY COMMENT '6;要素名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `mast_vr_item` COMMENT 'ベンチャーリパブリック連携用途マスタ;ベンチャーリパブリックとのデータ連携用の用途マスタ';

--   *** ------------------------------------
--  *** ST_VR_ROOM_TYPE
--   *** ------------------------------------
-- 
CREATE TABLE `mast_vr_room_type` (
    `category_id` BIGINT COMMENT '1;カテゴリID;',
    `element_id` BIGINT COMMENT '2;要素ID;',
    `element_nm` VARCHAR(255) BINARY COMMENT '3;要素名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_vr_room_type` COMMENT 'ベンチャーリパブリック連携部屋タイプマスタ;ベンチャーリパブリックとのデータ連携用の部屋タイプマスタ';

--   *** ------------------------------------
--  *** ST_WARD
--   *** ------------------------------------
-- 
CREATE TABLE `mast_ward` (
    `ward_id` DECIMAL(20, 0) COMMENT '1;区ID;',
    `pref_id` TINYINT COMMENT '2;都道府県ID;',
    `city_id` DECIMAL(20, 0) COMMENT '3;市ID;',
    `ward_cd` VARCHAR(50) BINARY COMMENT '4;区コード;',
    `ward_nm` VARCHAR(60) BINARY COMMENT '5;区名称;',
    `city_ward_nm` VARCHAR(75) BINARY COMMENT '6;市区名称;',
    `pref_city_ward_nm` VARCHAR(150) BINARY COMMENT '7;都道府県市区名称;',
    `order_no` INT COMMENT '8;区表示順序;',
    `delete_ymd` DATETIME COMMENT '9;削除日;削除日以降は管理画面上に表示されない。（注意を促す？）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `mast_ward` COMMENT '区マスタ;';

--   *** ------------------------------------
--  *** ST_WARDZONE
--   *** ------------------------------------
-- 
CREATE TABLE `mast_wardzone` (
    `wardzone_id` DECIMAL(20, 0) COMMENT '1;地域ID;',
    `wardzone_nm` VARCHAR(96) BINARY COMMENT '2;地域名称;23区中央とか',
    `order_no` INT COMMENT '3;地域表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `mast_wardzone` COMMENT '区割マスタ;';

--   *** ------------------------------------
--  *** ST_WARDZONE_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `mast_wardzone_detail` (
    `wardzone_id` DECIMAL(20, 0) COMMENT '1;地域ID;',
    `ward_id` DECIMAL(20, 0) COMMENT '2;区ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mast_wardzone_detail` COMMENT '区割の所属;地区と区の関連付け';

--   *** ------------------------------------
--  *** DIA
--   *** ------------------------------------
-- 
CREATE TABLE `media` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `media_no` SMALLINT COMMENT '2;メディアNo;',
    `order_no` SMALLINT COMMENT '3;管理表示順序;管理画面上の表示順序です',
    `label_cd` VARCHAR(5) BINARY COMMENT '4;ラベルコード;施設が画像を管理するために用いるラベル 左から外観、地図、館内、客室、その他（1:有効 0:無効）',
    `title` VARCHAR(300) BINARY COMMENT '5;画像タイトル;',
    `file_nm` VARCHAR(100) BINARY COMMENT '6;ファイル名称;',
    `mime_type` VARCHAR(50) BINARY COMMENT '7;メディアタイプ;image/gif image/jpeg video/quicktime など',
    `width` SMALLINT COMMENT '8;幅;',
    `height` SMALLINT COMMENT '9;高さ;',
    `upload_dtm` DATETIME COMMENT '10;アップロード日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `media` COMMENT 'メディア;';

--   *** ------------------------------------
--  *** DIA_ORG
--   *** ------------------------------------
-- 
CREATE TABLE `media_org` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `media_no` SMALLINT COMMENT '2;メディアNo;ベストリザーブは3文字',
    `org_file_nm` VARCHAR(600) BINARY COMMENT '3;オリジナルファイル名称;アップロードファイル名称(マルチバイト許可)',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `media_org` COMMENT 'メディアオリジナル名称情報;施設画像ファイルのファイル名称を管理(画像アップロード後ファイル名をリネームするため)';

--   *** ------------------------------------
--  *** DIA_REMOVED
--   *** ------------------------------------
-- 
CREATE TABLE `media_removed` (
    `media_removed_id` DECIMAL(20, 0) COMMENT '1;画像削除ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `media_no` SMALLINT COMMENT '5;メディアNo;',
    `title` VARCHAR(300) BINARY COMMENT '6;画像タイトル;',
    `file_nm` VARCHAR(100) BINARY COMMENT '7;ファイル名称;',
    `mime_type` VARCHAR(50) BINARY COMMENT '8;メディアタイプ;image/gif image/jpeg video/quicktime など',
    `width` SMALLINT COMMENT '9;幅;',
    `height` SMALLINT COMMENT '10;高さ;',
    `upload_dtm` DATETIME COMMENT '11;アップロード日時;',
    `delete_dtm` DATETIME COMMENT '12;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `media_removed` COMMENT '画像削除情報;施設、部屋、プランに関連する画像が削除されたときに登録される';

--   *** ------------------------------------
--  *** MBER
--   *** ------------------------------------
-- 
CREATE TABLE `member` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `reserve_system` VARCHAR(12) BINARY COMMENT '2;会員登録予約システム;reserve:リザーブ dash:ダッシュ',
    `partner_cd` VARCHAR(10) BINARY COMMENT '3;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '4;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '5;アフィリエイトコード枝番;',
    `member_status` TINYINT COMMENT '6;会員状態;0:退会 1:会員',
    `point_status` TINYINT COMMENT '7;ポイントステータス;0:付与しない 1:付与する',
    `entry_dtm` DATETIME COMMENT '8;会員登録日時;',
    `withdraw_dtm` DATETIME COMMENT '9;会員退会日時;',
    `note` VARCHAR(3000) BINARY COMMENT '10;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `member_type` TINYINT
);

ALTER TABLE
    `member` COMMENT '会員情報;';

--   *** ------------------------------------
--  *** MBER_CARD
--   *** ------------------------------------
-- 
CREATE TABLE `member_card` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `branch_no` TINYINT COMMENT '2;枝番;',
    `card_id` TINYINT COMMENT '3;カードID;',
    `card_limit_ym` DATETIME COMMENT '4;クレジットカード有効期限;',
    `card_no_r4` VARCHAR(12) BINARY COMMENT '5;クレジットカード番号下４桁;クレジットカード番号下４桁を暗号化して保存する。（表示用）',
    `security_status` TINYINT COMMENT '6;セキュリティコード;visa master JCB は、3桁  AMEXは 4桁',
    `used_dtm` DATETIME COMMENT '7;最終利用日時;',
    `used_stay_dtm` DATETIME COMMENT '8;最終利用日時（通常予約）;',
    `used_power_dtm` DATETIME COMMENT '9;最終利用日時（ハイランク）;',
    `used_cpn_dtm` DATETIME COMMENT '10;最終利用日時（ベストク）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `trc_card_no` VARCHAR(48) BINARY,
    `used_car_dtm` DATETIME,
    `latest_stay_order_id` VARCHAR(50) BINARY,
    `latest_power_order_id` VARCHAR(50) BINARY,
    `latest_cpn_order_id` VARCHAR(50) BINARY,
    `latest_car_order_id` VARCHAR(50) BINARY
);

ALTER TABLE
    `member_card` COMMENT '会員カード情報;';

--   *** ------------------------------------
--  *** MBER_COUPON
--   *** ------------------------------------
-- 
CREATE TABLE `member_coupon` (
    `member_coupon_id` DECIMAL(22, 0) COMMENT '1;会員保持クーポンID;シーケンスより取得',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `welfare_grants_history_id` BIGINT COMMENT '3;クーポンID(補助金履歴ID);welfare_grants_historyテーブルのID(クーポンは補助金のテーブルを使うため）',
    `partner_cd` VARCHAR(10) BINARY COMMENT '4;提携先コード;このクーポンの利用可能な提携先コード　アフィリエイターコードとどちらか片方のみ値を格納',
    `affiliater_cd` VARCHAR(10) BINARY COMMENT '5;アフィリエイターコード;このクーポンの利用可能なアフィリエイターコード　提携先コードとどちらか片方のみ値を格納',
    `get_dtm` DATETIME COMMENT '6;取得日時;このクーポンの取得日時',
    `get_status` TINYINT COMMENT '7;取得状態;予約時に付与されるクーポン用のフラグ　0:対象外  1:予約  2:キャンセル',
    `get_order_cd` VARCHAR(15) BINARY COMMENT '8;取得時予約コード;予約後発行クーポンの場合に付与元になった予約のORDER_CD',
    `use_status` TINYINT COMMENT '9;利用状態;0:未使用 1:利用済',
    `use_dtm` DATETIME COMMENT '10;利用日時;このクーポンの利用日時',
    `use_order_cd` VARCHAR(15) BINARY COMMENT '11;利用予約申込コード;このクーポンを利用した予約のORDER_CD',
    `target_rsv_s_dtm` DATETIME COMMENT '12;対象予約期間開始日時;年月日時分秒まで設定',
    `target_rsv_e_dtm` DATETIME COMMENT '13;対象予約期間終了日時;',
    `target_stay_s_ymd` DATETIME COMMENT '14;対象宿泊期間開始日;',
    `target_stay_e_ymd` DATETIME COMMENT '15;対象宿泊期間終了日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `member_coupon` COMMENT '会員保持クーポン;会員が保持しているクーポンを格納する';

--   *** ------------------------------------
--  *** MBER_CREDITCARD
--   *** ------------------------------------
-- 
CREATE TABLE `member_creditcard` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `branch_no` TINYINT COMMENT '3;枝番;',
    `card_id` TINYINT COMMENT '4;カードID;',
    `card_limit_ym` DATETIME COMMENT '5;クレジットカード有効期限;',
    `card_no_r4` VARCHAR(12) BINARY COMMENT '6;クレジットカード番号下４桁;クレジットカード番号下４桁を暗号化して保存する。（表示用）',
    `security_cd` VARCHAR(4) BINARY COMMENT '7;セキュリティコード;visa master JCB は、3桁  AMEXは 4桁',
    `used_dtm` DATETIME COMMENT '8;最終利用日時;',
    `used_stay_dtm` DATETIME COMMENT '9;最終利用日時（通常予約）;',
    `used_power_dtm` DATETIME COMMENT '10;最終利用日時（ハイランク）;',
    `used_cpn_dtm` DATETIME COMMENT '11;最終利用日時（ベストク）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `member_creditcard` COMMENT '会員カード情報（提携先対応）;';

--   *** ------------------------------------
--  *** MBER_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `member_detail` (
    `member_cd` VARCHAR(128) BINARY,
    `account_id` VARCHAR(120) BINARY,
    `family_nm` VARCHAR(30) BINARY,
    `given_nm` VARCHAR(60) BINARY,
    `family_kn` VARCHAR(60) BINARY,
    `given_kn` VARCHAR(60) BINARY,
    `gender` VARCHAR(1) BINARY,
    `birth_ymd` DATETIME,
    `contact_type` TINYINT,
    `postal_cd` VARCHAR(12) BINARY,
    `pref_id` TINYINT,
    `address1` VARCHAR(300) BINARY,
    `address2` VARCHAR(300) BINARY,
    `tel` VARCHAR(17) BINARY,
    `email` VARCHAR(200) BINARY,
    `member_group` VARCHAR(150) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME,
    `optional_tel` VARCHAR(15) BINARY,
    `email_row` VARCHAR(200) BINARY
);

--   *** ------------------------------------
--  *** MBER_DETAIL_SP
--   *** ------------------------------------
-- 
CREATE TABLE `member_detail_sp` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `account_id` VARCHAR(120) BINARY COMMENT '2;アカウントID;',
    `family_nm` VARCHAR(30) BINARY COMMENT '3;会員名称（姓）;',
    `given_nm` VARCHAR(60) BINARY COMMENT '4;会員名称（名）;',
    `family_kn` VARCHAR(60) BINARY COMMENT '5;会員名称かな（姓）;',
    `given_kn` VARCHAR(60) BINARY COMMENT '6;会員名称かな（名）;',
    `gender` VARCHAR(1) BINARY COMMENT '7;性別;m:男性 f:女性',
    `birth_ymd` DATETIME COMMENT '8;生年月日;',
    `contact_type` TINYINT COMMENT '9;連絡先タイプ;0:自宅 1:会社',
    `postal_cd` VARCHAR(12) BINARY COMMENT '10;郵便番号（旧システム互換）;ハイフン含む（旧システムにおいて郵便番号が12文字で入力されているため）',
    `pref_id` TINYINT COMMENT '11;都道府県ID;',
    `address1` VARCHAR(300) BINARY COMMENT '12;住所１;',
    `address2` VARCHAR(300) BINARY COMMENT '13;住所２;',
    `tel` VARCHAR(17) BINARY COMMENT '14;電話番号（旧システム互換）;ハイフン含む（旧システムにおいて電話番号が17文字で入力されているため）',
    `email` VARCHAR(200) BINARY COMMENT '15;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `member_group` VARCHAR(150) BINARY COMMENT '16;所属団体;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '17;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '18;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '19;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '20;更新日時;',
    `optional_tel` VARCHAR(15) BINARY
);

ALTER TABLE
    `member_detail_sp` COMMENT '会員情報詳細;';

--   *** ------------------------------------
--  *** MBER_EPARK
--   *** ------------------------------------
-- 
CREATE TABLE `member_epark` (
    `epark_id` VARCHAR(30) BINARY COMMENT '1;EPARK会員ID;',
    `username` VARCHAR(100) BINARY COMMENT '2;アカウントID;emailの「@」より前の部分',
    `email` VARCHAR(200) BINARY COMMENT '3;メール;携帯電話番号会員の場合は空文字が返却されます。',
    `email_verified` TINYINT COMMENT '4;メールアドレス本人認証;0:承認/1:未承認',
    `email_row` VARCHAR(200) BINARY COMMENT '5;電子メールアドレス（平文）;平文の電子メールアドレス（email）',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '7;都道府県ID;',
    `prefecture` VARCHAR(15) BINARY COMMENT '8;都道府県名;',
    `address` VARCHAR(360) BINARY COMMENT '9;市区町村;全角120文字まで',
    `other_address` VARCHAR(360) BINARY COMMENT '10;住所（市区町村以降）;',
    `tel` VARCHAR(15) BINARY COMMENT '11;電話番号;ハイフン含む',
    `birth_ymd` DATETIME COMMENT '12;生年月日;',
    `gender` VARCHAR(1) BINARY COMMENT '13;性別;m:男性 f:女性（EPARK上では男性:0/女性:1/未設定:9）',
    `mem_app_id` VARCHAR(100) BINARY COMMENT '14;メンバーID;EPARK会員アプリID',
    `kanji_last_name` VARCHAR(100) BINARY COMMENT '15;漢字姓;会員の漢字姓',
    `kanji_first_name` VARCHAR(100) BINARY COMMENT '16;漢字名;会員の漢字名',
    `kana_last_name` VARCHAR(100) BINARY COMMENT '17;かな姓;会員のかな姓',
    `kana_first_name` VARCHAR(100) BINARY COMMENT '18;かな名;会員のかな名',
    `phone_number` VARCHAR(15) BINARY COMMENT '19;携帯電話番号(ログイン用);',
    `phone_number_verified` TINYINT COMMENT '20;携帯電話番号本人確認;0:承認/1:未承認',
    `epark_mailmag` TINYINT COMMENT '21;EPARKメルマガ受信;0:受け取らない（省略時）/1: 受け取る',
    `mail_style` TINYINT COMMENT '22;メールスタイル;0:TEXT形式 /1: HTML形式',
    `created_at` DATETIME COMMENT '23;EPARK会員登録日時;',
    `withdraw_at` DATETIME COMMENT '24;EPARK会員退会日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '25;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '26;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '27;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '28;更新日時;'
);

ALTER TABLE
    `member_epark` COMMENT 'EPARK連携会員情報;EPARKの会員情報を保持する';

--   *** ------------------------------------
--  *** MBER_EPKP
--   *** ------------------------------------
-- 
CREATE TABLE `member_epkp` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `rand_key` VARCHAR(12) BINARY COMMENT '2;EPK予約完了時返信用ランダムキー;ランダム生成文字列',
    `pc_mb` VARCHAR(2) BINARY COMMENT '3;PC/MB;pc:パソコン mb:携帯端末',
    `back_url` VARCHAR(200) BINARY COMMENT '4;EPKポータル（戻り用）リンク;予約完了時のEPARK戻り先URL',
    `email` VARCHAR(300) BINARY COMMENT '5;EPKメールアドレス;暗号化した値',
    `call_name` VARCHAR(120) BINARY COMMENT '6;EPK呼出名;',
    `zip_code` VARCHAR(7) BINARY COMMENT '7;EPK郵便番号;',
    `prefecture` VARCHAR(2) BINARY COMMENT '8;EPK都道府県;JIS X 0402',
    `address` VARCHAR(360) BINARY COMMENT '9;EPK市区町村;',
    `sex` VARCHAR(1) BINARY COMMENT '10;EPK性別;1:女性 2:男性',
    `birthday` DATETIME COMMENT '11;EPK生年月日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `member_epkp` COMMENT 'EPK予約者情報;';

--   *** ------------------------------------
--  *** MBER_FORCED_STOP_MAIL
--   *** ------------------------------------
-- 
CREATE TABLE `member_forced_stop_mail` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `entry_cd` VARCHAR(64) BINARY COMMENT '2;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '3;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '4;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '5;更新日時;'
);

ALTER TABLE
    `member_forced_stop_mail` COMMENT 'メール送信強制停止テーブル;会員へのメールマガジン等のメール送信を強制的に停止する';

--   *** ------------------------------------
--  *** MBER_FREE
--   *** ------------------------------------
-- 
CREATE TABLE `member_free` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `partner_cd` VARCHAR(10) BINARY COMMENT '2;提携先コード;',
    `email` VARCHAR(200) BINARY COMMENT '3;認証電子メール;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `check_in_ymd` DATETIME COMMENT '4;チェックイン日;',
    `stay` SMALLINT COMMENT '5;泊数;',
    `rooms` TINYINT COMMENT '6;部屋数合計;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '7;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '8;部屋コード;',
    `plan_cd` VARCHAR(16) BINARY COMMENT '9;プランコード;',
    `pref_id` TINYINT COMMENT '19;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '20;住所;市区町村以下',
    `gender` VARCHAR(1) BINARY COMMENT '21;性別;m:男性 f:女性',
    `birth_ymd` DATETIME COMMENT '22;生年月日;',
    `ip_address` VARCHAR(64) BINARY COMMENT '23;IPアドレス;社内からのアクセスも記録します。',
    `reserve_dtm` DATETIME COMMENT '24;予約受付日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '34;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '35;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '36;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '37;更新日時;',
    `payment_way_specified` TINYINT,
    `default_status` TINYINT COMMENT '25;初期表示の有無;認証電子メールアドレスをキーとして、次回予約時に初期値とする（ 1：使用する 0：しない ）',
    `room_id` VARCHAR(10) BINARY COMMENT '12;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '13;プランID;',
    `insurance_weather_status` TINYINT COMMENT '11;お天気保険プラン;',
    `adults` VARCHAR(30) BINARY COMMENT '26;大人人数;',
    `males` VARCHAR(30) BINARY COMMENT '27;宿泊人数男性;',
    `females` VARCHAR(30) BINARY COMMENT '28;宿泊人数女性;',
    `child1s` VARCHAR(30) BINARY COMMENT '29;宿泊人数子供１;',
    `child2s` VARCHAR(30) BINARY COMMENT '30;宿泊人数子供２;',
    `child3s` VARCHAR(30) BINARY COMMENT '31;宿泊人数子供３;食事なし、子供用の寝具',
    `child4s` VARCHAR(30) BINARY COMMENT '32;宿泊人数子供４;子供用の食事、寝具なし',
    `child5s` VARCHAR(30) BINARY COMMENT '33;宿泊人数子供５;食事なし、寝具なし',
    `payment_way` TINYINT COMMENT '10;決済方法;未使用 3:選択の場合ですでに決済方法が決定されている場合に登録 1:事前カード決済 2:現地決済',
    `member_last_nm` VARCHAR(36) BINARY COMMENT '14;予約者姓;',
    `member_first_nm` VARCHAR(36) BINARY COMMENT '15;予約者名;',
    `member_last_nm_kn` VARCHAR(36) BINARY COMMENT '16;予約者姓（カナ）;',
    `member_first_nm_kn` VARCHAR(36) BINARY COMMENT '17;予約者名（カナ）;',
    `member_tel` VARCHAR(15) BINARY COMMENT '18;予約者電話番号;',
    `extscd` VARCHAR(256) BINARY
);

ALTER TABLE
    `member_free` COMMENT 'フリーユーザ情報;';

--   *** ------------------------------------
--  *** MBER_FREE_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `member_free_relation` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `member_free_cd` VARCHAR(128) BINARY COMMENT '2;非会員コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_free_relation` COMMENT '会員非会員関連付け;非会員から会員になった関連付けテーブル';

--   *** ------------------------------------
--  *** MBER_FREE_TRACE
--   *** ------------------------------------
-- 
CREATE TABLE `member_free_trace` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `cccv` VARCHAR(3000) BINARY COMMENT '2;訪問クッキー;',
    `cccr` VARCHAR(3000) BINARY COMMENT '3;経路クッキー;',
    `cccf` VARCHAR(3000) BINARY COMMENT '4;最終クッキー;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `member_free_trace` COMMENT '非会員予約でのトレース情報;';

--   *** ------------------------------------
--  *** MBER_HOTELS
--   *** ------------------------------------
-- 
CREATE TABLE `member_hotels` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `entry_type` TINYINT COMMENT '2;登録種類;1:クリップホテル 2: 最近見たホテル',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `star_cnt` TINYINT COMMENT '4;星の数;クリップホテルで使用、表示ホテルはヌル値',
    `entry_dtm` DATETIME COMMENT '5;施設登録日時;',
    `recent_dtm` DATETIME COMMENT '6;最終表示日時;対象施設のページを表示した日時',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `member_hotels` COMMENT '会員利用ホテル;クリップホテル・表示ホテル';

--   *** ------------------------------------
--  *** MBER_HOTELS2
--   *** ------------------------------------
-- 
CREATE TABLE `member_hotels2` (
    `member_cd` VARCHAR(128) BINARY,
    `entry_type` TINYINT,
    `hotel_cd` VARCHAR(10) BINARY,
    `star_cnt` TINYINT,
    `entry_dtm` DATETIME,
    `recent_dtm` DATETIME,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** MBER_JWEST
--   *** ------------------------------------
-- 
CREATE TABLE `member_jwest` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `j_westid` VARCHAR(12) BINARY COMMENT '2;J-WESTID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_jwest` COMMENT 'JRおでかけネット会員情報;';

--   *** ------------------------------------
--  *** MBER_LIVEDOOR
--   *** ------------------------------------
-- 
CREATE TABLE `member_livedoor` (
    `transaction_cd` VARCHAR(14) BINARY COMMENT '1;トランザクションコード;最初の予約コードを設定（複数部屋の予約について）',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `family_nm` VARCHAR(30) BINARY COMMENT '3;会員名称（姓）;',
    `given_nm` VARCHAR(30) BINARY COMMENT '4;会員名称（名）;',
    `family_kn` VARCHAR(60) BINARY COMMENT '5;会員名称かな（姓）;',
    `given_kn` VARCHAR(60) BINARY COMMENT '6;会員名称かな（名）;',
    `tel` VARCHAR(15) BINARY COMMENT '7;会員電話番号;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '8;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '9;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '10;住所;市区町村以下',
    `gender` VARCHAR(1) BINARY COMMENT '11;性別;m:男性 f:女性',
    `birth_ymd` DATETIME COMMENT '12;生年月日;',
    `email` VARCHAR(200) BINARY COMMENT '13;認証電子メール;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `member_livedoor` COMMENT 'ライブドア予約者情報;';

--   *** ------------------------------------
--  *** MBER_MAIL
--   *** ------------------------------------
-- 
CREATE TABLE `member_mail` (
    `member_mail_cd` VARCHAR(12) BINARY COMMENT '1;会員メールID;YYYYMMNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `email` VARCHAR(200) BINARY COMMENT '3;電子メールアドレス;',
    `email_type` TINYINT COMMENT '4;電子メールタイプ;0:パソコン用レイアウト 1:携帯端末用レイアウト',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `email_row` VARCHAR(200) BINARY COMMENT '平文の電子メールアドレス（email）'
);

ALTER TABLE
    `member_mail` COMMENT '会員メールアドレス;';

--   *** ------------------------------------
--  *** MBER_MOBILE
--   *** ------------------------------------
-- 
CREATE TABLE `member_mobile` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `mobile_id` VARCHAR(128) BINARY COMMENT '2;契約者固有ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_mobile` COMMENT '契約者固有IDテーブル;';

--   *** ------------------------------------
--  *** MBER_MOBILE_MAIL
--   *** ------------------------------------
-- 
CREATE TABLE `member_mobile_mail` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `send_mail_type` VARCHAR(20) BINARY COMMENT '2;メール送信タイプ;mailmagazine:メールマガジン、thankyou:サンキューメール、stayconfirm:宿泊確認メール',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_mobile_mail` COMMENT 'モバイルメールマガジン送信否;会員へのメール送信否（member_sending_mail）の携帯版';

--   *** ------------------------------------
--  *** MBER_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `member_point` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `status` TINYINT DEFAULT 0 COMMENT '2;状態;0:無効 1:有効',
    `version` VARCHAR(2) BINARY COMMENT '3;バージョン;ヌル値:しない 3:バージョン３ 4:バージョン４',
    `accept_s_ymd` DATETIME COMMENT '4;開始年月日;',
    `lost_base_ymd` DATETIME COMMENT '5;失効基準年月日;失効する日の基準日',
    `lost_ymd` DATETIME COMMENT '6;失効年月日;失効基準年月日の翌日１日から１３ヶ月目の１日',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `migration_ymd` DATETIME,
    `migration_dtm` DATETIME,
    `lost_e_ymd` DATETIME,
    `valid_point` BIGINT
);

ALTER TABLE
    `member_point` COMMENT '会員BRポイント設定;';

--   *** ------------------------------------
--  *** MBER_SEARCH_MAIL
--   *** ------------------------------------
-- 
CREATE TABLE `member_search_mail` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `entry_type` TINYINT COMMENT '2;登録種類;0:会員登録 1:その他１ 2:その他２ 3:その他３',
    `account` VARCHAR(200) BINARY COMMENT '3;アカウント;英文字小文字の暗号化',
    `domain` VARCHAR(200) BINARY COMMENT '4;ドメイン;小文字暗号化なし',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `member_search_mail` COMMENT '会員メールアドレス検索用テーブル;';

--   *** ------------------------------------
--  *** MBER_SEARCH_MAIL_SP
--   *** ------------------------------------
-- 
CREATE TABLE `member_search_mail_sp` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `entry_type` TINYINT COMMENT '2;登録種類;0:会員登録 1:その他１ 2:その他２ 3:その他３',
    `account` VARCHAR(200) BINARY COMMENT '3;アカウント;英文字小文字の暗号化',
    `domain` VARCHAR(200) BINARY COMMENT '4;ドメイン;小文字暗号化なし',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `account_sp` VARCHAR(200) BINARY,
    `email_sp` VARCHAR(200) BINARY
);

ALTER TABLE
    `member_search_mail_sp` COMMENT '会員メールアドレス検索用テーブルSP;';

--   *** ------------------------------------
--  *** MBER_SENDING_MAIL
--   *** ------------------------------------
-- 
CREATE TABLE `member_sending_mail` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `send_mail_type` VARCHAR(20) BINARY COMMENT '2;メール送信タイプ;mailmagazine:メールマガジン、thankyou:サンキューメール、stayconfirm:宿泊確認メール',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_sending_mail` COMMENT '会員へのメール送信否;送信しないメールアドレスを設定';

--   *** ------------------------------------
--  *** MBER_SP
--   *** ------------------------------------
-- 
CREATE TABLE `member_sp` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `reserve_system` VARCHAR(12) BINARY COMMENT '2;会員登録予約システム;reserve:リザーブ dash:ダッシュ',
    `partner_cd` VARCHAR(10) BINARY COMMENT '3;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '4;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '5;アフィリエイトコード枝番;',
    `member_status` TINYINT COMMENT '6;会員状態;0:退会 1:会員',
    `point_status` TINYINT COMMENT '7;ポイントステータス;0:付与しない 1:付与する',
    `entry_dtm` DATETIME COMMENT '8;会員登録日時;',
    `withdraw_dtm` DATETIME COMMENT '9;会員退会日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `member_type` TINYINT
);

ALTER TABLE
    `member_sp` COMMENT '会員情報;';

--   *** ------------------------------------
--  *** MBER_SSO
--   *** ------------------------------------
-- 
CREATE TABLE `member_sso` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `sso_cd` VARCHAR(64) BINARY COMMENT '2;会員変動コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_sso` COMMENT '会員SSO;会員のSSOに関する情報を管理します。';

--   *** ------------------------------------
--  *** MBER_STAFF_NOTE
--   *** ------------------------------------
-- 
CREATE TABLE `member_staff_note` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `staff_note` VARCHAR(3000) BINARY COMMENT '2;スタッフノート;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_staff_note` COMMENT '会員スタッフノート;';

--   *** ------------------------------------
--  *** MBER_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `member_yahoo` (
    `transaction_cd` VARCHAR(14) BINARY COMMENT '1;トランザクションコード;最初の予約コードを設定（複数部屋の予約について）',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `pref_id` TINYINT COMMENT '8;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '9;住所;市区町村以下',
    `gender` VARCHAR(1) BINARY COMMENT '10;性別;m:男性 f:女性',
    `birth_ymd` DATETIME COMMENT '11;生年月日;',
    `email` VARCHAR(200) BINARY COMMENT '12;認証電子メール;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `email_notify` TINYINT COMMENT '13;ニュースレター通知可否;0:否通知 1:通知',
    `guest_nm` VARCHAR(75) BINARY COMMENT '14;宿泊代表者氏名;1部屋目の代表者氏名',
    `guest_tel` VARCHAR(15) BINARY COMMENT '19;宿泊代表者電話番号;',
    `guest_group` VARCHAR(150) BINARY COMMENT '22;宿泊代表者所属団体;',
    `smoking` TINYINT COMMENT '23;禁煙喫煙;0:なし 1:禁煙 2:喫煙 3:予約時にたずねる',
    `entry_cd` VARCHAR(64) BINARY COMMENT '24;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '25;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '26;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '27;更新日時;',
    `member_tel` VARCHAR(50) BINARY COMMENT '7;予約者電話番号;',
    `member_last_nm` VARCHAR(36) BINARY COMMENT '3;予約者姓;',
    `member_first_nm` VARCHAR(36) BINARY COMMENT '4;予約者名;',
    `member_last_nm_kn` VARCHAR(36) BINARY COMMENT '5;予約者姓（カナ）;全角カタカナ と 全角・半角スペース',
    `member_first_nm_kn` VARCHAR(36) BINARY COMMENT '6;予約者名（カナ）;全角カタカナ と 全角・半角スペース',
    `guest_last_nm` VARCHAR(36) BINARY COMMENT '15;宿泊代表者姓;宿泊する部屋の代表者姓（複数部屋の予約の場合は1部屋目の宿泊者を登録）',
    `guest_first_nm` VARCHAR(36) BINARY COMMENT '16;宿泊代表者名;宿泊する部屋の代表者名（複数部屋の予約の場合は1部屋目の宿泊者を登録）',
    `guest_last_nm_kn` VARCHAR(36) BINARY COMMENT '17;宿泊代表者姓（カナ）;全角カタカナ と 全角・半角スペース',
    `guest_first_nm_kn` VARCHAR(36) BINARY COMMENT '18;宿泊代表者名（カナ）;全角カタカナ と 全角・半角スペース',
    `guest_pref_id` TINYINT COMMENT '20;宿泊代表者都道府県ID;',
    `guest_address` VARCHAR(300) BINARY COMMENT '21;宿泊代表者住所;市区町村以下'
);

ALTER TABLE
    `member_yahoo` COMMENT 'Yahoo予約者情報;';

--   *** ------------------------------------
--  *** MBER_YDP
--   *** ------------------------------------
-- 
CREATE TABLE `member_ydp` (
    `member_id` VARCHAR(10) BINARY COMMENT '1;日本旅行会員ID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;ベストリザーブ会員コード;ベストリザーブ会員は20バイト',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `member_ydp` COMMENT '日本旅行会員移行対応;';

--   *** ------------------------------------
--  *** MBER_ZAP
--   *** ------------------------------------
-- 
CREATE TABLE `member_zap` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `ip_address` VARCHAR(64) BINARY COMMENT '2;IPアドレス;社内からのアクセスも記録します。',
    `reason` VARCHAR(120) BINARY COMMENT '3;退会理由;',
    `withdraw_dtm` DATETIME COMMENT '4;会員退会日時;',
    `entry_dtm` DATETIME COMMENT '5;会員登録日時;',
    `entry_dtm2` DATETIME COMMENT '6;会員登録日時2;',
    `fax` VARCHAR(15) BINARY COMMENT '7;ファックス番号;ハイフン含む',
    `group_type` VARCHAR(50) BINARY COMMENT '8;会員属性;0=会社員・1=学生・2=主婦・9=その他',
    `teikei_cd` VARCHAR(10) BINARY COMMENT '9;表示画面タイプ;旧type_cd',
    `teikei_nm` VARCHAR(150) BINARY COMMENT '10;提携先名称;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `member_zap` COMMENT '旧会員情報;';

--   *** ------------------------------------
--  *** GRATION
--   *** ------------------------------------
-- 
CREATE TABLE `migration` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `complete_status` VARCHAR(50) BINARY COMMENT '2;移行完了ステータス;0:未完 1:完了',
    `msc_type` TINYINT COMMENT '3;マルチサイトコントローラ;0:なし  1:らくじゃん 2: 手間いらず  3:TL-リンカーン 4:その他',
    `msc_etc` VARCHAR(50) BINARY COMMENT '4;マルチサイトコントローラその他;その他を選ばれた場合、どれを選んだか入力してください。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `migration` COMMENT 'マイグレーション;';

--   *** ------------------------------------
--  *** GRATION_BASE
--   *** ------------------------------------
-- 
CREATE TABLE `migration_base` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `room_label_cd` VARCHAR(10) BINARY COMMENT '4;部屋ラベル;',
    `plan_type` VARCHAR(10) BINARY COMMENT '5;プランタイプ;null:通常 fss:金土日',
    `plan_nm` VARCHAR(375) BINARY COMMENT '6;プラン名称;ベストリザーブは40文字',
    `charge_type` TINYINT COMMENT '7;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '8;定員;ルームチャージの場合は最小と最大が同じ',
    `payment_way` TINYINT COMMENT '9;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `stay_limit` TINYINT COMMENT '10;最低連泊数;',
    `stay_cap` TINYINT COMMENT '11;連泊限界数;最大泊数が null か 予約泊数以下の時予約可能',
    `accept_s_ymd` DATETIME COMMENT '12;料金登録開始日付;',
    `accept_e_ymd` DATETIME COMMENT '13;料金登録終了日付;',
    `accept_e_day` TINYINT COMMENT '14;販売終了日;null:手仕舞いなし',
    `accept_e_hour` VARCHAR(5) BINARY COMMENT '15;販売終了時間;',
    `accept_e_status` BIGINT COMMENT '16;販売終了更新状態;0: 更新しない、 1:指定された日程で更新',
    `check_in` VARCHAR(5) BINARY COMMENT '17;チェックイン開始時刻;HH:MM',
    `check_in_end` VARCHAR(5) BINARY COMMENT '18;チェックイン終了時刻;HH:MM',
    `check_out` VARCHAR(5) BINARY COMMENT '19;チェックアウト時刻;HH:MM',
    `plan_label_cd` VARCHAR(10) BINARY COMMENT '20;プランラベル;',
    `cancel_policy` VARCHAR(600) BINARY COMMENT '21;キャンセルポリシー;200文字',
    `info` VARCHAR(4000) BINARY COMMENT '22;特色;',
    `issue_point_rate` SMALLINT COMMENT '23;付与ポイント率;',
    `issue_point_rate_our` SMALLINT COMMENT '24;獲得ポイント当社負担率;',
    `issue_point_stauts` VARCHAR(50) BINARY COMMENT '25;付与ポイント率状態;0:変更なし、1:変更あり',
    `point_status` TINYINT COMMENT '26;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '27;増量単位;',
    `min_point` INT COMMENT '28;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '29;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `extend_status` TINYINT COMMENT '30;自動延長状態;0:停止中 1受付中:',
    `entry_cd` VARCHAR(64) BINARY COMMENT '31;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '32;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '33;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '34;更新日時;'
);

ALTER TABLE
    `migration_base` COMMENT 'マイグレーション基本情報;';

--   *** ------------------------------------
--  *** GRATION_CANCEL_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `migration_cancel_rate` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `days` SMALLINT COMMENT '3;宿泊日からの日数;',
    `cancel_rate` SMALLINT COMMENT '4;キャンセル料率;',
    `policy_status` TINYINT COMMENT '5;プランポリシーステータス;0:適用外 1:適用中 プラン単位に設定されます。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `migration_cancel_rate` COMMENT 'マイグレーションキャンセル料率;';

--   *** ------------------------------------
--  *** GRATION_CANCEL_RATE_TEMP
--   *** ------------------------------------
-- 
CREATE TABLE `migration_cancel_rate_temp` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランＩＤ;',
    `days` SMALLINT COMMENT '3;宿泊日からの日数;',
    `cancel_rate` SMALLINT COMMENT '4;キャンセル料率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `migration_cancel_rate_temp` COMMENT '販売提携グループキャンセル料率（保存用）;';

--   *** ------------------------------------
--  *** GRATION_CHARGE_TEMP
--   *** ------------------------------------
-- 
CREATE TABLE `migration_charge_temp` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `capacity` SMALLINT COMMENT '5;人数;',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `usual_charge` INT COMMENT '7;大人一人通常料金;',
    `usual_charge_revise` TINYINT COMMENT '8;大人一人通常料金補正値;',
    `sales_charge` INT COMMENT '9;大人一人販売料金;',
    `sales_charge_revise` TINYINT COMMENT '10;大人一人販売料金補正地;',
    `accept_status` TINYINT COMMENT '11;予約受付状態;0:停止中 1:受付中',
    `accept_s_dtm` DATETIME COMMENT '12;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '13;終了日時;',
    `low_price_status` TINYINT COMMENT '14;最安値宣言ステータス;0:宣言しない 1:宣言する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `migration_charge_temp` COMMENT '販売提携グループ料金（保存用）;';

--   *** ------------------------------------
--  *** GRATION_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `migration_match` (
    `id` INT COMMENT '1;ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `parent_room_id` VARCHAR(10) BINARY COMMENT '3;親部屋ID;',
    `parent_plan_id` VARCHAR(10) BINARY COMMENT '4;親プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '5;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '6;プランID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `migration_match` COMMENT 'マイグレーションプラン統合;';

--   *** ------------------------------------
--  *** GRATION_MEDIA
--   *** ------------------------------------
-- 
CREATE TABLE `migration_media` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `media_no` SMALLINT COMMENT '3;メディアNo;ベストリザーブは3文字',
    `order_no` SMALLINT COMMENT '4;画像表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `migration_media` COMMENT 'マイグレーションメディア;';

--   *** ------------------------------------
--  *** GRATION_PLAN_TEMP
--   *** ------------------------------------
-- 
CREATE TABLE `migration_plan_temp` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランＩＤ;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '3;プラン名称;ベストリザーブは40文字 plan',
    `premium_status` TINYINT DEFAULT 0 COMMENT '4;プレミアムステータス;0:通常 1:プレミアム施設',
    `stock_type` TINYINT COMMENT '5;仕入タイプ;0:受託販売 1:買取販売 2:一括受託（宿ぷらざ）',
    `room_type` TINYINT COMMENT '6;部屋タイプ;0:カプセル 1:シングル 2:ツイン 3:セミダブル 4:ダブル 5:トリプル 6:4ベッド 7:スイート 8:メゾネット 9:和室 10:和洋室 11:その他',
    `capacity_min` TINYINT COMMENT '7;最小定員;',
    `capacity_max` TINYINT COMMENT '8;最大定員;',
    `floorage_min` SMALLINT COMMENT '9;最小床面積;',
    `floorage_max` SMALLINT COMMENT '10;最大床面積;',
    `floor_unit` TINYINT COMMENT '11;広さ単位;0:平方メートル 1:疊',
    `network` TINYINT COMMENT '12;ネットワーク接続可否;0:接続環境なし 1:無料（全客室） 2:無料（一部客室） 3:有料（全客室） 4:有料（一部客室） 9:不明',
    `rental` TINYINT COMMENT '13;接続機器貸し出し;1:部屋常設 2:無料貸出 3:有料貸出 4:持ち込み',
    `connector` TINYINT COMMENT '14;接続コネクタ種類;1:無線 2:LAN 3:ＴＥＬ 4:その他',
    `network_note` VARCHAR(750) BINARY COMMENT '15;ネットワーク詳細;',
    `plan_type` VARCHAR(10) BINARY COMMENT '16;プランタイプ;null:通常 fss:金土日',
    `charge_type` TINYINT COMMENT '17;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '18;定員;ルームチャージの場合は最小と最大が同じ',
    `payment_way` TINYINT COMMENT '19;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `stay_limit` TINYINT COMMENT '20;最低連泊数;',
    `stay_cap` TINYINT COMMENT '21;連泊限界数;最大泊数が null か 予約泊数以下の時予約可能',
    `room_label_cd` VARCHAR(10) BINARY COMMENT '22;部屋ラベルコード;施設が画像を管理するために用いるラベル 左から外観、地図、館内、客室、その他（1:有効 0:無効）',
    `plan_label_cd` VARCHAR(10) BINARY COMMENT '23;プランラベルコード;',
    `accept_status` TINYINT COMMENT '24;予約受付状態;0:停止中 1:受付中',
    `accept_s_ymd` DATETIME COMMENT '25;料金登録開始日付;',
    `accept_e_ymd` DATETIME COMMENT '26;料金登録終了日付;',
    `accept_e_day` TINYINT COMMENT '27;販売終了日;null:手仕舞いなし',
    `accept_e_hour` VARCHAR(5) BINARY COMMENT '28;販売終了時間;',
    `check_in` VARCHAR(5) BINARY COMMENT '29;チェックイン開始時刻;HH:MM',
    `check_in_end` VARCHAR(5) BINARY COMMENT '30;チェックイン終了時刻;HH:MM',
    `check_out` VARCHAR(5) BINARY COMMENT '31;チェックアウト時刻;HH:MM',
    `info` VARCHAR(4000) BINARY COMMENT '32;プラン特色;',
    `element_value_id` VARCHAR(50) BINARY COMMENT '33;食事（スペック）;',
    `cancel_policy` VARCHAR(600) BINARY COMMENT '34;キャンセルポリシー;200文字',
    `issue_point_rate` SMALLINT COMMENT '35;獲得ポイント率;Yahoo!ポイント専用、BRは通常は1%、プレミアムは2%',
    `issue_point_rate_our` SMALLINT COMMENT '36;獲得ポイント当社負担率;',
    `point_status` TINYINT COMMENT '37;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '38;増量単位;',
    `min_point` INT COMMENT '39;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '40;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `cancel_priority` TINYINT COMMENT '41;優先設定;0:料金から差し引く 1:ポイントから差し引く',
    `entry_cd` VARCHAR(64) BINARY COMMENT '42;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '43;登録日時;',
    `modify_ts` DATETIME COMMENT '44;更新日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '45;更新者コード;/controller/action.(user_id) または 更新者メールアドレス'
);

ALTER TABLE
    `migration_plan_temp` COMMENT '販売提携グループプラン（保存用）;';

--   *** ------------------------------------
--  *** GRATION_SPEC
--   *** ------------------------------------
-- 
CREATE TABLE `migration_spec` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `element_id` SMALLINT COMMENT '3;要素ID;',
    `element_value_id` TINYINT COMMENT '4;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `migration_spec` COMMENT 'マイグレーションスペック;';

--   *** ------------------------------------
--  *** S_GENRE
--   *** ------------------------------------
-- 
CREATE TABLE `mms_genre` (
    `mail_magazine_simple_id` BIGINT COMMENT '1;汎用メールマガジンID;',
    `genre` VARCHAR(20) BINARY COMMENT '2;メールマガジンジャンル;mailmagazine:メールマガジン（毎日）、mailmagazine-week:メールマガジン（週） bestcou:ベストク（毎日） bestcou-week:ベストク（週）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mms_genre` COMMENT '汎用メールマガジンジャンル;汎用メールマガジンのジャンル';

--   *** ------------------------------------
--  *** S_LINK_ANALYZE
--   *** ------------------------------------
-- 
CREATE TABLE `mms_link_analyze` (
    `mail_magazine_simple_id` BIGINT COMMENT '1;汎用メールマガジンID;',
    `link_no` SMALLINT COMMENT '2;メルマガ内リンク番号;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '3;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '4;アフィリエイトコード枝番;',
    `uri` VARCHAR(255) BINARY COMMENT '5;URIアドレス;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mms_link_analyze` COMMENT '汎用メールマガジンリンク分析;汎用メールマガジン本文内のリンク分析結果（mms→mail_magazine_simple）';

--   *** ------------------------------------
--  *** S_SEND_EXTRACT_CONDITION
--   *** ------------------------------------
-- 
CREATE TABLE `mms_send_extract_condition` (
    `condition_id` BIGINT COMMENT '1;抽出条件ID;',
    `condition_nm` VARCHAR(50) BINARY COMMENT '2;抽出条件の名称;',
    `select_sql_string` LONGTEXT COMMENT '3;抽出条件のSQL;',
    `note` VARCHAR(1200) BINARY COMMENT '4;抽出条件コメント（備考）;',
    `display_status` TINYINT COMMENT '5;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `mms_send_extract_condition` COMMENT '汎用メールマガジン送信対象抽出条件;汎用メールマガジン特殊な送信対象抽出条件（mms→mail_magazine_simple）';

--   *** ------------------------------------
--  *** S_SEND_EXTRACT_RERATION
--   *** ------------------------------------
-- 
CREATE TABLE `mms_send_extract_reration` (
    `mail_magazine_simple_id` BIGINT COMMENT '1;汎用メールマガジンID;',
    `condition_id` BIGINT COMMENT '2;抽出条件ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `mms_send_extract_reration` COMMENT '汎用メルマガと特殊な抽出条件の関連付け;汎用メルマガと特殊な抽出条件の関連付け';

--   *** ------------------------------------
--  *** NEY_SCHEDULE
--   *** ------------------------------------
-- 
CREATE TABLE `money_schedule` (
    `ym` DATETIME COMMENT '1;処理年月;',
    `money_schedule_id` INT COMMENT '2;スケジュールID;1:締め日 2:送客日 3:支払予定日 4:振込期日',
    `date_ymd` DATETIME COMMENT '3;日付;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `money_schedule` COMMENT '経理関係スケジュール;';

--   *** ------------------------------------
--  *** _HOTEL_LIST
--   *** ------------------------------------
-- 
CREATE TABLE `my_hotel_list` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `entry_status` TINYINT COMMENT '3;施設登録状態;0:非表示 1:表示',
    `star_cnt` TINYINT COMMENT '4;星の数;',
    `memo` VARCHAR(1500) BINARY COMMENT '5;メモ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `my_hotel_list` COMMENT 'MYホテル一覧;';

--   *** ------------------------------------
--  *** _HOTEL_RESERVED
--   *** ------------------------------------
-- 
CREATE TABLE `my_hotel_reserved` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `used_status` TINYINT COMMENT '3;使用状態;0:予約ホテル 1:ＭＹホテル移行済み 3:拒否ホテル',
    `date_ymd` DATETIME COMMENT '4;宿泊開始日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `my_hotel_reserved` COMMENT '会員予約ホテルリスト;会員が過去に宿泊したホテルリスト（電話キャンセル：NOSHOW未対応';

--   *** ------------------------------------
--  *** _SEARCH_SETTING
--   *** ------------------------------------
-- 
CREATE TABLE `my_search_setting` (
    `my_search_setting_cd` VARCHAR(10) BINARY COMMENT '1;ＭＹ検索条件ID;YYYYNNNNNN',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `order_no` TINYINT COMMENT '3;条件表示順序;最大５件',
    `lead_wday` TINYINT COMMENT '4;リード曜日;0:リードタイム 1:日 2:月 3:火 4:水 5:木 6:金 7:土',
    `lead_day` SMALLINT COMMENT '5;リードタイム;',
    `stay` BIGINT COMMENT '6;宿泊日数;',
    `capacity` TINYINT COMMENT '7;定員;ルームチャージの場合は最小と最大が同じ',
    `rooms` SMALLINT COMMENT '8;部屋数;',
    `charge_min` INT COMMENT '9;予算MIN;',
    `charge_max` INT COMMENT '10;予算MAX;',
    `target_hotel` TINYINT COMMENT '11;対象施設;0:MYホテル 1:都道府県',
    `pref_id` TINYINT COMMENT '12;都道府県ID;外に出す？',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `my_search_setting` COMMENT 'ＭＹ検索条件;';

--   *** ------------------------------------
--  *** _SETTING
--   *** ------------------------------------
-- 
CREATE TABLE `my_setting` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `item_cd` VARCHAR(16) BINARY COMMENT '2;項目;defaultview:検索フォーム',
    `value` VARCHAR(24) BINARY COMMENT '3;値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `my_setting` COMMENT 'ＭＹＰＡＧＥ表示制御;検索ホームのデフォルト表示項目等をせっていする。';

--   *** ------------------------------------
--  *** TIFY
--   *** ------------------------------------
-- 
CREATE TABLE `notify` (
    `notify_id` BIGINT COMMENT '1;通知ID;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `notify_cd` VARCHAR(21) BINARY COMMENT '3;通知コード;予約通知FAXの原稿のヘッダー箇所の番号 （NNNNNNNNNN-NNNNNNNNNN:施設コード-通知No）',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `result_condition` VARCHAR(10) BINARY COMMENT '5;結果状態;reserve:予約 short:日程短縮 cancel:キャンセル',
    `notify_condition` VARCHAR(10) BINARY COMMENT '6;通知状態;reserve:予約 short:日程短縮 cancel:キャンセル no_create:作成しない',
    `rapid_status` TINYINT DEFAULT 0 COMMENT '7;即時通知;0:通知しない 1:通知する',
    `notify_status` TINYINT COMMENT '8;通知結果;0:作成待ち 1:作成中／済 2:送信依頼中 3:送信中 4:送信完了(OK) 5:送信エラー',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `notify` COMMENT '通知;原稿作成判断用';

--   *** ------------------------------------
--  *** TIFY_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `notify_detail` (
    `notify_cd` VARCHAR(21) BINARY COMMENT '1;通知コード;予約通知FAXの原稿のヘッダー箇所の番号 （NNNNNNNNNN-NNNNNNNNNN:施設コード-通知No）',
    `notify_device` TINYINT DEFAULT 1 COMMENT '2;通知媒体;1:ファックス 2:電子メール',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `notify_condition` VARCHAR(16) BINARY COMMENT '4;通知状況;create:原稿作成済み request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '5;原稿作成日時;',
    `send_request_dtm` DATETIME COMMENT '6;送信依頼日時;',
    `send_accept_dtm` DATETIME COMMENT '7;送信受付日時;',
    `send_result_dtm` DATETIME COMMENT '8;送信処理完了日時;',
    `book_path` VARCHAR(128) BINARY COMMENT '9;原稿ファイルパス;',
    `notify_email` VARCHAR(500) BINARY COMMENT '10;通知電子メールアドレス;カンマ区切りで複数可',
    `notify_fax` VARCHAR(15) BINARY COMMENT '11;通知ファックス番号;ハイフン含む',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `notify_detail` COMMENT '通知詳細;通知対象となるデータ';

--   *** ------------------------------------
--  *** TIFY_PAGE
--   *** ------------------------------------
-- 
CREATE TABLE `notify_page` (
    `notify_cd` VARCHAR(21) BINARY COMMENT '1;通知コード;予約通知FAXの原稿のヘッダー箇所の番号 （NNNNNNNNNN-NNNNNNNNNN:施設コード-通知No）',
    `page` SMALLINT COMMENT '2;ページ数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `notify_page` COMMENT 'ファックス枚数;ファックスの送信枚数を保存します';

--   *** ------------------------------------
--  *** TIFY_RIZAPULI
--   *** ------------------------------------
-- 
CREATE TABLE `notify_rizapuli` (
    `notify_rizapuli_id` BIGINT COMMENT '1;リザプリ通知ID;シーケンスで発番された値',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `notify_rizapuli_cd` VARCHAR(21) BINARY COMMENT '3;リザプリ通知コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `result_condition` VARCHAR(10) BINARY COMMENT '5;結果状態;reserve:予約 short:日程短縮 cancel:キャンセル modify:変更',
    `notify_condition` VARCHAR(10) BINARY COMMENT '6;通知状態;reserve:予約 short:日程短縮 cancel:キャンセル modify:変更 no_create:作成しない',
    `notify_status` TINYINT COMMENT '7;通知結果;0:作成待ち 1:作成中／済 2:送信依頼中 3:送信中 4:送信完了(OK) 5:送信エラー',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `notify_rizapuli` COMMENT '通知（リザプリ）;リザプリ原稿作成判断用';

--   *** ------------------------------------
--  *** TIFY_RIZAPULI_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `notify_rizapuli_detail` (
    `notify_rizapuli_cd` VARCHAR(21) BINARY COMMENT '1;リザプリ通知コード;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `notify_condition` VARCHAR(16) BINARY COMMENT '3;通知状況;create:原稿作成済み request_[ok|nok]:送信依頼[正常|異常] accept_[ok|nok]:送信受付[正常|異常] result_[ok|nok|unsend]:送信結果[正常|異常|否送信]',
    `book_create_dtm` DATETIME COMMENT '4;原稿作成日時;',
    `send_request_dtm` DATETIME COMMENT '5;送信依頼日時;',
    `send_accept_dtm` DATETIME COMMENT '6;送信受付日時;',
    `send_result_dtm` DATETIME COMMENT '7;送信処理完了日時;',
    `book_path` VARCHAR(128) BINARY COMMENT '8;原稿ファイルパス;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `notify_rizapuli_detail` COMMENT '通知詳細（リザプリ）;リザプリの通知対象となるデータ情報管理';

--   *** ------------------------------------
--  *** TIFY_RIZAPULI_STAY
--   *** ------------------------------------
-- 
CREATE TABLE `notify_rizapuli_stay` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `notify_rizapuli_stay` COMMENT '前回通知宿泊日（リザプリ）;前回通知時に「○」にて通知した一覧を保持';

--   *** ------------------------------------
--  *** TIFY_STAY
--   *** ------------------------------------
-- 
CREATE TABLE `notify_stay` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `notify_stay` COMMENT '前回通知宿泊日;前回通知時に「○」にて通知した一覧を保持';

--   *** ------------------------------------
--  *** A_STAFF
--   *** ------------------------------------
-- 
CREATE TABLE `nta_staff` (
    `nta_staff_id` INT COMMENT '1;日本旅行スタッフID;',
    `staff_nm` VARCHAR(96) BINARY COMMENT '2;スタッフ;',
    `email` VARCHAR(200) BINARY COMMENT '3;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `staff_post` VARCHAR(96) BINARY COMMENT '4;スタッフ役職;',
    `tel` VARCHAR(15) BINARY COMMENT '5;電話番号;ハイフン含む',
    `staff_group` VARCHAR(150) BINARY COMMENT '6;所属組織;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `nta_staff` COMMENT '日本旅行スタッフ;';

--   *** ------------------------------------
--  *** A_STAFF_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `nta_staff_account` (
    `nta_staff_id` INT COMMENT '1;日本旅行スタッフID;',
    `account_id` VARCHAR(60) BINARY COMMENT '2;日本旅行アカウントID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;暗号化した値',
    `accept_status` TINYINT COMMENT '4;ステータス;0:利用不可 1:利用可',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `nta_staff_account` COMMENT '日本旅行スタッフアカウント;';

--   *** ------------------------------------
--  *** DER_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `order_grants` (
    `order_cd` VARCHAR(15) BINARY COMMENT '1;予約申込コード;B99999999-NNN （  B+８桁数値-年（桁）月の１６進 ）',
    `welfare_grants_id` BIGINT COMMENT '2;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '3;福利厚生補助金情報履歴ID;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '4;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '5;アフィリエイトコード;YYYYNNNNNN',
    `before_adult_discount_charge` INT DEFAULT 0 COMMENT '6;予約時大人割引料金;予約時点で発行された補助金の金額、更新されない値。',
    `before_child_discount_charge` INT DEFAULT 0 COMMENT '7;予約時子供割引料金;予約時点で発行された補助金の金額、更新されない値。',
    `before_discount_charge` INT DEFAULT 0 COMMENT '8;予約時割引料金;予約時点で発行された補助金の金額合計、更新されない値。',
    `discount_charge` INT DEFAULT 0 COMMENT '9;割引料金;予約時割引料金から変更された割引料金',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `order_grants` COMMENT '予約申込補助金情報;予約申込単位での適用された補助金情報';

--   *** ------------------------------------
--  *** ICO
--   *** ------------------------------------
-- 
CREATE TABLE `orico` (
    `order_id` VARCHAR(100) BINARY COMMENT '1;取引ID;',
    `base_order_id` VARCHAR(100) BINARY COMMENT '2;元取引ID;',
    `item_type` TINYINT COMMENT '3;BR商品タイプ;0:宿泊 1:ベストク',
    `status` VARCHAR(32) BINARY COMMENT '4;処理結果コード;仕様書の詳細結果コードにあたる null 未処理',
    `service_type` VARCHAR(12) BINARY COMMENT '5;決済サービスタイプ;br:後払い card:カード決済 mpi:MPIホスティング cvs:コンビニ決済 em:電子マネー bank:銀行決済 upop:銀レイネット決済 paypal:PayPal決済 alipay:Alipay決済 carrier:キャリア決済 oricosc:ショッピングクレジット決済',
    `service_option_nm` VARCHAR(32) BINARY COMMENT '6;決済サービスオプション;MPIホスティング、コンビニ決済、電子マネー決済、銀レイ決済、キャリア決済で必要',
    `condition` TINYINT COMMENT '7;決済取引処理;-3返金完了 -2:売上キャンセル完了 -1: 与信キャンセル完了 0:与信完了 1:再与信完了 2:売上完了',
    `sales_status` TINYINT COMMENT '8;売上ステータス;0:未売上 1:売上完了',
    `sold_dtm` DATETIME COMMENT '9;売上確定完了日時;',
    `acquire_cd` VARCHAR(2) BINARY COMMENT '10;仕向け先コード;仕向け先カード会社の一覧参照（01:シティカードジャパン 01:JCBなど）',
    `demand_charge` INT COMMENT '11;決済料金;',
    `chargeback_charge` INT COMMENT '12;返金額;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `orico` COMMENT '決済テーブル;';

--   *** ------------------------------------
--  *** ICOPAYMENT
--   *** ------------------------------------
-- 
CREATE TABLE `oricopayment` (
    `authori_ymd` DATETIME COMMENT '1;決済日付;',
    `demand_dtm` DATETIME COMMENT '2;処理日時;キャンセル済みに変更した日時',
    `shop_nm` VARCHAR(55) BINARY COMMENT '3;ショップ名;',
    `order_cd` VARCHAR(16) BINARY COMMENT '4;注文番号;',
    `login_id` VARCHAR(50) BINARY COMMENT '5;会員ID;ベストリザーブ予約情報（reserve)のトランザクションコードを登録',
    `login_nm` VARCHAR(50) BINARY COMMENT '6;氏名漢字;',
    `action_type` VARCHAR(60) BINARY COMMENT '7;処理区分;1;売上 -1: 売上確定後キャンセル',
    `action_status` VARCHAR(30) BINARY COMMENT '8;処理状態;0:未処理 1:処理対象 2:処理完了',
    `action_condition` VARCHAR(30) BINARY COMMENT '9;売上確定状態;',
    `action_ymd` DATETIME COMMENT '10;売上確定日付;',
    `voucher_no` VARCHAR(5) BINARY COMMENT '11;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '12;承認番号;オーソリ正常終了時に設定されます',
    `use_ymd` DATETIME COMMENT '13;利用日;',
    `deal_detail` VARCHAR(50) BINARY COMMENT '14;商品情報;',
    `deal_cnt` SMALLINT COMMENT '15;個数;',
    `deal_charge` INT COMMENT '16;商品金額合計;',
    `deal_tax_charge` INT COMMENT '17;消費税合計;',
    `sent_charge` INT COMMENT '18;配送料金;',
    `charge` INT COMMENT '19;処理金額;',
    `payment_cnt` VARCHAR(30) BINARY COMMENT '20;支払方法;１回払い など',
    `card_company_nm` VARCHAR(30) BINARY COMMENT '21;カード会社名;',
    `demand_charge` INT COMMENT '22;売上確定時金額;',
    `error_cd` VARCHAR(50) BINARY COMMENT '23;エラーコード;',
    `note` VARCHAR(3000) BINARY COMMENT '24;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '25;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '26;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '27;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '28;更新日時;'
);

ALTER TABLE
    `oricopayment` COMMENT 'オリコペイメント売上データ;';

--   *** ------------------------------------
--  *** ICO_RESERVE
--   *** ------------------------------------
-- 
CREATE TABLE `orico_reserve` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `order_id` VARCHAR(100) BINARY COMMENT '3;取引ID;',
    `reauthori_ymd` DATETIME COMMENT '4;再与信予定日;チェックアウト日-60日を予約単位で設定',
    `reauthori_dtm` DATETIME COMMENT '5;再与信完了日時;',
    `sales_ymd` DATETIME COMMENT '6;売上確定予定日;',
    `sold_dtm` DATETIME COMMENT '7;売上確定完了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `orico_reserve` COMMENT '予約決済テーブル;';

--   *** ------------------------------------
--  *** ICO_RESERVE_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `orico_reserve_history` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `branch_no` TINYINT COMMENT '3;枝番;',
    `order_id` VARCHAR(100) BINARY COMMENT '4;取引ID;',
    `item_type` TINYINT COMMENT '5;BR商品タイプ;0:宿泊 1:ベストク',
    `status` VARCHAR(32) BINARY COMMENT '6;処理結果コード;仕様書の詳細結果コードにあたる null 未処理',
    `authori_dtm` DATETIME COMMENT '7;与信完了日時;',
    `service_type` VARCHAR(12) BINARY COMMENT '8;決済サービスタイプ;br:後払い card:カード決済 mpi:MPIホスティング cvs:コンビニ決済 em:電子マネー bank:銀行決済 upop:銀レイネット決済 paypal:PayPal決済 alipay:Alipay決済 carrier:キャリア決済 oricosc:ショッピングクレジット決済',
    `service_option_nm` VARCHAR(32) BINARY COMMENT '9;決済サービスオプション;MPIホスティング、コンビニ決済、電子マネー決済、銀レイ決済、キャリア決済で必要',
    `condition` TINYINT COMMENT '10;決済取引処理;-1: 与信キャンセル完了 0:与信完了 1:再与信完了',
    `demand_charge` INT COMMENT '11;決済料金;',
    `chargeback_charge` INT COMMENT '12;返金額;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `orico_reserve_history` COMMENT '予約与信テーブル履歴;';

--   *** ------------------------------------
--  *** ICO_RETRY
--   *** ------------------------------------
-- 
CREATE TABLE `orico_retry` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `branch_no` TINYINT COMMENT '3;枝番;',
    `task_type` TINYINT COMMENT '4;処理タイプ;0:再与信 1:与信キャンセル 2:与信＆売上 3:売上 4:売上キャンセル',
    `execute_ymd` DATETIME COMMENT '5;実行予定日;',
    `execute_dtm` DATETIME COMMENT '6;実行完了日時;',
    `order_id` VARCHAR(100) BINARY COMMENT '7;取引ID;',
    `base_order_id` VARCHAR(100) BINARY COMMENT '8;元取引ID;',
    `item_type` TINYINT COMMENT '9;BR商品タイプ;0:宿泊 1:ベストク',
    `stock_type` TINYINT COMMENT '10;仕入タイプ;0:受託販売 1:買取販売 2:一括受託（東横イン） -2:一括受託（旧宿ぷらざ）',
    `status` VARCHAR(32) BINARY COMMENT '11;処理結果コード;仕様書の詳細結果コードにあたる null 未処理',
    `service_type` VARCHAR(12) BINARY COMMENT '12;決済サービスタイプ;br:後払い card:カード決済 mpi:MPIホスティング cvs:コンビニ決済 em:電子マネー bank:銀行決済 upop:銀レイネット決済 paypal:PayPal決済 alipay:Alipay決済 carrier:キャリア決済 oricosc:ショッピングクレジット決済',
    `service_option_nm` VARCHAR(32) BINARY COMMENT '13;決済サービスオプション;MPIホスティング、コンビニ決済、電子マネー決済、銀レイ決済、キャリア決済で必要',
    `demand_charge` INT COMMENT '14;決済料金;',
    `chargeback_charge` INT COMMENT '15;返金額;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `orico_retry` COMMENT '再決済処理テーブル;';

--   *** ------------------------------------
--  *** ICO_SALES_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `orico_sales_history` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `branch_no` TINYINT COMMENT '3;枝番;',
    `order_id` VARCHAR(100) BINARY COMMENT '4;取引ID;',
    `item_type` TINYINT COMMENT '5;BR商品タイプ;0:宿泊 1:ベストク',
    `status` VARCHAR(32) BINARY COMMENT '6;処理結果コード;仕様書の詳細結果コードにあたる null 未処理',
    `service_type` VARCHAR(12) BINARY COMMENT '7;決済サービスタイプ;br:後払い card:カード決済 mpi:MPIホスティング cvs:コンビニ決済 em:電子マネー bank:銀行決済 upop:銀レイネット決済 paypal:PayPal決済 alipay:Alipay決済 carrier:キャリア決済 oricosc:ショッピングクレジット決済',
    `service_option_nm` VARCHAR(32) BINARY COMMENT '8;決済サービスオプション;MPIホスティング、コンビニ決済、電子マネー決済、銀レイ決済、キャリア決済で必要',
    `condition` TINYINT COMMENT '9;決済取引処理;-3返金完了 -2:売上キャンセル完了 2:売上完了',
    `acquire_cd` VARCHAR(2) BINARY COMMENT '10;仕向け先コード;仕向け先カード会社の一覧参照（01:シティカードジャパン 01:JCBなど）',
    `demand_charge` INT COMMENT '11;決済料金;',
    `chargeback_charge` INT COMMENT '12;返金額;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `orico_sales_history` COMMENT '予約売上テーブル履歴;';

--   *** ------------------------------------
--  *** ICO_WEBSERVICE
--   *** ------------------------------------
-- 
CREATE TABLE `orico_webservice` (
    `service_cd` VARCHAR(6) BINARY COMMENT '1;サービスコード;YYYYNN',
    `reserve_cd` VARCHAR(50) BINARY COMMENT '2;提携先予約コード;',
    `order_id` VARCHAR(100) BINARY COMMENT '3;ウェブサービス用取引ID;サービスを特定するコード-提携先予約コード-連番',
    `sold_dtm` DATETIME COMMENT '4;売上確定完了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `member_cd` VARCHAR(128) BINARY
);

ALTER TABLE
    `orico_webservice` COMMENT 'ウェブサービス用決済テーブル;';

--   *** ------------------------------------
--  *** A_HOTEL_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `ota_hotel_relation` (
    `ota_hotel_relation_id` BIGINT COMMENT '1;OTA・BR間施設関連ID;',
    `ota_kind` VARCHAR(5) BINARY COMMENT '2;OTA種別;OTAの種類 じゃらん：JRN',
    `br_hotel_cd` VARCHAR(10) BINARY COMMENT '3;BR施設コード;',
    `ota_hotel_cd` VARCHAR(30) BINARY COMMENT '4;OTA施設コード;',
    `notactive_flg` VARCHAR(1) BINARY COMMENT '5;削除フラグ;0:有効 1:無効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `ota_hotel_relation` COMMENT 'OTA・BR間施設関連;OTAからのデータ取り込みを行った際に、OTA⇔BR間施設の関連付けを保持する';

--   *** ------------------------------------
--  *** A_PLAN_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `ota_plan_relation` (
    `ota_plan_relation_id` BIGINT COMMENT '1;OTA・BR間プラン関連ID;',
    `ota_kind` VARCHAR(5) BINARY COMMENT '2;OTA種別;OTAの種類 じゃらん：JRN',
    `br_hotel_cd` VARCHAR(10) BINARY COMMENT '3;BR施設コード;',
    `ota_hotel_cd` VARCHAR(30) BINARY COMMENT '4;OTA施設コード;',
    `br_plan_id` VARCHAR(10) BINARY COMMENT '5;BRプランID;',
    `ota_plan_cd` VARCHAR(30) BINARY COMMENT '6;OTAプランコード;',
    `notactive_flg` VARCHAR(1) BINARY COMMENT '7;削除フラグ;0:有効 1:無効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `ota_plan_relation` COMMENT 'OTA・BR間プラン関連;OTAからのデータ取り込みを行った際に、OTA⇔BR間のプランの関連付けを保持する';

--   *** ------------------------------------
--  *** A_ROOM_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `ota_room_relation` (
    `ota_room_relation_id` BIGINT COMMENT '1;OTA・BR間部屋関連ID;',
    `ota_kind` VARCHAR(5) BINARY COMMENT '2;OTA種別;OTAの種類 じゃらん：JRN',
    `br_hotel_cd` VARCHAR(10) BINARY COMMENT '3;BR施設コード;',
    `ota_hotel_cd` VARCHAR(30) BINARY COMMENT '4;OTA施設コード;',
    `br_room_id` VARCHAR(10) BINARY COMMENT '5;BR部屋ID;',
    `ota_room_cd` VARCHAR(30) BINARY COMMENT '6;OTA部屋コード;',
    `notactive_flg` VARCHAR(1) BINARY COMMENT '7;削除フラグ;0:有効 1:無効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `ota_room_relation` COMMENT 'OTA・BR間部屋関連;OTAからのデータ取り込みを行った際に、OTA⇔BR間の部屋の関連付けを保持する';

--   *** ------------------------------------
--  *** RTNER
--   *** ------------------------------------
-- 
CREATE TABLE `partner` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `partner_nm` VARCHAR(196) BINARY COMMENT '2;提携先名称;',
    `system_nm` VARCHAR(196) BINARY COMMENT '3;システム名称;',
    `partner_ns` VARCHAR(60) BINARY COMMENT '4;提携先略称;',
    `url` VARCHAR(128) BINARY COMMENT '5;ウェブサイトアドレス;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '6;郵便番号;ハイフン含む',
    `address` VARCHAR(300) BINARY COMMENT '7;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '8;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '9;ファックス番号;ハイフン含む',
    `person_post` VARCHAR(96) BINARY COMMENT '10;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '11;担当者名称;',
    `person_kn` VARCHAR(192) BINARY COMMENT '12;担当者かな名称;',
    `person_email` VARCHAR(128) BINARY COMMENT '13;担当者電子メールアドレス;',
    `open_ymd` DATETIME COMMENT '14;公開日;',
    `tieup_ymd` DATETIME COMMENT '15;提携日;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `partner` COMMENT '提携先;';

--   *** ------------------------------------
--  *** RTNER_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `partner_account` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `account_type` TINYINT COMMENT '2;アカウントタイプ;0:スタッフ 1:会員 2:施設 3:施設統括 4:提携先管理者 5:提携先運用者',
    `account_id` VARCHAR(20) BINARY COMMENT '3;アカウントID;入力アカウントIDを大文字に統一した値',
    `password` VARCHAR(64) BINARY COMMENT '4;パスワード;暗号化した値',
    `accept_status` TINYINT COMMENT '5;ステータス;0:利用不可 1:利用可',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `partner_account` COMMENT '提携先アカウント;';

--   *** ------------------------------------
--  *** RTNER_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `partner_book` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約参照コード;',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `billpay_ym` DATETIME COMMENT '3;請求支払処理年月;請求・支払作成実行年月',
    `site_cd` VARCHAR(10) BINARY COMMENT '4;サイトコード;',
    `branch_no` VARCHAR(20) BINARY COMMENT '5;枝番;',
    `site_type` TINYINT COMMENT '6;サイトタイプ;1:partner 2:affiliate',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '7;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '8;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '9;プランID;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '10;ホテル名;',
    `room_nm` VARCHAR(120) BINARY COMMENT '11;部屋名;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '12;プラン名;',
    `sales_charge` INT COMMENT '13;料金;',
    `reserve_status` TINYINT COMMENT '14;予約状態;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `reserve_dtm` DATETIME COMMENT '15;予約受付日時;',
    `cancel_dtm` DATETIME COMMENT '16;キャンセル日時;',
    `order_cd` VARCHAR(15) BINARY COMMENT '17;オーダーコード;',
    `cancel_charge` INT COMMENT '18;キャンセル料金;',
    `rate` DECIMAL(5, 2) COMMENT '19;料率;',
    `tax_charge` INT COMMENT '20;消費税額;最終販売価格に対する消費税額（販売料金 - (販売料金 / 消費税))',
    `stay_tax_charge` INT COMMENT '21;宿泊税;東京都宿泊税など',
    `tax_out_charge` INT COMMENT '22;税抜き価格;',
    `system_fee` INT COMMENT '23;システム利用料;税別 小数点以下切捨て',
    `entry_cd` VARCHAR(64) BINARY COMMENT '24;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '25;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '26;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '27;更新日時;',
    `akafu_status` TINYINT,
    `premium_status` TINYINT,
    `visual_status` TINYINT
);

ALTER TABLE
    `partner_book` COMMENT '提携先実績テーブル;';

--   *** ------------------------------------
--  *** RTNER_BOOK_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `partner_book_account` (
    `customer_id` VARCHAR(20) BINARY COMMENT '1;支払先ID;',
    `account_id` VARCHAR(60) BINARY COMMENT '2;ログインID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;暗号化した値',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `partner_book_account` COMMENT 'ログイン認証;';

--   *** ------------------------------------
--  *** RTNER_BOOK_CUSTOMER
--   *** ------------------------------------
-- 
CREATE TABLE `partner_book_customer` (
    `customer_id` VARCHAR(20) BINARY COMMENT '1;支払先ID;大文字統一',
    `customer_nm` VARCHAR(150) BINARY COMMENT '2;支払先名称;',
    `tel` VARCHAR(15) BINARY COMMENT '3;電話番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '4;チャネル合算支払先;',
    `person_post` VARCHAR(96) BINARY COMMENT '5;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '6;担当者名称;',
    `excel_status` TINYINT COMMENT '7;エクセル作成可否;0 : 作成しない 1 : 作成する',
    `mail_send` TINYINT COMMENT '8;メール送信可否;0:送付しない 1:送付する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `partner_book_customer` COMMENT 'チャネル合算支払先情報;';

--   *** ------------------------------------
--  *** RTNER_BOOK_CUSTOMER_DTL
--   *** ------------------------------------
-- 
CREATE TABLE `partner_book_customer_dtl` (
    `site_cd` VARCHAR(10) BINARY COMMENT '1;サイトコード;',
    `email` VARCHAR(200) BINARY COMMENT '2;チャネル別支払先;',
    `person_post` VARCHAR(96) BINARY COMMENT '3;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '4;担当者名称;',
    `excel_status` TINYINT COMMENT '5;エクセル作成可否;0 : 作成しない 1 : 作成する',
    `mail_send` TINYINT COMMENT '6;メール送信可否;0:送付しない 1:送付する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `partner_book_customer_dtl` COMMENT 'チャネル別支払先情報;';

--   *** ------------------------------------
--  *** RTNER_BOOK_PARTNER
--   *** ------------------------------------
-- 
CREATE TABLE `partner_book_partner` (
    `customer_id` VARCHAR(20) BINARY COMMENT '1;支払先ID;大文字統一',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `site_type` TINYINT COMMENT '3;サイトタイプ;1:partner 2:affiliate',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `partner_book_partner` COMMENT 'アカウント・パートナー（アフィリエイト）match;';

--   *** ------------------------------------
--  *** RTNER_CLOCK
--   *** ------------------------------------
-- 
CREATE TABLE `partner_clock` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `table_name` VARCHAR(30) BINARY COMMENT '2;テーブル名称;',
    `next_dtm` DATETIME COMMENT '3;次回取得日時;前回データ取得時設定された次回取得日時',
    `basic_time` SMALLINT COMMENT '4;基本時間;取得範囲の基準となる時間',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `partner_clock` COMMENT '差分日時管理テーブル;';

--   *** ------------------------------------
--  *** RTNER_CLOUT
--   *** ------------------------------------
-- 
CREATE TABLE `partner_clout` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `request_url` VARCHAR(256) BINARY COMMENT '2;会員認証アドレス;予約の手続き、予約確認のときに入力された会員コード、パスワードを確認するCGIプログラムのアドレス',
    `charset` VARCHAR(16) BINARY DEFAULT 'sjis' Comment '3;キャラクタセット;sjis:シフトJIS euc-jp:日本語EUC 会員認証で戻されるドキュメントの文字コード',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `partner_clout` COMMENT '提携先管理CLOUT用;';

--   *** ------------------------------------
--  *** RTNER_CLUTCH
--   *** ------------------------------------
-- 
CREATE TABLE `partner_clutch` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `request_url` VARCHAR(256) BINARY COMMENT '2;会員認証アドレス;予約の手続き、予約確認のときに入力された会員コード、パスワードを確認するCGIプログラムのアドレス',
    `charset` VARCHAR(16) BINARY DEFAULT 'sjis' Comment '3;キャラクタセット;sjis:シフトJIS euc-jp:日本語EUC 会員認証で戻されるドキュメントの文字コード',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `partner_clutch` COMMENT '提携先管理CLUTCH用;CLUTCH用データ';

--   *** ------------------------------------
--  *** RTNER_CONTROL
--   *** ------------------------------------
-- 
CREATE TABLE `partner_control` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `connect_cls` VARCHAR(12) BINARY COMMENT '2;接続形態;reserve dash clone inquiry gather ccg',
    `connect_type` VARCHAR(12) BINARY COMMENT '3;接続形態（詳細）;reserve dash clone clout clutch livedoor inquiry gather ccg ccg-c',
    `entry_status` TINYINT COMMENT '4;提携先登録状態;0:公開中 1:接続テスト中 9:解約',
    `pw_admin` VARCHAR(64) BINARY COMMENT '5;管理パスワード;暗号化した値',
    `pw_operator` VARCHAR(64) BINARY COMMENT '6;運用パスワード;暗号化した値',
    `pw_user` VARCHAR(12) BINARY COMMENT '7;接続パスワード;',
    `charset` VARCHAR(16) BINARY DEFAULT 'sjis' Comment '8;キャラクタセット;sjis:シフトJIS euc-jp:日本語EUC 会員認証で戻されるドキュメントの文字コード',
    `voice_status` TINYINT COMMENT '9;掲示板表示設定;0:非表示 1:表示',
    `page_timelimit` SMALLINT COMMENT '10;ページ有効時間;',
    `extension_state` TINYINT COMMENT '11;付随情報表示状態;0:非表示 1:表示',
    `stock_type` TINYINT COMMENT '12;販売可能仕入タイプ;1:受託販売 2:買取販売 3の場合は受託＆買取',
    `payment_way` SMALLINT COMMENT '13;販売可能決済方法;1:現地決済 2:クレジット決済 4:銀行振込 5の場合は現地決済＆銀行振り込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;',
    `sales_type` INT,
    `auth_type` VARCHAR(12) BINARY,
    `rate` SMALLINT,
    `partner_point_status` TINYINT,
    `later_payment` TINYINT,
    `version` BIGINT,
    `result_email` VARCHAR(200) BINARY,
    `stayconfirm_status` TINYINT,
    `email_from_nm` VARCHAR(45) BINARY,
    `result_rpc_status` TINYINT,
    `result_rpc_url` VARCHAR(256) BINARY
);

ALTER TABLE
    `partner_control` COMMENT '提携先管理;';

--   *** ------------------------------------
--  *** RTNER_CUSTOMER
--   *** ------------------------------------
-- 
CREATE TABLE `partner_customer` (
    `customer_id` BIGINT COMMENT '1;支払先ID;大文字統一',
    `customer_nm` VARCHAR(150) BINARY COMMENT '2;支払先名称;',
    `postal_cd` VARCHAR(8) BINARY COMMENT '3;郵便番号;ハイフン含む',
    `pref_id` TINYINT COMMENT '4;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '5;住所;市区町村以下',
    `tel` VARCHAR(15) BINARY COMMENT '6;電話番号;ハイフン含む',
    `fax` VARCHAR(15) BINARY COMMENT '7;ファックス番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '8;チャネル合算支払先;',
    `person_post` VARCHAR(96) BINARY COMMENT '9;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '10;担当者名称;',
    `mail_send` TINYINT COMMENT '11;メール送信可否;0:送付しない 1:送付する',
    `cancel_status` TINYINT COMMENT '12;精算キャンセル対象状態;0: 予約のみ 1:キャンセル含む',
    `tax_unit` BIGINT COMMENT '13;消費税単位;1:サイト単位 2:手数料率単位（NTA精算用）',
    `document_type` TINYINT COMMENT '14;精算書タイプ;1: 請求のみ 2:支払のみ 3:両方',
    `detail_status` TINYINT COMMENT '15;明細書有無;0:明細なし 1:明細あり',
    `billpay_day` TINYINT COMMENT '16;精算日;1: 仮締日（毎月１日）  8:本締日（毎月8日）',
    `billpay_required_month` VARCHAR(12) BINARY COMMENT '17;精算必須月;1月から１２月分 の１２桁の01の文字列、1が立ってる桁が精算月になります。（例 ４月精算 = 000100000000)',
    `billpay_charge_min` INT COMMENT '18;精算最低金額;デフォルト: 50000',
    `entry_cd` VARCHAR(64) BINARY COMMENT '19;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '20;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '21;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '22;更新日時;'
);

ALTER TABLE
    `partner_customer` COMMENT '精算先（提携先）;';

--   *** ------------------------------------
--  *** RTNER_CUSTOMER_SITE
--   *** ------------------------------------
-- 
CREATE TABLE `partner_customer_site` (
    `customer_id` BIGINT COMMENT '1;支払先ID;大文字統一',
    `site_cd` VARCHAR(10) BINARY COMMENT '2;サイトコード;',
    `fee_type` TINYINT COMMENT '3;手数料タイプ;1:販売 2:在庫',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `partner_customer_site` COMMENT '精算先関連サイト（提携先）;';

--   *** ------------------------------------
--  *** RTNER_DEFAULT
--   *** ------------------------------------
-- 
CREATE TABLE `partner_default` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `check_in_days` SMALLINT COMMENT '2;チェックイン日数;',
    `stay` SMALLINT COMMENT '3;泊数;',
    `rooms` SMALLINT COMMENT '4;部屋数;',
    `capacity` SMALLINT COMMENT '5;人数;',
    `charge_min` INT COMMENT '6;料金範囲下限;',
    `charge_max` INT COMMENT '7;料金範囲上限;',
    `place_cd` VARCHAR(10) BINARY DEFAULT 'p13' Comment '8;プレイスコード;p+pref_id:都道府県、z+wardzone_id:区地域',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `partner_default` COMMENT '提携先デフォルト;宿泊条件の初期表示内容';

--   *** ------------------------------------
--  *** RTNER_DENY_KEYWORDS
--   *** ------------------------------------
-- 
CREATE TABLE `partner_deny_keywords` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `keyword_group_id` INT COMMENT '2;キーワードグループID;',
    `status` TINYINT COMMENT '3;有効状態;0:無効 1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `partner_deny_keywords` COMMENT '提携先別除外キーワード;キーワード辞書マスタに存在するキーワードを含むプランを除外します。';

--   *** ------------------------------------
--  *** RTNER_GROUP
--   *** ------------------------------------
-- 
CREATE TABLE `partner_group` (
    `partner_group_id` BIGINT COMMENT '1;提携先グループID;',
    `partner_group_nm` VARCHAR(196) BINARY COMMENT '2;提携先グループ名称;',
    `accept_status` TINYINT COMMENT '3;受付状態;0:停止中 1:受付中',
    `target_s_ym` DATETIME COMMENT '4;対象宿泊期間開始年月;',
    `order_no` BIGINT COMMENT '5;表示順序;',
    `note` VARCHAR(150) BINARY COMMENT '6;コメント;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `priority` TINYINT DEFAULT 0
);

ALTER TABLE
    `partner_group` COMMENT '提携先グループ;';

--   *** ------------------------------------
--  *** RTNER_GROUP_JOIN
--   *** ------------------------------------
-- 
CREATE TABLE `partner_group_join` (
    `partner_group_id` BIGINT COMMENT '1;提携先グループID;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '2;提携先コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `partner_group_join` COMMENT '提携先グループ関連;';

--   *** ------------------------------------
--  *** RTNER_INQUIRY
--   *** ------------------------------------
-- 
CREATE TABLE `partner_inquiry` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `in_charset` VARCHAR(16) BINARY COMMENT '2;リクエストキャラクタセット;sjis:シフトJIS euc-jp:日本語EUC utf8:UTF8',
    `out_charset` VARCHAR(16) BINARY COMMENT '3;出力キャラクタセット;sjis:シフトJIS euc-jp:日本語EUC utf8:UTF8',
    `interface_type` TINYINT COMMENT '4;インターフェース形式;0:ジョルダンバージョン 1:一般パートナー向けジョルダンバージョン 2:SW形式 3:プール形式 4:オーシャン形式',
    `format_type` VARCHAR(6) BINARY COMMENT '5;データ形式;csv xml',
    `layout_type` VARCHAR(10) BINARY COMMENT '6;データレイアウト;mobile、pc',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `partner_inquiry` COMMENT '提携先管理用INQUIRY;';

--   *** ------------------------------------
--  *** RTNER_KEYWORD_EXAMPLE
--   *** ------------------------------------
-- 
CREATE TABLE `partner_keyword_example` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `layout_type` BIGINT COMMENT '2;表示場所;0:キーワード 1:駅路線 2:ランドマーク',
    `branch_no` TINYINT COMMENT '3;枝番;',
    `word` VARCHAR(96) BINARY COMMENT '4;表示文字列;',
    `value` VARCHAR(96) BINARY COMMENT '5;値;',
    `order_no` BIGINT COMMENT '6;表示順序;',
    `display_status` TINYINT COMMENT '7;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `partner_keyword_example` COMMENT '提携先別キーワード;';

--   *** ------------------------------------
--  *** RTNER_LAYOUT
--   *** ------------------------------------
-- 
CREATE TABLE `partner_layout` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `version` VARCHAR(8) BINARY COMMENT '2;バージョン;レイアウトのバージョン情報とともに画像の位置も示す',
    `color_bg` VARCHAR(8) BINARY COMMENT '3;背景色;ページの背景色;ページの背景色',
    `color_ft` VARCHAR(8) BINARY COMMENT '4;テキスト色;ページの文字色;ページの文字色',
    `color_lk` VARCHAR(8) BINARY COMMENT '5;リンク;リンクの文字色',
    `color_lv` VARCHAR(8) BINARY COMMENT '6;訪問済みリンク;訪問済みリンクの文字色',
    `color_01` VARCHAR(8) BINARY COMMENT '7;色１;ヘッダ上部の背景色',
    `color_02` VARCHAR(8) BINARY COMMENT '8;色２;ヘッダ下部の背景色',
    `color_03` VARCHAR(8) BINARY COMMENT '9;色３;ヘッダ下部の文字色',
    `color_04` VARCHAR(8) BINARY COMMENT '10;色４;大見出しの背景色',
    `color_05` VARCHAR(8) BINARY COMMENT '11;色５;項目見出しの背景色',
    `color_06` VARCHAR(8) BINARY COMMENT '12;色６;項目見出しの文字色',
    `color_07` VARCHAR(8) BINARY COMMENT '13;色７;大見出しの文字色',
    `page_title` VARCHAR(196) BINARY COMMENT '14;ページタイトル;TITLE要素の内容',
    `page_position` VARCHAR(16) BINARY COMMENT '15;ページポジション;left:左寄せ center:中央寄せ',
    `page_width` INT COMMENT '16;ページ基本幅;ページをレイアウトしているテーブルの最小幅',
    `head_style` TINYINT COMMENT '17;ヘッダ様式;0:定型 1:定型＋オリジナルリンク 2:カスタム',
    `head_text` LONGTEXT COMMENT '18;ヘッダソース;',
    `foot_style` TINYINT COMMENT '19;フッタ様式;0:定型 2:カスタム',
    `foot_text` VARCHAR(768) BINARY COMMENT '20;フッタソース;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '21;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '22;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '23;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '24;更新日時;'
);

ALTER TABLE
    `partner_layout` COMMENT '提携先レイアウト;DASH、CLONEの外観のカスタマイズ項目';

--   *** ------------------------------------
--  *** RTNER_LAYOUT2
--   *** ------------------------------------
-- 
CREATE TABLE `partner_layout2` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `logo_file_nm` VARCHAR(36) BINARY COMMENT '2;ロゴファイル名称;',
    `logo_width` SMALLINT COMMENT '3;ロゴ幅;',
    `logo_height` SMALLINT COMMENT '4;ロゴ高さ;',
    `site_explain` VARCHAR(3000) BINARY COMMENT '5;サイト説明;',
    `site_explain_type` TINYINT COMMENT '6;サイト説明表示タイプ;0:TOPページのみ表示 1:各ページに表示',
    `site_color` TINYINT COMMENT '7;サイトカラー;1:オレンジ 2:ブルー 3:グリーン 4:オリーブイエロー 5:ローズピンク 6:パープル 7:ホワイト',
    `section_type` TINYINT COMMENT '8;所属団体タイプ;0:自由入力 1:選択（partner_section)',
    `charge_min` INT COMMENT '9;料金範囲下限;',
    `charge_max` INT COMMENT '10;料金範囲上限;',
    `voice_status` TINYINT COMMENT '11;掲示板表示設定;0:非表示 1:表示',
    `map_type` TINYINT COMMENT '12;地図タイプ;0:Google 1:Yahoo 2:BingMaps',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `section_label` VARCHAR(18) BINARY,
    `entrance_type` TINYINT,
    `entrance_banner_type` TINYINT
);

ALTER TABLE
    `partner_layout2` COMMENT '提携先レイアウト２;';

--   *** ------------------------------------
--  *** RTNER_LINKS
--   *** ------------------------------------
-- 
CREATE TABLE `partner_links` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `title_01` VARCHAR(50) BINARY COMMENT '2;タイトル１;',
    `title_02` VARCHAR(50) BINARY COMMENT '3;タイトル２;',
    `title_03` VARCHAR(50) BINARY COMMENT '4;タイトル３;',
    `title_04` VARCHAR(50) BINARY COMMENT '5;タイトル４;',
    `title_05` VARCHAR(50) BINARY COMMENT '6;タイトル５;',
    `link_01` VARCHAR(50) BINARY COMMENT '7;リンク１;',
    `link_02` VARCHAR(50) BINARY COMMENT '8;リンク２;',
    `link_03` VARCHAR(50) BINARY COMMENT '9;リンク３;',
    `link_04` VARCHAR(50) BINARY COMMENT '10;リンク４;',
    `link_05` VARCHAR(50) BINARY COMMENT '11;リンク５;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `partner_links` COMMENT '提携先リンク;';

--   *** ------------------------------------
--  *** RTNER_OAUTH2
--   *** ------------------------------------
-- 
CREATE TABLE `partner_oauth2` (
    `client_id` VARCHAR(10) BINARY COMMENT '1;クライアントID;',
    `redirect_uri` VARCHAR(256) BINARY COMMENT '2;リダイレクトURI;',
    `client_secret` VARCHAR(128) BINARY COMMENT '3;クライアントシークレット;',
    `access_token_expire` SMALLINT COMMENT '4;アクセストークン有効期限;',
    `access_token_stamp_cd` VARCHAR(12) BINARY COMMENT '5;アクセストークン発行先情報コード;NULL:設定なし IP:IPアドレス',
    `accept_status` TINYINT COMMENT '6;受付状態;0:停止中 1:受付中',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `skip_authorization` TINYINT
);

ALTER TABLE
    `partner_oauth2` COMMENT 'Oauth管理テーブル;';

--   *** ------------------------------------
--  *** RTNER_OAUTH2_MEMBER
--   *** ------------------------------------
-- 
CREATE TABLE `partner_oauth2_member` (
    `client_id` VARCHAR(10) BINARY COMMENT '1;クライアントID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `authorization_cd` VARCHAR(128) BINARY COMMENT '3;認可コード;',
    `access_token` VARCHAR(128) BINARY COMMENT '4;アクセストークン;',
    `access_token_limit` DATETIME COMMENT '5;アクセストークン期限;',
    `access_token_stamp` VARCHAR(512) BINARY COMMENT '6;アクセストークン発行先情報;発行先のIPアドレスなどアクセストークン発行先情報コードで保持する情報が決定する',
    `accept_status` TINYINT COMMENT '7;受入許可;0:拒否 1:許可',
    `relation_member_cd` VARCHAR(32) BINARY COMMENT '8;提供会員コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `partner_oauth2_member` COMMENT 'Oauth 会員テーブル;';

--   *** ------------------------------------
--  *** RTNER_OPENID
--   *** ------------------------------------
-- 
CREATE TABLE `partner_openid` (
    `client_id` VARCHAR(10) BINARY COMMENT '1;クライアントID;リライングパーティー (Relying Party:RP)',
    `return_uri` VARCHAR(256) BINARY COMMENT '2;リターンURI;',
    `accept_status` TINYINT COMMENT '3;受付状態;0:停止中 1:受付中',
    `skip_authorization` TINYINT COMMENT '4;認証画面スキップ;0:認証画面をスキップしない。1:ログイン時で同意を得られている場合は認証画面をスキップ',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `partner_openid` COMMENT 'OpenID管理テーブル;';

--   *** ------------------------------------
--  *** RTNER_OPENID_MEMBER
--   *** ------------------------------------
-- 
CREATE TABLE `partner_openid_member` (
    `client_id` VARCHAR(10) BINARY COMMENT '1;クライアントID;リライングパーティー (Relying Party:RP)',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `access_token` VARCHAR(128) BINARY COMMENT '3;アクセストークン;',
    `access_token_limit` DATETIME COMMENT '4;アクセストークン期限;',
    `accept_status` TINYINT COMMENT '5;受入許可;0:拒否 1:許可',
    `relation_member_cd` VARCHAR(32) BINARY COMMENT '6;提供会員コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `partner_openid_member` COMMENT 'OpenID 会員テーブル;';

--   *** ------------------------------------
--  *** RTNER_POOL
--   *** ------------------------------------
-- 
CREATE TABLE `partner_pool` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `password` VARCHAR(64) BINARY COMMENT '2;パスワード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `partner_pool` COMMENT '提携先（プール）;';

--   *** ------------------------------------
--  *** RTNER_POOL2
--   *** ------------------------------------
-- 
CREATE TABLE `partner_pool2` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `password` VARCHAR(64) BINARY COMMENT '2;パスワード;暗号化した値',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `partner_pool2` COMMENT '募集型企画旅行関係提携先情報;';

--   *** ------------------------------------
--  *** RTNER_SECTION
--   *** ------------------------------------
-- 
CREATE TABLE `partner_section` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `section_id` TINYINT COMMENT '2;所属団体ID;',
    `section_nm` VARCHAR(96) BINARY COMMENT '3;所属団体名称;',
    `order_no` BIGINT COMMENT '4;表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `partner_section` COMMENT '提携先所属団体リスト;';

--   *** ------------------------------------
--  *** RTNER_SITE
--   *** ------------------------------------
-- 
CREATE TABLE `partner_site` (
    `site_cd` VARCHAR(10) BINARY COMMENT '1;サイトコード;YYYYMM9999',
    `site_nm` VARCHAR(196) BINARY COMMENT '2;提携先サイト名称;',
    `email` VARCHAR(200) BINARY COMMENT '3;チャネル別支払先;',
    `person_post` VARCHAR(96) BINARY COMMENT '4;担当者役職;',
    `person_nm` VARCHAR(96) BINARY COMMENT '5;担当者名称;',
    `mail_send` TINYINT COMMENT '6;メール送信可否;0:送付しない 1:送付する',
    `partner_cd` VARCHAR(10) BINARY COMMENT '7;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '8;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `partner_site` COMMENT '提携サイト;';

--   *** ------------------------------------
--  *** RTNER_SITE_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `partner_site_rate` (
    `site_cd` VARCHAR(10) BINARY COMMENT '1;サイトコード;',
    `accept_s_ymd` DATETIME COMMENT '2;開始日;1:partner 2:affiliate',
    `fee_type` TINYINT COMMENT '3;手数料タイプ;1:販売  2:在庫',
    `stock_class` TINYINT COMMENT '4;在庫種類;1:一般ネット在庫 2:連動在庫（通常） 3:連動在庫（ヴィジュアル） 4:連動在庫（プレミアム） 5:東横イン在庫',
    `rate` DECIMAL(4, 2) COMMENT '5;手数料率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `partner_site_rate` COMMENT '提携先サイト手数料率;';

--   *** ------------------------------------
--  *** RTNER_YDP
--   *** ------------------------------------
-- 
CREATE TABLE `partner_ydp` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '2;アフィリエイトコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `partner_ydp` COMMENT '日本旅行コーポレートプラン移行対応;';

--   *** ------------------------------------
--  *** YMENT
--   *** ------------------------------------
-- 
CREATE TABLE `payment` (
    `payment_id` DECIMAL(32, 0) COMMENT '1;入金情報ID;',
    `match_complete_dtm` DATETIME COMMENT '2;引当完了日時;未完了の場合＝NULL',
    `reference_number` VARCHAR(16) BINARY COMMENT '3;照会番号;必ずしもユニークではないので注意すること',
    `transfer_ymd` DATETIME COMMENT '4;預入・払出日;1:入金 2:出金',
    `in_out_type` SMALLINT COMMENT '5;入払区分;',
    `account_type` INT COMMENT '6;取引区分;10:現金 11:振込 12:他点券入金 13:交換 14:振替 18:その他 19:訂正',
    `account_charge` DECIMAL(32, 0) COMMENT '7;取引金額;',
    `acc_client_cd` VARCHAR(60) BINARY COMMENT '8;振込依頼人コード;',
    `acc_client_nm` VARCHAR(1024) BINARY COMMENT '9;振込依頼人;',
    `acc_client_bank_nm` VARCHAR(256) BINARY COMMENT '10;仕向銀行名;',
    `acc_client_branch_nm` VARCHAR(256) BINARY COMMENT '11;仕向支店名;',
    `own_bank_cd` VARCHAR(4) BINARY COMMENT '12;自社銀行コード;',
    `own_bank_brach_cd` VARCHAR(3) BINARY COMMENT '13;自社銀行支店コード;',
    `own_bank_accnout_type` SMALLINT COMMENT '14;自社預金種目;1:普通 2:当座',
    `own_bank_accnout_no` VARCHAR(60) BINARY COMMENT '15;自社預金口座番号;',
    `account_ymd` DATETIME COMMENT '16;勘定日;BRでは未使用',
    `another_charge` DECIMAL(32, 0) COMMENT '17;うち他店券金額;BRでは未使用',
    `swap_disp_ymd` DATETIME COMMENT '18;交換提示日;BRでは未使用',
    `delay_ret_ymd` DATETIME COMMENT '19;不渡返還日;BRでは未使用',
    `bill_check_type` SMALLINT COMMENT '20;手形・小切手区分;BRでは未使用',
    `bill_check_no` VARCHAR(14) BINARY COMMENT '21;手形・小切手番号;BRでは未使用',
    `staff_shop_no` VARCHAR(6) BINARY COMMENT '22;僚店番号;BRでは未使用',
    `note` VARCHAR(60) BINARY COMMENT '23;摘要内容;BRでは未使用',
    `edi_info` VARCHAR(60) BINARY COMMENT '24;EDI情報;BRでは未使用',
    `entry_cd` VARCHAR(64) BINARY COMMENT '25;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '26;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '27;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '28;更新日時;'
);

ALTER TABLE
    `payment` COMMENT '入金情報;SMBCからの入金を登録する';

--   *** ------------------------------------
--  *** YMENT_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `payment_match` (
    `payment_match_id` DECIMAL(32, 0) COMMENT '1;入金引当状態ID;',
    `payment_match_dtm` DATETIME COMMENT '2;引当日時;',
    `payment_id` DECIMAL(32, 0) COMMENT '3;入金情報ID;',
    `billpay_cd` VARCHAR(12) BINARY COMMENT '4;		請求支払コード;',
    `billpay_branch_no` TINYINT COMMENT '5;請求支払枝番;1以上の場合、請求・支払コードに「-」で結合して表示する （ YYYYMM9999A-9 ）',
    `pcg_bill_id` DECIMAL(32, 0) COMMENT '6;PCG請求ID;',
    `match_charge` DECIMAL(32, 0) COMMENT '7;引当額;',
    `adjust_charge` DECIMAL(32, 0) DEFAULT 0 COMMENT '9;調整金額;引当額のうち入金額以外に調整した金額を入れる(例：引落から請求に変えた際の差額100円ならここに＋100を設定）',
    `note` VARCHAR(3000) BINARY COMMENT '9;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `payment_match` COMMENT '入金引当状態;入金引当状態を保存する';

--   *** ------------------------------------
--  DDL for Table PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `plan_type` VARCHAR(10) BINARY COMMENT '3;プランタイプ;null:通常 fss:金土日',
    `plan_nm` VARCHAR(375) BINARY COMMENT '4;プラン名称;ベストリザーブは40文字',
    `charge_type` TINYINT COMMENT '5;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '6;定員;ルームチャージの場合は最小と最大が同じ',
    `payment_way` TINYINT COMMENT '7;決済方法;1:事前カード決済 2:現地決済 3:事前カード決済、現地決済選択',
    `stay_limit` TINYINT COMMENT '8;最低連泊数;',
    `order_no` BIGINT COMMENT '9;管理画面表示順序;',
    `active_status` TINYINT COMMENT '10;システム取扱状態;0:停止中 1:受付中',
    `display_status` TINYINT COMMENT '11;表示ステータス;0:非表示 1:表示',
    `label_cd` VARCHAR(10) BINARY COMMENT '12;プランラベル;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;',
    `accept_s_ymd` DATETIME,
    `accept_e_ymd` DATETIME,
    `accept_e_day` TINYINT,
    `accept_e_hour` VARCHAR(5) BINARY,
    `accept_status` TINYINT,
    `check_in` VARCHAR(5) BINARY,
    `check_in_end` VARCHAR(5) BINARY,
    `check_out` VARCHAR(5) BINARY,
    `stay_cap` TINYINT,
    `user_side_order_no` BIGINT
);

ALTER TABLE
    `plan` COMMENT '新プラン;';

--   *** ------------------------------------
--  *** AN_AKF_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `plan_akf_relation` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `plan_cd_akf` VARCHAR(20) BINARY COMMENT '3;NTAプランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `plan_akf_relation` COMMENT '日本旅行プラン関連（リロ）;';

--   *** ------------------------------------
--  *** AN_CANCEL_POLICY
--   *** ------------------------------------
-- 
CREATE TABLE `plan_cancel_policy` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `cancel_policy` VARCHAR(2850) BINARY COMMENT '3;キャンセルポリシー;200文字',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `plan_cancel_policy` COMMENT 'プランキャンセルポリシー PLN155;';

--   *** ------------------------------------
--  *** AN_CANCEL_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `plan_cancel_rate` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `days` SMALLINT COMMENT '3;宿泊日からの日数;',
    `cancel_rate` SMALLINT COMMENT '4;キャンセル料率;',
    `policy_status` TINYINT COMMENT '5;プランポリシーステータス;0:適用外 1:適用中 プラン単位に設定されます。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `plan_cancel_rate` COMMENT 'プランキャンセル料率 PLN154;';

--   *** ------------------------------------
--  *** AN_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `plan_grants` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `grants_status` SMALLINT COMMENT '3;補助金利用可否;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `plan_grants` COMMENT 'プラン補助金設定情報;プラン毎の補助金利用制限を保持する';

--   *** ------------------------------------
--  *** AN_HOLD_KEYWORDS
--   *** ------------------------------------
-- 
CREATE TABLE `plan_hold_keywords` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `keyword_id` INT COMMENT '3;キーワードID;',
    `status` TINYINT COMMENT '4;有効状態;0:無効 1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `plan_hold_keywords` COMMENT 'プランに含まれるキーワード;プランにキーワード辞書マスタのキーワードが含まれているものを抽出';

--   *** ------------------------------------
--  *** AN_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `plan_info` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `info` VARCHAR(4000) BINARY COMMENT '3;特色;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `plan_info` COMMENT 'プラン特色 PLN143;';

--   *** ------------------------------------
--  *** AN_JR
--   *** ------------------------------------
-- 
CREATE TABLE `plan_jr` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '3;プラン名称;ベストリザーブは40文字',
    `info` VARCHAR(4000) BINARY COMMENT '4;プラン特色;',
    `accept_status` TINYINT COMMENT '5;予約受付状態;0:停止中 1:受付中',
    `active_status` TINYINT COMMENT '6;システム取扱状態;0:停止中 1:受付中',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `info_use_status` TINYINT COMMENT '11;プラン特色使用状態;プラン特色の使用状態 0:使用しない 1:使用する'
);

ALTER TABLE
    `plan_jr` COMMENT 'プラン情報（JRセットプラン）;JRセットプラン専用プラン情報';

--   *** ------------------------------------
--  *** AN_MEDIA
--   *** ------------------------------------
-- 
CREATE TABLE `plan_media` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `media_no` SMALLINT COMMENT '3;メディアNo;ベストリザーブは3文字',
    `order_no` SMALLINT COMMENT '4;画像表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `plan_media` COMMENT 'プランメディア PLN144;';

--   *** ------------------------------------
--  *** AN_PARTNER_GROUP
--   *** ------------------------------------
-- 
CREATE TABLE `plan_partner_group` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `partner_group_id` BIGINT COMMENT '3;提携先グループID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `plan_partner_group` COMMENT 'プラン提携先グループ;';

--   *** ------------------------------------
--  *** AN_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `plan_point` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `issue_point_rate` SMALLINT COMMENT '3;付与ポイント率;',
    `issue_point_rate_our` SMALLINT COMMENT '4;獲得ポイント当社負担率;',
    `point_status` TINYINT COMMENT '5;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '6;増量単位;',
    `min_point` INT COMMENT '7;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '8;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `plan_point` COMMENT 'プランポイント設定情報 PLN157;';

--   *** ------------------------------------
--  *** AN_POINT_20170101
--   *** ------------------------------------
-- 
CREATE TABLE `plan_point_20170101` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `issue_point_rate` SMALLINT COMMENT '3;獲得ポイント率;Yahoo!ポイント専用、BRは通常は1%、プレミアムは2%',
    `copy_status` TINYINT COMMENT '4;コピー状況;0:未コピー 1:コピー済',
    `attribute` VARCHAR(256) BINARY COMMENT '5;補足情報;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `pp_issue_point_rate` SMALLINT COMMENT '10;PLAN_POINT更新前獲得ポイント率;PLAN_POINTのポイント付与率更新直前にBKとして現在のPLAN_POINTのポイント率を保持する項目',
    `pp_last_modify_cd` VARCHAR(64) BINARY COMMENT '11;PLAN_POINT更新前最終更新者;PLAN_POINTのポイント付与率更新直前にPLAN_POINT最終更新者(modify_cd)を保持する項目',
    `pp_last_upd_dtm` DATETIME COMMENT '12;PLAN_POINT更新前最終更新時間;PLAN_POINTのポイント付与率更新直前にPLAN_POINT最終更新時間(modify_ts)を保持する項目',
    `pp_issue_point_rate_our` SMALLINT COMMENT '13;PLAN_POINT更新前当社負担率;PLAN_POINTの当社ポイント負担率更新直前にBKとして現在のPLAN_POINTのポイント負担率を保持する項目'
);

ALTER TABLE
    `plan_point_20170101` COMMENT 'プランポイント設定情報_20170101_ポイント率改定用;20170101からの施設ポイント負担率改定用のテーブル';

--   *** ------------------------------------
--  *** AN_SPEC
--   *** ------------------------------------
-- 
CREATE TABLE `plan_spec` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;YYYYNNNNNN',
    `element_id` SMALLINT COMMENT '3;要素ID;',
    `element_value_id` TINYINT COMMENT '4;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `plan_spec` COMMENT 'プランスペック PLN142;';

--   *** ------------------------------------
--  *** AN_STATUS_POOL2
--   *** ------------------------------------
-- 
CREATE TABLE `plan_status_pool2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `last_modify_dtm` DATETIME COMMENT '5;審査関連情報最終更新日時;',
    `judge_status` TINYINT COMMENT '6;審査状態;0:審査中 1:審査OK 2:審査NG 3:停止',
    `judge_message` VARCHAR(4000) BINARY COMMENT '7;審査メッセージ;',
    `judge_word` VARCHAR(1800) BINARY COMMENT '8;審査ワード;タブ区切り',
    `judge_condition` TINYINT COMMENT '9;審査区分;null : 審査中 0:システムNG 1:NGワード 2:NG表記（遠回しな言い方）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;',
    `judge_s_dtm` DATETIME,
    `judge_dtm` DATETIME
);

ALTER TABLE
    `plan_status_pool2` COMMENT 'プラン審査状態;募集型企画旅行プランの最新の審査状況';

--   *** ------------------------------------
--  *** AN_TABLE
--   *** ------------------------------------
-- 
CREATE TABLE `plan_table` (
    `statement_id` VARCHAR(30) BINARY,
    `plan_id` DOUBLE,
    `timestamp` DATETIME,
    `remarks` VARCHAR(4000) BINARY,
    `operation` VARCHAR(30) BINARY,
    `options` VARCHAR(255) BINARY,
    `object_node` VARCHAR(128) BINARY,
    `object_owner` VARCHAR(30) BINARY,
    `object_name` VARCHAR(30) BINARY,
    `object_alias` VARCHAR(65) BINARY,
    `object_instance` DECIMAL(38, 0),
    `object_type` VARCHAR(30) BINARY,
    `optimizer` VARCHAR(255) BINARY,
    `search_columns` DOUBLE,
    `id` DECIMAL(38, 0),
    `parent_id` DECIMAL(38, 0),
    `depth` DECIMAL(38, 0),
    `position` DECIMAL(38, 0),
    `cost` DECIMAL(38, 0),
    `cardinality` DECIMAL(38, 0),
    `bytes` DECIMAL(38, 0),
    `other_tag` VARCHAR(255) BINARY,
    `partition_start` VARCHAR(255) BINARY,
    `partition_stop` VARCHAR(255) BINARY,
    `partition_id` DECIMAL(38, 0),
    `other` LONGTEXT,
    `distribution` VARCHAR(30) BINARY,
    `cpu_cost` DECIMAL(38, 0),
    `io_cost` DECIMAL(38, 0),
    `temp_space` DECIMAL(38, 0),
    `access_predicates` VARCHAR(4000) BINARY,
    `filter_predicates` VARCHAR(4000) BINARY,
    `projection` VARCHAR(4000) BINARY,
    `time` DECIMAL(38, 0),
    `qblock_name` VARCHAR(30) BINARY,
    `other_xml` LONGTEXT
);

--   *** ------------------------------------
--  *** AN_TYK
--   *** ------------------------------------
-- 
CREATE TABLE `plan_tyk` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `tyk_plan_cd` VARCHAR(20) BINARY COMMENT '3;東横インプランコード;',
    `dtm_status` VARCHAR(1) BINARY COMMENT '4;後払DTM状態;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `plan_tyk` COMMENT '東横インプラン関連付けテーブル;';

--   *** ------------------------------------
--  *** AN_YDK
--   *** ------------------------------------
-- 
CREATE TABLE `plan_ydk` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `plan_cd_ydk` VARCHAR(20) BINARY COMMENT '3;YDKプランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `plan_ydk` COMMENT '宿研部屋関連付けテーブル;';

--   *** ------------------------------------
--  *** INT
--   *** ------------------------------------
-- 
CREATE TABLE `point` (
    `point_id` BIGINT COMMENT '1;ポイント付与ID;',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `date_ymd` DATETIME COMMENT '3;宿泊日;ポイント内容区分１２：宿泊の時のみセット',
    `issue_dtm` DATETIME COMMENT '4;付与日時;',
    `issue_point` BIGINT DEFAULT 0 COMMENT '5;付与ポイント;',
    `point_type` TINYINT COMMENT '6;ポイント内容タイプ;00:調整値 01:マイナスポイント調整 11:入会 12:宿泊 13:宿泊ボーナスポイント 14:エンターテイメントキャンペーン 15:オペレータ操作による付与 21:ギフト交換 51:有効期限切れ 71:宿泊ポイント取消 72:宿泊ボーナスポイント取消 73:オペレータ操作による強制取消 81:ギフト交換の取消 82:ギフト交換のオペレータによる取消',
    `limit_ymd` DATETIME COMMENT '7;有効期限日;',
    `stay_mark` TINYINT DEFAULT 0 COMMENT '8;宿泊マーク;宿泊したら1、取消したら-1　宿泊に関係のない時は0',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '9;予約コード;YYYYMMNNNNNNNN',
    `order_cd` VARCHAR(10) BINARY COMMENT '10;申込みコード;YYYYNNNNNN',
    `get_link` BIGINT COMMENT '11;取得リンク;関連ポイント付与ID',
    `get_point` BIGINT DEFAULT 0 COMMENT '12;取得ポイント;関連ポイント付与IDより取得したポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `point` COMMENT 'ポイント付与テーブル;';

--   *** ------------------------------------
--  *** INT_BONUS
--   *** ------------------------------------
-- 
CREATE TABLE `point_bonus` (
    `point_bonus_id` INT COMMENT '1;宿泊ボーナスポイントID;',
    `stay_count` SMALLINT DEFAULT 0 COMMENT '2;付与泊数;ボーナスポイントを付与する宿泊数',
    `issue_point` BIGINT DEFAULT 0 COMMENT '3;付与ポイント数;',
    `loop_status` TINYINT DEFAULT 0 COMMENT '4;最大値ループ状態;0：最大値ループ無し　1：最大値ループ有り',
    `accept_s_ymd` DATETIME COMMENT '5;付与適用日;この日付以降で宿泊ボーナスポイントを計算',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `point_bonus` COMMENT '宿泊ボーナスポイント管理;';

--   *** ------------------------------------
--  *** INT_CAMP
--   *** ------------------------------------
-- 
CREATE TABLE `point_camp` (
    `point_camp_cd` VARCHAR(8) BINARY COMMENT '1;ポイントキャンペーンコード;YYYYMM99',
    `point_camp_key` VARCHAR(10) BINARY COMMENT '2;キャンペーンキー;半角英数字最大10文字',
    `point_camp_nm` VARCHAR(200) BINARY COMMENT '3;キャンペーン名称;',
    `entry_s_dtm` DATETIME COMMENT '4;申込開始日時;',
    `entry_e_dtm` DATETIME COMMENT '5;申込終了日時;',
    `stay_s_ymd` DATETIME COMMENT '6;対象宿泊期間開始日付;',
    `stay_e_ymd` DATETIME COMMENT '7;対象宿泊期間終了日付;',
    `limit_ymd` DATETIME COMMENT '8;受取期日;',
    `winner_cnt` SMALLINT COMMENT '9;当選者数;',
    `point` INT COMMENT '10;付与ポイント数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `point_camp` COMMENT 'ポイントキャンペーン;ポイントキャンペーン用マスタテーブル';

--   *** ------------------------------------
--  *** INT_CAMP_ORDER
--   *** ------------------------------------
-- 
CREATE TABLE `point_camp_order` (
    `point_camp_cd` VARCHAR(8) BINARY COMMENT '1;ポイントキャンペーンコード;YYYYMM99',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `valid_status` TINYINT COMMENT '3;参加権利状態;ヌル値:応募中  0:無効 1:有効',
    `winner_status` TINYINT COMMENT '4;当選状況; ヌル値:選定待ち  0:落選 1:当選',
    `winner_mail_status` TINYINT COMMENT '5;当選通知状況;ヌル値:選定待ち 0:未通知 1:通知済',
    `agree_status` TINYINT COMMENT '6;受取承諾状況;ヌル値:選定待ち 0:受取待ち 1:受取済み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `valid_email` VARCHAR(200) BINARY
);

ALTER TABLE
    `point_camp_order` COMMENT 'ポイント還元キャンペーン応募;';

--   *** ------------------------------------
--  *** INT_CAMP_WINNING_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `point_camp_winning_rsv` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `point_camp_cd` VARCHAR(8) BINARY COMMENT '2;ポイントキャンペーンコード;YYYYMM99',
    `member_cd` VARCHAR(128) BINARY COMMENT '3;会員コード;ベストリザーブ会員は20バイト',
    `point` INT COMMENT '4;付与ポイント数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `point_camp_winning_rsv` COMMENT 'ポイントキャンペーン当選予約情報;ポイントキャンペーンで予約単位で当選対象を決定する場合に使用';

--   *** ------------------------------------
--  *** EVENT_ACCESSES
--   *** ------------------------------------
-- 
CREATE TABLE `prevent_accesses` (
    `account_key` VARCHAR(128) BINARY COMMENT '1;アカウント特定CD;ユーザ向けサービスの場合:member_cd 管理システムの場合:operator_cd（施設なら施設コード スタッフならスタッフID）',
    `uri` VARCHAR(200) BINARY COMMENT '2;リクエストURI;',
    `request_dtm` DATETIME COMMENT '3;リクエスト日時;この日付より３秒間は同一アカウント特定CD、リクエストURIにてアクセス防止',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `prevent_accesses` COMMENT '重複アクセス防止;ダブルクリックなどで処理が重なることを防止するテーブル';

--   *** ------------------------------------
--  *** NKING_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `ranking_hotel` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `ranking_unit` VARCHAR(20) BINARY COMMENT '2;集計単位;week:前週　month：前月',
    `reserve_count` SMALLINT COMMENT '3;予約数;',
    `stay_count` SMALLINT COMMENT '4;宿泊数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `ranking_hotel` COMMENT 'ホテル管理画面ランキング;';

--   *** ------------------------------------
--  *** CEIPT_POWER
--   *** ------------------------------------
-- 
CREATE TABLE `receipt_power` (
    `id` INT COMMENT '1;ID;',
    `stock_power_id` INT COMMENT '2;パワー仕入れID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `date_ymd` DATETIME COMMENT '4;日付;',
    `receipt_charge` INT COMMENT '5;入金額;',
    `receipt_status` TINYINT COMMENT '6;入金状況;0：未入金　1：入金済',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `receipt_power` COMMENT 'パワー入金テーブル;';

--   *** ------------------------------------
--  *** CORD_HOTEL_PLAN_COUNT
--   *** ------------------------------------
-- 
CREATE TABLE `record_hotel_plan_count` (
    `record_dtm` DATETIME COMMENT '1;集計日時;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `accept_status` TINYINT COMMENT '3;予約受付状態;0:停止中 1:受付中',
    `accept_dtm` DATETIME COMMENT '4;予約受付状態更新日時;',
    `count_total` INT COMMENT '5;プラン数合計;',
    `count_sales` INT COMMENT '6;プラン数販売中;料金登録あり、部屋登録あり、販売中',
    `count_stop` INT COMMENT '7;プラン数休止中;料金登録あり、部屋登録あり、休止中',
    `count_room_none` INT COMMENT '8;プラン数部屋なし;料金登録あり、部屋登録なし',
    `count_room_zero` INT COMMENT '9;プラン数部屋ゼロ;料金登録あり 部屋登録ゼロ',
    `count_charge_none` INT COMMENT '10;プラン数料金なし;料金登録なし',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `count_card_avail` INT COMMENT '15;プラン数カード決済あり;',
    `count_card_avail_sales` INT COMMENT '15;プラン数カード決済あり販売中;',
    `akafu_status` TINYINT COMMENT '17;赤い風船在庫利用施設;0:利用否 1:利用施設;',
    `all_room_type_count_sales` BIGINT COMMENT '18;部屋タイプ数販売中全て;',
    `akafu_room_type_count_sales` BIGINT COMMENT '19;部屋タイプ数販売中連動在庫;',
    `partner_group_br_sales` INT COMMENT '20;販売中BR共通在庫プラン;',
    `partner_group_other_sales` INT COMMENT '21;販売中提携先専用プラン;'
);

ALTER TABLE
    `record_hotel_plan_count` COMMENT '施設別プラン登録数;';

--   *** ------------------------------------
--  *** CORD_HOTEL_RESERVE
--   *** ------------------------------------
-- 
CREATE TABLE `record_hotel_reserve` (
    `date_ymd` DATETIME COMMENT '1;日付;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `capacity` TINYINT COMMENT '3;集計定員;1:１名利用 2:２名利用以上',
    `submit_transaction_count` INT COMMENT '4;操作予約回数;',
    `submit_reserve_count` INT COMMENT '5;操作予約泊数;予約泊数=操作予約泊数-操作キャンセル泊数-操作強制キャンセル泊数-操作無断不泊泊数',
    `submit_cancel_count` INT COMMENT '6;操作キャンセル泊数;電話キャンセル、不泊の泊数は含まれません。',
    `submit_force_count` INT COMMENT '7;操作強制キャンセル泊数;',
    `submit_noshow_count` INT COMMENT '8;操作無断不泊泊数;',
    `stay_reserve_count` INT COMMENT '9;宿泊予約泊数;宿泊数=宿泊予約泊数-宿泊キャンセル泊数-宿泊強制キャンセル泊数-宿泊無断不泊泊数',
    `stay_cancel_count` INT COMMENT '10;宿泊キャンセル泊数;',
    `stay_force_count` INT COMMENT '11;宿泊強制キャンセル泊数;',
    `stay_noshow_count` INT COMMENT '12;宿泊無断不泊泊数;',
    `stay_total_charge` BIGINT COMMENT '13;宿泊合計料金;税サ込。宿泊税、入湯税などは含まない。',
    `stay_total_commission` BIGINT COMMENT '14;売上合計金額;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `record_hotel_reserve` COMMENT '日次集計（ホテル泊数）;';

--   *** ------------------------------------
--  *** CORD_HOTEL_VIEW
--   *** ------------------------------------
-- 
CREATE TABLE `record_hotel_view` (
    `date_ymd` DATETIME COMMENT '1;日付;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `page_type` VARCHAR(16) BINARY COMMENT '3;ページタイプ;hotel:情報ページ vacant:プランカレンダ',
    `page_view_count` INT COMMENT '4;ページビュー数;',
    `uniq_user_count` INT COMMENT '5;ユニークユーザ数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `record_hotel_view` COMMENT '日次集計（ホテルビュー数）;';

--   *** ------------------------------------
--  *** CORD_MOBILE_RESERVE
--   *** ------------------------------------
-- 
CREATE TABLE `record_mobile_reserve` (
    `date_ymd` DATETIME COMMENT '1;日付;',
    `career_type` TINYINT COMMENT '2;キャリアタイプ; 0:不明、1:docomo、2:au、3:softbank',
    `submit_reserve_count` INT COMMENT '3;操作予約泊数;予約泊数=操作予約泊数-操作キャンセル泊数',
    `submit_cancel_count` INT COMMENT '4;操作キャンセル泊数;',
    `submit_immediate_count` INT COMMENT '5;即日キャンセル操作泊数;',
    `stay_reserve_count` INT COMMENT '6;宿泊予約泊数;',
    `stay_cancel_count` INT COMMENT '7;宿泊キャンセル泊数;',
    `session_count` INT COMMENT '8;セッション数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `record_mobile_reserve` COMMENT '日次集計モバイル（泊数）;';

--   *** ------------------------------------
--  *** CORD_MOBILE_VARIOUS
--   *** ------------------------------------
-- 
CREATE TABLE `record_mobile_various` (
    `date_ymd` DATETIME COMMENT '1;日付;',
    `member_entry_count` INT COMMENT '2;会員申込数;',
    `member_withdraw_count` INT COMMENT '3;会員退会数;',
    `member_total` INT COMMENT '4;会員登録累計数;',
    `uniq_user_count` INT COMMENT '5;ユニークユーザ数;',
    `first_visit_count` INT COMMENT '6;全ページビュー数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `record_mobile_various` COMMENT '日次集計モバイル（その他）;';

--   *** ------------------------------------
--  *** CORD_RESERVE
--   *** ------------------------------------
-- 
CREATE TABLE `record_reserve` (
    `date_ymd` DATETIME COMMENT '1;日付;YYYY年MM月DD日',
    `system_type` TINYINT COMMENT '2;システムタイプ;0:リザーブ 1:ストリーム',
    `submit_reserve_count` INT COMMENT '3;予約操作泊数;',
    `submit_cancel_count` INT COMMENT '4;キャンセル操作泊数;電話キャンセル、不泊の泊数は含まれません。',
    `submit_immediate_count` INT COMMENT '5;即日キャンセル操作泊数;',
    `stay_reserve_count` INT COMMENT '6;宿泊泊数;',
    `stay_cancel_count` INT COMMENT '7;宿泊キャンセル泊数;電話キャンセル、不泊の泊数は含まれません。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `record_reserve` COMMENT '日次集計（泊数）;';

--   *** ------------------------------------
--  *** CORD_RESERVE2
--   *** ------------------------------------
-- 
CREATE TABLE `record_reserve2` (
    `date_ymd` DATETIME COMMENT '1;年月日;YYYY年MM月DD日',
    `record_type` SMALLINT COMMENT '2;レコードタイプ;0:全体 1:ベストリザーブ 2:ベストリザーブ スマートフォン 3:ヤフートラベル 4:ヤフートラベル スマートフォン 5:東横イン',
    `submit_transaction_count` INT DEFAULT 0 COMMENT '3;操作予約回数;',
    `submit_reserve_count` INT DEFAULT 0 COMMENT '4;操作予約泊数;',
    `submit_reserve_charge` BIGINT DEFAULT 0 COMMENT '5;操作予約料金;',
    `submit_cancel_count` INT DEFAULT 0 COMMENT '6;操作キャンセル泊数;',
    `submit_cancel_charge` BIGINT DEFAULT 0 COMMENT '7;操作キャンセル料金;',
    `stay_reserve_count` INT DEFAULT 0 COMMENT '8;宿泊予約泊数;',
    `stay_reserve_charge` BIGINT DEFAULT 0 COMMENT '9;宿泊予約料金;',
    `stay_cancel_count` INT DEFAULT 0 COMMENT '10;宿泊キャンセル泊数;',
    `stay_cancel_charge` BIGINT DEFAULT 0 COMMENT '11;宿泊キャンセル料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `record_reserve2` COMMENT '	日次集計（泊数２）;';

--   *** ------------------------------------
--  *** CORD_VARIOUS
--   *** ------------------------------------
-- 
CREATE TABLE `record_various` (
    `date_ymd` DATETIME COMMENT '1;日付;YYYY年MM月DD日',
    `member_entry_count` INT COMMENT '2;会員申込数;',
    `member_commit_count` INT COMMENT '3;会員確定数;',
    `member_withdraw_count` INT COMMENT '4;会員退会数;',
    `member_total` INT COMMENT '5;会員登録累計数;',
    `hotel_total` INT COMMENT '6;施設登録累計数;集計時点',
    `first_visit_count` INT COMMENT '7;訪問数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `first_visit_count_mypage` INT COMMENT '8;ＭＹページ訪問数;トップページ訪問数に含まれます。',
    `member_rsv_entry_count` INT,
    `member_rsv_commit_count` INT,
    `member_rsv_withdraw_count` INT,
    `member_rsv_total` INT,
    `member_dash_entry_count` INT,
    `member_dash_commit_count` INT,
    `member_dash_withdraw_count` INT,
    `member_dash_total` INT
);

ALTER TABLE
    `record_various` COMMENT '日次集計（その他）;';

--   *** ------------------------------------
--  *** PORT_GENDER_AGE
--   *** ------------------------------------
-- 
CREATE TABLE `report_gender_age` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ymd` DATETIME COMMENT '2;日付;',
    `age` SMALLINT COMMENT '3;年齢;',
    `gender` VARCHAR(1) BINARY COMMENT '4;性別;m:男性 f:女性',
    `stay_count` SMALLINT COMMENT '5;宿泊数;',
    `reserve_count` SMALLINT COMMENT '6;予約数;',
    `fix_status` TINYINT COMMENT '7;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `report_gender_age` COMMENT '年齢・男女別集計表;予泊数集計表にも使用';

--   *** ------------------------------------
--  *** PORT_LEAD_TIME
--   *** ------------------------------------
-- 
CREATE TABLE `report_lead_time` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ymd` DATETIME COMMENT '2;日付;',
    `lead_day` SMALLINT COMMENT '3;リードタイム;',
    `stay_count` SMALLINT COMMENT '4;宿泊数;',
    `fix_status` TINYINT COMMENT '5;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `report_lead_time` COMMENT 'リードタイム集計表;';

--   *** ------------------------------------
--  *** PORT_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `report_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ymd` DATETIME COMMENT '2;日付;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `charge_type` TINYINT COMMENT '5;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '6;定員;ルームチャージの場合は最小と最大が同じ',
    `stay_count` SMALLINT COMMENT '7;宿泊数;',
    `stay_max_charge` INT COMMENT '8;宿泊最大料金;',
    `stay_min_charge` INT COMMENT '9;宿泊最小料金;',
    `stay_total_charge` INT COMMENT '10;宿泊合計料金;',
    `reserve_count` SMALLINT COMMENT '11;予約数;',
    `reserve_max_charge` INT COMMENT '12;予約最大料金;',
    `reserve_min_charge` INT COMMENT '13;予約最小料金;',
    `reserve_total_charge` INT COMMENT '14;予約合計料金;',
    `fix_status` TINYINT COMMENT '15;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `report_plan` COMMENT 'プラン別集計表;';

--   *** ------------------------------------
--  *** PORT_PLAN2
--   *** ------------------------------------
-- 
CREATE TABLE `report_plan2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ymd` DATETIME COMMENT '2;日付;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '4;プランID;YYYYNNNNNN',
    `charge_type` TINYINT COMMENT '5;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '6;定員;ルームチャージの場合は最小と最大が同じ',
    `stay_count` SMALLINT COMMENT '7;宿泊数;',
    `stay_max_charge` INT COMMENT '8;宿泊最大料金;',
    `stay_min_charge` INT COMMENT '9;宿泊最小料金;',
    `stay_total_charge` INT COMMENT '10;宿泊合計料金;',
    `reserve_count` SMALLINT COMMENT '11;予約数;',
    `reserve_max_charge` INT COMMENT '12;予約最大料金;',
    `reserve_min_charge` INT COMMENT '13;予約最小料金;',
    `reserve_total_charge` INT COMMENT '14;予約合計料金;',
    `fix_status` TINYINT COMMENT '15;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `report_plan2` COMMENT 'プラン別集計表 SYS126;';

--   *** ------------------------------------
--  *** PORT_PREF
--   *** ------------------------------------
-- 
CREATE TABLE `report_pref` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ymd` DATETIME COMMENT '2;日付;',
    `pref_id` TINYINT COMMENT '3;都道府県ID;',
    `stay_count` SMALLINT COMMENT '4;宿泊数;',
    `reserve_count` SMALLINT COMMENT '5;予約数;',
    `fix_status` TINYINT COMMENT '6;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `report_pref` COMMENT '出身地集計表;';

--   *** ------------------------------------
--  *** PORT_WEEK_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `report_week_hotel` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ymd` DATETIME COMMENT '2;日付;',
    `charge_type` TINYINT COMMENT '3;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '4;定員;ルームチャージの場合は最小と最大が同じ',
    `wday` TINYINT COMMENT '5;曜日;1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜',
    `stay_count` SMALLINT COMMENT '6;宿泊数;',
    `stay_max_charge` INT COMMENT '7;宿泊最大料金;',
    `stay_min_charge` INT COMMENT '8;宿泊最小料金;',
    `stay_total_charge` INT COMMENT '9;宿泊合計料金;',
    `reserve_count` SMALLINT COMMENT '10;予約数;',
    `reserve_max_charge` INT COMMENT '11;予約最大料金;',
    `reserve_min_charge` INT COMMENT '12;予約最小料金;',
    `reserve_total_charge` INT COMMENT '13;予約合計料金;',
    `fix_status` TINYINT COMMENT '14;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '15;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '16;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '17;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '18;更新日時;'
);

ALTER TABLE
    `report_week_hotel` COMMENT '施設別曜日別平均単価集計表;';

--   *** ------------------------------------
--  *** PORT_WEEK_HOTEL2
--   *** ------------------------------------
-- 
CREATE TABLE `report_week_hotel2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `reserve_ymd` DATETIME COMMENT '2;予約日;',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `charge_type` TINYINT COMMENT '4;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '5;定員;ルームチャージの場合は最小と最大が同じ',
    `stay_week` TINYINT COMMENT '6;宿泊曜日;',
    `reserve_week` TINYINT COMMENT '7;予約曜日;',
    `stay_count` SMALLINT COMMENT '8;宿泊数;',
    `stay_max_charge` INT COMMENT '9;宿泊最大料金;',
    `stay_min_charge` INT COMMENT '10;宿泊最小料金;',
    `stay_total_charge` INT COMMENT '11;宿泊合計料金;',
    `fix_status` TINYINT COMMENT '12;確定ステータス;0:確定 1:速報',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `report_week_hotel2` COMMENT '施設別曜日別平均単価集計表２;';

--   *** ------------------------------------
--  *** SERVE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `partner_ref` VARCHAR(40) BINARY COMMENT '3;提携先参照コード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '4;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '5;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '6;アフィリエイトコード枝番;',
    `member_cd` VARCHAR(128) BINARY COMMENT '7;会員コード;ベストリザーブ会員は20バイト',
    `guests` TINYINT COMMENT '8;宿泊人数;',
    `auth_type` VARCHAR(12) BINARY COMMENT '9;認証タイプ;free:非会員認証 bestreserve:会員認証 partner:提携先会員認証',
    `reserve_system` VARCHAR(12) BINARY COMMENT '10;予約システム;reserve:リザーブ dash:ダッシュ clone:クローン inquiry:インクイリー',
    `reserve_status` TINYINT COMMENT '11;予約ステータス;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `reserve_dtm` DATETIME COMMENT '12;予約受付日時;',
    `cancel_dtm` DATETIME COMMENT '13;取り消し日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;',
    `hotel_cd` VARCHAR(10) BINARY,
    `transaction_cd` VARCHAR(14) BINARY,
    `order_cd` VARCHAR(15) BINARY,
    `reserve_type` TINYINT
);

ALTER TABLE
    `reserve` COMMENT '予約テーブル;';

--   *** ------------------------------------
--  *** SERVE_ADDED_GOTO
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_added_goto` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `added_type` SMALLINT COMMENT '2;後付けタイプ;1. 標準メッセージ （予約に応じてメッセージを変えられるように準備しておく）',
    `apply_dtm` DATETIME COMMENT '3;後付承認日時;会員がGOTO付与を選択入力した日時',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `reserve_added_goto` COMMENT 'GOTO後付対象予約;GoTo後付対象の予約に対して作られるレコード';

--   *** ------------------------------------
--  *** SERVE_ADDED_MESSAGE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_added_message` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `msg_type` DECIMAL(22, 0) COMMENT '2;メッセージの目的;1:Gotoクーポン取消し　（要件が増えたら随時追加する）',
    `msg_for_hotel` VARCHAR(3000) BINARY COMMENT '3;施設向けメッセージ;',
    `msg_for_guest` VARCHAR(3000) BINARY COMMENT '4;お客様向けメッセージ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `reserve_added_message` COMMENT '予約追加メッセージ;BRからの予約への追加情報';

--   *** ------------------------------------
--  *** SERVE_AKAFU
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_akafu` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '2;部屋タイプコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `reserve_akafu` COMMENT '予約赤い風船部屋情報;';

--   *** ------------------------------------
--  *** SERVE_AUTHORI
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_authori` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `authori_status` TINYINT COMMENT '2;オーソリステータス;0:オーソリ 1:一部キャンセル 2:キャンセル',
    `sales_dtm` DATETIME COMMENT '3;売上データ作成日時;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '4;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '5;カード有効期限;',
    `demand_charge` INT COMMENT '6;売上料金;',
    `authori_dtm` DATETIME COMMENT '7;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(16) BINARY COMMENT '8;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '9;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `reserve_authori` COMMENT 'オーソリ状況;最後に行ったオーソリの内容を保持します。';

--   *** ------------------------------------
--  *** SERVE_AUTHORI_ZAP
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_authori_zap` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `condition` VARCHAR(16) BINARY COMMENT '3;予約状態;reserve:予約 cancel:キャンセル',
    `sales_dtm` DATETIME COMMENT '4;売上データ作成日時;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '5;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '6;カード有効期限;',
    `card_owner` VARCHAR(50) BINARY COMMENT '7;カード氏名;',
    `demand_charge` INT COMMENT '8;売上料金;',
    `authori_dtm` DATETIME COMMENT '9;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(16) BINARY COMMENT '10;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '11;承認番号;オーソリ正常終了時に設定されます',
    `c_demand_charge` INT COMMENT '12;キャンセルフィー;',
    `c_authori_dtm` DATETIME COMMENT '13;キャンセルフィーオーソリ日時;オーソリ処理日時',
    `c_voucher_no` VARCHAR(16) BINARY COMMENT '14;キャンセルフィー伝票番号;オーソリ正常終了時に設定されます',
    `c_approval_no` VARCHAR(7) BINARY COMMENT '15;キャンセルフィー承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '16;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '17;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '18;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '19;更新日時;'
);

ALTER TABLE
    `reserve_authori_zap` COMMENT '旧オーソリ状況テーブル;';

--   *** ------------------------------------
--  *** SERVE_BASE_CHARGE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_base_charge` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `capacity` SMALLINT COMMENT '2;人数;',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `usual_charge` INT COMMENT '4;大人一人通常料金;',
    `usual_charge_revise` TINYINT COMMENT '5;大人一人通常料金補正値;',
    `sales_charge` INT COMMENT '6;大人一人販売料金;',
    `sales_charge_revise` TINYINT COMMENT '7;大人一人販売料金補正地;',
    `accept_status` TINYINT COMMENT '8;予約受付状態;0:停止中 1:受付中',
    `accept_s_dtm` DATETIME COMMENT '9;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '10;終了日時;',
    `low_price_status` TINYINT COMMENT '11;最安値宣言ステータス;0:宣言しない 1:宣言する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;',
    `org_modify_cd` VARCHAR(64) BINARY COMMENT '16;元更新者コード;予約時のchargeテーブルの/controller/action.(user_id) または 更新者メールアドレス',
    `org_modify_ts` DATETIME COMMENT '17;元更新日時;予約時のchargeテーブルの更新日時'
);

ALTER TABLE
    `reserve_base_charge` COMMENT '予約時料金;';

--   *** ------------------------------------
--  *** SERVE_BASE_CHILD
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_base_child` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `child1_accept` TINYINT COMMENT '2;子供1部屋受入;大人に順ずる食事と寝具（0:受け入れない 1:受け入れる）',
    `child2_accept` TINYINT COMMENT '3;子供2部屋受入;子供用の食事と寝具（0:受け入れない 1:受け入れる）',
    `child3_accept` TINYINT COMMENT '4;子供3部屋受入;子供用の寝具（0:受け入れない 1:受け入れる）',
    `child4_accept` TINYINT COMMENT '5;子供4部屋受入;子供用の食事（0:受け入れない 1:受け入れる）',
    `child5_accept` TINYINT COMMENT '6;子供5部屋受入;食事寝具なし（0:受け入れない 1:受け入れる）',
    `child1_person` DECIMAL(2, 1) DEFAULT 1 COMMENT '7;子供1部屋人数係数;',
    `child2_person` DECIMAL(2, 1) DEFAULT 1 COMMENT '8;子供2部屋人数係数;',
    `child3_person` DECIMAL(2, 1) DEFAULT 1 COMMENT '9;子供3部屋人数係数;',
    `child4_person` DECIMAL(2, 1) DEFAULT 0 COMMENT '10;子供4部屋人数係数;',
    `child5_person` DECIMAL(2, 1) DEFAULT 0 COMMENT '11;子供5部屋人数係数;',
    `child1_charge_include` TINYINT COMMENT '12;子供1料金計算時の定員に含める;0:含めない 1:含める',
    `child2_charge_include` TINYINT COMMENT '13;子供2料金計算時の定員に含める;0:含めない 1:含める',
    `child3_charge_include` TINYINT COMMENT '14;子供3料金計算時の定員に含める;0:含めない 1:含める',
    `child4_charge_include` TINYINT COMMENT '15;子供4料金計算時の定員に含める;0:含めない 1:含める',
    `child5_charge_include` TINYINT COMMENT '16;子供5料金計算時の定員に含める;0:含めない 1:含める',
    `child1_charge_unit` TINYINT COMMENT '17;子供1料金単位;0:率 1;金額 2:差額',
    `child2_charge_unit` TINYINT COMMENT '18;子供2料金単位;0:率 1;金額 2:差額',
    `child3_charge_unit` TINYINT COMMENT '19;子供3料金単位;0:率 1;金額 2:差額',
    `child4_charge_unit` TINYINT COMMENT '20;子供4料金単位;0:率 1;金額 2:差額',
    `child5_charge_unit` TINYINT COMMENT '21;子供5料金単位;0:率 1;金額 2:差額',
    `child1_charge` INT COMMENT '22;子供1料金;',
    `child2_charge` INT COMMENT '23;子供2料金;',
    `child3_charge` INT COMMENT '24;子供3料金;',
    `child4_charge` INT COMMENT '25;子供4料金;',
    `child5_charge` INT COMMENT '26;子供5料金;',
    `child1_rate` SMALLINT COMMENT '27;子供1率;',
    `child2_rate` SMALLINT COMMENT '28;子供2率;',
    `child3_rate` SMALLINT COMMENT '29;子供3率;',
    `child4_rate` SMALLINT COMMENT '30;子供4率;',
    `child5_rate` SMALLINT COMMENT '31;子供5率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '32;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '33;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '34;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '35;更新日時;'
);

ALTER TABLE
    `reserve_base_child` COMMENT '予約時子供人数情報;';

--   *** ------------------------------------
--  *** SERVE_BATH_TAX
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_bath_tax` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `bath_tax_charge` INT COMMENT '2;入湯税金額（大人）;',
    `bath_tax_charge_child` INT COMMENT '3;入湯税金額（子供）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `reserve_bath_tax` COMMENT '予約時入湯税;';

--   *** ------------------------------------
--  *** SERVE_CAMP
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_camp` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `camp_cd` VARCHAR(10) BINARY COMMENT '2;キャンペーンID;YYYYMMNNNN',
    `camp_nm` VARCHAR(96) BINARY COMMENT '3;キャンペーン名称;',
    `description` VARCHAR(4000) BINARY COMMENT '4;詳細;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `reserve_camp` COMMENT '予約キャンペーン情報;予約時のキャンペーン情報を保持';

--   *** ------------------------------------
--  *** SERVE_CANCEL_POLICY
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_cancel_policy` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `cancel_policy` VARCHAR(2850) BINARY COMMENT '2;キャンセルポリシー;200文字',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `reserve_cancel_policy` COMMENT '予約キャンセルポリシー;';

--   *** ------------------------------------
--  *** SERVE_CANCEL_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_cancel_rate` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `days` SMALLINT COMMENT '2;宿泊日からの日数;',
    `cancel_rate` SMALLINT COMMENT '3;キャンセル料率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `reserve_cancel_rate` COMMENT '予約キャンセル料率;';

--   *** ------------------------------------
--  *** SERVE_CHARGE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_charge` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `partner_group_id` BIGINT COMMENT '3;提携先グループID;',
    `usual_charge` INT COMMENT '4;通常料金;',
    `discount_type` TINYINT COMMENT '5;施設割引料金タイプ;0:通常 1:早割 2:当日',
    `before_sales_charge` INT COMMENT '6;予約時販売料金;データ作成時の販売料金(変更不可） 税サ込み',
    `sales_charge` INT COMMENT '7;販売料金;総額を持ちます（販売料金 + 税 - 割引料金）',
    `tax_charge` INT COMMENT '8;消費税額;最終販売価格に対する消費税額（販売料金 - (販売料金 / 1.05))',
    `stay_tax_charge` INT COMMENT '9;宿泊税;東京都宿泊税など',
    `cancel_charge` INT COMMENT '10;キャンセル料金;税サ込',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `payment_way` TINYINT,
    `system_rate` SMALLINT,
    `tax_rate` TINYINT,
    `sales_rate` DECIMAL(4, 2),
    `stock_rate` DECIMAL(4, 2),
    `issue_point_rate` DECIMAL(5, 2),
    `issue_point_rate_our` DECIMAL(5, 2),
    `later_payment` TINYINT,
    `base_cancel_charge` INT
);

ALTER TABLE
    `reserve_charge` COMMENT '予約料金;';

--   *** ------------------------------------
--  *** SERVE_CHARGE_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_charge_detail` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `capacity_type` VARCHAR(50) BINARY COMMENT '3;利用者タイプ;0 : 大人、1 : 子供１（大人と同等の食事と寝具）、2 : 子供２（子供用の食事と寝具）、3 : 子供３（食事なし、子供用の寝具）、4 : 子供４（子供用の食事、寝具なし）、5 : 子供５（食事なし、寝具なし）',
    `capacity` SMALLINT COMMENT '4;人数;',
    `sales_charge` INT COMMENT '5;一人販売料金;',
    `sales_charge_revise` INT COMMENT '6;販売料金補正値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `reserve_charge_detail` COMMENT '予約料金詳細（利用者タイプ別）;';

--   *** ------------------------------------
--  *** SERVE_CHARGE_DISCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_charge_discount` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `discount_factor_id` TINYINT DEFAULT 0 COMMENT '3;割引要素ID;1:パワーダウンチャージ 2:Y!ポイント',
    `discount_charge` INT DEFAULT 0 COMMENT '4;割引料金;税込み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `before_discount_charge` INT
);

ALTER TABLE
    `reserve_charge_discount` COMMENT '予約割引料金;';

--   *** ------------------------------------
--  *** SERVE_CONTACT
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_contact` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `email` VARCHAR(200) BINARY COMMENT '2;電子メールアドレス;暗号化',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `member_nm` VARCHAR(90) BINARY COMMENT '3;予約者氏名;',
    `email_row` VARCHAR(200) BINARY COMMENT '9;電子メールアドレス（平文）;平文の電子メールアドレス（email）',
    `member_nm_kn` VARCHAR(90) BINARY COMMENT '4;予約者氏名（かな）;'
);

ALTER TABLE
    `reserve_contact` COMMENT '予約連絡先情報;予約完了メールの送信先電子メールアドレス';

--   *** ------------------------------------
--  *** SERVE_CREDIT
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_credit` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `authori_status` TINYINT COMMENT '3;オーソリステータス;0:オーソリ 1:一部キャンセル 2:キャンセル（料金０円）',
    `sales_status` TINYINT COMMENT '4;売り上げステータス;0:未売り上げ 1:売り上げ済み',
    `sales_dtm` DATETIME COMMENT '5;売上データ作成日時;オーソリステータス「2」の場合はNULLのまま',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '6;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '7;カード有効期限;',
    `demand_charge` INT COMMENT '8;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '9;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '10;端末コード;03232:パワーホテル',
    `authori_dtm` DATETIME COMMENT '11;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(5) BINARY COMMENT '12;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '13;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;',
    `card_id` TINYINT
);

ALTER TABLE
    `reserve_credit` COMMENT 'クレジットカード決済オーソリ;';

--   *** ------------------------------------
--  *** SERVE_CREDIT_DEV
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_credit_dev` (
    `reserve_cd` VARCHAR(14) BINARY,
    `date_ymd` DATETIME,
    `authori_status` TINYINT,
    `sales_status` TINYINT,
    `sales_dtm` DATETIME,
    `card_company_cd` VARCHAR(4) BINARY,
    `card_id` TINYINT,
    `card_limit_ym` DATETIME,
    `demand_charge` INT,
    `mall_cd` VARCHAR(7) BINARY,
    `terminal_cd` VARCHAR(5) BINARY,
    `authori_dtm` DATETIME,
    `voucher_no` VARCHAR(5) BINARY,
    `approval_no` VARCHAR(7) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** SERVE_CREDIT_FLUCTUATE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_credit_fluctuate` (
    `id` INT COMMENT '1;ID;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `authori_condition` TINYINT COMMENT '4;オーソリ状態;0:オーソリ対象 1:キャンセル（オーソリ対象外）',
    `authoring_ymd` DATETIME COMMENT '5;オーソリ予定日;',
    `selling_ymd` DATETIME COMMENT '6;売り上げ確定予定日;',
    `authori_status` TINYINT COMMENT '7;オーソリ完了ステータス;0:未オーソリ 1:オーソリ済み',
    `sales_status` TINYINT COMMENT '8;売り上げステータス;0:未売り上げ 1:売り上げ済み',
    `authori_dtm` DATETIME COMMENT '9;オーソリ日時;オーソリ処理日時',
    `sales_dtm` DATETIME COMMENT '10;売上データ作成日時;オーソリステータス「2」の場合はNULLのまま',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '11;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '12;カード有効期限;',
    `demand_charge` INT COMMENT '13;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '14;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '15;端末コード;03232:パワーホテル',
    `voucher_no` VARCHAR(5) BINARY COMMENT '16;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '17;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '18;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '19;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '20;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '21;更新日時;'
);

ALTER TABLE
    `reserve_credit_fluctuate` COMMENT '増減額オーソリテーブル;';

--   *** ------------------------------------
--  *** SERVE_DISPOSE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose` (
    `dispose_id` BIGINT COMMENT '1;赤伝ID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `operation_dtm` DATETIME COMMENT '5;操作日時;',
    `billpayed_status` TINYINT COMMENT '6;清算状態;0：未清算 1:清算済',
    `reserve_status` TINYINT COMMENT '7;予約ステータス;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `sales_charge` INT COMMENT '8;販売料金（変更後）;',
    `card_charge` INT COMMENT '9;事前カード決済金額（変更後）;',
    `discount_charge` INT COMMENT '10;割引金額（変更後）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `site_cd` VARCHAR(10) BINARY,
    `fee_type` TINYINT
);

ALTER TABLE
    `reserve_dispose` COMMENT '赤伝票;';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_9xg` (
    `dispose_id` BIGINT COMMENT '1;赤伝ID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `operation_dtm` DATETIME COMMENT '5;操作日時;',
    `billpayed_status` TINYINT COMMENT '6;清算状態;0：未清算 1:清算済',
    `reserve_status` TINYINT COMMENT '7;予約ステータス;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `sales_charge` INT COMMENT '8;販売料金（変更後）;',
    `card_charge` INT COMMENT '9;事前カード決済金額（変更後）;',
    `discount_charge` INT COMMENT '10;割引金額（変更後）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `site_cd` VARCHAR(10) BINARY COMMENT '15;サイトコード;',
    `fee_type` TINYINT COMMENT '16;手数料タイプ;1:販売  2:在庫（NTA）'
);

ALTER TABLE
    `reserve_dispose_9xg` COMMENT '赤伝票_テスト用;赤伝票本番テスト用';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_grants` (
    `dispose_grants_id` BIGINT COMMENT '1;赤伝補助金ID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `customer_type` TINYINT COMMENT '4;精算先タイプ;0：提携先 1：施設',
    `site_cd` VARCHAR(10) BINARY COMMENT '5;サイトコード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '6;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '7;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '8;アフィリエイトコード枝番;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '9;施設コード;',
    `welfare_grants_id` BIGINT COMMENT '10;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '11;福利厚生補助金履歴ID;',
    `operation_dtm` DATETIME COMMENT '12;操作日時;',
    `billpayed_status` TINYINT COMMENT '13;清算状態;0：未清算 1:清算済',
    `reserve_status` TINYINT COMMENT '14;予約ステータス;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `sales_charge` INT COMMENT '15;販売料金（変更後）;',
    `discount_charge` INT COMMENT '16;割引金額（変更後）;',
    `use_grants` VARCHAR(50) BINARY COMMENT '17;補助金額(変更後);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '18;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '19;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '20;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '21;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `reserve_dispose_grants` COMMENT '赤伝票補助金;補助金用赤伝票';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_GRANTS_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_grants_9xg` (
    `dispose_grants_id` BIGINT COMMENT '1;赤伝補助金ID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `customer_type` TINYINT COMMENT '4;精算先タイプ;0：提携先 1：施設',
    `site_cd` VARCHAR(10) BINARY COMMENT '5;サイトコード;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '6;提携先コード;',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '7;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(20) BINARY COMMENT '8;アフィリエイトコード枝番;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '9;施設コード;',
    `welfare_grants_id` BIGINT COMMENT '10;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '11;福利厚生補助金履歴ID;',
    `operation_dtm` DATETIME COMMENT '12;操作日時;',
    `billpayed_status` TINYINT COMMENT '13;清算状態;0：未清算 1:清算済',
    `reserve_status` TINYINT COMMENT '14;予約ステータス;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `sales_charge` INT COMMENT '15;販売料金（変更後）;',
    `discount_charge` INT COMMENT '16;割引金額（変更後）;',
    `use_grants` VARCHAR(50) BINARY COMMENT '17;補助金額(変更後);',
    `entry_cd` VARCHAR(64) BINARY COMMENT '18;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '19;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '20;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '21;更新日時;',
    `coupon_flg` TINYINT
);

ALTER TABLE
    `reserve_dispose_grants_9xg` COMMENT '赤伝票補助金_テスト;';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_RSV
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_rsv` (
    `dispose_rsv_id` BIGINT COMMENT '1;赤伝リザーブID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `operation_dtm` DATETIME COMMENT '5;操作日時;',
    `billpayed_status` TINYINT COMMENT '6;清算状態;0：未清算 1:清算済',
    `use_br_point_charge` INT COMMENT '7;消費ＢＲポイント割引料金（変更後）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `get_br_point_charge` INT
);

ALTER TABLE
    `reserve_dispose_rsv` COMMENT '赤伝票リザーブ;ＢＲポイント割引料金';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_RSV_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_rsv_9xg` (
    `dispose_rsv_id` BIGINT COMMENT '1;赤伝リザーブID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `operation_dtm` DATETIME COMMENT '5;操作日時;',
    `billpayed_status` TINYINT COMMENT '6;清算状態;0：未清算 1:清算済',
    `get_br_point_charge` INT COMMENT '7;獲得ＢＲポイント（変更後）;',
    `use_br_point_charge` INT COMMENT '8;消費ＢＲポイント割引料金（変更後）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `reserve_dispose_rsv_9xg` COMMENT '赤伝票リザーブ_テスト用;赤伝票リザーブ本番テスト用';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_yahoo` (
    `dispose_yahoo_id` BIGINT COMMENT '1;赤伝ヤフーID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `operation_dtm` DATETIME COMMENT '5;操作日時;',
    `billpayed_status` TINYINT COMMENT '6;清算状態;0：未清算 1:清算済',
    `get_yahoo_point` INT COMMENT '7;獲得ヤフーポイント（変更後）;',
    `use_yahoo_point` INT COMMENT '8;消費ヤフーポイント（変更後）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `reserve_dispose_yahoo` COMMENT '赤伝票ヤフー;';

--   *** ------------------------------------
--  *** SERVE_DISPOSE_YAHOO_9XG
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_dispose_yahoo_9xg` (
    `dispose_yahoo_id` BIGINT COMMENT '1;赤伝ヤフーID;YYYYMM連番４桁（連番は、年月内の最大値+1)',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '4;施設コード;',
    `operation_dtm` DATETIME COMMENT '5;操作日時;',
    `billpayed_status` TINYINT COMMENT '6;清算状態;0：未清算 1:清算済',
    `get_yahoo_point` INT COMMENT '7;獲得ヤフーポイント（変更後）;',
    `use_yahoo_point` INT COMMENT '8;消費ヤフーポイント（変更後）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `reserve_dispose_yahoo_9xg` COMMENT '赤伝票ヤフー_テスト用;赤伝票ヤフー本番テスト用';

--   *** ------------------------------------
--  *** SERVE_EXTENSION
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_extension` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `extension_value` VARCHAR(3000) BINARY COMMENT '2;付随情報;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `reserve_extension` COMMENT '予約付随情報;';

--   *** ------------------------------------
--  *** SERVE_FIX
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_fix` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `fix_type` SMALLINT COMMENT '3;確定タイプ;1:ポイント利用 2:ポイント発行 3:クレジット（パワー以外）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `reserve_fix` COMMENT '予約確定テーブル;';

--   *** ------------------------------------
--  *** SERVE_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_grants` (
    `order_cd` VARCHAR(14) BINARY COMMENT '1;予約申込コード;B99999999-NNN （ B+８桁数値-年（桁）月の１６進 ）',
    `welfare_grants_history_id` VARCHAR(10) BINARY COMMENT '2;福利厚生補助金情報履歴ID;welfare_grants.welfare_grants_idのFK',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '3;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '4;宿泊日;',
    `before_discount_charge` INT DEFAULT 0 COMMENT '5;予約時割引料金;予約時点で発行された補助金の金額、更新されない値。',
    `discount_charge` INT DEFAULT 0 COMMENT '6;割引料金;予約時割引料金から変更された割引料金',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `coupon_flg` TINYINT,
    `note` VARCHAR(256) BINARY
);

ALTER TABLE
    `reserve_grants` COMMENT '予約補助金情報;福利厚生補助で予約した予約CDと補助金情報登録';

--   *** ------------------------------------
--  *** SERVE_GUEST
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_guest` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `check_in` VARCHAR(5) BINARY COMMENT '2;チェックイン時刻;',
    `guest_nm` VARCHAR(75) BINARY COMMENT '3;宿泊代表者氏名;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '8;宿泊代表者電話番号;',
    `guest_group` VARCHAR(150) BINARY COMMENT '11;宿泊代表者所属団体;',
    `note` VARCHAR(600) BINARY COMMENT '20;宿泊者備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;',
    `smoke` TINYINT COMMENT '12;宿泊者禁煙喫煙希望状態;0:なし 1:禁煙ルーム 2:喫煙ルーム 3:禁煙喫煙（どちらでもよい） ヌル値： 不明（備考に文字列として設定）、部屋で禁煙喫煙が決定されている場合は「0:なし」が適用',
    `guest_address` VARCHAR(600) BINARY COMMENT '10;宿泊代表者住所;市区町村以下',
    `male` TINYINT COMMENT '13;宿泊人数男性;',
    `female` TINYINT COMMENT '14;宿泊人数女性;',
    `guest_pref_id` TINYINT COMMENT '9;宿泊代表者都道府県ID;',
    `child1` TINYINT COMMENT '15;宿泊人数子供１;大人と同等の食事と寝具',
    `child2` TINYINT COMMENT '16;宿泊人数子供２;子供用の食事と寝具',
    `child3` TINYINT COMMENT '17;宿泊人数子供３;食事なし、子供用の寝具',
    `child4` TINYINT COMMENT '18;宿泊人数子供４;子供用の食事、寝具なし',
    `child5` TINYINT COMMENT '19;宿泊人数子供５;食事なし、寝具なし',
    `card_status` TINYINT COMMENT '21;カード次回使用状態;',
    `guest_last_nm` VARCHAR(36) BINARY COMMENT '4;宿泊代表者姓;宿泊する部屋の代表者姓',
    `guest_first_nm` VARCHAR(36) BINARY COMMENT '5;宿泊代表者名;宿泊する部屋の代表者名',
    `guest_last_nm_kn` VARCHAR(36) BINARY COMMENT '6;宿泊代表者姓（カナ）;',
    `guest_first_nm_kn` VARCHAR(36) BINARY COMMENT '7;宿泊代表者名（カナ）;'
);

ALTER TABLE
    `reserve_guest` COMMENT '予約宿泊者情報テーブル;';

--   *** ------------------------------------
--  *** SERVE_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_hotel` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `check_in` VARCHAR(5) BINARY COMMENT '2;チェックイン開始時刻;HH:MM',
    `check_in_end` VARCHAR(5) BINARY COMMENT '3;チェックイン終了時刻;HH:MM',
    `charge_round` SMALLINT COMMENT '4;金額切り捨て桁;10:10の位以下切り捨て、100:100の位以下で切り捨て',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;',
    `display_status_note` TINYINT,
    `note_info` VARCHAR(90) BINARY
);

ALTER TABLE
    `reserve_hotel` COMMENT '予約時施設情報;';

--   *** ------------------------------------
--  *** SERVE_INSURANCE_WEATHER
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_insurance_weather` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `status` TINYINT DEFAULT 0 COMMENT '3;状態;0:未加入 1:加入',
    `condition` TINYINT COMMENT '4;申込状況;1: 申込 2:取消 10:成立（雨：給付対象） 20:未成立 11: 口座入力済み',
    `amedas_cd` VARCHAR(5) BINARY COMMENT '5;観測所番号;',
    `amedas_nm` VARCHAR(50) BINARY COMMENT '6;アメダス観測所名称;',
    `insurance_charge` INT COMMENT '7;保険料;',
    `present_charge` INT COMMENT '8;給付額;上限は１０万円',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `bank_branch_no` TINYINT,
    `jbr_no` INT,
    `insurance_rate` DECIMAL(3, 1),
    `jbr_rate` DECIMAL(3, 1),
    `order_type` TINYINT,
    `action_condition` TINYINT
);

ALTER TABLE
    `reserve_insurance_weather` COMMENT 'お天気保険料金;';

--   *** ------------------------------------
--  *** SERVE_JR
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_jr` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `tour_cd` VARCHAR(32) BINARY COMMENT '2;ツアー予約番号;',
    `tour_total_charge` INT COMMENT '3;ツアー総額;',
    `supplementation` VARCHAR(300) BINARY COMMENT '4;施設情報補足等;旅程',
    `return_ymd` DATETIME COMMENT '5;帰着日;',
    `company_nm` VARCHAR(90) BINARY COMMENT '6;旅行会社名称;',
    `office_nm` VARCHAR(150) BINARY COMMENT '7;旅行会社営業所名;',
    `guest_family_kn` VARCHAR(36) BINARY COMMENT '8;宿泊代表者氏名 姓かな;',
    `guest_first_kn` VARCHAR(36) BINARY COMMENT '9;宿泊代表者氏名 名かな;',
    `guest_family_nm` VARCHAR(36) BINARY COMMENT '10;宿泊代表者氏名 姓;漢字',
    `guest_first_nm` VARCHAR(36) BINARY COMMENT '11;宿泊代表者氏名 名;漢字',
    `guest_postal_cd` VARCHAR(8) BINARY COMMENT '12;宿泊代表者郵便番号;',
    `guest_address` VARCHAR(900) BINARY COMMENT '13;宿泊代表者住所;',
    `guest_address_note` VARCHAR(225) BINARY COMMENT '14;宿泊代表者住所備考;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '15;宿泊代表者電話番号;',
    `guest_age` TINYINT COMMENT '16;宿泊代表者年代;0:10代 1:20-24歳 2:25-29歳 3:30-34歳 4:35-39歳 5:40-49歳 6:50-59歳 7:60歳以上',
    `guest_email` VARCHAR(200) BINARY COMMENT '17;宿泊代表者電子メールアドレス;',
    `member_nm` VARCHAR(90) BINARY COMMENT '18;予約者氏名;',
    `member_cd` VARCHAR(128) BINARY COMMENT '19;会員コード;ベストリザーブ会員は20バイト',
    `guest_note` VARCHAR(600) BINARY COMMENT '20;お客様特記事項;',
    `guest_request` VARCHAR(600) BINARY COMMENT '21;個別リクエスト;',
    `guest_memo` VARCHAR(1) BINARY COMMENT '22;お客様フリーノート;',
    `shop_reserve_cd` VARCHAR(30) BINARY COMMENT '23;旅行会社予約番号;',
    `shop_hotel_cd` VARCHAR(10) BINARY COMMENT '24;旅行会社施設コード;',
    `shop_plan_cd` VARCHAR(10) BINARY COMMENT '25;旅行会社プランコード;',
    `shop_nm` VARCHAR(200) BINARY COMMENT '26;取扱店舗名;',
    `shop_tel` VARCHAR(15) BINARY COMMENT '27;取扱店舗電話番号;ハイフン含む',
    `shop_person` VARCHAR(30) BINARY COMMENT '28;旅行会社担当者;',
    `shop_note` VARCHAR(900) BINARY COMMENT '29;旅行会社特記事項;',
    `person_cd` VARCHAR(40) BINARY COMMENT '30;受付担当者コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '31;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '32;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '33;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '34;更新日時;'
);

ALTER TABLE
    `reserve_jr` COMMENT 'JRコレクション予約情報;';

--   *** ------------------------------------
--  *** SERVE_JWEST
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_jwest` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `j_westid` VARCHAR(12) BINARY COMMENT '2;J-WESTID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `reserve_jwest` COMMENT 'JRおでかけネット予約情報;';

--   *** ------------------------------------
--  *** SERVE_MATERIAL
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_material` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `ccd` VARCHAR(14) BINARY COMMENT '2;予約接続コード;',
    `room` TINYINT COMMENT '3;部屋数（何室目）;',
    `rooms` TINYINT COMMENT '4;部屋数合計;',
    `partner_ref` VARCHAR(40) BINARY COMMENT '5;提携先参照コード;',
    `member_cd` VARCHAR(128) BINARY COMMENT '6;会員コード;ベストリザーブ会員は20バイト',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '7;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '8;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '9;プランコード;',
    `check_in_ymd` DATETIME COMMENT '10;チェックイン日;',
    `check_in` VARCHAR(5) BINARY COMMENT '11;チェックイン時刻;',
    `check_out_ymd` DATETIME COMMENT '12;チェックアウト日;',
    `guest_nm` VARCHAR(75) BINARY COMMENT '13;宿泊代表者氏名;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '14;宿泊代表者電話番号;',
    `guest_group` VARCHAR(150) BINARY COMMENT '15;宿泊代表者所属団体;',
    `discount_charge` INT DEFAULT 0 COMMENT '16;割引料金;税込み',
    `powerdown_charge` INT COMMENT '17;パワーダウン料金;税込み',
    `card_limit_ym` DATETIME COMMENT '18;カード有効期限;',
    `affiliate_cd` VARCHAR(31) BINARY COMMENT '19;アフィリエイトコード;YYYYNNNNNN',
    `note` VARCHAR(600) BINARY COMMENT '20;宿泊者備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '21;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '22;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '23;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '24;更新日時;',
    `contact_email` VARCHAR(200) BINARY,
    `extension_value` VARCHAR(3000) BINARY,
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY,
    `capacity` TINYINT
);

ALTER TABLE
    `reserve_material` COMMENT '予約確定材料;';

--   *** ------------------------------------
--  *** SERVE_MESSAGE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_message` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `message` VARCHAR(3000) BINARY COMMENT '2;メッセージ;',
    `display_status` TINYINT COMMENT '3;表示ステータス;0:非表示 1:表示',
    `display_s_dtm` DATETIME COMMENT '4;メッセージ表示開始日時;YYYY-MM-DD HH24:00:00',
    `display_e_dtm` DATETIME COMMENT '5;メッセージ表示終了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `reserve_message` COMMENT '予約メッセージ;予約に対してシステム・スタッフ・施設からのメッセージ';

--   *** ------------------------------------
--  *** SERVE_MODIFY_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_modify_history` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `branch_no` SMALLINT COMMENT '2;変更履歴枝番号;',
    `field_nm` VARCHAR(30) BINARY COMMENT '3;フィールド名称;カラム名(hotel_nm等）',
    `before` VARCHAR(4000) BINARY COMMENT '4;変更前内容;',
    `after` VARCHAR(4000) BINARY COMMENT '5;変更後内容;',
    `modify_dtm` DATETIME COMMENT '6;変更日時;',
    `notify_cd` VARCHAR(21) BINARY COMMENT '7;通知コード;予約通知FAXの原稿のヘッダー箇所の番号 （NNNNNNNNNN-NNNNNNNNNN:施設コード-通知No） 予約通知にどこまでの履歴を掲載したかを判定',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `reserve_modify_history` COMMENT '予約情報変更;';

--   *** ------------------------------------
--  *** SERVE_MODIFY_RIZAPULI
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_modify_rizapuli` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `branch_no` SMALLINT COMMENT '2;変更履歴枝番号;',
    `field_nm` VARCHAR(30) BINARY COMMENT '3;フィールド名称;カラム名(hotel_nm等）',
    `before` VARCHAR(4000) BINARY COMMENT '4;変更前内容;',
    `after` VARCHAR(4000) BINARY COMMENT '5;変更後内容;',
    `modify_dtm` DATETIME COMMENT '6;変更日時;',
    `notify_rizapuli_cd` VARCHAR(21) BINARY COMMENT '7;リザプリ通知コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `reserve_modify_rizapuli` COMMENT '予約情報変更（リザプリ）;リザプリ通知用予約情報変更格納TBL';

--   *** ------------------------------------
--  *** SERVE_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_plan` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '5;施設名称;',
    `room_nm` VARCHAR(45) BINARY COMMENT '6;部屋名称;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '7;プラン名称;',
    `stock_type` TINYINT COMMENT '8;仕入タイプ;0:受託販売 1:買取販売',
    `room_type` TINYINT COMMENT '9;部屋タイプ;0:洋室 1:和室 2:カプセル',
    `floorage_min` SMALLINT COMMENT '10;最小床面積;',
    `floorage_max` SMALLINT COMMENT '11;最大床面積;',
    `floor_unit` TINYINT COMMENT '12;広さ単位;0:平方メートル 1:疊',
    `network` TINYINT COMMENT '13;ネットワーク接続可否;0:接続環境なし 1:無料（全客室） 2:無料（一部客室） 3:有料（全客室） 4:有料（一部客室） 9:不明',
    `rental` TINYINT COMMENT '14;接続機器貸し出し;1:部屋常設 2:無料貸出 3:有料貸出 4:持ち込み',
    `connector` TINYINT COMMENT '15;接続コネクタ種類;1:無線 2:LAN 3:ＴＥＬ 4:その他',
    `network_note` VARCHAR(750) BINARY COMMENT '16;ネットワーク詳細;',
    `plan_type` VARCHAR(10) BINARY COMMENT '17;プランタイプ;null:通常 fss:金土日',
    `charge_type` TINYINT COMMENT '18;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '19;定員;ルームチャージの場合は最小と最大が同じ',
    `payment_way` TINYINT COMMENT '20;決済方法;0:現地決済 1:クレジット決済 2:銀行振込',
    `stay_limit` TINYINT COMMENT '21;最低連泊数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;',
    `premium_status` TINYINT DEFAULT 0,
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY,
    `room_label_cd` VARCHAR(10) BINARY,
    `plan_label_cd` VARCHAR(10) BINARY,
    `capacity_min` TINYINT,
    `capacity_max` TINYINT,
    `stay_cap` TINYINT,
    `accept_status` TINYINT,
    `accept_s_ymd` DATETIME,
    `accept_e_ymd` DATETIME,
    `accept_e_day` TINYINT,
    `accept_e_hour` VARCHAR(5) BINARY,
    `check_in` VARCHAR(5) BINARY,
    `check_in_end` VARCHAR(5) BINARY,
    `check_out` VARCHAR(5) BINARY,
    `room_nl` VARCHAR(120) BINARY,
    `visual_status` TINYINT
);

ALTER TABLE
    `reserve_plan` COMMENT '予約プラン情報;';

--   *** ------------------------------------
--  *** SERVE_PLAN_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_plan_grants` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `grants_status` SMALLINT COMMENT '2;補助金利用可否;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `reserve_plan_grants` COMMENT '予約時の補助金設定情報;';

--   *** ------------------------------------
--  *** SERVE_PLAN_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_plan_info` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `info` VARCHAR(4000) BINARY COMMENT '2;特色;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `reserve_plan_info` COMMENT '予約プラン特色;';

--   *** ------------------------------------
--  *** SERVE_PLAN_JR
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_plan_jr` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `room_nm` VARCHAR(120) BINARY COMMENT '2;部屋名称;',
    `plan_nm` VARCHAR(375) BINARY COMMENT '3;プラン名称;ベストリザーブは40文字',
    `info` VARCHAR(4000) BINARY COMMENT '4;特色;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `reserve_plan_jr` COMMENT 'JRコレクションプラン情報;';

--   *** ------------------------------------
--  *** SERVE_PLAN_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_plan_point` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `issue_point_rate` DECIMAL(5, 2) COMMENT '2;獲得ポイント率;Yahoo!ポイント専用、BRは通常は1%、プレミアムは2%',
    `issue_point_rate_our` DECIMAL(5, 2) COMMENT '3;獲得ポイント当社負担率;',
    `point_status` TINYINT COMMENT '4;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '5;増量単位;',
    `min_point` INT COMMENT '6;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '7;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `reserve_plan_point` COMMENT '予約時のポイント設定情報;plan_pointのスナップショット';

--   *** ------------------------------------
--  *** SERVE_PLAN_SPEC
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_plan_spec` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `element_id` SMALLINT COMMENT '2;要素ID;',
    `element_value_id` TINYINT COMMENT '3;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `reserve_plan_spec` COMMENT '予約プランスペック;マスタの内容が変わった時に予約後のデータも変更される';

--   *** ------------------------------------
--  *** SERVE_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_point` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `issue_point_rate` DECIMAL(5, 2) COMMENT '2;付与ポイント率;',
    `point_status` TINYINT COMMENT '3;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '4;増量単位;',
    `min_point` INT COMMENT '5;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '6;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `cancel_priority` TINYINT COMMENT '7;優先設定;0:料金から差し引く 1:ポイントから差し引く',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `issue_point_rate_our` DECIMAL(5, 2)
);

ALTER TABLE
    `reserve_point` COMMENT '予約時のポイント設定情報;';

--   *** ------------------------------------
--  *** SERVE_POWER
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_power` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `authori_status` TINYINT COMMENT '2;オーソリステータス;0:オーソリ 1:一部キャンセル 2:キャンセル',
    `sales_dtm` DATETIME COMMENT '3;売上データ作成日時;',
    `card_company_cd` VARCHAR(4) BINARY COMMENT '4;クレジット会社コード;',
    `card_limit_ym` DATETIME COMMENT '5;カード有効期限;',
    `demand_charge` INT COMMENT '6;売上料金;',
    `mall_cd` VARCHAR(7) BINARY COMMENT '7;モールコード;0000482',
    `terminal_cd` VARCHAR(5) BINARY COMMENT '8;端末コード;03232:パワーホテル',
    `authori_dtm` DATETIME COMMENT '9;オーソリ日時;オーソリ処理日時',
    `voucher_no` VARCHAR(16) BINARY COMMENT '10;伝票番号;オーソリ正常終了時に設定されます',
    `approval_no` VARCHAR(7) BINARY COMMENT '11;承認番号;オーソリ正常終了時に設定されます',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;',
    `card_id` TINYINT
);

ALTER TABLE
    `reserve_power` COMMENT 'パワーオーソリ状況;最後に行ったオーソリの内容を保持します。';

--   *** ------------------------------------
--  *** SERVE_POWER_DEV
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_power_dev` (
    `reserve_cd` VARCHAR(14) BINARY,
    `authori_status` TINYINT,
    `sales_dtm` DATETIME,
    `card_company_cd` VARCHAR(4) BINARY,
    `card_id` TINYINT,
    `card_limit_ym` DATETIME,
    `demand_charge` INT,
    `mall_cd` VARCHAR(7) BINARY,
    `terminal_cd` VARCHAR(5) BINARY,
    `authori_dtm` DATETIME,
    `voucher_no` VARCHAR(5) BINARY,
    `approval_no` VARCHAR(7) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** SERVE_RECEIPT
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_receipt` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `receipt_policy` TINYINT COMMENT '2;領収書ポリシー;1:ポイント利用差引、ポイント利用額の明細記載なし 2:ポイント利用差引なし、ポイント利用額の明細記載なし 3:ポイント利用差引なし、ポイント利用額の内訳掲載あり',
    `accept_s_ymd` DATETIME COMMENT '3;表示開始年月日;',
    `accept_e_ymd` DATETIME COMMENT '4;表示終了年月日;',
    `receipt_nm` VARCHAR(300) BINARY COMMENT '5;領収書の宛名;最終表示時の宛名を保持する。',
    `limit_show_cnt` TINYINT COMMENT '6;表示限界回数;',
    `show_cnt` TINYINT COMMENT '7;表示回数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `reserve_receipt` COMMENT '領収書表示状態;';

--   *** ------------------------------------
--  *** SERVE_TICKET
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_ticket` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `br_point_gift_id` VARCHAR(10) BINARY COMMENT '2;ＢＲポイントギフト交換コード;ＹＹＹＹＭＭ９９９９',
    `status` TINYINT COMMENT '3;状態;0:無効 1:有効',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `reserve_ticket` COMMENT '金券使用テーブル;';

--   *** ------------------------------------
--  *** SERVE_TOUR
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_tour` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `tour_cd` VARCHAR(32) BINARY COMMENT '2;ツアー予約番号;',
    `tour_total_charge` INT COMMENT '3;ツアー総額;',
    `supplementation` VARCHAR(300) BINARY COMMENT '4;施設情報補足等;',
    `return_ymd` DATETIME COMMENT '5;帰着日;',
    `company_nm` VARCHAR(90) BINARY COMMENT '6;旅行会社名称;',
    `office_nm` VARCHAR(90) BINARY COMMENT '7;旅行会社営業所名;',
    `guest_family_kn` VARCHAR(36) BINARY COMMENT '8;宿泊代表者氏名 姓かな;',
    `guest_first_kn` VARCHAR(36) BINARY COMMENT '9;宿泊代表者氏名 名かな;',
    `guest_family_nm` VARCHAR(36) BINARY COMMENT '10;宿泊代表者氏名 姓;漢字',
    `guest_first_nm` VARCHAR(36) BINARY COMMENT '11;宿泊代表者氏名 名;漢字',
    `guest_postal_cd` VARCHAR(8) BINARY COMMENT '12;宿泊代表者郵便番号;',
    `guest_address` VARCHAR(900) BINARY COMMENT '13;宿泊代表者住所;',
    `guest_address_note` VARCHAR(225) BINARY COMMENT '14;宿泊代表者住所備考;',
    `guest_tel` VARCHAR(15) BINARY COMMENT '15;宿泊代表者電話番号;',
    `guest_age` TINYINT COMMENT '16;宿泊代表者年代;0:10代 1:20-24歳 2:25-29歳 3:30-34歳 4:35-39歳 5:40-49歳 6:50-59歳 7:60歳以上',
    `guest_email` VARCHAR(200) BINARY COMMENT '17;宿泊代表者電子メールアドレス;',
    `member_nm` VARCHAR(90) BINARY COMMENT '18;予約者氏名;',
    `member_cd` VARCHAR(128) BINARY COMMENT '19;会員コード;ベストリザーブ会員は20バイト',
    `guest_note` VARCHAR(600) BINARY COMMENT '20;お客様特記事項;',
    `guest_request` VARCHAR(600) BINARY COMMENT '21;個別リクエスト;',
    `guest_memo` VARCHAR(1) BINARY COMMENT '22;お客様フリーノート;',
    `shop_reserve_cd` VARCHAR(30) BINARY COMMENT '23;旅行会社予約番号;',
    `shop_hotel_cd` VARCHAR(10) BINARY COMMENT '24;旅行会社施設コード;',
    `shop_plan_cd` VARCHAR(10) BINARY COMMENT '25;旅行会社プランコード;',
    `shop_nm` VARCHAR(200) BINARY COMMENT '26;取扱店舗名;',
    `shop_tel` VARCHAR(15) BINARY COMMENT '27;取扱店舗電話番号;ハイフン含む',
    `shop_person` VARCHAR(30) BINARY COMMENT '28;旅行会社担当者;',
    `shop_note` VARCHAR(900) BINARY COMMENT '29;旅行会社特記事項;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '30;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '31;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '32;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '33;更新日時;',
    `person_cd` VARCHAR(40) BINARY
);

ALTER TABLE
    `reserve_tour` COMMENT '予約情報（募集型企画旅行）;';

--   *** ------------------------------------
--  *** SERVE_TRACE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_trace` (
    `transaction_cd` VARCHAR(14) BINARY COMMENT '1;トランザクションコード;最初の予約コードを設定（複数部屋の予約について）',
    `type` TINYINT COMMENT '2;種類;1:入場 2:特定 3:確定',
    `referrer` VARCHAR(255) BINARY COMMENT '3;遷移元;',
    `place` VARCHAR(128) BINARY COMMENT '4;場所;',
    `content` VARCHAR(128) BINARY COMMENT '5;内容;',
    `access_dtm` DATETIME COMMENT '6;アクセス日時;リンクを押下した日時',
    `request_uri` VARCHAR(255) BINARY COMMENT '7;遷移先;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `reserve_trace` COMMENT '予約トレース;';

--   *** ------------------------------------
--  *** SERVE_VERIFY_YDP
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_verify_ydp` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;日本旅行から取得した予約ID：ベストリザーブ予約参照コード（reserve.partner_ref)',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `verify_status` TINYINT COMMENT '3;突合状態;0: 未チェック  1:予約  2:キャンセル  3:BRキャンセル  4:情報変更',
    `verify_dtm` DATETIME COMMENT '4;突合日時;',
    `reservation_id` VARCHAR(12) BINARY COMMENT '5;Ydb予約ID;日本旅行から取得した予約ID：ベストリザーブ予約参照コード（reserve.partner_ref)',
    `reserve_ymd` DATETIME COMMENT '6;予約日;',
    `reserve_nm` VARCHAR(60) BINARY COMMENT '7;予約者名;',
    `guest_nm` VARCHAR(60) BINARY COMMENT '8;宿泊者名;',
    `guest_kn` VARCHAR(60) BINARY COMMENT '9;宿泊者名かな;',
    `pref_nm` VARCHAR(15) BINARY COMMENT '10;都道府県;',
    `hotel_nm` VARCHAR(150) BINARY COMMENT '11;ホテル名;',
    `room_type_nm` VARCHAR(375) BINARY COMMENT '12;部屋タイプ;0:洋室 1:和室 2:カプセル',
    `room_dinner_fg` TINYINT COMMENT '13;夕食有無フラグ;0:無し　1:有り',
    `room_dinner_charge` VARCHAR(30) BINARY COMMENT '14;夕食料金;ﾇﾙ値、込み、料金（数字）',
    `room_breakfast_fg` TINYINT COMMENT '15;朝食有無フラグ;0:無し　1:有り',
    `room_breakfast_charge` VARCHAR(30) BINARY COMMENT '16;朝食料金;ﾇﾙ値、込み、料金（数字）',
    `adult_nr` TINYINT COMMENT '17;大人数;',
    `child_nr` TINYINT COMMENT '18;小人数;',
    `rooms` SMALLINT COMMENT '19;部屋数;',
    `sale_nr` INT COMMENT '20;宿泊金額;',
    `fee_nr` INT COMMENT '21;手数料;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '22;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '23;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '24;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '25;更新日時;'
);

ALTER TABLE
    `reserve_verify_ydp` COMMENT '日本旅行 予約突合せ;';

--   *** ------------------------------------
--  *** SERVE_YDP
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_ydp` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `affiliate_id` VARCHAR(10) BINARY COMMENT '2;アフィリエイトID;',
    `passwd` VARCHAR(10) BINARY COMMENT '3;パスワード;',
    `hotel_id` VARCHAR(10) BINARY COMMENT '4;施設ID;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '5;部屋タイプコード;',
    `breakfast_fg` TINYINT COMMENT '6;オプション朝食クラグ;0 : 無し , 1 : 有り',
    `dinner_fg` TINYINT COMMENT '7;オプション夕食フラグ;0 : 無し , 1 : 夕食1 , 2 : 夕食2 , 3 : 夕食3',
    `use_dt` DATETIME COMMENT '8;対象年月日;チェックイン日 YYYYMMDD',
    `stay` TINYINT COMMENT '9;宿泊日数;',
    `room_nr` TINYINT COMMENT '10;部屋数;',
    `man_nr` TINYINT COMMENT '11;男性人数;',
    `woman_nr` TINYINT COMMENT '12;女性人数;',
    `child_a_nr` TINYINT COMMENT '13;子供人数;0: ルームチャージ  n:マンチャージ',
    `child_b_nr` TINYINT COMMENT '14;子供(子供料理)人数;0: ルームチャージ  n:マンチャージ',
    `child_c_nr` TINYINT COMMENT '15;子供(料理なし)人数;0: ルームチャージ  n:マンチャージ',
    `baby_nr` TINYINT COMMENT '16;幼児人数;0: ルームチャージ  n:マンチャージ',
    `last_nm` VARCHAR(60) BINARY COMMENT '17;予約者姓;',
    `first_nm` VARCHAR(60) BINARY COMMENT '18;予約者名;',
    `k_last_nm` VARCHAR(60) BINARY COMMENT '19;予約者姓（カナ）;全角カタカナ と 全角・半角スペース',
    `k_first_nm` VARCHAR(60) BINARY COMMENT '20;予約者名（カナ）;全角カタカナ と 全角・半角スペース',
    `sex` TINYINT COMMENT '21;性別;1:男性 2:女性',
    `prefecture_cd` VARCHAR(2) BINARY COMMENT '22;都道府県コード;JIS X 0401 01:北海道 ・・・・・',
    `tel` VARCHAR(15) BINARY COMMENT '23;自宅電話番号;9999-9999-9999',
    `represent_tel` VARCHAR(15) BINARY COMMENT '24;連絡先電話番号;9999-9999-9999',
    `represent_tel_cd` TINYINT COMMENT '25;宿泊者電話番号区分;1:自宅 2:勤務先 3:携帯',
    `email` VARCHAR(100) BINARY COMMENT '26;メールアドレス;',
    `login_id` VARCHAR(50) BINARY COMMENT '27;会員ID;ベストリザーブ予約情報（reserve)のトランザクションコードを登録',
    `checkin_tm` VARCHAR(5) BINARY COMMENT '28;チェックイン時刻(時:分);チェックイン時間 （00:00〜30:00　を指定）',
    `member_ct` VARCHAR(600) BINARY COMMENT '29;予約者コメント;',
    `transporttype_fg` TINYINT COMMENT '30;利用交通機関フラグ;1:公共交通機関等 2:車',
    `addmethod_cd` TINYINT COMMENT '31;加算方式区分;1:部屋加算 2:人数加算 (初期値=１）',
    `roomadd_rt` VARCHAR(71) BINARY COMMENT '32;部屋加算料金;連泊の場合は、ハイフン"-"半角で区切る （9999999-9999999）',
    `adult_rt` VARCHAR(71) BINARY COMMENT '33;大人料金;',
    `child_a_rt` VARCHAR(71) BINARY COMMENT '34;子供料金;',
    `child_b_rt` VARCHAR(71) BINARY COMMENT '35;子供(子供料理)料金;',
    `child_c_rt` VARCHAR(71) BINARY COMMENT '36;子供(料理なし)料金;',
    `baby_rt` VARCHAR(71) BINARY COMMENT '37;幼児料金;',
    `sale_rt` VARCHAR(9) BINARY COMMENT '38;合計料金;',
    `room_breakfast_fg` TINYINT COMMENT '39;朝食有無フラグ;0:無し　1:有り',
    `room_dinner_fg` TINYINT COMMENT '40;夕食有無フラグ;0:無し　1:有り',
    `out_rsv_id` VARCHAR(20) BINARY COMMENT '41;外部予約ID;ベストリザーブ予約コード（reserve.reserve_cd)',
    `reservation_id` VARCHAR(15) BINARY COMMENT '42;Ydb予約ID;日本旅行から取得した予約ID：ベストリザーブ予約参照コード（reserve.partner_ref)',
    `entry_cd` VARCHAR(64) BINARY COMMENT '43;登録者コード;/controller/action.(user_id) または 登録者メールアドレス',
    `entry_ts` DATETIME COMMENT '44;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '45;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '46;更新日時;',
    `payment_cd` TINYINT
);

ALTER TABLE
    `reserve_ydp` COMMENT '日本旅行 予約情報;';

--   *** ------------------------------------
--  *** SERVE_YDP_RECEIVE
--   *** ------------------------------------
-- 
CREATE TABLE `reserve_ydp_receive` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `affiliate_id` VARCHAR(10) BINARY COMMENT '2;アフィリエイトID;',
    `passwd` VARCHAR(20) BINARY COMMENT '3;パスワード;暗号化した値',
    `hotel_id` VARCHAR(10) BINARY COMMENT '4;施設ID;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '5;部屋タイプコード;',
    `breakfast_fg` TINYINT COMMENT '6;オプション朝食クラグ;0 : 無し , 1 : 有り',
    `dinner_fg` TINYINT COMMENT '7;オプション夕食フラグ;0 : 無し , 1 : 夕食1 , 2 : 夕食2 , 3 : 夕食3',
    `use_dt` DATETIME COMMENT '8;対象年月日;チェックイン日 YYYYMMDD',
    `stay` TINYINT COMMENT '9;宿泊日数;',
    `room_nr` TINYINT COMMENT '10;部屋数;',
    `man_nr` TINYINT COMMENT '11;男性人数;',
    `woman_nr` TINYINT COMMENT '12;女性人数;',
    `child_a_nr` TINYINT COMMENT '13;子供人数;0: ルームチャージ  n:マンチャージ',
    `child_b_nr` TINYINT COMMENT '14;子供(子供料理)人数;0: ルームチャージ  n:マンチャージ',
    `child_c_nr` TINYINT COMMENT '15;子供(料理なし)人数;0: ルームチャージ  n:マンチャージ',
    `baby_nr` TINYINT COMMENT '16;幼児人数;0: ルームチャージ  n:マンチャージ',
    `last_nm` VARCHAR(30) BINARY COMMENT '17;予約者姓;',
    `first_nm` VARCHAR(30) BINARY COMMENT '18;予約者名;',
    `k_last_nm` VARCHAR(30) BINARY COMMENT '19;予約者姓（カナ）;全角カタカナ と 全角・半角スペース',
    `k_first_nm` VARCHAR(30) BINARY COMMENT '20;予約者名（カナ）;全角カタカナ と 全角・半角スペース',
    `sex` TINYINT COMMENT '21;性別;m:男性 f:女性',
    `prefecture_cd` VARCHAR(2) BINARY COMMENT '22;都道府県コード;JIS X 0401',
    `tel` VARCHAR(15) BINARY COMMENT '23;自宅電話番号;9999-9999-9999',
    `represent_tel` VARCHAR(15) BINARY COMMENT '24;連絡先電話番号;9999-9999-9999',
    `represent_tel_cd` TINYINT COMMENT '25;宿泊者電話番号区分;1:自宅 2:勤務先 3:携帯',
    `email` VARCHAR(300) BINARY COMMENT '26;メールアドレス;',
    `login_id` VARCHAR(50) BINARY COMMENT '27;会員ID;ベストリザーブ予約情報（reserve)のトランザクションコードを登録',
    `checkin_tm` VARCHAR(5) BINARY COMMENT '28;チェックイン時刻(時:分);チェックイン時間 （00:00〜30:00　を指定）',
    `member_ct` VARCHAR(300) BINARY COMMENT '29;予約者コメント;',
    `transporttype_fg` TINYINT COMMENT '30;利用交通機関フラグ;1:公共交通機関等 2:車',
    `addmethod_cd` TINYINT COMMENT '31;加算方式区分;1:部屋加算 2:人数加算 (初期値=１）',
    `roomadd_rt` VARCHAR(71) BINARY COMMENT '32;部屋加算料金;連泊の場合は、ハイフン"-"半角で区切る （9999999-9999999）',
    `adult_rt` VARCHAR(71) BINARY COMMENT '33;大人料金;',
    `child_a_rt` VARCHAR(71) BINARY COMMENT '34;子供料金;',
    `child_b_rt` VARCHAR(71) BINARY COMMENT '35;子供(子供料理)料金;',
    `child_c_rt` VARCHAR(71) BINARY COMMENT '36;子供(料理なし)料金;',
    `baby_rt` VARCHAR(71) BINARY COMMENT '37;幼児料金;',
    `sale_rt` VARCHAR(9) BINARY COMMENT '38;合計料金;',
    `room_breakfast_fg` TINYINT COMMENT '39;朝食有無フラグ;0:無し　1:有り',
    `room_dinner_fg` TINYINT COMMENT '40;夕食有無フラグ;0:無し　1:有り',
    `out_rsv_id` VARCHAR(20) BINARY COMMENT '41;外部予約ID;ベストリザーブ予約コード（reserve.reserve_cd)',
    `reservation_id` VARCHAR(12) BINARY COMMENT '42;Ydb予約ID;日本旅行から取得した予約ID：ベストリザーブ予約参照コード（reserve.partner_ref)',
    `entry_cd` VARCHAR(64) BINARY COMMENT '43;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '44;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '45;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '46;更新日時;',
    `out_mem_id` VARCHAR(15) BINARY
);

ALTER TABLE
    `reserve_ydp_receive` COMMENT '日本旅行 予約情報(受信);';

--   *** ------------------------------------
--  DDL for Table ROOM
--   *** ------------------------------------
-- 
CREATE TABLE `room` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `room_nm` VARCHAR(45) BINARY COMMENT '3;部屋名称;',
    `room_type` TINYINT COMMENT '4;部屋タイプ;0:洋室 1:和室 2:カプセル',
    `floorage_min` SMALLINT COMMENT '5;最小床面積;',
    `floorage_max` SMALLINT COMMENT '6;最大床面積;',
    `floor_unit` TINYINT COMMENT '7;広さ単位;0:平方メートル 1:疊',
    `active_status` TINYINT COMMENT '8;システム取扱状態;0:停止中 1:受付中',
    `display_status` TINYINT COMMENT '9;表示ステータス;0:非表示 1:表示',
    `order_no` BIGINT COMMENT '10;管理画面表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;',
    `accept_status` TINYINT,
    `room_nl` VARCHAR(120) BINARY,
    `user_side_order_no` BIGINT
);

ALTER TABLE
    `room` COMMENT '部屋;';

--   *** ------------------------------------
--  *** OM2
--   *** ------------------------------------
-- 
CREATE TABLE `room2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `room_nm` VARCHAR(45) BINARY COMMENT '3;部屋名称;',
    `room_type` TINYINT COMMENT '4;部屋タイプ;0:カプセル 1:シングル 2:ツイン 3:セミダブル 4:ダブル 5:トリプル 6:4ベッド 7:スイート 8:メゾネット 9:和室 10:和洋室 11:その他',
    `floorage_min` SMALLINT COMMENT '5;最小床面積;',
    `floorage_max` SMALLINT COMMENT '6;最大床面積;',
    `floor_unit` TINYINT COMMENT '7;広さ単位;0:平方メートル 1:疊',
    `active_status` TINYINT COMMENT '8;システム取扱状態;0:停止中 1:受付中',
    `display_status` TINYINT COMMENT '9;表示ステータス;0:非表示 1:表示',
    `order_no` BIGINT COMMENT '10;管理画面表示順序;',
    `label_cd` VARCHAR(10) BINARY COMMENT '11;部屋ラベル;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;',
    `capacity_min` TINYINT,
    `capacity_max` TINYINT,
    `accept_status` TINYINT,
    `room_nl` VARCHAR(120) BINARY,
    `user_side_order_no` BIGINT
);

ALTER TABLE
    `room2` COMMENT '部屋 PLN100;';

--   *** ------------------------------------
--  *** OM_AKAFU
--   *** ------------------------------------
-- 
CREATE TABLE `room_akafu` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '2;部屋タイプコード;',
    `roomtype_nm` VARCHAR(256) BINARY COMMENT '3;部屋タイプ名称;',
    `min_nr` TINYINT COMMENT '4;定員数始め;',
    `max_nr` TINYINT COMMENT '5;定員数終り;',
    `note` VARCHAR(900) BINARY COMMENT '6;部屋特徴;',
    `area_rep` VARCHAR(60) BINARY COMMENT '7;広さ;',
    `areaunit` VARCHAR(1) BINARY COMMENT '8;広さ単位（赤風）;1:平方メートル 2:畳',
    `roomclose_dt` SMALLINT COMMENT '9;部屋手仕舞い日;',
    `roomclose_tm` VARCHAR(5) BINARY COMMENT '10;部屋手仕舞い時間;HH:MM',
    `checkin_from_tm` VARCHAR(5) BINARY COMMENT '11;チェックイン始め時刻;HH:MM',
    `checkin_to_tm` VARCHAR(5) BINARY COMMENT '12;チェックアウト時刻（赤風）;HH:MM',
    `area_cd` VARCHAR(4) BINARY COMMENT '13;地区コード;',
    `institution_cd` VARCHAR(3) BINARY COMMENT '14;施設コード（赤風）;',
    `notactive_flg` VARCHAR(1) BINARY COMMENT '15;削除フラグ;',
    `kamei_fg` VARCHAR(1) BINARY COMMENT '16;旅連加盟フラグ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '17;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '18;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '19;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '20;更新日時;'
);

ALTER TABLE
    `room_akafu` COMMENT '赤い風船部屋情報;';

--   *** ------------------------------------
--  *** OM_AKAFU_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `room_akafu_relation` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '3;部屋タイプコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_akafu_relation` COMMENT '赤い風船部屋関連;';

--   *** ------------------------------------
--  *** OM_AKF
--   *** ------------------------------------
-- 
CREATE TABLE `room_akf` (
    `hotel_cd` VARCHAR(10) BINARY,
    `room_id` VARCHAR(10) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** OM_AKF_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `room_akf_relation` (
    `hotel_cd` VARCHAR(10) BINARY,
    `room_id` VARCHAR(10) BINARY,
    `room_cd_akf` VARCHAR(20) BINARY,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** OM_CHARGE
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `usual_charge` INT COMMENT '6;通常料金;',
    `sales_charge` INT COMMENT '7;販売料金;税サ込み',
    `accept_status` TINYINT COMMENT '8;予約受付状態;0:停止中 1:受付中',
    `accept_s_dtm` DATETIME COMMENT '9;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '10;終了日時;',
    `low_price_status` TINYINT COMMENT '11;最安値宣言ステータス;0:宣言しない 1:宣言する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `room_charge` COMMENT '部屋料金情報;';

--   *** ------------------------------------
--  *** OM_CHARGE_EARLY
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_early` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `accept_e_ymd` DATETIME COMMENT '6;終了日;',
    `unit` TINYINT COMMENT '7;早割引単位;0:率 1;金額 2:差額',
    `discount_rate` SMALLINT COMMENT '8;早割引率;',
    `discount_charge` INT COMMENT '9;早割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `room_charge_early` COMMENT '部屋料金早割料金情報;早割料金があるときのみデータが存在';

--   *** ------------------------------------
--  *** OM_CHARGE_INITIAL
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_initial` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `usual_charge_sun` INT COMMENT '5;日曜通常料金;',
    `usual_charge_mon` INT COMMENT '6;月曜通常料金;',
    `usual_charge_tue` INT COMMENT '7;火曜標準料金;',
    `usual_charge_wed` INT COMMENT '8;水曜通常料金;',
    `usual_charge_thu` INT COMMENT '9;木曜通常料金;',
    `usual_charge_fri` INT COMMENT '10;金曜通常料金;',
    `usual_charge_sat` INT COMMENT '11;土曜通常料金;',
    `usual_charge_hol` INT COMMENT '12;祝日通常料金;',
    `usual_charge_bfo` INT COMMENT '13;休前日通常料金;土曜は必ず土曜日です。',
    `discount_charge_sun` INT COMMENT '14;日曜割引料金;',
    `discount_charge_mon` INT COMMENT '15;月曜割引料金;',
    `discount_charge_tue` INT COMMENT '16;火曜割引料金;',
    `discount_charge_wed` INT COMMENT '17;水曜割引料金;',
    `discount_charge_thu` INT COMMENT '18;木曜割引料金;',
    `discount_charge_fri` INT COMMENT '19;金曜割引料金;',
    `discount_charge_sat` INT COMMENT '20;土曜割引料金;',
    `discount_charge_hol` INT COMMENT '21;祝日割引料金;',
    `discount_charge_bfo` INT COMMENT '22;休前日割引料金;土曜は必ず土曜日です。',
    `low_price_status` TINYINT COMMENT '23;最安値宣言ステータス;0:宣言しない 1:宣言する',
    `accept_s_day` TINYINT COMMENT '24;販売開始日;null:すぐ販売',
    `accept_s_hour` VARCHAR(5) BINARY COMMENT '25;販売開始時間;',
    `accept_e_day` TINYINT COMMENT '26;販売終了日;null:手仕舞いなし',
    `accept_e_hour` VARCHAR(5) BINARY COMMENT '27;販売終了時間;',
    `early_day` TINYINT COMMENT '28;早期割引日;null:早割りなし',
    `unit` TINYINT COMMENT '29;早割引単位;0:率 1;金額 2:差額',
    `discount_rate` SMALLINT COMMENT '30;早割引率;',
    `discount_charge` INT COMMENT '31;早割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '32;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '33;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '34;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '35;更新日時;'
);

ALTER TABLE
    `room_charge_initial` COMMENT 'プラン料金希望情報;プラン料金の基本情報を保持します。';

--   *** ------------------------------------
--  *** OM_CHARGE_REMOVED
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_removed` (
    `room_charge_removed_id` DECIMAL(20, 0) COMMENT '1;料金削除ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `partner_group_id` BIGINT COMMENT '5;提携先グループID;',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `delete_dtm` DATETIME COMMENT '7;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;',
    `capacity` TINYINT,
    `payment_way` TINYINT,
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `room_charge_removed` COMMENT '料金削除情報;';

--   *** ------------------------------------
--  *** OM_CHARGE_TODAY
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_today` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `timetable` DATETIME COMMENT '6;当日割引設定時間;',
    `unit` TINYINT COMMENT '7;当日割引単位;0:率 1;金額 2:差額',
    `discount_rate` SMALLINT COMMENT '8;当日割引率;',
    `discount_charge` INT COMMENT '9;当日割引料金;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `room_charge_today` COMMENT '部屋料金当日料金情報;当日販売料金があるときのみデータが存在';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp` (
    `rec_type` TINYINT,
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp` COMMENT '部屋料金情報【旧】Yahoo Batch Temp;';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_1
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_1` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_1` COMMENT 'Yahoo差分料金用テンポラリテーブル1;Yahoo 差分料金のバッチ専用テンポラリテーブル1　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_10
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_10` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_10` COMMENT 'Yahoo差分料金用テンポラリテーブル10;Yahoo 差分料金のバッチ専用テンポラリテーブル10　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_2
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_2` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_2` COMMENT 'Yahoo差分料金用テンポラリテーブル2;Yahoo 差分料金のバッチ専用テンポラリテーブル2　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_3
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_3` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_3` COMMENT 'Yahoo差分料金用テンポラリテーブル3;Yahoo 差分料金のバッチ専用テンポラリテーブル3　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_4
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_4` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_4` COMMENT 'Yahoo差分料金用テンポラリテーブル4;Yahoo 差分料金のバッチ専用テンポラリテーブル4　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_5
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_5` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_5` COMMENT 'Yahoo差分料金用テンポラリテーブル5;Yahoo 差分料金のバッチ専用テンポラリテーブル5　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_6
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_6` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_6` COMMENT 'Yahoo差分料金用テンポラリテーブル6;Yahoo 差分料金のバッチ専用テンポラリテーブル6　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_7
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_7` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_7` COMMENT 'Yahoo差分料金用テンポラリテーブル7;Yahoo 差分料金のバッチ専用テンポラリテーブル7　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_8
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_8` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_8` COMMENT 'Yahoo差分料金用テンポラリテーブル8;Yahoo 差分料金のバッチ専用テンポラリテーブル8　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_CHARGE_YHO_BAT_TMP_9
--   *** ------------------------------------
-- 
CREATE TABLE `room_charge_yho_bat_tmp_9` (
    `rec_type` SMALLINT COMMENT '1;レコードタイプ;1:販売終了料金用 2:終了日変更用 3:販売開始料金用 4:当日料金用 5:変更料金用 7:お天気',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `accept_status` TINYINT COMMENT '7;予約受付状態;0:停止中 1:受付中',
    `modify_ts_src` DATETIME COMMENT '8;更新日時（元レコード）;このテーブルにデータを作る元となったレコードのmodify_tsの値が格納されています',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_charge_yho_bat_tmp_9` COMMENT 'Yahoo差分料金用テンポラリテーブル9;Yahoo 差分料金のバッチ専用テンポラリテーブル9　実行のたびにtruncateされます';

--   *** ------------------------------------
--  *** OM_COUNT
--   *** ------------------------------------
-- 
CREATE TABLE `room_count` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `rooms` SMALLINT COMMENT '4;在庫数;',
    `reserve_rooms` SMALLINT COMMENT '5;予約部屋数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `accept_status` TINYINT
);

ALTER TABLE
    `room_count` COMMENT '在庫数;';

--   *** ------------------------------------
--  *** OM_COUNT2
--   *** ------------------------------------
-- 
CREATE TABLE `room_count2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `date_ymd` DATETIME COMMENT '3;宿泊日;',
    `rooms` SMALLINT COMMENT '4;在庫数;',
    `reserve_rooms` SMALLINT COMMENT '5;予約部屋数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `accept_status` TINYINT
);

ALTER TABLE
    `room_count2` COMMENT '在庫数 PLN104;';

--   *** ------------------------------------
--  *** OM_COUNT_AKAFU
--   *** ------------------------------------
-- 
CREATE TABLE `room_count_akafu` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `roomtype_cd` VARCHAR(20) BINARY COMMENT '2;部屋タイプコード;',
    `use_dt` DATETIME COMMENT '3;対象年月日;チェックイン日 YYYYMMDD',
    `stock_nr` SMALLINT COMMENT '4;投入在庫数;',
    `sold_nr` SMALLINT COMMENT '5;販売済在庫数;',
    `stop_fg` VARCHAR(1) BINARY COMMENT '6;販売停止フラグ;0:販売 1:販売停止',
    `parent_rest_nr` SMALLINT COMMENT '7;親部屋タイプ空室数;',
    `close_dttm` DATETIME COMMENT '8;手仕舞い日;',
    `batch_sequence` INT COMMENT '9;登録バッチ用SEQ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `room_count_akafu` COMMENT '赤い風船在庫情報;';

--   *** ------------------------------------
--  *** OM_COUNT_INITIAL
--   *** ------------------------------------
-- 
CREATE TABLE `room_count_initial` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `rooms_sun` SMALLINT COMMENT '3;日曜部屋数;',
    `rooms_mon` SMALLINT COMMENT '4;月曜部屋数;',
    `rooms_tue` SMALLINT COMMENT '5;火曜部屋数;',
    `rooms_wed` SMALLINT COMMENT '6;水曜部屋数;',
    `rooms_thu` SMALLINT COMMENT '7;木曜部屋数;',
    `rooms_fri` SMALLINT COMMENT '8;金曜部屋数;',
    `rooms_sat` SMALLINT COMMENT '9;土曜部屋数;',
    `rooms_hol` SMALLINT COMMENT '10;祝日部屋数;',
    `rooms_bfo` SMALLINT COMMENT '11;休前日部屋数;土曜は必ず土曜日です。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `room_count_initial` COMMENT '部屋数希望情報;部屋数の基本情報を保持します。';

--   *** ------------------------------------
--  *** OM_COUNT_INITIAL2
--   *** ------------------------------------
-- 
CREATE TABLE `room_count_initial2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `rooms_sun` SMALLINT COMMENT '3;日曜部屋数;',
    `rooms_mon` SMALLINT COMMENT '4;月曜部屋数;',
    `rooms_tue` SMALLINT COMMENT '5;火曜部屋数;',
    `rooms_wed` SMALLINT COMMENT '6;水曜部屋数;',
    `rooms_thu` SMALLINT COMMENT '7;木曜部屋数;',
    `rooms_fri` SMALLINT COMMENT '8;金曜部屋数;',
    `rooms_sat` SMALLINT COMMENT '9;土曜部屋数;',
    `rooms_hol` SMALLINT COMMENT '10;祝日部屋数;',
    `rooms_bfo` SMALLINT COMMENT '11;休前日部屋数;土曜は必ず土曜日です。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `room_count_initial2` COMMENT '部屋数希望情報 PLN103;';

--   *** ------------------------------------
--  *** OM_COUNT_REMOVED
--   *** ------------------------------------
-- 
CREATE TABLE `room_count_removed` (
    `room_count_removed_id` DECIMAL(20, 0) COMMENT '1;在庫削除ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `date_ymd` DATETIME COMMENT '4;宿泊日;',
    `delete_dtm` DATETIME COMMENT '5;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `room_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `room_count_removed` COMMENT '在庫削除情報;';

--   *** ------------------------------------
--  *** OM_JR
--   *** ------------------------------------
-- 
CREATE TABLE `room_jr` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `room_nm` VARCHAR(120) BINARY COMMENT '3;部屋名称;',
    `accept_status` TINYINT COMMENT '4;予約受付状態;0:停止中 1:受付中',
    `active_status` TINYINT COMMENT '5;システム取扱状態;0:停止中 1:受付中',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `room_jr` COMMENT '部屋情報（JRセットプラン）;部屋情報（JRセットプラン）';

--   *** ------------------------------------
--  *** OM_LEGACY
--   *** ------------------------------------
-- 
CREATE TABLE `room_legacy` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_legacy` COMMENT '旧部屋コード;';

--   *** ------------------------------------
--  *** OM_MEDIA
--   *** ------------------------------------
-- 
CREATE TABLE `room_media` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `media_no` SMALLINT COMMENT '3;メディアNo;',
    `order_no` SMALLINT COMMENT '4;画像表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_media` COMMENT '部屋メディア;';

--   *** ------------------------------------
--  *** OM_MEDIA2
--   *** ------------------------------------
-- 
CREATE TABLE `room_media2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `media_no` SMALLINT COMMENT '3;メディアNo;ベストリザーブは3文字',
    `order_no` SMALLINT COMMENT '4;画像表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_media2` COMMENT '部屋メディア PLN106;';

--   *** ------------------------------------
--  *** OM_NETWORK
--   *** ------------------------------------
-- 
CREATE TABLE `room_network` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `network` TINYINT COMMENT '3;ネットワーク接続可否;0:接続環境なし 1:無料（全客室） 2:無料（一部客室） 3:有料（全客室） 4:有料（一部客室） 9:不明',
    `rental` TINYINT COMMENT '4;接続機器貸し出し;1:部屋常設 2:無料貸出 3:有料貸出 4:持ち込み',
    `connector` TINYINT COMMENT '5;接続コネクタ種類;1:無線 2:LAN 3:ＴＥＬ 4:その他',
    `network_note` VARCHAR(750) BINARY COMMENT '6;ネットワーク詳細;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `room_network` COMMENT '部屋ネットワーク環境;';

--   *** ------------------------------------
--  *** OM_NETWORK2
--   *** ------------------------------------
-- 
CREATE TABLE `room_network2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `network` TINYINT COMMENT '3;ネットワーク接続可否;0:接続環境なし 1:無料（全客室） 2:無料（一部客室） 3:有料（全客室） 4:有料（一部客室） 9:不明',
    `rental` TINYINT COMMENT '4;接続機器貸し出し;1:部屋常設 2:無料貸出 3:有料貸出 4:持ち込み',
    `connector` TINYINT COMMENT '5;接続コネクタ種類;1:無線 2:LAN 3:ＴＥＬ 4:その他',
    `network_note` VARCHAR(750) BINARY COMMENT '6;ネットワーク詳細;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `room_network2` COMMENT '部屋ネットワーク環境 PLN102;';

--   *** ------------------------------------
--  *** OM_NTA_RELATION
--   *** ------------------------------------
-- 
CREATE TABLE `room_nta_relation` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `room_cd_nta` VARCHAR(20) BINARY COMMENT '3;NTA部屋コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_nta_relation` COMMENT '日本旅行部屋関連（リロ）;';

--   *** ------------------------------------
--  *** OM_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `plan_type` VARCHAR(10) BINARY COMMENT '4;プランタイプ;null:通常 fss:金土日',
    `plan_nm` VARCHAR(375) BINARY COMMENT '5;プラン名称;',
    `charge_type` TINYINT COMMENT '6;料金タイプ;0:ルームチャージ 1:マンチャージ',
    `capacity` TINYINT COMMENT '7;定員;ルームチャージの場合は最小と最大が同じ',
    `payment_way` TINYINT COMMENT '8;決済方法;0:現地決済 1:クレジット決済 2:銀行振込',
    `stay_limit` TINYINT COMMENT '9;最低連泊数;',
    `order_no` BIGINT COMMENT '10;管理画面表示順序;',
    `display_status` TINYINT COMMENT '11;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;',
    `user_side_order_no` BIGINT,
    `accept_status` TINYINT,
    `check_in` VARCHAR(5) BINARY,
    `check_in_end` VARCHAR(5) BINARY,
    `check_out` VARCHAR(5) BINARY,
    `stay_cap` TINYINT
);

ALTER TABLE
    `room_plan` COMMENT 'プラン;';

--   *** ------------------------------------
--  *** OM_PLAN_CANCEL_POLICY
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_cancel_policy` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `cancel_policy` VARCHAR(2850) BINARY COMMENT '4;キャンセルポリシー;200文字',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_cancel_policy` COMMENT 'プランキャンセルポリシー;クレジットカード決済の場合のキャンセルポリシー';

--   *** ------------------------------------
--  *** OM_PLAN_CANCEL_RATE
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_cancel_rate` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `days` SMALLINT COMMENT '4;宿泊日からの日数;',
    `cancel_rate` SMALLINT COMMENT '5;キャンセル料率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `policy_status` TINYINT
);

ALTER TABLE
    `room_plan_cancel_rate` COMMENT 'プランキャンセル料率;クレジットカード決済の場合のキャンセルポリシー';

--   *** ------------------------------------
--  *** OM_PLAN_CAPACITY
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_capacity` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `date_ym` DATETIME COMMENT '4;宿泊年月;',
    `capacity` SMALLINT COMMENT '5;人数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `room_plan_capacity` COMMENT '部屋プラン利用人数;';

--   *** ------------------------------------
--  *** OM_PLAN_CHILD
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_child` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `child1_accept` TINYINT COMMENT '4;子供1部屋受入;大人に順ずる食事と寝具（0:受け入れない 1:受け入れる）',
    `child2_accept` TINYINT COMMENT '5;子供2部屋受入;子供用の食事と寝具（0:受け入れない 1:受け入れる）',
    `child3_accept` TINYINT COMMENT '6;子供3部屋受入;子供用の寝具（0:受け入れない 1:受け入れる）',
    `child4_accept` TINYINT COMMENT '7;子供4部屋受入;子供用の食事（0:受け入れない 1:受け入れる）',
    `child5_accept` TINYINT COMMENT '8;子供5部屋受入;食事寝具なし（0:受け入れない 1:受け入れる）',
    `child1_person` DECIMAL(2, 1) DEFAULT 1 COMMENT '9;子供1部屋人数係数;',
    `child2_person` DECIMAL(2, 1) DEFAULT 1 COMMENT '10;子供2部屋人数係数;',
    `child3_person` DECIMAL(2, 1) DEFAULT 1 COMMENT '11;子供3部屋人数係数;',
    `child4_person` DECIMAL(2, 1) DEFAULT 0 COMMENT '12;子供4部屋人数係数;',
    `child5_person` DECIMAL(2, 1) DEFAULT 0 COMMENT '13;子供5部屋人数係数;',
    `child1_charge_include` TINYINT COMMENT '14;子供1料金計算時の定員に含める;0:含めない 1:含める',
    `child2_charge_include` TINYINT COMMENT '15;子供2料金計算時の定員に含める;0:含めない 1:含める',
    `child3_charge_include` TINYINT COMMENT '16;子供3料金計算時の定員に含める;0:含めない 1:含める',
    `child4_charge_include` TINYINT COMMENT '17;子供4料金計算時の定員に含める;0:含めない 1:含める',
    `child5_charge_include` TINYINT COMMENT '18;子供5料金計算時の定員に含める;0:含めない 1:含める',
    `child1_charge_unit` TINYINT COMMENT '19;子供1料金単位;0:率 1;金額 2:差額',
    `child2_charge_unit` TINYINT COMMENT '20;子供2料金単位;0:率 1;金額 2:差額',
    `child3_charge_unit` TINYINT COMMENT '21;子供3料金単位;0:率 1;金額 2:差額',
    `child4_charge_unit` TINYINT COMMENT '22;子供4料金単位;0:率 1;金額 2:差額',
    `child5_charge_unit` TINYINT COMMENT '23;子供5料金単位;0:率 1;金額 2:差額',
    `child1_charge` INT COMMENT '24;子供1料金;',
    `child2_charge` INT COMMENT '25;子供2料金;',
    `child3_charge` INT COMMENT '26;子供3料金;',
    `child4_charge` INT COMMENT '27;子供4料金;',
    `child5_charge` INT COMMENT '28;子供5料金;',
    `child1_rate` SMALLINT COMMENT '29;子供1率;',
    `child2_rate` SMALLINT COMMENT '30;子供2率;',
    `child3_rate` SMALLINT COMMENT '31;子供3率;',
    `child4_rate` SMALLINT COMMENT '32;子供4率;',
    `child5_rate` SMALLINT COMMENT '33;子供5率;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '34;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '35;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '36;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '37;更新日時;'
);

ALTER TABLE
    `room_plan_child` COMMENT '子供人数情報;';

--   *** ------------------------------------
--  *** OM_PLAN_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_grants` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `grants_status` SMALLINT COMMENT '4;補助金利用可否;0:許可しない 1:許可する',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_grants` COMMENT 'プラン補助金設定情報【旧】;プラン毎の補助金利用制限を保持する';

--   *** ------------------------------------
--  *** OM_PLAN_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_info` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `info` VARCHAR(4000) BINARY COMMENT '4;特色;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_info` COMMENT 'プラン特色;';

--   *** ------------------------------------
--  *** OM_PLAN_LEGACY
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_legacy` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `room_id` VARCHAR(10) BINARY COMMENT '4;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '5;プランID;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `room_plan_legacy` COMMENT '旧プランコード;';

--   *** ------------------------------------
--  *** OM_PLAN_LOWEST
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_lowest` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '2;プランコード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `charge_condition` TINYINT COMMENT '4;料金区分;非会員：1 会員：2 非会員・会員：3',
    `date_ymd` DATETIME COMMENT '5;宿泊日;',
    `sales_charge` INT COMMENT '6;販売料金;総額（販売料金 + 税 - 割引料金）',
    `rate` SMALLINT COMMENT '7;割引率;',
    `plan_condition` SMALLINT COMMENT '8;プラン状態;新規プラン：1  早割り：2  当日割り：4 （ 複数対応する場合は、合計： 新規プラン・早割り=3）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_plan_lowest` COMMENT 'プラン最安値;';

--   *** ------------------------------------
--  *** OM_PLAN_LOWEST2
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_lowest2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;',
    `capacity` TINYINT COMMENT '4;利用人数;',
    `charge_condition` TINYINT COMMENT '5;料金区分;非会員：-1 非会員・会員：0 会員：1',
    `date_ymd` DATETIME COMMENT '6;宿泊日;',
    `sales_charge` INT COMMENT '7;販売料金;総額（販売料金 + 税 - 割引料金）',
    `rate` SMALLINT COMMENT '8;割引率;',
    `plan_condition` SMALLINT COMMENT '9;プラン状態;新規プラン：1  早割り：2  当日割り：4 （ 複数対応する場合は、合計： 新規プラン・早割り=3）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `room_plan_lowest2` COMMENT 'プラン最安値;';

--   *** ------------------------------------
--  *** OM_PLAN_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_match` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_plan_match` COMMENT '部屋プランマッチ;';

--   *** ------------------------------------
--  *** OM_PLAN_MATCH_REMOVED
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_match_removed` (
    `room_plan_match_removed_id` DECIMAL(20, 0) COMMENT '1;プラン付け替えID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `room_id` VARCHAR(10) BINARY COMMENT '4;部屋ID;',
    `delete_dtm` DATETIME COMMENT '5;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `room_plan_match_removed` COMMENT 'プラン部屋の付け替え情報;';

--   *** ------------------------------------
--  *** OM_PLAN_MEDIA
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_media` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `media_no` SMALLINT COMMENT '4;メディアNo;',
    `order_no` SMALLINT COMMENT '5;画像表示順序;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `room_plan_media` COMMENT 'プランメディア;';

--   *** ------------------------------------
--  *** OM_PLAN_NEW
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_new` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `deadline_ymd` DATETIME COMMENT '4;表示最終年月日;最も小さい販売開始年月日から１４日後',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_new` COMMENT 'プランNEWマーク表示制御情報;プラン内で最も小さい販売開始年月日から１４日後までプランNewマークを表示する。';

--   *** ------------------------------------
--  *** OM_PLAN_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_point` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `issue_point_rate` SMALLINT COMMENT '4;付与ポイント率;',
    `point_status` TINYINT COMMENT '5;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '6;増量単位;',
    `min_point` INT COMMENT '7;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '8;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `issue_point_rate_our` SMALLINT
);

ALTER TABLE
    `room_plan_point` COMMENT 'プランポイント設定情報;ポイント利用条件設定';

--   *** ------------------------------------
--  *** OM_PLAN_POINT_20170101
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_point_20170101` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `issue_point_rate` SMALLINT COMMENT '4;獲得ポイント率;Yahoo!ポイント専用、BRは通常は1%、プレミアムは2%',
    `copy_status` TINYINT COMMENT '5;コピー状況;0:未コピー 1:コピー済',
    `attribute` VARCHAR(256) BINARY COMMENT '6;補足情報;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;',
    `rpp_issue_point_rate` SMALLINT COMMENT '11;ROOM_PLAN_POINT更新前獲得ポイント率;ROOM_PLAN_POINTのポイント付与率更新直前にBKとして現在のROOM_PLAN_POINTのポイント率を保持する項目',
    `rpp_last_modify_cd` VARCHAR(64) BINARY COMMENT '12;ROOM_PLAN_POINT更新前最終更新者;ROOM_PLAN_POINTのポイント付与率更新直前にROOM_PLAN_POINT最終更新者modify_cd)を保持する項目',
    `rpp_last_upd_dtm` DATETIME COMMENT '13;ROOM_PLAN_POINT更新前最終更新時間;ROOM_PLAN_POINTのポイント付与率更新直前にROOM_PLAN_POINT最終更新時間(modify_ts)を保持する項目',
    `rpp_issue_point_rate_our` SMALLINT COMMENT '14;ROOM_PLAN_POINT更新前当社負担率;ROOM_PLAN_POINTの当社ポイント負担率更新直前にBKとして現在のROOM_PLAN_POINTのポイント負担率を保持する項目'
);

ALTER TABLE
    `room_plan_point_20170101` COMMENT 'プランポイント【旧】_20170101_ポイント率改定用;20170101からの施設ポイント負担率改定用のテーブル(旧管理用)';

--   *** ------------------------------------
--  *** OM_PLAN_PRIORITY
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_priority` (
    `pref_id` TINYINT COMMENT '1;都道府県ID;',
    `span` TINYINT COMMENT '2;宿泊対象期間;0:検索日から0-6日後 7:検索日から7-35日後',
    `wday` TINYINT COMMENT '3;曜日;1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜',
    `priority` SMALLINT COMMENT '4;重点表示順位;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '6;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '7;プランコード;',
    `display_status` TINYINT COMMENT '8;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;',
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `room_plan_priority` COMMENT '重点表示部屋プラン;重点表示プランを保持します。';

--   *** ------------------------------------
--  *** OM_PLAN_RANKING
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ranking` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `wday` TINYINT COMMENT '4;曜日;1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜',
    `ranking` BIGINT COMMENT '5;売れ筋順位;',
    `ranking_org` BIGINT COMMENT '6;売れ筋順位オリジナル;係数に基づいて決められた順位。',
    `ranking_point` BIGINT,
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `room_plan_ranking` COMMENT '部屋プラン売れ筋ランキング;空室検索結果の表示順序決定に使用する値を保持します。';

--   *** ------------------------------------
--  *** OM_PLAN_RANKING2
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ranking2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;YYYYNNNNNN',
    `wday` TINYINT COMMENT '4;曜日;1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜',
    `ranking` BIGINT COMMENT '5;売れ筋順位;',
    `ranking_org` BIGINT COMMENT '6;売れ筋順位オリジナル;係数に基づいて決められた順位。',
    `ranking_point` BIGINT,
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `room_plan_ranking2` COMMENT '部屋プラン売れ筋ランキング PLN151;';

--   *** ------------------------------------
--  *** OM_PLAN_RANKING_BASE
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ranking_base` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `calc_value` DECIMAL(5, 2) COMMENT '4;計算値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_ranking_base` COMMENT '部屋プラン売れ筋ランキング調整;部屋プラン売れ筋ランキングを調整するためのデータを保持します。';

--   *** ------------------------------------
--  *** OM_PLAN_RANKING_BASE2
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ranking_base2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;YYYYNNNNNN',
    `calc_value` DECIMAL(5, 2) COMMENT '4;計算値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_ranking_base2` COMMENT '部屋プラン売れ筋ランキング調整 PLN152;';

--   *** ------------------------------------
--  *** OM_PLAN_RANKING_CALC
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ranking_calc` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `wday` TINYINT COMMENT '4;曜日;1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜',
    `weekly_value` BIGINT COMMENT '5;週別計算値;',
    `monthly_value` BIGINT COMMENT '6;月別計算値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `room_plan_ranking_calc` COMMENT '部屋プラン売れ筋計算;売れ筋係数を計算するための値を保持します。';

--   *** ------------------------------------
--  *** OM_PLAN_RANKING_CALC2
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ranking_calc2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;YYYYNNNNNN',
    `wday` TINYINT COMMENT '4;曜日;1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜',
    `weekly_value` BIGINT COMMENT '5;週別計算値;',
    `monthly_value` BIGINT COMMENT '6;月別計算値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `room_plan_ranking_calc2` COMMENT '部屋プラン売れ筋計算 PLN153;';

--   *** ------------------------------------
--  *** OM_PLAN_REMOVED
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_removed` (
    `room_plan_removed_id` DECIMAL(20, 0) COMMENT '1;プラン削除ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '3;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '4;プランコード;',
    `delete_dtm` DATETIME COMMENT '5;削除日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `payment_way` TINYINT,
    `room_id` VARCHAR(10) BINARY,
    `plan_id` VARCHAR(10) BINARY
);

ALTER TABLE
    `room_plan_removed` COMMENT 'プラン削除情報;';

--   *** ------------------------------------
--  *** OM_PLAN_SPEC
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_spec` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `element_id` SMALLINT COMMENT '4;要素ID;',
    `element_value_id` TINYINT COMMENT '5;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `room_plan_spec` COMMENT 'プランスペック;';

--   *** ------------------------------------
--  *** OM_PLAN_YAHOO_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_yahoo_point` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `issue_point_rate` SMALLINT COMMENT '4;付与ポイント率;',
    `point_status` TINYINT COMMENT '5;ポイント利用可否;0:使用しない 1:使用する',
    `amount` SMALLINT COMMENT '6;増量単位;',
    `min_point` INT COMMENT '7;最低利用ポイント;１回の予約に用いる最低ポイントを設定',
    `max_point` INT COMMENT '8;最大利用ポイント;１部屋１日を設定、1000を設定字に ２部屋 ２泊の場合は 4000ポイント利用、最大10万ポイント',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `room_plan_yahoo_point` COMMENT 'プランYahooポイント（room_plan_pointへ;ヤフーポイントプラン room_plan_pointへ移行予定';

--   *** ------------------------------------
--  *** OM_PLAN_YDP2
--   *** ------------------------------------
-- 
CREATE TABLE `room_plan_ydp2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '3;プランID;',
    `ydp_room_cd` VARCHAR(20) BINARY COMMENT '4;YDP部屋コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_plan_ydp2` COMMENT '日本旅行プラン移行対応;';

--   *** ------------------------------------
--  *** OM_SPEC
--   *** ------------------------------------
-- 
CREATE TABLE `room_spec` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `element_id` SMALLINT COMMENT '3;要素ID;',
    `element_value_id` TINYINT COMMENT '4;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_spec` COMMENT '部屋スペック;';

--   *** ------------------------------------
--  *** OM_SPEC2
--   *** ------------------------------------
-- 
CREATE TABLE `room_spec2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;YYYYNNNNNN',
    `element_id` SMALLINT COMMENT '3;要素ID;',
    `element_value_id` TINYINT COMMENT '4;値ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `room_spec2` COMMENT '部屋スペック PLN101;';

--   *** ------------------------------------
--  *** OM_TYK
--   *** ------------------------------------
-- 
CREATE TABLE `room_tyk` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `tyk_room_cd` VARCHAR(20) BINARY COMMENT '3;東横イン部屋コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_tyk` COMMENT '東横イン部屋関連付けテーブル;';

--   *** ------------------------------------
--  *** OM_YDK
--   *** ------------------------------------
-- 
CREATE TABLE `room_ydk` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `room_cd_ydk` VARCHAR(20) BINARY COMMENT '3;YDK部屋コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_ydk` COMMENT '宿研部屋関連付けテーブル;';

--   *** ------------------------------------
--  *** OM_YDP
--   *** ------------------------------------
-- 
CREATE TABLE `room_ydp` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `ydp_room_cd` VARCHAR(20) BINARY COMMENT '3;YDP部屋コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `addmethod_cd` TINYINT COMMENT '4;加算方式区分;1:部屋加算 2:人数加算 (初期値=１）',
    `checkin_from_tm` VARCHAR(5) BINARY COMMENT '5;チェックイン始め時間;HH:MI',
    `checkin_to_tm` VARCHAR(5) BINARY COMMENT '6;チェックイン終り時間;HH:MI'
);

ALTER TABLE
    `room_ydp` COMMENT '日本旅行 部屋関連付け;';

--   *** ------------------------------------
--  *** OM_YDP2
--   *** ------------------------------------
-- 
CREATE TABLE `room_ydp2` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '2;部屋ID;',
    `ydp_room_cd` VARCHAR(20) BINARY COMMENT '3;YDP部屋コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `room_ydp2` COMMENT '日本旅行部屋移行対応;';

--   *** ------------------------------------
--  *** UTE_MAP
--   *** ------------------------------------
-- 
CREATE TABLE `route_map` (
    `route_id` VARCHAR(5) BINARY COMMENT '1;路線ID;',
    `station_id1` VARCHAR(7) BINARY COMMENT '2;駅ID1;',
    `station_id2` VARCHAR(7) BINARY COMMENT '3;駅ID2;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `distance` DECIMAL(3, 1) COMMENT '4;駅間距離;',
    `time` SMALLINT COMMENT '5;駅間所要時間;',
    `order_no` BIGINT
);

ALTER TABLE
    `route_map` COMMENT '路線図;';

--   *** ------------------------------------
--  *** V_PUSH_AREAS
--   *** ------------------------------------
-- 
CREATE TABLE `rsv_push_areas` (
    `area_id` VARCHAR(10) BINARY COMMENT '1;エリアID;YYYYMM99',
    `area_nm` VARCHAR(24) BINARY COMMENT '2;エリア名称;',
    `uri` VARCHAR(3000) BINARY,
    `order_no` TINYINT,
    `begin_ymd` DATETIME,
    `deadline_ymd` DATETIME,
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `rsv_push_areas` COMMENT 'プッシュエリア;新UI エリア指定検索用  デフォルト表示分は必ず登録（掲載開始年月日ヌル値）';

--   *** ------------------------------------
--  *** V_TOP_CONTENTS
--   *** ------------------------------------
-- 
CREATE TABLE `rsv_top_contents` (
    `content_cd` VARCHAR(8) BINARY COMMENT '1;コンテンツコード;YYYYMM99',
    `version_no` TINYINT COMMENT '2;バージョンNO;レイアウトのバージョン',
    `place` VARCHAR(10) BINARY COMMENT '3;場所;10:トップテキスト（最大表示2件とする） 20:トップ訴求（右上） 30:バナーA（大） 31:バナーB（小上） 32:バナーC（小下） 40:パネルA（大） 41:パネルB（小） 50:特集',
    `begin_dtm` DATETIME COMMENT '4;掲載開始日時;（YYYY-MM-DD HH24） ヌル値の場合は、同一タイプで表示対象がない場合のデフォルト値とする。',
    `deadline_dtm` DATETIME COMMENT '5;掲載最終日時;（YYYY-MM-DD HH24） ヌル値の場合は、永続表示する。',
    `affiliate_cd` VARCHAR(10) BINARY COMMENT '6;アフィリエイトコード;YYYYNNNNNN',
    `affiliate_cd_sub` VARCHAR(10) BINARY COMMENT '7;アフィリエイトコード枝番;',
    `title` VARCHAR(300) BINARY COMMENT '8;タイトル;',
    `uri` VARCHAR(256) BINARY COMMENT '9;リンク先;ドメインは指定しない、 （例： /campaign/NNYYYYMM9/）',
    `img_src` VARCHAR(256) BINARY COMMENT '10;画像URL;ドメインは指定しない、 （例： /campaign/NNYYYYMM9/banner.jpg）',
    `tpl_src` VARCHAR(256) BINARY COMMENT '11;テンプレート;開発者のみ設定可能とする。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `rsv_top_contents` COMMENT 'ベストリザーブトップページ（ 広告・キャンペーン）;新UI用';

--   *** ------------------------------------
--  *** CURE_LICENSE
--   *** ------------------------------------
-- 
CREATE TABLE `secure_license` (
    `license_id` INT COMMENT '1;ライセンスID;',
    `license_token` VARCHAR(64) BINARY COMMENT '2;ライセンストークン;暗号化した値（sha256)',
    `applicant_staff_id` INT COMMENT '3;申請者スタッフID;申請者のstaff_id',
    `approver_staff_id` INT COMMENT '4;承認者スタッフID;承認者のstaff_id',
    `license_status` TINYINT COMMENT '5;ライセンスステータス;0:有効　1:無効',
    `license_limit_dtm` DATETIME COMMENT '6;ライセンス有効期限;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `secure_license` COMMENT 'セキュア認可;';

--   *** ------------------------------------
--  *** ND_MAIL_QUEUE
--   *** ------------------------------------
-- 
CREATE TABLE `send_mail_queue` (
    `mail_cd` VARCHAR(16) BINARY COMMENT '1;送信電子メールキューコード;YYYYMMDDNNNNNNNN',
    `from_mail` VARCHAR(200) BINARY COMMENT '2;送信元アドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `from_nm` VARCHAR(300) BINARY COMMENT '3;送信元名称;',
    `bcc_mail` VARCHAR(200) BINARY COMMENT '4;BCCアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `to_mail` VARCHAR(200) BINARY COMMENT '5;宛先アドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `return_path` VARCHAR(128) BINARY COMMENT '6;エラー戻り先アドレス;',
    `free_field` VARCHAR(300) BINARY COMMENT '7;フリーフィールド;',
    `subject` VARCHAR(384) BINARY COMMENT '8;件名;メールマガジンのタイトル',
    `contents` LONGTEXT COMMENT '9;本文;',
    `cipher` TINYINT COMMENT '10;本文暗号化;0:非暗号化 1:暗号化',
    `start_dtm` DATETIME COMMENT '11;送信開始日時;null:即時',
    `send_dtm` DATETIME COMMENT '12;送信完了日時;',
    `send_status` TINYINT COMMENT '13;送信状態;0:未送信 1:送信済み',
    `entry_cd` VARCHAR(64) BINARY COMMENT '14;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '15;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '16;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '17;更新日時;'
);

ALTER TABLE
    `send_mail_queue` COMMENT '送信電子メールキュー;';

--   *** ------------------------------------
--  *** RVICE_HUNTING
--   *** ------------------------------------
-- 
CREATE TABLE `service_hunting` (
    `hunting_id` BIGINT COMMENT '1;施設探しID;追加するときは、連番で最大値＋１の値を登録すること',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `order_limit` INT COMMENT '3;応募受付人数;',
    `order_count` INT COMMENT '4;応募人数;',
    `get_br_point` INT COMMENT '5;獲得ポイント;',
    `open_ymd` DATETIME COMMENT '6;公開年月日;公開日の朝３時のバッチで、施設情報ページの更新をして、応募アイコンをつける',
    `accept_s_dtm` DATETIME COMMENT '7;受付開始年月日時;年月日 時 まで入力する。分秒は0分0秒を設定する',
    `accept_e_ymd` DATETIME COMMENT '8;受付終了年月日;受付終了年月日の翌日朝３時のバッチで、施設情報ページの更新をして、応募アイコンを外す',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `service_hunting` COMMENT 'ホテルを探してポイントゲット管理テーブル;';

--   *** ------------------------------------
--  *** RVICE_VOTE
--   *** ------------------------------------
-- 
CREATE TABLE `service_vote` (
    `vote_cd` INT COMMENT '1;設問コード;YYYYMMXX （XX:自動採番）',
    `vote_no` SMALLINT COMMENT '2;設問回;',
    `question` VARCHAR(360) BINARY COMMENT '3;設問;',
    `question_msg` VARCHAR(4000) BINARY COMMENT '4;設問メッセージ;',
    `get_br_point` INT COMMENT '5;獲得ポイント;',
    `result_msg` VARCHAR(4000) BINARY COMMENT '6;結果メッセージ;',
    `open_dtm` DATETIME COMMENT '7;表示開始日時;YYYY-MM-DD HH24:00:00',
    `vote_s_dtm` DATETIME COMMENT '8;投票開始日時;YYYY-MM-DD HH24:00:00',
    `vote_e_ymd` DATETIME COMMENT '9;投票終了日;YYYY-MM-DD 00:00:00',
    `result_dtm` DATETIME COMMENT '10;結果発表日時;YYYY-MM-DD HH24:00:00',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `service_vote` COMMENT '大投票 設問データ;';

--   *** ------------------------------------
--  *** RVICE_VOTE_ANSWER
--   *** ------------------------------------
-- 
CREATE TABLE `service_vote_answer` (
    `vote_cd` INT COMMENT '1;訪問コード;YYYYMMXX （XX:自動採番）',
    `member_cd` VARCHAR(128) BINARY COMMENT '2;会員コード;ベストリザーブ会員は20バイト',
    `choice_cd` TINYINT COMMENT '3;選択肢コード;',
    `pref_id` TINYINT COMMENT '4;都道府県ID;JIS X 0401',
    `gender` VARCHAR(1) BINARY COMMENT '5;性別;m:男性 f:女性',
    `age` SMALLINT COMMENT '6;年齢;',
    `answer_note` VARCHAR(1500) BINARY COMMENT '7;回答コメント;',
    `answer_dtm` DATETIME COMMENT '8;回答日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `service_vote_answer` COMMENT '大投票 設問回答;';

--   *** ------------------------------------
--  *** RVICE_VOTE_CHOICES
--   *** ------------------------------------
-- 
CREATE TABLE `service_vote_choices` (
    `vote_cd` INT COMMENT '1;設問コード;YYYYMMXX （XX:自動採番）',
    `choice_cd` TINYINT COMMENT '2;選択肢コード;',
    `order_no` TINYINT COMMENT '3;表示順序;',
    `choice_nm` VARCHAR(180) BINARY COMMENT '4;選択肢;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `service_vote_choices` COMMENT '大投票 設問選択肢;';

--   *** ------------------------------------
--  *** Y_AREA
--   *** ------------------------------------
-- 
CREATE TABLE `sky_area` (
    `pref_id` TINYINT,
    `pref_nm` VARCHAR(15) BINARY,
    `address` VARCHAR(600) BINARY
);

--   *** ------------------------------------
--  DDL for Table SPOT
--   *** ------------------------------------
-- 
CREATE TABLE `spot` (
    `spot_id` BIGINT COMMENT '1;スポットID;',
    `spot_nm` VARCHAR(300) BINARY COMMENT '2;スポット名称;',
    `pref_id` TINYINT COMMENT '3;都道府県ID;',
    `address` VARCHAR(300) BINARY COMMENT '4;住所;市区町村以下',
    `wgs_lat_d` VARCHAR(16) BINARY COMMENT '5;世界測地系-度-緯度;JGD2000と同様',
    `wgs_lng_d` VARCHAR(16) BINARY COMMENT '6;世界測地系-度-経度;JGD2000と同様',
    `attract_message` VARCHAR(3000) BINARY COMMENT '7;引き文言;',
    `display_status` TINYINT COMMENT '8;表示ステータス;0:非表示 1:表示',
    `hotel_distance` BIGINT COMMENT '9;施設半径;メートル',
    `spot_distance` BIGINT COMMENT '10;スポット半径;メートル',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `spot` COMMENT 'スポット;';

--   *** ------------------------------------
--  *** OT_GROUP
--   *** ------------------------------------
-- 
CREATE TABLE `spot_group` (
    `spot_group_id` BIGINT COMMENT '1;スポットグループID;',
    `spot_group_nm` VARCHAR(300) BINARY COMMENT '2;スポットグループ名称;',
    `image` VARCHAR(300) BINARY COMMENT '3;画像情報;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `spot_group` COMMENT 'スポットグループ;';

--   *** ------------------------------------
--  *** OT_GROUP_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `spot_group_match` (
    `spot_group_id` BIGINT COMMENT '1;スポットグループID;',
    `spot_id` BIGINT COMMENT '2;スポットID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `spot_group_match` COMMENT 'スポットグループマッチ;';

--   *** ------------------------------------
--  *** OT_NEARBY
--   *** ------------------------------------
-- 
CREATE TABLE `spot_nearby` (
    `spot_id` BIGINT COMMENT '1;スポットID;',
    `spot_nearby_id` BIGINT COMMENT '2;近隣のスポットID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `spot_nearby` COMMENT 'スポット近隣のスポット;';

--   *** ------------------------------------
--  *** O_COMPARE
--   *** ------------------------------------
-- 
CREATE TABLE `sso_compare` (
    `account_type` TINYINT COMMENT '1;アカウントタイプ;0:スタッフ 1:会員 2:施設 3:施設統括 4:提携先',
    `account_key` VARCHAR(128) BINARY COMMENT '2;アカウント認証キー;アカウントを特定するためのキー （staff_id、member_cd、hotel_cd、supervisor_cd、partner_cd）',
    `compare_cd` VARCHAR(128) BINARY COMMENT '3;照合コード;',
    `various_cd` VARCHAR(64) BINARY COMMENT '4;汎用コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `sso_compare` COMMENT 'SSO照合;';

--   *** ------------------------------------
--  *** AFF
--   *** ------------------------------------
-- 
CREATE TABLE `staff` (
    `staff_id` INT COMMENT '1;スタッフID;',
    `staff_nm` VARCHAR(96) BINARY COMMENT '2;スタッフ;',
    `staff_cd` VARCHAR(10) BINARY COMMENT '3;スタッフコード;',
    `staff_status` TINYINT COMMENT '5;スタッフ状態;0:退社 1:在籍',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;',
    `email` VARCHAR(200) BINARY COMMENT '4;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト'
);

ALTER TABLE
    `staff` COMMENT 'スタッフ;';

--   *** ------------------------------------
--  *** AFF_ACCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `staff_account` (
    `staff_id` INT COMMENT '1;スタッフID;',
    `account_id` VARCHAR(60) BINARY COMMENT '2;アカウントID;',
    `password` VARCHAR(64) BINARY COMMENT '3;パスワード;暗号化した値',
    `accept_status` TINYINT COMMENT '4;ステータス;0:利用不可 1:利用可',
	`remember_token` VARCHAR(100) COMMENT 'ログイン状態保持トークン',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `staff_account` COMMENT 'スタッフ認証;';

--   *** ------------------------------------
--  *** AT_TABLE
--   *** ------------------------------------
-- 
CREATE TABLE `stat_table` (
    `statid` VARCHAR(30) BINARY,
    `type` CHAR(1) BINARY,
    `version` DOUBLE,
    `flags` DOUBLE,
    `c1` VARCHAR(30) BINARY,
    `c2` VARCHAR(30) BINARY,
    `c3` VARCHAR(30) BINARY,
    `c4` VARCHAR(30) BINARY,
    `c5` VARCHAR(30) BINARY,
    `n1` DOUBLE,
    `n2` DOUBLE,
    `n3` DOUBLE,
    `n4` DOUBLE,
    `n5` DOUBLE,
    `n6` DOUBLE,
    `n7` DOUBLE,
    `n8` DOUBLE,
    `n9` DOUBLE,
    `n10` DOUBLE,
    `n11` DOUBLE,
    `n12` DOUBLE,
    `d1` DATETIME,
    `r1` VARBINARY(32),
    `r2` VARBINARY(32),
    `ch1` VARCHAR(1000) BINARY,
    `cl1` LONGTEXT
);

--   *** ------------------------------------
--  *** OCK_POWER
--   *** ------------------------------------
-- 
CREATE TABLE `stock_power` (
    `id` INT COMMENT '1;ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `date_ymd` DATETIME COMMENT '3;日付;',
    `stock_charge` INT COMMENT '4;仕入れ料金;税込価格',
    `tax_charge` INT COMMENT '5;消費税額;最終販売価格に対する消費税額（販売料金 - (販売料金 / 1.05))',
    `stay_tax_charge` INT COMMENT '6;宿泊税;東京都宿泊税など',
    `rooms` SMALLINT COMMENT '7;部屋数;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `stock_power` COMMENT 'パワー仕入れテーブル;';

--   *** ------------------------------------
--  *** BMIT_FORM_CHECK
--   *** ------------------------------------
-- 
CREATE TABLE `submit_form_check` (
    `check_cd` VARCHAR(256) BINARY COMMENT '1;チェックコード;同一の値が存在したら、実行しないようにする。',
    `entry_cd` VARCHAR(64) BINARY COMMENT '2;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '3;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '4;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '5;更新日時;'
);

ALTER TABLE
    `submit_form_check` COMMENT '重複実行チェック;';

--   *** ------------------------------------
--  *** MP_GROUP_RESERVE
--   *** ------------------------------------
-- 
CREATE TABLE `temp_group_reserve` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `order_no` BIGINT COMMENT '2;申し込みNo;',
    `reply_no` SMALLINT COMMENT '3;回答No;',
    `member_cd` VARCHAR(128) BINARY COMMENT '4;会員コード;ベストリザーブ会員は20バイト',
    `check_in_ymd` DATETIME COMMENT '5;チェックイン日付;',
    `check_out_ymd` DATETIME COMMENT '6;チェックアウト日付;',
    `capacity` SMALLINT COMMENT '7;利用人数;',
    `stay_charge` INT COMMENT '8;宿泊料金;',
    `tax_type` TINYINT COMMENT '9;税区分;',
    `check_in` VARCHAR(5) BINARY COMMENT '10;チェックイン時刻;',
    `guest_nm` VARCHAR(75) BINARY COMMENT '11;宿泊代表者氏名;',
    `member_group` VARCHAR(150) BINARY COMMENT '12;所属団体;',
    `tel` VARCHAR(15) BINARY COMMENT '13;電話番号;ハイフン含む',
    `email` VARCHAR(200) BINARY COMMENT '14;電子メールアドレス;128バイトを暗号化すると160バイト、余裕を見て200バイト',
    `email_type` TINYINT COMMENT '15;電子メールタイプ;0:パソコン用レイアウト 1:携帯端末用レイアウト',
    `note` VARCHAR(3000) BINARY COMMENT '16;備考;',
    `reserve_status` TINYINT COMMENT '17;予約ステータス;0:予約 1:本人取り消し 2:強制取り消し 4:無断不泊',
    `reserve_dtm` DATETIME COMMENT '18;予約受付日時;',
    `cancel_dtm` DATETIME COMMENT '19;取り消し日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '20;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '21;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '22;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '23;更新日時;'
);

ALTER TABLE
    `temp_group_reserve` COMMENT '団体予約;旧GRP_RESERVE_DATA';

--   *** ------------------------------------
--  *** MP_HOTEL_VS_MTN
--   *** ------------------------------------
-- 
CREATE TABLE `temp_hotel_vs_mtn` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `support_state` TINYINT DEFAULT 0 COMMENT '2;賛同状況;-1:拒否 0:保留 1:賛同',
    `notes` VARCHAR(4000) BINARY,
    `entry_dtm` DATETIME COMMENT '4;登録日時;',
    `update_dtm` DATETIME COMMENT '5;更新日時;'
);

ALTER TABLE
    `temp_hotel_vs_mtn` COMMENT '施設賛同;管理サイトの対楽天対応での賛同状態を保存。2006年01月の請求データ作成まで有効。';

--   *** ------------------------------------
--  *** MP_KEI_MONTH_DISCOUNT
--   *** ------------------------------------
-- 
CREATE TABLE `temp_kei_month_discount` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `date_ym` DATETIME COMMENT '2;送客年月;',
    `discount_cd` SMALLINT COMMENT '3;割引コード;',
    `discount` BIGINT COMMENT '4;割引料;',
    `note` VARCHAR(256) BINARY COMMENT '5;備考;',
    `update_dtm` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `temp_kei_month_discount` COMMENT '送客請求割引明細;';

--   *** ------------------------------------
--  *** MP_YAHOO_POINT_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `temp_yahoo_point_book` (
    `reserve_cd` VARCHAR(20) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `use_yahoo_point` INT COMMENT '2;利用ポイント;現在の利用ポイントを保持（履歴は後ほど対応）',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;',
    `issue_yahoo_point` INT
);

ALTER TABLE
    `temp_yahoo_point_book` COMMENT 'ポイント台帳仮;';

--   *** ------------------------------------
--  *** P_ATTENTION
--   *** ------------------------------------
-- 
CREATE TABLE `top_attention` (
    `attention_id` DECIMAL(22, 0) COMMENT '1;注目表示ID;',
    `start_date` DATETIME COMMENT '2;掲載開始日;',
    `display_status` TINYINT COMMENT '3;表示方法;2項目もしくは4項目',
    `display_flag` TINYINT COMMENT '4;表示フラグ;0:非表示 1:表示',
    `title` VARCHAR(300) BINARY COMMENT '5;タイトル;',
    `note` VARCHAR(3000) BINARY COMMENT '6;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `top_attention` COMMENT 'ベストリザーブトップページ（注目表示）;ベストリザーブトップページの注目に表示する項目を管理する。';

--   *** ------------------------------------
--  *** P_ATTENTION_DETAIL
--   *** ------------------------------------
-- 
CREATE TABLE `top_attention_detail` (
    `attention_detail_id` DECIMAL(22, 0) COMMENT '1;注目詳細表示ID;',
    `attention_id` DECIMAL(22, 0) COMMENT '2;注目表示ID;',
    `order_no` TINYINT COMMENT '3;表示順位;',
    `word` VARCHAR(300) BINARY COMMENT '4;掲載文;',
    `url` VARCHAR(2083) BINARY COMMENT '5;URL;',
    `jwest_word` VARCHAR(300) BINARY COMMENT '6;J-WEST用掲載文;',
    `jwest_url` VARCHAR(2083) BINARY COMMENT '7;J-WEST用URL;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `top_attention_detail` COMMENT 'ベストリザーブトップページ（注目表示詳細）;ベストリザーブトップページに表示する注目の詳細を保持する。';

--   *** ------------------------------------
--  *** ITTER
--   *** ------------------------------------
-- 
CREATE TABLE `twitter` (
    `twitter_cd` VARCHAR(12) BINARY COMMENT '1;メッセージコード;YYYYMMNNNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `alert_system_cd` VARCHAR(6) BINARY COMMENT '3;アラートシステムコード;vacant:空室アラート voice:宿泊体験 group:団体予約',
    `tag` VARCHAR(100) BINARY COMMENT '4;タグ;',
    `limit_dtm` DATETIME COMMENT '5;有効期限;',
    `status` TINYINT COMMENT '6;表示ステータス;0:無効 1:有効',
    `title` VARCHAR(240) BINARY COMMENT '7;お知らせタイトル;',
    `description` VARCHAR(3000) BINARY COMMENT '8;詳細;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `twitter` COMMENT '情報メッセージ;';

--   *** ------------------------------------
--  *** RIFY_YAHOO_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `verify_yahoo_point` (
    `verify_point_cd` VARCHAR(16) BINARY COMMENT '1;ベリファイポイントコード;YYYYMMNNNNNNNNNN',
    `api_nm` VARCHAR(32) BINARY COMMENT '2;API名称;',
    `point_dtm` DATETIME COMMENT '3;ポイント処理日時;',
    `transaction_cd` VARCHAR(40) BINARY COMMENT '4;トランザクションコード（Yahoo）;yahoo_point_book.transaction_cd',
    `company_cd` VARCHAR(4) BINARY COMMENT '5;企業コード（Yahoo）;Yahoo から割り当てたら得たコード',
    `point_cd` VARCHAR(4) BINARY COMMENT '6;ポイントコード（Yahoo）;Yahoo から割り当てられたコード',
    `get_yahoo_point` INT COMMENT '7;獲得Yahooポイント;',
    `use_yahoo_point` INT COMMENT '8;消費Yahooポイント;',
    `verify_status` TINYINT COMMENT '9;ベリファイステータス;0:未ベリファイ 1:ベリファイ済み',
    `verify_dtm` DATETIME COMMENT '10;ベリファイ日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '11;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '12;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '13;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '14;更新日時;'
);

ALTER TABLE
    `verify_yahoo_point` COMMENT 'ベリファイYahooポイント;';

--   *** ------------------------------------
--  *** ICE_REPLY
--   *** ------------------------------------
-- 
CREATE TABLE `voice_reply` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `voice_cd` VARCHAR(13) BINARY COMMENT '2;投稿ID;YYYYMMNNNNNNN',
    `reply_type` TINYINT DEFAULT 0 COMMENT '3;返答者;0:施設 1:運用',
    `answer` VARCHAR(3000) BINARY COMMENT '4;返答内容;',
    `reply_dtm` DATETIME COMMENT '5;返答日時;返答内容を追加した時の日時、更新時は変更しない',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `voice_reply` COMMENT '宿泊体験返答;';

--   *** ------------------------------------
--  *** ICE_REVIEW
--   *** ------------------------------------
-- 
CREATE TABLE `voice_review` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `review_id` TINYINT COMMENT '3;クチコミ項目;部屋:１ バス・トイレ：2 食事（朝食・夕食）:3 接客・サービス:4 料金:5 立地:6 総合:0',
    `review_cnt` DECIMAL(2, 1) COMMENT '4;クチコミ評点;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '5;施設コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `voice_review` COMMENT 'クチコミ評点;';

--   *** ------------------------------------
--  *** ICE_STAY
--   *** ------------------------------------
-- 
CREATE TABLE `voice_stay` (
    `voice_cd` VARCHAR(13) BINARY COMMENT '1;投稿ID;YYYYMMNNNNNNN',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '2;予約コード;YYYYMMNNNNNNNN',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '3;施設コード;',
    `member_cd` VARCHAR(128) BINARY COMMENT '4;会員コード;ベストリザーブ会員は20バイト',
    `title` VARCHAR(90) BINARY COMMENT '5;投稿タイトル;',
    `explain` VARCHAR(1500) BINARY COMMENT '6;投稿内容;',
    `experience_dtm` DATETIME COMMENT '7;投稿日時;投稿内容を追加した時の日時、更新時は変更しない',
    `limit_dtm` DATETIME COMMENT '8;有効期限;',
    `status` TINYINT DEFAULT 0 COMMENT '9;状態;0:有効  1:本人取り消し 2:強制取り消し',
    `cancel_dtm` DATETIME COMMENT '10;取り消し日時;',
    `note` VARCHAR(3000) BINARY COMMENT '11;備考;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;',
    `gender` VARCHAR(1) BINARY,
    `age` SMALLINT
);

ALTER TABLE
    `voice_stay` COMMENT '宿泊体験;';

--   *** ------------------------------------
--  *** ATHER_AREA_CITY
--   *** ------------------------------------
-- 
CREATE TABLE `weather_area_city` (
    `city_id` DECIMAL(20, 0) COMMENT '1;市ID;',
    `weather_area_id` SMALLINT COMMENT '2;天気予報地域ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `weather_area_city` COMMENT '天気予報地域対応表;';

--   *** ------------------------------------
--  *** LFARE_DEST_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_dest_history` (
    `welfare_dest_history_id` BIGINT COMMENT '1;福利厚生割引対象目的地履歴ID;',
    `welfare_grants_history_id` BIGINT COMMENT '2;福利厚生補助金情報履歴ID;',
    `destination_type` TINYINT COMMENT '3;目的地タイプ;1:地方エリア・2:県・3:詳細エリア・4:施設指定',
    `area_id` SMALLINT COMMENT '4;地域ID;目的地タイプ:1,3の場合に値が設定される',
    `pref_id` TINYINT COMMENT '5;都道府県ID;目的地タイプ:2の場合に値が設定される',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;目的地タイプ:4の場合に値が設定される',
    `entry_cd` VARCHAR(64) BINARY COMMENT '7;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '8;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '9;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '10;更新日時;'
);

ALTER TABLE
    `welfare_dest_history` COMMENT '福利厚生割引対象目的地履歴;補助金情報更新処理で更新前の補助金情報目的地を管理するテーブル';

--   *** ------------------------------------
--  *** LFARE_GRANTS
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_grants` (
    `welfare_grants_id` BIGINT COMMENT '1;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '2;福利厚生補助金情報履歴ID;最新の福利厚生補助金情報履歴ID',
    `note` VARCHAR(3000) BINARY COMMENT '3;メモ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;',
    `ro_affiliater_cd` VARCHAR(10) BINARY COMMENT 'レコードオーナーのアフィリエイターコード',
    `ro_partner_cd` VARCHAR(10) BINARY COMMENT 'レコードオーナーの提携先コード',
    `coupon_flg` TINYINT COMMENT 'NULL or 0:補助金、1:クーポン'
);

ALTER TABLE
    `welfare_grants` COMMENT '福利厚生補助金情報;最新の補助金情報を管理するテーブル';

--   *** ------------------------------------
--  *** LFARE_GRANTS_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_grants_history` (
    `welfare_grants_history_id` BIGINT COMMENT '1;福利厚生補助金情報履歴ID;',
    `welfare_grants_id` BIGINT COMMENT '2;福利厚生補助金ID;',
    `branch_no` SMALLINT COMMENT '3;変更履歴枝番号;',
    `discount_nm` VARCHAR(300) BINARY COMMENT '4;割引名称;',
    `user_site_disp_nm` VARCHAR(300) BINARY COMMENT '5;お客様表示名称;',
    `creator_nm` VARCHAR(36) BINARY COMMENT '6;登録者名;',
    `active_flg` TINYINT COMMENT '7;公開/非公開フラグ;1:公開・0:非公開',
    `comb_available_flg` TINYINT COMMENT '8;併用可/不可フラグ;1:併用可・0:併用不可',
    `target_rsv_s_dtm` DATETIME COMMENT '9;対象予約期間開始日時;年月日時分秒まで設定',
    `target_rsv_e_dtm` DATETIME COMMENT '10;対象予約期間終了日時;年月日時分秒まで設定',
    `target_stay_s_ymd` DATETIME COMMENT '11;対象宿泊期間開始日;',
    `target_stay_e_ymd` DATETIME COMMENT '12;対象宿泊期間終了日;',
    `use_cond_type` TINYINT COMMENT '13;利用条件数タイプ;1:人泊 2:人 3:組数',
    `min_use_cond_num` INT COMMENT '14;最低利用条件数;',
    `max_use_cond_num` INT COMMENT '15;最大利用条件数;',
    `first_arrival_type` TINYINT COMMENT '16;先着タイプ;1:対象条件として使用 2:対象条件として使用しない',
    `first_arrival_num` INT COMMENT '17;先着数;単位は組数(予約数)',
    `destination_type` TINYINT COMMENT '18;目的地タイプ;1:地方エリア・2:県・3:詳細エリア・4:施設指定',
    `adult_discount_charge` INT COMMENT '20;大人割引金額;',
    `child_discount_charge` INT COMMENT '21;子供割引金額;',
    `url` VARCHAR(512) BINARY COMMENT '22;割引案内ページURL;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '23;提携先コード;',
    `affiliater_cd` VARCHAR(10) BINARY COMMENT '24;アフィリエイターコード;YYYYNNNNNN',
    `available_s_dtm` DATETIME COMMENT '25;有効期間開始日時;',
    `available_e_dtm` DATETIME COMMENT '26;有効期間終了日時;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '27;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '28;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '29;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '30;更新日時;',
    `apply_type` TINYINT COMMENT '19;補助金適用タイプ;1:人泊 2:人 3:組数',
    `charge_lower_limit_type` TINYINT COMMENT 'NULL:適用しない 1:人泊 2:人 3:組数',
    `charge_lower_limit` INT COMMENT 'この金額以上なら適用',
    `holiday_bfo_type` TINYINT COMMENT '0:対象条件として使用しない 1:休前日を除く 2:休前日のみ',
    `get_limit` BIGINT COMMENT '会員毎の取得枚数上限',
    `discount_src_limit` BIGINT COMMENT '割引対象宿泊料金上限   割引タイプが％の場合、この金額まで割引可能。0の場合は無制限。'
);

ALTER TABLE
    `welfare_grants_history` COMMENT '福利厚生補助金情報履歴;補助金情報更新処理で更新前の補助金情報を管理するテーブル';

--   *** ------------------------------------
--  *** LFARE_MATCH
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_match` (
    `welfare_match_id` BIGINT COMMENT '1;福利厚生補助金_マッチングID;',
    `welfare_grants_id` BIGINT COMMENT '2;福利厚生補助金ID;',
    `welfare_match_history_id` BIGINT COMMENT '3;福利厚生補助金_マッチング履歴ID;',
    `partner_cd` BIGINT COMMENT '4;提携先コード;',
    `affiliater_cd` VARCHAR(10) BINARY COMMENT '5;アフィリエイターコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `welfare_match` COMMENT '福利厚生補助金_マッチング情報;補助金と、会員の紐付';

--   *** ------------------------------------
--  *** LFARE_MATCH_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_match_history` (
    `welfare_match_history_id` BIGINT COMMENT '1;福利厚生補助金_マッチング履歴ID;',
    `welfare_match_id` BIGINT COMMENT '2;福利厚生補助金_マッチングID;',
    `branch_no` SMALLINT COMMENT '3;更新履歴枝番;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `welfare_match_history` COMMENT '福利厚生補助金_マッチング情報履歴;紐付更新処理で更新前の各補助金情報と紐づく会員ランクの情報を管理するテーブル';

--   *** ------------------------------------
--  *** LFARE_OP
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_op` (
    `welfare_op_id` BIGINT COMMENT '1;	補助金情報操作ID;',
    `partner_cd` VARCHAR(10) BINARY COMMENT '2;提携先コード;',
    `affiliater_cd` VARCHAR(10) BINARY COMMENT '3;アフィリエイターコード;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `welfare_op` COMMENT '福利厚生補助金情報操作;画面からの更新履歴';

--   *** ------------------------------------
--  *** LFARE_OP_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_op_history` (
    `welfare_op_history_id` VARCHAR(10) BINARY COMMENT '1;補助金情報操作履歴ID;',
    `welfare_op_id` VARCHAR(10) BINARY COMMENT '2;補助金情報操作ID;',
    `welfare_match_id` BIGINT COMMENT '3;福利厚生補助金_マッチングID;',
    `welfare_match_history_id` BIGINT COMMENT '4;福利厚生補助金_マッチング履歴ID;',
    `welfare_grants_id` BIGINT COMMENT '5;福利厚生補助金ID;',
    `welfare_grants_history_id` BIGINT COMMENT '6;福利厚生補助金履歴ID;',
    `update_type` TINYINT COMMENT '7;更新タイプ;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `welfare_op_history` COMMENT '福利厚生補助金情報操作履歴;補助金の操作履歴';

--   *** ------------------------------------
--  *** LFARE_RANK_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `welfare_rank_history` (
    `welfare_rank_history_id` BIGINT COMMENT '1;福利厚生補助金_ランク情報履歴ID;',
    `welfare_match_history_id` BIGINT COMMENT '2;福利厚生補助金_マッチング履歴ID;',
    `rank_id` VARCHAR(10) BINARY COMMENT '3;ランクID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `welfare_rank_history` COMMENT '福利厚生補助金_ランク情報履歴;紐づく会員ランクの情報を管理するテーブル';

--   *** ------------------------------------
--  *** _ROOM_PLAN_SALES
--   *** ------------------------------------
-- 
CREATE TABLE `wk_room_plan_sales` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `stock_status` TINYINT COMMENT '4;在庫有無;0:なし 1:あり',
    `charge_status` TINYINT COMMENT '5;料金有無;0:なし 1:あり',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;'
);

ALTER TABLE
    `wk_room_plan_sales` COMMENT '販売部屋プラン;';

--   *** ------------------------------------
--  *** HOO_POINT_BOOK
--   *** ------------------------------------
-- 
CREATE TABLE `yahoo_point_book` (
    `yahoo_point_cd` VARCHAR(16) BINARY COMMENT '1;ヤフーポイントコード;YYYYMMNNNNNNNNNN',
    `relation_cd` VARCHAR(16) BINARY COMMENT '2;関連ヤフーポイントコード;YYYYMMNNNNNNNNNN',
    `transaction_cd` VARCHAR(40) BINARY COMMENT '3;トランザクションコード（ヤフー）;処理開始年月日時分秒＋予約コード＋３桁連番（999から降順）',
    `yahoo_point_type` TINYINT COMMENT '4;ヤフーポイント種別;11:獲得、12:消費',
    `get_yahoo_point` INT COMMENT '5;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point` INT COMMENT '6;消費ヤフーポイント;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '7;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '8;宿泊日;',
    `various_cd` VARCHAR(64) BINARY COMMENT '9;汎用コード;',
    `applied_ymd` DATETIME COMMENT '10;適用年月日;',
    `shift_dtm` DATETIME COMMENT '11;確定年月日時分秒;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '12;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '13;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '14;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '15;更新日時;'
);

ALTER TABLE
    `yahoo_point_book` COMMENT 'ヤフーポイント台帳;確定済みのYahooポイント';

--   *** ------------------------------------
--  *** HOO_POINT_BOOK_PRE
--   *** ------------------------------------
-- 
CREATE TABLE `yahoo_point_book_pre` (
    `yahoo_point_cd` VARCHAR(16) BINARY COMMENT '1;ヤフーポイントコード;YYYYMMNNNNNNNNNN',
    `relation_cd` VARCHAR(16) BINARY COMMENT '2;関連ヤフーポイントコード;YYYYMMNNNNNNNNNN',
    `transaction_cd` VARCHAR(40) BINARY COMMENT '3;トランザクションコード（ヤフー）;処理開始年月日時分秒＋予約コード＋３桁連番（999から降順）',
    `yahoo_point_type` TINYINT COMMENT '4;ヤフーポイント種別;:仮獲得、2:仮消費、-1:仮獲得取消、-2:仮消費取消',
    `get_yahoo_point` INT COMMENT '5;獲得ヤフーポイント;1ポイント１円 税込',
    `use_yahoo_point` INT COMMENT '6;消費ヤフーポイント;',
    `reserve_cd` VARCHAR(14) BINARY COMMENT '7;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '8;宿泊日;',
    `various_cd` VARCHAR(64) BINARY COMMENT '9;汎用コード;',
    `applied_ymd` DATETIME COMMENT '10;適用年月日;',
    `shifting_ymd` DATETIME COMMENT '11;確定予定年月日;',
    `shift_dtm` DATETIME COMMENT '12;確定年月日時分秒;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `yahoo_point_book_pre` COMMENT 'ヤフーポイント仮台帳;';

--   *** ------------------------------------
--  *** HOO_POINT_CANCEL_QUEUE
--   *** ------------------------------------
-- 
CREATE TABLE `yahoo_point_cancel_queue` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `date_ymd` DATETIME COMMENT '2;宿泊日;',
    `canceling_ymd` DATETIME COMMENT '3;キャンセル予定日;',
    `cancel_status` TINYINT COMMENT '4;キャンセルステータス;0:キャンセル対象外 1:キャンセル対象 2:キャンセル済み',
    `cancel_dtm` DATETIME COMMENT '5;処理日時;キャンセル済みに変更した日時',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `yahoo_point_cancel_queue` COMMENT 'ヤフーポイントキャンセルキュー;施設・スタッフがキャンセル操作した分の';

--   *** ------------------------------------
--  *** HOO_POINT_PLUS_HOTEL
--   *** ------------------------------------
-- 
CREATE TABLE `yahoo_point_plus_hotel` (
    `point_plus_id` BIGINT COMMENT '1;ポイント加算情報ID;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '2;施設コード;',
    `target_status` TINYINT COMMENT '3;対象状態;0:加算対象外(削除扱い) 1:加算対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `yahoo_point_plus_hotel` COMMENT 'ヤフーポイント加算対象施設;';

--   *** ------------------------------------
--  *** HOO_POINT_PLUS_INFO
--   *** ------------------------------------
-- 
CREATE TABLE `yahoo_point_plus_info` (
    `point_plus_id` BIGINT COMMENT '1;ポイント加算情報ID;',
    `point_plus_nm` VARCHAR(96) BINARY COMMENT '2;ポイント加算情報名称;',
    `description` VARCHAR(4000) BINARY COMMENT '3;ポイント加算情報説明;',
    `description_to_hotel` VARCHAR(4000) BINARY COMMENT '4;ポイント加算情報施設向け説明;',
    `target_rsv_s_ymd` DATETIME COMMENT '5;対象予約期間開始日;',
    `target_rsv_e_ymd` DATETIME COMMENT '6;対象予約期間終了日;',
    `plus_point_rate` DECIMAL(5, 2) COMMENT '7;加算ヤフーポイント率;',
    `plus_target_type` TINYINT COMMENT '8;加算対象;1:全施設 2:特定施設 3:特定プラン',
    `display_status` TINYINT COMMENT '9;表示ステータス;0:非表示 1:表示',
    `entry_cd` VARCHAR(64) BINARY COMMENT '10;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '11;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '12;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '13;更新日時;'
);

ALTER TABLE
    `yahoo_point_plus_info` COMMENT 'ヤフーポイント加算情報;施設様設定の付与ポイント率に対しBR側で加算するポイント率の履歴情報';

--   *** ------------------------------------
--  *** HOO_POINT_PLUS_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `yahoo_point_plus_plan` (
    `point_plus_id` BIGINT COMMENT '1;ポイント加算情報ID;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `target_status` TINYINT COMMENT '3;対象状態;0:加算対象外(削除扱い) 1:加算対象',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `yahoo_point_plus_plan` COMMENT 'ヤフーポイント加算対象プラン;';

--   *** ------------------------------------
--  *** P_BASE_TIME
--   *** ------------------------------------
-- 
CREATE TABLE `ydp_base_time` (
    `partner_cd` VARCHAR(10) BINARY COMMENT '1;提携先コード;',
    `cooperation_cd` VARCHAR(4) BINARY COMMENT '2;連携情報種別;',
    `base_tm` DATETIME COMMENT '3;基準時間;',
    `time_range` SMALLINT COMMENT '4;連携時間範囲;',
    `activ_fg` VARCHAR(1) BINARY COMMENT '5;有効フラグ;',
    `upd_id` VARCHAR(10) BINARY COMMENT '6;更新者ID;',
    `upd_dt` DATETIME COMMENT '7;最終更新時刻;',
    `stock_fg` VARCHAR(1) BINARY COMMENT '8;在庫譲渡範囲フラグ;',
    `max_cnt` INT COMMENT '9;最大取得件数;',
    `day_range` SMALLINT COMMENT '10;取得日数;',
    `cooperation_type_cd` TINYINT COMMENT '11;連携対象コード;',
    `timeout` SMALLINT COMMENT '12;タイムアウト値;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '13;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '14;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '15;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '16;更新日時;'
);

ALTER TABLE
    `ydp_base_time` COMMENT '外部連携基準時間;fd_base_timeからydp_base_timeにテーブル名変更';

--   *** ------------------------------------
--  *** P_ITEM
--   *** ------------------------------------
-- 
CREATE TABLE `ydp_item` (
    `cooperation_cd` VARCHAR(4) BINARY COMMENT '1;連携情報種別;',
    `item_cd` VARCHAR(30) BINARY COMMENT '2;連携コード;',
    `item_nm` VARCHAR(60) BINARY COMMENT '3;連携コード名;',
    `ins_id` VARCHAR(10) BINARY COMMENT '4;登録者ID;',
    `ins_dt` DATETIME COMMENT '5;新規登録時刻;',
    `upd_id` VARCHAR(10) BINARY COMMENT '6;更新者ID;',
    `upd_dt` DATETIME COMMENT '7;最終更新時刻;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '8;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '9;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '10;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '11;更新日時;'
);

ALTER TABLE
    `ydp_item` COMMENT '外部連携コードマスタ;';

--   *** ------------------------------------
--  *** P_ITEM_CONTROL
--   *** ------------------------------------
-- 
CREATE TABLE `ydp_item_control` (
    `affiliate_id` VARCHAR(10) BINARY COMMENT '1;アフィリエイトID;',
    `cooperation_cd` VARCHAR(4) BINARY COMMENT '2;連携情報種別;',
    `item_cd` VARCHAR(30) BINARY COMMENT '3;連携項目コード;',
    `use_fg` VARCHAR(1) BINARY COMMENT '4;利用フラグ;',
    `ins_id` VARCHAR(10) BINARY COMMENT '5;登録者ID;',
    `ins_dt` DATETIME COMMENT '6;新規登録時刻;',
    `upd_id` VARCHAR(10) BINARY COMMENT '7;更新者ID;',
    `upd_dt` DATETIME COMMENT '8;最終更新時刻;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `ydp_item_control` COMMENT '外部連携項目設定マスタ;';

--   *** ------------------------------------
--  *** KON
--   *** ------------------------------------
-- 
CREATE TABLE `yokon` (`id1` VARCHAR(1) BINARY);

--   *** ------------------------------------
--  *** P_MEMBER_EPARK
--   *** ------------------------------------
-- 
CREATE TABLE `zap_member_epark` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `epark_id` VARCHAR(30) BINARY COMMENT '2;EPARK会員ID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;',
    `relate_status` TINYINT COMMENT '3;連携ステータス;0:本連携、1:自動生成による仮連携',
    `active_status` TINYINT COMMENT '4;有効フラグ;0:無効,１:有効',
    `relate_start_ts` DATETIME COMMENT '5;連携開始日時;',
    `relate_del_ts` DATETIME COMMENT '6;連携削除日時;'
);

ALTER TABLE
    `zap_member_epark` COMMENT 'EPARK会員CD中間テーブル;BR会員とEPARK会員連携状態を保持する';

--   *** ------------------------------------
--  *** P_MEMBER_EPARK_HISTORY
--   *** ------------------------------------
-- 
CREATE TABLE `zap_member_epark_history` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `epark_id` VARCHAR(30) BINARY COMMENT '2;EPARK会員ID;',
    `relate_status` TINYINT COMMENT '3;連携ステータス;0:本連携、1:自動生成による仮連携',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `zap_member_epark_history` COMMENT 'EPARK会員CD中間テーブル	履歴;BR会員とEPARK会員連携状態の履歴を保持する';

--   *** ------------------------------------
--  *** P_MEMBER_YAHOO
--   *** ------------------------------------
-- 
CREATE TABLE `zap_member_yahoo` (
    `member_cd` VARCHAR(128) BINARY COMMENT '1;会員コード;ベストリザーブ会員は20バイト',
    `bbauth_member_cd` VARCHAR(128) BINARY COMMENT '2;BBAuth会員コード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '3;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '4;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '5;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '6;更新日時;'
);

ALTER TABLE
    `zap_member_yahoo` COMMENT 'Yahoo会員CD中間テーブル;BBAuth会員CDとYconnect会員CD紐付テーブル';

--   *** ------------------------------------
--  *** P_MSC
--   *** ------------------------------------
-- 
CREATE TABLE `zap_msc` (
    `random_cd` VARCHAR(64) BINARY COMMENT '1;ランダムコード;最初の予約コードを設定（複数部屋の予約について）',
    `system_nm` VARCHAR(12) BINARY COMMENT '2;システム名称;rakujan:らくじゃん tema:手間いらず',
    `transaction` VARCHAR(64) BINARY COMMENT '3;トランザクション;',
    `accept_s_dtm` DATETIME COMMENT '4;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '5;終了日時;',
    `diff_sec` BIGINT COMMENT '6;差秒;'
);

ALTER TABLE
    `zap_msc` COMMENT 'MSC処理時間計測;';

--   *** ------------------------------------
--  *** P_PLAN_PARTNER_GROUP
--   *** ------------------------------------
-- 
CREATE TABLE `zap_plan_partner_group` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `partner_group_id` BIGINT COMMENT '4;提携先グループID;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `zap_plan_partner_group` COMMENT 'プラン提携先グループ【旧】;';

--   *** ------------------------------------
--  *** P_PLAN_POINT
--   *** ------------------------------------
-- 
CREATE TABLE `zap_plan_point` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `plan_id` VARCHAR(10) BINARY COMMENT '2;プランID;',
    `before_issue_point_rate` SMALLINT COMMENT '3;変更前獲得ポイント率;',
    `issue_point_rate` SMALLINT COMMENT '4;獲得ポイント率;Yahoo!ポイント専用、BRは通常は1%、プレミアムは2%',
    `entry_cd` VARCHAR(64) BINARY COMMENT '5;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '6;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '7;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '8;更新日時;'
);

ALTER TABLE
    `zap_plan_point` COMMENT 'プランポイント設定情報（当社ゼロポイント負担対応用）;';

--   *** ------------------------------------
--  *** P_RAKUJAN
--   *** ------------------------------------
-- 
CREATE TABLE `zap_rakujan` (
    `random_cd` VARCHAR(64) BINARY COMMENT '1;ランダムコード;最初の予約コードを設定（複数部屋の予約について）',
    `check_point` VARCHAR(64) BINARY COMMENT '2;チェックポイント;',
    `transaction` VARCHAR(64) BINARY COMMENT '3;トランザクション;',
    `accept_s_dtm` DATETIME COMMENT '4;開始日時;',
    `accept_e_dtm` DATETIME COMMENT '5;終了日時;',
    `diff_sec` BIGINT COMMENT '6;差秒;',
    `sec` BIGINT COMMENT '7;合計秒;',
    `account_id` VARCHAR(20) BINARY COMMENT '8;アカウントID;大文字統一',
    `length_xml` BIGINT COMMENT '9;XML文字長;',
    `xml` LONGTEXT COMMENT '10;XML;',
    `memory` BIGINT COMMENT '11;使用メモリ（キロ）;'
);

ALTER TABLE
    `zap_rakujan` COMMENT 'らくじゃん速度計測;';

--   *** ------------------------------------
--  *** P_RESERVE_SP
--   *** ------------------------------------
-- 
CREATE TABLE `zap_reserve_sp` (
    `reserve_cd` VARCHAR(14) BINARY COMMENT '1;予約コード;YYYYMMNNNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '2;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '3;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '4;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '5;更新日時;'
);

ALTER TABLE
    `zap_reserve_sp` COMMENT 'スマートフォン予約リスト;reserve_system への対応が終わるまでの暫定テーブルです。';

--   *** ------------------------------------
--  *** P_ROOM
--   *** ------------------------------------
-- 
CREATE TABLE `zap_room` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `room_id` VARCHAR(10) BINARY COMMENT '3;部屋ID;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '4;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '5;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '6;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '7;更新日時;'
);

ALTER TABLE
    `zap_room` COMMENT '部屋作成テーブル;';

--   *** ------------------------------------
--  *** P_ROOM_CHARGE
--   *** ------------------------------------
-- 
CREATE TABLE `zap_room_charge` (
    `hotel_cd` VARCHAR(10) BINARY,
    `room_cd` VARCHAR(10) BINARY,
    `plan_cd` VARCHAR(10) BINARY,
    `partner_group_id` BIGINT,
    `date_ymd` DATETIME,
    `usual_charge` INT,
    `sales_charge` INT,
    `accept_status` TINYINT,
    `accept_s_dtm` DATETIME,
    `accept_e_dtm` DATETIME,
    `low_price_status` TINYINT,
    `entry_cd` VARCHAR(64) BINARY,
    `entry_ts` DATETIME,
    `modify_cd` VARCHAR(64) BINARY,
    `modify_ts` DATETIME
);

--   *** ------------------------------------
--  *** P_ROOM_PLAN
--   *** ------------------------------------
-- 
CREATE TABLE `zap_room_plan` (
    `hotel_cd` VARCHAR(10) BINARY COMMENT '1;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '2;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '3;プランコード;',
    `room_id` VARCHAR(10) BINARY COMMENT '4;部屋ID;YYYYNNNNNN',
    `plan_id` VARCHAR(10) BINARY COMMENT '5;プランID;YYYYNNNNNN',
    `entry_cd` VARCHAR(64) BINARY COMMENT '6;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '7;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '8;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '9;更新日時;'
);

ALTER TABLE
    `zap_room_plan` COMMENT 'プラン作成テーブル;';

--   *** ------------------------------------
--  *** P_ROOM_PLAN_CHARGE
--   *** ------------------------------------
-- 
CREATE TABLE `zap_room_plan_charge` (
    `id` BIGINT COMMENT '1;ID;',
    `parent_hotel_cd` VARCHAR(10) BINARY COMMENT '2;親施設コード;',
    `parent_room_id` VARCHAR(10) BINARY COMMENT '3;親部屋ID;',
    `parent_plan_id` VARCHAR(10) BINARY COMMENT '4;親プランID;',
    `parent_capacity` TINYINT COMMENT '5;親人数;',
    `hotel_cd` VARCHAR(10) BINARY COMMENT '6;施設コード;',
    `room_cd` VARCHAR(10) BINARY COMMENT '7;部屋コード;',
    `plan_cd` VARCHAR(10) BINARY COMMENT '8;プランコード;',
    `entry_cd` VARCHAR(64) BINARY COMMENT '9;登録者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `entry_ts` DATETIME COMMENT '10;登録日時;',
    `modify_cd` VARCHAR(64) BINARY COMMENT '11;更新者コード;/controller/action.(user_id) または 更新者メールアドレス',
    `modify_ts` DATETIME COMMENT '12;更新日時;'
);

ALTER TABLE
    `zap_room_plan_charge` COMMENT '料金データ旧との互換性;新料金で複数の利用人数が登録された場合に旧部屋プランにプランを作成し料金を登録し関連性を保持';

-- シーケンス用
CREATE TABLE `tbl_sequence` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL COMMENT 'カラム名',
    `current_val` bigint(15) NOT NULL DEFAULT '0' COMMENT '現在値',
    `increment` int(11) NOT NULL DEFAULT '1' COMMENT '加算値',
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB AUTO_INCREMENT = 119 DEFAULT CHARSET = cp932;