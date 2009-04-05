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
	private static $dbtype=null;

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
 	
	// Implement the singleton pattern
	public static function createInstance($DBTYPE, $DBHOST, $DBPORT, $DBNAME, $DBUSER, $DBPASS, $DBPERSIST, $DB_UTF8_COLLATION) {
		if (self::$pdo instanceof PDO) {
    	trigger_error('PGV_DB::createInstance() can only be called once.', E_USER_ERROR);
		}
		// mysqli is legacy, from PEAR::DB
		if ($DBTYPE=='mysqli') {
			$DBTYPE='mysql';
		}
		// Remember the DB type, so we can implement database abstraction
		self::$dbtype=$DBTYPE;
		// Create the underlying PDO object
		self::$pdo=new PDO(
			self::createDSN($DBTYPE, $DBHOST, $DBPORT, $DBNAME), $DBUSER, $DBPASS,
			array(
				PDO::ATTR_PERSISTENT=>(bool)$DBPERSIST,
				PDO::ATTR_AUTOCOMMIT=>true,
				PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ
			)
		);
		// Perform DB-specific initialisation
		self::initialiseConnection($DBTYPE, $DB_UTF8_COLLATION);
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

	// Create a PDO-style DSN from PGV's database connection parameters
	private static function createDSN($DBTYPE, $DBHOST, $DBPORT, $DBNAME) {
		// localhost can be problematic with non-default ports, so use IP address
		if ($DBHOST=='localhost' && $DBPORT) {
			$DBHOST='host=127.0.0.1;';
		} elseif ($DBHOST) {
			$DBHOST="host={$DBHOST};";
		}
		// Port number is optional
		if ($DBPORT) {
			$DBHOST.=';port='.(int)$DBPORT;
		}
		return "{$DBTYPE}:{$DBHOST}dbname={$DBNAME}";
	}

	// One-off initialisation of a new PDO connection
	private static function initialiseConnection($DBTYPE, $DB_UTF8_COLLATION) {
		switch ($DBTYPE) {
		case 'mysql':
			if ($DB_UTF8_COLLATION) {
				self::$pdo->exec("SET NAMES UTF8");
			}
			break;
		case 'pgsql':
			if ($DB_UTF8_COLLATION) {
				self::$pdo->exec("SET NAMES 'UTF8'");
			}
			break;
		case 'sqlite':
			if ($DB_UTF8_COLLATION) {
				self::$pdo->exec('PRAGMA encoding="UTF-8"');
			}
			break;
		case 'mssql':
		break;
		default:
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	// LOGGING
	// Keep a log of the statements executed using this connection
	//////////////////////////////////////////////////////////////////////////////
	private static $log=array();

	// Add an entry to the log
	public static function logQuery($query, $rows, $microtime) {
		self::$log[]='<tr><td>'.htmlspecialchars($query).'</td><td>'.(int)$rows.'</td><td>'.round($microtime*1000, 3).'</td></tr>';
	}

	// Total number of queries executed, for the page statistics
	public static function getQueryCount() {
		return count(self::$log);
	}

	// Display the query log as a table, for debugging
	public function getQueryLog() {
		return '<table border="1"><tr><th>Query</th><th>Rows</th><th>Time (ms)</th></tr>'.implode('', self::$log).'</table>';
	}

	//////////////////////////////////////////////////////////////////////////////
	// SQL Compatibility
	//////////////////////////////////////////////////////////////////////////////
	public static function mod_function($x, $y) {
		switch (self::$dbtype) {
		case 'sqlite':
			return "(($x)%($y))";
		case 'mysql':
		case 'pgsql':
		case 'mssql':
			return "MOD($x,$y)";
		}
	}

	public static function random_function() {
		switch (self::$dbtype) {
		case 'mysql':
			return 'RAND()';
		case 'sqlite':
		case 'pgsql':
			return 'RANDOM()';
		case 'mssql':
			return 'NEWID()';
		}
	}

	public static function limit_query($sql, $n) {
		$n=(int)$n;
		switch (self::$dbtype) {
		case 'mysql':
		case 'sqlite':
		case 'pgsql':
			return "{$sql} LIMIT {$n}";
		case 'mssql':
			return preg_replace('/^\s*SELECT /i', "SELECT TOP {$n} ", $sql);
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
	public static function query($string, $parameter_type= PDO::PARAM_STR) {
		$start=microtime(true);
		$result=self::$pdo->query($string, $parameter_type);
		$end=microtime(true);
		self::logQuery($string, count($result), $end-$start);
		return $result;
	}

	// Add logging to exec()
	public static function exec($statement) {
		$start=microtime(true);
		$result=self::$pdo->exec($statement);
		$end=microtime(true);
		self::logQuery($statement, $result, $end-$start);
		return $result;
	}

	// Add logging/functionality to prepare()
	public static function prepare($statement) {
		return new PGV_DBStatement(self::$pdo->prepare($statement));
	}
	
	// Map all other functions onto the base PDO object
	public function __call($function, $params) {
		return call_user_func_array(array(self::$pdo, $function), $params);
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
				PGV_DB::logQuery($this->pdostatement->queryString, $this->pdostatement->rowCount(), $end-$start);
				return $this;
			}
		case 'fetch':
		case 'fetchColumn':
		case 'fetchObject':
		case 'fetchAll':
			// Automatically execute the query
			if (!$this->executed) {
				$this->pdostatement->execute();
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
			$this->pdostatement->execute();
		}
		$row=$this->pdostatement->fetch($fetch_style);
		$this->pdostatement->closeCursor();
		$this->executed=false;
		return $row;
	}

	// Fetch one value and close the cursor.  e.g. SELECT MAX(foo) FROM bar
	public function fetchOne() {
		if (!$this->executed) {
			$this->pdostatement->execute();
		}
		$row=$this->pdostatement->fetch(PDO::FETCH_NUM);
		$this->pdostatement->closeCursor();
		$this->executed=false;
		if (is_array($row)) {
			return $row[0];
		} else {
			return $row;
		}
	}
}
