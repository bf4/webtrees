<?php
//
// Class file for the database access.  Extend PHP's native PDO and
// PDOStatement classes to provide database access with logging, etc.
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (c) 2009-2010 Greg Roach
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
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class WT_DB {
	//////////////////////////////////////////////////////////////////////////////
	// CONSTRUCTION
	// Implement a singleton to decorate a PDO object.
	// See http://en.wikipedia.org/wiki/Singleton_pattern
	// See http://en.wikipedia.org/wiki/Decorator_pattern
	//////////////////////////////////////////////////////////////////////////////
	private static $instance=null;
	private static $pdo=null;

	// Prevent instantiation via new WT_DB
	private final function __construct() {
	}

	// Prevent instantiation via clone()
	public final function __clone() {
		trigger_error('WT_DB::clone() is not allowed.', E_USER_ERROR);
	}

	// Prevent instantiation via serialize()
	public final function __wakeup() {
		trigger_error('WT_DB::unserialize() is not allowed.', E_USER_ERROR);
	}

	// Disconnect from the server, so we can connect to another one
	public static function disconnect() {
		self::$pdo=null;
	}

	// Implement the singleton pattern
	public static function createInstance($DBHOST, $DBPORT, $DBNAME, $DBUSER, $DBPASS) {
		if (self::$pdo instanceof PDO) {
			trigger_error('WT_DB::createInstance() can only be called once.', E_USER_ERROR);
		}
		// Create the underlying PDO object
		self::$pdo=new PDO(
			(substr($DBHOST, 0, 1)=='/' ?
				"mysql:unix_socket={$DBHOST}" :
				"mysql:host={$DBHOST};dbname={$DBNAME};port={$DBPORT}"
			),
			$DBUSER, $DBPASS,
			array(
				PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
				PDO::ATTR_CASE=>PDO::CASE_LOWER,
				PDO::ATTR_AUTOCOMMIT=>true
			)
		);
		self::$pdo->exec("SET NAMES UTF8");

		// Assign the singleton
		self::$instance=new self;
	}

	// We don't access this directly, only via query(), exec() and prepare()
	public static function getInstance() {
		if (self::$pdo instanceof PDO) {
			return self::$instance;
		} else {
			trigger_error('WT_DB::createInstance() must be called before WT_DB::getInstance().', E_USER_ERROR);
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
		if (WT_DEBUG_SQL) {
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
	// INTERROGATE DATA DICTIONARY
	//////////////////////////////////////////////////////////////////////////////
	public static function table_exists($table) {
		global $DBNAME;

		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'mysql':
			// Mysql 4.x does not support the information schema
		default:
			// Catch-all for other databases
			try {
				WT_DB::prepare("SELECT 1 FROM {$table}")->fetchOne();
				return true;
			} catch (PDOException $ex) {
				return false;
			}
		}
	}

	public static function column_exists($table, $column) {
		global $DBNAME;

		switch (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
		case 'mysql':
			// Mysql 4.x does not support the information schema
		default:
			// Catch-all for other databases
			try {
				WT_DB::prepare("SELECT {$column} FROM {$table}")->fetchOne();
				return true;
			} catch (PDOException $ex) {
				return false;
			}
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// FUNCTIONALITY ENHANCEMENTS
	//////////////////////////////////////////////////////////////////////////////

	// The native quote() function does not convert PHP nulls to DB nulls
	public static function quote($string, $parameter_type=PDO::PARAM_STR) {
		if (is_null($string)) {
			return 'NULL';
		} else {
			return self::$pdo->quote($string, $parameter_type);
		}
	}

	// Add logging to query()
	public static function query($statement, $parameter_type= PDO::PARAM_STR) {
		$statement=str_replace('##', WT_TBLPREFIX, $statement);
		$start=microtime(true);
		$result=self::$pdo->query($statement, $parameter_type);
		$end=microtime(true);
		self::logQuery($statement, count($result), $end-$start, array());
		return $result;
	}

	// Add logging to exec()
	public static function exec($statement) {
		$statement=str_replace('##', WT_TBLPREFIX, $statement);
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
		$statement=str_replace('##', WT_TBLPREFIX, $statement);
		return new WT_DBStatement(self::$pdo->prepare($statement));
	}

	// Map all other functions onto the base PDO object
	public function __call($function, $params) {
		return call_user_func_array(array(self::$pdo, $function), $params);
	}

	//////////////////////////////////////////////////////////////////////////////
	// Create/update tables, indexes, etc.
	//////////////////////////////////////////////////////////////////////////////
	public static function updateSchema($schema_dir, $schema_name, $target_version) {
		try {
			$current_version=(int)get_site_setting($schema_name);
		} catch (PDOException $e) {
			// During initial installation, this table won't exist.
			// It will only be a problem if we can't subsequently create it.
			$current_version=0;
		}
		if ($current_version<$target_version) {
			while ($current_version<$target_version) {
				$next_version=$current_version+1;
				require $schema_dir.'db_schema_'.$current_version.'_'.$next_version.'.php';
				// The updatescript should update the version or throw an exception
				$current_version=(int)get_site_setting($schema_name);
				if ($current_version!=$next_version) {
					die("Internal error while updating {$schema_name} to {$next_version}");
				}
			}
			// After an update, there may well be old files to delete.
			if (file_exists($schema_dir.'delete_old_files.php')) {
				require $schema_dir.'delete_old_files.php';
			}
		}
	}
}
