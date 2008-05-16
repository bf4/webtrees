<?php
/**
 *
 * Create the database schema and migrate data from PGV v4.x to PGV v5.x
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008 Greg Roach
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @version $Id$
 * @package PhpGedView
 * @subpackage DB
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

global $DBH, $TBLPREFIX, $DBTYPE;

// DB portability
switch ($DBTYPE) {
case 'mysql':
case 'mysqli':
	$AUTONUM_TYPE="INTEGER NOT NULL AUTO_INCREMENT";
	$COLLATION="COLLATE utf8_general_ci";
	$STORAGE="ENGINE=INNODB";
	break;
case 'postgres':
	$AUTONUM_TYPE="SERIAL NOT NULL";
	$COLLATION="";
	$STORAGE="";
	break;
case 'sqlite':
	$AUTONUM_TYPE="INTEGER NOT NULL AUTOINCREMENT";
	$COLLATION="COLLATE utf8_general_ci";
	$STORAGE="";
	break;
case 'mssql':
	$AUTONUM_TYPE="INTEGER NOT NULL IDENTITY";
	$COLLATION="COLLATE utf8_general_ci";
	$STORAGE="";
	break;
default:
	$AUTONUM_TYPE="INTEGER NOT NULL AUTO_INCREMENT";
	$COLLATION="COLLATE utf8_general_ci";
	$STORAGE="";
	break;
}

// Import data from PGV4.x style config files into an array of definitions
if (!function_exists('get_file_scalar_definitions')) {
	function get_file_scalar_definitions($filename) {
		$vars=array();
		if (file_exists($filename)) {
			foreach (file($filename) as $line) {
				if (preg_match('/^\s*\$(\w+)\s*=\s*(true|false)\s*;/', $line, $match)) {
					$vars[$match[1]]=$match[2]=='true' ? 'Y' :'N';
				} else {
					if (preg_match('/^\s*\$(\w+)\s*=\s*"(.*)"\s*;/', $line, $match)) {
						$vars[$match[1]]=$match[2];
					}
				}
			}
		}
		return $vars;
	}
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: GEDCOM
// (ged_id) is the DB key.
// (ged_name) is the user-friendly key.
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}gedcom (".
		" ged_id   {$AUTONUM_TYPE},".
		" ged_name VARCHAR(255) NOT NULL,".
		" CONSTRAINT {$TBLPREFIX}gedcom_pk PRIMARY KEY (ged_id),".
		" CONSTRAINT {$TBLPREFIX}gedcom_uk UNIQUE      (ged_name)".
		") {$STORAGE} {$COLLATION}"
	);

	// Migrate PGV4.x data from gedcoms.php
	global $INDEX_DIRECTORY;
	if (isset($INDEX_DIRECTORY) && file_exists($INDEX_DIRECTORY.'gedcoms.php')) {
		require $INDEX_DIRECTORY.'gedcoms.php';
		if (isset($GEDCOMS) && is_array($GEDCOMS)) {
			$statement=$DBH->prepare("INSERT INTO {$TBLPREFIX}gedcom (ged_id, ged_name) VALUES (?, ?)");
			foreach ($GEDCOMS as $GEDCOM) {
				$statement->bindValue(1, $GEDCOM['id'],     PDO::PARAM_INT);
				$statement->bindValue(2, $GEDCOM['gedcom'], PDO::PARAM_STR);
				$statement->execute();
			}
		}
	}
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: GEDCOM_SETTING
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}gedcom_setting (".
		" gset_ged_id    INTEGER      NOT NULL,".
		" gset_parameter VARCHAR(32)  NOT NULL,".
		" gset_value     VARCHAR(255) NULL,".
		" CONSTRAINT {$TBLPREFIX}gedcom_setting_pk  PRIMARY KEY (gset_ged_id, gset_parameter),".
		" CONSTRAINT {$TBLPREFIX}gedcom_setting_fk1 FOREIGN KEY (gset_ged_id) REFERENCES {$TBLPREFIX}gedcom (ged_id) ON DELETE CASCADE".
		") {$STORAGE} {$COLLATION}"
	);
	// These two indexes should ensure we never need to access the table
	$DBH->exec("CREATE INDEX {$TBLPREFIX}gedcom_setting_ix1 ON {$TBLPREFIX}gedcom_setting (gset_ged_id, gset_parameter, gset_value)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}gedcom_setting_ix2 ON {$TBLPREFIX}gedcom_setting (gset_parameter, gset_value, gset_ged_id)");

	// Migrate PGV4.x data from gedcoms.php and *_(conf|priv).php
	global $INDEX_DIRECTORY;
	if (isset($INDEX_DIRECTORY) && file_exists($INDEX_DIRECTORY.'gedcoms.php')) {
		require $INDEX_DIRECTORY.'gedcoms.php';
		if (isset($GEDCOMS) && is_array($GEDCOMS)) {
			$statement=$DBH->prepare(
				"INSERT INTO {$TBLPREFIX}gedcom_setting (gset_ged_id, gset_parameter, gset_value)".
				"	SELECT ged_id, ?, ? FROM {$TBLPREFIX}gedcom WHERE ged_id=?"
			);
			foreach ($GEDCOMS as $GEDCOM) {
				$statement->bindValue(3, $GEDCOM['id'], PDO::PARAM_STR);
				foreach ($GEDCOM as $key=>$value) {
					if (!in_array($key, array('id', 'gedcom', 'config', 'privacy'))) {
						// Convert booleans to Y/N strings
						if (is_bool($value)) {
							$value=$value ? 'Y' : 'N';
						}
						$statement->bindValue(1, $key,   PDO::PARAM_STR);
						$statement->bindValue(2, $value, PDO::PARAM_STR);
						$statement->execute();
					}
				}
	
				foreach (get_file_scalar_definitions($GEDCOM['config']) as $param=>$value) {
					$statement->bindValue(1, $param, PDO::PARAM_STR);
					$statement->bindValue(2, $value, PDO::PARAM_STR);
					$statement->execute();
				}
				foreach (get_file_scalar_definitions($GEDCOM['privacy']) as $param=>$value) {
					$statement->bindValue(1, $param, PDO::PARAM_STR);
					$statement->bindValue(2, $value, PDO::PARAM_STR);
					$statement->execute();
				}
	
				//unlink($GEDCOM['config']);
			}
		}
	}
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: USER
// (user_id) is the DB key.
// (user_name) is the user-friendly key.
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}user (".
		" user_id {$AUTONUM_TYPE},".
		" user_name VARCHAR(255) NOT NULL,".
		" user_pass VARCHAR(255) NOT NULL,".
		" CONSTRAINT {$TBLPREFIX}user_pk PRIMARY KEY (user_id),".
		" CONSTRAINT {$TBLPREFIX}user_u1 UNIQUE (user_name)".
		") {$STORAGE} {$COLLATION}"
	);

	// Migrate PGV4.x data from pgv_users
	$DBH->exec(
		"INSERT INTO {$TBLPREFIX}user (user_name, user_pass)".
		"	SELECT u_username, u_password FROM {$TBLPREFIX}users"
	);
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: PRIVACY
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}privacy (".
		" priv_id        {$AUTONUM_TYPE},".
		" priv_ged_id    INTEGER      NOT NULL,".
		" priv_user_id   INTEGER      NULL,".
		" priv_xref      VARCHAR(32)  NULL,".
		" priv_fact      VARCHAR(15)  NULL,".
		" priv_show      INTEGER      NOT NULL,".
		" priv_details   INTEGER      NOT NULL,".
		" CONSTRAINT {$TBLPREFIX}privacy_pk  PRIMARY KEY (priv_id),".
		" CONSTRAINT {$TBLPREFIX}privacy_fk1 FOREIGN KEY (priv_ged_id)  REFERENCES {$TBLPREFIX}gedcom (ged_id) ON DELETE CASCADE,".
		" CONSTRAINT {$TBLPREFIX}privacy_fk2 FOREIGN KEY (priv_user_id) REFERENCES {$TBLPREFIX}user  (user_id) ON DELETE CASCADE".
		") {$STORAGE} {$COLLATION}"
	);
	$DBH->exec("CREATE INDEX {$TBLPREFIX}privacy_ix1 ON {$TBLPREFIX}privacy (priv_xref, priv_ged_id)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}privacy_ix2 ON {$TBLPREFIX}privacy (priv_fact)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}privacy_ix3 ON {$TBLPREFIX}privacy (priv_type)");

	// Migrate PGV4.x data from *_priv.php
	global $INDEX_DIRECTORY;
	if (isset($INDEX_DIRECTORY) && file_exists($INDEX_DIRECTORY.'gedcoms.php')) {
		require $INDEX_DIRECTORY.'gedcoms.php';
		if (isset($GEDCOMS) && is_array($GEDCOMS)) {
			$statement1=$DBH->prepare(
				"INSERT INTO {$TBLPREFIX}privacy (priv_ged_id, priv_user_id, priv_xref, priv_fact, priv_show, priv_detail)".
				"	SELECT ged_id, NULL, ?, ?, ?, ? FROM {$TBLPREFIX}gedcom".
				"  WHERE ged_name=?");
			$statement2=$DBH->prepare(
				"INSERT INTO {$TBLPREFIX}privacy (priv_ged_id, priv_user_id, priv_xref, priv_fact, priv_show, priv_detail)".
				"	SELECT ged_id, user_id, ?, NULL, ?, NULL FROM {$TBLPREFIX}gedcom, {$TBLPREFIX}user".
				"  WHERE ged_name=? AND user_name=?");
			foreach ($GEDCOMS as $GEDCOM) {
				if (file_exists($GEDCOM['privacy'])) {
					require $GEDCOM['privacy'];
					$statement1->bindValue(5, $GEDCOM['gedcom'], PDO::PARAM_STR);
					$statement2->bindValue(3, $GEDCOM['gedcom'], PDO::PARAM_STR);
					foreach ($person_privacy as $key1=>$value1) {
						$statement1->bindValue(1, $key1,   PDO::PARAM_STR);
						$statement1->bindValue(2, null,    PDO::PARAM_NULL);
						$statement1->bindValue(3, $value1, PDO::PARAM_INT);
						$statement1->bindValue(4, null,    PDO::PARAM_NULL);
						$statement1->execute();
					}
					foreach ($user_privacy as $key1=>$value1) {
						foreach ($value1 as $key2=>$value2) {
							$statement2->bindValue(1, $key2,   PDO::PARAM_STR);
							$statement2->bindValue(2, $value2, PDO::PARAM_INT);
							$statement2->bindValue(4, $key1,   PDO::PARAM_STR);
							$statement2->execute();
						}
					}
					foreach ($global_facts as $key1=>$value1) {
						foreach ($value1 as $key2=>$value2) {
							$statement1->bindValue(1, null,  PDO::PARAM_NULL);
							$statement1->bindValue(2, $key1, PDO::PARAM_STR);
							if ($key2=='show') {
								$statement1->bindValue(3, $value2, PDO::PARAM_STR);
								$statement1->bindValue(4, null,    PDO::PARAM_NULL);
							} else {
								$statement1->bindValue(3, null,    PDO::PARAM_NULL);
								$statement1->bindValue(4, $value2, PDO::PARAM_INT);
							}
							$statement1->execute();
						}
					}
					foreach ($person_facts as $key1=>$value1) {
						foreach ($value1 as $key2=>$value2) {
							foreach ($value2 as $key3=>$value3) {
								$statement1->bindValue(1, $key1, PDO::PARAM_STR);
								$statement1->bindValue(2, $key2, PDO::PARAM_STR);
								if ($key3=='show') {
									$statement1->bindValue(3, $value3, PDO::PARAM_STR);
									$statement1->bindValue(4, null,    PDO::PARAM_NULL);
								} else {
									$statement1->bindValue(3, null,    PDO::PARAM_NULL);
									$statement1->bindValue(4, $value3, PDO::PARAM_STR);
								}
								$statement1->execute();
							}
						}
					}
		
					// unlink($GEDCOM['privacy']);
				}
			}
		}
	}
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: USER_SETTING
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}user_setting (".
		" uset_user_id   INTEGER      NOT NULL,".
		" uset_parameter VARCHAR(32)  NOT NULL,".
		" uset_value     VARCHAR(255) NULL,".
		" CONSTRAINT {$TBLPREFIX}user_setting_pk  PRIMARY KEY (uset_user_id, uset_parameter),".
		" CONSTRAINT {$TBLPREFIX}user_setting_fk1 FOREIGN KEY (uset_user_id) REFERENCES {$TBLPREFIX}user (user_id) ON DELETE CASCADE".
		") {$STORAGE} {$COLLATION}"
	);
	// These two indexes should ensure we never need to access the table
	$DBH->exec("CREATE INDEX {$TBLPREFIX}user_setting_ix1 ON {$TBLPREFIX}user_setting (uset_user_id, uset_parameter, uset_value)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}user_setting_ix2 ON {$TBLPREFIX}user_setting (uset_parameter, uset_value, uset_user_id)");

	// Migrate PGV4.x data from gedcoms.php into the new table
	$columns=array(
		'firstname', 'lastname', 'canadmin', 'email', 'verified', 'verified_by_admin',
		'language', 'pwrequested', 'reg_timestamp', 'reg_hashcode', 'theme', 'loggedin',
		'sessiontime', 'contactmethod', 'visibleonline', 'editaccount', 'defaulttab', 'comment',
		'comment_exp', 'sync_gedcom', 'relationship_privacy' ,'max_relation_path', 'auto_accept'
	);
	foreach ($columns as $column) {
		$n=$DBH->exec(
			"INSERT INTO {$TBLPREFIX}user_setting (uset_user_id, uset_parameter, uset_value)".
			" SELECT user_id, '{$column}', u_{$column} FROM {$TBLPREFIX}users, {$TBLPREFIX}user".
			"  WHERE u_username=user_name"
		);
	}

	$statement=$DBH->prepare(
		"INSERT INTO {$TBLPREFIX}user_setting (uset_user_id, uset_parameter, uset_value)".
		" SELECT user_id, ?, ? FROM {$TBLPREFIX}users, {$TBLPREFIX}user".
		"  WHERE u_username=user_name AND user_name=?"
	);
	$columns=array('gedcomid', 'rootid', 'canedit');
	foreach ($columns as $column) {
		$rows=$DBH->query("SELECT u_username, u_{$column} AS col FROM {$TBLPREFIX}users");
		foreach ($rows as $row) {
			foreach (unserialize($row->col) as $gedcom=>$value) {
				$statement->bindValue(1, $gedcom,          PDO::PARAM_STR);
				$statement->bindValue(2, $column,          PDO::PARAM_STR);
				$statement->bindValue(3, $row->u_username, PDO::PARAM_STR);
				$statement->execute();
			}
		}
	}

	//$DBH->exec("DROP TABLE {$TBLPREFIX}users");
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: USER_GEDCOM_SETTING
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}user_gedcom_setting (".
		" ugset_user_id   INTEGER      NOT NULL,".
		" ugset_ged_id    INTEGER      NOT NULL,".
		" ugset_parameter VARCHAR(32)  NOT NULL,".
		" ugset_value     VARCHAR(255) NULL,".
		" CONSTRAINT {$TBLPREFIX}user_gedcom_setting_pk  PRIMARY KEY (ugset_user_id, ugset_ged_id, ugset_parameter),".
		" CONSTRAINT {$TBLPREFIX}user_gedcom_setting_fk1 FOREIGN KEY (ugset_user_id) REFERENCES {$TBLPREFIX}user (user_id) ON DELETE CASCADE,".
		" CONSTRAINT {$TBLPREFIX}user_gedcom_setting_fk2 FOREIGN KEY (ugset_ged_id)  REFERENCES {$TBLPREFIX}gedcom (ged_id) ON DELETE CASCADE".
		") {$STORAGE} {$COLLATION}"
	);
	// These two indexes should ensure we never need to access the table
	$DBH->exec("CREATE INDEX {$TBLPREFIX}user_gedcom_setting_ix1 ON {$TBLPREFIX}user_gedcom_setting (ugset_user_id, ugset_ged_id, ugset_parameter, ugset_value)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}user_gedcom_setting_ix2 ON {$TBLPREFIX}user_gedcom_setting (ugset_parameter, ugset_value, ugset_user_id, ugset_ged_id)");

	// Migrate PGV4.x data from pgv_useres
	$statement=$DBH->prepare(
		"INSERT INTO {$TBLPREFIX}user_gedcom_setting (ugset_user_id, ugset_ged_id, ugset_parameter, ugset_value)".
		" SELECT ?, ged_id, ?, ? FROM {$TBLPREFIX}gedcom".
		"  WHERE ged_name=?"
	);

	foreach (array('gedcomid', 'rootid', 'canedit') as $column) {
		$rows=$DBH->query("SELECT user_id, u_{$column} AS arr FROM {$TBLPREFIX}users, {$TBLPREFIX}user WHERE u_username=user_name");
		foreach ($rows as $row) {
			$arr=unserialize($row->arr);
			foreach ($arr as $gedcom=>$value) {
				$statement->bindValue(1, $row->user_id, PDO::PARAM_INT);
				$statement->bindValue(2, $column,       PDO::PARAM_STR);
				$statement->bindValue(3, $value,        PDO::PARAM_STR);
				$statement->bindValue(4, $gedcom,       PDO::PARAM_INT);
				$statement->execute();
			}
		}
	}
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: EDIT
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}edit (".
		" edit_id      {$AUTONUM_TYPE},".
		" edit_user_id INTEGER   NOT NULL,".
		" edit_time    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,".
		" CONSTRAINT {$TBLPREFIX}edit_pk  PRIMARY KEY (edit_id),".
		" CONSTRAINT {$TBLPREFIX}edit_fk1 FOREIGN KEY (edit_user_id) REFERENCES {$TBLPREFIX}user (user_id) ON DELETE RESTRICT".
		") {$STORAGE} {$COLLATION}"
	);
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: RECORD
// (rec_id) is the DB key.
// (rec_ged_id, rec_xref) is the user-friendly key.
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}record (".
		" rec_id      {$AUTONUM_TYPE},".
		" rec_ged_id  INTEGER     NOT NULL,".
		" rec_xref    VARCHAR(32) NULL,". // I123, etc.
		" rec_type    VARCHAR(15) NULL,". // SOUR/INDI/FAM/etc.
		" CONSTRAINT {$TBLPREFIX}record_pk  PRIMARY KEY (rec_id),".
		" CONSTRAINT {$TBLPREFIX}record_fk1 FOREIGN KEY (rec_ged_id) REFERENCES {$TBLPREFIX}gedcom (ged_id) ON DELETE CASCADE".
		") {$STORAGE} {$COLLATION}"
	);
	$DBH->exec("CREATE UNIQUE INDEX {$TBLPREFIX}record_ix1 ON {$TBLPREFIX}record (rec_xref, rec_ged_id, rec_type)");
	$DBH->exec("CREATE UNIQUE INDEX {$TBLPREFIX}record_ix2 ON {$TBLPREFIX}record  (rec_type, rec_ged_id, rec_xref)");
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: FACT
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}fact (".
		" fact_id      {$AUTONUM_TYPE},".
		" fact_rec_id  INTEGER      NOT NULL,".
		" fact_type    VARCHAR(15)  NOT NULL,". // BIRT, MARR, etc.
		" fact_value   VARCHAR(255) NULL,".     // Any text after the fact_type, e.g. "Y"
		" fact_resn    ENUM ('locked', 'confidential', 'privacy') NULL,". // Any 2 RESN value
		" fact_plac    VARCHAR(255) NULL,". // Any 2 PLAC value
		" fact_date    VARCHAR(255) NULL,". // Any 2 DATE value
		" fact_jd1     INTEGER      NULL,". // The start of the date (range)
		" fact_jd2     INTEGER      NULL,". // The end of the date (range)
		" fact_day1    TINYINT      NULL,".
		" fact_day2    TINYINT      NULL,".
		" fact_mon1    TINYINT      NULL,".
		" fact_mon2    TINYINT      NULL,".
		" fact_year1   SMALLINT     NULL,".
		" fact_year2   SMALLINT     NULL,".
		" fact_cal1    ENUM ('@#DGREGORIAN@', '@#DJULIAN@', '@#DFRENCH R@', '@#DHEBREW@', '@#DROMAN', '@#DHIJRI@') NULL,".
		" fact_cal2    ENUM ('@#DGREGORIAN@', '@#DJULIAN@', '@#DFRENCH R@', '@#DHEBREW@', '@#DROMAN', '@#DHIJRI@') NULL,".
		" fact_gedcom  TEXT         NULL,". // The gedcom text
		" fact_created INTEGER      NULL,".
		" fact_deleted INTEGER      NULL,".
		" CONSTRAINT {$TBLPREFIX}fact_pk  PRIMARY KEY (fact_id),".
		" CONSTRAINT {$TBLPREFIX}fact_fk1 FOREIGN KEY (fact_rec_id)  REFERENCES {$TBLPREFIX}record (rec_id) ON DELETE CASCADE,".
		" CONSTRAINT {$TBLPREFIX}fact_fk2 FOREIGN KEY (fact_created) REFERENCES {$TBLPREFIX}edit (edit_id) ON DELETE RESTRICT,".
		" CONSTRAINT {$TBLPREFIX}fact_fk3 FOREIGN KEY (fact_deleted) REFERENCES {$TBLPREFIX}edit (edit_id) ON DELETE RESTRICT".
		") {$STORAGE} {$COLLATION}"
	);
	$DBH->exec("CREATE INDEX {$TBLPREFIX}fact_ix1 ON {$TBLPREFIX}fact (fact_type)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}fact_ix2 ON {$TBLPREFIX}fact (fact_deleted, fact_created)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}fact_ix3 ON {$TBLPREFIX}fact (fact_created, fact_deleted)");
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: LINK
// Note that "linker" and "linkee" are transititive dependencies on other
// fields, hence this table is not properly normalised.  We include them for
// performance reasons, as this table is used to determine relationship privacy.
// The linkee is NULL because the record may not exist (yet/ever), and so will
// need to be populated later.
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
	"CREATE TABLE {$TBLPREFIX}link (".
	" link_id      {$AUTONUM_TYPE},".
	" link_fact_id INTEGER     NOT NULL,".
	" link_type    VARCHAR(15) NOT NULL,". // SOUR/INDI/FAM/etc.
	" link_xref    VARCHAR(32) NOT NULL,". // I123, etc.
	" link_rec_id1 INTEGER     NOT NULL,". // linker
	" link_rec_id2 INTEGER     NULL,".     // linkee
	" CONSTRAINT {$TBLPREFIX}link_pk  PRIMARY KEY (link_id),".
	" CONSTRAINT {$TBLPREFIX}link_fk1 FOREIGN KEY (link_fact_id) REFERENCES {$TBLPREFIX}fact (fact_id) ON DELETE CASCADE".
	") {$STORAGE} {$COLLATION}"
	);
	$DBH->exec("CREATE INDEX {$TBLPREFIX}link_ix1 ON {$TBLPREFIX}link (link_fact_id, link_type, link_xref)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}link_ix2 ON {$TBLPREFIX}link (link_type, link_fact_id, link_xref)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}link_ix3 ON {$TBLPREFIX}link (link_rec_id2, link_type, link_rec_id1)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}link_ix4 ON {$TBLPREFIX}link (link_rec_id1, link_type, link_rec_id2)");
} catch (PDOException $e) {
}

////////////////////////////////////////////////////////////////////////////////
// TABLE: NAME
////////////////////////////////////////////////////////////////////////////////
try {
	$DBH->exec(
		"CREATE TABLE {$TBLPREFIX}name (".
		" name_id      {$AUTONUM_TYPE},".
		" name_fact_id INTEGER      NOT NULL,".
		" name_type    VARCHAR(15)  NOT NULL,".
		" name_full    VARCHAR(255) NOT NULL,".
		" name_npfx    VARCHAR(255) NOT NULL,".
		" name_givn    VARCHAR(255) NOT NULL,".
		" name_spfx    VARCHAR(255) NOT NULL,".
		" name_surn    VARCHAR(255) NOT NULL,".
		" name_nsfx    VARCHAR(255) NOT NULL,".
		" CONSTRAINT {$TBLPREFIX}name_pk  PRIMARY KEY (name_id),".
		" CONSTRAINT {$TBLPREFIX}name_fk1 FOREIGN KEY (name_fact_id) REFERENCES {$TBLPREFIX}fact (fact_id) ON DELETE CASCADE".
		") {$STORAGE} {$COLLATION}"
	);
	$DBH->exec("CREATE INDEX {$TBLPREFIX}name_ix1 ON {$TBLPREFIX}name (name_type)");
	$DBH->exec("CREATE INDEX {$TBLPREFIX}name_ix2 ON {$TBLPREFIX}name (name_surn, name_type, name_givn)");
} catch (PDOException $e) {
}

// Temporary: this logic should eventually move here.
CheckTableExists();

?>
