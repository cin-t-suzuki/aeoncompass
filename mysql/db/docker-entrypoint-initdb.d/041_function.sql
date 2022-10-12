USE ac_travel;

-- 権限が必要 my.cnf [mysqld] log_bin_trust_function_creators = 1


--   *** ------------------------------------
--  ***  Function GET_BILL_YMD
--   *** ------------------------------------

  -- 
  DROP FUNCTION IF EXISTS `get_bill_ymd`;

  DELIMITER //

  CREATE FUNCTION `get_bill_ymd` (
    iv_billpay_cd varchar(4000),
    id_bill_ymd datetime
  )returns  datetime
  begin
    declare n_bill_add_month         double;
    declare n_bill_day               double;
    declare d_bill_ymd               datetime;
 
     set d_bill_ymd =id_bill_ymd;

     select 
         bill_add_month,
         bill_day
     into
         n_bill_add_month, 
         n_bill_day
     from customer
     where customer_id = iv_billpay_cd;

     if (d_bill_ymd is not null and n_bill_day is not null) then
       -- 月を設定
       set d_bill_ymd = timestampadd (month, n_bill_add_month,  d_bill_ymd);

       -- 日を設定
       if(n_bill_day = 99) then
           set d_bill_ymd = last_day(d_bill_ymd);
       else
           set d_bill_ymd = str_to_date(concat(ifnull(date_format(d_bill_ymd, '%Y-%m-'), '') , ifnull(n_bill_day, '')) , '%Y-%m-%d');
       end if;
     end if;
     
  return(d_bill_ymd);
  
end;
  //
-- 終端文字列をデフォルトに戻す
DELIMITER ;

-- 不要get_bill_ymd;

  -- シーケンス代替
  DROP FUNCTION IF EXISTS `NextVal`;

  DELIMITER //

  CREATE 
    FUNCTION `NextVal`(seq_name VARCHAR(50)) RETURNS bigint(15)
  BEGIN
    DECLARE val bigint(15);
    DECLARE incval bigint(15);
    
    SET val = 0;
    SET incval = 0;
    
    SELECT current_val,increment
    INTO val,incval
    FROM tbl_sequence
    WHERE name = seq_name;

    UPDATE TBL_SEQUENCE SET current_val = current_val + increment
    WHERE name = seq_name;
    
    RETURN val + incval;
  END;

  //
-- 終端文字列をデフォルトに戻す
DELIMITER ;


-- 不要/
