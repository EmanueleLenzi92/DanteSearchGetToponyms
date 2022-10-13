<?php
$apiDante="http://vdl.isti.cnr.it/dsapi/";
$file = fopen('toponimiNuovi.csv', 'r');
//$cont=1;

$errori= array();

$csv= array();


while (($line = fgetcsv($file)) !== FALSE) {
  //$line is an array of the csv elements
  //print_r($line);
	
   for($i=0; $i < sizeOf($line); $i=$i+3){
		
		
/* 		print($cont . " - " . $line[0] . ", " . $line[1] . ", " . $line[2] . "</br>");
		$cont++; */
		
		
		$row= array();
		
		$json = file_get_contents($apiDante . $line[0]);
		$obj = json_decode($json);
		//var_dump( $obj->risultati);
		
		if($obj->occorrenze_totali == 0){
		
			array_push($errori, $line[0]);
		
		}
				
		
		// risultati
		for ($i=0; $i < sizeOf($obj->risultati); $i++){
			
			$row["toponimo"] = $line[0];
			$row["lemma"] = $obj->lemma;
			$row["occorrenze_totali"] = $obj->occorrenze_totali;	

			// occorrenze in ciascun'opera
			foreach ($obj->occorrenze_opera as $key => $value) {
			   echo $key . " - ";
			   echo $value . "</br>";
			   
			   $row[$key] = $value;
			}			
			
			
			foreach ($obj->risultati[$i] as $key => $value) {
			   echo $key . " - ";
			   echo $value . "</br>";
			   
			   $row[$key]= $value;
			}
			
			array_push($csv, $row);
		}
		
		
			
	
	
	} 
  

}
fclose($file);





		// write csv
 		$fp = fopen('file.csv', 'wb');
		

		$savedKeys = [] ;
		foreach ($csv as $getKey) {
			$savedKeys = array_merge($savedKeys,array_keys($getKey)) ;
		}
		$savedKeys =array_unique($savedKeys) ;
		sort($savedKeys);

		fputcsv($fp, $savedKeys, ","); // Add the keys as the column headers

		foreach ($csv as $field) {
			
			foreach ($savedKeys AS $checkKey){
					if (!isset($field[$checkKey])){
						$field[$checkKey] = 0;
					}
				}
				
			ksort($field);
				
			fputcsv($fp, $field, ",");
			
			//print_r($field);
		}

		fclose($fp); 







print_r($errori);







