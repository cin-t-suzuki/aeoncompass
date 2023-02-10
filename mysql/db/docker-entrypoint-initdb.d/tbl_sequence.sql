USE ac_travel;

-- INSERTING into tbl_sequence
/* SET DEFINE OFF; */
INSERT INTO
	tbl_sequence (name, current_val, increment)
VALUES
	('id@hotel_supervisor_hotel', 1, 1) -- 必要分のレコードを追記
;