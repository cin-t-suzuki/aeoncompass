USE ac_travel;

CREATE TABLE `insurance_payment_bank` (
    `reserve_cd` varchar(14) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '予約コード|',
    `branch_no` int(11) NOT NULL COMMENT '枝番|',
    `payment_bank_cd` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '支払銀行コード|',
    `payment_bank_branch_cd` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '支払支店コード|',
    `payment_bank_account_type` int(11) DEFAULT NULL COMMENT '支払口座種別|1:普通 2:当座（4:貯蓄 9:その他）',
    `payment_bank_account_no` varchar(14) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '支払口座番号|口座番号７桁',
    `payment_bank_account_kn` varchar(90) DEFAULT NULL COMMENT '支払口座名義（カナ）|',
    `entry_cd` varchar(50) DEFAULT NULL COMMENT '登録者コード|',
    `entry_ts` datetime DEFAULT NULL COMMENT '登録日時|',
    `modify_cd` varchar(50) DEFAULT NULL COMMENT '更新者コード|',
    `modify_ts` datetime DEFAULT NULL COMMENT '更新日時|',
    PRIMARY KEY (`reserve_cd`, `branch_no`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `member_card_no` (
    `member_cd` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '会員コード|ベストリザーブ会員は20バイト',
    `branch_no` int(11) NOT NULL COMMENT '枝番|',
    `card_no` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'クレジットカード番号|',
    `entry_cd` varchar(50) DEFAULT NULL COMMENT '登録者コード|',
    `entry_ts` datetime DEFAULT NULL COMMENT '登録日時|',
    `modify_cd` varchar(50) DEFAULT NULL COMMENT '更新者コード|',
    `modify_ts` datetime DEFAULT NULL COMMENT '更新日時|',
    PRIMARY KEY (`member_cd`, `branch_no`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '会員カード番号|';

CREATE TABLE `member_creditcard_no` (
    `partner_cd` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '提携先コード|',
    `member_cd` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '会員コード|ベストリザーブ会員は20バイト',
    `branch_no` int(11) NOT NULL COMMENT '枝番|',
    `card_no` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'クレジットカード番号|暗号化した値（Blowfish）',
    `entry_cd` varchar(50) DEFAULT NULL COMMENT '登録者コード|',
    `entry_ts` datetime DEFAULT NULL COMMENT '登録日時|',
    `modify_cd` varchar(50) DEFAULT NULL COMMENT '更新者コード|',
    `modify_ts` datetime DEFAULT NULL COMMENT '更新日時|',
    PRIMARY KEY (`partner_cd`, `member_cd`, `branch_no`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '会員カード番号（提携先対応）|';

CREATE TABLE `member_password` (
    `member_cd` varchar(64) NOT NULL COMMENT '会員コード|ベストリザーブ会員は20バイト',
    `password` varchar(64) DEFAULT NULL COMMENT 'パスワード|MD5した値',
    `entry_cd` varchar(50) DEFAULT NULL COMMENT '登録者コード|',
    `entry_ts` datetime DEFAULT NULL COMMENT '登録日時|',
    `modify_cd` varchar(50) DEFAULT NULL COMMENT '更新者コード|',
    `modify_ts` datetime DEFAULT NULL COMMENT '更新日時|',
    PRIMARY KEY (`member_cd`),
    KEY `member_password_ind_01` (`password`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '会員パスワード|';

CREATE TABLE `reserve_authori_card` (
    `reserve_cd` varchar(14) NOT NULL COMMENT '予約コード|',
    `card_no` varchar(32) DEFAULT NULL COMMENT 'クレジットカード番号|暗号化した値',
    `entry_cd` varchar(50) DEFAULT NULL COMMENT '登録者コード|',
    `entry_ts` datetime DEFAULT NULL COMMENT '登録日時|',
    `modify_cd` varchar(50) DEFAULT NULL COMMENT '更新者コード|',
    `modify_ts` datetime DEFAULT NULL COMMENT '更新日時|',
    PRIMARY KEY (`reserve_cd`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = 'オーソリ状況テーブル|';

CREATE TABLE `reserve_material_card` (
    `partner_cd` varchar(10) NOT NULL COMMENT '提携先コード|',
    `ccd` varchar(14) NOT NULL COMMENT '予約接続コード|',
    `room` tinyint(4) NOT NULL COMMENT '部屋数|',
    `card_no` varchar(32) DEFAULT NULL,
    `entry_cd` varchar(50) DEFAULT NULL COMMENT '登録者コード|',
    `entry_ts` datetime DEFAULT NULL COMMENT '登録日時|',
    `modify_cd` varchar(50) DEFAULT NULL COMMENT '更新者コード|',
    `modify_ts` datetime DEFAULT NULL COMMENT '更新日時|',
    PRIMARY KEY (`partner_cd`, `ccd`, `room`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '予約確定材料|';

CREATE TABLE `yho_access_token` (
    `access_token_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `access_token` varchar(6144) DEFAULT NULL,
    `user_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `double_check` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `entry_cd` varchar(50) DEFAULT NULL,
    `entry_ts` datetime DEFAULT NULL,
    `modify_cd` varchar(50) DEFAULT NULL,
    `modify_ts` datetime DEFAULT NULL,
    PRIMARY KEY (`access_token_key`),
    KEY `yho_access_token_ind_01` (`entry_ts`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `yho_nonce_token` (
    `nonce_token_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `nonce_token` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `entry_cd` varchar(50) DEFAULT NULL,
    `entry_ts` datetime DEFAULT NULL,
    `modify_cd` varchar(50) DEFAULT NULL,
    `modify_ts` datetime DEFAULT NULL,
    PRIMARY KEY (`nonce_token_key`),
    KEY `yho_nonce_token_ind_01` (`entry_ts`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `yho_refresh_token` (
    `refresh_token_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `refresh_token` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `user_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `double_check` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `entry_cd` varchar(50) DEFAULT NULL,
    `entry_ts` datetime DEFAULT NULL,
    `modify_cd` varchar(50) DEFAULT NULL,
    `modify_ts` datetime DEFAULT NULL,
    PRIMARY KEY (`refresh_token_key`),
    KEY `yho_refresh_token_ind_01` (`entry_ts`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `yho_state_token` (
    `state_token_key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `state_token` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `redirect_path` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `entry_cd` varchar(50) DEFAULT NULL,
    `entry_ts` datetime DEFAULT NULL,
    `modify_cd` varchar(50) DEFAULT NULL,
    `modify_ts` datetime DEFAULT NULL,
    PRIMARY KEY (`state_token_key`),
    KEY `yho_state_token_ind_01` (`entry_ts`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `yho_test_token` (
    `test_token_key` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `test_token` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
    `entry_cd` varchar(50) DEFAULT NULL,
    `entry_ts` datetime DEFAULT NULL,
    `modify_cd` varchar(50) DEFAULT NULL,
    `modify_ts` datetime DEFAULT NULL,
    PRIMARY KEY (`test_token_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `users` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT primary key,
    `email` varchar(255) NOT NULL unique COMMENT 'メールアドレス',
    `email_verified_at` timestamp NULL,
    `password` varchar(255) NOT NULL COMMENT 'パスワード',
    `remember_token` varchar(100) NULL,
    `secret_question` int(1) NOT NULL COMMENT '秘密の質問',
    `secret_answer` varchar(256) NOT NULL COMMENT '秘密の回答',

    `surname_kanji` varchar(40) NOT NULL COMMENT '姓（漢字）',
    `given_name_kanji` varchar(40) NOT NULL COMMENT '名（漢字）',
    `surname_kana` varchar(40) NOT NULL COMMENT 'セイ（カナ）',
    `given_name_kana` varchar(40) NOT NULL COMMENT 'メイ（カナ）',
    `sex` int(1) NOT NULL COMMENT '性別',
    `birth_date` date NOT NULL COMMENT '生年月日',
    `occupation` int(2) DEFAULT NULL COMMENT 'ご職業',
    `passport_surname` varchar(256) DEFAULT NULL COMMENT 'パスポート姓',
    `passport_given_name` varchar(256) DEFAULT NULL COMMENT 'パスポート名',
    `nationality` varchar(100) DEFAULT NULL COMMENT '国籍',
    `passport_no` varchar(20) DEFAULT NULL COMMENT '旅券番号',
    `issue_date` date DEFAULT NULL COMMENT '発行年月日',
    `expire_date` date DEFAULT NULL COMMENT '有効期間満了日',
    `phone_number` varchar(11) NOT NULL COMMENT '電話番号',
    `phone_email` varchar(256) DEFAULT NULL COMMENT '携帯メール',
    `postal_code` varchar(7) NOT NULL COMMENT '郵便番号',
    `address1` varchar(100) NOT NULL COMMENT '住所１',
    `address2` varchar(40) NOT NULL COMMENT '住所２',
    `address3` varchar(40) NOT NULL COMMENT '住所３',
    `address4` varchar(40) DEFAULT NULL COMMENT '住所４',
    `contact_type` int(1) NOT NULL COMMENT 'ご連絡先',
    `contact_name` varchar(100) DEFAULT NULL COMMENT 'ご連絡先名',
    `contact_phone_number` varchar(11) DEFAULT NULL COMMENT 'ご連絡先 電話番号',
    `contact_postal_number` varchar(7) DEFAULT NULL COMMENT 'ご連絡先 郵便番号',
    `contact_address1` varchar(100) DEFAULT NULL COMMENT 'ご連絡先 住所1',
    `contact_address2` varchar(40) DEFAULT NULL COMMENT 'ご連絡先 住所2',
    `contact_address3` varchar(40) DEFAULT NULL COMMENT 'ご連絡先 住所3',
    `contact_address4` varchar(40) DEFAULT NULL COMMENT 'ご連絡先 住所4',

    `deleted_at` datetime NULL DEFAULT NULL,
    `created_by` varchar(256) NOT NULL DEFAULT '',
    `created_at` datetime NULL,
    `updated_by` varchar(256) NOT NULL DEFAULT '',
    `updated_at` datetime NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
