<?php
class Query{

	public $result;

	function connect($server,$username,$password,$db_name){
	
		try{
		
			$conn = mysql_connect($server,$username,$password);
		
		}catch(Exception $e){
		
			die("Could not establish server connection.");
		
		}
		
		if($conn){
		
			if(!mysql_select_db($db_name)){
			
				die("Could not establish connection with the database.");
			}
			else{
			
				return $conn;
			
			}
		
		}
	
	}
	
	function insertSpecific($tableName,$columnames,$values){
	
	$result = mysql_query("INSERT INTO ".$tableName."($column_names) VALUES ('' ".$values.")") or die(mysql_error());
	return $result;
	
	}
	
	function insert($tableName,$values){
	
	$result = mysql_query("INSERT INTO ".$tableName." VALUES ('' ".$values.")") or die(mysql_error());
	return $result;
	
	}
	
	function selectAll($sql){
		$result = array();
		$i = 0;
		$sqlQuery = mysql_query($sql);
		while($row = mysql_fetch_assoc($sqlQuery)){
			$result[$i] = $row;
			$i++ ;
		}
		mysql_free_result($sqlQuery);
		return $result;
	}

	
}

$qry = new Query;

?>