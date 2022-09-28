USE ac_travel;

-- 半角スペースを入れていないとmysqlはエラーになる

--   *** ------------------------------------
--  *** 火曜日-6月-07-2022   
--   *** ------------------------------------
--   *** ------------------------------------
--  *** Table REPORT_WEEK_HOTEL2
--   *** ------------------------------------

  ALTER TABLE `REPORT_WEEK_HOTEL2` ADD CONSTRAINT `REPORT_WEEK_HOTEL2_PKY` PRIMARY KEY (`HOTEL_CD`, `RESERVE_YMD`, `DATE_YMD`, `CHARGE_TYPE`, `CAPACITY`)
  ;
--   *** ------------------------------------
--  *** Table VOICE_REPLY
--   *** ------------------------------------

  ALTER TABLE `VOICE_REPLY` ADD CONSTRAINT `VOICE_REPLY_PKY` PRIMARY KEY (`HOTEL_CD`, `VOICE_CD`)
  ;
--   *** ------------------------------------
--  *** Table EPARK_REFRESH_TOKEN
--   *** ------------------------------------

  ALTER TABLE `EPARK_REFRESH_TOKEN` ADD CONSTRAINT `EPARK_REFRESH_TOKEN_PKY` PRIMARY KEY (`EPARK_ID`)
  ;
--   *** ------------------------------------
--  *** Table YDP_ITEM_CONTROL
--   *** ------------------------------------

  ALTER TABLE `YDP_ITEM_CONTROL` ADD CONSTRAINT `YDP_ITEM_CONTROL_PKY` PRIMARY KEY (`AFFILIATE_ID`, `COOPERATION_CD`, `ITEM_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_9
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_9` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_9_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_POWERDOWN
--   *** ------------------------------------

  ALTER TABLE `HOTEL_POWERDOWN` ADD CONSTRAINT `HOTEL_POWERDOWN_PKY` PRIMARY KEY (`HOTEL_CD`, `POWERDOWN_SEQ`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_POINT
--   *** ------------------------------------

  ALTER TABLE `PLAN_POINT` ADD CONSTRAINT `PLAN_POINT_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_SITE_RATE
--   *** ------------------------------------

  ALTER TABLE `PARTNER_SITE_RATE` ADD CONSTRAINT `PARTNER_SITE_RATE_PKY` PRIMARY KEY (`SITE_CD`, `ACCEPT_S_YMD`, `FEE_TYPE`, `STOCK_CLASS`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_PLAN
--   *** ------------------------------------

  ALTER TABLE `RESERVE_PLAN` ADD CONSTRAINT `RESERVE_PLAN_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_POINT
--   *** ------------------------------------

  ALTER TABLE `RESERVE_POINT` ADD CONSTRAINT `RESERVE_POINT_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_ROOM
--   *** ------------------------------------

  ALTER TABLE `ZAP_ROOM` ADD CONSTRAINT `ZAP_ROOM_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_BACK_NUMBER
--   *** ------------------------------------

  ALTER TABLE `MAIL_MAGAZINE_BACK_NUMBER` ADD CONSTRAINT `MAIL_MAGAZINE_BACK_NUMBER_PKY` PRIMARY KEY (`MAGAZINE_NO`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_PLAN_PARTNER_GROUP
--   *** ------------------------------------

  ALTER TABLE `ZAP_PLAN_PARTNER_GROUP` ADD CONSTRAINT `ZAP_PLAN_PARTNER_GROUP_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_CAPACITY
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_CAPACITY` ADD CONSTRAINT `ROOM_PLAN_CAPACITY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `DATE_YM`, `CAPACITY`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY_RIZAPULI_STAY
--   *** ------------------------------------

  ALTER TABLE `NOTIFY_RIZAPULI_STAY` ADD CONSTRAINT `NOTIFY_RIZAPULI_STAY_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_5
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_5` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_5_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_TYK
--   *** ------------------------------------

  ALTER TABLE `PLAN_TYK` ADD CONSTRAINT `PLAN_TYK_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_AKF →修正
--   *** ------------------------------------

--  ALTER TABLE `ROOM_AKF` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
 ALTER TABLE `ROOM_AKF` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL;
 
--  ALTER TABLE `ROOM_AKF` MODIFY (`ROOM_ID` NOT NULL ENABLE);
 ALTER TABLE `ROOM_AKF` MODIFY `ROOM_ID` varchar(10) BINARY NOT NULL;
--   *** ------------------------------------
--  *** Table PARTNER_BOOK_CUSTOMER
--   *** ------------------------------------

  ALTER TABLE `PARTNER_BOOK_CUSTOMER` ADD CONSTRAINT `PARTNER_BOOK_CUSTOMER_PKY` PRIMARY KEY (`CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table GIFT_SUPPLIER
--   *** ------------------------------------

  ALTER TABLE `GIFT_SUPPLIER` ADD CONSTRAINT `GIFT_SUPPLIER_PKY` PRIMARY KEY (`GIFT_SUPPLIER_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_WARDZONE
--   *** ------------------------------------

  ALTER TABLE `MAST_WARDZONE` ADD CONSTRAINT `MAST_WARDZONE_PKY` PRIMARY KEY (`WARDZONE_ID`)
  ;
--   *** ------------------------------------
--  *** Table AFFILIATE_PROGRAM
--   *** ------------------------------------

  ALTER TABLE `AFFILIATE_PROGRAM` ADD CONSTRAINT `AFFILIATE_PROGRAM_PKY` PRIMARY KEY (`AFFILIATE_CD`, `RESERVE_SYSTEM`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATIONS
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATIONS` ADD CONSTRAINT `HOTEL_STATIONS_PKY` PRIMARY KEY (`HOTEL_CD`, `STATION_ID`, `TRAFFIC_WAY`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE
--   *** ------------------------------------

  ALTER TABLE `RESERVE` ADD CONSTRAINT `RESERVE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table SERVICE_HUNTING
--   *** ------------------------------------

  ALTER TABLE `SERVICE_HUNTING` ADD CONSTRAINT `SERVICE_HUNTING_PKY` PRIMARY KEY (`HUNTING_ID`)
  ;
 
  ALTER TABLE `SERVICE_HUNTING` ADD CONSTRAINT `SERVICE_HUNTING_UNQ_01` UNIQUE (`HOTEL_CD`, `OPEN_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_YDP
--   *** ------------------------------------

  ALTER TABLE `MEMBER_YDP` ADD CONSTRAINT `MEMBER_YDP_PKY` PRIMARY KEY (`MEMBER_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_YDP2
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_YDP2` ADD CONSTRAINT `ROOM_PLAN_YDP2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_MSC_LOGIN
--   *** ------------------------------------

  ALTER TABLE `HOTEL_MSC_LOGIN` ADD CONSTRAINT `HOTEL_MSC_LOGIN_PKY` PRIMARY KEY (`HOTEL_CD`, `MSC_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table YAHOO_POINT_CANCEL_QUEUE
--   *** ------------------------------------

  ALTER TABLE `YAHOO_POINT_CANCEL_QUEUE` ADD CONSTRAINT `YAHOO_POINT_CANCEL_QUEUE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_POINT_20170101
--   *** ------------------------------------

  ALTER TABLE `PLAN_POINT_20170101` ADD CONSTRAINT `PLAN_POINT_20170101_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_RIZAPULI_NOTIFY
--   *** ------------------------------------

  ALTER TABLE `LOG_RIZAPULI_NOTIFY` ADD CONSTRAINT `LOG_RIZAPULI_NOTIFY_PKY` PRIMARY KEY (`RIZAPULI_REQUEST_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CHARGE_DETAIL
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CHARGE_DETAIL` ADD CONSTRAINT `RESERVE_CHARGE_DETAIL_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `CAPACITY_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_TICKET
--   *** ------------------------------------

  ALTER TABLE `RESERVE_TICKET` ADD CONSTRAINT `RESERVE_TICKET_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_YAHOO
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_YAHOO` ADD CONSTRAINT `CHECKSHEET_YAHOO_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table FAX_PR
--   *** ------------------------------------

  ALTER TABLE `FAX_PR` ADD CONSTRAINT `FAX_PR_PKY` PRIMARY KEY (`FAX_PR_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_LANDMARK
--   *** ------------------------------------

  ALTER TABLE `MAST_LANDMARK` ADD CONSTRAINT `MAST_LANDMARK_PKY` PRIMARY KEY (`LANDMARK_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_11
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_11` ADD CONSTRAINT `LOG_SECURITY_11_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_DEFAULT
--   *** ------------------------------------

  ALTER TABLE `PARTNER_DEFAULT` ADD CONSTRAINT `PARTNER_DEFAULT_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_ROUTE
--   *** ------------------------------------

  ALTER TABLE `MAST_ROUTE` ADD CONSTRAINT `MAST_ROUTE_PKY` PRIMARY KEY (`ROUTE_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_ADDED_GOTO
--   *** ------------------------------------

  ALTER TABLE `RESERVE_ADDED_GOTO` ADD CONSTRAINT `RESERVE_ADDED_GOTO_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
 
--  ALTER TABLE `RESERVE_ADDED_GOTO` MODIFY (`ADDED_TYPE` NOT NULL ENABLE);
  ALTER TABLE `RESERVE_ADDED_GOTO` MODIFY `ADDED_TYPE` smallint NOT NULL ;

--   *** ------------------------------------
--  *** Table LANDMARK_CAMPAIGN
--   *** ------------------------------------

  ALTER TABLE `LANDMARK_CAMPAIGN` ADD CONSTRAINT `LANDMARK_CAMPAIGN_PKY` PRIMARY KEY (`CAMPAIGN_ID`);
--   *** ------------------------------------
--  *** Table HOTEL_YDP2_YAHOO
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDP2_YAHOO` ADD CONSTRAINT `HOTEL_YDP2_YAHOO_PKY` PRIMARY KEY (`YDP_HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_RECOMMEND
--   *** ------------------------------------

  ALTER TABLE `HOTEL_RECOMMEND` ADD CONSTRAINT `HOTEL_RECOMMEND_PKY` PRIMARY KEY (`MEMBER_CD`, `RESERVE_CD`, `RECOMMEND_ID`)
  ;
--   *** ------------------------------------
--  *** Table EXTEND_SETTING_PLAN
--   *** ------------------------------------

  ALTER TABLE `EXTEND_SETTING_PLAN` ADD CONSTRAINT `EXTEND_SETTING_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_08
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_08` ADD CONSTRAINT `LOG_SECURITY_08_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table ALERT_POST
--   *** ------------------------------------

  ALTER TABLE `ALERT_POST` ADD CONSTRAINT `ALERT_POST_PKY` PRIMARY KEY (`PERSON_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_RECEIPT
--   *** ------------------------------------

  ALTER TABLE `HOTEL_RECEIPT` ADD CONSTRAINT `HOTEL_RECEIPT_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table PAYMENT_MATCH
--   *** ------------------------------------

  ALTER TABLE `PAYMENT_MATCH` ADD CONSTRAINT `PAYMENT_MATCH_PKY` PRIMARY KEY (`PAYMENT_MATCH_ID`)
  ;
 
--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`PAYMENT_MATCH_DTM` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT_MATCH` MODIFY `PAYMENT_MATCH_DTM` datetime  NOT NULL ;

--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`PAYMENT_ID` NOT NULL ENABLE);
  ALTER TABLE `PAYMENT_MATCH` MODIFY `PAYMENT_ID` decimal(32,0) NOT NULL;
 
--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`MATCH_CHARGE` NOT NULL ENABLE);
  ALTER TABLE `PAYMENT_MATCH` MODIFY `MATCH_CHARGE` decimal(32,0)  NOT NULL ;
 
--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
  ALTER TABLE `PAYMENT_MATCH` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;
 
--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
  ALTER TABLE `PAYMENT_MATCH` MODIFY `ENTRY_TS` datetime NOT NULL ;
 
--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
  ALTER TABLE `PAYMENT_MATCH` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;
 
--  ALTER TABLE `PAYMENT_MATCH` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
  ALTER TABLE `PAYMENT_MATCH` MODIFY `MODIFY_TS` datetime NOT NULL ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_FIX
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_FIX` ADD CONSTRAINT `CHECKSHEET_FIX_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ALERT_MAIL_OPC
--   *** ------------------------------------

  ALTER TABLE `ALERT_MAIL_OPC` ADD CONSTRAINT `ALERT_MAIL_OPC_PKY` PRIMARY KEY (`PERSON_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL` ADD CONSTRAINT `BILLPAY_HOTEL_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table SPOT_NEARBY
--   *** ------------------------------------

  ALTER TABLE `SPOT_NEARBY` ADD CONSTRAINT `SPOT_NEARBY_PKY` PRIMARY KEY (`SPOT_ID`, `SPOT_NEARBY_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_CANCEL_POLICY
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_CANCEL_POLICY` ADD CONSTRAINT `ROOM_PLAN_CANCEL_POLICY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_TYPE_STOCK
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN_TYPE_STOCK` ADD CONSTRAINT `BILLPAYED_PTN_TYPE_STOCK_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `STOCK_TYPE`, `STOCK_RATE`)
  ;
--   *** ------------------------------------
--  *** Table FEATURE_DETAIL
--   *** ------------------------------------

  ALTER TABLE `FEATURE_DETAIL` ADD CONSTRAINT `FEATURE_DETAIL_PKY` PRIMARY KEY (`FEATURE_ID`, `FEATURE_DETAIL_ID`)
  ;
 
--  ALTER TABLE `FEATURE_DETAIL` MODIFY (`FEATURE_GROUP_ID` NOT NULL ENABLE);
  ALTER TABLE `FEATURE_DETAIL` MODIFY `FEATURE_GROUP_ID` varchar(3) BINARY NOT NULL ;
 
--  ALTER TABLE `FEATURE_DETAIL` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
  ALTER TABLE `FEATURE_DETAIL` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table ROOM_COUNT_INITIAL
--   *** ------------------------------------

  ALTER TABLE `ROOM_COUNT_INITIAL` ADD CONSTRAINT `ROOM_COUNT_INITIAL_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table TEMP_YAHOO_POINT_BOOK
--   *** ------------------------------------

  ALTER TABLE `TEMP_YAHOO_POINT_BOOK` ADD CONSTRAINT `TEMP_YAHOO_POINT_BOOK_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_BASE
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_RANKING_BASE` ADD CONSTRAINT `ROOM_PLAN_RANKING_BASE_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_JR
--   *** ------------------------------------

  ALTER TABLE `RESERVE_JR` ADD CONSTRAINT `RESERVE_JR_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AREA_NEARBY
--   *** ------------------------------------

  ALTER TABLE `MAST_AREA_NEARBY` ADD CONSTRAINT `MAST_AREA_NEARBY_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table YAHOO_POINT_PLUS_HOTEL
--   *** ------------------------------------

  ALTER TABLE `YAHOO_POINT_PLUS_HOTEL` ADD CONSTRAINT `YAHOO_POINT_PLUS_HOTEL_PKY` PRIMARY KEY (`POINT_PLUS_ID`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_AKF_RELATION
--   *** ------------------------------------

  ALTER TABLE `PLAN_AKF_RELATION` ADD CONSTRAINT `PLAN_AKF_RELATION_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table SERVICE_VOTE_CHOICES
--   *** ------------------------------------

  ALTER TABLE `SERVICE_VOTE_CHOICES` ADD CONSTRAINT `SERVICE_VOTE_CHOICES_PKY` PRIMARY KEY (`VOTE_CD`, `CHOICE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_ELEMENT_REMOVED
--   *** ------------------------------------

  ALTER TABLE `HOTEL_ELEMENT_REMOVED` ADD CONSTRAINT `HOTEL_ELEMENT_REMOVED_PKY` PRIMARY KEY (`HOTEL_CD`, `TABLE_NAME`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `PARTNER_ACCOUNT` ADD CONSTRAINT `PARTNER_ACCOUNT_PKY` PRIMARY KEY (`PARTNER_CD`, `ACCOUNT_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table SUBMIT_FORM_CHECK
--   *** ------------------------------------

  ALTER TABLE `SUBMIT_FORM_CHECK` ADD CONSTRAINT `SUBMIT_FORM_CHECK_UNQ_01` UNIQUE (`CHECK_CD`)
  ;
--   *** ------------------------------------
--  *** Table YAHOO_POINT_BOOK
--   *** ------------------------------------

  ALTER TABLE `YAHOO_POINT_BOOK` ADD CONSTRAINT `YAHOO_POINT_BOOK_PKY` PRIMARY KEY (`YAHOO_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_MEDIA2
--   *** ------------------------------------

  ALTER TABLE `ROOM_MEDIA2` ADD CONSTRAINT `ROOM_MEDIA2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_CUSTOMER_SITE
--   *** ------------------------------------

  ALTER TABLE `PARTNER_CUSTOMER_SITE` ADD CONSTRAINT `PARTNER_CUSTOMER_SITE_PKY` PRIMARY KEY (`CUSTOMER_ID`, `SITE_CD`, `FEE_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_DENY_KEYWORDS
--   *** ------------------------------------

  ALTER TABLE `PARTNER_DENY_KEYWORDS` ADD CONSTRAINT `PARTNER_DENY_KEYWORDS_PKY` PRIMARY KEY (`PARTNER_CD`, `KEYWORD_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_AKAFU
--   *** ------------------------------------

  ALTER TABLE `ROOM_AKAFU` ADD CONSTRAINT `ROOM_AKAFU_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOMTYPE_CD`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY_RIZAPULI
--   *** ------------------------------------

  ALTER TABLE `NOTIFY_RIZAPULI` ADD CONSTRAINT `NOTIFY_RIZAPULI_PKY` PRIMARY KEY (`NOTIFY_RIZAPULI_ID`)
  ;
--   *** ------------------------------------
--  *** Table CONTACT_SENDBOX
--   *** ------------------------------------

  ALTER TABLE `CONTACT_SENDBOX` ADD CONSTRAINT `CONTACT_SENDBOX_PKY` PRIMARY KEY (`SENDBOX_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER
--   *** ------------------------------------

  ALTER TABLE `PARTNER` ADD CONSTRAINT `PARTNER_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_FEE_DRAFT
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_FEE_DRAFT` ADD CONSTRAINT `BILLPAY_FEE_DRAFT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_CREDIT
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_CREDIT` ADD CONSTRAINT `BILLPAYED_HOTEL_CREDIT_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_CALC
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_RANKING_CALC` ADD CONSTRAINT `ROOM_PLAN_RANKING_CALC_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `WDAY`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_RSV_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_RSV_9XG` ADD CONSTRAINT `BILLPAYED_RSV_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_CREDIT_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_CREDIT_9XG` ADD CONSTRAINT `BILLPAYED_HOTEL_CREDIT_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_CREDIT
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_CREDIT` ADD CONSTRAINT `BILLPAYED_CREDIT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_DEST_HISTORY
--   *** ------------------------------------

  ALTER TABLE `WELFARE_DEST_HISTORY` ADD CONSTRAINT `WELFARE_DEST_HISTORY_PKY` PRIMARY KEY (`WELFARE_DEST_HISTORY_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_SP
--   *** ------------------------------------

  ALTER TABLE `MEMBER_SP` ADD CONSTRAINT `MEMBER_SP_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
 
--  ALTER TABLE `MEMBER_SP` MODIFY (`MEMBER_CD` NOT NULL ENABLE);
  ALTER TABLE `MEMBER_SP` MODIFY `MEMBER_CD` varchar(128) BINARY NOT NULL ;
--   *** ------------------------------------
--  *** Table HOTEL_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `HOTEL_ACCOUNT` ADD CONSTRAINT `HOTEL_ACCOUNT_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
 
  ALTER TABLE `HOTEL_ACCOUNT` ADD CONSTRAINT `HOTEL_ACCOUNT_UNQ_01` UNIQUE (`ACCOUNT_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_TYK
--   *** ------------------------------------

  ALTER TABLE `ROOM_TYK` ADD CONSTRAINT `ROOM_TYK_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_CARD
--   *** ------------------------------------

  ALTER TABLE `MEMBER_CARD` ADD CONSTRAINT `MEMBER_CARD_PKY` PRIMARY KEY (`MEMBER_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_GRANTS
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_GRANTS` ADD CONSTRAINT `ROOM_PLAN_GRANTS_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_RSV_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_RSV_9XG` ADD CONSTRAINT `BILLPAY_HOTEL_RSV_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table WEATHER_AREA_CITY
--   *** ------------------------------------

  ALTER TABLE `WEATHER_AREA_CITY` ADD CONSTRAINT `WEATHER_AREA_CITY_PKY` PRIMARY KEY (`CITY_ID`)
  ;
--   *** ------------------------------------
--  *** Table CONFIRM_HOTEL_PERSON
--   *** ------------------------------------

  ALTER TABLE `CONFIRM_HOTEL_PERSON` ADD CONSTRAINT `CONFIRM_HOTEL_PERSON_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_3
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_3` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_3_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CAMP
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CAMP` ADD CONSTRAINT `HOTEL_CAMP_PKY` PRIMARY KEY (`CAMP_CD`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_MEMBER_YAHOO
--   *** ------------------------------------

  ALTER TABLE `ZAP_MEMBER_YAHOO` ADD CONSTRAINT `ZAP_MEMBER_YAHOO_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_SALES
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_SALES` ADD CONSTRAINT `BILLPAY_SALES_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_MEDIA
--   *** ------------------------------------

  ALTER TABLE `ROOM_MEDIA` ADD CONSTRAINT `ROOM_MEDIA_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table SEND_MAIL_QUEUE
--   *** ------------------------------------

  ALTER TABLE `SEND_MAIL_QUEUE` ADD CONSTRAINT `SEND_MAIL_QUEUE_PKY` PRIMARY KEY (`MAIL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_GRANTS_9XG` ADD CONSTRAINT `BILLPAY_HOTEL_GRANTS_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_AKAFU_RELATION
--   *** ------------------------------------

  ALTER TABLE `ROOM_AKAFU_RELATION` ADD CONSTRAINT `ROOM_AKAFU_RELATION_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table ORICO_RESERVE
--   *** ------------------------------------

  ALTER TABLE `ORICO_RESERVE` ADD CONSTRAINT `ORICO_RESERVE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_LINK
--   *** ------------------------------------

  ALTER TABLE `HOTEL_LINK` ADD CONSTRAINT `HOTEL_LINK_PKY` PRIMARY KEY (`HOTEL_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_RSV
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_RSV` ADD CONSTRAINT `CHECKSHEET_RSV_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_FIX
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_FIX` ADD CONSTRAINT `BILLPAY_FIX_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MMS_SEND_EXTRACT_CONDITION
--   *** ------------------------------------

  ALTER TABLE `MMS_SEND_EXTRACT_CONDITION` ADD CONSTRAINT `MMS_SEND_EXTRACT_CONDITION_PKY` PRIMARY KEY (`CONDITION_ID`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_STATUS_POOL2
--   *** ------------------------------------

  ALTER TABLE `PLAN_STATUS_POOL2` ADD CONSTRAINT `PLAN_STATUS_POOL2_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `ROOM_ID`, `PARTNER_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_STAY_COUNT
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_STAY_COUNT` ADD CONSTRAINT `BR_POINT_STAY_COUNT_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN` ADD CONSTRAINT `BILLPAYED_PTN_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `FEE_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_COUNT
--   *** ------------------------------------

  ALTER TABLE `ROOM_COUNT` ADD CONSTRAINT `ROOM_COUNT_CHK_01` CHECK (ROOMS - RESERVE_ROOMS >= 0 and ROOMS >= 0);
 
  ALTER TABLE `ROOM_COUNT` ADD CONSTRAINT `ROOM_COUNT_CHK_02` CHECK (ROOMS >= 0 and RESERVE_ROOMS >= 0);
 
  ALTER TABLE `ROOM_COUNT` ADD CONSTRAINT `ROOM_COUNT_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE
--   *** ------------------------------------

  ALTER TABLE `MAIL_MAGAZINE` ADD CONSTRAINT `MAIL_MAGAZINE_PKY` PRIMARY KEY (`MAIL_MAGAZINE_ID`)
  ;
--   *** ------------------------------------
--  *** Table ALERT_VACANT
--   *** ------------------------------------

  ALTER TABLE `ALERT_VACANT` ADD CONSTRAINT `ALERT_VACANT_PKY` PRIMARY KEY (`PERSON_CD`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_SSO
--   *** ------------------------------------

  ALTER TABLE `MEMBER_SSO` ADD CONSTRAINT `MEMBER_SSO_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
 
  ALTER TABLE `MEMBER_SSO` ADD CONSTRAINT `MEMBER_SSO_UNQ_01` UNIQUE (`SSO_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PR_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PR_GRANTS` ADD CONSTRAINT `BILLPAY_PR_GRANTS_UNQ_01` UNIQUE (`RESERVE_CD`, `DATE_YMD`, `SITE_CD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_CANCEL_POLICY
--   *** ------------------------------------

  ALTER TABLE `PLAN_CANCEL_POLICY` ADD CONSTRAINT `PLAN_CANCEL_POLICY_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table TOP_ATTENTION
--   *** ------------------------------------

  ALTER TABLE `TOP_ATTENTION` ADD CONSTRAINT `TOP_ATTENTION_PKY` PRIMARY KEY (`ATTENTION_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YDP_FACTORING
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDP_FACTORING` ADD CONSTRAINT `HOTEL_YDP_FACTORING_PKY` PRIMARY KEY (`YDP_HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_YDP
--   *** ------------------------------------

  ALTER TABLE `PARTNER_YDP` ADD CONSTRAINT `PARTNER_YDP_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V3
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_BOOK_V3` ADD CONSTRAINT `BR_POINT_BOOK_V3_PKY` PRIMARY KEY (`BR_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_EPKP
--   *** ------------------------------------

  ALTER TABLE `MEMBER_EPKP` ADD CONSTRAINT `MEMBER_EPKP_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_FREE_TRACE
--   *** ------------------------------------

  ALTER TABLE `MEMBER_FREE_TRACE` ADD CONSTRAINT `MEMBER_FREE_TRACE_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_SEARCH_MAIL
--   *** ------------------------------------

  ALTER TABLE `MEMBER_SEARCH_MAIL` ADD CONSTRAINT `MEMBER_SEARCH_MAIL_PKY` PRIMARY KEY (`MEMBER_CD`, `ENTRY_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AREA
--   *** ------------------------------------

  ALTER TABLE `MAST_AREA` ADD CONSTRAINT `MAST_AREA_PKY` PRIMARY KEY (`AREA_ID`)
  ;
--   *** ------------------------------------
--  *** Table POINT_CAMP
--   *** ------------------------------------

  ALTER TABLE `POINT_CAMP` ADD CONSTRAINT `POINT_CAMP_PKY` PRIMARY KEY (`POINT_CAMP_CD`)
  ;
--   *** ------------------------------------
--  *** Table FM_AFFILIATE
--   *** ------------------------------------

  ALTER TABLE `FM_AFFILIATE` ADD CONSTRAINT `FM_AFFILIATE_PKY` PRIMARY KEY (`AFFILIATE_ID`)
  ;
 
--  ALTER TABLE `FM_AFFILIATE` MODIFY (`AFFILIATE_NM` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `AFFILIATE_NM` varchar(100) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`PASSWD` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `PASSWD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`STATUS` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `STATUS` varchar(2) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`FEERATIO_RT` NOT NULL ENABLE);
  ALTER TABLE `FM_AFFILIATE` MODIFY `FEERATIO_RT` decimal(4,1) NOT NULL;
 
--  ALTER TABLE `FM_AFFILIATE` MODIFY (`ZIP1` NOT NULL ENABLE);
  ALTER TABLE `FM_AFFILIATE` MODIFY `ZIP1`  varchar(3) BINARY NOT NULL ;
 
--  ALTER TABLE `FM_AFFILIATE` MODIFY (`ZIP2` NOT NULL ENABLE);
  ALTER TABLE `FM_AFFILIATE` MODIFY `ZIP2` varchar(4) BINARY NOT NULL ;
 
--  ALTER TABLE `FM_AFFILIATE` MODIFY (`PREFECTURE_CD` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `PREFECTURE_CD` varchar(2) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`TEL` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `TEL` varchar(15) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`EMAIL` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `EMAIL` varchar(100) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`BANK_NM` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `BANK_NM` varchar(60) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`ACCOUNT_NM` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `ACCOUNT_NM` varchar(60) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`ACCOUNT_NO` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `ACCOUNT_NO` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`UPD_ID` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `UPD_ID` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`UPD_DT` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `UPD_DT` datetime NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`SITE_TYPE` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `SITE_TYPE` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`CID_FG` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `CID_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`MEMBER_TYPE` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `MEMBER_TYPE` varchar(1) BINARY NOT NULL;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`POINTUSE_FG` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `POINTUSE_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`COLORUSE_FG` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `COLORUSE_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`SPRATE_FG` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `SPRATE_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`MYPAGESHOW_FG` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `MYPAGESHOW_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`RESERVE_CID_FG` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `RESERVE_CID_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`SCREEN_TYPE_CD` NOT NULL ENABLE);
   ALTER TABLE `FM_AFFILIATE` MODIFY `SCREEN_TYPE_CD` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FM_AFFILIATE` MODIFY (`PAYMENT_CD` NOT NULL ENABLE);
  ALTER TABLE `FM_AFFILIATE` MODIFY `PAYMENT_CD` varchar(1) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_GRANTS_9XG` ADD CONSTRAINT `RESERVE_DISPOSE_GRANTS_9XG_PKY` PRIMARY KEY (`DISPOSE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_STATION_COMPANY
--   *** ------------------------------------

  ALTER TABLE `MAST_STATION_COMPANY` ADD CONSTRAINT `MAST_STATION_COMPANY_PKY` PRIMARY KEY (`COMPANY_CD`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_DELIVERY
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_DELIVERY` ADD CONSTRAINT `GROUP_BUYING_DELIVERY_PKY` PRIMARY KEY (`ORDER_ID`)
  ;
 
--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`POSTAL_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `POSTAL_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`CITY` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `CITY` varchar(375) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`PERSON_FAMILY` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `PERSON_FAMILY` varchar(51) BINARY NOT NULL ;
 
--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`TEL` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `TEL` varchar(32) BINARY NOT NULL ;
 
--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `ENTRY_TS` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DELIVERY` MODIFY `MODIFY_TS` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table BR_POINT_SHORT_TERM
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_SHORT_TERM` ADD CONSTRAINT `BR_POINT_SHORT_TERM_PKY` PRIMARY KEY (`SHORT_TERM_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_RSV
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_RSV` ADD CONSTRAINT `BILLPAYED_RSV_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table DISPOSE_VOUCHER
--   *** ------------------------------------

  ALTER TABLE `DISPOSE_VOUCHER` ADD CONSTRAINT `DISPOSE_VOUCHER_PKY` PRIMARY KEY (`HOTEL_CD`, `OPERATION_YMD`, `RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table EXTEND_SWITCH_PLAN2
--   *** ------------------------------------

  ALTER TABLE `EXTEND_SWITCH_PLAN2` ADD CONSTRAINT `EXTEND_SWITCH_PLAN2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_CUSTOMER
--   *** ------------------------------------

  ALTER TABLE `PARTNER_CUSTOMER` ADD CONSTRAINT `PARTNER_CUSTOMER_PKY` PRIMARY KEY (`CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_RECOMMEND_RESULT
--   *** ------------------------------------

  ALTER TABLE `HOTEL_RECOMMEND_RESULT` ADD CONSTRAINT `HOTEL_RECOMMEND_RESULT_PKY` PRIMARY KEY (`HOTEL_CD`, `RECOMMEND_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_01
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_01` ADD CONSTRAINT `LOG_SECURITY_01_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table EPARK_STATE_TOKEN
--   *** ------------------------------------

  ALTER TABLE `EPARK_STATE_TOKEN` ADD CONSTRAINT `EPARK_STATE_TOKEN_PKY` PRIMARY KEY (`STATE_TOKEN_KEY`)
  ;
--   *** ------------------------------------
--  *** Table POINT
--   *** ------------------------------------

  ALTER TABLE `POINT` ADD CONSTRAINT `POINT_PKY` PRIMARY KEY (`POINT_ID`)
  ;
--   *** ------------------------------------
--  *** Table EPARK_ACCESS_TOKEN
--   *** ------------------------------------

  ALTER TABLE `EPARK_ACCESS_TOKEN` ADD CONSTRAINT `EPARK_ACCESS_TOKEN_PKY` PRIMARY KEY (`EPARK_ID`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_MOBILE_VARIOUS
--   *** ------------------------------------

  ALTER TABLE `RECORD_MOBILE_VARIOUS` ADD CONSTRAINT `RECORD_MOBILE_VARIOUS_PKY` PRIMARY KEY (`DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_PAY_PER_CALL
--   *** ------------------------------------

  ALTER TABLE `HOTEL_PAY_PER_CALL` ADD CONSTRAINT `HOTEL_PAY_PER_CALL_PKY` PRIMARY KEY (`HOTEL_CD`, `PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_YAHOO
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_YAHOO` ADD CONSTRAINT `RESERVE_DISPOSE_YAHOO_PKY` PRIMARY KEY (`DISPOSE_YAHOO_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_NOTIFY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_NOTIFY` ADD CONSTRAINT `HOTEL_NOTIFY_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
 
--  ALTER TABLE `HOTEL_NOTIFY` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
  ALTER TABLE `HOTEL_NOTIFY` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL ;
  
--   *** ------------------------------------
--  *** Table PARTNER_CLOCK
--   *** ------------------------------------

  ALTER TABLE `PARTNER_CLOCK` ADD CONSTRAINT `PARTNER_CLOCK_PKY` PRIMARY KEY (`PARTNER_CD`, `TABLE_NAME`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_COUPON
--   *** ------------------------------------

  ALTER TABLE `MEMBER_COUPON` ADD CONSTRAINT `MEMBER_COUPON_PKY` PRIMARY KEY (`MEMBER_COUPON_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_EXTENSION
--   *** ------------------------------------

  ALTER TABLE `RESERVE_EXTENSION` ADD CONSTRAINT `RESERVE_EXTENSION_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATION_WK
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATION_WK` ADD CONSTRAINT `HOTEL_STATION_WK_PKY` PRIMARY KEY (`HOTEL_CD`, `STATION_ID`, `TRAFFIC_WAY`)
  ;
--   *** ------------------------------------
--  *** Table BROADCAST_MESSAGES_HOTEL
--   *** ------------------------------------

  ALTER TABLE `BROADCAST_MESSAGES_HOTEL` ADD CONSTRAINT `BROADCAST_MESSAGES_HOTEL_PKY` PRIMARY KEY (`BROADCAST_MESSAGES_HOTEL_ID`)
  ;
--   *** ------------------------------------
--  *** Table ORICO
--   *** ------------------------------------

  ALTER TABLE `ORICO` ADD CONSTRAINT `ORICO_PKY` PRIMARY KEY (`ORDER_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_FEE_BASE
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_FEE_BASE` ADD CONSTRAINT `CHECKSHEET_HOTEL_FEE_BASE_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_NTA_RELATION
--   *** ------------------------------------

  ALTER TABLE `ROOM_NTA_RELATION` ADD CONSTRAINT `ROOM_NTA_RELATION_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table SSO_COMPARE
--   *** ------------------------------------

  ALTER TABLE `SSO_COMPARE` ADD CONSTRAINT `SSO_COMPARE_PKY` PRIMARY KEY (`ACCOUNT_TYPE`, `ACCOUNT_KEY`)
  ;
 
  ALTER TABLE `SSO_COMPARE` ADD CONSTRAINT `SSO_COMPARE_UNQ_01` UNIQUE (`COMPARE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SYSTEM_VERSION
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SYSTEM_VERSION` ADD CONSTRAINT `HOTEL_SYSTEM_VERSION_PKY` PRIMARY KEY (`HOTEL_CD`, `SYSTEM_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_NETWORK
--   *** ------------------------------------

  ALTER TABLE `ROOM_NETWORK` ADD CONSTRAINT `ROOM_NETWORK_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_CHILD
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_CHILD` ADD CONSTRAINT `ROOM_PLAN_CHILD_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_LINKS
--   *** ------------------------------------

  ALTER TABLE `PARTNER_LINKS` ADD CONSTRAINT `PARTNER_LINKS_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_JR_ENTRY_STATUS
--   *** ------------------------------------

  ALTER TABLE `HOTEL_JR_ENTRY_STATUS` ADD CONSTRAINT `HOTEL_JR_ENTRY_STATUS_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM
--   *** ------------------------------------

  ALTER TABLE `ROOM` ADD CONSTRAINT `ROOM_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4_LOG
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_BOOK_V4_LOG` ADD CONSTRAINT `BR_POINT_BOOK_V4_LOG_PKY` PRIMARY KEY (`BR_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AREA_STATION
--   *** ------------------------------------

  ALTER TABLE `MAST_AREA_STATION` ADD CONSTRAINT `MAST_AREA_STATION_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_BOOK
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_BOOK` ADD CONSTRAINT `BR_POINT_BOOK_PKY` PRIMARY KEY (`BR_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_MSC
--   *** ------------------------------------

  ALTER TABLE `HOTEL_MSC` ADD CONSTRAINT `HOTEL_MSC_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION
--   *** ------------------------------------

  ALTER TABLE `MIGRATION` ADD CONSTRAINT `MIGRATION_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_FACILITY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_FACILITY` ADD CONSTRAINT `HOTEL_FACILITY_PKY` PRIMARY KEY (`HOTEL_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table TEMP_KEI_MONTH_DISCOUNT
--   *** ------------------------------------

  ALTER TABLE `TEMP_KEI_MONTH_DISCOUNT` ADD CONSTRAINT `TEMP_KEI_MONTH_DISCOUNT_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YM`, `DISCOUNT_CD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_CANCEL_RATE
--   *** ------------------------------------

  ALTER TABLE `PLAN_CANCEL_RATE` ADD CONSTRAINT `PLAN_CANCEL_RATE_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `DAYS`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATUS_JR
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATUS_JR` ADD CONSTRAINT `HOTEL_STATUS_JR_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY_PAGE
--   *** ------------------------------------

  ALTER TABLE `NOTIFY_PAGE` ADD CONSTRAINT `NOTIFY_PAGE_PKY` PRIMARY KEY (`NOTIFY_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_SPEC
--   *** ------------------------------------

  ALTER TABLE `ROOM_SPEC` ADD CONSTRAINT `ROOM_SPEC_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YDK
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDK` ADD CONSTRAINT `HOTEL_YDK_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_CSTMRSITE
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_CSTMRSITE` ADD CONSTRAINT `BILLPAY_PTN_CSTMRSITE_PKY` PRIMARY KEY (`BILLPAY_YM`, `CUSTOMER_ID`, `SITE_CD`, `FEE_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_COUPON
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_COUPON` ADD CONSTRAINT `GROUP_BUYING_COUPON_PKY` PRIMARY KEY (`ORDER_ID`, `COUPON_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_RSV
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_RSV` ADD CONSTRAINT `BILLPAY_HOTEL_RSV_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_GRANTS` ADD CONSTRAINT `BILLPAYED_HOTEL_GRANTS_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL` ADD CONSTRAINT `BILLPAYED_HOTEL_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table REPORT_GENDER_AGE
--   *** ------------------------------------

  ALTER TABLE `REPORT_GENDER_AGE` ADD CONSTRAINT `REPORT_GENDER_AGE_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YMD`, `AGE`, `GENDER`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_RSV_9XG
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_RSV_9XG` ADD CONSTRAINT `RESERVE_DISPOSE_RSV_9XG_PKY` PRIMARY KEY (`DISPOSE_RSV_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_NEW
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_NEW` ADD CONSTRAINT `ROOM_PLAN_NEW_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_INITIAL2
--   *** ------------------------------------

--  ALTER TABLE `ROOM_CHARGE_INITIAL2` ADD CONSTRAINT `ROOM_CHARGE_INITIAL2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_GOTO_REGIST
--   *** ------------------------------------

  ALTER TABLE `HOTEL_GOTO_REGIST` ADD CONSTRAINT `HOTEL_GOTO_REGIST_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
 
--  ALTER TABLE `HOTEL_GOTO_REGIST` MODIFY (`REGIST_STATUS` NOT NULL ENABLE);
  ALTER TABLE `HOTEL_GOTO_REGIST` MODIFY `REGIST_STATUS` tinyint NOT NULL ;

--   *** ------------------------------------
--  *** Table ZAP_MSC
--   *** ------------------------------------

  ALTER TABLE `ZAP_MSC` ADD CONSTRAINT `ZAP_MSC_PKY` PRIMARY KEY (`RANDOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AREA_LANDMARK
--   *** ------------------------------------

  ALTER TABLE `MAST_AREA_LANDMARK` ADD CONSTRAINT `MAST_AREA_LANDMARK_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_POWERDOWN_S
--   *** ------------------------------------

  ALTER TABLE `HOTEL_POWERDOWN_S` ADD CONSTRAINT `HOTEL_POWERDOWN_S_PKY` PRIMARY KEY (`HOTEL_CD`, `POWERDOWN_SEQ`)
  ;
--   *** ------------------------------------
--  *** Table ORDER_GRANTS
--   *** ------------------------------------

  ALTER TABLE `ORDER_GRANTS` ADD CONSTRAINT `ORDER_GRANTS_PKY` PRIMARY KEY (`ORDER_CD`, `WELFARE_GRANTS_HISTORY_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_RSV_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_RSV_9XG` ADD CONSTRAINT `BILLPAYED_HOTEL_RSV_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_FEE
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_FEE` ADD CONSTRAINT `CHECKSHEET_FEE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MEDIA_REMOVED
--   *** ------------------------------------

  ALTER TABLE `MEDIA_REMOVED` ADD CONSTRAINT `MEDIA_REMOVED_PKY` PRIMARY KEY (`MEDIA_REMOVED_ID`)
  ;
--   *** ------------------------------------
--  *** Table ADDITIONAL_ZENGIN
--   *** ------------------------------------

  ALTER TABLE `ADDITIONAL_ZENGIN` ADD CONSTRAINT `ADDITIONAL_ZENGIN_PKY` PRIMARY KEY (`ZENGIN_YM`, `BRANCH_ID`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_OP_HISTORY
--   *** ------------------------------------

--  ALTER TABLE `WELFARE_OP_HISTORY` MODIFY (`WELFARE_MATCH_ID` NOT NULL ENABLE);
   ALTER TABLE `WELFARE_OP_HISTORY` MODIFY `WELFARE_MATCH_ID` bigint  NOT NULL ;

--  ALTER TABLE `WELFARE_OP_HISTORY` MODIFY (`WELFARE_MATCH_HISTORY_ID` NOT NULL ENABLE);
   ALTER TABLE `WELFARE_OP_HISTORY` MODIFY `WELFARE_MATCH_HISTORY_ID` bigint  NOT NULL ;

--  ALTER TABLE `WELFARE_OP_HISTORY` MODIFY (`WELFARE_GRANTS_ID` NOT NULL ENABLE);
   ALTER TABLE `WELFARE_OP_HISTORY` MODIFY `WELFARE_GRANTS_ID` bigint  NOT NULL ;

--  ALTER TABLE `WELFARE_OP_HISTORY` MODIFY (`WELFARE_GRANTS_HISTORY_ID` NOT NULL ENABLE);
   ALTER TABLE `WELFARE_OP_HISTORY` MODIFY `WELFARE_GRANTS_HISTORY_ID` bigint  NOT NULL ;

  ALTER TABLE `WELFARE_OP_HISTORY` ADD CONSTRAINT `WELFARE_OP_HISTORY_PKY` PRIMARY KEY (`WELFARE_OP_HISTORY_ID`)
  ;
 
  ALTER TABLE `WELFARE_OP_HISTORY` ADD CONSTRAINT `WELFARE_OP_HISTORY_UNQ_01` UNIQUE (`WELFARE_MATCH_HISTORY_ID`, `WELFARE_GRANTS_ID`, `WELFARE_GRANTS_HISTORY_ID`)
  ;
--   *** ------------------------------------
--  *** Table AKF_STOCK_FRAME_NO
--   *** ------------------------------------

  ALTER TABLE `AKF_STOCK_FRAME_NO` ADD CONSTRAINT `AKF_STOCK_FRAME_NO_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table CUSTOMER
--   *** ------------------------------------

  ALTER TABLE `CUSTOMER` ADD CONSTRAINT `CUSTOMER_PKY` PRIMARY KEY (`CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table NTA_STAFF
--   *** ------------------------------------

  ALTER TABLE `NTA_STAFF` ADD CONSTRAINT `NTA_STAFF_PKY` PRIMARY KEY (`NTA_STAFF_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_CLOUT
--   *** ------------------------------------

  ALTER TABLE `PARTNER_CLOUT` ADD CONSTRAINT `PARTNER_CLOUT_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CREDIT_FLUCTUATE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CREDIT_FLUCTUATE` ADD CONSTRAINT `RESERVE_CREDIT_FLUCTUATE_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table VOICE_REVIEW
--   *** ------------------------------------

  ALTER TABLE `VOICE_REVIEW` ADD CONSTRAINT `VOICE_REVIEW_PKY` PRIMARY KEY (`MEMBER_CD`, `RESERVE_CD`, `REVIEW_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_YAHOO_POINT
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_YAHOO_POINT` ADD CONSTRAINT `ROOM_PLAN_YAHOO_POINT_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_CREDIT_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_CREDIT_9XG` ADD CONSTRAINT `BILLPAYED_CREDIT_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table STAFF
--   *** ------------------------------------

  ALTER TABLE `STAFF` ADD CONSTRAINT `STAFF_PKY` PRIMARY KEY (`STAFF_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_WARD
--   *** ------------------------------------

  ALTER TABLE `MAST_WARD` ADD CONSTRAINT `MAST_WARD_PKY` PRIMARY KEY (`WARD_ID`)
  ;
--   *** ------------------------------------
--  *** Table CARD_PAYMENT_CREDIT
--   *** ------------------------------------

  ALTER TABLE `CARD_PAYMENT_CREDIT` ADD CONSTRAINT `CARD_PAYMENT_CREDIT_PKY` PRIMARY KEY (`CARD_PAYMENT_ID`)
  ;
 
  ALTER TABLE `CARD_PAYMENT_CREDIT` ADD CONSTRAINT `CARD_PAYMENT_CREDIT_UNQ_01` UNIQUE (`PAYMENT_SYSTEM`, `DEMAND_DTM`, `RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_NETWORK2
--   *** ------------------------------------

  ALTER TABLE `ROOM_NETWORK2` ADD CONSTRAINT `ROOM_NETWORK2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CANCEL_POLICY
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CANCEL_POLICY` ADD CONSTRAINT `RESERVE_CANCEL_POLICY_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table EXTEND_SETTING
--   *** ------------------------------------

  ALTER TABLE `EXTEND_SETTING` ADD CONSTRAINT `EXTEND_SETTING_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_COUNT_INITIAL2
--   *** ------------------------------------

  ALTER TABLE `ROOM_COUNT_INITIAL2` ADD CONSTRAINT `ROOM_COUNT_INITIAL2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HR_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HR_GRANTS` ADD CONSTRAINT `BILLPAY_HR_GRANTS_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_YAHOO
--   *** ------------------------------------

  ALTER TABLE `MEMBER_YAHOO` ADD CONSTRAINT `MEMBER_YAHOO_PKY` PRIMARY KEY (`TRANSACTION_CD`, `MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_LANDMARK_CATEGORY_2ND
--   *** ------------------------------------

  ALTER TABLE `MAST_LANDMARK_CATEGORY_2ND` ADD CONSTRAINT `MAST_LANDMARK_CATEGORY_2ND_PKY` PRIMARY KEY (`CATEGORY_2ND_ID`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_PLAN_POINT
--   *** ------------------------------------

  ALTER TABLE `ZAP_PLAN_POINT` ADD CONSTRAINT `ZAP_PLAN_POINT_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_STAFF_NOTE
--   *** ------------------------------------

  ALTER TABLE `MEMBER_STAFF_NOTE` ADD CONSTRAINT `MEMBER_STAFF_NOTE_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_BATH_TAX
--   *** ------------------------------------

  ALTER TABLE `RESERVE_BATH_TAX` ADD CONSTRAINT `RESERVE_BATH_TAX_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_HOTEL_RESERVE
--   *** ------------------------------------

  ALTER TABLE `RECORD_HOTEL_RESERVE` ADD CONSTRAINT `RECORD_HOTEL_RESERVE_PKY` PRIMARY KEY (`DATE_YMD`, `HOTEL_CD`, `CAPACITY`)
  ;
--   *** ------------------------------------
--  *** Table ORICO_RETRY
--   *** ------------------------------------

  ALTER TABLE `ORICO_RETRY` ADD CONSTRAINT `ORICO_RETRY_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING` ADD CONSTRAINT `GROUP_BUYING_PKY` PRIMARY KEY (`DEAL_ID`)
  ;
 
--  ALTER TABLE `GROUP_BUYING` MODIFY (`COUPON_GOAL` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `COUPON_GOAL` int NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`COUPON_COUNT` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `COUPON_COUNT` int NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`STATUS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `STATUS` tinyint NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`SUPPLIER_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `SUPPLIER_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `ENTRY_TS` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING` MODIFY `MODIFY_TS` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table RECORD_HOTEL_PLAN_COUNT
--   *** ------------------------------------

  ALTER TABLE `RECORD_HOTEL_PLAN_COUNT` ADD CONSTRAINT `RECORD_HOTEL_PLAN_COUNT_PKY` PRIMARY KEY (`RECORD_DTM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_LEGACY
--   *** ------------------------------------

  ALTER TABLE `ROOM_LEGACY` ADD CONSTRAINT `ROOM_LEGACY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table MMS_SEND_EXTRACT_RERATION
--   *** ------------------------------------

  ALTER TABLE `MMS_SEND_EXTRACT_RERATION` ADD CONSTRAINT `MMS_SEND_EXTRACT_RERATION_PKY` PRIMARY KEY (`MAIL_MAGAZINE_SIMPLE_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_POWER
--   *** ------------------------------------

  ALTER TABLE `LOG_POWER` ADD CONSTRAINT `LOG_POWER_PKY` PRIMARY KEY (`RESERVE_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_COUNT_AKAFU
--   *** ------------------------------------

  ALTER TABLE `ROOM_COUNT_AKAFU` ADD CONSTRAINT `ROOM_COUNT_AKAFU_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOMTYPE_CD`, `USE_DT`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_BOOK
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_BOOK` ADD CONSTRAINT `BILLPAY_PTN_BOOK_PKY` PRIMARY KEY (`BILLPAY_PTN_CD`, `BILLPAY_BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table MEDIA
--   *** ------------------------------------

  ALTER TABLE `MEDIA` ADD CONSTRAINT `MEDIA_PKY` PRIMARY KEY (`HOTEL_CD`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CONTROL_NOTE
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CONTROL_NOTE` ADD CONSTRAINT `HOTEL_CONTROL_NOTE_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_YDP_RECEIVE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_YDP_RECEIVE` ADD CONSTRAINT `RESERVE_YDP_RECEIVE_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_SIMPLE_COND
--   *** ------------------------------------

  ALTER TABLE `MAIL_MAGAZINE_SIMPLE_COND` ADD CONSTRAINT `MAIL_MAGAZINE_SIMPLE_COND_PKY` PRIMARY KEY (`MAIL_MAGAZINE_SIMPLE_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_CREDIT_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_CREDIT_9XG` ADD CONSTRAINT `BILLPAY_HOTEL_CREDIT_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_04
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_04` ADD CONSTRAINT `LOG_SECURITY_04_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_SIMPLE
--   *** ------------------------------------

  ALTER TABLE `MAIL_MAGAZINE_SIMPLE` ADD CONSTRAINT `MAIL_MAGAZINE_SIMPLE_PKY` PRIMARY KEY (`MAIL_MAGAZINE_SIMPLE_ID`)
  ;
--   *** ------------------------------------
--  *** Table EXTEND_TASK
--   *** ------------------------------------

  ALTER TABLE `EXTEND_TASK` ADD CONSTRAINT `EXTEND_TASK_PKY` PRIMARY KEY (`HOTEL_CD`, `TARGET_YM`, `TYPE`)
  ;
--   *** ------------------------------------
--  *** Table OTA_HOTEL_RELATION
--   *** ------------------------------------

  ALTER TABLE `OTA_HOTEL_RELATION` ADD CONSTRAINT `OTA_HOTEL_RELATION_PKY` PRIMARY KEY (`OTA_HOTEL_RELATION_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_FIX_9XG
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_FIX_9XG` ADD CONSTRAINT `CHECKSHEET_FIX_9XG_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_BOOK_CUSTOMER_DTL
--   *** ------------------------------------

  ALTER TABLE `PARTNER_BOOK_CUSTOMER_DTL` ADD CONSTRAINT `PARTNER_BOOK_CUSTOMER_DTL_PKY` PRIMARY KEY (`SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_MAIL
--   *** ------------------------------------

  ALTER TABLE `MEMBER_MAIL` ADD CONSTRAINT `MEMBER_MAIL_PKY` PRIMARY KEY (`MEMBER_MAIL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_FORCED_STOP_MAIL
--   *** ------------------------------------

  ALTER TABLE `MEMBER_FORCED_STOP_MAIL` ADD CONSTRAINT `MEMBER_FORCED_STOP_MAIL_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YDP_BR
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDP_BR` ADD CONSTRAINT `HOTEL_YDP_BR_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
 
--  ALTER TABLE `HOTEL_YDP_BR` MODIFY (`HOTEL_CD_YDP` NOT NULL ENABLE);
  ALTER TABLE `HOTEL_YDP_BR` MODIFY `HOTEL_CD_YDP` varchar(10) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table BILLPAY_PTN_TYPE_STOCK
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_TYPE_STOCK` ADD CONSTRAINT `BILLPAY_PTN_TYPE_STOCK_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `STOCK_TYPE`, `STOCK_RATE`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HR_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HR_GRANTS_9XG` ADD CONSTRAINT `BILLPAYED_HR_GRANTS_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_AUTHORI
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_AUTHORI` ADD CONSTRAINT `GROUP_BUYING_AUTHORI_PKY` PRIMARY KEY (`ORDER_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_07
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_07` ADD CONSTRAINT `LOG_SECURITY_07_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table POINT_CAMP_WINNING_RSV
--   *** ------------------------------------

  ALTER TABLE `POINT_CAMP_WINNING_RSV` ADD CONSTRAINT `POINT_CAMP_WINNING_RSV_PKY` PRIMARY KEY (`RESERVE_CD`, `POINT_CAMP_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HR_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HR_GRANTS` ADD CONSTRAINT `BILLPAYED_HR_GRANTS_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_BOOK
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_BOOK` ADD CONSTRAINT `CHECKSHEET_BOOK_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_FEE_BASE2
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_FEE_BASE2` ADD CONSTRAINT `CHECKSHEET_HOTEL_FEE_BASE2_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table LANDMARK_CATEGORY_MATCH
--   *** ------------------------------------

  ALTER TABLE `LANDMARK_CATEGORY_MATCH` ADD CONSTRAINT `LANDMARK_CATEGORY_MATCH_PKY` PRIMARY KEY (`LANDMARK_ID`, `CATEGORY_2ND_ID`)
  ;
--   *** ------------------------------------
--  *** Table RANKING_HOTEL
--   *** ------------------------------------

  ALTER TABLE `RANKING_HOTEL` ADD CONSTRAINT `RANKING_HOTEL_PKY` PRIMARY KEY (`HOTEL_CD`, `RANKING_UNIT`)
  ;
--   *** ------------------------------------
--  *** Table YAHOO_POINT_PLUS_PLAN
--   *** ------------------------------------

  ALTER TABLE `YAHOO_POINT_PLUS_PLAN` ADD CONSTRAINT `YAHOO_POINT_PLUS_PLAN_PKY` PRIMARY KEY (`POINT_PLUS_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CHARGE_DISCOUNT
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CHARGE_DISCOUNT` ADD CONSTRAINT `RESERVE_CHARGE_DISCOUNT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `DISCOUNT_FACTOR_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_SPEC2
--   *** ------------------------------------

  ALTER TABLE `ROOM_SPEC2` ADD CONSTRAINT `ROOM_SPEC2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table POINT_CAMP_ORDER
--   *** ------------------------------------

  ALTER TABLE `POINT_CAMP_ORDER` ADD CONSTRAINT `POINT_CAMP_ORDER_PKY` PRIMARY KEY (`POINT_CAMP_CD`, `MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE2
--   *** ------------------------------------

-- 存在しない  ALTER TABLE `ROOM_CHARGE2` ADD CONSTRAINT `ROOM_CHARGE2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`, `DATE_YMD`)  ;
--   *** ------------------------------------
--  *** Table RECEIPT_POWER
--   *** ------------------------------------

  ALTER TABLE `RECEIPT_POWER` ADD CONSTRAINT `RECEIPT_POWER_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN_GOTO
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CAMP_PLAN_GOTO` ADD CONSTRAINT `HOTEL_CAMP_PLAN_GOTO_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `CAMP_CD`)
  ;
 
--  ALTER TABLE `HOTEL_CAMP_PLAN_GOTO` MODIFY (`DISPLAY_STATUS` NOT NULL ENABLE);
  ALTER TABLE `HOTEL_CAMP_PLAN_GOTO` MODIFY `DISPLAY_STATUS` tinyint NOT NULL ;

--   *** ------------------------------------
--  *** Table BILLPAYED_YAHOO_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_YAHOO_9XG` ADD CONSTRAINT `BILLPAYED_YAHOO_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CANCEL_RATE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CANCEL_RATE` ADD CONSTRAINT `RESERVE_CANCEL_RATE_PKY` PRIMARY KEY (`RESERVE_CD`, `DAYS`)
  ;
--   *** ------------------------------------
--  *** Table OTA_PLAN_RELATION
--   *** ------------------------------------

  ALTER TABLE `OTA_PLAN_RELATION` ADD CONSTRAINT `OTA_PLAN_RELATION_PKY` PRIMARY KEY (`OTA_PLAN_RELATION_ID`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_HOTEL_VIEW
--   *** ------------------------------------

  ALTER TABLE `RECORD_HOTEL_VIEW` ADD CONSTRAINT `RECORD_HOTEL_VIEW_PKY` PRIMARY KEY (`DATE_YMD`, `HOTEL_CD`, `PAGE_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4_DRAFT
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_BOOK_V4_DRAFT` ADD CONSTRAINT `BR_POINT_BOOK_V4_DRAFT_PKY` PRIMARY KEY (`BR_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_9XG` ADD CONSTRAINT `BILLPAYED_HOTEL_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_STATIONS
--   *** ------------------------------------

  ALTER TABLE `MAST_STATIONS` ADD CONSTRAINT `MAST_STATIONS_PKY` PRIMARY KEY (`STATION_ID`)
  ;
  
--  ALTER TABLE `MAST_STATIONS` MODIFY (`STATION_ID` NOT NULL ENABLE);
  ALTER TABLE `MAST_STATIONS` MODIFY `STATION_ID` varchar(7) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table HOTEL_INSURANCE_WEATHER
--   *** ------------------------------------

  ALTER TABLE `HOTEL_INSURANCE_WEATHER` ADD CONSTRAINT `HOTEL_INSURANCE_WEATHER_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SPOT
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SPOT` ADD CONSTRAINT `HOTEL_SPOT_PKY` PRIMARY KEY (`SPOT_ID`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_GUEST
--   *** ------------------------------------

  ALTER TABLE `RESERVE_GUEST` ADD CONSTRAINT `RESERVE_GUEST_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_05
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_05` ADD CONSTRAINT `LOG_SECURITY_05_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING2
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_RANKING2` ADD CONSTRAINT `ROOM_PLAN_RANKING2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `WDAY`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_RECEIPT
--   *** ------------------------------------

  ALTER TABLE `RESERVE_RECEIPT` ADD CONSTRAINT `RESERVE_RECEIPT_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_REVIEW
--   *** ------------------------------------

  ALTER TABLE `MAST_REVIEW` ADD CONSTRAINT `MAST_REVIEW_PKY` PRIMARY KEY (`REVIEW_ID`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_HOTEL
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_HOTEL` ADD CONSTRAINT `GROUP_BUYING_HOTEL_PKY` PRIMARY KEY (`DEAL_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_REMOVED
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_REMOVED` ADD CONSTRAINT `ROOM_PLAN_REMOVED_PKY` PRIMARY KEY (`ROOM_PLAN_REMOVED_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_CUSTOMER
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_CUSTOMER` ADD CONSTRAINT `CHECKSHEET_CUSTOMER_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_AKAFU
--   *** ------------------------------------

  ALTER TABLE `RESERVE_AKAFU` ADD CONSTRAINT `RESERVE_AKAFU_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_JR
--   *** ------------------------------------

  ALTER TABLE `ROOM_JR` ADD CONSTRAINT `ROOM_JR_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATIONS_SURVEY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATIONS_SURVEY` ADD CONSTRAINT `HOTEL_STATIONS_SURVEY_PKY` PRIMARY KEY (`STATION_ID`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_CREDITCARD
--   *** ------------------------------------

  ALTER TABLE `MEMBER_CREDITCARD` ADD CONSTRAINT `MEMBER_CREDITCARD_PKY` PRIMARY KEY (`PARTNER_CD`, `MEMBER_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table MAST_RECOMMEND
--   *** ------------------------------------

  ALTER TABLE `MAST_RECOMMEND` ADD CONSTRAINT `MAST_RECOMMEND_PKY` PRIMARY KEY (`RECOMMEND_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_BOOK
--   *** ------------------------------------

  ALTER TABLE `PARTNER_BOOK` ADD CONSTRAINT `PARTNER_BOOK_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_BOOK_PARTNER
--   *** ------------------------------------

  ALTER TABLE `PARTNER_BOOK_PARTNER` ADD CONSTRAINT `PARTNER_BOOK_PARTNER_PKY` PRIMARY KEY (`CUSTOMER_ID`, `SITE_CD`, `SITE_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_OAUTH2_MEMBER
--   *** ------------------------------------

  ALTER TABLE `PARTNER_OAUTH2_MEMBER` ADD CONSTRAINT `PARTNER_OAUTH2_MEMBER_PKY` PRIMARY KEY (`CLIENT_ID`, `MEMBER_CD`)
  ;
 
  ALTER TABLE `PARTNER_OAUTH2_MEMBER` ADD CONSTRAINT `PARTNER_OAUTH2_MEMBER_UNQ_01` UNIQUE (`AUTHORIZATION_CD`)
  ;
 
  ALTER TABLE `PARTNER_OAUTH2_MEMBER` ADD CONSTRAINT `PARTNER_OAUTH2_MEMBER_UNQ_02` UNIQUE (`ACCESS_TOKEN`)
  ;
 
  ALTER TABLE `PARTNER_OAUTH2_MEMBER` ADD CONSTRAINT `PARTNER_OAUTH2_MEMBER_UNQ_03` UNIQUE (`CLIENT_ID`, `RELATION_MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_RSV
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_RSV` ADD CONSTRAINT `BILLPAY_RSV_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_YDP
--   *** ------------------------------------

  ALTER TABLE `ROOM_YDP` ADD CONSTRAINT `ROOM_YDP_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_9XG` ADD CONSTRAINT `BILLPAY_HOTEL_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_GRANTS_9XG` ADD CONSTRAINT `CHECKSHEET_HTL_GRANTS_9XG_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ORICOPAYMENT
--   *** ------------------------------------

  ALTER TABLE `ORICOPAYMENT` ADD CONSTRAINT `ORICOPAYMENT_PKY` PRIMARY KEY (`DEMAND_DTM`, `SHOP_NM`, `ORDER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CAMP
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CAMP` ADD CONSTRAINT `RESERVE_CAMP_PKY` PRIMARY KEY (`RESERVE_CD`, `CAMP_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CAMP_GOTO
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CAMP_GOTO` ADD CONSTRAINT `HOTEL_CAMP_GOTO_PKY` PRIMARY KEY (`CAMP_CD`)
  ;
 
--  ALTER TABLE `HOTEL_CAMP_GOTO` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
  ALTER TABLE `HOTEL_CAMP_GOTO` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL ;
 
--  ALTER TABLE `HOTEL_CAMP_GOTO` MODIFY (`CAMP_NM` NOT NULL ENABLE);
   ALTER TABLE `HOTEL_CAMP_GOTO` MODIFY `CAMP_NM` varchar(96) BINARY NOT NULL ;

--  ALTER TABLE `HOTEL_CAMP_GOTO` MODIFY (`DISPLAY_STATUS` NOT NULL ENABLE);
   ALTER TABLE `HOTEL_CAMP_GOTO` MODIFY `DISPLAY_STATUS` tinyint NOT NULL ;

--   *** ------------------------------------
--  *** Table PARTNER_BOOK_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `PARTNER_BOOK_ACCOUNT` ADD CONSTRAINT `PARTNER_BOOK_ACCOUNT_PKY` PRIMARY KEY (`CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_RSV_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_RSV_9XG` ADD CONSTRAINT `BILLPAY_RSV_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_PARTNER_GROUP
--   *** ------------------------------------

  ALTER TABLE `PLAN_PARTNER_GROUP` ADD CONSTRAINT `PLAN_PARTNER_GROUP_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `PARTNER_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_JWEST
--   *** ------------------------------------

  ALTER TABLE `MEMBER_JWEST` ADD CONSTRAINT `MEMBER_JWEST_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_YDP2
--   *** ------------------------------------

  ALTER TABLE `ROOM_YDP2` ADD CONSTRAINT `ROOM_YDP2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_MEMBER_EPARK
--   *** ------------------------------------

  ALTER TABLE `ZAP_MEMBER_EPARK` ADD CONSTRAINT `ZAP_MEMBER_EPARK_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_PLAN_ELEMENT_VALUE
--   *** ------------------------------------

  ALTER TABLE `MAST_PLAN_ELEMENT_VALUE` ADD CONSTRAINT `MAST_PLAN_ELEMENT_VALUE_PKY` PRIMARY KEY (`ELEMENT_ID`, `ELEMENT_VALUE_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE` ADD CONSTRAINT `RESERVE_DISPOSE_PKY` PRIMARY KEY (`DISPOSE_ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4_WK
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_BOOK_V4_WK` ADD CONSTRAINT `BR_POINT_BOOK_V4_WK_PKY` PRIMARY KEY (`BR_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_MATCH_REMOVED
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_MATCH_REMOVED` ADD CONSTRAINT `ROOM_PLAN_MATCH_REMOVED_PKY` PRIMARY KEY (`ROOM_PLAN_MATCH_REMOVED_ID`)
  ;
--   *** ------------------------------------
--  *** Table LANDMARK_BASIC_INFO
--   *** ------------------------------------

  ALTER TABLE `LANDMARK_BASIC_INFO` ADD CONSTRAINT `LANDMARK_BASIC_INFO_PKY` PRIMARY KEY (`LANDMARK_ID`, `ITEM_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_TAX
--   *** ------------------------------------

  ALTER TABLE `MAST_TAX` ADD CONSTRAINT `MAST_TAX_PKY` PRIMARY KEY (`TAX_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_NOTIFY
--   *** ------------------------------------

  ALTER TABLE `LOG_NOTIFY` ADD CONSTRAINT `LOG_NOTIFY_PKY` PRIMARY KEY (`REQUEST_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_TYPE_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN_TYPE_GRANTS` ADD CONSTRAINT `BILLPAYED_PTN_TYPE_GRANTS_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_POWER_DEV
--   *** ------------------------------------

  ALTER TABLE `RESERVE_POWER_DEV` ADD CONSTRAINT `RESERVE_POWER_DEV_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_GRANTS
--   *** ------------------------------------

  ALTER TABLE `WELFARE_GRANTS` ADD CONSTRAINT `WELFARE_GRANTS_PKY` PRIMARY KEY (`WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_WARDZONE_DETAIL
--   *** ------------------------------------

  ALTER TABLE `MAST_WARDZONE_DETAIL` ADD CONSTRAINT `MAST_WARDZONE_DETAIL_PKY` PRIMARY KEY (`WARDZONE_ID`, `WARD_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_GROUP
--   *** ------------------------------------

  ALTER TABLE `PARTNER_GROUP` ADD CONSTRAINT `PARTNER_GROUP_PKY` PRIMARY KEY (`PARTNER_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_BOOK_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_BOOK_9XG` ADD CONSTRAINT `BILLPAY_BOOK_9XG_PKY` PRIMARY KEY (`BILLPAY_CD`, `BILLPAY_BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table YDP_BASE_TIME
--   *** ------------------------------------

  ALTER TABLE `YDP_BASE_TIME` ADD CONSTRAINT `YDP_BASE_TIME_PKY` PRIMARY KEY (`PARTNER_CD`, `COOPERATION_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_CREDIT
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_CREDIT` ADD CONSTRAINT `CHECKSHEET_HOTEL_CREDIT_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE_REMIND
--   *** ------------------------------------

  ALTER TABLE `CHARGE_REMIND` ADD CONSTRAINT `CHARGE_REMIND_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `CAPACITY`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_TRACE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_TRACE` ADD CONSTRAINT `RESERVE_TRACE_PKY` PRIMARY KEY (`TRANSACTION_CD`, `TYPE`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_OPENID_MEMBER
--   *** ------------------------------------

  ALTER TABLE `PARTNER_OPENID_MEMBER` ADD CONSTRAINT `PARTNER_OPENID_MEMBER_PKY` PRIMARY KEY (`CLIENT_ID`, `MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_VR_HOTEL_CATEGORY
--   *** ------------------------------------

  ALTER TABLE `MAST_VR_HOTEL_CATEGORY` ADD CONSTRAINT `MAST_VR_HOTEL_CATEGORY_PKY` PRIMARY KEY (`CATEGORY_CD`)
  ;
--   *** ------------------------------------
--  *** Table GIFT_ORDER
--   *** ------------------------------------

  ALTER TABLE `GIFT_ORDER` ADD CONSTRAINT `GIFT_ORDER_PKY` PRIMARY KEY (`GIFT_ORDER_CD`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY_STAY
--   *** ------------------------------------

  ALTER TABLE `NOTIFY_STAY` ADD CONSTRAINT `NOTIFY_STAY_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_8
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_8` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_8_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_HOTEL_ELEMENT
--   *** ------------------------------------

  ALTER TABLE `MAST_HOTEL_ELEMENT` ADD CONSTRAINT `MAST_HOTEL_ELEMENT_PKY` PRIMARY KEY (`ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_MATCH
--   *** ------------------------------------

  ALTER TABLE `WELFARE_MATCH` ADD CONSTRAINT `WELFARE_MATCH_PKY` PRIMARY KEY (`WELFARE_MATCH_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_EXTEND
--   *** ------------------------------------

  ALTER TABLE `LOG_EXTEND` ADD CONSTRAINT `LOG_EXTEND_PKY` PRIMARY KEY (`HOTEL_CD`, `AFTER_YM`)
  ;
--   *** ------------------------------------
--  *** Table REPORT_LEAD_TIME
--   *** ------------------------------------

  ALTER TABLE `REPORT_LEAD_TIME` ADD CONSTRAINT `REPORT_LEAD_TIME_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YMD`, `LEAD_DAY`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_MEDIA
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_MEDIA` ADD CONSTRAINT `ROOM_PLAN_MEDIA_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_10
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_10` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_10_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SERVICE
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SERVICE` ADD CONSTRAINT `HOTEL_SERVICE_PKY` PRIMARY KEY (`HOTEL_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_MEDIA
--   *** ------------------------------------

  ALTER TABLE `PLAN_MEDIA` ADD CONSTRAINT `PLAN_MEDIA_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_MATCH
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_MATCH` ADD CONSTRAINT `ROOM_PLAN_MATCH_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table REPORT_PLAN2
--   *** ------------------------------------

  ALTER TABLE `REPORT_PLAN2` ADD CONSTRAINT `REPORT_PLAN2_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YMD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table MY_HOTEL_RESERVED
--   *** ------------------------------------

  ALTER TABLE `MY_HOTEL_RESERVED` ADD CONSTRAINT `MY_HOTEL_RESERVED_PKY` PRIMARY KEY (`MEMBER_CD`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_EARLY
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_EARLY` ADD CONSTRAINT `ROOM_CHARGE_EARLY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_GRANTS
--   *** ------------------------------------

  ALTER TABLE `PLAN_GRANTS` ADD CONSTRAINT `PLAN_GRANTS_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_SERVICE
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_SERVICE` ADD CONSTRAINT `BR_POINT_SERVICE_PKY` PRIMARY KEY (`SERVICE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_4
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_4` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_4_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_06
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_06` ADD CONSTRAINT `LOG_SECURITY_06_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SUPERVISOR_HOTEL
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SUPERVISOR_HOTEL` ADD CONSTRAINT `HOTEL_SUPERVISOR_HOTEL_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE_INITIAL
--   *** ------------------------------------

  ALTER TABLE `CHARGE_INITIAL` ADD CONSTRAINT `CHARGE_INITIAL_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `CAPACITY`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_FREE_RELATION
--   *** ------------------------------------

  ALTER TABLE `MEMBER_FREE_RELATION` ADD CONSTRAINT `MEMBER_FREE_RELATION_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
 
  ALTER TABLE `MEMBER_FREE_RELATION` ADD CONSTRAINT `MEMBER_FREE_RELATION_UNQ_01` UNIQUE (`MEMBER_FREE_CD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_BOUNCED_MAIL
--   *** ------------------------------------

  ALTER TABLE `LOG_BOUNCED_MAIL` ADD CONSTRAINT `LOG_BOUNCED_MAIL_PKY` PRIMARY KEY (`LOG_BOUNCED_MAIL_ID`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_CARD
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_CARD` ADD CONSTRAINT `GROUP_BUYING_CARD_PKY` PRIMARY KEY (`ORDER_ID`)
  ;
 
--  ALTER TABLE `GROUP_BUYING_CARD` MODIFY (`CARD_NO` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_CARD` MODIFY `CARD_NO` varchar(32) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_CARD` MODIFY (`CARD_LIMIT_YM` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_CARD` MODIFY `CARD_LIMIT_YM` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_CARD` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_CARD` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_CARD` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_CARD` MODIFY `ENTRY_TS` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_CARD` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_CARD` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_CARD` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_CARD` MODIFY `MODIFY_TS` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table REPORT_PREF
--   *** ------------------------------------

  ALTER TABLE `REPORT_PREF` ADD CONSTRAINT `REPORT_PREF_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YMD`, `PREF_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN` ADD CONSTRAINT `BILLPAY_PTN_PKY` PRIMARY KEY (`FEE_TYPE`, `SITE_CD`, `BILLPAY_YM`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_LOWEST
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_LOWEST` ADD CONSTRAINT `ROOM_PLAN_LOWEST_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_CD`, `ROOM_CD`, `CHARGE_CONDITION`)
  ;
--   *** ------------------------------------
--  *** Table LOG_AUTHORI
--   *** ------------------------------------

  ALTER TABLE `LOG_AUTHORI` ADD CONSTRAINT `LOG_AUTHORI_PKY` PRIMARY KEY (`RESERVE_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_10
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_10` ADD CONSTRAINT `LOG_SECURITY_10_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_CUSTOMER_9XG` ADD CONSTRAINT `BILLPAY_CUSTOMER_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEDIA_ORG
--   *** ------------------------------------

  ALTER TABLE `MEDIA_ORG` ADD CONSTRAINT `MEDIA_ORG_PKY` PRIMARY KEY (`HOTEL_CD`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table NTA_STAFF_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `NTA_STAFF_ACCOUNT` ADD CONSTRAINT `NTA_STAFF_ACCOUNT_PKY` PRIMARY KEY (`NTA_STAFF_ID`)
  ;
--   *** ------------------------------------
--  *** Table POINT_BONUS
--   *** ------------------------------------

  ALTER TABLE `POINT_BONUS` ADD CONSTRAINT `POINT_BONUS_PKY` PRIMARY KEY (`POINT_BONUS_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_GROUP_JOIN
--   *** ------------------------------------

  ALTER TABLE `PARTNER_GROUP_JOIN` ADD CONSTRAINT `PARTNER_GROUP_JOIN_PKY` PRIMARY KEY (`PARTNER_GROUP_ID`, `PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_MODIFY_RIZAPULI
--   *** ------------------------------------

  ALTER TABLE `RESERVE_MODIFY_RIZAPULI` ADD CONSTRAINT `RESERVE_MODIFY_RIZAPULI_PKY` PRIMARY KEY (`RESERVE_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION_PLAN_TEMP
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_PLAN_TEMP` ADD CONSTRAINT `MIGRATION_PLAN_TEMP_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_POINT
--   *** ------------------------------------

  ALTER TABLE `MEMBER_POINT` ADD CONSTRAINT `MEMBER_POINT_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_AUTHORI
--   *** ------------------------------------

  ALTER TABLE `RESERVE_AUTHORI` ADD CONSTRAINT `RESERVE_AUTHORI_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION_MEDIA
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_MEDIA` ADD CONSTRAINT `MIGRATION_MEDIA_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table LOG_CREDIT
--   *** ------------------------------------

  ALTER TABLE `LOG_CREDIT` ADD CONSTRAINT `LOG_CREDIT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table LOG_ALERT_STOCK
--   *** ------------------------------------

  ALTER TABLE `LOG_ALERT_STOCK` ADD CONSTRAINT `LOG_ALERT_STOCK_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `DATE_YMD`, `RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM2
--   *** ------------------------------------

  ALTER TABLE `ROOM2` ADD CONSTRAINT `ROOM2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_ACCEPT_HISTORY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_ACCEPT_HISTORY` ADD CONSTRAINT `HOTEL_ACCEPT_HISTORY_PKY` PRIMARY KEY (`HOTEL_ACCEPT_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_POOL
--   *** ------------------------------------

  ALTER TABLE `PARTNER_POOL` ADD CONSTRAINT `PARTNER_POOL_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
 
--  ALTER TABLE `PARTNER_POOL` MODIFY (`PASSWORD` NOT NULL ENABLE);
  ALTER TABLE `PARTNER_POOL` MODIFY `PASSWORD` varchar(64) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table PARTNER_OPENID
--   *** ------------------------------------

  ALTER TABLE `PARTNER_OPENID` ADD CONSTRAINT `PARTNER_OPENID_PKY` PRIMARY KEY (`CLIENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_SALES
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_SALES` ADD CONSTRAINT `BILLPAYED_SALES_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `SITE_CD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE_CONDITION
--   *** ------------------------------------

  ALTER TABLE `CHARGE_CONDITION` ADD CONSTRAINT `CHARGE_CONDITION_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `ROOM_ID`, `CAPACITY`, `LOGIN_CONDITION`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE` ADD CONSTRAINT `ROOM_CHARGE_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_SECTION
--   *** ------------------------------------

  ALTER TABLE `PARTNER_SECTION` ADD CONSTRAINT `PARTNER_SECTION_PKY` PRIMARY KEY (`PARTNER_CD`, `SECTION_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN2
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CAMP_PLAN2` ADD CONSTRAINT `HOTEL_CAMP_PLAN2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `CAMP_CD`)
  ;
--   *** ------------------------------------
--  *** Table DENY_LIST_RETURN
--   *** ------------------------------------

  ALTER TABLE `DENY_LIST_RETURN` ADD CONSTRAINT `DENY_LIST_RETURN_PKY` PRIMARY KEY (`DENY_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_VERIFY_YDP
--   *** ------------------------------------

  ALTER TABLE `RESERVE_VERIFY_YDP` ADD CONSTRAINT `RESERVE_VERIFY_YDP_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE_EARLY
--   *** ------------------------------------

  ALTER TABLE `CHARGE_EARLY` ADD CONSTRAINT `CHARGE_EARLY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `CAPACITY`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_RSV
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_RSV` ADD CONSTRAINT `RESERVE_DISPOSE_RSV_PKY` PRIMARY KEY (`DISPOSE_RSV_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER
--   *** ------------------------------------

  ALTER TABLE `MEMBER` ADD CONSTRAINT `MEMBER_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN
--   *** ------------------------------------

  ALTER TABLE `PLAN` ADD CONSTRAINT `PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_GRANTS
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_GRANTS` ADD CONSTRAINT `CHECKSHEET_GRANTS_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HR_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HR_GRANTS_9XG` ADD CONSTRAINT `BILLPAY_HR_GRANTS_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table FACTORING_ZENGIN_REQUEST
--   *** ------------------------------------

  ALTER TABLE `FACTORING_ZENGIN_REQUEST` ADD CONSTRAINT `FACTORING_ZENGIN_REQUEST_PKY` PRIMARY KEY (`FACTORING_CD`)
  ;
--   *** ------------------------------------
--  *** Table MONEY_SCHEDULE
--   *** ------------------------------------

  ALTER TABLE `MONEY_SCHEDULE` ADD CONSTRAINT `MONEY_SCHEDULE_PKY` PRIMARY KEY (`YM`, `MONEY_SCHEDULE_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_CALC2
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_RANKING_CALC2` ADD CONSTRAINT `ROOM_PLAN_RANKING_CALC2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `WDAY`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YAHOO
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YAHOO` ADD CONSTRAINT `HOTEL_YAHOO_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_CREDIT
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_CREDIT` ADD CONSTRAINT `BILLPAY_HOTEL_CREDIT_PKY` PRIMARY KEY (`HOTEL_CD`, `BILLPAY_YM`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_FEE_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_FEE_9XG` ADD CONSTRAINT `BILLPAY_HOTEL_FEE_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_STOCK
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_STOCK` ADD CONSTRAINT `BILLPAY_PTN_STOCK_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `STOCK_RATE`)
  ;
--   *** ------------------------------------
--  *** Table MAST_PLAN_ELEMENT
--   *** ------------------------------------

  ALTER TABLE `MAST_PLAN_ELEMENT` ADD CONSTRAINT `MAST_PLAN_ELEMENT_PKY` PRIMARY KEY (`ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_CUSTOMER
--   *** ------------------------------------

  ALTER TABLE `LOG_CUSTOMER` ADD CONSTRAINT `LOG_CUSTOMER_PKY` PRIMARY KEY (`CUSTOMER_ID`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table ALERT_MAIL_HOTEL
--   *** ------------------------------------

  ALTER TABLE `ALERT_MAIL_HOTEL` ADD CONSTRAINT `ALERT_MAIL_HOTEL_PKY` PRIMARY KEY (`HOTEL_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table LOG_GROUP_BUYING
--   *** ------------------------------------

  ALTER TABLE `LOG_GROUP_BUYING` ADD CONSTRAINT `LOG_GROUP_BUYING_PKY` PRIMARY KEY (`ORDER_ID`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table MAST_CARD
--   *** ------------------------------------

  ALTER TABLE `MAST_CARD` ADD CONSTRAINT `MAST_CARD_PKY` PRIMARY KEY (`CARD_ID`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_RAKUJAN
--   *** ------------------------------------

  ALTER TABLE `ZAP_RAKUJAN` ADD CONSTRAINT `ZAP_RAKUJAN_PKY` PRIMARY KEY (`RANDOM_CD`, `CHECK_POINT`)
  ;
--   *** ------------------------------------
--  *** Table MAST_VR_ITEM
--   *** ------------------------------------

  ALTER TABLE `MAST_VR_ITEM` ADD CONSTRAINT `MAST_VR_ITEM_PKY` PRIMARY KEY (`ITEM_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_EARLY2
--   *** ------------------------------------

-- 存在しない  ALTER TABLE `ROOM_CHARGE_EARLY2` ADD CONSTRAINT `ROOM_CHARGE_EARLY2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_PLAN_POINT
--   *** ------------------------------------

  ALTER TABLE `RESERVE_PLAN_POINT` ADD CONSTRAINT `RESERVE_PLAN_POINT_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_PRIORITY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_PRIORITY` ADD CONSTRAINT `HOTEL_PRIORITY_PKY` PRIMARY KEY (`CTRL_NO`)
  ;
--   *** ------------------------------------
--  *** Table MAST_REGION
--   *** ------------------------------------

  ALTER TABLE `MAST_REGION` ADD CONSTRAINT `MAST_REGION_PKY` PRIMARY KEY (`REGION_ID`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_ROOM_PLAN
--   *** ------------------------------------

  ALTER TABLE `ZAP_ROOM_PLAN` ADD CONSTRAINT `ZAP_ROOM_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table ENQUETE_6315
--   *** ------------------------------------

  ALTER TABLE `ENQUETE_6315` ADD CONSTRAINT `ENQUETE_6315_PKY` PRIMARY KEY (`ID`, `MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_AKF_RELATION
--   *** ------------------------------------

--  ALTER TABLE `ROOM_AKF_RELATION` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
   ALTER TABLE `ROOM_AKF_RELATION` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `ROOM_AKF_RELATION` MODIFY (`ROOM_ID` NOT NULL ENABLE);
   ALTER TABLE `ROOM_AKF_RELATION` MODIFY `ROOM_ID` varchar(10) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table MAST_STATION_JUNCTION
--   *** ------------------------------------

  ALTER TABLE `MAST_STATION_JUNCTION` ADD CONSTRAINT `MAST_STATION_JUNCTION_PKY` PRIMARY KEY (`JUNCTION_ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_GIFT_TICKET
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_GIFT_TICKET` ADD CONSTRAINT `BR_POINT_GIFT_TICKET_PKY` PRIMARY KEY (`BR_POINT_GIFT_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_1
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_1` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_1_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_PLUS_HOTEL
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_PLUS_HOTEL` ADD CONSTRAINT `BR_POINT_PLUS_HOTEL_PKY` PRIMARY KEY (`POINT_PLUS_ID`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_ROOM_PLAN_CHARGE
--   *** ------------------------------------

  ALTER TABLE `ZAP_ROOM_PLAN_CHARGE` ADD CONSTRAINT `ZAP_ROOM_PLAN_CHARGE_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_HOTEL
--   *** ------------------------------------

  ALTER TABLE `RESERVE_HOTEL` ADD CONSTRAINT `RESERVE_HOTEL_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_PLAN_INFO
--   *** ------------------------------------

  ALTER TABLE `RESERVE_PLAN_INFO` ADD CONSTRAINT `RESERVE_PLAN_INFO_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_VR_ROOM_TYPE
--   *** ------------------------------------

  ALTER TABLE `MAST_VR_ROOM_TYPE` ADD CONSTRAINT `MAST_VR_ROOM_TYPE_PKY` PRIMARY KEY (`CATEGORY_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_MESSAGE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_MESSAGE` ADD CONSTRAINT `RESERVE_MESSAGE_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_POINT_20170101
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_POINT_20170101` ADD CONSTRAINT `ROOM_PLAN_POINT_20170101_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_YAHOO_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_YAHOO_9XG` ADD CONSTRAINT `BILLPAY_YAHOO_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_GRANTS
--   *** ------------------------------------

  ALTER TABLE `RESERVE_GRANTS` ADD CONSTRAINT `RESERVE_GRANTS_PKY` PRIMARY KEY (`WELFARE_GRANTS_HISTORY_ID`, `ORDER_CD`, `RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_TOUR
--   *** ------------------------------------

  ALTER TABLE `RESERVE_TOUR` ADD CONSTRAINT `RESERVE_TOUR_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_TYPE_20170101
--   *** ------------------------------------

  ALTER TABLE `HOTEL_TYPE_20170101` ADD CONSTRAINT `HOTEL_TYPE_20170101_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_GRANTS_9XG` ADD CONSTRAINT `CHECKSHEET_GRANTS_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN2_GOTO
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CAMP_PLAN2_GOTO` ADD CONSTRAINT `HOTEL_CAMP_PLAN2_GOTO_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `CAMP_CD`)
  ;
 
--  ALTER TABLE `HOTEL_CAMP_PLAN2_GOTO` MODIFY (`DISPLAY_STATUS` NOT NULL ENABLE);
  ALTER TABLE `HOTEL_CAMP_PLAN2_GOTO` MODIFY `DISPLAY_STATUS` tinyint NOT NULL ;

--   *** ------------------------------------
--  *** Table BILLPAYED_FEE_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_FEE_9XG` ADD CONSTRAINT `BILLPAYED_FEE_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_LAYOUT
--   *** ------------------------------------

  ALTER TABLE `PARTNER_LAYOUT` ADD CONSTRAINT `PARTNER_LAYOUT_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_CANCEL
--   *** ------------------------------------

  ALTER TABLE `LOG_CANCEL` ADD CONSTRAINT `LOG_CANCEL_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `CANCEL_DTM`)
  ;
--   *** ------------------------------------
--  *** Table CARD_PAYMENT_GBY
--   *** ------------------------------------

  ALTER TABLE `CARD_PAYMENT_GBY` ADD CONSTRAINT `CARD_PAYMENT_GBY_PKY` PRIMARY KEY (`CARD_PAYMENT_ID`)
  ;
 
  ALTER TABLE `CARD_PAYMENT_GBY` ADD CONSTRAINT `CARD_PAYMENT_GBY_UNQ_01` UNIQUE (`PAYMENT_SYSTEM`, `DEMAND_DTM`, `ORDER_ID`)
  ;
--   *** ------------------------------------
--  *** Table AFFILIATER
--   *** ------------------------------------

  ALTER TABLE `AFFILIATER` ADD CONSTRAINT `AFFILIATER_PKY` PRIMARY KEY (`AFFILIATER_CD`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_HOLD_KEYWORDS
--   *** ------------------------------------

  ALTER TABLE `PLAN_HOLD_KEYWORDS` ADD CONSTRAINT `PLAN_HOLD_KEYWORDS_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `KEYWORD_ID`)
  ;
--   *** ------------------------------------
--  *** Table SECURE_LICENSE
--   *** ------------------------------------

  ALTER TABLE `SECURE_LICENSE` ADD CONSTRAINT `SECURE_LICENSE_PKY` PRIMARY KEY (`LICENSE_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE2
--   *** ------------------------------------

-- 存在しない  ALTER TABLE `CHARGE2` ADD CONSTRAINT `CHARGE2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `CAPACITY`, `DATE_YMD`)  ;

--   *** ------------------------------------
--  *** Table HOTEL_REVIEW
--   *** ------------------------------------

	  ALTER TABLE `HOTEL_REVIEW` ADD CONSTRAINT `HOTEL_REVIEW_PKY` PRIMARY KEY (`HOTEL_CD`, `REVIEW_TYPE`, `REVIEW_ID`) ;

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
 
--  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY (`REC_TYPE` NOT NULL ENABLE);
   ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY `REC_TYPE` tinyint NOT NULL ;

--  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
   ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY (`ROOM_CD` NOT NULL ENABLE);
   ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY `ROOM_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY (`PLAN_CD` NOT NULL ENABLE);
   ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY `PLAN_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY (`DATE_YMD` NOT NULL ENABLE);
   ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP` MODIFY `DATE_YMD` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table MIGRATION_SPEC
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_SPEC` ADD CONSTRAINT `MIGRATION_SPEC_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATION
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATION` ADD CONSTRAINT `HOTEL_STATION_PKY` PRIMARY KEY (`HOTEL_CD`, `STATION_ID`, `TRAFFIC_WAY`)
  ;
--   *** ------------------------------------
--  *** Table STAFF_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `STAFF_ACCOUNT` ADD CONSTRAINT `STAFF_ACCOUNT_PKY` PRIMARY KEY (`STAFF_ID`)
  ;
--   *** ------------------------------------
--  *** Table AKAFU_CANCEL_QUEUE
--   *** ------------------------------------

  ALTER TABLE `AKAFU_CANCEL_QUEUE` ADD CONSTRAINT `AKAFU_CANCEL_QUEUE_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_FEE_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_FEE_9XG` ADD CONSTRAINT `BILLPAYED_HOTEL_FEE_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_SALES
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_SALES` ADD CONSTRAINT `BILLPAY_PTN_SALES_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `SALES_RATE`)
  ;
--   *** ------------------------------------
--  *** Table REPORT_PLAN
--   *** ------------------------------------

  ALTER TABLE `REPORT_PLAN` ADD CONSTRAINT `REPORT_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YMD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_FIX_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_FIX_9XG` ADD CONSTRAINT `BILLPAY_FIX_9XG_PKY` PRIMARY KEY (`HOTEL_CD`, `FIX_STATUS`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING_BASE2
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_RANKING_BASE2` ADD CONSTRAINT `ROOM_PLAN_RANKING_BASE2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table YAHOO_POINT_PLUS_INFO
--   *** ------------------------------------

  ALTER TABLE `YAHOO_POINT_PLUS_INFO` ADD CONSTRAINT `YAHOO_POINT_PLUS_INFO_PKY` PRIMARY KEY (`POINT_PLUS_ID`)
  ;
--   *** ------------------------------------
--  *** Table VERIFY_YAHOO_POINT
--   *** ------------------------------------

  ALTER TABLE `VERIFY_YAHOO_POINT` ADD CONSTRAINT `VERIFY_YAHOO_POINT_PKY` PRIMARY KEY (`VERIFY_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_COUNT2
--   *** ------------------------------------

  ALTER TABLE `ROOM_COUNT2` ADD CONSTRAINT `ROOM_COUNT2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_INSURANCE_WEATHER
--   *** ------------------------------------

  ALTER TABLE `RESERVE_INSURANCE_WEATHER` ADD CONSTRAINT `RESERVE_INSURANCE_WEATHER_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_PREF
--   *** ------------------------------------

  ALTER TABLE `MAST_PREF` ADD CONSTRAINT `MAST_PREF_PKY` PRIMARY KEY (`PREF_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_9XG
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_9XG` ADD CONSTRAINT `RESERVE_DISPOSE_9XG_PKY` PRIMARY KEY (`DISPOSE_ID`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_VARIOUS
--   *** ------------------------------------

  ALTER TABLE `RECORD_VARIOUS` ADD CONSTRAINT `RECORD_VARIOUS_PKY` PRIMARY KEY (`DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table TEMP_HOTEL_VS_MTN
--   *** ------------------------------------

  ALTER TABLE `TEMP_HOTEL_VS_MTN` ADD CONSTRAINT `TEMP_HOTEL_VS_MTN_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table TEMP_GROUP_RESERVE
--   *** ------------------------------------

  ALTER TABLE `TEMP_GROUP_RESERVE` ADD CONSTRAINT `TEMP_GROUP_RESERVE_PKY` PRIMARY KEY (`HOTEL_CD`, `ORDER_NO`, `REPLY_NO`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_CSTMR
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_CSTMR` ADD CONSTRAINT `BILLPAY_PTN_CSTMR_PKY` PRIMARY KEY (`BILLPAY_YM`, `CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_RSV
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_RSV` ADD CONSTRAINT `BILLPAYED_HOTEL_RSV_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AREA_SURVEY
--   *** ------------------------------------

  ALTER TABLE `MAST_AREA_SURVEY` ADD CONSTRAINT `MAST_AREA_SURVEY_PKY` PRIMARY KEY (`SURVEY_CLASS`, `SURVEY_CD`)
  ;
--   *** ------------------------------------
--  *** Table GIFT
--   *** ------------------------------------

  ALTER TABLE `GIFT` ADD CONSTRAINT `GIFT_PKY` PRIMARY KEY (`GIFT_ID`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_OP
--   *** ------------------------------------

  ALTER TABLE `WELFARE_OP` ADD CONSTRAINT `WELFARE_OP_PKY` PRIMARY KEY (`WELFARE_OP_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_BATH_TAX
--   *** ------------------------------------

  ALTER TABLE `HOTEL_BATH_TAX` ADD CONSTRAINT `HOTEL_BATH_TAX_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table SPOT
--   *** ------------------------------------

  ALTER TABLE `SPOT` ADD CONSTRAINT `SPOT_PKY` PRIMARY KEY (`SPOT_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE
--   *** ------------------------------------

  ALTER TABLE `CHARGE` ADD CONSTRAINT `CHARGE_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `CAPACITY`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_DETAIL
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_DETAIL` ADD CONSTRAINT `GROUP_BUYING_DETAIL_PKY` PRIMARY KEY (`DEAL_ID`)
  ;
 
--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`DEAL_NM` NOT NULL ENABLE);
  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `DEAL_NM` varchar(768) BINARY NOT NULL ;
 
--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`USUAL_CHARGE` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `USUAL_CHARGE` int NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`DEAL_CHARGE` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `DEAL_CHARGE` int NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `ENTRY_TS` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_DETAIL` MODIFY `MODIFY_TS` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table HOTEL_SURVEY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SURVEY` ADD CONSTRAINT `HOTEL_SURVEY_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table DENY_LIST
--   *** ------------------------------------

  ALTER TABLE `DENY_LIST` ADD CONSTRAINT `DENY_LIST_PKY` PRIMARY KEY (`DENY_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_JWEST
--   *** ------------------------------------

  ALTER TABLE `RESERVE_JWEST` ADD CONSTRAINT `RESERVE_JWEST_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_MOBILE_MAIL
--   *** ------------------------------------

  ALTER TABLE `MEMBER_MOBILE_MAIL` ADD CONSTRAINT `MEMBER_MOBILE_MAIL_PKY` PRIMARY KEY (`MEMBER_CD`, `SEND_MAIL_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_MEDIA
--   *** ------------------------------------

  ALTER TABLE `HOTEL_MEDIA` ADD CONSTRAINT `HOTEL_MEDIA_PKY` PRIMARY KEY (`HOTEL_CD`, `TYPE`, `MEDIA_NO`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_PERSON
--   *** ------------------------------------

  ALTER TABLE `HOTEL_PERSON` ADD CONSTRAINT `HOTEL_PERSON_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_YAHOO_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_YAHOO_9XG` ADD CONSTRAINT `BILLPAY_HOTEL_YAHOO_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table SERVICE_VOTE_ANSWER
--   *** ------------------------------------

  ALTER TABLE `SERVICE_VOTE_ANSWER` ADD CONSTRAINT `SERVICE_VOTE_ANSWER_PKY` PRIMARY KEY (`VOTE_CD`, `MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table AREA_YDP_MATCH
--   *** ------------------------------------

  ALTER TABLE `AREA_YDP_MATCH` ADD CONSTRAINT `AREA_YDP_MATCH_PKY` PRIMARY KEY (`YDP_AREA_CD`)
  ;
--   *** ------------------------------------
--  *** Table MY_SEARCH_SETTING
--   *** ------------------------------------

  ALTER TABLE `MY_SEARCH_SETTING` ADD CONSTRAINT `MY_SEARCH_SETTING_PKY` PRIMARY KEY (`MY_SEARCH_SETTING_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SUPERVISOR
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SUPERVISOR` ADD CONSTRAINT `HOTEL_SUPERVISOR_PKY` PRIMARY KEY (`SUPERVISOR_CD`)
  ;
--   *** ------------------------------------
--  *** Table BROADCAST_MESSAGE
--   *** ------------------------------------

  ALTER TABLE `BROADCAST_MESSAGE` ADD CONSTRAINT `BROADCAST_MESSAGE_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table PREVENT_ACCESSES
--   *** ------------------------------------

  ALTER TABLE `PREVENT_ACCESSES` ADD CONSTRAINT `PREVENT_ACCESSES_PKY` PRIMARY KEY (`ACCOUNT_KEY`, `URI`)
  ;
--   *** ------------------------------------
--  *** Table CARD_PAYMENT_POWER
--   *** ------------------------------------

  ALTER TABLE `CARD_PAYMENT_POWER` ADD CONSTRAINT `CARD_PAYMENT_POWER_PKY` PRIMARY KEY (`CARD_PAYMENT_ID`)
  ;
 
  ALTER TABLE `CARD_PAYMENT_POWER` ADD CONSTRAINT `CARD_PAYMENT_POWER_UNQ_01` UNIQUE (`PAYMENT_SYSTEM`, `DEMAND_DTM`, `RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YDP2
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDP2` ADD CONSTRAINT `HOTEL_YDP2_PKY` PRIMARY KEY (`YDP_HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_GRANTS` ADD CONSTRAINT `BILLPAY_HOTEL_GRANTS_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_LEGACY
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_LEGACY` ADD CONSTRAINT `ROOM_PLAN_LEGACY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_CONTROL
--   *** ------------------------------------

  ALTER TABLE `PARTNER_CONTROL` ADD CONSTRAINT `PARTNER_CONTROL_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_KEYWORD_EXAMPLE
--   *** ------------------------------------

  ALTER TABLE `PARTNER_KEYWORD_EXAMPLE` ADD CONSTRAINT `PARTNER_KEYWORD_EXAMPLE_PKY` PRIMARY KEY (`PARTNER_CD`, `LAYOUT_TYPE`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_MATERIAL
--   *** ------------------------------------

  ALTER TABLE `RESERVE_MATERIAL` ADD CONSTRAINT `RESERVE_MATERIAL_PKY` PRIMARY KEY (`PARTNER_CD`, `CCD`, `ROOM`)
  ;
--   *** ------------------------------------
--  *** Table VOICE_STAY
--   *** ------------------------------------

  ALTER TABLE `VOICE_STAY` ADD CONSTRAINT `VOICE_STAY_PKY` PRIMARY KEY (`VOICE_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_CREDIT
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_CREDIT` ADD CONSTRAINT `CHECKSHEET_CREDIT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_BOOK
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_BOOK` ADD CONSTRAINT `BILLPAY_BOOK_PKY` PRIMARY KEY (`BILLPAY_CD`, `BILLPAY_BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_POOL2
--   *** ------------------------------------

  ALTER TABLE `PARTNER_POOL2` ADD CONSTRAINT `PARTNER_POOL2_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_FEE
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_FEE` ADD CONSTRAINT `CHECKSHEET_HOTEL_FEE_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_FEE
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_FEE` ADD CONSTRAINT `BILLPAY_FEE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_SPEC
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_SPEC` ADD CONSTRAINT `ROOM_PLAN_SPEC_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table WK_ROOM_PLAN_SALES
--   *** ------------------------------------

  ALTER TABLE `WK_ROOM_PLAN_SALES` ADD CONSTRAINT `WK_ROOM_PLAN_SALES_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table YDP_ITEM
--   *** ------------------------------------

  ALTER TABLE `YDP_ITEM` ADD CONSTRAINT `YDP_ITEM_PKY` PRIMARY KEY (`COOPERATION_CD`, `ITEM_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_GOTO_EXCEL
--   *** ------------------------------------

  ALTER TABLE `HOTEL_GOTO_EXCEL` ADD CONSTRAINT `HOTEL_GOTO_EXCEL_PKY` PRIMARY KEY (`HOTEL_GOTO_EXCEL_ID`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY
--   *** ------------------------------------

  ALTER TABLE `NOTIFY` ADD CONSTRAINT `NOTIFY_PKY` PRIMARY KEY (`NOTIFY_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_ADVERT_2009000400
--   *** ------------------------------------

  ALTER TABLE `HOTEL_ADVERT_2009000400` ADD CONSTRAINT `HOTEL_ADVERT_2009000400_PKY` PRIMARY KEY (`RECORD_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_RSV
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_RSV` ADD CONSTRAINT `CHECKSHEET_HOTEL_RSV_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_YAHOO
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_YAHOO` ADD CONSTRAINT `BILLPAY_HOTEL_YAHOO_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_PLAN_JR
--   *** ------------------------------------

  ALTER TABLE `RESERVE_PLAN_JR` ADD CONSTRAINT `RESERVE_PLAN_JR_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_SENDING_MAIL
--   *** ------------------------------------

  ALTER TABLE `MEMBER_SENDING_MAIL` ADD CONSTRAINT `MEMBER_SENDING_MAIL_PKY` PRIMARY KEY (`MEMBER_CD`, `SEND_MAIL_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table MY_HOTEL_LIST
--   *** ------------------------------------

  ALTER TABLE `MY_HOTEL_LIST` ADD CONSTRAINT `MY_HOTEL_LIST_PKY` PRIMARY KEY (`MEMBER_CD`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_COUNT_REMOVED
--   *** ------------------------------------

  ALTER TABLE `ROOM_COUNT_REMOVED` ADD CONSTRAINT `ROOM_COUNT_REMOVED_PKY` PRIMARY KEY (`ROOM_COUNT_REMOVED_ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_PLUS_PLAN
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_PLUS_PLAN` ADD CONSTRAINT `BR_POINT_PLUS_PLAN_PKY` PRIMARY KEY (`POINT_PLUS_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_7
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_7` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_7_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_MOBILE_RESERVE
--   *** ------------------------------------

  ALTER TABLE `RECORD_MOBILE_RESERVE` ADD CONSTRAINT `RECORD_MOBILE_RESERVE_PKY` PRIMARY KEY (`DATE_YMD`, `CAREER_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_RESERVE2
--   *** ------------------------------------

  ALTER TABLE `RECORD_RESERVE2` ADD CONSTRAINT `RECORD_RESERVE2_PKY` PRIMARY KEY (`DATE_YMD`, `RECORD_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_PLAN_GRANTS
--   *** ------------------------------------

  ALTER TABLE `RESERVE_PLAN_GRANTS` ADD CONSTRAINT `RESERVE_PLAN_GRANTS_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_LANDMARK_BASIC
--   *** ------------------------------------

  ALTER TABLE `MAST_LANDMARK_BASIC` ADD CONSTRAINT `MAST_LANDMARK_BASIC_PKY` PRIMARY KEY (`ITEM_ID`)
  ;
 
  ALTER TABLE `MAST_LANDMARK_BASIC` ADD CONSTRAINT `MAST_LANDMARK_BASIC_UNQ_01` UNIQUE (`ITEM_NM`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_MODIFY_HISTORY
--   *** ------------------------------------

  ALTER TABLE `RESERVE_MODIFY_HISTORY` ADD CONSTRAINT `RESERVE_MODIFY_HISTORY_PKY` PRIMARY KEY (`RESERVE_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table LOG_HOTEL_PERSON
--   *** ------------------------------------

  ALTER TABLE `LOG_HOTEL_PERSON` ADD CONSTRAINT `LOG_HOTEL_PERSON_PKY` PRIMARY KEY (`HOTEL_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_YDK
--   *** ------------------------------------

  ALTER TABLE `PLAN_YDK` ADD CONSTRAINT `PLAN_YDK_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_03
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_03` ADD CONSTRAINT `LOG_SECURITY_03_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_ROUTES
--   *** ------------------------------------

  ALTER TABLE `MAST_ROUTES` ADD CONSTRAINT `MAST_ROUTES_PKY` PRIMARY KEY (`ROUTE_ID`)
  ;
 
--  ALTER TABLE `MAST_ROUTES` MODIFY (`ROUTE_ID` NOT NULL ENABLE);
  ALTER TABLE `MAST_ROUTES` MODIFY `ROUTE_ID` varchar(5) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table ROOM_PLAN_POINT
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_POINT` ADD CONSTRAINT `ROOM_PLAN_POINT_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_INFO
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_INFO` ADD CONSTRAINT `ROOM_PLAN_INFO_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_ADDED_MESSAGE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_ADDED_MESSAGE` ADD CONSTRAINT `RESERVE_ADDED_MESSAGE_PKY` PRIMARY KEY (`RESERVE_CD`, `MSG_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table SERVICE_VOTE
--   *** ------------------------------------

  ALTER TABLE `SERVICE_VOTE` ADD CONSTRAINT `SERVICE_VOTE_PKY` PRIMARY KEY (`VOTE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SEARCH_WORDS
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SEARCH_WORDS` ADD CONSTRAINT `HOTEL_SEARCH_WORDS_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_DETAIL
--   *** ------------------------------------

  ALTER TABLE `MEMBER_DETAIL` ADD CONSTRAINT `MEMBER_DETAIL_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
 
  ALTER TABLE `MEMBER_DETAIL` ADD CONSTRAINT `MEMBER_DETAIL_UNQ_01` UNIQUE (`ACCOUNT_ID`)
  ;
 
--  ALTER TABLE `MEMBER_DETAIL` MODIFY (`MEMBER_CD` NOT NULL ENABLE);
  ALTER TABLE `MEMBER_DETAIL` MODIFY `MEMBER_CD` varchar(128) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table RSV_TOP_CONTENTS
--   *** ------------------------------------

  ALTER TABLE `RSV_TOP_CONTENTS` ADD CONSTRAINT `RSV_TOP_CONTENTS_PKY` PRIMARY KEY (`CONTENT_CD`)
  ;
 
--  ALTER TABLE `RSV_TOP_CONTENTS` MODIFY (`PLACE` NOT NULL ENABLE);
   ALTER TABLE `RSV_TOP_CONTENTS` MODIFY `PLACE` varchar(10) BINARY  NOT NULL ;

--  ALTER TABLE `RSV_TOP_CONTENTS` MODIFY (`TITLE` NOT NULL ENABLE);
   ALTER TABLE `RSV_TOP_CONTENTS` MODIFY `TITLE` varchar(300) BINARY  NOT NULL ;

--   *** ------------------------------------
--  *** Table EXTEND_SWITCH
--   *** ------------------------------------

  ALTER TABLE `EXTEND_SWITCH` ADD CONSTRAINT `EXTEND_SWITCH_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YDP
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDP` ADD CONSTRAINT `HOTEL_YDP_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_INITIAL
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_INITIAL` ADD CONSTRAINT `ROOM_CHARGE_INITIAL_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_YAHOO
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_YAHOO` ADD CONSTRAINT `BILLPAYED_HOTEL_YAHOO_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_MATCH_HISTORY
--   *** ------------------------------------

  ALTER TABLE `WELFARE_MATCH_HISTORY` ADD CONSTRAINT `WELFARE_MATCH_HISTORY_PKY` PRIMARY KEY (`WELFARE_MATCH_HISTORY_ID`)
  ;
 
  ALTER TABLE `WELFARE_MATCH_HISTORY` ADD CONSTRAINT `WELFARE_MATCH_HISTORY_UNQ_01` UNIQUE (`WELFARE_MATCH_ID`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AMEDAS
--   *** ------------------------------------

  ALTER TABLE `MAST_AMEDAS` ADD CONSTRAINT `MAST_AMEDAS_PKY` PRIMARY KEY (`JBR_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN` ADD CONSTRAINT `ROOM_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_GENRE
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_GENRE` ADD CONSTRAINT `GROUP_BUYING_GENRE_PKY` PRIMARY KEY (`GENRE_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_GRANTS_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_GRANTS_9XG` ADD CONSTRAINT `BILLPAYED_HOTEL_GRANTS_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER_HOTEL_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_CUSTOMER_HOTEL_9XG` ADD CONSTRAINT `BILLPAY_CUSTOMER_HOTEL_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table KEYWORDS_HOTEL
--   *** ------------------------------------

  ALTER TABLE `KEYWORDS_HOTEL` ADD CONSTRAINT `KEYWORDS_HOTEL_PKY` PRIMARY KEY (`HOTEL_CD`, `FIELD_NM`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_YAHOO
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_YAHOO` ADD CONSTRAINT `CHECKSHEET_HOTEL_YAHOO_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_FEE_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_FEE_9XG` ADD CONSTRAINT `BILLPAY_FEE_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_CITY
--   *** ------------------------------------

  ALTER TABLE `MAST_CITY` ADD CONSTRAINT `MAST_CITY_PKY` PRIMARY KEY (`CITY_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_PREMIUM
--   *** ------------------------------------

  ALTER TABLE `HOTEL_PREMIUM` ADD CONSTRAINT `HOTEL_PREMIUM_PKY` PRIMARY KEY (`HOTEL_CD`, `OPEN_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_HOTELS
--   *** ------------------------------------

  ALTER TABLE `MEMBER_HOTELS` ADD CONSTRAINT `MEMBER_HOTELS_PKY` PRIMARY KEY (`MEMBER_CD`, `ENTRY_TYPE`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION_BASE
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_BASE` ADD CONSTRAINT `MIGRATION_BASE_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_FACILITY_ROOM
--   *** ------------------------------------

  ALTER TABLE `HOTEL_FACILITY_ROOM` ADD CONSTRAINT `HOTEL_FACILITY_ROOM_PKY` PRIMARY KEY (`HOTEL_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_INFO
--   *** ------------------------------------

  ALTER TABLE `HOTEL_INFO` ADD CONSTRAINT `HOTEL_INFO_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_RELATION
--   *** ------------------------------------

  ALTER TABLE `HOTEL_RELATION` ADD CONSTRAINT `HOTEL_RELATION_PKY` PRIMARY KEY (`HOTEL_RELATION_CD`)
  ;
--   *** ------------------------------------
--  *** Table TWITTER
--   *** ------------------------------------

  ALTER TABLE `TWITTER` ADD CONSTRAINT `TWITTER_PKY` PRIMARY KEY (`TWITTER_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_GRANTS
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_GRANTS` ADD CONSTRAINT `RESERVE_DISPOSE_GRANTS_PKY` PRIMARY KEY (`DISPOSE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_AREA
--   *** ------------------------------------

  ALTER TABLE `HOTEL_AREA` ADD CONSTRAINT `HOTEL_AREA_PKY` PRIMARY KEY (`HOTEL_CD`, `ENTRY_NO`, `AREA_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_TODAY2
--   *** ------------------------------------

-- 存在しない  ALTER TABLE `ROOM_CHARGE_TODAY2` ADD CONSTRAINT `ROOM_CHARGE_TODAY2_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `DATE_YMD`, `TIMETABLE`)  ;
--   *** ------------------------------------
--  *** Table MAST_MONEY_SCHEDULE
--   *** ------------------------------------

  ALTER TABLE `MAST_MONEY_SCHEDULE` ADD CONSTRAINT `MAST_MONEY_SCHEDULE_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_FREE
--   *** ------------------------------------

  ALTER TABLE `MEMBER_FREE` ADD CONSTRAINT `MEMBER_FREE_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_TYK
--   *** ------------------------------------

  ALTER TABLE `HOTEL_TYK` ADD CONSTRAINT `HOTEL_TYK_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_BOOK_V4
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_BOOK_V4` ADD CONSTRAINT `BR_POINT_BOOK_V4_PKY` PRIMARY KEY (`BR_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table KEYWORDS_PLAN
--   *** ------------------------------------

  ALTER TABLE `KEYWORDS_PLAN` ADD CONSTRAINT `KEYWORDS_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `FIELD_NM`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATION_SURVEY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATION_SURVEY` ADD CONSTRAINT `HOTEL_STATION_SURVEY_PKY` PRIMARY KEY (`STATION_CD`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_YDP
--   *** ------------------------------------

  ALTER TABLE `RESERVE_YDP` ADD CONSTRAINT `RESERVE_YDP_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CONTROL
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CONTROL` ADD CONSTRAINT `HOTEL_CONTROL_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_STOCK
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN_STOCK` ADD CONSTRAINT `BILLPAYED_PTN_STOCK_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `STOCK_RATE`)
  ;
--   *** ------------------------------------
--  *** Table TOP_ATTENTION_DETAIL
--   *** ------------------------------------

  ALTER TABLE `TOP_ATTENTION_DETAIL` ADD CONSTRAINT `TOP_ATTENTION_DETAIL_PKY` PRIMARY KEY (`ATTENTION_DETAIL_ID`)
  ;
--   *** ------------------------------------
--  *** Table STOCK_POWER
--   *** ------------------------------------

  ALTER TABLE `STOCK_POWER` ADD CONSTRAINT `STOCK_POWER_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STATUS
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STATUS` ADD CONSTRAINT `HOTEL_STATUS_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_LIVEDOOR
--   *** ------------------------------------

  ALTER TABLE `MEMBER_LIVEDOOR` ADD CONSTRAINT `MEMBER_LIVEDOOR_PKY` PRIMARY KEY (`TRANSACTION_CD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_12
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_12` ADD CONSTRAINT `LOG_SECURITY_12_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_SUPPLIER
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_SUPPLIER` ADD CONSTRAINT `GROUP_BUYING_SUPPLIER_PKY` PRIMARY KEY (`SUPPLIER_CD`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_ORDER
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_ORDER` ADD CONSTRAINT `GROUP_BUYING_ORDER_PKY` PRIMARY KEY (`ORDER_ID`)
  ;
 
--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`SUPPLIER_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `SUPPLIER_CD` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`SUPPLIER_ORDER_ID` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `SUPPLIER_ORDER_ID` varchar(32) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`DEAL_ID` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `DEAL_ID` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`MEMBER_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `MEMBER_CD` varchar(128) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`ORDER_DTM` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `ORDER_DTM` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`ORDER_STATUS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `ORDER_STATUS` tinyint NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `ENTRY_TS` datetime NOT NULL ;

--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;
 
--  ALTER TABLE `GROUP_BUYING_ORDER` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
   ALTER TABLE `GROUP_BUYING_ORDER` MODIFY `MODIFY_TS` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table JOURNAL
--   *** ------------------------------------

  ALTER TABLE `JOURNAL` ADD CONSTRAINT `JOURNAL_PKY` PRIMARY KEY (`JOURNAL_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CREDIT_DEV
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CREDIT_DEV` ADD CONSTRAINT `RESERVE_CREDIT_DEV_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_6
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_6` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_6_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ALERT_SYSTEM
--   *** ------------------------------------

  ALTER TABLE `ALERT_SYSTEM` ADD CONSTRAINT `ALERT_SYSTEM_PKY` PRIMARY KEY (`ALERT_SYSTEM_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_CREDIT
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_CREDIT` ADD CONSTRAINT `BILLPAY_CREDIT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_BANK
--   *** ------------------------------------

  ALTER TABLE `MAST_BANK` ADD CONSTRAINT `MAST_BANK_PKY` PRIMARY KEY (`BANK_CD`)
  ;
--   *** ------------------------------------
--  *** Table HIKARI_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `HIKARI_ACCOUNT` ADD CONSTRAINT `HIKARI_ACCOUNT_PKY` PRIMARY KEY (`ID`)
  ;
 
  ALTER TABLE `HIKARI_ACCOUNT` ADD CONSTRAINT `HIKARI_ACCOUNT_UNQ_01` UNIQUE (`ACCOUNT_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_09
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_09` ADD CONSTRAINT `LOG_SECURITY_09_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table SPOT_GROUP_MATCH
--   *** ------------------------------------

  ALTER TABLE `SPOT_GROUP_MATCH` ADD CONSTRAINT `SPOT_GROUP_MATCH_PKY` PRIMARY KEY (`SPOT_GROUP_ID`, `SPOT_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_SALES
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN_SALES` ADD CONSTRAINT `BILLPAYED_PTN_SALES_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `SALES_RATE`)
  ;
--   *** ------------------------------------
--  *** Table RECORD_RESERVE
--   *** ------------------------------------

  ALTER TABLE `RECORD_RESERVE` ADD CONSTRAINT `RECORD_RESERVE_PKY` PRIMARY KEY (`DATE_YMD`, `SYSTEM_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_HOTEL_FEE
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_HOTEL_FEE` ADD CONSTRAINT `BILLPAY_HOTEL_FEE_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ORICO_SALES_HISTORY
--   *** ------------------------------------

  ALTER TABLE `ORICO_SALES_HISTORY` ADD CONSTRAINT `ORICO_SALES_HISTORY_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table EXTEND_SWITCH_PLAN
--   *** ------------------------------------

  ALTER TABLE `EXTEND_SWITCH_PLAN` ADD CONSTRAINT `EXTEND_SWITCH_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_YAHOO
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_YAHOO` ADD CONSTRAINT `BILLPAYED_YAHOO_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_REMOVED
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_REMOVED` ADD CONSTRAINT `ROOM_CHARGE_REMOVED_PKY` PRIMARY KEY (`ROOM_CHARGE_REMOVED_ID`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_JR
--   *** ------------------------------------

  ALTER TABLE `PLAN_JR` ADD CONSTRAINT `PLAN_JR_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table CHARGE_TODAY
--   *** ------------------------------------

  ALTER TABLE `CHARGE_TODAY` ADD CONSTRAINT `CHARGE_TODAY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`, `PLAN_ID`, `PARTNER_GROUP_ID`, `CAPACITY`, `DATE_YMD`, `TIMETABLE`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_YDP_MATCH
--   *** ------------------------------------

  ALTER TABLE `HOTEL_YDP_MATCH` ADD CONSTRAINT `HOTEL_YDP_REDIRECT_PKY` PRIMARY KEY (`YDP_HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_YAHOO
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_YAHOO` ADD CONSTRAINT `BILLPAY_YAHOO_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROUTE_MAP
--   *** ------------------------------------

  ALTER TABLE `ROUTE_MAP` ADD CONSTRAINT `ROUTE_MAP_PKY` PRIMARY KEY (`ROUTE_ID`, `STATION_ID1`, `STATION_ID2`)
  ;
 
--  ALTER TABLE `ROUTE_MAP` MODIFY (`ROUTE_ID` NOT NULL ENABLE);
   ALTER TABLE `ROUTE_MAP` MODIFY `ROUTE_ID` varchar(5) BINARY NOT NULL ;

--  ALTER TABLE `ROUTE_MAP` MODIFY (`STATION_ID1` NOT NULL ENABLE);
   ALTER TABLE `ROUTE_MAP` MODIFY `STATION_ID1` varchar(7) BINARY NOT NULL ;

--  ALTER TABLE `ROUTE_MAP` MODIFY (`STATION_ID2` NOT NULL ENABLE);
   ALTER TABLE `ROUTE_MAP` MODIFY `STATION_ID2` varchar(7) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table RESERVE_POWER
--   *** ------------------------------------

  ALTER TABLE `RESERVE_POWER` ADD CONSTRAINT `RESERVE_POWER_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table AKAFU_STOCK_FRAME_NO
--   *** ------------------------------------

  ALTER TABLE `AKAFU_STOCK_FRAME_NO` ADD CONSTRAINT `AKAFU_STOCK_FRAME_NO_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_CHARGE_TODAY
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_TODAY` ADD CONSTRAINT `ROOM_CHARGE_TODAY_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`, `DATE_YMD`, `TIMETABLE`)
  ;
--   *** ------------------------------------
--  *** Table BR_SS_IMPORT
--   *** ------------------------------------

  ALTER TABLE `BR_SS_IMPORT` ADD CONSTRAINT `BR_SS_IMPORT_PKY` PRIMARY KEY (`BR_SS_IMPORT_ID`)
  ;
 
  ALTER TABLE `BR_SS_IMPORT` ADD CONSTRAINT `BR_SS_IMPORT_UNQ_01` UNIQUE (`MEMBER_CD`)
  ;
 
  ALTER TABLE `BR_SS_IMPORT` ADD CONSTRAINT `BR_SS_IMPORT_UNQ_02` UNIQUE (`CONFIRM_PAGE_URL`)
  ;
 
--  ALTER TABLE `BR_SS_IMPORT` MODIFY (`BR_SS_IMPORT_NM` NOT NULL ENABLE);
   ALTER TABLE `BR_SS_IMPORT` MODIFY `BR_SS_IMPORT_NM` varchar(60) BINARY NOT NULL ;

--  ALTER TABLE `BR_SS_IMPORT` MODIFY (`MEMBER_CD` NOT NULL ENABLE);
   ALTER TABLE `BR_SS_IMPORT` MODIFY `MEMBER_CD` varchar(128) BINARY NOT NULL ;

--  ALTER TABLE `BR_SS_IMPORT` MODIFY (`CONFIRM_PAGE_URL` NOT NULL ENABLE);
   ALTER TABLE `BR_SS_IMPORT` MODIFY `CONFIRM_PAGE_URL` varchar(255) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table BILLPAYED_STOCK
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_STOCK` ADD CONSTRAINT `BILLPAYED_STOCK_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `SITE_CD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION_CANCEL_RATE_TEMP
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_CANCEL_RATE_TEMP` ADD CONSTRAINT `MIGRATION_CANCEL_RATE_TEMP_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `DAYS`)
  ;
--   *** ------------------------------------
--  *** Table MAST_LANDMARK_CATEGORY_1ST
--   *** ------------------------------------

  ALTER TABLE `MAST_LANDMARK_CATEGORY_1ST` ADD CONSTRAINT `MAST_LANDMARK_CATEGORY_1ST_PKY` PRIMARY KEY (`CATEGORY_1ST_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAILMAG_V2_SET
--   *** ------------------------------------

  ALTER TABLE `MAILMAG_V2_SET` ADD CONSTRAINT `MAILMAG_V2_SET_PKY` PRIMARY KEY (`MAILMAG_V2_SET_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PR_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PR_GRANTS` ADD CONSTRAINT `BILLPAYED_PR_GRANTS_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `SITE_CD`, `OPERATION_YMD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_ZAP
--   *** ------------------------------------

  ALTER TABLE `MEMBER_ZAP` ADD CONSTRAINT `MEMBER_ZAP_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_KEYWORD_MATCH
--   *** ------------------------------------

  ALTER TABLE `MAST_KEYWORD_MATCH` ADD CONSTRAINT `MAST_KEYWORD_MATCH_PKY` PRIMARY KEY (`KEYWORD_GROUP_ID`, `KEYWORD_ID`)
  ;
--   *** ------------------------------------
--  *** Table LANDMARK_PREF_MATCH
--   *** ------------------------------------

  ALTER TABLE `LANDMARK_PREF_MATCH` ADD CONSTRAINT `LANDMARK_PREF_MATCH_PKY` PRIMARY KEY (`LANDMARK_ID`, `PREF_ID`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY_DETAIL
--   *** ------------------------------------

  ALTER TABLE `NOTIFY_DETAIL` ADD CONSTRAINT `NOTIFY_DETAIL_PKY` PRIMARY KEY (`NOTIFY_CD`, `NOTIFY_DEVICE`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_GRANTS` ADD CONSTRAINT `BILLPAY_PTN_GRANTS_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_MOBILE
--   *** ------------------------------------

  ALTER TABLE `MEMBER_MOBILE` ADD CONSTRAINT `MEMBER_MOBILE_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table GOUDA_AR_TEST
--   *** ------------------------------------

  ALTER TABLE `GOUDA_AR_TEST` ADD CONSTRAINT `GOUDA_AR_TEST_PKY` PRIMARY KEY (`ID`, `CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_HOTEL_GRANTS
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_HOTEL_GRANTS` ADD CONSTRAINT `CHECKSHEET_HOTEL_GRANTS_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table ORICO_RESERVE_HISTORY
--   *** ------------------------------------

  ALTER TABLE `ORICO_RESERVE_HISTORY` ADD CONSTRAINT `ORICO_RESERVE_HISTORY_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_INFO
--   *** ------------------------------------

  ALTER TABLE `PLAN_INFO` ADD CONSTRAINT `PLAN_INFO_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_CLUTCH
--   *** ------------------------------------

  ALTER TABLE `PARTNER_CLUTCH` ADD CONSTRAINT `PARTNER_CLUTCH_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_HOTELS2
--   *** ------------------------------------

  ALTER TABLE `MEMBER_HOTELS2` ADD CONSTRAINT `MEMBER_HOTELS2_PKY` PRIMARY KEY (`MEMBER_CD`, `ENTRY_TYPE`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER_HOTEL
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_CUSTOMER_HOTEL` ADD CONSTRAINT `BILLPAY_CUSTOMER_HOTEL_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_SITE
--   *** ------------------------------------

  ALTER TABLE `PARTNER_SITE` ADD CONSTRAINT `PARTNER_SITE_PKY` PRIMARY KEY (`SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_KEYWORD_GROUP
--   *** ------------------------------------

  ALTER TABLE `MAST_KEYWORD_GROUP` ADD CONSTRAINT `MAST_KEYWORD_GROUP_PKY` PRIMARY KEY (`KEYWORD_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table PLAN_SPEC
--   *** ------------------------------------

  ALTER TABLE `PLAN_SPEC` ADD CONSTRAINT `PLAN_SPEC_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CHARGE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CHARGE` ADD CONSTRAINT `RESERVE_CHARGE_CHK_01` CHECK (sales_charge is not null);
 
  ALTER TABLE `RESERVE_CHARGE` ADD CONSTRAINT `RESERVE_CHARGE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CREDIT
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CREDIT` ADD CONSTRAINT `RESERVE_CREDIT_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_OAUTH2
--   *** ------------------------------------

  ALTER TABLE `PARTNER_OAUTH2` ADD CONSTRAINT `PARTNER_OAUTH2_PKY` PRIMARY KEY (`CLIENT_ID`)
  ;
 
  ALTER TABLE `PARTNER_OAUTH2` ADD CONSTRAINT `PARTNER_OAUTH2_UNQ_01` UNIQUE (`CLIENT_SECRET`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_EPARK
--   *** ------------------------------------

  ALTER TABLE `MEMBER_EPARK` ADD CONSTRAINT `MEMBER_EPARK_PKY` PRIMARY KEY (`EPARK_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CANCEL_RATE
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CANCEL_RATE` ADD CONSTRAINT `HOTEL_CANCEL_RATE_PKY` PRIMARY KEY (`HOTEL_CD`, `DAYS`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_TYPE_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_TYPE_GRANTS` ADD CONSTRAINT `BILLPAY_PTN_TYPE_GRANTS_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `WELFARE_GRANTS_ID`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION_MATCH
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_MATCH` ADD CONSTRAINT `MIGRATION_MATCH_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL
--   *** ------------------------------------

  ALTER TABLE `HOTEL` ADD CONSTRAINT `HOTEL_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
 
--  ALTER TABLE `HOTEL` MODIFY (`HOTEL_CD` NOT NULL ENABLE);
   ALTER TABLE `HOTEL` MODIFY `HOTEL_CD` varchar(10) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table MAST_STATION
--   *** ------------------------------------

  ALTER TABLE `MAST_STATION` ADD CONSTRAINT `MAST_STATION_PKY` PRIMARY KEY (`STATION_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_RANKING
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_RANKING` ADD CONSTRAINT `ROOM_PLAN_RANKING_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `WDAY`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_PRIORITY
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_PRIORITY` ADD CONSTRAINT `ROOM_PLAN_PRIORITY_PKY` PRIMARY KEY (`PREF_ID`, `SPAN`, `WDAY`, `PRIORITY`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_BASE_CHILD
--   *** ------------------------------------

  ALTER TABLE `RESERVE_BASE_CHILD` ADD CONSTRAINT `RESERVE_BASE_CHILD_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_CUSTOMER
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_CUSTOMER` ADD CONSTRAINT `BILLPAY_CUSTOMER_PKY` PRIMARY KEY (`BILLPAY_YM`, `CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_RATE
--   *** ------------------------------------

  ALTER TABLE `HOTEL_RATE` ADD CONSTRAINT `HOTEL_RATE_PKY` PRIMARY KEY (`HOTEL_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table MAST_HOTEL_ELEMENT_VALUE
--   *** ------------------------------------

  ALTER TABLE `MAST_HOTEL_ELEMENT_VALUE` ADD CONSTRAINT `MAST_HOTEL_ELEMENT_VALUE_PKY` PRIMARY KEY (`ELEMENT_ID`, `ELEMENT_VALUE_ID`)
  ;
--   *** ------------------------------------
--  *** Table OTA_ROOM_RELATION
--   *** ------------------------------------

  ALTER TABLE `OTA_ROOM_RELATION` ADD CONSTRAINT `OTA_ROOM_RELATION_PKY` PRIMARY KEY (`OTA_ROOM_RELATION_ID`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_DETAIL_SP
--   *** ------------------------------------

  ALTER TABLE `MEMBER_DETAIL_SP` ADD CONSTRAINT `MEMBER_DETAIL_SP_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
 
  ALTER TABLE `MEMBER_DETAIL_SP` ADD CONSTRAINT `MEMBER_DETAIL_SP_UNQ_01` UNIQUE (`ACCOUNT_ID`)
  ;
 
--  ALTER TABLE `MEMBER_DETAIL_SP` MODIFY (`MEMBER_CD` NOT NULL ENABLE);
  ALTER TABLE `MEMBER_DETAIL_SP` MODIFY `MEMBER_CD` varchar(128) BINARY NOT NULL ;

--   *** ------------------------------------
--  *** Table MAST_BANK_BRANCH
--   *** ------------------------------------

  ALTER TABLE `MAST_BANK_BRANCH` ADD CONSTRAINT `MAST_BANK_BRANCH_PKY` PRIMARY KEY (`BANK_CD`, `BANK_BRANCH_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_TYPE_SALES
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_TYPE_SALES` ADD CONSTRAINT `BILLPAY_PTN_TYPE_SALES_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `STOCK_TYPE`, `SALES_RATE`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_PLAN_SPEC
--   *** ------------------------------------

  ALTER TABLE `RESERVE_PLAN_SPEC` ADD CONSTRAINT `RESERVE_PLAN_SPEC_PKY` PRIMARY KEY (`RESERVE_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table CUSTOMER_HIKARI
--   *** ------------------------------------

--  ALTER TABLE `CUSTOMER_HIKARI` MODIFY (`CUSTOMER_ID` NOT NULL ENABLE);
  ALTER TABLE `CUSTOMER_HIKARI` MODIFY `CUSTOMER_ID` bigint NOT NULL ;

--   *** ------------------------------------
--  *** Table BILLPAYED_FEE
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_FEE` ADD CONSTRAINT `BILLPAYED_FEE_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `OPERATION_YMD`)
  ;
--   *** ------------------------------------
--  *** Table MAIL_MAGAZINE_SIMPLE_BCC
--   *** ------------------------------------

  ALTER TABLE `MAIL_MAGAZINE_SIMPLE_BCC` ADD CONSTRAINT `MAIL_MAGAZINE_SIMPLE_BCC_PKY` PRIMARY KEY (`MAIL_MAGAZINE_SIMPLE_ID`, `MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CAMP_PLAN
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CAMP_PLAN` ADD CONSTRAINT `HOTEL_CAMP_PLAN_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `CAMP_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_CONTACT
--   *** ------------------------------------

  ALTER TABLE `RESERVE_CONTACT` ADD CONSTRAINT `RESERVE_CONTACT_PKY` PRIMARY KEY (`RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_MODIFY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_MODIFY` ADD CONSTRAINT `HOTEL_MODIFY_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table LANDMARKS
--   *** ------------------------------------

  ALTER TABLE `LANDMARKS` ADD CONSTRAINT `LANDMARKS_PKY` PRIMARY KEY (`LANDMARK_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_LANDMARK_SURVEY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_LANDMARK_SURVEY` ADD CONSTRAINT `HOTEL_LANDMARK_SURVEY_PKY` PRIMARY KEY (`LANDMARK_ID`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_CALENDAR
--   *** ------------------------------------

  ALTER TABLE `MAST_CALENDAR` ADD CONSTRAINT `MAST_CALENDAR_PKY` PRIMARY KEY (`DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table CUSTOMER_HOTEL
--   *** ------------------------------------

  ALTER TABLE `CUSTOMER_HOTEL` ADD CONSTRAINT `CUSTOMER_HOTEL_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_SHORT_TERM_COND
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_SHORT_TERM_COND` ADD CONSTRAINT `BR_POINT_SHORT_TERM_COND_PKY` PRIMARY KEY (`MEMBER_TYPE`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_5273
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_5273` ADD CONSTRAINT `GROUP_BUYING_5273_PKY` PRIMARY KEY (`MEMBER_CD`)
  ;
--   *** ------------------------------------
--  *** Table SPOT_GROUP
--   *** ------------------------------------

  ALTER TABLE `SPOT_GROUP` ADD CONSTRAINT `SPOT_GROUP_PKY` PRIMARY KEY (`SPOT_GROUP_ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_LANDMARK
--   *** ------------------------------------

  ALTER TABLE `HOTEL_LANDMARK` ADD CONSTRAINT `HOTEL_LANDMARK_PKY` PRIMARY KEY (`HOTEL_CD`, `LANDMARK_ID`)
  ;
--   *** ------------------------------------
--  *** Table BR_POINT_PLUS_INFO
--   *** ------------------------------------

  ALTER TABLE `BR_POINT_PLUS_INFO` ADD CONSTRAINT `BR_POINT_PLUS_INFO_PKY` PRIMARY KEY (`POINT_PLUS_ID`)
  ;
--   *** ------------------------------------
--  *** Table LOG_PLAN_STATUS_POOL2
--   *** ------------------------------------

  ALTER TABLE `LOG_PLAN_STATUS_POOL2` ADD CONSTRAINT `LOG_PLAN_STATUS_POOL2_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_SUPERVISOR_ACCOUNT
--   *** ------------------------------------

  ALTER TABLE `HOTEL_SUPERVISOR_ACCOUNT` ADD CONSTRAINT `HOTEL_SUPERVISOR_ACCOUNT_PKY` PRIMARY KEY (`SUPERVISOR_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_YAHOO_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_YAHOO_9XG` ADD CONSTRAINT `BILLPAYED_HOTEL_YAHOO_9XG_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CARD
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CARD` ADD CONSTRAINT `HOTEL_CARD_PKY` PRIMARY KEY (`HOTEL_CD`, `CARD_ID`)
  ;
--   *** ------------------------------------
--  *** Table MY_SETTING
--   *** ------------------------------------

  ALTER TABLE `MY_SETTING` ADD CONSTRAINT `MY_SETTING_PKY` PRIMARY KEY (`MEMBER_CD`, `ITEM_CD`)
  ;
--   *** ------------------------------------
--  *** Table EXTEND_SETTING_ROOM
--   *** ------------------------------------

  ALTER TABLE `EXTEND_SETTING_ROOM` ADD CONSTRAINT `EXTEND_SETTING_ROOM_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table MAST_KEYWORDS
--   *** ------------------------------------

  ALTER TABLE `MAST_KEYWORDS` ADD CONSTRAINT `MAST_KEYWORDS_PKY` PRIMARY KEY (`KEYWORD_ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_STOCK
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_STOCK` ADD CONSTRAINT `BILLPAY_STOCK_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_INFORM
--   *** ------------------------------------

  ALTER TABLE `HOTEL_INFORM` ADD CONSTRAINT `HOTEL_INFORM_PKY` PRIMARY KEY (`HOTEL_CD`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_STAFF_NOTE
--   *** ------------------------------------

  ALTER TABLE `HOTEL_STAFF_NOTE` ADD CONSTRAINT `HOTEL_STAFF_NOTE_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table MMS_GENRE
--   *** ------------------------------------

  ALTER TABLE `MMS_GENRE` ADD CONSTRAINT `MMS_GENRE_PKY` PRIMARY KEY (`MAIL_MAGAZINE_SIMPLE_ID`, `GENRE`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_NEARBY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_NEARBY` ADD CONSTRAINT `HOTEL_NEARBY_PKY` PRIMARY KEY (`HOTEL_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_CANCEL_RATE
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_CANCEL_RATE` ADD CONSTRAINT `ROOM_PLAN_CANCEL_RATE_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DAYS`)
  ;
--   *** ------------------------------------
--  *** Table MEMBER_SEARCH_MAIL_SP
--   *** ------------------------------------

  ALTER TABLE `MEMBER_SEARCH_MAIL_SP` ADD CONSTRAINT `MEMBER_SEARCH_MAIL_SP_PKY` PRIMARY KEY (`MEMBER_CD`, `ENTRY_TYPE`)
  ;
 
--  ALTER TABLE `MEMBER_SEARCH_MAIL_SP` MODIFY (`MEMBER_CD` NOT NULL ENABLE);
   ALTER TABLE `MEMBER_SEARCH_MAIL_SP` MODIFY `MEMBER_CD` varchar(128) BINARY NOT NULL ;

--  ALTER TABLE `MEMBER_SEARCH_MAIL_SP` MODIFY (`ENTRY_TYPE` NOT NULL ENABLE);
   ALTER TABLE `MEMBER_SEARCH_MAIL_SP` MODIFY `ENTRY_TYPE` tinyint NOT NULL ;

--   *** ------------------------------------
--  *** Table MAST_HOLIDAY
--   *** ------------------------------------

  ALTER TABLE `MAST_HOLIDAY` ADD CONSTRAINT `MAST_HOLIDAY_PKY` PRIMARY KEY (`HOLIDAY`)
  ;
--   *** ------------------------------------
--  *** Table GROUP_BUYING_AUTHORI_DEV
--   *** ------------------------------------

  ALTER TABLE `GROUP_BUYING_AUTHORI_DEV` ADD CONSTRAINT `GROUP_BUYING_AUTHORI_DEV_PKY` PRIMARY KEY (`ORDER_ID`)
  ;
--   *** ------------------------------------
--  *** Table PAYMENT
--   *** ------------------------------------

  ALTER TABLE `PAYMENT` ADD CONSTRAINT `PAYMENT_PKY` PRIMARY KEY (`PAYMENT_ID`)
  ;
 
  ALTER TABLE `PAYMENT` ADD CONSTRAINT `PAYMENT_UNQ_01` UNIQUE (`TRANSFER_YMD`, `IN_OUT_TYPE`, `ACCOUNT_TYPE`, `ACCOUNT_CHARGE`, `ACC_CLIENT_CD`, `ACC_CLIENT_BANK_NM`, `ACC_CLIENT_BRANCH_NM`)
  ;
 
--  ALTER TABLE `PAYMENT` MODIFY (`TRANSFER_YMD` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT` MODIFY `TRANSFER_YMD` datetime NOT NULL ;

--  ALTER TABLE `PAYMENT` MODIFY (`ACCOUNT_CHARGE` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT` MODIFY`ACCOUNT_CHARGE` decimal(32,0) NOT NULL ;

--  ALTER TABLE `PAYMENT` MODIFY (`ENTRY_CD` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT` MODIFY `ENTRY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `PAYMENT` MODIFY (`ENTRY_TS` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT` MODIFY `ENTRY_TS` datetime NOT NULL ;

--  ALTER TABLE `PAYMENT` MODIFY (`MODIFY_CD` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT` MODIFY `MODIFY_CD` varchar(64) BINARY NOT NULL ;

--  ALTER TABLE `PAYMENT` MODIFY (`MODIFY_TS` NOT NULL ENABLE);
   ALTER TABLE `PAYMENT` MODIFY `MODIFY_TS` datetime NOT NULL ;

--   *** ------------------------------------
--  *** Table PARTNER_LAYOUT2
--   *** ------------------------------------

  ALTER TABLE `PARTNER_LAYOUT2` ADD CONSTRAINT `PARTNER_LAYOUT2_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_CREDIT_9XG
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_CREDIT_9XG` ADD CONSTRAINT `BILLPAY_CREDIT_9XG_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table LOG_SECURITY_02
--   *** ------------------------------------

  ALTER TABLE `LOG_SECURITY_02` ADD CONSTRAINT `LOG_SECURITY_02_PKY` PRIMARY KEY (`SECURITY_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_CANCEL_POLICY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_CANCEL_POLICY` ADD CONSTRAINT `HOTEL_CANCEL_POLICY_PKY` PRIMARY KEY (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_GRANTS
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN_GRANTS` ADD CONSTRAINT `BILLPAYED_PTN_GRANTS_UNQ_01` UNIQUE (`BILLPAY_YM`, `SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_AMENITY
--   *** ------------------------------------

  ALTER TABLE `HOTEL_AMENITY` ADD CONSTRAINT `HOTEL_AMENITY_PKY` PRIMARY KEY (`HOTEL_CD`, `ELEMENT_ID`)
  ;
--   *** ------------------------------------
--  *** Table FD_BASE_TIME
--   *** ------------------------------------

  ALTER TABLE `FD_BASE_TIME` ADD CONSTRAINT `FD_BASE_TIME_PKY` PRIMARY KEY (`AFFILIATE_ID`, `COOPERATION_CD`)
  ;
 
--  ALTER TABLE `FD_BASE_TIME` MODIFY (`BASE_TM` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `BASE_TM` datetime NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`TIME_RANGE` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `TIME_RANGE` smallint NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`ACTIV_FG` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `ACTIV_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`UPD_ID` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `UPD_ID` varchar(10) BINARY NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`UPD_DT` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `UPD_DT` datetime NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`STOCK_FG` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY`STOCK_FG` varchar(1) BINARY NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`COOPERATION_TYPE_CD` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `COOPERATION_TYPE_CD` tinyint NOT NULL ;

--  ALTER TABLE `FD_BASE_TIME` MODIFY (`TIMEOUT` NOT NULL ENABLE);
   ALTER TABLE `FD_BASE_TIME` MODIFY `TIMEOUT` smallint NOT NULL ;

--   *** ------------------------------------
--  *** Table ROOM_CHARGE_YHO_BAT_TMP_2
--   *** ------------------------------------

  ALTER TABLE `ROOM_CHARGE_YHO_BAT_TMP_2` ADD CONSTRAINT `ROOM_CHARGE_YHO_BAT_TMP_2_PKY` PRIMARY KEY (`REC_TYPE`, `HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table ZAP_ROOM_CHARGE
--   *** ------------------------------------

  ALTER TABLE `ZAP_ROOM_CHARGE` ADD CONSTRAINT `ZAP_ROOM_CHARGE_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_CD`, `PLAN_CD`, `PARTNER_GROUP_ID`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table NOTIFY_RIZAPULI_DETAIL
--   *** ------------------------------------

  ALTER TABLE `NOTIFY_RIZAPULI_DETAIL` ADD CONSTRAINT `NOTIFY_RIZAPULI_DETAIL_PKY` PRIMARY KEY (`NOTIFY_RIZAPULI_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_BASE_CHARGE
--   *** ------------------------------------

  ALTER TABLE `RESERVE_BASE_CHARGE` ADD CONSTRAINT `RESERVE_BASE_CHARGE_PKY` PRIMARY KEY (`RESERVE_CD`, `CAPACITY`, `DATE_YMD`)
  ;
--   *** ------------------------------------
--  *** Table PARTNER_INQUIRY
--   *** ------------------------------------

  ALTER TABLE `PARTNER_INQUIRY` ADD CONSTRAINT `PARTNER_INQUIRY_PKY` PRIMARY KEY (`PARTNER_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_ORDER
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_ORDER` ADD CONSTRAINT `CHECKSHEET_ORDER_PKY` PRIMARY KEY (`ORDER_NO`)
  ;
 
  ALTER TABLE `CHECKSHEET_ORDER` ADD CONSTRAINT `CHECKSHEET_ORDER_UNQ_01` UNIQUE (`HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table CHECKSHEET_CUSTOMER_9XG
--   *** ------------------------------------

  ALTER TABLE `CHECKSHEET_CUSTOMER_9XG` ADD CONSTRAINT `CHECKSHEET_CUSTOMER_9XG_PKY` PRIMARY KEY (`CHECKSHEET_YM`, `CUSTOMER_ID`)
  ;
--   *** ------------------------------------
--  *** Table MMS_LINK_ANALYZE
--   *** ------------------------------------

  ALTER TABLE `MMS_LINK_ANALYZE` ADD CONSTRAINT `MMS_LINK_ANALYZE_PKY` PRIMARY KEY (`MAIL_MAGAZINE_SIMPLE_ID`, `LINK_NO`)
  ;
--   *** ------------------------------------
--  *** Table FEATURE
--   *** ------------------------------------

  ALTER TABLE `FEATURE` ADD CONSTRAINT `FEATURE_PKY` PRIMARY KEY (`FEATURE_ID`)
  ;
--   *** ------------------------------------
--  *** Table WELFARE_GRANTS_HISTORY
--   *** ------------------------------------

  ALTER TABLE `WELFARE_GRANTS_HISTORY` ADD CONSTRAINT `WELFARE_GRANTS_HISTORY_PKY` PRIMARY KEY (`WELFARE_GRANTS_HISTORY_ID`)
  ;
 
  ALTER TABLE `WELFARE_GRANTS_HISTORY` ADD CONSTRAINT `WELFARE_GRANTS_HISTORY_UNQ_01` UNIQUE (`WELFARE_GRANTS_ID`, `BRANCH_NO`)
  ;
--   *** ------------------------------------
--  *** Table REPORT_WEEK_HOTEL
--   *** ------------------------------------

  ALTER TABLE `REPORT_WEEK_HOTEL` ADD CONSTRAINT `REPORT_WEEK_HOTEL_PKY` PRIMARY KEY (`HOTEL_CD`, `DATE_YMD`, `CHARGE_TYPE`, `CAPACITY`)
  ;
--   *** ------------------------------------
--  *** Table RSV_PUSH_AREAS
--   *** ------------------------------------

  ALTER TABLE `RSV_PUSH_AREAS` ADD CONSTRAINT `RSV_PUSH_AREAS_PKY` PRIMARY KEY (`AREA_ID`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_DISPOSE_YAHOO_9XG
--   *** ------------------------------------

  ALTER TABLE `RESERVE_DISPOSE_YAHOO_9XG` ADD CONSTRAINT `RESERVE_DISPOSE_YAHOO_9XG_PKY` PRIMARY KEY (`DISPOSE_YAHOO_ID`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_PLAN_LOWEST2
--   *** ------------------------------------

  ALTER TABLE `ROOM_PLAN_LOWEST2` ADD CONSTRAINT `ROOM_PLAN_LOWEST2_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `ROOM_ID`, `CAPACITY`, `CHARGE_CONDITION`)
  ;
--   *** ------------------------------------
--  *** Table HOTEL_VISUAL
--   *** ------------------------------------

  ALTER TABLE `HOTEL_VISUAL` ADD CONSTRAINT `HOTEL_VISUAL_PKY` PRIMARY KEY (`HOTEL_CD`, `OPEN_YMD`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_HOTEL_FEE
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_HOTEL_FEE` ADD CONSTRAINT `BILLPAYED_HOTEL_FEE_PKY` PRIMARY KEY (`BILLPAY_YM`, `HOTEL_CD`)
  ;
--   *** ------------------------------------
--  *** Table YAHOO_POINT_BOOK_PRE
--   *** ------------------------------------

  ALTER TABLE `YAHOO_POINT_BOOK_PRE` ADD CONSTRAINT `YAHOO_POINT_BOOK_PRE_PKY` PRIMARY KEY (`YAHOO_POINT_CD`)
  ;
--   *** ------------------------------------
--  *** Table MAST_AREA_MATCH
--   *** ------------------------------------

  ALTER TABLE `MAST_AREA_MATCH` ADD CONSTRAINT `MAST_AREA_MATCH_PKY` PRIMARY KEY (`ID`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAYED_PTN_TYPE_SALES
--   *** ------------------------------------

  ALTER TABLE `BILLPAYED_PTN_TYPE_SALES` ADD CONSTRAINT `BILLPAYED_PTN_TYPE_SALES_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`, `STOCK_TYPE`, `SALES_RATE`)
  ;
--   *** ------------------------------------
--  *** Table ROOM_YDK
--   *** ------------------------------------

  ALTER TABLE `ROOM_YDK` ADD CONSTRAINT `ROOM_YDK_PKY` PRIMARY KEY (`HOTEL_CD`, `ROOM_ID`)
  ;
--   *** ------------------------------------
--  *** Table ORICO_WEBSERVICE
--   *** ------------------------------------

  ALTER TABLE `ORICO_WEBSERVICE` ADD CONSTRAINT `ORICO_WEBSERVICE_PKY` PRIMARY KEY (`SERVICE_CD`, `RESERVE_CD`)
  ;
--   *** ------------------------------------
--  *** Table MIGRATION_CANCEL_RATE
--   *** ------------------------------------

  ALTER TABLE `MIGRATION_CANCEL_RATE` ADD CONSTRAINT `MIGRATION_CANCEL_RATE_PKY` PRIMARY KEY (`HOTEL_CD`, `PLAN_ID`, `DAYS`)
  ;
--   *** ------------------------------------
--  *** Table BILLPAY_PTN_SITE
--   *** ------------------------------------

  ALTER TABLE `BILLPAY_PTN_SITE` ADD CONSTRAINT `BILLPAY_PTN_SITE_PKY` PRIMARY KEY (`BILLPAY_YM`, `SITE_CD`)
  ;
--   *** ------------------------------------
--  *** Table RESERVE_FIX
--   *** ------------------------------------

  ALTER TABLE `RESERVE_FIX` ADD CONSTRAINT `RESERVE_FIX_PKY` PRIMARY KEY (`RESERVE_CD`, `DATE_YMD`, `FIX_TYPE`)
  ;
