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
	DETERMINISTIC
	SQL SECURITY DEFINER
	MODIFIES SQL DATA
BEGIN
	INSERT INTO `##log` (log_type, log_message, ip_address, user_id, gedcom_id)
	VALUES (p_type, p_message, IFNULL(@WT_IP_ADDRESS, 'cli'), @WT_USER_ID, @WT_GED_ID);
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
BEGIN
	DECLARE l_count INTEGER;

	SELECT SQL_CACHE COUNT(*) INTO l_count
	FROM `##user_setting`
	WHERE setting_name='canadmin' AND setting_value IN ('1', 'Y', 'Yes');

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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
/* FUNCTION: GET_NON_ADMIN_USER_COUNT                                         */
/******************************************************************************/

DELIMITER //
DROP FUNCTION IF EXISTS `##get_non_admin_user_count` //
CREATE FUNCTION `##get_non_admin_user_count`(
) RETURNS BOOLEAN
	COMMENT 'includes/functions/functions_db.php:get_non_admin_user_count()'
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
BEGIN
	DECLARE l_count INTEGER;

	SELECT SQL_CACHE COUNT(*) INTO l_count
	FROM `##user_setting`
	WHERE setting_name='canadmin' AND setting_value NOT IN ('1', 'Y', 'Yes');

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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	p_setting_name  VARCHAR(32 ),
	p_default_value VARCHAR(255)
) RETURNS         VARCHAR(255)
	COMMENT 'includes/functions/functions_db.php:get_user_setting()'
	DETERMINISTIC
	READS SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	NO SQL
BEGIN
	RETURN REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(p_text, '&', '&amp;'), '"', '&quot;'), '''', '&#39;'), '<', '&lt;'), '>', '&gt;');
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
	DETERMINISTIC
	MODIFIES SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	MODIFIES SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	MODIFIES SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	MODIFIES SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	MODIFIES SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	MODIFIES SQL DATA
	SQL SECURITY DEFINER
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
BEGIN
	RETURN `##get_user_setting`(p_user_id, 'auto_accept', 'N') IN ('1', 'Y', 'YES');
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
BEGIN
	/* A gedcom admin can accept, even if editing has been disabled */
	RETURN
		`##user_gedcom_admin`(p_user_id, p_gedcom_id) OR
		`##get_gedcom_setting`(p_gedcom_id, 'ALLOW_EDIT_GEDCOM', '0') IN ('1', 'Y', 'YES') AND
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
BEGIN
	RETURN
		`##get_gedcom_setting`(p_gedcom_id, 'ALLOW_EDIT_GEDCOM', '0') IN ('1', 'Y', 'YES') AND (
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	MODIFIES SQL DATA
BEGIN
	DECLARE l_line MEDIUMTEXT;
	DECLARE l_tag  VARCHAR(15);
	DECLARE l_xref VARCHAR(20);
	WHILE p_gedcom <> '' DO
		SET l_line=SUBSTRING_INDEX(p_gedcom, '\n', 1);
		SET p_gedcom=SUBSTRING(p_gedcom FROM LENGTH(l_line)+2);
		IF l_line REGEXP '^[1-9] [A-Z_][A-Z0-9]{0,14} @[A-Z0-9_-]{1,20}@$' THEN
			SET l_tag =SUBSTRING_INDEX(SUBSTRING_INDEX(l_line, ' ', 2), ' ', -1);
			SET l_xref=SUBSTRING_INDEX(SUBSTRING_INDEX(l_line, '@', 2), '@', -1);
			INSERT IGNORE INTO `##link` (l_file, l_from, l_type, l_to) VALUES (p_gedcom_id, p_xref, l_tag, l_xref);
			IF l_tag='RFN' THEN
				BEGIN END;
			END IF;
		ELSEIF l_line LIKE '2 PLAC _%' THEN
			SET l_line=SUBSTR(l_line FROM 8);
		ELSEIF l_line LIKE '2 DATE _%' THEN
			SET l_line=SUBSTR(l_line FROM 8);
		ELSEIF l_line REGEXP '^1 RFN [A-Z0-9_-]{1,20}$' THEN
			SET l_xref=SUBSTRING(l_line FROM 7);
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
	COMMENT 'PHP:user_contact_link()'
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
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
	DETERMINISTIC
	SQL SECURITY DEFINER
	READS SQL DATA
BEGIN
	RETURN `##get_user_setting`(p_user_id, 'canadmin', 'N') IN ('1', 'Y', 'YES');
END //
DELIMITER ;


