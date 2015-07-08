<?php

	//Merge two tables(with data from csv files) using join create a third csv output file and display longitude and latitude into map if any
	
	include('query.php');//Database query class
	include('CSVOperation.php');//CSVOperation class
	
	define("db_name","db_csvmerge");//Database name
	define("host_name","localhost");//Host name
	define("user_name","root");//Database Username
	define("password","");//Database Password
	
	define("file1","contract.csv");//Filename 1
	define("file2","rewards.csv");//Filename 2
	define("file3","result.csv");//Filename 3
	
	define("common_field","contract_name");//Common field in file 1 and file 2
	
	$conn = $qry->connect(host_name,user_name,password,db_name);
	
	$pos = strpos(file1,".");//FInd position of the dot in file name
	$filename1 = str_split(file1,$pos);//Get extension and real file name by splitting filename
	
	$table_name1 = $filename1[0];//Get table name as real file name
	
	$pos = strpos(file2,".");
	$filename2 = str_split(file2,$pos);
	
	$table_name2 = $filename2[0];
	
	$pos = strpos(file3,".");
	$filename3 = str_split(file3,$pos);
	
	$table_name3 = $filename3[0];
	
	//Insert logic starts here
	//Insert only when GET is set
	if(isset($_GET['insert'])){
	if($_GET['insert']=="true"):
			//Check if filename is a valid csv file
			if($filename1[1]==".csv"){
			
			try{
			
			$file1 = fopen(file1,"r");
			$file1Arr = $csvoperation->convert_to_array($file1);//convert a csv file into a multidimensional array
			fclose($file1);
			
			}catch(Exception $e){die("IO Exception Occured");}
			
			}
			else{
			
				die("Invalid file extension");
			
			}
			
			//Check if filename is a valid csv file
			if($filename2[1]==".csv"){
			
			try{
			
			$file2 = fopen(file2,"r");
			$file2Arr = $csvoperation->convert_to_array($file2);//convert a csv file into a multidimensional array
			fclose($file2);
			
			}catch(Exception $e){die("IO Exception Occured");}
			
			
			}
			else{
			
				die("Invalid file extension");
			
			}
			
			//Insert values from the csv file into the database tables
			foreach($file1Arr as $row){
			
				$row1 = "'".implode("','",$row)."'";
				$qry->insert($table_name1,$row1);
			}
			
			//Insert values from the csv file into the database tables
			foreach($file2Arr as $row){
			
				$row2 = "'".implode("','",$row)."'";
				$qry->insert($table_name2,$row2);
			} 
	endif;
	}//Insert logic ends here

	
	$join = "Select * from ".$table_name1." as tb1 join ".$table_name2." as tb2 on tb1.".common_field."=tb2.".common_field;
	//echo $join;die();
	try{
	$res = $qry->selectAll($join);
	}catch(Exception $e){

	
	}
	if(!$res){
		
		echo "<center><a href='index.php?insert=true'>Click to insert data from csv files</a><center>";
		die();
	}

	//var_dump($res);
	
	if($filename3[1]==".csv"){
		try{
		$file3 = fopen(file3,"w+");
		}catch(Exception $e){die("IO exception occured");}
		
		
	//Loop through every row
	foreach($res as $val){
	
		$arr = array();
		foreach($val as $row){
		
			array_push($arr,$row);//create an array from a row from the table
		
		}
		$rowString = implode(",",$arr)."\n";//get comma separated string for each row from join
		fwrite($file3,$rowString);
	
	}
		

	
	echo "<h2 style='text-align:center;' class='suc'>File write successful</h2>";
	
		
	}
	else{
	
		die("Invalid file extension");
	
	}

	
?>
<html>
<head>
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
	<script src="js/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script>setInterval(function(){$('.suc').hide('slow')},3000);</script>
	

	
	
	<script>
	
		$(document).ready(function(){
		
			$(".cname").hover(function(){
			
				$("#longitude").text($(this).data('longitude'));
				$("#latitude").text($(this).data('latitude'));
			
			
			var map;
		  map = new google.maps.Map(document.getElementById('map'), {
			zoom: 8,
			center: {lat: $(this).data('longitude'), lng: $(this).data('latitude')}
		  });
		
			 
		});
			
			});

	
	
	</script>
	
</head>


	<div class="container">
	<div  class="companyListing">
	<legend>Companies (Hover the company name for longitude and latitude)</legend>
		<ul>
		<?php

			foreach($res as $val){
			
		?>
			<li class="cname" data-longitude="<?php echo $val['longitude']?>" data-latitude="<?php echo $val['latitude']?>"><?php echo $val['company_name'];?></li>
			
		<?php
			
			}


		?>
		</ul>
	</div>
		<div class="graph">
			Longitude:<span id="longitude"></span><br>
			Latitude:<span id="latitude"></span>
		</div>
		
		<div id="map"></div>
		
	</div>
		
</html>



