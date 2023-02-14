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