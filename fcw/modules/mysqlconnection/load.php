<?php

class mysqlconnection{
	
	private $args;
	
	private $con;

	function mysqlconnection($args){
	
		$this->__construct($args);
	
	}
	
	function __construct($args){
	
		$this->args = $args;
		
		//$this->connect_to_database();
	
	}
	
	function connect_to_database(){
	
		extract($this->args);
		
		$this->con = mysql_connect($server,$username,$password);
		
		if (!$this->con){
			die('Could not connect: ' . mysql_error());
		}
		
	}
	
	function select($select, $from, $where){
	
		extract($this->args);
		
		$this->connect_to_database();
	
		mysql_select_db($db_name, $this->con);
		
		$result = mysql_query('SELECT ' . $select . ' FROM ' . $from. ' WHERE ' . $where);
		
		$return = mysql_fetch_array($result);
		
		mysql_close($this->con);
		
		return $return;
		
	}
	
	function count($select, $from, $where){
	
		extract($this->args);
		
		$this->connect_to_database();
	
		mysql_select_db($db_name, $this->con);

		$result = mysql_query('SELECT ' . $select . ' FROM ' . $from. ' WHERE ' . $where);
		
		$return = mysql_num_rows($result);
		
		mysql_close($this->con);
				
		return $return;
		
	}
	
	function insert($table, $cols, $vals){
	
		extract($this->args);
		
		$this->connect_to_database();
	
		mysql_select_db($db_name, $this->con);

		$result = mysql_query('INSERT INTO `' . $table . '` (' . $cols . ') VALUES (\'' . $vals . '\')');
				
		mysql_close($this->con);
		
		return $result;		
		
	}
	
	function update($table, $set, $where){
	
		extract($this->args);
		
		$this->connect_to_database();
	
		mysql_select_db($db_name, $this->con);

		$result = mysql_query('UPDATE ' . $table . ' SET ' . $set. ' WHERE ' . $where);
				
		return $result;
		
	}
	
}