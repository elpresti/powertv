<?php
	include('../../util/common.php');
	//include('util.php');
	$rawWeatherData=array(
   	'windguru' => null,
      'smn' 	  => null,
      'telpin'   => null
   );
	
	function getRawWeatherData(){
     global $rawWeatherData;
     $windguruSsUrl="https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/1/public/values?alt=json-in-script";
     $rawWeatherData['windguru'] = getSpreadSheetDataIntoArray($windguruSsUrl);
     
     $smnSsUrl="https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/2/public/values?alt=json-in-script";
     $rawWeatherData['smn'] = getSpreadSheetDataIntoArray($smnSsUrl);
     
     $telpinSsUrl="https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/3/public/values?alt=json-in-script";
     $rawWeatherData['telpin'] = getSpreadSheetDataIntoArray($telpinSsUrl);
     
     //echo '-------PRIMERO---------<pre>' . var_export($rawWeatherData,true).'</pre>--------- FIN PRIMERO ---------';
     //echo json_encode($rawWeatherData);
   }

	function getCurrentWeather(){
     
   }

	function getForecast(){
     
   }

	function getWindForecast(){
     
   }

	getRawWeatherData();

?>