/*******************************************************************************
* MySQL stored procedures for webtrees
*
* vim:foldmarker=DELIMITER\ \/\/,DELIMITER\ \;
* vim:foldmethod=marker
*
* zo=open, zc=close, zR=open-all, zM=close-all, zk=prev, zj=next
*
* Copyright (c) 2008-2010 Greg Roach (fisharebest@gmail.com)
*******************************************************************************/

/* TODO: @WT_USER_ID */
/* TODO: @WT_GED_ID */
/* TODO: @WT_IP_ADDRESS */
/* TODO: @WT_SERVER_JD */

/******************************************************************************/
/* FUNCTION: ADD_TO_LOG                                                       */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##add_to_log` //
CREATE PROCEDURE `##add_to_log`(
	p_message TEXT,
	p_type    ENUM('auth', 'config', 'debug', 'edit', 'error', 'media', 'search')
)
	COMMENT 'includes/authentication.php:AddToLog()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	INSERT INTO `##log` (log_type, log_message, ip_address, user_id, gedcom_id)
	VALUES (p_type, p_message, IFNULL(@WT_IP_ADDRESS, 'cli'), @WT_USER_ID, @WT_GED_ID);
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: DAYS_IN_MONTH                                                    */
/* Determine the number of days in a given month/year.                        */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##days_in_month` //
CREATE FUNCTION `##days_in_month` (
	p_calendar VARCHAR(16),
	p_year     INTEGER,
	p_month    INTEGER
) RETURNS    INTEGER
	COMMENT 'Determine the number of days in a given month'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE p_calendar
	WHEN '@#DGREGORIAN@' THEN
		CASE
		WHEN p_month IN (1,3,5,7,8,10,12) THEN RETURN 31;
		WHEN p_month IN (4,6,9,11) THEN RETURN 30;
		WHEN `##is_leap_year`(p_calendar, p_year) THEN RETURN 29;
		ELSE RETURN 28;
		END CASE;
	WHEN '@#DJULIAN@' THEN
		CASE
		WHEN p_month IN (1,3,5,7,8,10,12) THEN RETURN 31;
		WHEN p_month IN (4,6,9,11) THEN RETURN 30;
		WHEN `##is_leap_year`(p_calendar, p_year) THEN RETURN 29;
		ELSE RETURN 28;
		END CASE;
	WHEN '@#DFRENCH R@' THEN
		CASE
		WHEN p_month<13 THEN RETURN 30;
		WHEN `##is_leap_year`(p_calendar, p_year) THEN RETURN 6;
		ELSE RETURN 5;
		END CASE;
	ELSE
		/* General case */
		BEGIN
			DECLARE next_year  INTEGER DEFAULT p_year;
			DECLARE next_month INTEGER DEFAULT p_month;
			CALL `##next_month`(p_calendar, next_year, next_month);
			RETURN `##ym_to_jd`(p_calendar, next_year, next_month)-`##ym_to_jd`(p_calendar, p_year, p_month);
		END;
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: DAYS_IN_YEAR                                                    */
/* Calculate the number of days in a given year.                              */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##days_in_year` //
CREATE FUNCTION `##days_in_year` (
	p_calendar VARCHAR(16),
	p_year     INTEGER
) RETURNS   INTEGER
	COMMENT 'Determine the number of days in a given year'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE
	WHEN p_calendar IN ('@#DFRENCH R', '@#DGREGORIAN@', '@#DJULIAN@') THEN
		RETURN CASE WHEN `##is_leap_year`(p_calendar, p_year) THEN 366 ELSE 365 END;
	ELSE
		/* General case */
		RETURN `##y_to_jd`(p_calendar, `##next_year`(p_calendar, p_year))-`##y_to_jd`(p_calendar, p_year);
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: DELETE_GEDCOM                                                    */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##delete_gedcom` //
CREATE PROCEDURE `##delete_gedcom`(
	p_gedcom_id INTEGER
)
	COMMENT 'includes/functions/functions_db.php:delete_gedcom()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	IF `##get_site_setting`('DEFAULT_GEDCOM', NULL)=`##get_gedcom_from_id`(p_gedcom_id) THEN
		CALL `##set_site_setting`('DEFAULT_GEDCOM', '');
	END IF;
	
	/* Don't delete the logs. */
	UPDATE `##log` SET gedcom_id:=NULL WHERE gedcom_id=p_gedcom_id;

	DELETE `##block_setting` FROM `##block_setting` JOIN `##block` USING (block_id) WHERE gedcom_id=p_gedcom_id;
	DELETE FROM `##block`               WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##dates`               WHERE d_file    =p_gedcom_id;
	DELETE FROM `##families`            WHERE f_file    =p_gedcom_id;
	DELETE FROM `##user_gedcom_setting` WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##gedcom_setting`      WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##individuals`         WHERE i_file    =p_gedcom_id;
	DELETE FROM `##link`                WHERE l_file    =p_gedcom_id;
	DELETE FROM `##media`               WHERE m_gedfile =p_gedcom_id;
	DELETE FROM `##media_mapping`       WHERE mm_gedfile=p_gedcom_id;
	DELETE FROM `##module_privacy`      WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##name`                WHERE n_file    =p_gedcom_id;
	DELETE FROM `##next_id`             WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##other`               WHERE o_file    =p_gedcom_id;
	DELETE FROM `##placelinks`          WHERE pl_file   =p_gedcom_id;
	DELETE FROM `##places`              WHERE p_file    =p_gedcom_id;
	DELETE FROM `##sources`             WHERE s_file    =p_gedcom_id;
	DELETE FROM `##hit_counter`         WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##change`              WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##default_resn`        WHERE gedcom_id =p_gedcom_id;
	DELETE FROM `##gedcom`              WHERE gedcom_id =p_gedcom_id;

END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: EXISTS_PENDING_CHANGE                                            */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##exists_pending_change` //
CREATE FUNCTION `##exists_pending_change`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     BOOLEAN
	COMMENT 'includes/functions/functions_db.php:exists_pending_change()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	RETURN `##user_can_accept`(p_user_id, p_gedcom_id) AND EXISTS (
		SELECT 1 FROM `##change` WHERE status='pending' AND gedcom_id=p_gedcom_id
	);
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_FAMILY_RECORD                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_family_record` //
CREATE FUNCTION `##find_family_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_family_record()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_NO_CACHE f_gedcom INTO l_gedcom
	FROM `##families`
	WHERE f_file=p_gedcom_id AND f_id=p_xref;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_GEDCOM_RECORD                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_gedcom_record` //
CREATE FUNCTION `##find_gedcom_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER,
	p_pending   BOOLEAN
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_gedcom_record()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;

	IF p_pending THEN
		SET l_gedcom:=`##find_updated_record`(p_xref, p_gedcom_id);
	END IF;

	IF l_gedcom IS NULL THEN
		SET l_gedcom:=`##find_person_record`(p_xref, p_gedcom_id);
		IF l_gedcom IS NULL THEN
			SET l_gedcom:=`##find_family_record`(p_xref, p_gedcom_id);
			IF l_gedcom IS NULL THEN
				SET l_gedcom:=`##find_source_record`(p_xref, p_gedcom_id);
				IF l_gedcom IS NULL THEN
					SET l_gedcom:=`##find_media_record`(p_xref, p_gedcom_id);
					IF l_gedcom IS NULL THEN
						SET l_gedcom:=`##find_other_record`(p_xref, p_gedcom_id);
					END IF;
				END IF;
			END IF;
		END IF;
	END IF;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_MEDIA_RECORD                                                */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_media_record` //
CREATE FUNCTION `##find_media_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_media_record()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_NO_CACHE m_gedrec INTO l_gedcom
	FROM `##media`
	WHERE m_gedfile=p_gedcom_id AND m_media=p_xref;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_OTHER_RECORD                                                */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_other_record` //
CREATE FUNCTION `##find_other_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_other_record()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_NO_CACHE o_gedcom INTO l_gedcom
	FROM `##other`
	WHERE o_file=p_gedcom_id AND o_id=p_xref;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_PERSON_RECORD                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_person_record` //
CREATE FUNCTION `##find_person_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_person_record()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_NO_CACHE i_gedcom INTO l_gedcom
	FROM `##individuals`
	WHERE i_file=p_gedcom_id AND i_id=p_xref;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_SOURCE_RECORD                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_source_record` //
CREATE FUNCTION `##find_source_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_source_record()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_NO_CACHE s_gedcom INTO l_gedcom
	FROM `##sources`
	WHERE s_file=p_gedcom_id AND s_id=p_xref;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: FIND_UPDATED_RECORD                                              */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##find_updated_record` //
CREATE FUNCTION `##find_updated_record`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     MEDIUMTEXT
	COMMENT 'includes/functions/functions_db.php:find_updated_record()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom MEDIUMTEXT;
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_NO_CACHE new_gedcom INTO l_gedcom
	FROM `##change`
	WHERE gedcom_id=p_gedcom_id AND xref=p_xref AND status='pending'
	ORDER BY change_id DESC LIMIT 1;

	RETURN l_gedcom;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GEDCOM_RECORD_TYPE                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##gedcom_record_type` //
CREATE FUNCTION `##gedcom_record_type`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER
) RETURNS     VARCHAR(15)
	COMMENT 'includes/functions/functions_db.php:gedcom_record_type()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_type VARCHAR(15);
	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT 'INDI' INTO l_type FROM `##individuals`
	WHERE i_file=p_gedcom_id AND i_id=p_xref;

	IF l_type IS NOT NULL THEN
		RETURN l_type;
	END IF;

	SELECT 'FAM' INTO l_type FROM `##families`
	WHERE f_file=p_gedcom_id AND f_id=p_xref;

	IF l_type IS NOT NULL THEN
		RETURN l_type;
	END IF;

	SELECT 'SOUR' INTO l_type FROM `##sources`
	WHERE s_file=p_gedcom_id AND s_id=p_xref;

	IF l_type IS NOT NULL THEN
		RETURN l_type;
	END IF;

	SELECT 'OBJE' INTO l_type FROM `##media`
	WHERE m_gedfile=p_gedcom_id AND m_media=p_xref;

	IF l_type IS NOT NULL THEN
		RETURN l_type;
	END IF;

	SELECT o_type INTO l_type FROM `##other`
	WHERE o_file=p_gedcom_id AND o_id=p_xref;

	RETURN l_type;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_ADMIN_USER_COUNT                                             */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_admin_user_count` //
CREATE FUNCTION `##get_admin_user_count`(
) RETURNS BOOLEAN
	COMMENT 'includes/functions/functions_db.php:get_admin_user_count()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_count INTEGER;

	SELECT SQL_CACHE COUNT(*) INTO l_count
	FROM `##user_setting`
	WHERE setting_name='canadmin' AND setting_value;

	RETURN l_count;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_BLOCK_SETTING                                                */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_block_setting` //
CREATE FUNCTION `##get_block_setting`(
	p_block_id      INTEGER,
	p_setting_name  VARCHAR(32),
	p_default_value TEXT       
) RETURNS         TEXT       
	COMMENT 'includes/functions/functions_db.php:get_block_setting()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_setting_value TEXT;

	DECLARE CONTINUE HANDLER FOR NOT FOUND RETURN p_default_value;

	SELECT SQL_CACHE setting_value INTO l_setting_value
	FROM   `##block_setting`
	WHERE  block_id=p_block_id AND setting_name=p_setting_name;

	RETURN l_setting_value;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_GEDCOM_FROM_ID                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_gedcom_from_id` //
CREATE FUNCTION `##get_gedcom_from_id`(
	p_gedcom_id INTEGER
) RETURNS     VARCHAR(255)
	COMMENT 'includes/functions/functions_db.php:get_gedcom_from_id()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom_name VARCHAR(255);

	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_CACHE gedcom_name INTO l_gedcom_name
	FROM  `##gedcom`
	WHERE  gedcom_id=p_gedcom_id;

	RETURN l_gedcom_name;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_ID_FROM_GEDCOM                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_id_from_gedcom` //
CREATE FUNCTION `##get_id_from_gedcom`(
	p_gedcom_name VARCHAR(255)
) RETURNS       INTEGER
	COMMENT 'includes/functions/functions_db.php:get_id_from_gedcom()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_gedcom_id INTEGER;

	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_CACHE gedcom_id INTO l_gedcom_id
	FROM  `##gedcom`
	WHERE  gedcom_name=p_gedcom_name;

	RETURN l_gedcom_id;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_GEDCOM_SETTING                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_gedcom_setting` //
CREATE FUNCTION `##get_gedcom_setting`(
	p_gedcom_id     INTEGER,
	p_setting_name  VARCHAR(32 ),
	p_default_value VARCHAR(255)
) RETURNS         VARCHAR(255)
	COMMENT 'includes/functions/functions_db.php:get_gedcom_setting()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_setting_value VARCHAR(255);

	DECLARE CONTINUE HANDLER FOR NOT FOUND RETURN p_default_value;

	SELECT SQL_CACHE setting_value INTO l_setting_value
	FROM   `##gedcom_setting`
	WHERE  gedcom_id=p_gedcom_id AND setting_name=p_setting_name;

	RETURN l_setting_value;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_MODULE_SETTING                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_module_setting` //
CREATE FUNCTION `##get_module_setting`(
	p_module_name   VARCHAR(32),
	p_setting_name  VARCHAR(32),
	p_default_value TEXT       
) RETURNS         TEXT       
	COMMENT 'includes/functions/functions_db.php:get_module_setting()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_setting_value TEXT;

	DECLARE CONTINUE HANDLER FOR NOT FOUND RETURN p_default_value;

	SELECT SQL_CACHE setting_value INTO l_setting_value
	FROM   `##module_setting`
	WHERE  module_name=p_module_name AND setting_name=p_setting_name;

	RETURN l_setting_value;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_NEW_XREF                                                     */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_new_xref` //
CREATE FUNCTION `##get_new_xref`(
	p_type      VARCHAR(15),
	p_gedcom_id INTEGER
) RETURNS     VARCHAR(20)
	COMMENT 'includes/functions/functions.php:get_new_xref()'
	NOT DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	DECLARE l_prefix VARCHAR(20);
	DECLARE l_number DECIMAL(20) DEFAULT 1;
	CASE p_type
		WHEN 'INDI' THEN
			SET l_prefix:=`##get_gedcom_setting`(p_gedcom_id, 'GEDCOM_ID_PREFIX', 'I');
		WHEN 'FAM' THEN
			SET l_prefix:=`##get_gedcom_setting`(p_gedcom_id, 'FAM_ID_PREFIX',    'F');
		WHEN 'OBJE' THEN
			SET l_prefix:=`##get_gedcom_setting`(p_gedcom_id, 'MEDIA_ID_PREFIX',  'M');
		WHEN 'SOUR' THEN
			SET l_prefix:=`##get_gedcom_setting`(p_gedcom_id, 'SOURCE_ID_PREFIX', 'S');
		WHEN 'REPO' THEN
			SET l_prefix:=`##get_gedcom_setting`(p_gedcom_id, 'REPO_ID_PREFIX',   'R');
		ELSE
			SET l_prefix:=LEFT(p_type, 1);
	END CASE;

	BEGIN
		DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;
		SELECT next_id INTO l_number
		FROM   `##next_id`
		WHERE  record_type=p_type AND gedcom_id=p_gedcom_id;
	END;

	/* Make sure this number is not already used */
	WHILE find_gedcom_record(CONCAT(l_prefix, l_number), p_gedcom_id, TRUE) DO
		SET l_number:=l_number+1;
	END WHILE;

	REPLACE INTO `##next_id` (gedcom_id, record_type, next_id) VALUES (p_gedcom_id, p_type, l_number);

	RETURN CONCAT(l_prefix, l_number);
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_NON_ADMIN_USER_COUNT                                         */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_non_admin_user_count` //
CREATE FUNCTION `##get_non_admin_user_count`(
) RETURNS BOOLEAN
	COMMENT 'includes/functions/functions_db.php:get_non_admin_user_count()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_count INTEGER;

	SELECT SQL_CACHE COUNT(*) INTO l_count
	FROM `##user_setting`
	WHERE setting_name='canadmin' AND setting_value;

	RETURN l_count;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_SITE_SETTING                                                 */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_site_setting` //
CREATE FUNCTION `##get_site_setting`(
	p_setting_name  VARCHAR(32 ),
	p_default_value VARCHAR(255)
) RETURNS         VARCHAR(255)
	COMMENT 'includes/functions/functions_db.php:get_site_setting()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_setting_value VARCHAR(255);

	DECLARE CONTINUE HANDLER FOR NOT FOUND RETURN p_default_value;

	SELECT SQL_CACHE setting_value INTO l_setting_value
	FROM   `##site_setting`
	WHERE  setting_name=p_setting_name;

	RETURN l_setting_value;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_ACCESS_LEVEL                                            */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_access_level` //
CREATE FUNCTION `##get_user_access_level`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     INTEGER
	COMMENT 'includes/functions/functions_privacy.php:getUserAccessLevel()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	IF p_user_id IS NOT NULL THEN
		IF `##user_gedcom_admin`(p_user_id, p_gedcom_id) THEN
			RETURN 0; /* WT_PRIV_NONE */
		ELSE
			IF `##user_can_access`(p_user_id, p_gedcom_id) THEN
				RETURN 1; /* WT_PRIV_USER */
			ELSE
				RETURN 2; /* WT_PRIV_PUBLIC */
			END IF;
		END IF;
	ELSE
		RETURN 2; /* WT_PRIV_PUBLIC */
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_GEDCOM_ID                                               */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_gedcom_id` //
CREATE FUNCTION `##get_user_gedcom_id`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     VARCHAR(20)
	COMMENT 'includes/authentication.php:getUserGedcomId()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN `##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'gedcomid', NULL);
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_GEDCOM_SETTING                                          */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_gedcom_setting` //
CREATE FUNCTION `##get_user_gedcom_setting`(
	p_user_id       INTEGER,
	p_gedcom_id     INTEGER,
	p_setting_name  VARCHAR(32 ),
	p_default_value VARCHAR(255)
) RETURNS         VARCHAR(255)
	COMMENT 'includes/functions/functions_db.php:get_user_gedcom_setting()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_setting_value VARCHAR(255);

	DECLARE CONTINUE HANDLER FOR NOT FOUND RETURN p_default_value;

	SELECT SQL_CACHE setting_value INTO l_setting_value
	FROM   `##user_gedcom_setting`
	WHERE  user_id=p_user_id AND gedcom_id=p_gedcom_id AND setting_name=p_setting_name;

	RETURN l_setting_value;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_ID                                                      */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_id` //
CREATE FUNCTION `##get_user_id`(
	p_user_name VARCHAR(32)
) RETURNS     INTEGER
	COMMENT 'includes/functions/functions_db.php:get_user_id()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_user_id INTEGER;

	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_CACHE user_id INTO l_user_id
	FROM  `##user`
	WHERE  user_name=p_user_name;

	RETURN l_user_id;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_NAME                                                    */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_name` //
CREATE FUNCTION `##get_user_name`(
	p_user_id INTEGER
) RETURNS   VARCHAR(32)
	COMMENT 'includes/functions/functions_db.php:get_user_name()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_user_name VARCHAR(32);

	DECLARE CONTINUE HANDLER FOR NOT FOUND BEGIN END;

	SELECT SQL_CACHE user_name INTO l_user_name
	FROM  `##user`
	WHERE  user_id=p_user_id;

	RETURN l_user_name;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GETUSEREMAIL                                                     */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##getuseremail` //
CREATE FUNCTION `##getuseremail`(
	p_user_id       INTEGER
) RETURNS         VARCHAR(64)
	COMMENT 'includes/authentication.php:getUserEmail()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_email VARCHAR(64);

	SELECT SQL_CACHE email INTO l_email
	FROM   `##user`
	WHERE  user_id=p_user_id;

	RETURN l_email;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GETUSERFULLNAME                                                  */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##getuserfullname` //
CREATE FUNCTION `##getuserfullname`(
	p_user_id       INTEGER
) RETURNS         VARCHAR(64)
	COMMENT 'includes/authentication.php:getUserFullName()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_real_name VARCHAR(64);

	SELECT SQL_CACHE real_name INTO l_real_name
	FROM   `##user`
	WHERE  user_id=p_user_id;

	RETURN l_real_name;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_SETTING                                                 */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_setting` //
CREATE FUNCTION `##get_user_setting`(
	p_user_id       INTEGER,
	p_setting_name  VARCHAR(32),
	p_default_value VARCHAR(255)
) RETURNS         VARCHAR(255)
	COMMENT 'includes/functions/functions_db.php:get_user_setting()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	DECLARE l_setting_value VARCHAR(255);

	DECLARE CONTINUE HANDLER FOR NOT FOUND RETURN p_default_value;

	SELECT SQL_CACHE setting_value INTO l_setting_value
	FROM   `##user_setting`
	WHERE  user_id=p_user_id AND setting_name=p_setting_name;

	RETURN l_setting_value;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: GET_USER_ROOT_ID                                                 */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_user_root_id` //
CREATE FUNCTION `##get_user_root_id`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     VARCHAR(20)
	COMMENT 'includes/authentication.php:getUserRootId()'
	DETERMINISTIC SQL SECURITY DEFINER READS SQL DATA
BEGIN
	RETURN COALESCE(
		`##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'rootid',   NULL),
		`##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'gedcomid', NULL)
	);
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: HTMLSPECIALCHARS                                                 */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##htmlspecialchars` //
CREATE FUNCTION `##htmlspecialchars`(
	p_text  TEXT
) RETURNS TEXT
	COMMENT 'PHP:htmlspecialchars()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(p_text, '&', '&amp;'), '"', '&quot;'), '''', '&#39;'), '<', '&lt;'), '>', '&gt;');
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: IS_DEAD                                                          */
/* Requires: @WT_SERVER_JD                                                    */  
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##is_dead` //
CREATE FUNCTION `##is_dead`(
	p_xref      VARCHAR(20),
	p_gedcom_id INTEGER,
	p_gedrec    MEDIUMTEXT
) RETURNS     BOOLEAN
	COMMENT 'includes/functions/functions_privacy.php:is_dead()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	/* Maximum natural lifespan */
	DECLARE l_max_alive_age INTEGER DEFAULT `##get_gedcom_setting`(p_gedcom_id, 'MAX_ALIVE_AGE', 100*365);
	/* Normal age range of fertility */
	DECLARE l_min_birth_age INTEGER DEFAULT 15*365;
	DECLARE l_max_birth_age INTEGER DEFAULT 45*365;
	/* Earliest age to marry */
	DECLARE l_min_marr_age INTEGER DEFAULT 15*365;
	/* Maximum difference in spouse ages */
	DECLARE l_max_marr_age_diff INTEGER DEFAULT 20*365;

	/* Treat recently dead as still living */
	DECLARE l_keep_alive_years_birth INTEGER DEFAULT `##get_gedcom_setting`(p_gedcom_id, 'KEEP_ALIVE_YEARS_BIRTH', 0);
	DECLARE l_keep_alive_years_death INTEGER DEFAULT `##get_gedcom_setting`(p_gedcom_id, 'KEEP_ALIVE_YEARS_DEATH', 0);

	/* Have we already calculated this value? */
	DECLARE l_is_dead INTEGER;
	SELECT i_isdead INTO l_is_dead FROM `##individuals` WHERE i_file=p_gedcom_id AND i_id=p_xref;

	IF l_is_dead=0 THEN
		/* Known to be alive */
		RETURN FALSE;
	END IF;

	IF l_is_dead=1 AND l_keep_alive_years_birth=0 AND l_keep_alive_years_death=0 THEN
		/* Known to be dead */
		RETURN TRUE;
	END IF;

	IF (
		/* Explicit death record? */
		p_gedrec REGEXP '\n1 (DEAT|BURI|CREM)( Y|(\n2[^\n]*)*\n2 (DATE|PLAC) )'
		) OR EXISTS(
		/* Any personal event a long time ago? */
		SELECT 1
		FROM   `##dates`
		WHERE  d_gid=p_xref AND d_file=p_gedcom_id
		AND    d_type<>'CHAN' AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age
	) OR EXISTS(
		/* Any family event a long time ago? */
		SELECT 1
		FROM   `##link`
		JOIN   `##dates` ON (d_file=l_file AND d_gid=l_to)
		WHERE  l_from=p_xref AND l_file=p_gedcom_id AND l_type='FAMS'
		AND    d_type<>'CHAN' AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age-l_min_marr_age
	) OR EXISTS(
		/* Spouse born (or had any other event) a long time ago? */
		SELECT 1
		FROM   `##link` l1
		JOIN   `##link` l2 ON (l2.l_file=l1.l_file AND l2.l_to=l1.l_to AND l2.l_type='FAMS' AND l1.l_from<>l2.l_from)
		JOIN   `##dates` d ON (d.d_file=l1.l_file AND d.d_gid=l2.l_from)
		WHERE  l1.l_from=p_xref AND l1.l_file=p_gedcom_id AND l1.l_type='FAMS'
		AND    d_type <> 'CHAN' AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age-l_max_marr_age_diff
	) OR EXISTS(
		/* Parents lived a long time ago? */
		SELECT 1
		FROM   `##link` l1
		JOIN   `##link` l2 ON (l2.l_file=l1.l_file AND l2.l_to=l1.l_to AND l2.l_type='FAMS')
		JOIN   `##dates` d ON (d.d_file=l1.l_file AND d.d_gid=l2.l_from)
		WHERE  l1.l_from=p_xref AND l1.l_file=p_gedcom_id AND l1.l_type='FAMC'
		AND (
			d_type <> 'CHAN' AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age-l_max_birth_age
		OR
			d_type IN ('DEAT', 'BURI', 'CREM') AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age
		)
	) OR EXISTS(
		/* Children lived a long time ago? */
		SELECT 1
		FROM   `##link` l1
		JOIN   `##link` l2 ON (l2.l_file=l1.l_file AND l2.l_to=l1.l_to AND l2.l_type='FAMC')
		JOIN   `##dates` d ON (d.d_file=l1.l_file AND d.d_gid=l2.l_from)
		WHERE  l1.l_from=p_xref AND l1.l_file=p_gedcom_id AND l1.l_type='FAMS'
		AND    d_type<>'CHAN' AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age+l_min_birth_age
	) OR EXISTS(
		/* Grandparents lived a long time ago? */
		SELECT 1
		FROM   `##link` l1
		JOIN   `##link` l2 ON (l2.l_file=l1.l_file AND l2.l_to  =l1.l_to   AND l2.l_type='FAMS')
		JOIN   `##link` l3 ON (l3.l_file=l1.l_file AND l3.l_from=l2.l_from AND l3.l_type='FAMC')
		JOIN   `##link` l4 ON (l4.l_file=l1.l_file AND l4.l_to  =l3.l_to   AND l4.l_type='FAMS')
		JOIN   `##dates` d ON ( d.d_file=l1.l_file AND l4.l_from=d.d_gid)
		WHERE  l1.l_from='I001' AND l1.l_file=1 AND l1.l_type='FAMC'
		AND (
			d_type <> 'CHAN' AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age-l_max_birth_age*2
		OR
			d_type IN ('DEAT', 'BURI', 'CREM') AND d_julianday2 BETWEEN 1 AND @WT_SERVER_JD-l_max_alive_age-l_max_birth_age
		)
	) THEN
		/* Make a permanent record of the is_dead status */
		IF l_is_dead=-1 THEN
			UPDATE `##individuals` SET i_isdead=1 WHERE i_file=p_gedcom_id AND i_id=p_xref;
		END IF;
		/* Keep recently dead people alive? */
		IF l_keep_alive_years_birth>0 AND EXISTS(
			SELECT 1
			FROM   `##dates`
			WHERE  d_file=p_gedcom_id AND d_gid=p_xref AND d_type IN ('BIRT','BAPM','CHR')
			AND    d_julianday2 > @WT_SERVER_JD-l_keep_alive_years_birth*365
		) OR l_keep_alive_years_death>0 AND EXISTS(
			SELECT 1
			FROM   `##dates`
			WHERE  d_file=p_gedcom_id AND d_gid=p_xref AND d_type IN ('DEAT','BURI','CREM')
			AND    d_julianday2 > @WT_SERVER_JD-l_keep_alive_years_death*365
		) THEN
			RETURN FALSE;
		ELSE
			RETURN TRUE;
		END IF;
	ELSE
		/* Person could still be alive */
		UPDATE `##individuals` SET i_isdead=0 WHERE i_file=p_gedcom_id AND i_id=p_xref;
		RETURN FALSE;
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: IS_LEAP_YEAR                                                     */
/* Determine whether a given year is a leap year.                             */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##is_leap_year` //
CREATE FUNCTION `##is_leap_year`(
	p_calendar VARCHAR(16),
	p_year     INTEGER
) RETURNS BOOLEAN
	COMMENT 'Determine whether a given year is a leap year'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE p_calendar
	WHEN '@#DGREGORIAN@' THEN
		RETURN MOD(p_year,4)=0 AND MOD(p_year,100)<>0 || MOD(p_year,400)=0;
	WHEN '@#DJULIAN@' THEN
		IF p_year<0 THEN
			RETURN MOD(1-p_year,4)=0;
		ELSE
			RETURN MOD(p_year,4)=0;
		END IF;
	WHEN '@#DHEBREW@' THEN
		RETURN MOD(7*p_year+1,19)<7;
	WHEN '@#DFRENCH R@' THEN
		RETURN MOD(p_year,4)=3;
	WHEN '@#HIJRI@' THEN
		RETURN FALSE;
	WHEN '@#DSHAMSI@#' THEN
		RETURN FALSE;
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: JD_TO_YMD                                                       */
/* Calculate the year/month/day for a given julian day number.                */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##jd_to_ymd` //
CREATE PROCEDURE `##jd_to_ymd` (
	IN  p_jd       INTEGER,
	IN  p_calendar VARCHAR(16),
	OUT p_year     INTEGER,
	OUT p_month    INTEGER,
	OUT p_day      INTEGER
)
	COMMENT 'Find the calendar date for a julian day number'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE p_era
	WHEN '@#DFRENCH R@' THEN
		SET p_year :=FLOOR((p_jd-2375109)*4/1461)-1;
		SET p_month:=FLOOR((p_jd-2375475-p_year*365-FLOOR(p_year/4))/30)+1;
		SET p_day  :=p_jd-2375444-p_month*30-p_year*365-FLOOR(p_year/4);
	WHEN '@#DGREGORIAN@' THEN
		BEGIN
			DECLARE l_a INTEGER DEFAULT p_jd+32044;
			DECLARE l_b INTEGER DEFAULT FLOOR((4*l_a+3)/146097);
			DECLARE l_c INTEGER DEFAULT l_a-FLOOR(l_b*146097/4);
			DECLARE l_d INTEGER DEFAULT FLOOR((4*l_c+3)/1461);
			DECLARE l_e INTEGER DEFAULT l_c-FLOOR((1461*l_d)/4);
			DECLARE l_f INTEGER DEFAULT FLOOR((5*l_e+2)/153);
			SET p_day:=l_e-FLOOR((153*l_f+2)/5)+1;
			SET p_month:=l_f+3-12*FLOOR(l_f/10);
			SET p_year:=l_b*100+l_d-4800+FLOOR(l_f/10);
		END;
	WHEN '@#DHEBREW@' THEN
		SET p_year:=FLOOR((p_jd-347997)/365)+1;
		SET p_month:=FLOOR((p_jd-heb_y_to_jd(p_calendar, p_era, p_year))/19)+1;
		IF p_month>6 AND NOT is_leap_year(p_calendar, p_era, p_year) THEN
			SET p_month:=p_month-1;
		END IF;
		SET p_day:=p_jd-ymd_to_jd(p_calendar, p_era, p_year, p_month, 1)+1;
	WHEN '@#DHIJRI@' THEN
		SET p_year:=FLOOR((30*(p_jd-1948440)+10646)/10631);
		SET p_month:=FLOOR((11*(p_jd-p_year*354-FLOOR((3+11*p_year)/30)-1948086)+330)/325);
		SET p_day:=p_jd-29*(p_month-1)-FLOOR((6*p_month-1)/11)-p_year*354-FLOOR((3+11*p_year)/30)-1948085;
	WHEN '@#DJULIAN' THEN
		BEGIN
			DECLARE l_c INTEGER DEFAULT p_jd+32082;
			DECLARE l_d INTEGER DEFAULT FLOOR((4*l_c+3)/1461);
			DECLARE l_e INTEGER DEFAULT l_c-FLOOR(1461*l_d/4);
			DECLARE l_f INTEGER DEFAULT FLOOR((5*l_e+2)/153);
			SET p_day:=l_e-FLOOR((153*l_f+2)/5)+1;
			SET p_month:=l_f+3-12*FLOOR(l_f/10);
			IF p_jd<1721426 THEN
				SET p_year:=4801-l_d-FLOOR(l_f/10);
			ELSE
				SET p_year:=4800-l_d-FLOOR(l_f/10);
			END IF;
		END;
	WHEN '@#DSHAMSI@' THEN
		BEGIN
			DECLARE depoch INTEGER DEFAULT jdn - ymd_to_jd_shamsi(475, 1, 1);
			DECLARE cycle  INTEGER DEFAULT FLOOR(depoch/1029983);
			DECLARE cyear  INTEGER DEFAULT MOD(depoch, 1029983);
			DECLARE ycycle INTEGER;
			DECLARE aux1   INTEGER;
			DECLARE aux2   INTEGER;
			DECLARE yday   INTEGER;
			IF cyear=1029982 THEN
				SET ycycle:=2820;
			ELSE
				SET aux1:=FLOOR(cyear/366);
				SET aux2:=MOD(cyear, 366);
				SET ycycle:=(((2134 * aux1) + (2816 * aux2) + 2815) / 1028522) + aux1 + 1;
			END IF;
			SET p_year := ycycle + (2820 * cycle) + 474;
			IF p_year <= 0 THEN
				SET p_year := p_year - 1;
			END IF;
			SET yday := (jdn - ymd_to_jd_shamsi(p_year, 1, 1)) + 1;
			IF yday <= 186 THEN
				SET p_month:=CEIL(yday/31);
			ELSE
				SET p_month:=CEIL((yday-6)/30);
			END IF;
			SET p_day:=(p_jd-ymd_to_jd_shamsi(p_year, iMonth, 1))+1;
		END;
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: NEXT_MONTH                                                      */
/* Determine the month/year that follows a given month/year.                  */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##next_month` //
CREATE PROCEDURE `##next_month` (
	IN    p_calendar VARCHAR(16),
	INOUT p_year     INTEGER,
	INOUT p_month    INTEGER
)
	COMMENT 'Find the year and month that follows a given year and month'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE p_calendar
	WHEN '@#DGREGORIAN' THEN
		IF p_month=12 THEN
			SET p_month:=1;
			SET p_year:=p_year+1;
		ELSE
			SET p_month:=p_month+1;
		END IF;
	WHEN '@#DJULIAN' THEN
		IF p_month=12 THEN
			SET p_month:=1;
			IF p_year=-1 THEN
				SET p_year:=1;
			ELSE
				SET p_year:=p_year+1;
			END IF;
		ELSE
			SET p_month:=p_month+1;
		END IF;
	WHEN '@#DFRENCH R@' THEN
		IF p_month=13 THEN
			SET p_month:=1;
			SET p_year:=p_year+1;
		ELSE
			SET p_month:=p_month+1;
		END IF;
	WHEN '@#DHEBREW@' THEN
		IF p_month=13 THEN
			SET p_month:=1;
			SET p_year:=p_year+1;
		ELSEIF p_month=6 THEN
			IF `##is_leap_year`(p_calendar, p_year) THEN
				SET p_month:=7;
			ELSE
				SET p_month:=8;
			END IF;
		ELSE
			SET p_month:=p_month+1;
		END IF;
	WHEN '@#DHIJRI@' THEN
		IF p_month=12 THEN
			SET p_month:=1;
			SET p_year:=p_year+1;
		ELSE
			SET p_month:=p_month+1;
		END IF;
	WHEN '@#DSHAMSI@' THEN
		IF p_month=12 THEN
			SET p_month:=1;
			SET p_year:=p_year+1;
		ELSE
			SET p_month:=p_month+1;
		END IF;
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: NEXT_YEAR()                                                      */
/* Determine the year number that follows a given year.                       */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##next_year` //
CREATE FUNCTION `##next_year` (
	p_calendar VARCHAR(16),
	p_year     INTEGER
) RETURNS    INTEGER
	COMMENT 'Find the year that follows a given year'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	IF p_calendar='@#JULIAN@' AND p_year=-1 THEN
		RETURN 1;
	ELSE
		RETURN p_year+1;
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: SET_BLOCK_SETTING                                               */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##set_block_setting` //
CREATE PROCEDURE `##set_block_setting`(
	IN p_block_id      INTEGER,
	IN p_setting_name  VARCHAR(32),
	IN p_setting_value TEXT       
)
	COMMENT 'includes/functions/functions_db.php:set_block_setting()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	IF p_setting_value IS NULL THEN
		DELETE FROM `##block_setting`
		WHERE block_id=p_block_id AND setting_name=p_setting_name;
	ELSE
		REPLACE INTO `##block_setting`(
			block_id, setting_name, setting_value
		) VALUES (
			p_block_id, p_setting_name, p_setting_value
		);
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: SET_GEDCOM_SETTING                                              */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##set_gedcom_setting` //
CREATE PROCEDURE `##set_gedcom_setting`(
	IN p_gedcom_id     INTEGER,
	IN p_setting_name  VARCHAR(32 ),
	IN p_setting_value VARCHAR(255)
)
	COMMENT 'includes/functions/functions_db.php:set_gedcom_setting()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	DECLARE l_old_value VARCHAR(255) DEFAULT `##get_gedcom_setting`(p_gedcom_id, p_setting_name, NULL);

	IF NOT l_old_value=p_setting_value THEN
		CALL `##add_to_log`(CONCAT_WS('', 'Gedcom setting "', p_setting_name, '" set to "', p_setting_value, '"'), 'config');
	END IF;
	
	IF p_setting_value IS NULL THEN
		DELETE FROM `##gedcom_setting`
		WHERE gedcom_id=p_gedcom_id AND setting_name=p_setting_name;
	ELSE
		REPLACE INTO `##gedcom_setting`(
			gedcom_id, setting_name, setting_value
		) VALUES (
			p_gedcom_id, p_setting_name, p_setting_value
		);
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: SET_MODULE_SETTING                                              */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##set_module_setting` //
CREATE PROCEDURE `##set_module_setting`(
	IN p_module_name   VARCHAR(32),
	IN p_setting_name  VARCHAR(32),
	IN p_setting_value TEXT       
)
	COMMENT 'includes/functions/functions_db.php:set_module_setting()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	IF p_setting_value IS NULL THEN
		DELETE FROM `##module_setting`
		WHERE module_name=p_gedcom_name AND setting_name=p_setting_name;
	ELSE
		REPLACE INTO `##module_setting`(
			module_name, setting_name, setting_value
		) VALUES (
			p_module_name, p_setting_name, p_setting_value
		);
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: SET_SITE_SETTING                                                */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##set_site_setting` //
CREATE PROCEDURE `##set_site_setting`(
	IN p_setting_name  VARCHAR(32 ),
	IN p_setting_value VARCHAR(255)
)
	COMMENT 'includes/functions/functions_db.php:set_site_setting()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	DECLARE l_old_value VARCHAR(255) DEFAULT `##get_site_setting`(p_setting_name, NULL);

	IF NOT l_old_value=p_setting_value THEN
		CALL `##add_to_log`(CONCAT_WS('', 'Site setting "', p_setting_name, '" set to "', p_setting_value, '"'), 'config');
	END IF;
	
	IF p_setting_value IS NULL THEN
		DELETE FROM `##site_setting`
		WHERE setting_name=p_setting_name;
	ELSE
		REPLACE INTO `##site_setting`(
			setting_name, setting_value
		) VALUES (
			p_setting_name, p_setting_value
		);
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: SET_USER_GEDCOM_SETTING                                         */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##set_user_gedcom_setting` //
CREATE PROCEDURE `##set_user_gedcom_setting`(
	IN p_user_id       INTEGER,
	IN p_gedcom_id     INTEGER,
	IN p_setting_name  VARCHAR(32 ),
	IN p_setting_value VARCHAR(255)
)
	COMMENT 'includes/functions/functions_db.php:set_user_gedcom_setting()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	IF p_setting_value IS NULL THEN
		DELETE FROM `##user_gedcom_setting`
		WHERE user_id=p_user_id AND gedcom_id=p_gedcom_id AND setting_name=p_setting_name;
	ELSE
		REPLACE INTO `##user_gedcom_setting`(
			user_id, gedcom_id, setting_name, setting_value
		) VALUES (
			p_user_id, p_gedcom_id, p_setting_name, p_setting_value
		);
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: SET_USER_SETTING                                                */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##set_user_setting` //
CREATE PROCEDURE `##set_user_setting`(
	IN p_user_id       INTEGER,
	IN p_setting_name  VARCHAR(32 ),
	IN p_setting_value VARCHAR(255)
)
	COMMENT 'includes/functions/functions_db.php:set_user_setting()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	IF p_setting_value IS NULL THEN
		DELETE FROM `##user_setting`
		WHERE user_id=p_user_id AND setting_name=p_setting_name;
	ELSE
		REPLACE INTO `##user_setting`(
			user_id, setting_name, setting_value
		) VALUES (
			p_user_id, p_setting_name, p_setting_value
		);
	END IF;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_AUTO_ACCEPT                                                 */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_auto_accept` //
CREATE FUNCTION `##user_auto_accept`(
	p_user_id INTEGER
) RETURNS   BOOLEAN
	COMMENT 'includes/authentication.php:userAutoAccept()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN `##get_user_setting`(p_user_id, 'auto_accept', '0');
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_CAN_ACCEPT                                                  */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_can_accept` //
CREATE FUNCTION `##user_can_accept`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     BOOLEAN
	COMMENT 'includes/authentication.php:userCanAccept()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	/* A gedcom admin can accept, even if editing has been disabled */
	RETURN
		`##user_gedcom_admin`(p_user_id, p_gedcom_id) OR
		`##get_gedcom_setting`(p_gedcom_id, 'ALLOW_EDIT_GEDCOM', '0') AND
		`##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'canedit', 'none') IN ('admin', 'accept');
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_CAN_ACCESS                                                  */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_can_access` //
CREATE FUNCTION `##user_can_access`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     BOOLEAN
	COMMENT 'includes/authentication.php:userCanAccess()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN
		`##user_gedcom_admin`(p_user_id, p_gedcom_id) OR
		`##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'canedit', 'none') IN ('admin', 'accept', 'edit', 'access');
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_CAN_EDIT                                                    */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_can_edit` //
CREATE FUNCTION `##user_can_edit`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     BOOLEAN
	COMMENT 'includes/authentication.php:userCanEdit()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN
		`##get_gedcom_setting`(p_gedcom_id, 'ALLOW_EDIT_GEDCOM', '0') AND (
			`##user_is_admin`(p_user_id) OR
			`##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'canedit', 'none') IN ('admin', 'accept', 'edit')
		);
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: UPDATE_LINKS                                                     */
/******************************************************************************/

DELIMITER //
DROP PROCEDURE IF EXISTS `##update_links` //
CREATE PROCEDURE `##update_links`(
	IN p_xref      VARCHAR(20),
	IN p_gedcom_id INTEGER,
	IN p_gedcom    MEDIUMTEXT
)
	COMMENT 'includes/functions/functions_import.php:update_links()'
	DETERMINISTIC SQL SECURITY DEFINER MODIFIES SQL DATA
BEGIN
	DECLARE l_line MEDIUMTEXT;
	DECLARE l_tag  VARCHAR(15);
	DECLARE l_xref VARCHAR(20);
	WHILE p_gedcom <> '' DO
		SET l_line:=SUBSTRING_INDEX(p_gedcom, '\n', 1);
		SET p_gedcom:=SUBSTRING(p_gedcom FROM LENGTH(l_line)+2);
		IF l_line REGEXP '^[1-9] [A-Z_][A-Z0-9]{0,14} @[A-Z0-9_-]{1,20}@$' THEN
			SET l_tag :=SUBSTRING_INDEX(SUBSTRING_INDEX(l_line, ' ', 2), ' ', -1);
			SET l_xref:=SUBSTRING_INDEX(SUBSTRING_INDEX(l_line, '@', 2), '@', -1);
			INSERT IGNORE INTO `##link` (l_file, l_from, l_type, l_to) VALUES (p_gedcom_id, p_xref, l_tag, l_xref);
			IF l_tag='RFN' THEN
				BEGIN END;
			END IF;
		ELSEIF l_line LIKE '2 PLAC _%' THEN
			SET l_line:=SUBSTR(l_line FROM 8);
		ELSEIF l_line LIKE '2 DATE _%' THEN
			SET l_line:=SUBSTR(l_line FROM 8);
		ELSEIF l_line REGEXP '^1 RFN [A-Z0-9_-]{1,20}$' THEN
			SET l_xref:=SUBSTRING(l_line FROM 7);
			INSERT IGNORE INTO `##rlink` (r_file, r_link_id, r_gid) VALUES (p_gedcom_id, l_xref, p_xref);
		END IF;
	END WHILE;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_CONTACT_LINK                                                */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_contact_link` //
CREATE FUNCTION `##user_contact_link`(
	p_user_id INTEGER
) RETURNS VARCHAR(255)
	COMMENT 'includes/functions/functions_print.php:user_contact_link()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	DECLARE l_method TEXT DEFAULT `##get_user_setting`(p_user_id, 'contactmethod', 'mailto');

	CASE l_method
		WHEN 'none' THEN
			RETURN '';
		WHEN 'mailto' THEN
			RETURN CONCAT(
				'<a href="mailto:',
				`##htmlspecialchars`(`##getuseremail`(p_user_id)),
				'">',
				`##htmlspecialchars`(`##getuserfullname`(p_user_id)),
				'</a>'
			);
		ELSE
			RETURN CONCAT(
				'<a href="javascript:;" onclick="message(''',
				`##htmlspecialchars`(`##get_user_name`(p_user_id)),
				''', ''',
				l_method,
				'''); return false;">',
				`##htmlspecialchars`(`##getuserfullname`(p_user_id)),
				'</a>'
			);
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_GEDCOM_ADMIN                                                */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_gedcom_admin` //
CREATE FUNCTION `##user_gedcom_admin`(
	p_user_id   INTEGER,
	p_gedcom_id INTEGER
) RETURNS     BOOLEAN
	COMMENT 'includes/authentication.php:userGedcomAdmin()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN
		`##user_is_admin`(p_user_id) OR
		`##get_user_gedcom_setting`(p_user_id, p_gedcom_id, 'canedit', 'none')='admin';
END //
DELIMITER ;

/******************************************************************************/
/* FUNCTION: USER_IS_ADMIN                                                    */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##user_is_admin` //
CREATE FUNCTION `##user_is_admin`(
	p_user_id INTEGER
) RETURNS   BOOLEAN
	COMMENT 'includes/authentication.php:userIsAdmin()'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN `##get_user_setting`(p_user_id, 'canadmin', '0');
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: Y_TO_JD                                                         */
/* Calculate the julian day for the first day of a given year.                */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##y_to_jd` //
CREATE FUNCTION `##y_to_jd` (
	p_calendar VARCHAR(16),
	p_year     INTEGER
) RETURNS    INTEGER
	COMMENT 'Calculate the julian day for the first day of a given year'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE p_calendar
	WHEN '@#DFRENCH R@' THEN
		RETURN 2375471+p_year*365+FLOOR(p_year/4);
	WHEN '@#DGREGORIAN@' THEN
		BEGIN
			DECLARE l_y INTEGER DEFAULT p_year+4799;
			RETURN 306+365*l_y+FLOOR(l_y/4)-FLOOR(l_y/100)+FLOOR(l_y/400)-32044;
		END;
	WHEN '@#DHEBREW@' THEN
		RETURN NULL;
	WHEN '@#DHIJRI@' THEN
		RETURN 1948086+p_year*354+FLOOR((3+11*p_year)/30);
	WHEN '@#DJULIAN@' THEN
		IF p_year<0 THEN
			BEGIN
				DECLARE l_y INTEGER DEFAULT -p_year+4800;
				RETURN 306+365*l_y+FLOOR(l_y/4)-32082;
			END;
		ELSE
			BEGIN
				DECLARE l_y INTEGER DEFAULT p_year+4799;
				RETURN 306+365*l_y+FLOOR(l_y/4)-32082;
			END;
		END IF;
	WHEN '@#DSHAMSI@' THEN
		BEGIN
			DECLARE epbase INTEGER DEFAULT p_year-474;
			DECLARE epyear INTEGER DEFAULT 474+MOD(epbase, 2820);
			RETURN FLOOR((epyear*682-110)/2816)+(epyear-1)*365+FLOOR(epbase/2820)*1029983+1948321;
		END;
	END CASE;
END //
DELIMITER ;

/*******************************************************************************
* PROCEDURE ym_to_jd()
*
* Calculate the julian day number for the first day of a given month/year
*******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##ym_to_jd` //
CREATE FUNCTION `##ym_to_jd` (
	p_calendar VARCHAR(16),
	p_year     INTEGER,
	p_month    INTEGER
) RETURNS    INTEGER
	COMMENT 'Calculate the julian day for the first day of a given month/year'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	CASE p_calendar
	WHEN '@#DFRENCH R@' THEN
		RETURN 2375441+p_month*30+p_year*365+FLOOR(p_year/4);
	WHEN '@#DGREGORIAN@' THEN
		BEGIN
			DECLARE l_a INTEGER DEFAULT FLOOR((14-p_month)/12);
			DECLARE l_y INTEGER DEFAULT p_year+4800-l_a;
			DECLARE l_f INTEGER DEFAULT p_month+12*l_a-3;
			RETURN FLOOR((153*l_f+2)/5)+365*l_y+FLOOR(l_y/4)-FLOOR(l_y/100)+FLOOR(l_y/400)-32044;
		END;
	WHEN '@#DHEBREW@' THEN
		RETURN NULL;
	WHEN '@#DHIJRI@' THEN
		RETURN 1948086+29*(p_month-1)+FLOOR((6*p_month-1)/11)+p_year*354+FLOOR((3+11*p_year)/30);
	WHEN '@#DJULIAN@' THEN
		IF p_year<0 THEN
			BEGIN
				DECLARE l_a INTEGER DEFAULT FLOOR((14-p_month)/12);
				DECLARE l_y INTEGER DEFAULT -p_year+4801-l_a;
				DECLARE l_f INTEGER DEFAULT p_month+12*l_a-3;
				RETURN FLOOR((153*l_f+2)/5)+365*l_y+FLOOR(l_y/4)-32082;
			END;
		ELSE
			BEGIN
				DECLARE l_a INTEGER DEFAULT FLOOR((14-p_month)/12);
				DECLARE l_y INTEGER DEFAULT p_year+4800-l_a;
				DECLARE l_f INTEGER DEFAULT p_month+12*l_a-3;
				RETURN FLOOR((153*l_f+2)/5)+365*l_y+FLOOR(l_y/4)-32082;
			END;
		END IF;
	WHEN '@#DSHAMSI@' THEN
		BEGIN
			DECLARE epbase INTEGER DEFAULT p_year-474;
			DECLARE epyear INTEGER DEFAULT 474+MOD(epbase, 2820);
			DECLARE mdays  INTEGER;
			IF p_month<=7 THEN
				SET mdays:=(p_month-1)*31;
			ELSE
				SET mdays:=(p_month-1)*30+6;
			END IF;
			RETURN mdays+FLOOR((epyear*682-110)/2816)+(epyear-1)*365+FLOOR(epbase/2820)*1029983+1948321;
		END;
	END CASE;
END //
DELIMITER ;

/******************************************************************************/
/* PROCEDURE: YMD_TO_JD                                                       */
/* Calculate the julian day number for the first day of a given day/month/year*/
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##ymd_to_jd` //
CREATE FUNCTION `##ymd_to_jd` (
	p_calendar VARCHAR(16),
	p_year     INTEGER,
	p_month    INTEGER,
	p_day      INTEGER
) RETURNS    INTEGER
	COMMENT 'Calculate the julian day for a given day/month/year'
	DETERMINISTIC SQL SECURITY DEFINER NO SQL
BEGIN
	RETURN `##ym_to_jd`(p_calendar, p_year, p_month) + p_day - 1;
END //
DELIMITER ;

