<?php

/*
  ===========================

  boastMachine v3.1
  Released : Sunday, June 5th 2005 ( 06/05/2005 )
  http://boastology.com

  Developed by Kailash Nadh
  Email   : mail@kailashnadh.name
  Website : http://kailashnadh.name, http://bnsoft.net
  Blog    : http://boastology.com/blog

  boastMachine is a free software licensed under GPL (General public license)

  ===========================
*/

// The boastMachine database class :)

$proc_name="MySQL";
		// The name of the database this class is currently processing

$debug_mode=true;
		// Run in Debug mode? Running in debug mode will produce error messages
		// when one occurs, else, no messages will be produced but a -1 will be returned by the function

class bDb {

	var $my_prefix; // The mysql table prefix
	var $link; // this object the MysqL connection handle
	var $last_query=null; // Holds the copy of the last query
	var $last_result=null; // Holds the last result of query
	var $num_rows=0;
	var $result=null;
	var $output=null;

	// The constructor. Get the mysql info vars and connect to the server
	function bDb() {
		global $my_host,$my_user,$my_pass,$my_db,$my_prefix;

		$this->link = mysql_connect($my_host,$my_user,$my_pass); // Connect to the MySQL server

			// Cant connect to the server!
			if(!$this->link) {
				die(mysql_error());
			}

			// Cant select the database!
			if(!@mysql_select_db($my_db,$this->link)) {
				die(mysql_error());
			}
	}


	// Perform the MySQL queries
	function query($query_str,$multi=true) {

		$row=null;
		$num=0;
		$this->last_query=null;
		$this->result=null;
		$this->output=null;
		$this->output=array();

		if(!$query_str) { return 0; } // If there is no query string, return 0

		$this->last_query = $query_str;
		$this->result = mysql_query($query_str, $this->link) or die(mysql_error()); // Do the query

		if ( preg_match("/^\\s*(insert|delete|update|replace) /i",$query_str) ) {
			$this->rows_done = mysql_affected_rows();
			// Return the number of rows affected by this operation
			return $this->rows_done;

		} else {

			// Return multiple rows stored in a multi dimensional array
			if($multi) {
				// If the query was to get data, i.e, select, then return the data as arrays

				while ( $row = mysql_fetch_array($this->result, MYSQL_ASSOC) ) {
					$this->output[$num] = $row;
					$num++;
				}
			}


			else {
				// Return the result in a one dimensional array
				return mysql_fetch_array($this->result, MYSQL_ASSOC);
			}

			return $this->output; // Return the multi demensional array
		}

	} // End function


	// Get the number of rows for a particular query
	function row_count($row) {
		if(!$row) return 0;
		return mysql_num_rows(mysql_query($row,$this->link));
	}

	// Show error messages - DEBUG MODE
	function show_error($error_msg) {
		global $debug_mode;

		if($debug_mode) {
		echo $error_msg;
		} else {
		return;
		}
	}

} // End class

?>