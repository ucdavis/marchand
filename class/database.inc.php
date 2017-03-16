<?php

class database {
	var private $initialized = false;
	var private $handle = null; // database connection handle
	var private $last_result = null;
	
	static public function query($sql) {
		if( ! database::$initialized) database::init();
		
		$result = mysql_query($sql);
		if($result == false) {
			echo "An error occurred while executing the statement: ".mysql_error()."<br />";
			return(false);
		}
		
		database::$last_result = $result;
		
		if($result === true) return true; // insert, update, delete, drop does this
		
		$results = array();
		while($row = mysql_fetch_assoc($result)) {
			$results[] = $row;
		}
		
		return $results;
	}
	
	static private function init() {
		database::$handle = mysql_connect("localhost", "hc", "admin");
		
		if(database::$handle == false) {
			echo "Could not connect to database: ".mysql_error()."<br />";
			return;
		}
		
		$err = mysql_select_db("calaggie", database::$handle);
		if($err == false) {
			echo "Connected but could not select database: ".mysql_error()."<br />";
			return;
		}
		
		database::$initialized = true;
	}
	
	static public function escape($str) {
		if( ! database::$initialized) database::init();
		
		if( get_magic_quotes_gpc() )
			return $str;
		else
			return mysql_real_escape_string($str);
	}
	
	static public function num_rows() {
		if( ! database::$initialized) database::init();
	
		return mysql_num_rows(database::$last_result);
	}
	
	static public function insert_id() {
		return mysql_insert_id(database::$handle);
	}
};
