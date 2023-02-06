USE ac_travel;

--   *** ------------------------------------
--  *** View V_HOTELS
--   *** ------------------------------------
-- Tabはエラーになる
CREATE
OR REPLACE VIEW `v_hotels` (`HOTELS`) AS
select
    count(1) as hotels
from
    hotel_status
where
    entry_status = 0;

--   *** ------------------------------------
--  *** View V_TAX_CALENDAR
--   *** ------------------------------------
-- 
CREATE
OR REPLACE VIEW `v_tax_calendar` (`DATE_YMD`, `TAX`) AS
select
    q1.date_ymd,
    max(tax) as tax
from
    mast_tax,
    (
        select
            date_ymd
        from
            mast_calendar
        where
            date_ymd >= date(date_add(sysdate(), interval - 1 day))
    ) q1
where
    mast_tax.accept_s_ymd <= q1.date_ymd
group by
    q1.date_ymd;