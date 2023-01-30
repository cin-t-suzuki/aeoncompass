USE ac_travel;

-- 半角スペースを入れていないとmysqlはエラーになる
--   *** ------------------------------------
--  *** 火曜日-6月-07-2022   
--   *** ------------------------------------
--   *** ------------------------------------
--  *** Table REPORT_WEEK_HOTEL2
--   *** ------------------------------------
ALTER TABLE
    `report_week_hotel2`
ADD
    CONSTRAINT `report_week_hotel2_pky` PRIMARY KEY (
        `hotel_cd`,
        `reserve_ymd`,
        `date_ymd`,
        `charge_type`,
        `capacity`
    );

--   *** ------------------------------------
--  *** Table VOICE_REPLY
--   *** ------------------------------------
ALTER TABLE
    `voice_reply`
ADD
    CONSTRAINT `voice_reply_pky` PRIMARY KEY (`hotel_cd`, `voice_cd`);

--   *** ------------------------------------
--  *** Table EPARK_REFRESH_TOKEN
--   *** ------------------------------------
ALTER TABLE
    `epark_refresh_token`
ADD
    CONSTRAINT `epark_refresh_token_pky` PRIMARY KEY (`epark_id`);

--   *** ------------------------------------
--  *** Table YDP_ITEM_CONTROL
--   *** ------------------------------------
ALTER TABLE
    `ydp_item_control`
ADD
    CONSTRAINT `ydp_item_control_pky` PRIMARY KEY (`affiliate_id`, `cooperation_cd`, `item_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_9
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_9`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_9_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table HOTEL_POWERDOWN
--   *** ------------------------------------
ALTER TABLE
    `hotel_powerdown`
ADD
    CONSTRAINT `hotel_powerdown_pky` PRIMARY KEY (`hotel_cd`, `powerdown_seq`);

--   *** ------------------------------------
--  *** Table PLAN_POINT
--   *** ------------------------------------
ALTER TABLE
    `plan_point`
ADD
    CONSTRAINT `plan_point_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table PARTNER_SITE_RATE
--   *** ------------------------------------
ALTER TABLE
    `partner_site_rate`
ADD
    CONSTRAINT `partner_site_rate_pky` PRIMARY KEY (
        `site_cd`,
        `accept_s_ymd`,
        `fee_type`,
        `stock_class`
    );

--   *** ------------------------------------
--  *** Table RESERVE_PLAN
--   *** ------------------------------------
ALTER TABLE
    `reserve_plan`
ADD
    CONSTRAINT `reserve_plan_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_POINT
--   *** ------------------------------------
ALTER TABLE
    `reserve_point`
ADD
    CONSTRAINT `reserve_point_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table ZAP_ROOM
--   *** ------------------------------------
ALTER TABLE
    `zap_room`
ADD
    CONSTRAINT `zap_room_pky` PRIMARY KEY (`hotel_cd`, `room_cd`);

--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_BACK_NUMBER
--   *** ------------------------------------
ALTER TABLE
    `mail_magazine_back_number`
ADD
    CONSTRAINT `mail_magazine_back_number_pky` PRIMARY KEY (`magazine_no`);

--   *** ------------------------------------
--  *** Table ZAP_PLAN_PARTNER_GROUP
--   *** ------------------------------------
ALTER TABLE
    `zap_plan_partner_group`
ADD
    CONSTRAINT `zap_plan_partner_group_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `partner_group_id`
    );

--   *** ------------------------------------
--  *** Table ROOM_PLAN_CAPACITY
--   *** ------------------------------------
ALTER TABLE
    `room_plan_capacity`
ADD
    CONSTRAINT `room_plan_capacity_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_id`,
        `plan_id`,
        `date_ym`,
        `capacity`
    );

--   *** ------------------------------------
--  *** Table NOTIFY_RIZAPULI_STAY
--   *** ------------------------------------
ALTER TABLE
    `notify_rizapuli_stay`
ADD
    CONSTRAINT `notify_rizapuli_stay_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_5
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_5`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_5_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table PLAN_TYK
--   *** ------------------------------------
ALTER TABLE
    `plan_tyk`
ADD
    CONSTRAINT `plan_tyk_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table ROOM_AKF →修正
--   *** ------------------------------------
--  ALTER TABLE `room_akf` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `room_akf`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `room_akf` MODIFY (`room_id` NOT NULL ENABLE);
ALTER TABLE
    `room_akf`
MODIFY
    `room_id` varchar(10) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table PARTNER_BOOK_CUSTOMER
--   *** ------------------------------------
ALTER TABLE
    `partner_book_customer`
ADD
    CONSTRAINT `partner_book_customer_pky` PRIMARY KEY (`customer_id`);

--   *** ------------------------------------
--  *** Table GIFT_SUPPLIER
--   *** ------------------------------------
ALTER TABLE
    `gift_supplier`
ADD
    CONSTRAINT `gift_supplier_pky` PRIMARY KEY (`gift_supplier_id`);

--   *** ------------------------------------
--  *** Table MAST_WARDZONE
--   *** ------------------------------------
ALTER TABLE
    `mast_wardzone`
ADD
    CONSTRAINT `mast_wardzone_pky` PRIMARY KEY (`wardzone_id`);

--   *** ------------------------------------
--  *** Table AFFILIATE_PROGRAM
--   *** ------------------------------------
ALTER TABLE
    `affiliate_program`
ADD
    CONSTRAINT `affiliate_program_pky` PRIMARY KEY (`affiliate_cd`, `reserve_system`);

--   *** ------------------------------------
--  *** Table HOTEL_STATIONS
--   *** ------------------------------------
ALTER TABLE
    `hotel_stations`
ADD
    CONSTRAINT `hotel_stations_pky` PRIMARY KEY (`hotel_cd`, `station_id`, `traffic_way`);

--   *** ------------------------------------
--  *** Table RESERVE
--   *** ------------------------------------
ALTER TABLE
    `reserve`
ADD
    CONSTRAINT `reserve_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table SERVICE_HUNTING
--   *** ------------------------------------
ALTER TABLE
    `service_hunting`
ADD
    CONSTRAINT `service_hunting_pky` PRIMARY KEY (`hunting_id`);

ALTER TABLE
    `service_hunting`
ADD
    CONSTRAINT `service_hunting_unq_01` UNIQUE (`hotel_cd`, `open_ymd`);

--   *** ------------------------------------
--  *** Table MEMBER_YDP
--   *** ------------------------------------
ALTER TABLE
    `member_ydp`
ADD
    CONSTRAINT `member_ydp_pky` PRIMARY KEY (`member_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_YDP2
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ydp2`
ADD
    CONSTRAINT `room_plan_ydp2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table HOTEL_MSC_LOGIN
--   *** ------------------------------------
ALTER TABLE
    `hotel_msc_login`
ADD
    CONSTRAINT `hotel_msc_login_pky` PRIMARY KEY (`hotel_cd`, `msc_type`);

--   *** ------------------------------------
--  *** Table YAHOO_POINT_CANCEL_QUEUE
--   *** ------------------------------------
ALTER TABLE
    `yahoo_point_cancel_queue`
ADD
    CONSTRAINT `yahoo_point_cancel_queue_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table PLAN_POINT_20170101
--   *** ------------------------------------
ALTER TABLE
    `plan_point_20170101`
ADD
    CONSTRAINT `plan_point_20170101_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table LOG_RIZAPULI_NOTIFY
--   *** ------------------------------------
ALTER TABLE
    `log_rizapuli_notify`
ADD
    CONSTRAINT `log_rizapuli_notify_pky` PRIMARY KEY (`rizapuli_request_id`);

--   *** ------------------------------------
--  *** Table RESERVE_CHARGE_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `reserve_charge_detail`
ADD
    CONSTRAINT `reserve_charge_detail_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `capacity_type`);

--   *** ------------------------------------
--  *** Table RESERVE_TICKET
--   *** ------------------------------------
ALTER TABLE
    `reserve_ticket`
ADD
    CONSTRAINT `reserve_ticket_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `checksheet_yahoo`
ADD
    CONSTRAINT `checksheet_yahoo_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table FAX_PR
--   *** ------------------------------------
ALTER TABLE
    `fax_pr`
ADD
    CONSTRAINT `fax_pr_pky` PRIMARY KEY (`fax_pr_id`);

--   *** ------------------------------------
--  *** Table MAST_LANDMARK
--   *** ------------------------------------
ALTER TABLE
    `mast_landmark`
ADD
    CONSTRAINT `mast_landmark_pky` PRIMARY KEY (`landmark_id`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_11
--   *** ------------------------------------
ALTER TABLE
    `log_security_11`
ADD
    CONSTRAINT `log_security_11_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table PARTNER_DEFAULT
--   *** ------------------------------------
ALTER TABLE
    `partner_default`
ADD
    CONSTRAINT `partner_default_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table MAST_ROUTE
--   *** ------------------------------------
ALTER TABLE
    `mast_route`
ADD
    CONSTRAINT `mast_route_pky` PRIMARY KEY (`route_id`);

--   *** ------------------------------------
--  *** Table RESERVE_ADDED_GOTO
--   *** ------------------------------------
ALTER TABLE
    `reserve_added_goto`
ADD
    CONSTRAINT `reserve_added_goto_pky` PRIMARY KEY (`reserve_cd`);

--  ALTER TABLE `reserve_added_goto` MODIFY (`added_type` NOT NULL ENABLE);
ALTER TABLE
    `reserve_added_goto`
MODIFY
    `added_type` smallint NOT NULL;

--   *** ------------------------------------
--  *** Table LANDMARK_CAMPAIGN
--   *** ------------------------------------
ALTER TABLE
    `landmark_campaign`
ADD
    CONSTRAINT `landmark_campaign_pky` PRIMARY KEY (`campaign_id`);

--   *** ------------------------------------
--  *** Table HOTEL_YDP2_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydp2_yahoo`
ADD
    CONSTRAINT `hotel_ydp2_yahoo_pky` PRIMARY KEY (`ydp_hotel_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_RECOMMEND
--   *** ------------------------------------
ALTER TABLE
    `hotel_recommend`
ADD
    CONSTRAINT `hotel_recommend_pky` PRIMARY KEY (`member_cd`, `reserve_cd`, `recommend_id`);

--   *** ------------------------------------
--  *** Table EXTEND_SETTING_PLAN
--   *** ------------------------------------
ALTER TABLE
    `extend_setting_plan`
ADD
    CONSTRAINT `extend_setting_plan_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_08
--   *** ------------------------------------
ALTER TABLE
    `log_security_08`
ADD
    CONSTRAINT `log_security_08_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table ALERT_POST
--   *** ------------------------------------
ALTER TABLE
    `alert_post`
ADD
    CONSTRAINT `alert_post_pky` PRIMARY KEY (`person_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_RECEIPT
--   *** ------------------------------------
ALTER TABLE
    `hotel_receipt`
ADD
    CONSTRAINT `hotel_receipt_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table PAYMENT_MATCH
--   *** ------------------------------------
ALTER TABLE
    `payment_match`
ADD
    CONSTRAINT `payment_match_pky` PRIMARY KEY (`payment_match_id`);

--  ALTER TABLE `payment_match` MODIFY (`payment_match_dtm` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `payment_match_dtm` datetime NOT NULL;

--  ALTER TABLE `payment_match` MODIFY (`payment_id` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `payment_id` decimal(32, 0) NOT NULL;

--  ALTER TABLE `payment_match` MODIFY (`match_charge` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `match_charge` decimal(32, 0) NOT NULL;

--  ALTER TABLE `payment_match` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `payment_match` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `payment_match` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `payment_match` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `payment_match`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table CHECKSHEET_FIX
--   *** ------------------------------------
ALTER TABLE
    `checksheet_fix`
ADD
    CONSTRAINT `checksheet_fix_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ALERT_MAIL_OPC
--   *** ------------------------------------
ALTER TABLE
    `alert_mail_opc`
ADD
    CONSTRAINT `alert_mail_opc_pky` PRIMARY KEY (`person_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel`
ADD
    CONSTRAINT `billpay_hotel_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table SPOT_NEARBY
--   *** ------------------------------------
ALTER TABLE
    `spot_nearby`
ADD
    CONSTRAINT `spot_nearby_pky` PRIMARY KEY (`spot_id`, `spot_nearby_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_CANCEL_POLICY
--   *** ------------------------------------
ALTER TABLE
    `room_plan_cancel_policy`
ADD
    CONSTRAINT `room_plan_cancel_policy_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_TYPE_STOCK
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn_type_stock`
ADD
    CONSTRAINT `billpayed_ptn_type_stock_pky` PRIMARY KEY (
        `billpay_ym`,
        `site_cd`,
        `stock_type`,
        `stock_rate`
    );

--   *** ------------------------------------
--  *** Table FEATURE_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `feature_detail`
ADD
    CONSTRAINT `feature_detail_pky` PRIMARY KEY (`feature_id`, `feature_detail_id`);

--  ALTER TABLE `feature_detail` MODIFY (`feature_group_id` NOT NULL ENABLE);
ALTER TABLE
    `feature_detail`
MODIFY
    `feature_group_id` varchar(3) BINARY NOT NULL;

--  ALTER TABLE `feature_detail` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `feature_detail`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table ROOM_COUNT_INITIAL
--   *** ------------------------------------
ALTER TABLE
    `room_count_initial`
ADD
    CONSTRAINT `room_count_initial_pky` PRIMARY KEY (`hotel_cd`, `room_cd`);

--   *** ------------------------------------
--  *** Table TEMP_YAHOO_POINT_BOOK
--   *** ------------------------------------
ALTER TABLE
    `temp_yahoo_point_book`
ADD
    CONSTRAINT `temp_yahoo_point_book_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_BASE
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ranking_base`
ADD
    CONSTRAINT `room_plan_ranking_base_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_JR
--   *** ------------------------------------
ALTER TABLE
    `reserve_jr`
ADD
    CONSTRAINT `reserve_jr_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MAST_AREA_NEARBY
--   *** ------------------------------------
ALTER TABLE
    `mast_area_nearby`
ADD
    CONSTRAINT `mast_area_nearby_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table YAHOO_POINT_PLUS_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `yahoo_point_plus_hotel`
ADD
    CONSTRAINT `yahoo_point_plus_hotel_pky` PRIMARY KEY (`point_plus_id`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table PLAN_AKF_RELATION
--   *** ------------------------------------
ALTER TABLE
    `plan_akf_relation`
ADD
    CONSTRAINT `plan_akf_relation_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table SERVICE_VOTE_CHOICES
--   *** ------------------------------------
ALTER TABLE
    `service_vote_choices`
ADD
    CONSTRAINT `service_vote_choices_pky` PRIMARY KEY (`vote_cd`, `choice_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_ELEMENT_REMOVED
--   *** ------------------------------------
ALTER TABLE
    `hotel_element_removed`
ADD
    CONSTRAINT `hotel_element_removed_pky` PRIMARY KEY (`hotel_cd`, `table_name`);

--   *** ------------------------------------
--  *** Table PARTNER_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `partner_account`
ADD
    CONSTRAINT `partner_account_pky` PRIMARY KEY (`partner_cd`, `account_type`);

--   *** ------------------------------------
--  *** Table SUBMIT_FORM_CHECK
--   *** ------------------------------------
ALTER TABLE
    `submit_form_check`
ADD
    CONSTRAINT `submit_form_check_unq_01` UNIQUE (`check_cd`);

--   *** ------------------------------------
--  *** Table YAHOO_POINT_BOOK
--   *** ------------------------------------
ALTER TABLE
    `yahoo_point_book`
ADD
    CONSTRAINT `yahoo_point_book_pky` PRIMARY KEY (`yahoo_point_cd`);

--   *** ------------------------------------
--  *** Table ROOM_MEDIA2
--   *** ------------------------------------
ALTER TABLE
    `room_media2`
ADD
    CONSTRAINT `room_media2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `media_no`);

--   *** ------------------------------------
--  *** Table PARTNER_CUSTOMER_SITE
--   *** ------------------------------------
ALTER TABLE
    `partner_customer_site`
ADD
    CONSTRAINT `partner_customer_site_pky` PRIMARY KEY (`customer_id`, `site_cd`, `fee_type`);

--   *** ------------------------------------
--  *** Table PARTNER_DENY_KEYWORDS
--   *** ------------------------------------
ALTER TABLE
    `partner_deny_keywords`
ADD
    CONSTRAINT `partner_deny_keywords_pky` PRIMARY KEY (`partner_cd`, `keyword_group_id`);

--   *** ------------------------------------
--  *** Table ROOM_AKAFU
--   *** ------------------------------------
ALTER TABLE
    `room_akafu`
ADD
    CONSTRAINT `room_akafu_pky` PRIMARY KEY (`hotel_cd`, `roomtype_cd`);

--   *** ------------------------------------
--  *** Table NOTIFY_RIZAPULI
--   *** ------------------------------------
ALTER TABLE
    `notify_rizapuli`
ADD
    CONSTRAINT `notify_rizapuli_pky` PRIMARY KEY (`notify_rizapuli_id`);

--   *** ------------------------------------
--  *** Table CONTACT_SENDBOX
--   *** ------------------------------------
ALTER TABLE
    `contact_sendbox`
ADD
    CONSTRAINT `contact_sendbox_pky` PRIMARY KEY (`sendbox_cd`);

--   *** ------------------------------------
--  *** Table PARTNER
--   *** ------------------------------------
ALTER TABLE
    `partner`
ADD
    CONSTRAINT `partner_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_FEE_DRAFT
--   *** ------------------------------------
ALTER TABLE
    `billpay_fee_draft`
ADD
    CONSTRAINT `billpay_fee_draft_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_credit`
ADD
    CONSTRAINT `billpayed_hotel_credit_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_CALC
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ranking_calc`
ADD
    CONSTRAINT `room_plan_ranking_calc_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `wday`);

--   *** ------------------------------------
--  *** Table BILLPAYED_RSV_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_rsv_9xg`
ADD
    CONSTRAINT `billpayed_rsv_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_CREDIT_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_credit_9xg`
ADD
    CONSTRAINT `billpayed_hotel_credit_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `billpayed_credit`
ADD
    CONSTRAINT `billpayed_credit_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table WELFARE_DEST_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `welfare_dest_history`
ADD
    CONSTRAINT `welfare_dest_history_pky` PRIMARY KEY (`welfare_dest_history_id`);

--   *** ------------------------------------
--  *** Table MEMBER_SP
--   *** ------------------------------------
ALTER TABLE
    `member_sp`
ADD
    CONSTRAINT `member_sp_pky` PRIMARY KEY (`member_cd`);

--  ALTER TABLE `member_sp` MODIFY (`member_cd` NOT NULL ENABLE);
ALTER TABLE
    `member_sp`
MODIFY
    `member_cd` varchar(128) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table HOTEL_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `hotel_account`
ADD
    CONSTRAINT `hotel_account_pky` PRIMARY KEY (`hotel_cd`);

ALTER TABLE
    `hotel_account`
ADD
    CONSTRAINT `hotel_account_unq_01` UNIQUE (`account_id`);

--   *** ------------------------------------
--  *** Table ROOM_TYK
--   *** ------------------------------------
ALTER TABLE
    `room_tyk`
ADD
    CONSTRAINT `room_tyk_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table MEMBER_CARD
--   *** ------------------------------------
ALTER TABLE
    `member_card`
ADD
    CONSTRAINT `member_card_pky` PRIMARY KEY (`member_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `room_plan_grants`
ADD
    CONSTRAINT `room_plan_grants_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_RSV_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_rsv_9xg`
ADD
    CONSTRAINT `billpay_hotel_rsv_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table WEATHER_AREA_CITY
--   *** ------------------------------------
ALTER TABLE
    `weather_area_city`
ADD
    CONSTRAINT `weather_area_city_pky` PRIMARY KEY (`city_id`);

--   *** ------------------------------------
--  *** Table CONFIRM_HOTEL_PERSON
--   *** ------------------------------------
ALTER TABLE
    `confirm_hotel_person`
ADD
    CONSTRAINT `confirm_hotel_person_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_3
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_3`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_3_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table HOTEL_CAMP
--   *** ------------------------------------
ALTER TABLE
    `hotel_camp`
ADD
    CONSTRAINT `hotel_camp_pky` PRIMARY KEY (`camp_cd`);

--   *** ------------------------------------
--  *** Table ZAP_MEMBER_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `zap_member_yahoo`
ADD
    CONSTRAINT `zap_member_yahoo_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_SALES
--   *** ------------------------------------
ALTER TABLE
    `billpay_sales`
ADD
    CONSTRAINT `billpay_sales_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `site_cd`);

--   *** ------------------------------------
--  *** Table ROOM_MEDIA
--   *** ------------------------------------
ALTER TABLE
    `room_media`
ADD
    CONSTRAINT `room_media_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `media_no`);

--   *** ------------------------------------
--  *** Table SEND_MAIL_QUEUE
--   *** ------------------------------------
ALTER TABLE
    `send_mail_queue`
ADD
    CONSTRAINT `send_mail_queue_pky` PRIMARY KEY (`mail_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_grants_9xg`
ADD
    CONSTRAINT `billpay_hotel_grants_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_AKAFU_RELATION
--   *** ------------------------------------
ALTER TABLE
    `room_akafu_relation`
ADD
    CONSTRAINT `room_akafu_relation_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table ORICO_RESERVE
--   *** ------------------------------------
ALTER TABLE
    `orico_reserve`
ADD
    CONSTRAINT `orico_reserve_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table HOTEL_LINK
--   *** ------------------------------------
ALTER TABLE
    `hotel_link`
ADD
    CONSTRAINT `hotel_link_pky` PRIMARY KEY (`hotel_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_RSV
--   *** ------------------------------------
ALTER TABLE
    `checksheet_rsv`
ADD
    CONSTRAINT `checksheet_rsv_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table BILLPAY_FIX
--   *** ------------------------------------
ALTER TABLE
    `billpay_fix`
ADD
    CONSTRAINT `billpay_fix_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table MMS_SEND_EXTRACT_CONDITION
--   *** ------------------------------------
ALTER TABLE
    `mms_send_extract_condition`
ADD
    CONSTRAINT `mms_send_extract_condition_pky` PRIMARY KEY (`condition_id`);

--   *** ------------------------------------
--  *** Table PLAN_STATUS_POOL2
--   *** ------------------------------------
ALTER TABLE
    `plan_status_pool2`
ADD
    CONSTRAINT `plan_status_pool2_pky` PRIMARY KEY (
        `hotel_cd`,
        `plan_id`,
        `room_id`,
        `partner_group_id`
    );

--   *** ------------------------------------
--  *** Table BR_POINT_STAY_COUNT
--   *** ------------------------------------
ALTER TABLE
    `br_point_stay_count`
ADD
    CONSTRAINT `br_point_stay_count_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn`
ADD
    CONSTRAINT `billpayed_ptn_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `fee_type`);

--   *** ------------------------------------
--  *** Table ROOM_COUNT
--   *** ------------------------------------
ALTER TABLE
    `room_count`
ADD
    CONSTRAINT `room_count_chk_01` CHECK (
        ROOMS - RESERVE_ROOMS >= 0
        and ROOMS >= 0
    );

ALTER TABLE
    `room_count`
ADD
    CONSTRAINT `room_count_chk_02` CHECK (
        ROOMS >= 0
        and RESERVE_ROOMS >= 0
    );

ALTER TABLE
    `room_count`
ADD
    CONSTRAINT `room_count_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE
--   *** ------------------------------------
ALTER TABLE
    `mail_magazine`
ADD
    CONSTRAINT `mail_magazine_pky` PRIMARY KEY (`mail_magazine_id`);

--   *** ------------------------------------
--  *** Table ALERT_VACANT
--   *** ------------------------------------
ALTER TABLE
    `alert_vacant`
ADD
    CONSTRAINT `alert_vacant_pky` PRIMARY KEY (`person_cd`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_SSO
--   *** ------------------------------------
ALTER TABLE
    `member_sso`
ADD
    CONSTRAINT `member_sso_pky` PRIMARY KEY (`member_cd`);

ALTER TABLE
    `member_sso`
ADD
    CONSTRAINT `member_sso_unq_01` UNIQUE (`sso_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_PR_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpay_pr_grants`
ADD
    CONSTRAINT `billpay_pr_grants_unq_01` UNIQUE (
        `reserve_cd`,
        `date_ymd`,
        `site_cd`,
        `welfare_grants_id`
    );

--   *** ------------------------------------
--  *** Table PLAN_CANCEL_POLICY
--   *** ------------------------------------
ALTER TABLE
    `plan_cancel_policy`
ADD
    CONSTRAINT `plan_cancel_policy_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table TOP_ATTENTION
--   *** ------------------------------------
ALTER TABLE
    `top_attention`
ADD
    CONSTRAINT `top_attention_pky` PRIMARY KEY (`attention_id`);

--   *** ------------------------------------
--  *** Table HOTEL_YDP_FACTORING
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydp_factoring`
ADD
    CONSTRAINT `hotel_ydp_factoring_pky` PRIMARY KEY (`ydp_hotel_cd`);

--   *** ------------------------------------
--  *** Table PARTNER_YDP
--   *** ------------------------------------
ALTER TABLE
    `partner_ydp`
ADD
    CONSTRAINT `partner_ydp_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V3
--   *** ------------------------------------
ALTER TABLE
    `br_point_book_v3`
ADD
    CONSTRAINT `br_point_book_v3_pky` PRIMARY KEY (`br_point_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_EPKP
--   *** ------------------------------------
ALTER TABLE
    `member_epkp`
ADD
    CONSTRAINT `member_epkp_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_FREE_TRACE
--   *** ------------------------------------
ALTER TABLE
    `member_free_trace`
ADD
    CONSTRAINT `member_free_trace_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_SEARCH_MAIL
--   *** ------------------------------------
ALTER TABLE
    `member_search_mail`
ADD
    CONSTRAINT `member_search_mail_pky` PRIMARY KEY (`member_cd`, `entry_type`);

--   *** ------------------------------------
--  *** Table MAST_AREA
--   *** ------------------------------------
ALTER TABLE
    `mast_area`
ADD
    CONSTRAINT `mast_area_pky` PRIMARY KEY (`area_id`);

--   *** ------------------------------------
--  *** Table POINT_CAMP
--   *** ------------------------------------
ALTER TABLE
    `point_camp`
ADD
    CONSTRAINT `point_camp_pky` PRIMARY KEY (`point_camp_cd`);

--   *** ------------------------------------
--  *** Table FM_AFFILIATE
--   *** ------------------------------------
ALTER TABLE
    `fm_affiliate`
ADD
    CONSTRAINT `fm_affiliate_pky` PRIMARY KEY (`affiliate_id`);

--  ALTER TABLE `fm_affiliate` MODIFY (`affiliate_nm` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `affiliate_nm` varchar(100) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`passwd` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `passwd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`status` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `status` varchar(2) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`feeratio_rt` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `feeratio_rt` decimal(4, 1) NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`zip1` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `zip1` varchar(3) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`zip2` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `zip2` varchar(4) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`prefecture_cd` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `prefecture_cd` varchar(2) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`tel` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `tel` varchar(15) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`email` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `email` varchar(100) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`bank_nm` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `bank_nm` varchar(60) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`account_nm` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `account_nm` varchar(60) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`account_no` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `account_no` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`upd_id` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `upd_id` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`upd_dt` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `upd_dt` datetime NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`site_type` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `site_type` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`cid_fg` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `cid_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`member_type` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `member_type` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`pointuse_fg` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `pointuse_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`coloruse_fg` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `coloruse_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`sprate_fg` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `sprate_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`mypageshow_fg` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `mypageshow_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`reserve_cid_fg` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `reserve_cid_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`screen_type_cd` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `screen_type_cd` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fm_affiliate` MODIFY (`payment_cd` NOT NULL ENABLE);
ALTER TABLE
    `fm_affiliate`
MODIFY
    `payment_cd` varchar(1) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_grants_9xg`
ADD
    CONSTRAINT `reserve_dispose_grants_9xg_pky` PRIMARY KEY (`dispose_grants_id`);

--   *** ------------------------------------
--  *** Table MAST_STATION_COMPANY
--   *** ------------------------------------
ALTER TABLE
    `mast_station_company`
ADD
    CONSTRAINT `mast_station_company_pky` PRIMARY KEY (`company_cd`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_DELIVERY
--   *** ------------------------------------
ALTER TABLE
    `group_buying_delivery`
ADD
    CONSTRAINT `group_buying_delivery_pky` PRIMARY KEY (`order_id`);

--  ALTER TABLE `group_buying_delivery` MODIFY (`postal_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `postal_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`city` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `city` varchar(375) BINARY NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`person_family` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `person_family` varchar(51) BINARY NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`tel` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `tel` varchar(32) BINARY NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_delivery` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_delivery`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table BR_POINT_SHORT_TERM
--   *** ------------------------------------
ALTER TABLE
    `br_point_short_term`
ADD
    CONSTRAINT `br_point_short_term_pky` PRIMARY KEY (`short_term_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_RSV
--   *** ------------------------------------
ALTER TABLE
    `billpayed_rsv`
ADD
    CONSTRAINT `billpayed_rsv_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table DISPOSE_VOUCHER
--   *** ------------------------------------
ALTER TABLE
    `dispose_voucher`
ADD
    CONSTRAINT `dispose_voucher_pky` PRIMARY KEY (
        `hotel_cd`,
        `operation_ymd`,
        `reserve_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table EXTEND_SWITCH_PLAN2
--   *** ------------------------------------
ALTER TABLE
    `extend_switch_plan2`
ADD
    CONSTRAINT `extend_switch_plan2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table PARTNER_CUSTOMER
--   *** ------------------------------------
ALTER TABLE
    `partner_customer`
ADD
    CONSTRAINT `partner_customer_pky` PRIMARY KEY (`customer_id`);

--   *** ------------------------------------
--  *** Table HOTEL_RECOMMEND_RESULT
--   *** ------------------------------------
ALTER TABLE
    `hotel_recommend_result`
ADD
    CONSTRAINT `hotel_recommend_result_pky` PRIMARY KEY (`hotel_cd`, `recommend_id`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_01
--   *** ------------------------------------
ALTER TABLE
    `log_security_01`
ADD
    CONSTRAINT `log_security_01_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table EPARK_STATE_TOKEN
--   *** ------------------------------------
ALTER TABLE
    `epark_state_token`
ADD
    CONSTRAINT `epark_state_token_pky` PRIMARY KEY (`state_token_key`);

--   *** ------------------------------------
--  *** Table POINT
--   *** ------------------------------------
ALTER TABLE
    `point`
ADD
    CONSTRAINT `point_pky` PRIMARY KEY (`point_id`);

--   *** ------------------------------------
--  *** Table EPARK_ACCESS_TOKEN
--   *** ------------------------------------
ALTER TABLE
    `epark_access_token`
ADD
    CONSTRAINT `epark_access_token_pky` PRIMARY KEY (`epark_id`);

--   *** ------------------------------------
--  *** Table RECORD_MOBILE_VARIOUS
--   *** ------------------------------------
ALTER TABLE
    `record_mobile_various`
ADD
    CONSTRAINT `record_mobile_various_pky` PRIMARY KEY (`date_ymd`);

--   *** ------------------------------------
--  *** Table HOTEL_PAY_PER_CALL
--   *** ------------------------------------
ALTER TABLE
    `hotel_pay_per_call`
ADD
    CONSTRAINT `hotel_pay_per_call_pky` PRIMARY KEY (`hotel_cd`, `partner_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_yahoo`
ADD
    CONSTRAINT `reserve_dispose_yahoo_pky` PRIMARY KEY (`dispose_yahoo_id`);

--   *** ------------------------------------
--  *** Table HOTEL_NOTIFY
--   *** ------------------------------------
ALTER TABLE
    `hotel_notify`
ADD
    CONSTRAINT `hotel_notify_pky` PRIMARY KEY (`hotel_cd`);

--  ALTER TABLE `hotel_notify` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `hotel_notify`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table PARTNER_CLOCK
--   *** ------------------------------------
ALTER TABLE
    `partner_clock`
ADD
    CONSTRAINT `partner_clock_pky` PRIMARY KEY (`partner_cd`, `table_name`);

--   *** ------------------------------------
--  *** Table MEMBER_COUPON
--   *** ------------------------------------
ALTER TABLE
    `member_coupon`
ADD
    CONSTRAINT `member_coupon_pky` PRIMARY KEY (`member_coupon_id`);

--   *** ------------------------------------
--  *** Table RESERVE_EXTENSION
--   *** ------------------------------------
ALTER TABLE
    `reserve_extension`
ADD
    CONSTRAINT `reserve_extension_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_STATION_WK
--   *** ------------------------------------
ALTER TABLE
    `hotel_station_wk`
ADD
    CONSTRAINT `hotel_station_wk_pky` PRIMARY KEY (`hotel_cd`, `station_id`, `traffic_way`);

--   *** ------------------------------------
--  *** Table BROADCAST_MESSAGES_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `broadcast_messages_hotel`
ADD
    CONSTRAINT `broadcast_messages_hotel_pky` PRIMARY KEY (`broadcast_messages_hotel_id`);

--   *** ------------------------------------
--  *** Table ORICO
--   *** ------------------------------------
ALTER TABLE
    `orico`
ADD
    CONSTRAINT `orico_pky` PRIMARY KEY (`order_id`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_FEE_BASE
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_fee_base`
ADD
    CONSTRAINT `checksheet_hotel_fee_base_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_NTA_RELATION
--   *** ------------------------------------
ALTER TABLE
    `room_nta_relation`
ADD
    CONSTRAINT `room_nta_relation_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table SSO_COMPARE
--   *** ------------------------------------
ALTER TABLE
    `sso_compare`
ADD
    CONSTRAINT `sso_compare_pky` PRIMARY KEY (`account_type`, `account_key`);

ALTER TABLE
    `sso_compare`
ADD
    CONSTRAINT `sso_compare_unq_01` UNIQUE (`compare_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_SYSTEM_VERSION
--   *** ------------------------------------
ALTER TABLE
    `hotel_system_version`
ADD
    CONSTRAINT `hotel_system_version_pky` PRIMARY KEY (`hotel_cd`, `system_type`);

--   *** ------------------------------------
--  *** Table ROOM_NETWORK
--   *** ------------------------------------
ALTER TABLE
    `room_network`
ADD
    CONSTRAINT `room_network_pky` PRIMARY KEY (`hotel_cd`, `room_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_CHILD
--   *** ------------------------------------
ALTER TABLE
    `room_plan_child`
ADD
    CONSTRAINT `room_plan_child_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table PARTNER_LINKS
--   *** ------------------------------------
ALTER TABLE
    `partner_links`
ADD
    CONSTRAINT `partner_links_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_JR_ENTRY_STATUS
--   *** ------------------------------------
ALTER TABLE
    `hotel_jr_entry_status`
ADD
    CONSTRAINT `hotel_jr_entry_status_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM
--   *** ------------------------------------
ALTER TABLE
    `room`
ADD
    CONSTRAINT `room_pky` PRIMARY KEY (`hotel_cd`, `room_cd`);

--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4_LOG
--   *** ------------------------------------
ALTER TABLE
    `br_point_book_v4_log`
ADD
    CONSTRAINT `br_point_book_v4_log_pky` PRIMARY KEY (`br_point_cd`);

--   *** ------------------------------------
--  *** Table MAST_AREA_STATION
--   *** ------------------------------------
ALTER TABLE
    `mast_area_station`
ADD
    CONSTRAINT `mast_area_station_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table BR_POINT_BOOK
--   *** ------------------------------------
ALTER TABLE
    `br_point_book`
ADD
    CONSTRAINT `br_point_book_pky` PRIMARY KEY (`br_point_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_MSC
--   *** ------------------------------------
ALTER TABLE
    `hotel_msc`
ADD
    CONSTRAINT `hotel_msc_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table MIGRATION
--   *** ------------------------------------
ALTER TABLE
    `migration`
ADD
    CONSTRAINT `migration_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_FACILITY
--   *** ------------------------------------
ALTER TABLE
    `hotel_facility`
ADD
    CONSTRAINT `hotel_facility_pky` PRIMARY KEY (`hotel_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table TEMP_KEI_MONTH_DISCOUNT
--   *** ------------------------------------
ALTER TABLE
    `temp_kei_month_discount`
ADD
    CONSTRAINT `temp_kei_month_discount_pky` PRIMARY KEY (`hotel_cd`, `date_ym`, `discount_cd`);

--   *** ------------------------------------
--  *** Table PLAN_CANCEL_RATE
--   *** ------------------------------------
ALTER TABLE
    `plan_cancel_rate`
ADD
    CONSTRAINT `plan_cancel_rate_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `days`);

--   *** ------------------------------------
--  *** Table HOTEL_STATUS_JR
--   *** ------------------------------------
ALTER TABLE
    `hotel_status_jr`
ADD
    CONSTRAINT `hotel_status_jr_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table NOTIFY_PAGE
--   *** ------------------------------------
ALTER TABLE
    `notify_page`
ADD
    CONSTRAINT `notify_page_pky` PRIMARY KEY (`notify_cd`);

--   *** ------------------------------------
--  *** Table ROOM_SPEC
--   *** ------------------------------------
ALTER TABLE
    `room_spec`
ADD
    CONSTRAINT `room_spec_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table HOTEL_YDK
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydk`
ADD
    CONSTRAINT `hotel_ydk_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_CSTMRSITE
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_cstmrsite`
ADD
    CONSTRAINT `billpay_ptn_cstmrsite_pky` PRIMARY KEY (
        `billpay_ym`,
        `customer_id`,
        `site_cd`,
        `fee_type`
    );

--   *** ------------------------------------
--  *** Table GROUP_BUYING_COUPON
--   *** ------------------------------------
ALTER TABLE
    `group_buying_coupon`
ADD
    CONSTRAINT `group_buying_coupon_pky` PRIMARY KEY (`order_id`, `coupon_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_RSV
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_rsv`
ADD
    CONSTRAINT `billpay_hotel_rsv_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_grants`
ADD
    CONSTRAINT `billpayed_hotel_grants_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel`
ADD
    CONSTRAINT `billpayed_hotel_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table REPORT_GENDER_AGE
--   *** ------------------------------------
ALTER TABLE
    `report_gender_age`
ADD
    CONSTRAINT `report_gender_age_pky` PRIMARY KEY (`hotel_cd`, `date_ymd`, `age`, `gender`);

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_RSV_9XG
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_rsv_9xg`
ADD
    CONSTRAINT `reserve_dispose_rsv_9xg_pky` PRIMARY KEY (`dispose_rsv_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_NEW
--   *** ------------------------------------
ALTER TABLE
    `room_plan_new`
ADD
    CONSTRAINT `room_plan_new_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_INITIAL2
--   *** ------------------------------------
--  ALTER TABLE `room_charge_initial2` ADD CONSTRAINT `room_charge_initial2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `partner_group_id`)
;

--   *** ------------------------------------
--  *** Table HOTEL_GOTO_REGIST
--   *** ------------------------------------
ALTER TABLE
    `hotel_goto_regist`
ADD
    CONSTRAINT `hotel_goto_regist_pky` PRIMARY KEY (`hotel_cd`);

--  ALTER TABLE `hotel_goto_regist` MODIFY (`regist_status` NOT NULL ENABLE);
ALTER TABLE
    `hotel_goto_regist`
MODIFY
    `regist_status` tinyint NOT NULL;

--   *** ------------------------------------
--  *** Table ZAP_MSC
--   *** ------------------------------------
ALTER TABLE
    `zap_msc`
ADD
    CONSTRAINT `zap_msc_pky` PRIMARY KEY (`random_cd`);

--   *** ------------------------------------
--  *** Table MAST_AREA_LANDMARK
--   *** ------------------------------------
ALTER TABLE
    `mast_area_landmark`
ADD
    CONSTRAINT `mast_area_landmark_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table HOTEL_POWERDOWN_S
--   *** ------------------------------------
ALTER TABLE
    `hotel_powerdown_s`
ADD
    CONSTRAINT `hotel_powerdown_s_pky` PRIMARY KEY (`hotel_cd`, `powerdown_seq`);

--   *** ------------------------------------
--  *** Table ORDER_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `order_grants`
ADD
    CONSTRAINT `order_grants_pky` PRIMARY KEY (`order_cd`, `welfare_grants_history_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_RSV_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_rsv_9xg`
ADD
    CONSTRAINT `billpayed_hotel_rsv_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_FEE
--   *** ------------------------------------
ALTER TABLE
    `checksheet_fee`
ADD
    CONSTRAINT `checksheet_fee_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table MEDIA_REMOVED
--   *** ------------------------------------
ALTER TABLE
    `media_removed`
ADD
    CONSTRAINT `media_removed_pky` PRIMARY KEY (`media_removed_id`);

--   *** ------------------------------------
--  *** Table ADDITIONAL_ZENGIN
--   *** ------------------------------------
ALTER TABLE
    `additional_zengin`
ADD
    CONSTRAINT `additional_zengin_pky` PRIMARY KEY (`zengin_ym`, `branch_id`);

--   *** ------------------------------------
--  *** Table WELFARE_OP_HISTORY
--   *** ------------------------------------
--  ALTER TABLE `welfare_op_history` MODIFY (`welfare_match_id` NOT NULL ENABLE);
ALTER TABLE
    `welfare_op_history`
MODIFY
    `welfare_match_id` bigint NOT NULL;

--  ALTER TABLE `welfare_op_history` MODIFY (`welfare_match_history_id` NOT NULL ENABLE);
ALTER TABLE
    `welfare_op_history`
MODIFY
    `welfare_match_history_id` bigint NOT NULL;

--  ALTER TABLE `welfare_op_history` MODIFY (`welfare_grants_id` NOT NULL ENABLE);
ALTER TABLE
    `welfare_op_history`
MODIFY
    `welfare_grants_id` bigint NOT NULL;

--  ALTER TABLE `welfare_op_history` MODIFY (`welfare_grants_history_id` NOT NULL ENABLE);
ALTER TABLE
    `welfare_op_history`
MODIFY
    `welfare_grants_history_id` bigint NOT NULL;

ALTER TABLE
    `welfare_op_history`
ADD
    CONSTRAINT `welfare_op_history_pky` PRIMARY KEY (`welfare_op_history_id`);

ALTER TABLE
    `welfare_op_history`
ADD
    CONSTRAINT `welfare_op_history_unq_01` UNIQUE (
        `welfare_match_history_id`,
        `welfare_grants_id`,
        `welfare_grants_history_id`
    );

--   *** ------------------------------------
--  *** Table AKF_STOCK_FRAME_NO
--   *** ------------------------------------
ALTER TABLE
    `akf_stock_frame_no`
ADD
    CONSTRAINT `akf_stock_frame_no_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table CUSTOMER
--   *** ------------------------------------
ALTER TABLE
    `customer`
ADD
    CONSTRAINT `customer_pky` PRIMARY KEY (`customer_id`);

--   *** ------------------------------------
--  *** Table NTA_STAFF
--   *** ------------------------------------
ALTER TABLE
    `nta_staff`
ADD
    CONSTRAINT `nta_staff_pky` PRIMARY KEY (`nta_staff_id`);

--   *** ------------------------------------
--  *** Table PARTNER_CLOUT
--   *** ------------------------------------
ALTER TABLE
    `partner_clout`
ADD
    CONSTRAINT `partner_clout_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_CREDIT_FLUCTUATE
--   *** ------------------------------------
ALTER TABLE
    `reserve_credit_fluctuate`
ADD
    CONSTRAINT `reserve_credit_fluctuate_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table VOICE_REVIEW
--   *** ------------------------------------
ALTER TABLE
    `voice_review`
ADD
    CONSTRAINT `voice_review_pky` PRIMARY KEY (`member_cd`, `reserve_cd`, `review_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_YAHOO_POINT
--   *** ------------------------------------
ALTER TABLE
    `room_plan_yahoo_point`
ADD
    CONSTRAINT `room_plan_yahoo_point_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_CREDIT_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_credit_9xg`
ADD
    CONSTRAINT `billpayed_credit_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table STAFF
--   *** ------------------------------------
ALTER TABLE
    `staff`
ADD
    CONSTRAINT `staff_pky` PRIMARY KEY (`staff_id`);

--   *** ------------------------------------
--  *** Table MAST_WARD
--   *** ------------------------------------
ALTER TABLE
    `mast_ward`
ADD
    CONSTRAINT `mast_ward_pky` PRIMARY KEY (`ward_id`);

--   *** ------------------------------------
--  *** Table CARD_PAYMENT_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `card_payment_credit`
ADD
    CONSTRAINT `card_payment_credit_pky` PRIMARY KEY (`card_payment_id`);

ALTER TABLE
    `card_payment_credit`
ADD
    CONSTRAINT `card_payment_credit_unq_01` UNIQUE (
        `payment_system`,
        `demand_dtm`,
        `reserve_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table ROOM_NETWORK2
--   *** ------------------------------------
ALTER TABLE
    `room_network2`
ADD
    CONSTRAINT `room_network2_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table RESERVE_CANCEL_POLICY
--   *** ------------------------------------
ALTER TABLE
    `reserve_cancel_policy`
ADD
    CONSTRAINT `reserve_cancel_policy_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table EXTEND_SETTING
--   *** ------------------------------------
ALTER TABLE
    `extend_setting`
ADD
    CONSTRAINT `extend_setting_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_COUNT_INITIAL2
--   *** ------------------------------------
ALTER TABLE
    `room_count_initial2`
ADD
    CONSTRAINT `room_count_initial2_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_HR_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpay_hr_grants`
ADD
    CONSTRAINT `billpay_hr_grants_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `welfare_grants_id`);

--   *** ------------------------------------
--  *** Table MEMBER_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `member_yahoo`
ADD
    CONSTRAINT `member_yahoo_pky` PRIMARY KEY (`transaction_cd`, `member_cd`);

--   *** ------------------------------------
--  *** Table MAST_LANDMARK_CATEGORY_2ND
--   *** ------------------------------------
ALTER TABLE
    `mast_landmark_category_2nd`
ADD
    CONSTRAINT `mast_landmark_category_2nd_pky` PRIMARY KEY (`category_2nd_id`);

--   *** ------------------------------------
--  *** Table ZAP_PLAN_POINT
--   *** ------------------------------------
ALTER TABLE
    `zap_plan_point`
ADD
    CONSTRAINT `zap_plan_point_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table MEMBER_STAFF_NOTE
--   *** ------------------------------------
ALTER TABLE
    `member_staff_note`
ADD
    CONSTRAINT `member_staff_note_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_BATH_TAX
--   *** ------------------------------------
ALTER TABLE
    `reserve_bath_tax`
ADD
    CONSTRAINT `reserve_bath_tax_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table RECORD_HOTEL_RESERVE
--   *** ------------------------------------
ALTER TABLE
    `record_hotel_reserve`
ADD
    CONSTRAINT `record_hotel_reserve_pky` PRIMARY KEY (`date_ymd`, `hotel_cd`, `capacity`);

--   *** ------------------------------------
--  *** Table ORICO_RETRY
--   *** ------------------------------------
ALTER TABLE
    `orico_retry`
ADD
    CONSTRAINT `orico_retry_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `branch_no`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING
--   *** ------------------------------------
ALTER TABLE
    `group_buying`
ADD
    CONSTRAINT `group_buying_pky` PRIMARY KEY (`deal_id`);

--  ALTER TABLE `group_buying` MODIFY (`coupon_goal` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `coupon_goal` int NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`coupon_count` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `coupon_count` int NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`status` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `status` tinyint NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`supplier_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `supplier_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `group_buying` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table RECORD_HOTEL_PLAN_COUNT
--   *** ------------------------------------
ALTER TABLE
    `record_hotel_plan_count`
ADD
    CONSTRAINT `record_hotel_plan_count_pky` PRIMARY KEY (`record_dtm`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_LEGACY
--   *** ------------------------------------
ALTER TABLE
    `room_legacy`
ADD
    CONSTRAINT `room_legacy_pky` PRIMARY KEY (`hotel_cd`, `room_cd`);

--   *** ------------------------------------
--  *** Table MMS_SEND_EXTRACT_RERATION
--   *** ------------------------------------
ALTER TABLE
    `mms_send_extract_reration`
ADD
    CONSTRAINT `mms_send_extract_reration_pky` PRIMARY KEY (`mail_magazine_simple_id`);

--   *** ------------------------------------
--  *** Table LOG_POWER
--   *** ------------------------------------
ALTER TABLE
    `log_power`
ADD
    CONSTRAINT `log_power_pky` PRIMARY KEY (`reserve_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table ROOM_COUNT_AKAFU
--   *** ------------------------------------
ALTER TABLE
    `room_count_akafu`
ADD
    CONSTRAINT `room_count_akafu_pky` PRIMARY KEY (`hotel_cd`, `roomtype_cd`, `use_dt`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_BOOK
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_book`
ADD
    CONSTRAINT `billpay_ptn_book_pky` PRIMARY KEY (`billpay_ptn_cd`, `billpay_branch_no`);

--   *** ------------------------------------
--  *** Table MEDIA
--   *** ------------------------------------
ALTER TABLE
    `media`
ADD
    CONSTRAINT `media_pky` PRIMARY KEY (`hotel_cd`, `media_no`);

--   *** ------------------------------------
--  *** Table HOTEL_CONTROL_NOTE
--   *** ------------------------------------
ALTER TABLE
    `hotel_control_note`
ADD
    CONSTRAINT `hotel_control_note_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_YDP_RECEIVE
--   *** ------------------------------------
ALTER TABLE
    `reserve_ydp_receive`
ADD
    CONSTRAINT `reserve_ydp_receive_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_SIMPLE_COND
--   *** ------------------------------------
ALTER TABLE
    `mail_magazine_simple_cond`
ADD
    CONSTRAINT `mail_magazine_simple_cond_pky` PRIMARY KEY (`mail_magazine_simple_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_CREDIT_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_credit_9xg`
ADD
    CONSTRAINT `billpay_hotel_credit_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_04
--   *** ------------------------------------
ALTER TABLE
    `log_security_04`
ADD
    CONSTRAINT `log_security_04_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_SIMPLE
--   *** ------------------------------------
ALTER TABLE
    `mail_magazine_simple`
ADD
    CONSTRAINT `mail_magazine_simple_pky` PRIMARY KEY (`mail_magazine_simple_id`);

--   *** ------------------------------------
--  *** Table EXTEND_TASK
--   *** ------------------------------------
ALTER TABLE
    `extend_task`
ADD
    CONSTRAINT `extend_task_pky` PRIMARY KEY (`hotel_cd`, `target_ym`, `type`);

--   *** ------------------------------------
--  *** Table OTA_HOTEL_RELATION
--   *** ------------------------------------
ALTER TABLE
    `ota_hotel_relation`
ADD
    CONSTRAINT `ota_hotel_relation_pky` PRIMARY KEY (`ota_hotel_relation_id`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_FIX_9XG
--   *** ------------------------------------
ALTER TABLE
    `checksheet_fix_9xg`
ADD
    CONSTRAINT `checksheet_fix_9xg_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table PARTNER_BOOK_CUSTOMER_DTL
--   *** ------------------------------------
ALTER TABLE
    `partner_book_customer_dtl`
ADD
    CONSTRAINT `partner_book_customer_dtl_pky` PRIMARY KEY (`site_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_MAIL
--   *** ------------------------------------
ALTER TABLE
    `member_mail`
ADD
    CONSTRAINT `member_mail_pky` PRIMARY KEY (`member_mail_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_FORCED_STOP_MAIL
--   *** ------------------------------------
ALTER TABLE
    `member_forced_stop_mail`
ADD
    CONSTRAINT `member_forced_stop_mail_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_YDP_BR
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydp_br`
ADD
    CONSTRAINT `hotel_ydp_br_pky` PRIMARY KEY (`hotel_cd`);

--  ALTER TABLE `hotel_ydp_br` MODIFY (`hotel_cd_ydp` NOT NULL ENABLE);
ALTER TABLE
    `hotel_ydp_br`
MODIFY
    `hotel_cd_ydp` varchar(10) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_TYPE_STOCK
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_type_stock`
ADD
    CONSTRAINT `billpay_ptn_type_stock_pky` PRIMARY KEY (
        `billpay_ym`,
        `site_cd`,
        `stock_type`,
        `stock_rate`
    );

--   *** ------------------------------------
--  *** Table BILLPAYED_HR_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hr_grants_9xg`
ADD
    CONSTRAINT `billpayed_hr_grants_9xg_pky` PRIMARY KEY (
        `reserve_cd`,
        `date_ymd`,
        `operation_ymd`,
        `welfare_grants_id`
    );

--   *** ------------------------------------
--  *** Table GROUP_BUYING_AUTHORI
--   *** ------------------------------------
ALTER TABLE
    `group_buying_authori`
ADD
    CONSTRAINT `group_buying_authori_pky` PRIMARY KEY (`order_id`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_07
--   *** ------------------------------------
ALTER TABLE
    `log_security_07`
ADD
    CONSTRAINT `log_security_07_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table POINT_CAMP_WINNING_RSV
--   *** ------------------------------------
ALTER TABLE
    `point_camp_winning_rsv`
ADD
    CONSTRAINT `point_camp_winning_rsv_pky` PRIMARY KEY (`reserve_cd`, `point_camp_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HR_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hr_grants`
ADD
    CONSTRAINT `billpayed_hr_grants_pky` PRIMARY KEY (
        `reserve_cd`,
        `date_ymd`,
        `operation_ymd`,
        `welfare_grants_id`
    );

--   *** ------------------------------------
--  *** Table CHECKSHEET_BOOK
--   *** ------------------------------------
ALTER TABLE
    `checksheet_book`
ADD
    CONSTRAINT `checksheet_book_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_FEE_BASE2
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_fee_base2`
ADD
    CONSTRAINT `checksheet_hotel_fee_base2_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table LANDMARK_CATEGORY_MATCH
--   *** ------------------------------------
ALTER TABLE
    `landmark_category_match`
ADD
    CONSTRAINT `landmark_category_match_pky` PRIMARY KEY (`landmark_id`, `category_2nd_id`);

--   *** ------------------------------------
--  *** Table RANKING_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `ranking_hotel`
ADD
    CONSTRAINT `ranking_hotel_pky` PRIMARY KEY (`hotel_cd`, `ranking_unit`);

--   *** ------------------------------------
--  *** Table YAHOO_POINT_PLUS_PLAN
--   *** ------------------------------------
ALTER TABLE
    `yahoo_point_plus_plan`
ADD
    CONSTRAINT `yahoo_point_plus_plan_pky` PRIMARY KEY (`point_plus_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table RESERVE_CHARGE_DISCOUNT
--   *** ------------------------------------
ALTER TABLE
    `reserve_charge_discount`
ADD
    CONSTRAINT `reserve_charge_discount_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `discount_factor_id`);

--   *** ------------------------------------
--  *** Table ROOM_SPEC2
--   *** ------------------------------------
ALTER TABLE
    `room_spec2`
ADD
    CONSTRAINT `room_spec2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `element_id`);

--   *** ------------------------------------
--  *** Table POINT_CAMP_ORDER
--   *** ------------------------------------
ALTER TABLE
    `point_camp_order`
ADD
    CONSTRAINT `point_camp_order_pky` PRIMARY KEY (`point_camp_cd`, `member_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE2
--   *** ------------------------------------
-- 存在しない  ALTER TABLE `room_charge2` ADD CONSTRAINT `room_charge2_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `partner_group_id`, `date_ymd`)  ;
--   *** ------------------------------------
--  *** Table RECEIPT_POWER
--   *** ------------------------------------
ALTER TABLE
    `receipt_power`
ADD
    CONSTRAINT `receipt_power_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN_GOTO
--   *** ------------------------------------
ALTER TABLE
    `hotel_camp_plan_goto`
ADD
    CONSTRAINT `hotel_camp_plan_goto_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `camp_cd`);

--  ALTER TABLE `hotel_camp_plan_goto` MODIFY (`display_status` NOT NULL ENABLE);
ALTER TABLE
    `hotel_camp_plan_goto`
MODIFY
    `display_status` tinyint NOT NULL;

--   *** ------------------------------------
--  *** Table BILLPAYED_YAHOO_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_yahoo_9xg`
ADD
    CONSTRAINT `billpayed_yahoo_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table RESERVE_CANCEL_RATE
--   *** ------------------------------------
ALTER TABLE
    `reserve_cancel_rate`
ADD
    CONSTRAINT `reserve_cancel_rate_pky` PRIMARY KEY (`reserve_cd`, `days`);

--   *** ------------------------------------
--  *** Table OTA_PLAN_RELATION
--   *** ------------------------------------
ALTER TABLE
    `ota_plan_relation`
ADD
    CONSTRAINT `ota_plan_relation_pky` PRIMARY KEY (`ota_plan_relation_id`);

--   *** ------------------------------------
--  *** Table RECORD_HOTEL_VIEW
--   *** ------------------------------------
ALTER TABLE
    `record_hotel_view`
ADD
    CONSTRAINT `record_hotel_view_pky` PRIMARY KEY (`date_ymd`, `hotel_cd`, `page_type`);

--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4_DRAFT
--   *** ------------------------------------
ALTER TABLE
    `br_point_book_v4_draft`
ADD
    CONSTRAINT `br_point_book_v4_draft_pky` PRIMARY KEY (`br_point_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_9xg`
ADD
    CONSTRAINT `billpayed_hotel_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table MAST_STATIONS
--   *** ------------------------------------
ALTER TABLE
    `mast_stations`
ADD
    CONSTRAINT `mast_stations_pky` PRIMARY KEY (`station_id`);

--  ALTER TABLE `mast_stations` MODIFY (`station_id` NOT NULL ENABLE);
ALTER TABLE
    `mast_stations`
MODIFY
    `station_id` varchar(7) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table HOTEL_INSURANCE_WEATHER
--   *** ------------------------------------
ALTER TABLE
    `hotel_insurance_weather`
ADD
    CONSTRAINT `hotel_insurance_weather_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_SPOT
--   *** ------------------------------------
ALTER TABLE
    `hotel_spot`
ADD
    CONSTRAINT `hotel_spot_pky` PRIMARY KEY (`spot_id`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_GUEST
--   *** ------------------------------------
ALTER TABLE
    `reserve_guest`
ADD
    CONSTRAINT `reserve_guest_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_05
--   *** ------------------------------------
ALTER TABLE
    `log_security_05`
ADD
    CONSTRAINT `log_security_05_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING2
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ranking2`
ADD
    CONSTRAINT `room_plan_ranking2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `wday`);

--   *** ------------------------------------
--  *** Table RESERVE_RECEIPT
--   *** ------------------------------------
ALTER TABLE
    `reserve_receipt`
ADD
    CONSTRAINT `reserve_receipt_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MAST_REVIEW
--   *** ------------------------------------
ALTER TABLE
    `mast_review`
ADD
    CONSTRAINT `mast_review_pky` PRIMARY KEY (`review_id`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `group_buying_hotel`
ADD
    CONSTRAINT `group_buying_hotel_pky` PRIMARY KEY (`deal_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_REMOVED
--   *** ------------------------------------
ALTER TABLE
    `room_plan_removed`
ADD
    CONSTRAINT `room_plan_removed_pky` PRIMARY KEY (`room_plan_removed_id`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_CUSTOMER
--   *** ------------------------------------
ALTER TABLE
    `checksheet_customer`
ADD
    CONSTRAINT `checksheet_customer_pky` PRIMARY KEY (`checksheet_ym`, `customer_id`);

--   *** ------------------------------------
--  *** Table RESERVE_AKAFU
--   *** ------------------------------------
ALTER TABLE
    `reserve_akafu`
ADD
    CONSTRAINT `reserve_akafu_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table ROOM_JR
--   *** ------------------------------------
ALTER TABLE
    `room_jr`
ADD
    CONSTRAINT `room_jr_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table HOTEL_STATIONS_SURVEY
--   *** ------------------------------------
ALTER TABLE
    `hotel_stations_survey`
ADD
    CONSTRAINT `hotel_stations_survey_pky` PRIMARY KEY (`station_id`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_CREDITCARD
--   *** ------------------------------------
ALTER TABLE
    `member_creditcard`
ADD
    CONSTRAINT `member_creditcard_pky` PRIMARY KEY (`partner_cd`, `member_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table MAST_RECOMMEND
--   *** ------------------------------------
ALTER TABLE
    `mast_recommend`
ADD
    CONSTRAINT `mast_recommend_pky` PRIMARY KEY (`recommend_id`);

--   *** ------------------------------------
--  *** Table PARTNER_BOOK
--   *** ------------------------------------
ALTER TABLE
    `partner_book`
ADD
    CONSTRAINT `partner_book_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table PARTNER_BOOK_PARTNER
--   *** ------------------------------------
ALTER TABLE
    `partner_book_partner`
ADD
    CONSTRAINT `partner_book_partner_pky` PRIMARY KEY (`customer_id`, `site_cd`, `site_type`);

--   *** ------------------------------------
--  *** Table PARTNER_OAUTH2_MEMBER
--   *** ------------------------------------
ALTER TABLE
    `partner_oauth2_member`
ADD
    CONSTRAINT `partner_oauth2_member_pky` PRIMARY KEY (`client_id`, `member_cd`);

ALTER TABLE
    `partner_oauth2_member`
ADD
    CONSTRAINT `partner_oauth2_member_unq_01` UNIQUE (`authorization_cd`);

ALTER TABLE
    `partner_oauth2_member`
ADD
    CONSTRAINT `partner_oauth2_member_unq_02` UNIQUE (`access_token`);

ALTER TABLE
    `partner_oauth2_member`
ADD
    CONSTRAINT `partner_oauth2_member_unq_03` UNIQUE (`client_id`, `relation_member_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_RSV
--   *** ------------------------------------
ALTER TABLE
    `billpay_rsv`
ADD
    CONSTRAINT `billpay_rsv_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_YDP
--   *** ------------------------------------
ALTER TABLE
    `room_ydp`
ADD
    CONSTRAINT `room_ydp_pky` PRIMARY KEY (`hotel_cd`, `room_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_9xg`
ADD
    CONSTRAINT `billpay_hotel_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_grants_9xg`
ADD
    CONSTRAINT `checksheet_htl_grants_9xg_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ORICOPAYMENT
--   *** ------------------------------------
ALTER TABLE
    `oricopayment`
ADD
    CONSTRAINT `oricopayment_pky` PRIMARY KEY (`demand_dtm`, `shop_nm`, `order_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_CAMP
--   *** ------------------------------------
ALTER TABLE
    `reserve_camp`
ADD
    CONSTRAINT `reserve_camp_pky` PRIMARY KEY (`reserve_cd`, `camp_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_CAMP_GOTO
--   *** ------------------------------------
ALTER TABLE
    `hotel_camp_goto`
ADD
    CONSTRAINT `hotel_camp_goto_pky` PRIMARY KEY (`camp_cd`);

--  ALTER TABLE `hotel_camp_goto` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `hotel_camp_goto`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `hotel_camp_goto` MODIFY (`camp_nm` NOT NULL ENABLE);
ALTER TABLE
    `hotel_camp_goto`
MODIFY
    `camp_nm` varchar(96) BINARY NOT NULL;

--  ALTER TABLE `hotel_camp_goto` MODIFY (`display_status` NOT NULL ENABLE);
ALTER TABLE
    `hotel_camp_goto`
MODIFY
    `display_status` tinyint NOT NULL;

--   *** ------------------------------------
--  *** Table PARTNER_BOOK_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `partner_book_account`
ADD
    CONSTRAINT `partner_book_account_pky` PRIMARY KEY (`customer_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_RSV_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_rsv_9xg`
ADD
    CONSTRAINT `billpay_rsv_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table PLAN_PARTNER_GROUP
--   *** ------------------------------------
ALTER TABLE
    `plan_partner_group`
ADD
    CONSTRAINT `plan_partner_group_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `partner_group_id`);

--   *** ------------------------------------
--  *** Table MEMBER_JWEST
--   *** ------------------------------------
ALTER TABLE
    `member_jwest`
ADD
    CONSTRAINT `member_jwest_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table ROOM_YDP2
--   *** ------------------------------------
ALTER TABLE
    `room_ydp2`
ADD
    CONSTRAINT `room_ydp2_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table ZAP_MEMBER_EPARK
--   *** ------------------------------------
ALTER TABLE
    `zap_member_epark`
ADD
    CONSTRAINT `zap_member_epark_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table MAST_PLAN_ELEMENT_VALUE
--   *** ------------------------------------
ALTER TABLE
    `mast_plan_element_value`
ADD
    CONSTRAINT `mast_plan_element_value_pky` PRIMARY KEY (`element_id`, `element_value_id`);

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose`
ADD
    CONSTRAINT `reserve_dispose_pky` PRIMARY KEY (`dispose_id`);

--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4_WK
--   *** ------------------------------------
ALTER TABLE
    `br_point_book_v4_wk`
ADD
    CONSTRAINT `br_point_book_v4_wk_pky` PRIMARY KEY (`br_point_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_MATCH_REMOVED
--   *** ------------------------------------
ALTER TABLE
    `room_plan_match_removed`
ADD
    CONSTRAINT `room_plan_match_removed_pky` PRIMARY KEY (`room_plan_match_removed_id`);

--   *** ------------------------------------
--  *** Table LANDMARK_BASIC_INFO
--   *** ------------------------------------
ALTER TABLE
    `landmark_basic_info`
ADD
    CONSTRAINT `landmark_basic_info_pky` PRIMARY KEY (`landmark_id`, `item_id`);

--   *** ------------------------------------
--  *** Table MAST_TAX
--   *** ------------------------------------
ALTER TABLE
    `mast_tax`
ADD
    CONSTRAINT `mast_tax_pky` PRIMARY KEY (`tax_id`);

--   *** ------------------------------------
--  *** Table LOG_NOTIFY
--   *** ------------------------------------
ALTER TABLE
    `log_notify`
ADD
    CONSTRAINT `log_notify_pky` PRIMARY KEY (`request_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_TYPE_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn_type_grants`
ADD
    CONSTRAINT `billpayed_ptn_type_grants_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `welfare_grants_id`);

--   *** ------------------------------------
--  *** Table RESERVE_POWER_DEV
--   *** ------------------------------------
ALTER TABLE
    `reserve_power_dev`
ADD
    CONSTRAINT `reserve_power_dev_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table WELFARE_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `welfare_grants`
ADD
    CONSTRAINT `welfare_grants_pky` PRIMARY KEY (`welfare_grants_id`);

--   *** ------------------------------------
--  *** Table MAST_WARDZONE_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `mast_wardzone_detail`
ADD
    CONSTRAINT `mast_wardzone_detail_pky` PRIMARY KEY (`wardzone_id`, `ward_id`);

--   *** ------------------------------------
--  *** Table PARTNER_GROUP
--   *** ------------------------------------
ALTER TABLE
    `partner_group`
ADD
    CONSTRAINT `partner_group_pky` PRIMARY KEY (`partner_group_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_BOOK_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_book_9xg`
ADD
    CONSTRAINT `billpay_book_9xg_pky` PRIMARY KEY (`billpay_cd`, `billpay_branch_no`);

--   *** ------------------------------------
--  *** Table YDP_BASE_TIME
--   *** ------------------------------------
ALTER TABLE
    `ydp_base_time`
ADD
    CONSTRAINT `ydp_base_time_pky` PRIMARY KEY (`partner_cd`, `cooperation_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_credit`
ADD
    CONSTRAINT `checksheet_hotel_credit_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table CHARGE_REMIND
--   *** ------------------------------------
ALTER TABLE
    `charge_remind`
ADD
    CONSTRAINT `charge_remind_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_id`,
        `plan_id`,
        `partner_group_id`,
        `capacity`
    );

--   *** ------------------------------------
--  *** Table RESERVE_TRACE
--   *** ------------------------------------
ALTER TABLE
    `reserve_trace`
ADD
    CONSTRAINT `reserve_trace_pky` PRIMARY KEY (`transaction_cd`, `type`);

--   *** ------------------------------------
--  *** Table PARTNER_OPENID_MEMBER
--   *** ------------------------------------
ALTER TABLE
    `partner_openid_member`
ADD
    CONSTRAINT `partner_openid_member_pky` PRIMARY KEY (`client_id`, `member_cd`);

--   *** ------------------------------------
--  *** Table MAST_VR_HOTEL_CATEGORY
--   *** ------------------------------------
ALTER TABLE
    `mast_vr_hotel_category`
ADD
    CONSTRAINT `mast_vr_hotel_category_pky` PRIMARY KEY (`category_cd`);

--   *** ------------------------------------
--  *** Table GIFT_ORDER
--   *** ------------------------------------
ALTER TABLE
    `gift_order`
ADD
    CONSTRAINT `gift_order_pky` PRIMARY KEY (`gift_order_cd`);

--   *** ------------------------------------
--  *** Table NOTIFY_STAY
--   *** ------------------------------------
ALTER TABLE
    `notify_stay`
ADD
    CONSTRAINT `notify_stay_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_8
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_8`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_8_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table MAST_HOTEL_ELEMENT
--   *** ------------------------------------
ALTER TABLE
    `mast_hotel_element`
ADD
    CONSTRAINT `mast_hotel_element_pky` PRIMARY KEY (`element_id`);

--   *** ------------------------------------
--  *** Table WELFARE_MATCH
--   *** ------------------------------------
ALTER TABLE
    `welfare_match`
ADD
    CONSTRAINT `welfare_match_pky` PRIMARY KEY (`welfare_match_id`);

--   *** ------------------------------------
--  *** Table LOG_EXTEND
--   *** ------------------------------------
ALTER TABLE
    `log_extend`
ADD
    CONSTRAINT `log_extend_pky` PRIMARY KEY (`hotel_cd`, `after_ym`);

--   *** ------------------------------------
--  *** Table REPORT_LEAD_TIME
--   *** ------------------------------------
ALTER TABLE
    `report_lead_time`
ADD
    CONSTRAINT `report_lead_time_pky` PRIMARY KEY (`hotel_cd`, `date_ymd`, `lead_day`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_MEDIA
--   *** ------------------------------------
ALTER TABLE
    `room_plan_media`
ADD
    CONSTRAINT `room_plan_media_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `media_no`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_10
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_10`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_10_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table HOTEL_SERVICE
--   *** ------------------------------------
ALTER TABLE
    `hotel_service`
ADD
    CONSTRAINT `hotel_service_pky` PRIMARY KEY (`hotel_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table PLAN_MEDIA
--   *** ------------------------------------
ALTER TABLE
    `plan_media`
ADD
    CONSTRAINT `plan_media_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `media_no`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_MATCH
--   *** ------------------------------------
ALTER TABLE
    `room_plan_match`
ADD
    CONSTRAINT `room_plan_match_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table REPORT_PLAN2
--   *** ------------------------------------
ALTER TABLE
    `report_plan2`
ADD
    CONSTRAINT `report_plan2_pky` PRIMARY KEY (`hotel_cd`, `date_ymd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table MY_HOTEL_RESERVED
--   *** ------------------------------------
ALTER TABLE
    `my_hotel_reserved`
ADD
    CONSTRAINT `my_hotel_reserved_pky` PRIMARY KEY (`member_cd`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_EARLY
--   *** ------------------------------------
ALTER TABLE
    `room_charge_early`
ADD
    CONSTRAINT `room_charge_early_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `partner_group_id`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table PLAN_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `plan_grants`
ADD
    CONSTRAINT `plan_grants_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table BR_POINT_SERVICE
--   *** ------------------------------------
ALTER TABLE
    `br_point_service`
ADD
    CONSTRAINT `br_point_service_pky` PRIMARY KEY (`service_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_4
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_4`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_4_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table LOG_SECURITY_06
--   *** ------------------------------------
ALTER TABLE
    `log_security_06`
ADD
    CONSTRAINT `log_security_06_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_SUPERVISOR_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `hotel_supervisor_hotel`
ADD
    CONSTRAINT `hotel_supervisor_hotel_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table CHARGE_INITIAL
--   *** ------------------------------------
ALTER TABLE
    `charge_initial`
ADD
    CONSTRAINT `charge_initial_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_id`,
        `plan_id`,
        `partner_group_id`,
        `capacity`
    );

--   *** ------------------------------------
--  *** Table MEMBER_FREE_RELATION
--   *** ------------------------------------
ALTER TABLE
    `member_free_relation`
ADD
    CONSTRAINT `member_free_relation_pky` PRIMARY KEY (`member_cd`);

ALTER TABLE
    `member_free_relation`
ADD
    CONSTRAINT `member_free_relation_unq_01` UNIQUE (`member_free_cd`);

--   *** ------------------------------------
--  *** Table LOG_BOUNCED_MAIL
--   *** ------------------------------------
ALTER TABLE
    `log_bounced_mail`
ADD
    CONSTRAINT `log_bounced_mail_pky` PRIMARY KEY (`log_bounced_mail_id`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_CARD
--   *** ------------------------------------
ALTER TABLE
    `group_buying_card`
ADD
    CONSTRAINT `group_buying_card_pky` PRIMARY KEY (`order_id`);

--  ALTER TABLE `group_buying_card` MODIFY (`card_no` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_card`
MODIFY
    `card_no` varchar(32) BINARY NOT NULL;

--  ALTER TABLE `group_buying_card` MODIFY (`card_limit_ym` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_card`
MODIFY
    `card_limit_ym` datetime NOT NULL;

--  ALTER TABLE `group_buying_card` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_card`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_card` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_card`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `group_buying_card` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_card`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_card` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_card`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table REPORT_PREF
--   *** ------------------------------------
ALTER TABLE
    `report_pref`
ADD
    CONSTRAINT `report_pref_pky` PRIMARY KEY (`hotel_cd`, `date_ymd`, `pref_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn`
ADD
    CONSTRAINT `billpay_ptn_pky` PRIMARY KEY (`fee_type`, `site_cd`, `billpay_ym`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_LOWEST
--   *** ------------------------------------
ALTER TABLE
    `room_plan_lowest`
ADD
    CONSTRAINT `room_plan_lowest_pky` PRIMARY KEY (
        `hotel_cd`,
        `plan_cd`,
        `room_cd`,
        `charge_condition`
    );

--   *** ------------------------------------
--  *** Table LOG_AUTHORI
--   *** ------------------------------------
ALTER TABLE
    `log_authori`
ADD
    CONSTRAINT `log_authori_pky` PRIMARY KEY (`reserve_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_10
--   *** ------------------------------------
ALTER TABLE
    `log_security_10`
ADD
    CONSTRAINT `log_security_10_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_customer_9xg`
ADD
    CONSTRAINT `billpay_customer_9xg_pky` PRIMARY KEY (`billpay_ym`, `customer_id`);

--   *** ------------------------------------
--  *** Table MEDIA_ORG
--   *** ------------------------------------
ALTER TABLE
    `media_org`
ADD
    CONSTRAINT `media_org_pky` PRIMARY KEY (`hotel_cd`, `media_no`);

--   *** ------------------------------------
--  *** Table NTA_STAFF_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `nta_staff_account`
ADD
    CONSTRAINT `nta_staff_account_pky` PRIMARY KEY (`nta_staff_id`);

--   *** ------------------------------------
--  *** Table POINT_BONUS
--   *** ------------------------------------
ALTER TABLE
    `point_bonus`
ADD
    CONSTRAINT `point_bonus_pky` PRIMARY KEY (`point_bonus_id`);

--   *** ------------------------------------
--  *** Table PARTNER_GROUP_JOIN
--   *** ------------------------------------
ALTER TABLE
    `partner_group_join`
ADD
    CONSTRAINT `partner_group_join_pky` PRIMARY KEY (`partner_group_id`, `partner_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_MODIFY_RIZAPULI
--   *** ------------------------------------
ALTER TABLE
    `reserve_modify_rizapuli`
ADD
    CONSTRAINT `reserve_modify_rizapuli_pky` PRIMARY KEY (`reserve_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table MIGRATION_PLAN_TEMP
--   *** ------------------------------------
ALTER TABLE
    `migration_plan_temp`
ADD
    CONSTRAINT `migration_plan_temp_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table MEMBER_POINT
--   *** ------------------------------------
ALTER TABLE
    `member_point`
ADD
    CONSTRAINT `member_point_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_AUTHORI
--   *** ------------------------------------
ALTER TABLE
    `reserve_authori`
ADD
    CONSTRAINT `reserve_authori_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MIGRATION_MEDIA
--   *** ------------------------------------
ALTER TABLE
    `migration_media`
ADD
    CONSTRAINT `migration_media_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `media_no`);

--   *** ------------------------------------
--  *** Table LOG_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `log_credit`
ADD
    CONSTRAINT `log_credit_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `branch_no`);

--   *** ------------------------------------
--  *** Table LOG_ALERT_STOCK
--   *** ------------------------------------
ALTER TABLE
    `log_alert_stock`
ADD
    CONSTRAINT `log_alert_stock_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `date_ymd`, `reserve_cd`);

--   *** ------------------------------------
--  *** Table ROOM2
--   *** ------------------------------------
ALTER TABLE
    `room2`
ADD
    CONSTRAINT `room2_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table HOTEL_ACCEPT_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `hotel_accept_history`
ADD
    CONSTRAINT `hotel_accept_history_pky` PRIMARY KEY (`hotel_accept_id`);

--   *** ------------------------------------
--  *** Table PARTNER_POOL
--   *** ------------------------------------
ALTER TABLE
    `partner_pool`
ADD
    CONSTRAINT `partner_pool_pky` PRIMARY KEY (`partner_cd`);

--  ALTER TABLE `partner_pool` MODIFY (`password` NOT NULL ENABLE);
ALTER TABLE
    `partner_pool`
MODIFY
    `password` varchar(64) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table PARTNER_OPENID
--   *** ------------------------------------
ALTER TABLE
    `partner_openid`
ADD
    CONSTRAINT `partner_openid_pky` PRIMARY KEY (`client_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_SALES
--   *** ------------------------------------
ALTER TABLE
    `billpayed_sales`
ADD
    CONSTRAINT `billpayed_sales_pky` PRIMARY KEY (
        `reserve_cd`,
        `date_ymd`,
        `site_cd`,
        `operation_ymd`
    );

--   *** ------------------------------------
--  *** Table CHARGE_CONDITION
--   *** ------------------------------------
ALTER TABLE
    `charge_condition`
ADD
    CONSTRAINT `charge_condition_pky` PRIMARY KEY (
        `hotel_cd`,
        `plan_id`,
        `room_id`,
        `capacity`,
        `login_condition`
    );

--   *** ------------------------------------
--  *** Table ROOM_CHARGE
--   *** ------------------------------------
ALTER TABLE
    `room_charge`
ADD
    CONSTRAINT `room_charge_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `partner_group_id`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table PARTNER_SECTION
--   *** ------------------------------------
ALTER TABLE
    `partner_section`
ADD
    CONSTRAINT `partner_section_pky` PRIMARY KEY (`partner_cd`, `section_id`);

--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN2
--   *** ------------------------------------
ALTER TABLE
    `hotel_camp_plan2`
ADD
    CONSTRAINT `hotel_camp_plan2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `camp_cd`);

--   *** ------------------------------------
--  *** Table DENY_LIST_RETURN
--   *** ------------------------------------
ALTER TABLE
    `deny_list_return`
ADD
    CONSTRAINT `deny_list_return_pky` PRIMARY KEY (`deny_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_VERIFY_YDP
--   *** ------------------------------------
ALTER TABLE
    `reserve_verify_ydp`
ADD
    CONSTRAINT `reserve_verify_ydp_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table CHARGE_EARLY
--   *** ------------------------------------
ALTER TABLE
    `charge_early`
ADD
    CONSTRAINT `charge_early_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_id`,
        `plan_id`,
        `partner_group_id`,
        `capacity`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_RSV
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_rsv`
ADD
    CONSTRAINT `reserve_dispose_rsv_pky` PRIMARY KEY (`dispose_rsv_id`);

--   *** ------------------------------------
--  *** Table MEMBER
--   *** ------------------------------------
ALTER TABLE
    `member`
ADD
    CONSTRAINT `member_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table PLAN
--   *** ------------------------------------
ALTER TABLE
    `plan`
ADD
    CONSTRAINT `plan_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `checksheet_grants`
ADD
    CONSTRAINT `checksheet_grants_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `welfare_grants_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_HR_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hr_grants_9xg`
ADD
    CONSTRAINT `billpay_hr_grants_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `welfare_grants_id`);

--   *** ------------------------------------
--  *** Table FACTORING_ZENGIN_REQUEST
--   *** ------------------------------------
ALTER TABLE
    `factoring_zengin_request`
ADD
    CONSTRAINT `factoring_zengin_request_pky` PRIMARY KEY (`factoring_cd`);

--   *** ------------------------------------
--  *** Table MONEY_SCHEDULE
--   *** ------------------------------------
ALTER TABLE
    `money_schedule`
ADD
    CONSTRAINT `money_schedule_pky` PRIMARY KEY (`ym`, `money_schedule_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_CALC2
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ranking_calc2`
ADD
    CONSTRAINT `room_plan_ranking_calc2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `wday`);

--   *** ------------------------------------
--  *** Table HOTEL_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `hotel_yahoo`
ADD
    CONSTRAINT `hotel_yahoo_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_credit`
ADD
    CONSTRAINT `billpay_hotel_credit_pky` PRIMARY KEY (`hotel_cd`, `billpay_ym`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_FEE_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_fee_9xg`
ADD
    CONSTRAINT `billpay_hotel_fee_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_STOCK
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_stock`
ADD
    CONSTRAINT `billpay_ptn_stock_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `stock_rate`);

--   *** ------------------------------------
--  *** Table MAST_PLAN_ELEMENT
--   *** ------------------------------------
ALTER TABLE
    `mast_plan_element`
ADD
    CONSTRAINT `mast_plan_element_pky` PRIMARY KEY (`element_id`);

--   *** ------------------------------------
--  *** Table LOG_CUSTOMER
--   *** ------------------------------------
ALTER TABLE
    `log_customer`
ADD
    CONSTRAINT `log_customer_pky` PRIMARY KEY (`customer_id`, `branch_no`);

--   *** ------------------------------------
--  *** Table ALERT_MAIL_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `alert_mail_hotel`
ADD
    CONSTRAINT `alert_mail_hotel_pky` PRIMARY KEY (`hotel_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table LOG_GROUP_BUYING
--   *** ------------------------------------
ALTER TABLE
    `log_group_buying`
ADD
    CONSTRAINT `log_group_buying_pky` PRIMARY KEY (`order_id`, `branch_no`);

--   *** ------------------------------------
--  *** Table MAST_CARD
--   *** ------------------------------------
ALTER TABLE
    `mast_card`
ADD
    CONSTRAINT `mast_card_pky` PRIMARY KEY (`card_id`);

--   *** ------------------------------------
--  *** Table ZAP_RAKUJAN
--   *** ------------------------------------
ALTER TABLE
    `zap_rakujan`
ADD
    CONSTRAINT `zap_rakujan_pky` PRIMARY KEY (`random_cd`, `check_point`);

--   *** ------------------------------------
--  *** Table MAST_VR_ITEM
--   *** ------------------------------------
ALTER TABLE
    `mast_vr_item`
ADD
    CONSTRAINT `mast_vr_item_pky` PRIMARY KEY (`item_id`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_EARLY2
--   *** ------------------------------------
-- 存在しない  ALTER TABLE `room_charge_early2` ADD CONSTRAINT `room_charge_early2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `partner_group_id`, `date_ymd`)
;

--   *** ------------------------------------
--  *** Table RESERVE_PLAN_POINT
--   *** ------------------------------------
ALTER TABLE
    `reserve_plan_point`
ADD
    CONSTRAINT `reserve_plan_point_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_PRIORITY
--   *** ------------------------------------
ALTER TABLE
    `hotel_priority`
ADD
    CONSTRAINT `hotel_priority_pky` PRIMARY KEY (`ctrl_no`);

--   *** ------------------------------------
--  *** Table MAST_REGION
--   *** ------------------------------------
ALTER TABLE
    `mast_region`
ADD
    CONSTRAINT `mast_region_pky` PRIMARY KEY (`region_id`);

--   *** ------------------------------------
--  *** Table ZAP_ROOM_PLAN
--   *** ------------------------------------
ALTER TABLE
    `zap_room_plan`
ADD
    CONSTRAINT `zap_room_plan_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table ENQUETE_6315
--   *** ------------------------------------
ALTER TABLE
    `enquete_6315`
ADD
    CONSTRAINT `enquete_6315_pky` PRIMARY KEY (`id`, `member_cd`);

--   *** ------------------------------------
--  *** Table ROOM_AKF_RELATION
--   *** ------------------------------------
--  ALTER TABLE `room_akf_relation` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `room_akf_relation`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `room_akf_relation` MODIFY (`room_id` NOT NULL ENABLE);
ALTER TABLE
    `room_akf_relation`
MODIFY
    `room_id` varchar(10) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table MAST_STATION_JUNCTION
--   *** ------------------------------------
ALTER TABLE
    `mast_station_junction`
ADD
    CONSTRAINT `mast_station_junction_pky` PRIMARY KEY (`junction_id`);

--   *** ------------------------------------
--  *** Table BR_POINT_GIFT_TICKET
--   *** ------------------------------------
ALTER TABLE
    `br_point_gift_ticket`
ADD
    CONSTRAINT `br_point_gift_ticket_pky` PRIMARY KEY (`br_point_gift_id`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_1
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_1`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_1_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table BR_POINT_PLUS_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `br_point_plus_hotel`
ADD
    CONSTRAINT `br_point_plus_hotel_pky` PRIMARY KEY (`point_plus_id`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ZAP_ROOM_PLAN_CHARGE
--   *** ------------------------------------
ALTER TABLE
    `zap_room_plan_charge`
ADD
    CONSTRAINT `zap_room_plan_charge_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table RESERVE_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `reserve_hotel`
ADD
    CONSTRAINT `reserve_hotel_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_PLAN_INFO
--   *** ------------------------------------
ALTER TABLE
    `reserve_plan_info`
ADD
    CONSTRAINT `reserve_plan_info_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MAST_VR_ROOM_TYPE
--   *** ------------------------------------
ALTER TABLE
    `mast_vr_room_type`
ADD
    CONSTRAINT `mast_vr_room_type_pky` PRIMARY KEY (`category_id`);

--   *** ------------------------------------
--  *** Table RESERVE_MESSAGE
--   *** ------------------------------------
ALTER TABLE
    `reserve_message`
ADD
    CONSTRAINT `reserve_message_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_POINT_20170101
--   *** ------------------------------------
ALTER TABLE
    `room_plan_point_20170101`
ADD
    CONSTRAINT `room_plan_point_20170101_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_YAHOO_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_yahoo_9xg`
ADD
    CONSTRAINT `billpay_yahoo_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table RESERVE_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `reserve_grants`
ADD
    CONSTRAINT `reserve_grants_pky` PRIMARY KEY (
        `welfare_grants_history_id`,
        `order_cd`,
        `reserve_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table RESERVE_TOUR
--   *** ------------------------------------
ALTER TABLE
    `reserve_tour`
ADD
    CONSTRAINT `reserve_tour_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_TYPE_20170101
--   *** ------------------------------------
ALTER TABLE
    `hotel_type_20170101`
ADD
    CONSTRAINT `hotel_type_20170101_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `checksheet_grants_9xg`
ADD
    CONSTRAINT `checksheet_grants_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `welfare_grants_id`);

--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN2_GOTO
--   *** ------------------------------------
ALTER TABLE
    `hotel_camp_plan2_goto`
ADD
    CONSTRAINT `hotel_camp_plan2_goto_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `camp_cd`);

--  ALTER TABLE `hotel_camp_plan2_goto` MODIFY (`display_status` NOT NULL ENABLE);
ALTER TABLE
    `hotel_camp_plan2_goto`
MODIFY
    `display_status` tinyint NOT NULL;

--   *** ------------------------------------
--  *** Table BILLPAYED_FEE_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_fee_9xg`
ADD
    CONSTRAINT `billpayed_fee_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table PARTNER_LAYOUT
--   *** ------------------------------------
ALTER TABLE
    `partner_layout`
ADD
    CONSTRAINT `partner_layout_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table LOG_CANCEL
--   *** ------------------------------------
ALTER TABLE
    `log_cancel`
ADD
    CONSTRAINT `log_cancel_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `cancel_dtm`);

--   *** ------------------------------------
--  *** Table CARD_PAYMENT_GBY
--   *** ------------------------------------
ALTER TABLE
    `card_payment_gby`
ADD
    CONSTRAINT `card_payment_gby_pky` PRIMARY KEY (`card_payment_id`);

ALTER TABLE
    `card_payment_gby`
ADD
    CONSTRAINT `card_payment_gby_unq_01` UNIQUE (`payment_system`, `demand_dtm`, `order_id`);

--   *** ------------------------------------
--  *** Table AFFILIATER
--   *** ------------------------------------
ALTER TABLE
    `affiliater`
ADD
    CONSTRAINT `affiliater_pky` PRIMARY KEY (`affiliater_cd`);

--   *** ------------------------------------
--  *** Table PLAN_HOLD_KEYWORDS
--   *** ------------------------------------
ALTER TABLE
    `plan_hold_keywords`
ADD
    CONSTRAINT `plan_hold_keywords_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `keyword_id`);

--   *** ------------------------------------
--  *** Table SECURE_LICENSE
--   *** ------------------------------------
ALTER TABLE
    `secure_license`
ADD
    CONSTRAINT `secure_license_pky` PRIMARY KEY (`license_id`);

--   *** ------------------------------------
--  *** Table CHARGE2
--   *** ------------------------------------
-- 存在しない  ALTER TABLE `charge2` ADD CONSTRAINT `charge2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `partner_group_id`, `capacity`, `date_ymd`)  ;
--   *** ------------------------------------
--  *** Table HOTEL_REVIEW
--   *** ------------------------------------
ALTER TABLE
    `hotel_review`
ADD
    CONSTRAINT `hotel_review_pky` PRIMARY KEY (`hotel_cd`, `review_type`, `review_id`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_pky` PRIMARY KEY (`rec_type`, `hotel_cd`, `room_cd`, `plan_cd`);

--  ALTER TABLE `room_charge_yho_bat_tmp` MODIFY (`rec_type` NOT NULL ENABLE);
ALTER TABLE
    `room_charge_yho_bat_tmp`
MODIFY
    `rec_type` tinyint NOT NULL;

--  ALTER TABLE `room_charge_yho_bat_tmp` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `room_charge_yho_bat_tmp`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `room_charge_yho_bat_tmp` MODIFY (`room_cd` NOT NULL ENABLE);
ALTER TABLE
    `room_charge_yho_bat_tmp`
MODIFY
    `room_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `room_charge_yho_bat_tmp` MODIFY (`plan_cd` NOT NULL ENABLE);
ALTER TABLE
    `room_charge_yho_bat_tmp`
MODIFY
    `plan_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `room_charge_yho_bat_tmp` MODIFY (`date_ymd` NOT NULL ENABLE);
ALTER TABLE
    `room_charge_yho_bat_tmp`
MODIFY
    `date_ymd` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table MIGRATION_SPEC
--   *** ------------------------------------
ALTER TABLE
    `migration_spec`
ADD
    CONSTRAINT `migration_spec_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `element_id`);

--   *** ------------------------------------
--  *** Table HOTEL_STATION
--   *** ------------------------------------
ALTER TABLE
    `hotel_station`
ADD
    CONSTRAINT `hotel_station_pky` PRIMARY KEY (`hotel_cd`, `station_id`, `traffic_way`);

--   *** ------------------------------------
--  *** Table STAFF_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `staff_account`
ADD
    CONSTRAINT `staff_account_pky` PRIMARY KEY (`staff_id`);

--   *** ------------------------------------
--  *** Table AKAFU_CANCEL_QUEUE
--   *** ------------------------------------
ALTER TABLE
    `akafu_cancel_queue`
ADD
    CONSTRAINT `akafu_cancel_queue_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_FEE_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_fee_9xg`
ADD
    CONSTRAINT `billpayed_hotel_fee_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_SALES
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_sales`
ADD
    CONSTRAINT `billpay_ptn_sales_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `sales_rate`);

--   *** ------------------------------------
--  *** Table REPORT_PLAN
--   *** ------------------------------------
ALTER TABLE
    `report_plan`
ADD
    CONSTRAINT `report_plan_pky` PRIMARY KEY (`hotel_cd`, `date_ymd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_FIX_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_fix_9xg`
ADD
    CONSTRAINT `billpay_fix_9xg_pky` PRIMARY KEY (`hotel_cd`, `fix_status`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_BASE2
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ranking_base2`
ADD
    CONSTRAINT `room_plan_ranking_base2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table YAHOO_POINT_PLUS_INFO
--   *** ------------------------------------
ALTER TABLE
    `yahoo_point_plus_info`
ADD
    CONSTRAINT `yahoo_point_plus_info_pky` PRIMARY KEY (`point_plus_id`);

--   *** ------------------------------------
--  *** Table VERIFY_YAHOO_POINT
--   *** ------------------------------------
ALTER TABLE
    `verify_yahoo_point`
ADD
    CONSTRAINT `verify_yahoo_point_pky` PRIMARY KEY (`verify_point_cd`);

--   *** ------------------------------------
--  *** Table ROOM_COUNT2
--   *** ------------------------------------
ALTER TABLE
    `room_count2`
ADD
    CONSTRAINT `room_count2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `date_ymd`);

--   *** ------------------------------------
--  *** Table RESERVE_INSURANCE_WEATHER
--   *** ------------------------------------
ALTER TABLE
    `reserve_insurance_weather`
ADD
    CONSTRAINT `reserve_insurance_weather_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table MAST_PREF
--   *** ------------------------------------
ALTER TABLE
    `mast_pref`
ADD
    CONSTRAINT `mast_pref_pky` PRIMARY KEY (`pref_id`);

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_9XG
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_9xg`
ADD
    CONSTRAINT `reserve_dispose_9xg_pky` PRIMARY KEY (`dispose_id`);

--   *** ------------------------------------
--  *** Table RECORD_VARIOUS
--   *** ------------------------------------
ALTER TABLE
    `record_various`
ADD
    CONSTRAINT `record_various_pky` PRIMARY KEY (`date_ymd`);

--   *** ------------------------------------
--  *** Table TEMP_HOTEL_VS_MTN
--   *** ------------------------------------
ALTER TABLE
    `temp_hotel_vs_mtn`
ADD
    CONSTRAINT `temp_hotel_vs_mtn_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table TEMP_GROUP_RESERVE
--   *** ------------------------------------
ALTER TABLE
    `temp_group_reserve`
ADD
    CONSTRAINT `temp_group_reserve_pky` PRIMARY KEY (`hotel_cd`, `order_no`, `reply_no`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_CSTMR
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_cstmr`
ADD
    CONSTRAINT `billpay_ptn_cstmr_pky` PRIMARY KEY (`billpay_ym`, `customer_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_RSV
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_rsv`
ADD
    CONSTRAINT `billpayed_hotel_rsv_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table MAST_AREA_SURVEY
--   *** ------------------------------------
ALTER TABLE
    `mast_area_survey`
ADD
    CONSTRAINT `mast_area_survey_pky` PRIMARY KEY (`survey_class`, `survey_cd`);

--   *** ------------------------------------
--  *** Table GIFT
--   *** ------------------------------------
ALTER TABLE
    `gift`
ADD
    CONSTRAINT `gift_pky` PRIMARY KEY (`gift_id`);

--   *** ------------------------------------
--  *** Table WELFARE_OP
--   *** ------------------------------------
ALTER TABLE
    `welfare_op`
ADD
    CONSTRAINT `welfare_op_pky` PRIMARY KEY (`welfare_op_id`);

--   *** ------------------------------------
--  *** Table HOTEL_BATH_TAX
--   *** ------------------------------------
ALTER TABLE
    `hotel_bath_tax`
ADD
    CONSTRAINT `hotel_bath_tax_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table SPOT
--   *** ------------------------------------
ALTER TABLE
    `spot`
ADD
    CONSTRAINT `spot_pky` PRIMARY KEY (`spot_id`);

--   *** ------------------------------------
--  *** Table CHARGE
--   *** ------------------------------------
ALTER TABLE
    `charge`
ADD
    CONSTRAINT `charge_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_id`,
        `plan_id`,
        `partner_group_id`,
        `capacity`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table GROUP_BUYING_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `group_buying_detail`
ADD
    CONSTRAINT `group_buying_detail_pky` PRIMARY KEY (`deal_id`);

--  ALTER TABLE `group_buying_detail` MODIFY (`deal_nm` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `deal_nm` varchar(768) BINARY NOT NULL;

--  ALTER TABLE `group_buying_detail` MODIFY (`usual_charge` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `usual_charge` int NOT NULL;

--  ALTER TABLE `group_buying_detail` MODIFY (`deal_charge` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `deal_charge` int NOT NULL;

--  ALTER TABLE `group_buying_detail` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_detail` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `group_buying_detail` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_detail` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_detail`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table HOTEL_SURVEY
--   *** ------------------------------------
ALTER TABLE
    `hotel_survey`
ADD
    CONSTRAINT `hotel_survey_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table DENY_LIST
--   *** ------------------------------------
ALTER TABLE
    `deny_list`
ADD
    CONSTRAINT `deny_list_pky` PRIMARY KEY (`deny_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_JWEST
--   *** ------------------------------------
ALTER TABLE
    `reserve_jwest`
ADD
    CONSTRAINT `reserve_jwest_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_MOBILE_MAIL
--   *** ------------------------------------
ALTER TABLE
    `member_mobile_mail`
ADD
    CONSTRAINT `member_mobile_mail_pky` PRIMARY KEY (`member_cd`, `send_mail_type`);

--   *** ------------------------------------
--  *** Table HOTEL_MEDIA
--   *** ------------------------------------
ALTER TABLE
    `hotel_media`
ADD
    CONSTRAINT `hotel_media_pky` PRIMARY KEY (`hotel_cd`, `type`, `media_no`);

--   *** ------------------------------------
--  *** Table HOTEL_PERSON
--   *** ------------------------------------
ALTER TABLE
    `hotel_person`
ADD
    CONSTRAINT `hotel_person_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_YAHOO_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_yahoo_9xg`
ADD
    CONSTRAINT `billpay_hotel_yahoo_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table SERVICE_VOTE_ANSWER
--   *** ------------------------------------
ALTER TABLE
    `service_vote_answer`
ADD
    CONSTRAINT `service_vote_answer_pky` PRIMARY KEY (`vote_cd`, `member_cd`);

--   *** ------------------------------------
--  *** Table AREA_YDP_MATCH
--   *** ------------------------------------
ALTER TABLE
    `area_ydp_match`
ADD
    CONSTRAINT `area_ydp_match_pky` PRIMARY KEY (`ydp_area_cd`);

--   *** ------------------------------------
--  *** Table MY_SEARCH_SETTING
--   *** ------------------------------------
ALTER TABLE
    `my_search_setting`
ADD
    CONSTRAINT `my_search_setting_pky` PRIMARY KEY (`my_search_setting_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_SUPERVISOR
--   *** ------------------------------------
ALTER TABLE
    `hotel_supervisor`
ADD
    CONSTRAINT `hotel_supervisor_pky` PRIMARY KEY (`supervisor_cd`);

--   *** ------------------------------------
--  *** Table BROADCAST_MESSAGE
--   *** ------------------------------------
ALTER TABLE
    `broadcast_message`
ADD
    CONSTRAINT `broadcast_message_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table PREVENT_ACCESSES
--   *** ------------------------------------
ALTER TABLE
    `prevent_accesses`
ADD
    CONSTRAINT `prevent_accesses_pky` PRIMARY KEY (`account_key`, `uri`);

--   *** ------------------------------------
--  *** Table CARD_PAYMENT_POWER
--   *** ------------------------------------
ALTER TABLE
    `card_payment_power`
ADD
    CONSTRAINT `card_payment_power_pky` PRIMARY KEY (`card_payment_id`);

ALTER TABLE
    `card_payment_power`
ADD
    CONSTRAINT `card_payment_power_unq_01` UNIQUE (`payment_system`, `demand_dtm`, `reserve_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_YDP2
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydp2`
ADD
    CONSTRAINT `hotel_ydp2_pky` PRIMARY KEY (`ydp_hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_grants`
ADD
    CONSTRAINT `billpay_hotel_grants_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_LEGACY
--   *** ------------------------------------
ALTER TABLE
    `room_plan_legacy`
ADD
    CONSTRAINT `room_plan_legacy_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table PARTNER_CONTROL
--   *** ------------------------------------
ALTER TABLE
    `partner_control`
ADD
    CONSTRAINT `partner_control_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table PARTNER_KEYWORD_EXAMPLE
--   *** ------------------------------------
ALTER TABLE
    `partner_keyword_example`
ADD
    CONSTRAINT `partner_keyword_example_pky` PRIMARY KEY (`partner_cd`, `layout_type`, `branch_no`);

--   *** ------------------------------------
--  *** Table RESERVE_MATERIAL
--   *** ------------------------------------
ALTER TABLE
    `reserve_material`
ADD
    CONSTRAINT `reserve_material_pky` PRIMARY KEY (`partner_cd`, `ccd`, `room`);

--   *** ------------------------------------
--  *** Table VOICE_STAY
--   *** ------------------------------------
ALTER TABLE
    `voice_stay`
ADD
    CONSTRAINT `voice_stay_pky` PRIMARY KEY (`voice_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `checksheet_credit`
ADD
    CONSTRAINT `checksheet_credit_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table BILLPAY_BOOK
--   *** ------------------------------------
ALTER TABLE
    `billpay_book`
ADD
    CONSTRAINT `billpay_book_pky` PRIMARY KEY (`billpay_cd`, `billpay_branch_no`);

--   *** ------------------------------------
--  *** Table PARTNER_POOL2
--   *** ------------------------------------
ALTER TABLE
    `partner_pool2`
ADD
    CONSTRAINT `partner_pool2_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_FEE
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_fee`
ADD
    CONSTRAINT `checksheet_hotel_fee_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_FEE
--   *** ------------------------------------
ALTER TABLE
    `billpay_fee`
ADD
    CONSTRAINT `billpay_fee_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_SPEC
--   *** ------------------------------------
ALTER TABLE
    `room_plan_spec`
ADD
    CONSTRAINT `room_plan_spec_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table WK_ROOM_PLAN_SALES
--   *** ------------------------------------
ALTER TABLE
    `wk_room_plan_sales`
ADD
    CONSTRAINT `wk_room_plan_sales_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table YDP_ITEM
--   *** ------------------------------------
ALTER TABLE
    `ydp_item`
ADD
    CONSTRAINT `ydp_item_pky` PRIMARY KEY (`cooperation_cd`, `item_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_GOTO_EXCEL
--   *** ------------------------------------
ALTER TABLE
    `hotel_goto_excel`
ADD
    CONSTRAINT `hotel_goto_excel_pky` PRIMARY KEY (`hotel_goto_excel_id`);

--   *** ------------------------------------
--  *** Table NOTIFY
--   *** ------------------------------------
ALTER TABLE
    `notify`
ADD
    CONSTRAINT `notify_pky` PRIMARY KEY (`notify_id`);

--   *** ------------------------------------
--  *** Table HOTEL_ADVERT_2009000400
--   *** ------------------------------------
ALTER TABLE
    `hotel_advert_2009000400`
ADD
    CONSTRAINT `hotel_advert_2009000400_pky` PRIMARY KEY (`record_id`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_RSV
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_rsv`
ADD
    CONSTRAINT `checksheet_hotel_rsv_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_yahoo`
ADD
    CONSTRAINT `billpay_hotel_yahoo_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_PLAN_JR
--   *** ------------------------------------
ALTER TABLE
    `reserve_plan_jr`
ADD
    CONSTRAINT `reserve_plan_jr_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_SENDING_MAIL
--   *** ------------------------------------
ALTER TABLE
    `member_sending_mail`
ADD
    CONSTRAINT `member_sending_mail_pky` PRIMARY KEY (`member_cd`, `send_mail_type`);

--   *** ------------------------------------
--  *** Table MY_HOTEL_LIST
--   *** ------------------------------------
ALTER TABLE
    `my_hotel_list`
ADD
    CONSTRAINT `my_hotel_list_pky` PRIMARY KEY (`member_cd`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_COUNT_REMOVED
--   *** ------------------------------------
ALTER TABLE
    `room_count_removed`
ADD
    CONSTRAINT `room_count_removed_pky` PRIMARY KEY (`room_count_removed_id`);

--   *** ------------------------------------
--  *** Table BR_POINT_PLUS_PLAN
--   *** ------------------------------------
ALTER TABLE
    `br_point_plus_plan`
ADD
    CONSTRAINT `br_point_plus_plan_pky` PRIMARY KEY (`point_plus_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_7
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_7`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_7_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table RECORD_MOBILE_RESERVE
--   *** ------------------------------------
ALTER TABLE
    `record_mobile_reserve`
ADD
    CONSTRAINT `record_mobile_reserve_pky` PRIMARY KEY (`date_ymd`, `career_type`);

--   *** ------------------------------------
--  *** Table RECORD_RESERVE2
--   *** ------------------------------------
ALTER TABLE
    `record_reserve2`
ADD
    CONSTRAINT `record_reserve2_pky` PRIMARY KEY (`date_ymd`, `record_type`);

--   *** ------------------------------------
--  *** Table RESERVE_PLAN_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `reserve_plan_grants`
ADD
    CONSTRAINT `reserve_plan_grants_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table MAST_LANDMARK_BASIC
--   *** ------------------------------------
ALTER TABLE
    `mast_landmark_basic`
ADD
    CONSTRAINT `mast_landmark_basic_pky` PRIMARY KEY (`item_id`);

ALTER TABLE
    `mast_landmark_basic`
ADD
    CONSTRAINT `mast_landmark_basic_unq_01` UNIQUE (`item_nm`);

--   *** ------------------------------------
--  *** Table RESERVE_MODIFY_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `reserve_modify_history`
ADD
    CONSTRAINT `reserve_modify_history_pky` PRIMARY KEY (`reserve_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table LOG_HOTEL_PERSON
--   *** ------------------------------------
ALTER TABLE
    `log_hotel_person`
ADD
    CONSTRAINT `log_hotel_person_pky` PRIMARY KEY (`hotel_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table PLAN_YDK
--   *** ------------------------------------
ALTER TABLE
    `plan_ydk`
ADD
    CONSTRAINT `plan_ydk_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_03
--   *** ------------------------------------
ALTER TABLE
    `log_security_03`
ADD
    CONSTRAINT `log_security_03_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table MAST_ROUTES
--   *** ------------------------------------
ALTER TABLE
    `mast_routes`
ADD
    CONSTRAINT `mast_routes_pky` PRIMARY KEY (`route_id`);

--  ALTER TABLE `mast_routes` MODIFY (`route_id` NOT NULL ENABLE);
ALTER TABLE
    `mast_routes`
MODIFY
    `route_id` varchar(5) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table ROOM_PLAN_POINT
--   *** ------------------------------------
ALTER TABLE
    `room_plan_point`
ADD
    CONSTRAINT `room_plan_point_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_INFO
--   *** ------------------------------------
ALTER TABLE
    `room_plan_info`
ADD
    CONSTRAINT `room_plan_info_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_ADDED_MESSAGE
--   *** ------------------------------------
ALTER TABLE
    `reserve_added_message`
ADD
    CONSTRAINT `reserve_added_message_pky` PRIMARY KEY (`reserve_cd`, `msg_type`);

--   *** ------------------------------------
--  *** Table SERVICE_VOTE
--   *** ------------------------------------
ALTER TABLE
    `service_vote`
ADD
    CONSTRAINT `service_vote_pky` PRIMARY KEY (`vote_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_SEARCH_WORDS
--   *** ------------------------------------
ALTER TABLE
    `hotel_search_words`
ADD
    CONSTRAINT `hotel_search_words_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `member_detail`
ADD
    CONSTRAINT `member_detail_pky` PRIMARY KEY (`member_cd`);

ALTER TABLE
    `member_detail`
ADD
    CONSTRAINT `member_detail_unq_01` UNIQUE (`account_id`);

--  ALTER TABLE `member_detail` MODIFY (`member_cd` NOT NULL ENABLE);
ALTER TABLE
    `member_detail`
MODIFY
    `member_cd` varchar(128) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table RSV_TOP_CONTENTS
--   *** ------------------------------------
ALTER TABLE
    `rsv_top_contents`
ADD
    CONSTRAINT `rsv_top_contents_pky` PRIMARY KEY (`content_cd`);

--  ALTER TABLE `rsv_top_contents` MODIFY (`place` NOT NULL ENABLE);
ALTER TABLE
    `rsv_top_contents`
MODIFY
    `place` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `rsv_top_contents` MODIFY (`title` NOT NULL ENABLE);
ALTER TABLE
    `rsv_top_contents`
MODIFY
    `title` varchar(300) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table EXTEND_SWITCH
--   *** ------------------------------------
ALTER TABLE
    `extend_switch`
ADD
    CONSTRAINT `extend_switch_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_YDP
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydp`
ADD
    CONSTRAINT `hotel_ydp_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_INITIAL
--   *** ------------------------------------
ALTER TABLE
    `room_charge_initial`
ADD
    CONSTRAINT `room_charge_initial_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `partner_group_id`
    );

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_yahoo`
ADD
    CONSTRAINT `billpayed_hotel_yahoo_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table WELFARE_MATCH_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `welfare_match_history`
ADD
    CONSTRAINT `welfare_match_history_pky` PRIMARY KEY (`welfare_match_history_id`);

ALTER TABLE
    `welfare_match_history`
ADD
    CONSTRAINT `welfare_match_history_unq_01` UNIQUE (`welfare_match_id`, `branch_no`);

--   *** ------------------------------------
--  *** Table MAST_AMEDAS
--   *** ------------------------------------
ALTER TABLE
    `mast_amedas`
ADD
    CONSTRAINT `mast_amedas_pky` PRIMARY KEY (`jbr_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN
--   *** ------------------------------------
ALTER TABLE
    `room_plan`
ADD
    CONSTRAINT `room_plan_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_GENRE
--   *** ------------------------------------
ALTER TABLE
    `group_buying_genre`
ADD
    CONSTRAINT `group_buying_genre_pky` PRIMARY KEY (`genre_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_GRANTS_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_grants_9xg`
ADD
    CONSTRAINT `billpayed_hotel_grants_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER_HOTEL_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_customer_hotel_9xg`
ADD
    CONSTRAINT `billpay_customer_hotel_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table KEYWORDS_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `keywords_hotel`
ADD
    CONSTRAINT `keywords_hotel_pky` PRIMARY KEY (`hotel_cd`, `field_nm`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_yahoo`
ADD
    CONSTRAINT `checksheet_hotel_yahoo_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_FEE_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_fee_9xg`
ADD
    CONSTRAINT `billpay_fee_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table MAST_CITY
--   *** ------------------------------------
ALTER TABLE
    `mast_city`
ADD
    CONSTRAINT `mast_city_pky` PRIMARY KEY (`city_id`);

--   *** ------------------------------------
--  *** Table HOTEL_PREMIUM
--   *** ------------------------------------
ALTER TABLE
    `hotel_premium`
ADD
    CONSTRAINT `hotel_premium_pky` PRIMARY KEY (`hotel_cd`, `open_ymd`);

--   *** ------------------------------------
--  *** Table MEMBER_HOTELS
--   *** ------------------------------------
ALTER TABLE
    `member_hotels`
ADD
    CONSTRAINT `member_hotels_pky` PRIMARY KEY (`member_cd`, `entry_type`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table MIGRATION_BASE
--   *** ------------------------------------
ALTER TABLE
    `migration_base`
ADD
    CONSTRAINT `migration_base_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`);

--   *** ------------------------------------
--  *** Table HOTEL_FACILITY_ROOM
--   *** ------------------------------------
ALTER TABLE
    `hotel_facility_room`
ADD
    CONSTRAINT `hotel_facility_room_pky` PRIMARY KEY (`hotel_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table HOTEL_INFO
--   *** ------------------------------------
ALTER TABLE
    `hotel_info`
ADD
    CONSTRAINT `hotel_info_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_RELATION
--   *** ------------------------------------
ALTER TABLE
    `hotel_relation`
ADD
    CONSTRAINT `hotel_relation_pky` PRIMARY KEY (`hotel_relation_cd`);

--   *** ------------------------------------
--  *** Table TWITTER
--   *** ------------------------------------
ALTER TABLE
    `twitter`
ADD
    CONSTRAINT `twitter_pky` PRIMARY KEY (`twitter_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_grants`
ADD
    CONSTRAINT `reserve_dispose_grants_pky` PRIMARY KEY (`dispose_grants_id`);

--   *** ------------------------------------
--  *** Table HOTEL_AREA
--   *** ------------------------------------
ALTER TABLE
    `hotel_area`
ADD
    CONSTRAINT `hotel_area_pky` PRIMARY KEY (`hotel_cd`, `entry_no`, `area_id`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_TODAY2
--   *** ------------------------------------
-- 存在しない  ALTER TABLE `room_charge_today2` ADD CONSTRAINT `room_charge_today2_pky` PRIMARY KEY (`hotel_cd`, `room_id`, `plan_id`, `partner_group_id`, `date_ymd`, `timetable`)  ;
--   *** ------------------------------------
--  *** Table MAST_MONEY_SCHEDULE
--   *** ------------------------------------
ALTER TABLE
    `mast_money_schedule`
ADD
    CONSTRAINT `mast_money_schedule_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table MEMBER_FREE
--   *** ------------------------------------
ALTER TABLE
    `member_free`
ADD
    CONSTRAINT `member_free_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_TYK
--   *** ------------------------------------
ALTER TABLE
    `hotel_tyk`
ADD
    CONSTRAINT `hotel_tyk_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4
--   *** ------------------------------------
ALTER TABLE
    `br_point_book_v4`
ADD
    CONSTRAINT `br_point_book_v4_pky` PRIMARY KEY (`br_point_cd`);

--   *** ------------------------------------
--  *** Table KEYWORDS_PLAN
--   *** ------------------------------------
ALTER TABLE
    `keywords_plan`
ADD
    CONSTRAINT `keywords_plan_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `field_nm`);

--   *** ------------------------------------
--  *** Table HOTEL_STATION_SURVEY
--   *** ------------------------------------
ALTER TABLE
    `hotel_station_survey`
ADD
    CONSTRAINT `hotel_station_survey_pky` PRIMARY KEY (`station_cd`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_YDP
--   *** ------------------------------------
ALTER TABLE
    `reserve_ydp`
ADD
    CONSTRAINT `reserve_ydp_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_CONTROL
--   *** ------------------------------------
ALTER TABLE
    `hotel_control`
ADD
    CONSTRAINT `hotel_control_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_STOCK
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn_stock`
ADD
    CONSTRAINT `billpayed_ptn_stock_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `stock_rate`);

--   *** ------------------------------------
--  *** Table TOP_ATTENTION_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `top_attention_detail`
ADD
    CONSTRAINT `top_attention_detail_pky` PRIMARY KEY (`attention_detail_id`);

--   *** ------------------------------------
--  *** Table STOCK_POWER
--   *** ------------------------------------
ALTER TABLE
    `stock_power`
ADD
    CONSTRAINT `stock_power_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table HOTEL_STATUS
--   *** ------------------------------------
ALTER TABLE
    `hotel_status`
ADD
    CONSTRAINT `hotel_status_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_LIVEDOOR
--   *** ------------------------------------
ALTER TABLE
    `member_livedoor`
ADD
    CONSTRAINT `member_livedoor_pky` PRIMARY KEY (`transaction_cd`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_12
--   *** ------------------------------------
ALTER TABLE
    `log_security_12`
ADD
    CONSTRAINT `log_security_12_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_SUPPLIER
--   *** ------------------------------------
ALTER TABLE
    `group_buying_supplier`
ADD
    CONSTRAINT `group_buying_supplier_pky` PRIMARY KEY (`supplier_cd`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_ORDER
--   *** ------------------------------------
ALTER TABLE
    `group_buying_order`
ADD
    CONSTRAINT `group_buying_order_pky` PRIMARY KEY (`order_id`);

--  ALTER TABLE `group_buying_order` MODIFY (`supplier_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `supplier_cd` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`supplier_order_id` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `supplier_order_id` varchar(32) BINARY NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`deal_id` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `deal_id` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`member_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `member_cd` varchar(128) BINARY NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`order_dtm` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `order_dtm` datetime NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`order_status` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `order_status` tinyint NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `group_buying_order` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `group_buying_order`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table JOURNAL
--   *** ------------------------------------
ALTER TABLE
    `journal`
ADD
    CONSTRAINT `journal_pky` PRIMARY KEY (`journal_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_CREDIT_DEV
--   *** ------------------------------------
ALTER TABLE
    `reserve_credit_dev`
ADD
    CONSTRAINT `reserve_credit_dev_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_6
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_6`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_6_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table ALERT_SYSTEM
--   *** ------------------------------------
ALTER TABLE
    `alert_system`
ADD
    CONSTRAINT `alert_system_pky` PRIMARY KEY (`alert_system_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `billpay_credit`
ADD
    CONSTRAINT `billpay_credit_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table MAST_BANK
--   *** ------------------------------------
ALTER TABLE
    `mast_bank`
ADD
    CONSTRAINT `mast_bank_pky` PRIMARY KEY (`bank_cd`);

--   *** ------------------------------------
--  *** Table HIKARI_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `hikari_account`
ADD
    CONSTRAINT `hikari_account_pky` PRIMARY KEY (`id`);

ALTER TABLE
    `hikari_account`
ADD
    CONSTRAINT `hikari_account_unq_01` UNIQUE (`account_id`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_09
--   *** ------------------------------------
ALTER TABLE
    `log_security_09`
ADD
    CONSTRAINT `log_security_09_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table SPOT_GROUP_MATCH
--   *** ------------------------------------
ALTER TABLE
    `spot_group_match`
ADD
    CONSTRAINT `spot_group_match_pky` PRIMARY KEY (`spot_group_id`, `spot_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_SALES
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn_sales`
ADD
    CONSTRAINT `billpayed_ptn_sales_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `sales_rate`);

--   *** ------------------------------------
--  *** Table RECORD_RESERVE
--   *** ------------------------------------
ALTER TABLE
    `record_reserve`
ADD
    CONSTRAINT `record_reserve_pky` PRIMARY KEY (`date_ymd`, `system_type`);

--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_FEE
--   *** ------------------------------------
ALTER TABLE
    `billpay_hotel_fee`
ADD
    CONSTRAINT `billpay_hotel_fee_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ORICO_SALES_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `orico_sales_history`
ADD
    CONSTRAINT `orico_sales_history_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `branch_no`);

--   *** ------------------------------------
--  *** Table EXTEND_SWITCH_PLAN
--   *** ------------------------------------
ALTER TABLE
    `extend_switch_plan`
ADD
    CONSTRAINT `extend_switch_plan_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `billpayed_yahoo`
ADD
    CONSTRAINT `billpayed_yahoo_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_REMOVED
--   *** ------------------------------------
ALTER TABLE
    `room_charge_removed`
ADD
    CONSTRAINT `room_charge_removed_pky` PRIMARY KEY (`room_charge_removed_id`);

--   *** ------------------------------------
--  *** Table PLAN_JR
--   *** ------------------------------------
ALTER TABLE
    `plan_jr`
ADD
    CONSTRAINT `plan_jr_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table CHARGE_TODAY
--   *** ------------------------------------
ALTER TABLE
    `charge_today`
ADD
    CONSTRAINT `charge_today_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_id`,
        `plan_id`,
        `partner_group_id`,
        `capacity`,
        `date_ymd`,
        `timetable`
    );

--   *** ------------------------------------
--  *** Table HOTEL_YDP_MATCH
--   *** ------------------------------------
ALTER TABLE
    `hotel_ydp_match`
ADD
    CONSTRAINT `hotel_ydp_redirect_pky` PRIMARY KEY (`ydp_hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_YAHOO
--   *** ------------------------------------
ALTER TABLE
    `billpay_yahoo`
ADD
    CONSTRAINT `billpay_yahoo_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROUTE_MAP
--   *** ------------------------------------
ALTER TABLE
    `route_map`
ADD
    CONSTRAINT `route_map_pky` PRIMARY KEY (`route_id`, `station_id1`, `station_id2`);

--  ALTER TABLE `route_map` MODIFY (`route_id` NOT NULL ENABLE);
ALTER TABLE
    `route_map`
MODIFY
    `route_id` varchar(5) BINARY NOT NULL;

--  ALTER TABLE `route_map` MODIFY (`station_id1` NOT NULL ENABLE);
ALTER TABLE
    `route_map`
MODIFY
    `station_id1` varchar(7) BINARY NOT NULL;

--  ALTER TABLE `route_map` MODIFY (`station_id2` NOT NULL ENABLE);
ALTER TABLE
    `route_map`
MODIFY
    `station_id2` varchar(7) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table RESERVE_POWER
--   *** ------------------------------------
ALTER TABLE
    `reserve_power`
ADD
    CONSTRAINT `reserve_power_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table AKAFU_STOCK_FRAME_NO
--   *** ------------------------------------
ALTER TABLE
    `akafu_stock_frame_no`
ADD
    CONSTRAINT `akafu_stock_frame_no_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_TODAY
--   *** ------------------------------------
ALTER TABLE
    `room_charge_today`
ADD
    CONSTRAINT `room_charge_today_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `partner_group_id`,
        `date_ymd`,
        `timetable`
    );

--   *** ------------------------------------
--  *** Table BR_SS_IMPORT
--   *** ------------------------------------
ALTER TABLE
    `br_ss_import`
ADD
    CONSTRAINT `br_ss_import_pky` PRIMARY KEY (`br_ss_import_id`);

ALTER TABLE
    `br_ss_import`
ADD
    CONSTRAINT `br_ss_import_unq_01` UNIQUE (`member_cd`);

ALTER TABLE
    `br_ss_import`
ADD
    CONSTRAINT `br_ss_import_unq_02` UNIQUE (`confirm_page_url`);

--  ALTER TABLE `br_ss_import` MODIFY (`br_ss_import_nm` NOT NULL ENABLE);
ALTER TABLE
    `br_ss_import`
MODIFY
    `br_ss_import_nm` varchar(60) BINARY NOT NULL;

--  ALTER TABLE `br_ss_import` MODIFY (`member_cd` NOT NULL ENABLE);
ALTER TABLE
    `br_ss_import`
MODIFY
    `member_cd` varchar(128) BINARY NOT NULL;

--  ALTER TABLE `br_ss_import` MODIFY (`confirm_page_url` NOT NULL ENABLE);
ALTER TABLE
    `br_ss_import`
MODIFY
    `confirm_page_url` varchar(255) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table BILLPAYED_STOCK
--   *** ------------------------------------
ALTER TABLE
    `billpayed_stock`
ADD
    CONSTRAINT `billpayed_stock_pky` PRIMARY KEY (
        `reserve_cd`,
        `date_ymd`,
        `site_cd`,
        `operation_ymd`
    );

--   *** ------------------------------------
--  *** Table MIGRATION_CANCEL_RATE_TEMP
--   *** ------------------------------------
ALTER TABLE
    `migration_cancel_rate_temp`
ADD
    CONSTRAINT `migration_cancel_rate_temp_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `days`);

--   *** ------------------------------------
--  *** Table MAST_LANDMARK_CATEGORY_1ST
--   *** ------------------------------------
ALTER TABLE
    `mast_landmark_category_1st`
ADD
    CONSTRAINT `mast_landmark_category_1st_pky` PRIMARY KEY (`category_1st_id`);

--   *** ------------------------------------
--  *** Table MAILMAG_V2_SET
--   *** ------------------------------------
ALTER TABLE
    `mailmag_v2_set`
ADD
    CONSTRAINT `mailmag_v2_set_pky` PRIMARY KEY (`mailmag_v2_set_id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PR_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpayed_pr_grants`
ADD
    CONSTRAINT `billpayed_pr_grants_pky` PRIMARY KEY (
        `reserve_cd`,
        `date_ymd`,
        `site_cd`,
        `operation_ymd`,
        `welfare_grants_id`
    );

--   *** ------------------------------------
--  *** Table MEMBER_ZAP
--   *** ------------------------------------
ALTER TABLE
    `member_zap`
ADD
    CONSTRAINT `member_zap_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table MAST_KEYWORD_MATCH
--   *** ------------------------------------
ALTER TABLE
    `mast_keyword_match`
ADD
    CONSTRAINT `mast_keyword_match_pky` PRIMARY KEY (`keyword_group_id`, `keyword_id`);

--   *** ------------------------------------
--  *** Table LANDMARK_PREF_MATCH
--   *** ------------------------------------
ALTER TABLE
    `landmark_pref_match`
ADD
    CONSTRAINT `landmark_pref_match_pky` PRIMARY KEY (`landmark_id`, `pref_id`);

--   *** ------------------------------------
--  *** Table NOTIFY_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `notify_detail`
ADD
    CONSTRAINT `notify_detail_pky` PRIMARY KEY (`notify_cd`, `notify_device`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_grants`
ADD
    CONSTRAINT `billpay_ptn_grants_pky` PRIMARY KEY (`billpay_ym`, `site_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_MOBILE
--   *** ------------------------------------
ALTER TABLE
    `member_mobile`
ADD
    CONSTRAINT `member_mobile_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table GOUDA_AR_TEST
--   *** ------------------------------------
ALTER TABLE
    `gouda_ar_test`
ADD
    CONSTRAINT `gouda_ar_test_pky` PRIMARY KEY (`id`, `cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `checksheet_hotel_grants`
ADD
    CONSTRAINT `checksheet_hotel_grants_pky` PRIMARY KEY (`checksheet_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table ORICO_RESERVE_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `orico_reserve_history`
ADD
    CONSTRAINT `orico_reserve_history_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `branch_no`);

--   *** ------------------------------------
--  *** Table PLAN_INFO
--   *** ------------------------------------
ALTER TABLE
    `plan_info`
ADD
    CONSTRAINT `plan_info_pky` PRIMARY KEY (`hotel_cd`, `plan_id`);

--   *** ------------------------------------
--  *** Table PARTNER_CLUTCH
--   *** ------------------------------------
ALTER TABLE
    `partner_clutch`
ADD
    CONSTRAINT `partner_clutch_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table MEMBER_HOTELS2
--   *** ------------------------------------
ALTER TABLE
    `member_hotels2`
ADD
    CONSTRAINT `member_hotels2_pky` PRIMARY KEY (`member_cd`, `entry_type`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `billpay_customer_hotel`
ADD
    CONSTRAINT `billpay_customer_hotel_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table PARTNER_SITE
--   *** ------------------------------------
ALTER TABLE
    `partner_site`
ADD
    CONSTRAINT `partner_site_pky` PRIMARY KEY (`site_cd`);

--   *** ------------------------------------
--  *** Table MAST_KEYWORD_GROUP
--   *** ------------------------------------
ALTER TABLE
    `mast_keyword_group`
ADD
    CONSTRAINT `mast_keyword_group_pky` PRIMARY KEY (`keyword_group_id`);

--   *** ------------------------------------
--  *** Table PLAN_SPEC
--   *** ------------------------------------
ALTER TABLE
    `plan_spec`
ADD
    CONSTRAINT `plan_spec_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `element_id`);

--   *** ------------------------------------
--  *** Table RESERVE_CHARGE
--   *** ------------------------------------
ALTER TABLE
    `reserve_charge`
ADD
    CONSTRAINT `reserve_charge_chk_01` CHECK (sales_charge is not null);

ALTER TABLE
    `reserve_charge`
ADD
    CONSTRAINT `reserve_charge_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table RESERVE_CREDIT
--   *** ------------------------------------
ALTER TABLE
    `reserve_credit`
ADD
    CONSTRAINT `reserve_credit_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table PARTNER_OAUTH2
--   *** ------------------------------------
ALTER TABLE
    `partner_oauth2`
ADD
    CONSTRAINT `partner_oauth2_pky` PRIMARY KEY (`client_id`);

ALTER TABLE
    `partner_oauth2`
ADD
    CONSTRAINT `partner_oauth2_unq_01` UNIQUE (`client_secret`);

--   *** ------------------------------------
--  *** Table MEMBER_EPARK
--   *** ------------------------------------
ALTER TABLE
    `member_epark`
ADD
    CONSTRAINT `member_epark_pky` PRIMARY KEY (`epark_id`);

--   *** ------------------------------------
--  *** Table HOTEL_CANCEL_RATE
--   *** ------------------------------------
ALTER TABLE
    `hotel_cancel_rate`
ADD
    CONSTRAINT `hotel_cancel_rate_pky` PRIMARY KEY (`hotel_cd`, `days`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_TYPE_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_type_grants`
ADD
    CONSTRAINT `billpay_ptn_type_grants_pky` PRIMARY KEY (`billpay_ym`, `site_cd`, `welfare_grants_id`);

--   *** ------------------------------------
--  *** Table MIGRATION_MATCH
--   *** ------------------------------------
ALTER TABLE
    `migration_match`
ADD
    CONSTRAINT `migration_match_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table HOTEL
--   *** ------------------------------------
ALTER TABLE
    `hotel`
ADD
    CONSTRAINT `hotel_pky` PRIMARY KEY (`hotel_cd`);

--  ALTER TABLE `hotel` MODIFY (`hotel_cd` NOT NULL ENABLE);
ALTER TABLE
    `hotel`
MODIFY
    `hotel_cd` varchar(10) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table MAST_STATION
--   *** ------------------------------------
ALTER TABLE
    `mast_station`
ADD
    CONSTRAINT `mast_station_pky` PRIMARY KEY (`station_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING
--   *** ------------------------------------
ALTER TABLE
    `room_plan_ranking`
ADD
    CONSTRAINT `room_plan_ranking_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `wday`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_PRIORITY
--   *** ------------------------------------
ALTER TABLE
    `room_plan_priority`
ADD
    CONSTRAINT `room_plan_priority_pky` PRIMARY KEY (`pref_id`, `span`, `wday`, `priority`);

--   *** ------------------------------------
--  *** Table RESERVE_BASE_CHILD
--   *** ------------------------------------
ALTER TABLE
    `reserve_base_child`
ADD
    CONSTRAINT `reserve_base_child_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER
--   *** ------------------------------------
ALTER TABLE
    `billpay_customer`
ADD
    CONSTRAINT `billpay_customer_pky` PRIMARY KEY (`billpay_ym`, `customer_id`);

--   *** ------------------------------------
--  *** Table HOTEL_RATE
--   *** ------------------------------------
ALTER TABLE
    `hotel_rate`
ADD
    CONSTRAINT `hotel_rate_pky` PRIMARY KEY (`hotel_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table MAST_HOTEL_ELEMENT_VALUE
--   *** ------------------------------------
ALTER TABLE
    `mast_hotel_element_value`
ADD
    CONSTRAINT `mast_hotel_element_value_pky` PRIMARY KEY (`element_id`, `element_value_id`);

--   *** ------------------------------------
--  *** Table OTA_ROOM_RELATION
--   *** ------------------------------------
ALTER TABLE
    `ota_room_relation`
ADD
    CONSTRAINT `ota_room_relation_pky` PRIMARY KEY (`ota_room_relation_id`);

--   *** ------------------------------------
--  *** Table MEMBER_DETAIL_SP
--   *** ------------------------------------
ALTER TABLE
    `member_detail_sp`
ADD
    CONSTRAINT `member_detail_sp_pky` PRIMARY KEY (`member_cd`);

ALTER TABLE
    `member_detail_sp`
ADD
    CONSTRAINT `member_detail_sp_unq_01` UNIQUE (`account_id`);

--  ALTER TABLE `member_detail_sp` MODIFY (`member_cd` NOT NULL ENABLE);
ALTER TABLE
    `member_detail_sp`
MODIFY
    `member_cd` varchar(128) BINARY NOT NULL;

--   *** ------------------------------------
--  *** Table MAST_BANK_BRANCH
--   *** ------------------------------------
ALTER TABLE
    `mast_bank_branch`
ADD
    CONSTRAINT `mast_bank_branch_pky` PRIMARY KEY (`bank_cd`, `bank_branch_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_TYPE_SALES
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_type_sales`
ADD
    CONSTRAINT `billpay_ptn_type_sales_pky` PRIMARY KEY (
        `billpay_ym`,
        `site_cd`,
        `stock_type`,
        `sales_rate`
    );

--   *** ------------------------------------
--  *** Table RESERVE_PLAN_SPEC
--   *** ------------------------------------
ALTER TABLE
    `reserve_plan_spec`
ADD
    CONSTRAINT `reserve_plan_spec_pky` PRIMARY KEY (`reserve_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table CUSTOMER_HIKARI
--   *** ------------------------------------
--  ALTER TABLE `customer_hikari` MODIFY (`customer_id` NOT NULL ENABLE);
ALTER TABLE
    `customer_hikari`
MODIFY
    `customer_id` bigint NOT NULL;

--   *** ------------------------------------
--  *** Table BILLPAYED_FEE
--   *** ------------------------------------
ALTER TABLE
    `billpayed_fee`
ADD
    CONSTRAINT `billpayed_fee_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `operation_ymd`);

--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_SIMPLE_BCC
--   *** ------------------------------------
ALTER TABLE
    `mail_magazine_simple_bcc`
ADD
    CONSTRAINT `mail_magazine_simple_bcc_pky` PRIMARY KEY (`mail_magazine_simple_id`, `member_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN
--   *** ------------------------------------
ALTER TABLE
    `hotel_camp_plan`
ADD
    CONSTRAINT `hotel_camp_plan_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `camp_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_CONTACT
--   *** ------------------------------------
ALTER TABLE
    `reserve_contact`
ADD
    CONSTRAINT `reserve_contact_pky` PRIMARY KEY (`reserve_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_MODIFY
--   *** ------------------------------------
ALTER TABLE
    `hotel_modify`
ADD
    CONSTRAINT `hotel_modify_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table LANDMARKS
--   *** ------------------------------------
ALTER TABLE
    `landmarks`
ADD
    CONSTRAINT `landmarks_pky` PRIMARY KEY (`landmark_id`);

--   *** ------------------------------------
--  *** Table HOTEL_LANDMARK_SURVEY
--   *** ------------------------------------
ALTER TABLE
    `hotel_landmark_survey`
ADD
    CONSTRAINT `hotel_landmark_survey_pky` PRIMARY KEY (`landmark_id`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table MAST_CALENDAR
--   *** ------------------------------------
ALTER TABLE
    `mast_calendar`
ADD
    CONSTRAINT `mast_calendar_pky` PRIMARY KEY (`date_ymd`);

--   *** ------------------------------------
--  *** Table CUSTOMER_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `customer_hotel`
ADD
    CONSTRAINT `customer_hotel_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BR_POINT_SHORT_TERM_COND
--   *** ------------------------------------
ALTER TABLE
    `br_point_short_term_cond`
ADD
    CONSTRAINT `br_point_short_term_cond_pky` PRIMARY KEY (`member_type`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_5273
--   *** ------------------------------------
ALTER TABLE
    `group_buying_5273`
ADD
    CONSTRAINT `group_buying_5273_pky` PRIMARY KEY (`member_cd`);

--   *** ------------------------------------
--  *** Table SPOT_GROUP
--   *** ------------------------------------
ALTER TABLE
    `spot_group`
ADD
    CONSTRAINT `spot_group_pky` PRIMARY KEY (`spot_group_id`);

--   *** ------------------------------------
--  *** Table HOTEL_LANDMARK
--   *** ------------------------------------
ALTER TABLE
    `hotel_landmark`
ADD
    CONSTRAINT `hotel_landmark_pky` PRIMARY KEY (`hotel_cd`, `landmark_id`);

--   *** ------------------------------------
--  *** Table BR_POINT_PLUS_INFO
--   *** ------------------------------------
ALTER TABLE
    `br_point_plus_info`
ADD
    CONSTRAINT `br_point_plus_info_pky` PRIMARY KEY (`point_plus_id`);

--   *** ------------------------------------
--  *** Table LOG_PLAN_STATUS_POOL2
--   *** ------------------------------------
ALTER TABLE
    `log_plan_status_pool2`
ADD
    CONSTRAINT `log_plan_status_pool2_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table HOTEL_SUPERVISOR_ACCOUNT
--   *** ------------------------------------
ALTER TABLE
    `hotel_supervisor_account`
ADD
    CONSTRAINT `hotel_supervisor_account_pky` PRIMARY KEY (`supervisor_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_YAHOO_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_yahoo_9xg`
ADD
    CONSTRAINT `billpayed_hotel_yahoo_9xg_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_CARD
--   *** ------------------------------------
ALTER TABLE
    `hotel_card`
ADD
    CONSTRAINT `hotel_card_pky` PRIMARY KEY (`hotel_cd`, `card_id`);

--   *** ------------------------------------
--  *** Table MY_SETTING
--   *** ------------------------------------
ALTER TABLE
    `my_setting`
ADD
    CONSTRAINT `my_setting_pky` PRIMARY KEY (`member_cd`, `item_cd`);

--   *** ------------------------------------
--  *** Table EXTEND_SETTING_ROOM
--   *** ------------------------------------
ALTER TABLE
    `extend_setting_room`
ADD
    CONSTRAINT `extend_setting_room_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table MAST_KEYWORDS
--   *** ------------------------------------
ALTER TABLE
    `mast_keywords`
ADD
    CONSTRAINT `mast_keywords_pky` PRIMARY KEY (`keyword_id`);

--   *** ------------------------------------
--  *** Table BILLPAY_STOCK
--   *** ------------------------------------
ALTER TABLE
    `billpay_stock`
ADD
    CONSTRAINT `billpay_stock_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `site_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_INFORM
--   *** ------------------------------------
ALTER TABLE
    `hotel_inform`
ADD
    CONSTRAINT `hotel_inform_pky` PRIMARY KEY (`hotel_cd`, `branch_no`);

--   *** ------------------------------------
--  *** Table HOTEL_STAFF_NOTE
--   *** ------------------------------------
ALTER TABLE
    `hotel_staff_note`
ADD
    CONSTRAINT `hotel_staff_note_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table MMS_GENRE
--   *** ------------------------------------
ALTER TABLE
    `mms_genre`
ADD
    CONSTRAINT `mms_genre_pky` PRIMARY KEY (`mail_magazine_simple_id`, `genre`);

--   *** ------------------------------------
--  *** Table HOTEL_NEARBY
--   *** ------------------------------------
ALTER TABLE
    `hotel_nearby`
ADD
    CONSTRAINT `hotel_nearby_pky` PRIMARY KEY (`hotel_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_CANCEL_RATE
--   *** ------------------------------------
ALTER TABLE
    `room_plan_cancel_rate`
ADD
    CONSTRAINT `room_plan_cancel_rate_pky` PRIMARY KEY (`hotel_cd`, `room_cd`, `plan_cd`, `days`);

--   *** ------------------------------------
--  *** Table MEMBER_SEARCH_MAIL_SP
--   *** ------------------------------------
ALTER TABLE
    `member_search_mail_sp`
ADD
    CONSTRAINT `member_search_mail_sp_pky` PRIMARY KEY (`member_cd`, `entry_type`);

--  ALTER TABLE `member_search_mail_sp` MODIFY (`member_cd` NOT NULL ENABLE);
ALTER TABLE
    `member_search_mail_sp`
MODIFY
    `member_cd` varchar(128) BINARY NOT NULL;

--  ALTER TABLE `member_search_mail_sp` MODIFY (`entry_type` NOT NULL ENABLE);
ALTER TABLE
    `member_search_mail_sp`
MODIFY
    `entry_type` tinyint NOT NULL;

--   *** ------------------------------------
--  *** Table MAST_HOLIDAY
--   *** ------------------------------------
ALTER TABLE
    `mast_holiday`
ADD
    CONSTRAINT `mast_holiday_pky` PRIMARY KEY (`holiday`);

--   *** ------------------------------------
--  *** Table GROUP_BUYING_AUTHORI_DEV
--   *** ------------------------------------
ALTER TABLE
    `group_buying_authori_dev`
ADD
    CONSTRAINT `group_buying_authori_dev_pky` PRIMARY KEY (`order_id`);

--   *** ------------------------------------
--  *** Table PAYMENT
--   *** ------------------------------------
ALTER TABLE
    `payment`
ADD
    CONSTRAINT `payment_pky` PRIMARY KEY (`payment_id`);

ALTER TABLE
    `payment`
ADD
    CONSTRAINT `payment_unq_01` UNIQUE (
        `transfer_ymd`,
        `in_out_type`,
        `account_type`,
        `account_charge`,
        `acc_client_cd`,
        `acc_client_bank_nm`,
        `acc_client_branch_nm`
    );

--  ALTER TABLE `payment` MODIFY (`transfer_ymd` NOT NULL ENABLE);
ALTER TABLE
    `payment`
MODIFY
    `transfer_ymd` datetime NOT NULL;

--  ALTER TABLE `payment` MODIFY (`account_charge` NOT NULL ENABLE);
ALTER TABLE
    `payment`
MODIFY
    `account_charge` decimal(32, 0) NOT NULL;

--  ALTER TABLE `payment` MODIFY (`entry_cd` NOT NULL ENABLE);
ALTER TABLE
    `payment`
MODIFY
    `entry_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `payment` MODIFY (`entry_ts` NOT NULL ENABLE);
ALTER TABLE
    `payment`
MODIFY
    `entry_ts` datetime NOT NULL;

--  ALTER TABLE `payment` MODIFY (`modify_cd` NOT NULL ENABLE);
ALTER TABLE
    `payment`
MODIFY
    `modify_cd` varchar(64) BINARY NOT NULL;

--  ALTER TABLE `payment` MODIFY (`modify_ts` NOT NULL ENABLE);
ALTER TABLE
    `payment`
MODIFY
    `modify_ts` datetime NOT NULL;

--   *** ------------------------------------
--  *** Table PARTNER_LAYOUT2
--   *** ------------------------------------
ALTER TABLE
    `partner_layout2`
ADD
    CONSTRAINT `partner_layout2_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table BILLPAY_CREDIT_9XG
--   *** ------------------------------------
ALTER TABLE
    `billpay_credit_9xg`
ADD
    CONSTRAINT `billpay_credit_9xg_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`);

--   *** ------------------------------------
--  *** Table LOG_SECURITY_02
--   *** ------------------------------------
ALTER TABLE
    `log_security_02`
ADD
    CONSTRAINT `log_security_02_pky` PRIMARY KEY (`security_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_CANCEL_POLICY
--   *** ------------------------------------
ALTER TABLE
    `hotel_cancel_policy`
ADD
    CONSTRAINT `hotel_cancel_policy_pky` PRIMARY KEY (`hotel_cd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_GRANTS
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn_grants`
ADD
    CONSTRAINT `billpayed_ptn_grants_unq_01` UNIQUE (`billpay_ym`, `site_cd`);

--   *** ------------------------------------
--  *** Table HOTEL_AMENITY
--   *** ------------------------------------
ALTER TABLE
    `hotel_amenity`
ADD
    CONSTRAINT `hotel_amenity_pky` PRIMARY KEY (`hotel_cd`, `element_id`);

--   *** ------------------------------------
--  *** Table FD_BASE_TIME
--   *** ------------------------------------
ALTER TABLE
    `fd_base_time`
ADD
    CONSTRAINT `fd_base_time_pky` PRIMARY KEY (`affiliate_id`, `cooperation_cd`);

--  ALTER TABLE `fd_base_time` MODIFY (`base_tm` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `base_tm` datetime NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`time_range` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `time_range` smallint NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`activ_fg` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `activ_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`upd_id` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `upd_id` varchar(10) BINARY NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`upd_dt` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `upd_dt` datetime NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`stock_fg` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `stock_fg` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`cooperation_type_cd` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `cooperation_type_cd` tinyint NOT NULL;

--  ALTER TABLE `fd_base_time` MODIFY (`timeout` NOT NULL ENABLE);
ALTER TABLE
    `fd_base_time`
MODIFY
    `timeout` smallint NOT NULL;

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_2
--   *** ------------------------------------
ALTER TABLE
    `room_charge_yho_bat_tmp_2`
ADD
    CONSTRAINT `room_charge_yho_bat_tmp_2_pky` PRIMARY KEY (
        `rec_type`,
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table ZAP_ROOM_CHARGE
--   *** ------------------------------------
ALTER TABLE
    `zap_room_charge`
ADD
    CONSTRAINT `zap_room_charge_pky` PRIMARY KEY (
        `hotel_cd`,
        `room_cd`,
        `plan_cd`,
        `partner_group_id`,
        `date_ymd`
    );

--   *** ------------------------------------
--  *** Table NOTIFY_RIZAPULI_DETAIL
--   *** ------------------------------------
ALTER TABLE
    `notify_rizapuli_detail`
ADD
    CONSTRAINT `notify_rizapuli_detail_pky` PRIMARY KEY (`notify_rizapuli_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_BASE_CHARGE
--   *** ------------------------------------
ALTER TABLE
    `reserve_base_charge`
ADD
    CONSTRAINT `reserve_base_charge_pky` PRIMARY KEY (`reserve_cd`, `capacity`, `date_ymd`);

--   *** ------------------------------------
--  *** Table PARTNER_INQUIRY
--   *** ------------------------------------
ALTER TABLE
    `partner_inquiry`
ADD
    CONSTRAINT `partner_inquiry_pky` PRIMARY KEY (`partner_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_ORDER
--   *** ------------------------------------
ALTER TABLE
    `checksheet_order`
ADD
    CONSTRAINT `checksheet_order_pky` PRIMARY KEY (`order_no`);

ALTER TABLE
    `checksheet_order`
ADD
    CONSTRAINT `checksheet_order_unq_01` UNIQUE (`hotel_cd`);

--   *** ------------------------------------
--  *** Table CHECKSHEET_CUSTOMER_9XG
--   *** ------------------------------------
ALTER TABLE
    `checksheet_customer_9xg`
ADD
    CONSTRAINT `checksheet_customer_9xg_pky` PRIMARY KEY (`checksheet_ym`, `customer_id`);

--   *** ------------------------------------
--  *** Table MMS_LINK_ANALYZE
--   *** ------------------------------------
ALTER TABLE
    `mms_link_analyze`
ADD
    CONSTRAINT `mms_link_analyze_pky` PRIMARY KEY (`mail_magazine_simple_id`, `link_no`);

--   *** ------------------------------------
--  *** Table FEATURE
--   *** ------------------------------------
ALTER TABLE
    `feature`
ADD
    CONSTRAINT `feature_pky` PRIMARY KEY (`feature_id`);

--   *** ------------------------------------
--  *** Table WELFARE_GRANTS_HISTORY
--   *** ------------------------------------
ALTER TABLE
    `welfare_grants_history`
ADD
    CONSTRAINT `welfare_grants_history_pky` PRIMARY KEY (`welfare_grants_history_id`);

ALTER TABLE
    `welfare_grants_history`
ADD
    CONSTRAINT `welfare_grants_history_unq_01` UNIQUE (`welfare_grants_id`, `branch_no`);

--   *** ------------------------------------
--  *** Table REPORT_WEEK_HOTEL
--   *** ------------------------------------
ALTER TABLE
    `report_week_hotel`
ADD
    CONSTRAINT `report_week_hotel_pky` PRIMARY KEY (
        `hotel_cd`,
        `date_ymd`,
        `charge_type`,
        `capacity`
    );

--   *** ------------------------------------
--  *** Table RSV_PUSH_AREAS
--   *** ------------------------------------
ALTER TABLE
    `rsv_push_areas`
ADD
    CONSTRAINT `rsv_push_areas_pky` PRIMARY KEY (`area_id`);

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_YAHOO_9XG
--   *** ------------------------------------
ALTER TABLE
    `reserve_dispose_yahoo_9xg`
ADD
    CONSTRAINT `reserve_dispose_yahoo_9xg_pky` PRIMARY KEY (`dispose_yahoo_id`);

--   *** ------------------------------------
--  *** Table ROOM_PLAN_LOWEST2
--   *** ------------------------------------
ALTER TABLE
    `room_plan_lowest2`
ADD
    CONSTRAINT `room_plan_lowest2_pky` PRIMARY KEY (
        `hotel_cd`,
        `plan_id`,
        `room_id`,
        `capacity`,
        `charge_condition`
    );

--   *** ------------------------------------
--  *** Table HOTEL_VISUAL
--   *** ------------------------------------
ALTER TABLE
    `hotel_visual`
ADD
    CONSTRAINT `hotel_visual_pky` PRIMARY KEY (`hotel_cd`, `open_ymd`);

--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_FEE
--   *** ------------------------------------
ALTER TABLE
    `billpayed_hotel_fee`
ADD
    CONSTRAINT `billpayed_hotel_fee_pky` PRIMARY KEY (`billpay_ym`, `hotel_cd`);

--   *** ------------------------------------
--  *** Table YAHOO_POINT_BOOK_PRE
--   *** ------------------------------------
ALTER TABLE
    `yahoo_point_book_pre`
ADD
    CONSTRAINT `yahoo_point_book_pre_pky` PRIMARY KEY (`yahoo_point_cd`);

--   *** ------------------------------------
--  *** Table MAST_AREA_MATCH
--   *** ------------------------------------
ALTER TABLE
    `mast_area_match`
ADD
    CONSTRAINT `mast_area_match_pky` PRIMARY KEY (`id`);

--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_TYPE_SALES
--   *** ------------------------------------
ALTER TABLE
    `billpayed_ptn_type_sales`
ADD
    CONSTRAINT `billpayed_ptn_type_sales_pky` PRIMARY KEY (
        `billpay_ym`,
        `site_cd`,
        `stock_type`,
        `sales_rate`
    );

--   *** ------------------------------------
--  *** Table ROOM_YDK
--   *** ------------------------------------
ALTER TABLE
    `room_ydk`
ADD
    CONSTRAINT `room_ydk_pky` PRIMARY KEY (`hotel_cd`, `room_id`);

--   *** ------------------------------------
--  *** Table ORICO_WEBSERVICE
--   *** ------------------------------------
ALTER TABLE
    `orico_webservice`
ADD
    CONSTRAINT `orico_webservice_pky` PRIMARY KEY (`service_cd`, `reserve_cd`);

--   *** ------------------------------------
--  *** Table MIGRATION_CANCEL_RATE
--   *** ------------------------------------
ALTER TABLE
    `migration_cancel_rate`
ADD
    CONSTRAINT `migration_cancel_rate_pky` PRIMARY KEY (`hotel_cd`, `plan_id`, `days`);

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_SITE
--   *** ------------------------------------
ALTER TABLE
    `billpay_ptn_site`
ADD
    CONSTRAINT `billpay_ptn_site_pky` PRIMARY KEY (`billpay_ym`, `site_cd`);

--   *** ------------------------------------
--  *** Table RESERVE_FIX
--   *** ------------------------------------
ALTER TABLE
    `reserve_fix`
ADD
    CONSTRAINT `reserve_fix_pky` PRIMARY KEY (`reserve_cd`, `date_ymd`, `fix_type`);