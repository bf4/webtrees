<?php
//
// Class file for the database access.  Extend PHP's native PDO and
// PDOStatement classes to provide database access with logging, etc.
//
// See documentation at http://wiki.phpgedview.net/en/index.php?title=PGV_Database_Functions
//
// phpGedView: Genealogy Viewer
// Copyright (C) 2009 Greg Roach (fisharebest)
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// @package PhpGedView
// @version $Id$

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_PGV_DB_PHP', '');

class PGV_DB {
	//////////////////////////////////////////////////////////////////////////////
	// CONSTRUCTION
	// Implement a singleton to decorate a PDO object.
	// See http://en.wikipedia.org/wiki/Singleton_pattern
	// See http://en.wikipedia.org/wiki/Decorator_pattern
	//////////////////////////////////////////////////////////////////////////////
	private static $instance=null;
	private static $pdo=null;

	// Dialects of SQL
	public static $AUTO_ID_TYPE =null; /* for primary keys */
	public static $ID_TYPE      =null; /* for foreign keys */
	public static $INT1_TYPE    =null;
	public static $INT2_TYPE    =null;
	public static $INT3_TYPE    =null;
	public static $INT4_TYPE    =null;
	public static $INT8_TYPE    =null;
	public static $CHAR_TYPE    =null;
	public static $VARCHAR_TYPE =null;
	public static $UNSIGNED     =null;
	public static $LIKE         =null;
	public static $RANDOM       =null;
	public static $TEXT_TYPE    =null;
	public static $LONGTEXT_TYPE=null;
	public static $UTF8_TABLE   =null;

	// Standard column types for gedcom data
	public static $COL_FILE=null;
	public static $COL_XREF=null;
	public static $COL_TAG =null;
	public static $COL_JD  =null;
	public static $COL_DAY =null;
	public static $COL_MON =null;
	public static $COL_YEAR=null;
	public static $COL_CAL =null;

	// Prevent instantiation via new PGV_DB
	private final function __construct() {
	}

	// Prevent instantiation via clone()
	public final function __clone() {
		trigger_error('PGV_DB::clone() is not allowed.', E_USER_ERROR);
	}
 	
	// Prevent instantiation via serialize()
	public final function __wakeup() {
		trigger_error('PGV_DB::unserialize() is not allowed.', E_USER_ERROR);
	}

	// Disconnect from the server, so we can connect to another one during install.php
	public static function disconnect() {
		self::$pdo=null;
	}

	// Implement the singleton pattern
	public static function createInstance($DBTYPE, $DBHOST, $DBPORT, $DBNAME, $DBUSER, $DBPASS, $DB_UTF8_COLLATION) {
		if (self::$pdo instanceof PDO) {
			trigger_error('PGV_DB::createInstance() can only be called once.', E_USER_ERROR);
		}
		// mysqli is legacy, from PEAR::DB
		if ($DBTYPE=='mysqli') {
			$DBTYPE='mysql';
		}
		// Check that the driver is loaded
		if (!extension_loaded('pdo') || !in_array($DBTYPE, PDO::getAvailableDrivers())) {
			trigger_error("PDO/{$DBTYPE} is not installed.", E_USER_ERROR);
		}
		// Create the underlying PDO object
		switch ($DBTYPE) {
		case 'mysql':
			self::$pdo=new PDO(
				"mysql:host={$DBHOST};dbname={$DBNAME};port={$DBPORT}", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER UNSIGNED AUTO_INCREMENT';
			self::$ID_TYPE      ='INTEGER UNSIGNED';
			self::$INT1_TYPE    ='TINYINT';
			self::$INT2_TYPE    ='SMALLINT';
			self::$INT3_TYPE    ='MEDIUMINT';
			self::$INT4_TYPE    ='INT';
			self::$INT8_TYPE    ='BIGINT';
			self::$CHAR_TYPE    ='CHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='UNSIGNED';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RAND()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='LONGTEXT';
			if ($DB_UTF8_COLLATION) {
				self::$pdo->exec("SET NAMES UTF8");
				self::$UTF8_TABLE   ='CHARACTER SET utf8 COLLATE utf8_unicode_ci';
			} else {
				self::$UTF8_TABLE   ='';
			}
			break;
		case 'pgsql':
			self::$pdo=new PDO(
				"pgsql:host={$DBHOST};dbname={$DBNAME};port={$DBPORT}", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='SERIAL';
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='SMALLINT';
			self::$INT2_TYPE    ='SMALLINT';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='BIGINT';
			self::$CHAR_TYPE    ='CHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='ILIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			if ($DB_UTF8_COLLATION) {
				self::$pdo->exec("SET NAMES 'UTF8'");
			}
			break;
		case 'mssql':
			self::$pdo=new PDO(
				"mssql:host={$DBHOST};dbname={$DBNAME}".($DBPORT ? ",{$DBPORT}" : ''), $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER IDENTITY';
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='NEWID';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case 'sqlite':
			try {
				self::$pdo=new PDO(
					"sqlite:{$DBNAME}", null, null,
					array(
						PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
						PDO::ATTR_CASE=>PDO::CASE_LOWER
					)
				);
				// Check if we can connect to the database
				// If not, we may have a sqlite2 database from PhpGedView 4.2.1 or earlier
				PGV_DB::exec("pragma table_info(sqlite_master)");

				PGV_DB::exec('PRAGMA encoding="UTF-8"');
			} catch (PDOException $ex) {
				// Couldn't connect using sqlite3 - try sqlite2
				self::$pdo=new PDO(
					"sqlite2:{$DBNAME}", null, null,
					array(
						PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
						PDO::ATTR_CASE=>PDO::CASE_LOWER
					)
				);
			}
			self::$AUTO_ID_TYPE ='INTEGER AUTOINCREMENT';
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case 'firebird': // This DSN has not been tested!
			self::$pdo=new PDO(
				"firebird:host={$DBHOST};dbname={$DBNAME};charset=UTF-8", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER'; // No autoincrement columns available
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='VARCHAR(32767)';
			self::$LONGTEXT_TYPE='BLOB SUB_TYPE TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case 'ibm': // This DSN has not been tested!
			self::$pdo=new PDO(
				"ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE={$DBNAME};HOSTNAME={$DBHOST};PORT={$DBPORT};PROTOCOL=TCPIP", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER AUTOINCREMENT'; // These values are guesses
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case 'informix': // This DSN has not been tested!
			self::$pdo=new PDO(
				"informix:host={$DBHOST};service={$DBPORT};database={$DBNAME}", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER AUTOINCREMENT'; // These values are guesses
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case 'oci': // This DSN has not been tested!
			self::$pdo=new PDO(
				"oci:dbname=//{$DBHOST}}:{$DBPORT}/{$DBNAME}", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER'; // No autoincrement columns available
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case 'odbc': // This DSN has not been tested!
			self::$pdo=new PDO(
				"odbc:$DBNAME", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER AUTOINCREMENT'; // These values are guesses
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		case '4D': // This DSN has not been tested!
			self::$pdo=new PDO(
				"4D:host={$DBHOST};port={$DBPORT};dbname={$DBNAME};charset=UTF-8", $DBUSER, $DBPASS,
				array(
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
					PDO::ATTR_CASE=>PDO::CASE_LOWER,
					PDO::ATTR_AUTOCOMMIT=>true
				)
			);
			self::$AUTO_ID_TYPE ='INTEGER AUTOINCREMENT'; // These values are guesses
			self::$ID_TYPE      ='INTEGER';
			self::$INT1_TYPE    ='INTEGER';
			self::$INT2_TYPE    ='INTEGER';
			self::$INT3_TYPE    ='INTEGER';
			self::$INT4_TYPE    ='INTEGER';
			self::$INT8_TYPE    ='INTEGER';
			self::$CHAR_TYPE    ='VARCHAR';
			self::$VARCHAR_TYPE ='VARCHAR';
			self::$UNSIGNED     ='';
			self::$LIKE         ='LIKE';
			self::$RANDOM       ='RANDOM()';
			self::$TEXT_TYPE    ='TEXT';
			self::$LONGTEXT_TYPE='TEXT';
			self::$UTF8_TABLE   ='';
			break;
		}

		// Assign the singleton
		self::$instance=new self;
	}

	// We don't access this directly, only via query(), exec() and prepare()
	public static function getInstance() {
		if (self::$pdo instanceof PDO) {
			return self::$instance;
		} else {
			trigger_error('PGV_DB::createInstance() must be called before PGV_DB::getInstance().', E_USER_ERROR);
		}
	}

	public static function isConnected() {
		return (self::$pdo instanceof PDO);
	}

	//////////////////////////////////////////////////////////////////////////////
	// LOGGING
	// Keep a log of the statements executed using this connection
	//////////////////////////////////////////////////////////////////////////////
	private static $log=array();

	// Add an entry to the log
	public static function logQuery($query, $rows, $microtime, $bind_variables) {
		if (PGV_DEBUG_SQL) {
			// Full logging
			// Trace
			$trace=debug_backtrace();
			array_shift($trace);
			array_shift($trace);
			foreach ($trace as $n=>$frame) {
				if (isset($frame['file']) && isset($frame['line'])) {
					$trace[$n]=basename($frame['file']).':'.$frame['line'].' '.$frame['function'].'('./*implode(',', $frame['args']).*/')';
				} else {
					unset($trace[$n]);
				}
			}
			$stack='<abbr title="'.htmlspecialchars(implode(" / ", $trace)).'">'.(count(self::$log)+1).'</abbr>';
			// Bind variables
			$query2='';
			foreach ($bind_variables as $key=>$value) {
				if (is_null($value)) {
					$bind_variables[$key]='[NULL]';
				}
			}
			foreach (str_split(htmlspecialchars($query)) as $char) {
				if ($char=='?') {
					$query2.='<abbr title="'.htmlspecialchars(array_shift($bind_variables)).'">'.$char.'</abbr>';
				} else {
					$query2.=$char;
				}
			}
			// Highlight embedded literal strings.
			if (preg_match('/[\'"]/', $query)) {
				$query2='<span style="background-color:yellow;">'.$query2.'</span>';
		}
			// Highlight slow queries
			$microtime*=1000; // convert to milliseconds
			if ($microtime>1000) {
				$microtime=sprintf('<span style="background-color:red">%.3f</span>', $microtime);
			} elseif ($microtime>100) {
				$microtime=sprintf('<span style="background-color:orange">%.3f</span>', $microtime);
			} elseif ($microtime>1) {
				$microtime=sprintf('<span style="background-color:yellow">%.3f</span>', $microtime);
			} else {
			$microtime=sprintf('%.3f', $microtime);
			}
			self::$log[]="<tr><td>{$stack}</td><td>{$query2}</td><td>{$rows}</td><td>{$microtime}</td></tr>";
		} else {
			// Just log query count for statistics
			self::$log[]=true;
		}
	}

	// Total number of queries executed, for the page statistics
	public static function getQueryCount() {
		return count(self::$log);
	}

	// Display the query log as a table, for debugging
	public static function getQueryLog() {
		$html='<table border="1"><col span="3"/><col align="char"/><thead><tr><th>#</th><th>Query</th><th>Rows</th><th>Time (ms)</th></tr><tbody/>'.implode('', self::$log).'</table>';
		self::$log=array();
		return $html;
	}

	//////////////////////////////////////////////////////////////////////////////
	// SQL Compatibility
	//////////////////////////////////////////////////////////////////////////////
	public static function mod_function($x, $y) {
		// TODO: When we get a d_leap_year column in the pgv_dates table, we will
		// no longer need this function.
		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'sqlite':
		case 'sqlite2':
			return "(($x)%($y))";
		case 'mysql':
		case 'pgsql':
		case 'mssql':
		case 'oci':
		case 'ibm':
		case 'firebird':
		case 'informix':
			return "MOD($x,$y)";
		case 'odbc':
			// We don't know the underlying database, so this is only a guess
			return "MOD($x,$y)";
		case '4D':
			// No MOD or TRUNC function - only ROUND
			return "(({$x})-({$y})*ROUNDING(({$x})/({$y})-0.5))";
		}
	}

	public static function random_function() {
		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'mysql':
		case 'firebird':
		case 'ibm':
			return 'RAND()';
		case 'sqlite':
		case 'sqlite2':
		case 'pgsql':
		case '4D':
			return 'RANDOM()';
		case 'mssql':
			return 'NEWID()';
		case 'oci':
			return 'DBMS_RANDOM.RANDOM';
		case 'odbc':
			// We don't know the underlying database, so there is little we can do.
			return '1';
		case 'informix':
			// Informix does not have a random number function??
			return '1';
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// INTERROGATE DATA DICTIONARY
	//////////////////////////////////////////////////////////////////////////////
	public static function all_tables() {
		global $DBNAME;

		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'mysql':
			// Mysql 4.x does not support the information schema
			return PGV_DB::prepare("SHOW TABLES")->fetchOneColumn();
		case 'pgsql':
		case 'mssql':
		default:
			// information_schema.tables is an ANSI standard.
			return
				PGV_DB::prepare("SELECT table_name FROM information_schema.tables WHERE table_schema=? ORDER BY table_name")
				->execute(array($DBNAME))
				->fetchOneColumn();
		case 'sqlite':
		case 'sqlite2':
			// SQLITE doesn't support the ANSI standard information_schema
			return
				PGV_DB::prepare("SELECT name FROM sqlite_master WHERE type=? ORDER BY name")
				->execute(array('table'))
				->fetchOneColumn();
		}
	}

	public static function all_columns($table) {
		global $DBNAME;

		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'mysql':
			// Mysql 4.x does not support the information schema
			return PGV_DB::prepare("DESC {$table}")->fetchOneColumn();
		case 'pgsql':
		case 'mssql':
		default:
			// information_schema.tables is an ANSI standard.
			return
				PGV_DB::prepare("SELECT column_name FROM information_schema.columns WHERE table_schema=? AND table_name=?")
				->execute(array($DBNAME, $table))
				->fetchOneColumn();
		case 'sqlite':
		case 'sqlite2':
			// SQLITE doesn't support the ANSI standard information_schema
			$rows=
				PGV_DB::prepare("pragma table_info('{$table}')")
				->execute(array())
				->fetchAll();
			$columns=array();
			foreach ($rows as $row) {
				$columns[]=$row->name;
			}
			return $columns;
		}
	}

	public static function table_exists($table) {
		global $DBNAME;

		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'pgsql':
		case 'mssql':
			// information_schema.tables is an ANSI standard.
			return (bool)
				PGV_DB::prepare("SELECT 1 FROM information_schema.tables WHERE table_schema=? AND table_name=?")
				->execute(array($DBNAME, $table))
				->fetchOne();
		case 'sqlite':
		case 'sqlite2':
			// SQLITE doesn't support the ANSI standard information_schema
			return (bool)
				PGV_DB::prepare("SELECT 1 FROM sqlite_master WHERE type=? AND name=?")
				->execute(array('table', $table))
				->fetchOne();
		case 'mysql':
			// Mysql 4.x does not support the information schema
		default:
			// Catch-all for other databases
			try {
				PGV_DB::prepare("SELECT 1 FROM {$table}")->fetchOne();
				return true;
			} catch (PDOException $ex) {
				return false;
			}
		}
	}

	public static function column_exists($table, $column) {
		global $DBNAME;

		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'pgsql':
		case 'mssql':
			// information_schema.columns is an ANSI standard.
			return (bool)
				PGV_DB::prepare("SELECT 1 FROM information_schema.columns WHERE table_schema=? AND table_name=? AND column_name=?")
				->execute(array($DBNAME, $table, $column))
				->fetchOne();
		case 'sqlite':
		case 'sqlite2':
			// SQLITE doesn't support the ANSI standard information_schema
			$rows=
				PGV_DB::prepare("pragma table_info({$table})")
				->fetchAll();
			foreach ($rows as $row) {
				if ($row->name==$column) {
					return true;
				}
			}
			return false;
		case 'mysql':
			// Mysql 4.x does not support the information schema
		default:
			// Catch-all for other databases
			try {
				PGV_DB::prepare("SELECT {$column} FROM {$table}")->fetchOne();
				return true;
			} catch (PDOException $ex) {
				return false;
			}
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// FUNCTIONALITY ENHANCEMENTS
	//////////////////////////////////////////////////////////////////////////////

	// Don't list sqlite2 as an available driver.  It is no good for PhpGedView
	public static function getAvailableDrivers() {
		$array=PDO::getAvailableDrivers();
		foreach ($array as $key=>$value) {
			if ($value=='sqlite2') {
				unset($array[$key]);
			}
		}
		return $array;
	}

	// The native quote() function does not convert PHP nulls to DB nulls
	public static function quote($string, $parameter_type=PDO::PARAM_STR) {
		if (is_null($string)) {
			return 'NULL';
		} else {
			return self::$pdo->quote($string, $parameter_type);
		}
	}

	// Add logging to query()
	public static function query($string, $parameter_type= PDO::PARAM_STR) {
		$start=microtime(true);
		$result=self::$pdo->query($string, $parameter_type);
		$end=microtime(true);
		self::logQuery($string, count($result), $end-$start, array());
		return $result;
	}

	// Add logging to exec()
	public static function exec($statement) {
		$start=microtime(true);
		$result=self::$pdo->exec($statement);
		$end=microtime(true);
		self::logQuery($statement, $result, $end-$start, array());
		return $result;
	}

	// Add logging/functionality to prepare()
	public static function prepare($statement) {
		if (!self::$pdo instanceof PDO) {
			throw new PDOException("No Connection Established");
		}
		return new PGV_DBStatement(self::$pdo->prepare($statement));
	}

	// Limit a query to the first $n rows
	public static function prepareLimit($statement, $n) {
		if (!self::$pdo instanceof PDO) {
			throw new PDOException("No Connection Established");
		}
		if ($n) {
			switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
			case 'mysql':
			case 'sqlite':
			case 'sqlite2':
			case 'pgsql':
			case '4D':
				$statement="{$statement} LIMIT {$n}";
				break;
			case 'mssql':
				$statement=preg_replace('/^\s*SELECT /i', "SELECT TOP {$n} ", $statement);
				break;
			case 'firebird':
			case 'informix':
				$statement=preg_replace('/^\s*SELECT /i', "SELECT FIRST {$n} ", $statement);
				break;
			case 'ibm':
				$statement="{$statement} FETCH FIRST {$n} ROWS ONLY";
				break;
			case 'oci':
				$statement="SELECT * FROM ($statement) WHERE ROWNUM<={$n}";
				break;
			case 'odbc':
				// We don't know the underlying database, so just return all rows :-(
				break;
			}
		}
		return new PGV_DBStatement(self::$pdo->prepare($statement));
	}
	
	// Map all other functions onto the base PDO object
	public function __call($function, $params) {
		return call_user_func_array(array(self::$pdo, $function), $params);
	}

	//////////////////////////////////////////////////////////////////////////////
	// Create/update tables, indexes, etc.
	//////////////////////////////////////////////////////////////////////////////
	public static function updateSchema($schema_dir, $schema_name, $target_version) {
		global $TBLPREFIX;

		// Allow the schema scripts to do different things for different databases		
		$DRIVER_NAME=self::getInstance()->getAttribute(PDO::ATTR_DRIVER_NAME);

		// Define some "standard" columns, so we create our tables consistently
		self::$COL_FILE=self::$INT2_TYPE.' '.self::$UNSIGNED; // Allow 32768/65536 Gedcoms
		self::$COL_XREF=self::$VARCHAR_TYPE.'(20)';           // Gedcom identifiers are max 20 chars
		self::$COL_TAG =self::$VARCHAR_TYPE.'(15)';           // Gedcom tags/record types are max 15 chars
		self::$COL_JD  =self::$INT3_TYPE.' '.self::$UNSIGNED; // Julian Day numbers only need 3 bytes
		self::$COL_DAY =self::$INT1_TYPE.' '.self::$UNSIGNED; // Day numbers only need 1 byte
		self::$COL_MON =self::$INT1_TYPE.' '.self::$UNSIGNED; // Month Day numbers only need 1 byte
		self::$COL_YEAR=self::$INT2_TYPE;                     // Year Day numbers only need 2 bytes

		if ($DRIVER_NAME=='mysql') {
			self::$COL_CAL="ENUM ('@#DGREGORIAN@', '@#DJULIAN@', '@#DHEBREW@', '@#DFRENCH R@', '@#DHIJRI@', '@#DROMAN@')"; // Fixed list of calendar names
		} else {
			self::$COL_CAL=self::$VARCHAR_TYPE.'(13)'; // Calendar names have max 13 characters
		}

		try {
			$current_version=(int)get_site_setting($schema_name);
		} catch (PDOException $e) {
			// During initial installation, this table won't exist.
			// It will only be a problem if we can't subsequently create it.
			$current_version=0;
		}
		while ($current_version<$target_version) {
			$next_version=$current_version+1;
			require $schema_dir.'db_schema_'.$current_version.'_'.$next_version.'.php';
			// The updatescript should update the version or throw an exception
			$current_version=(int)get_site_setting($schema_name);
			if ($current_version!=$next_version) {
				die("Internal error while updating {$schema_name} to {$next_version}");
			}
		}
	}
}

class PGV_DBStatement {
	//////////////////////////////////////////////////////////////////////////////
	// CONSTRUCTION
	// Decorate a PDOStatement object.
	// See http://en.wikipedia.org/wiki/Decorator_pattern
	//////////////////////////////////////////////////////////////////////////////
	private $pdostatement=null;

	// Keep track of calls to execute(), so we can do it automatically
	private $executed=false;

	// Keep a copy of the bind variables, for logging
	private $bind_variables=array();

	// Our constructor just takes a copy of the object to be decorated
	public function __construct(PDOStatement $statement) {
		$this->pdostatement=$statement;
	}

	//////////////////////////////////////////////////////////////////////////////
	// FLUENT INTERFACE
	// Add automatic calling of execute() and closeCursor()
	// See http://en.wikipedia.org/wiki/Fluent_interface
	//////////////////////////////////////////////////////////////////////////////
	public function __call($function, $params) {
		switch ($function) {
		case 'closeCursor':
			$this->executed=false;
			// no break;
		case 'bindColumn':
		case 'bindParam':
		case 'bindValue':
			// TODO: bind variables need to be stored in $this->bind_variables so we can log them
		case 'setAttribute':
		case 'setFetchMode':
			// Functions that return no values become fluent
			call_user_func_array(array($this->pdostatement, $function), $params);
			return $this;
		case 'execute':
			if ($this->executed) {
				trigger_error('PGV_DBStatement::execute() called twice.', E_USER_ERROR);
			} else {
				$start=microtime(true);
				$result=call_user_func_array(array($this->pdostatement, $function), $params);
				$end=microtime(true);
				$this->executed=!preg_match('/^(insert into|delete from|update|create|alter) /i', $this->pdostatement->queryString);
				if ($params) {
					$this->bind_variables=$params[0];
				}
				PGV_DB::logQuery($this->pdostatement->queryString, $this->pdostatement->rowCount(), $end-$start, $this->bind_variables);
				return $this;
			}
		case 'fetch':
		case 'fetchColumn':
		case 'fetchObject':
		case 'fetchAll':
			// Automatically execute the query
			if (!$this->executed) {
				$this->execute();
				$this->executed=true;
			}
			// no break;
		default:
			return call_user_func_array(array($this->pdostatement, $function), $params);
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// FUNCTIONALITY ENHANCEMENTS
	//////////////////////////////////////////////////////////////////////////////

	// Fetch one row, and close the cursor.  e.g. SELECT * FROM foo WHERE pk=bar
	public function fetchOneRow($fetch_style=PDO::FETCH_OBJ) {
		if (!$this->executed) {
			$this->execute();
		}
		$row=$this->pdostatement->fetch($fetch_style);
		$this->pdostatement->closeCursor();
		$this->executed=false;
		return $row ? $row : null;
	}

	// Fetch one value and close the cursor.  e.g. SELECT MAX(foo) FROM bar
	public function fetchOne($default=null) {
		if (!$this->executed) {
			$this->execute();
		}
		$row=$this->pdostatement->fetch(PDO::FETCH_NUM);
		$this->pdostatement->closeCursor();
		$this->executed=false;
		return is_array($row) ? $row[0] : $default;
	}

	// Fetch two columns, and return an associative array of col1=>col2
	public function fetchAssoc() {
		if (!$this->executed) {
			$this->execute();
		}
		$rows=array();
		while ($row=$this->pdostatement->fetch(PDO::FETCH_NUM)) {
			$rows[$row[0]]=$row[1];
		}
		$this->pdostatement->closeCursor();
		$this->executed=false;
		return $rows;
	}

	// Fetch all the first column, as an array
	public function fetchOneColumn() {
		if (!$this->executed) {
			$this->execute();
		}
		$list=array();
		while ($row=$this->pdostatement->fetch(PDO::FETCH_NUM)) {
			$list[]=$row[0];
		}
		$this->pdostatement->closeCursor();
		$this->executed=false;
		return $list;
	}
}
