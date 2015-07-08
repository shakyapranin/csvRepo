<?php

	class CSVOperation{
	
		public $result;
		
		function convert_to_array($file){
		
			$i = 0;
			$fileArr = array();
			while(!feof($file)){
				
				$oneRow = fgetcsv($file);
				foreach($oneRow as $key=>$val){
				
					$fileArr[$i][$key] = $val;
				
				}

				$i++;
				
			}
			
			return $fileArr;
		
		}
	
	}
	
	$csvoperation = new CSVOperation;


?>