
<?PHP
$StartDate=date_create('2013-08-11');
$StartDateStr = $StartDate->format('Y-m-d H:i:s');
$EndDate = date_create($StartDateStr);
date_modify($EndDate, '+1 day');
$EndDateStr = $EndDate->format('Y-m-d H:i:s');
var_dump($StartDateStr);
var_dump($EndDateStr);
//33, 34, 35, 36, 49, 50, 51, 52 are 15 mins, 65, 66, 67, 68, 81, 82, 83, 84, 97, 98, 99, 100 are in 1 min. 
	
	//get result from query and save it into csv file. 
$arrayZoneId = array(33, 34, 35, 36, 49, 50, 51, 52, 65, 66, 67, 68, 81, 82, 83, 84, 97, 98, 99, 100);
$arrayFileName = array(
    33 => 'videoInput_33.csv',
    34 => 'videoInput_34.csv',
    35 => 'videoInput_35.csv',
    36 => 'videoInput_36.csv',
    49 => 'videoInput_49.csv',
    50 => 'videoInput_50.csv',
    51 => 'videoInput_51.csv',
    52 => 'videoInput_52.csv',
    65 => 'videoInput_65.csv',
    66 => 'videoInput_66.csv',
    67 => 'videoInput_67.csv',
    68 => 'videoInput_68.csv',
    81 => 'videoInput_81.csv',
    82 => 'videoInput_82.csv',
    83 => 'videoInput_83.csv',
    84 => 'videoInput_84.csv',
    97 => 'videoInput_97.csv',
    98 => 'videoInput_98.csv',
    99 => 'videoInput_99.csv',
    100 => 'videoInput_100.csv'
);


while(true){

  $now = new DateTime(null, new DateTimeZone('America/New_York'));
	$nowStr = $now->format('Y-m-d');
	
	
  $CompareDate=date_create($EndDateStr);
	date_modify($CompareDate, '+1 day');
	$CompareDateStr= $CompareDate->format('Y-m-d H:i:s');
	var_dump($CompareDateStr);
	

	//Compare the current time and the latest update time in sql server, get all the available data. 
	if($now > $CompareDate)
	{
		print "sleep 10 seconds; \n";
	   $sleepSeconds=10;
	}
	else
	{
		//the data updates once per day, we read data every 24 hours. 
	    print "sleep one day \n";
		$sleepSeconds=86400;
	}
	sleep($sleepSeconds);


	//echo $EndDate->format('Y-m-d H:i:s');
	$user_name = "username";
	$password = "password";
	$database = "database";
	$server = "IPaddress";
	
	$db_handle = mysql_connect($server, $user_name, $password);
	print "MYsql Database connected \n". 
	$db_found = mysql_select_db($database, $db_handle);
	
	if ($db_found) {
		print "\n";
		foreach ($arrayZoneId as $zoneID){
			$fileName = $arrayFileName[$zoneID];
			
			$SQL = "SELECT * FROM tzonedata where  ZoneID=$zoneID and  TimeStamp >= '". $StartDateStr ."' and TimeStamp< '".$EndDateStr."'";
			var_dump($SQL);
			$result = mysql_query($SQL);
			var_dump($result);
			$fp = fopen($fileName, 'w');
			if ($fp)
			{
				while ( $db_field = mysql_fetch_assoc($result) ) {
					fputcsv($fp, $db_field);
					//print $db_field;
				}
				fclose($fp); 
				print " file wrote finsihed \n";
			}
			else
			{
				print "file cannot open \n";
			}
		}
		mysql_close($db_handle);
		
		print "saving data into sql server\n";
		//execute exe file written by C# to process the data (aggregate the 1 min to 15 mins)
		exec("vedioDataSaving.exe");
		print "data successfully saved\n";
		
	}
	else
	{
		print "Database NOT Found \n";
		mysql_close($db_handle);
	}
	
	
	
	$StartDateStr = $EndDateStr;
	var_dump($StartDateStr);
	$EndDate = date_create($StartDateStr);
	date_modify($EndDate, '+1 day');
  $EndDateStr = $EndDate->format('Y-m-d H:i:s');
}
?> 

