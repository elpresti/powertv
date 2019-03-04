<?php




	header('Content-Type: text/html; charset=utf-8');
	
	header("Access-Control-Allow-Origin: *");
	//header("Access-Control-Allow-Origin: http://powerhd.com.ar");
	
	include("simple_html_dom.php"); //Basic HTML parsing with PHP	

	date_default_timezone_set('America/Argentina/Buenos_Aires');

	$outMsg="NO MESSAGE";
	$outStatusCode=500;
	$outData=null;
	//$dayWeekNames = array('Domingo','Lunes','Martes',utf8_encode('Miércoles'),'Jueves','Viernes',utf8_encode('Sábado'));
	$dayWeekNames = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
	$monthsNames = array( 'Enero' => 1, 'Febrero' => 2, 'Marzo' => 3, 'Abril' => 4, 'Mayo' => 5, 'Junio' => 6, 'Julio' => 7,
						'Agosto' => 8, 'Septiembre' => 9, 'Octubre' => 10, 'Noviembre' => 11, 'Diciembre' => 12 );
	$todayForecastTable=null;
	$extendedForecastTable=null;
	$todayMode=null;
	$hoyIsToday=null;
	$day1Forecast=null;
	$day2Forecast=null;
	$day3Forecast=null;
	$day4Forecast=null;
	$day5Forecast=null;
	
	function printResultInJson(){
		global $outMsg, $outStatusCode, $outData;
		$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
		$out = json_encode($arr);
		$out = str_replace("\\\\\\", "", $out);
		echo $out;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	function getForecastData($prov,$city){
			global $todayForecastTable,$extendedForecastTable,$todayMode,$day1Forecast,$day2Forecast,$day3Forecast,$day4Forecast,$day5Forecast;
//			$fullUrl="http://www.smn.gov.ar/?mod=pron&id=4&provincia=".$prov."&ciudad=".$city;
//			$fullUrl="https://www.smn.gob.ar/smn/current_weather_by_city/".$locationId."/true";//4298
			$fullUrl="http://www3.smn.gov.ar/?mod=pron&id=4&provincia=".$prov."&ciudad=".$city;

		    $htmlCode = file_get_html($fullUrl);
			
			$htmlCodeMainDiv = $htmlCode->find('div[id=pruebaajax]', 0);
			$todayForecastTable=getTodayForecastTable($htmlCodeMainDiv);
			$todayMode=$todayForecastTable['todayMode'];
			$todayForecastTable=$todayForecastTable['todayForecastTable'];
			$extendedForecastTable=getExtendedForecastTable($htmlCodeMainDiv,$todayMode);
			
			/*
			echo "<br>todayMode=".$todayMode."<br>";
			echo "<br><br>todayForecastTable=".$todayForecastTable."<br>";
			//$htmlCodeMainDiv = $extendedForecastTable;
			$htmlCodeMainDiv = $extendedForecastTable;
			echo "<br>extended forecast table:<br>".$extendedForecastTable;
			//echo "<br><br>getTodayForecastTable():<br>".getTodayForecastTable($htmlCodeMainDiv)."<br><br>";
			//echo "<br><br>getExtendedForecastTable():<br>".getExtendedForecastTable($htmlCodeMainDiv)."<br><br>";

			//printHtmlRelevantData($htmlCodeMainDiv);
			*/
			$day1Forecast=getDia1Forecast($todayForecastTable,$todayMode);
			if (hoyIsToday()){
				$day1Forecast['date']=date('c', strtotime("+0 day"));
			}else{
				$day1Forecast['date']=date('c', strtotime("-1 day"));
			}
			//echo "<br><br>getDia1Forecast():<br>".print_r($day1Forecast,TRUE)."<br><br>";
			if ($todayMode){
				$day2Forecast=getDia2Forecast($todayForecastTable,$todayMode);
			}else{
				$day2Forecast=getDia2Forecast($extendedForecastTable,$todayMode);
			}
			//echo "<br><br>getDia2Forecast():<br>".print_r($day2Forecast,TRUE)."<br><br>";
			$day3Forecast=getDia3Forecast($extendedForecastTable,$todayMode);
			//echo "<br><br>getDia3Forecast():<br>".print_r($day3Forecast,TRUE)."<br><br>";
			$day4Forecast=getDia4Forecast($extendedForecastTable,$todayMode);
			//echo "<br><br>getDia4Forecast():<br>".print_r($day4Forecast,TRUE)."<br><br>";
			$day5Forecast=getDia5Forecast($extendedForecastTable,$todayMode);
			//echo "<br><br>getDia5Forecast():<br>".print_r($day5Forecast,TRUE)."<br><br>";
			
			$out = array(
				'alertaVigente' => getAlertaCiudad($htmlCodeMainDiv),
				'day1Forecast' => $day1Forecast,
				'day2Forecast' => $day2Forecast,
				'day3Forecast' => $day3Forecast,
				'day4Forecast' => $day4Forecast,
				'day5Forecast' => $day5Forecast
			);
			//echo "<br><br>outValues:<br>".print_r($out,TRUE)."<br><br>";

			return $out;
	}
	
	function getCurrentWeatherData($stationId){
		global $currentWeatherInfo;
		//$fullUrl="http://www.smn.gov.ar/?mod=dpd&id=21&e=".$stationId;
		$fullUrl="http://www3.smn.gov.ar/?mod=dpd&id=21&e=".$stationId;
		$htmlCode = file_get_html($fullUrl);
		
		$htmlCodeMainDiv = $htmlCode->find('div[id=pruebaajax]', 0);
		
		$html = $htmlCodeMainDiv;
		$out = array(   
			'city' => getCityName($html->find('td',1)->plaintext),
			'datetime' => buildDateTime(trim($html->find('td',4)->plaintext),trim($html->find('td',5)->plaintext)),
			'weatherStatus' => trim($html->find('span',4)->plaintext),
			'visibility' => getOnlyNumbers($html->find('span',5)->plaintext),
			'temperature' => getOnlyNumbers($html->find('span',6)->plaintext),
			'humidity' => getOnlyNumbers($html->find('span',7)->plaintext),
			'windSpeed' => getWindSpeed($html->find('span',8)->plaintext),
			'windDirection' => getWindDirection($html->find('span',8)->plaintext),
			'realFeel' => $html->find('span',9)->plaintext,
			'currentIcon' => (strpos($html->find('span',4)->parent()->parent()->style, 'background-image') !== false) ? get_string_between($html->find('span',4)->parent()->parent()->style, 'url(', ')') : null,
			'pressure' => getOnlyNumbers($html->find('span',10)->plaintext)
		);
		
		//echo "<br><br>outValues:<br>".print_r($out,TRUE)."<br><br>";
		
		return $out;
	}
	
	function getUVforecastData($cityName){
		$out = array(   
			'city' => $cityName,
			'ISUV' => null,
			'ISUVn' => null,
			'solarHalfDay' => null
		);
		//$fullUrl="http://www.smn.gov.ar/?mod=ozono&id=11&var=1";
		$fullUrl="http://www3.smn.gov.ar/?mod=ozono&id=11&var=1";
		$htmlCode = file_get_html($fullUrl);
		
		$htmlCodeMainDiv = $htmlCode->find('div[id=pruebaajax]', 0);
		
		$html = $htmlCodeMainDiv->find('table',1);
		//$i=0;
		$cityToFind=mb_strtolower($cityName, 'UTF-8');
		$cityFound=false;
		foreach($html->find('tr') as $element) {
			//echo '<br><strong>tr '.$i.':</strong><br>'.$element->plaintext . '<br><br>';
			if (strlen($element->plaintext)<100  &&  strpos(mb_strtolower($element->plaintext,'UTF-8'),$cityToFind)!==false){
				$cityFound=true;
				break;
			}
			//$i++;
		}
		if ($cityFound){
			$out['ISUV'] = trim($element->find('td',1)->plaintext);
			$out['ISUVn'] = trim($element->find('td',2)->plaintext);
			$out['solarHalfDay'] = trim($element->find('td',3)->plaintext);
		}
		return $out;
	}
	
	function getCapitalLetters($str){
		if(preg_match_all('#([A-Z]+)#',$str,$matches)){
			return implode('',$matches[1]);
		}else{
			return false;
		}
	}
	
	function getCityName($str){
		$out=getCapitalLetters($str);
		return $out;
	}
	
	function getWindSpeed($str){
		$str=getOnlyNumbers($str);
		return $str;
	}
	
	function getWindDirection($str){
		$str=trim($str);
		$str=explode(" ",$str);
		return $str[0];
	}
	
	function buildDateTime($dateStr,$timeStr){
		global $monthsNames;
		$dateStr=explode(":",$dateStr);
		$dateStr=trim($dateStr[1]);
		$dateStr=explode("-",$dateStr);
		$dateStr=$dateStr[0]."-".$monthsNames[$dateStr[1]]."-".$dateStr[2];
		
		$timeStr=explode(":",$timeStr);
		$timeStr=trim($timeStr[1].":".$timeStr[2]);
		$timeStr=explode(" ",$timeStr);
		$timeStr=$timeStr[0];
		$datetime = date_create_from_format('j-m-Y H:i',$dateStr." ".$timeStr);
		return date_format($datetime,'Y-m-d H:i');
	}
	
	function printHtmlRelevantData($htmlCodeMainDiv){
		$i=0;
		foreach($htmlCodeMainDiv->find('table') as $element) {
			echo '<br><strong>table '.$i.':</strong><br>'.$element->plaintext . '<br><br>';
			$i++;
		}
		$i=0;
		foreach($htmlCodeMainDiv->find('td') as $element) {
			echo '<br><strong>td '.$i.':</strong><br>'.$element->plaintext . '<br><br>';
			$i++;
		}
		$i=0;
		foreach($htmlCodeMainDiv->find('b') as $element) {
			echo '<br><strong>b element '.$i.':</strong><br>'.$element->plaintext.'<br><br>';
			$i++;
		}
		$i=0;
		foreach($htmlCodeMainDiv->find('p') as $element) {
			echo '<br><strong>p element '.$i.':</strong><br>'.$element->plaintext.'<br><br>';
			$i++;
		}
		$i=0;
		foreach($htmlCodeMainDiv->find('span') as $element) {
			echo '<br><strong>span element '.$i.':</strong><br>'.$element->plaintext.'<br><br>';
			$i++;
		} 
		$i=0;
		foreach($htmlCodeMainDiv->find('a') as $element) {
			echo '<br><strong>a element '.$i.':</strong><br>'.$element->plaintext.'<br><br>';
			$i++;
		}
		$i=0;
		foreach($htmlCodeMainDiv->find('img') as $element) {
			echo '<br><strong>img->src element '.$i.':</strong><br>'.$element->src.'<br><br>';
			$i++;
		}
		$i=0;
		foreach($htmlCodeMainDiv->find('font') as $element) {
			echo '<br><strong>font element '.$i.':</strong><br>'.$element->plaintext.'<br><br>';
			$i++;
		}		
	}
	
	function getTodayForecastTable($html){
		$out=null;
		$todayMode=false;
		$i=0;
		foreach($html->find('table') as $element) {
			$elemText = $element->plaintext;
			//$elemText = strtolower($elemText);
			//$elemText = mb_convert_encoding($elemText, "UTF-8", "auto");
			$elemText = mb_strtolower($elemText, 'UTF-8');
			if ( strlen($elemText)>1  &&  
				(strpos($elemText,"hoy")!==false  ||  strpos($elemText,mb_strtolower(getWeekdayName(), "UTF-8"))!==false )  && 
				(strpos($elemText,"tarde/noche")!==false )
			){
				$out=$element;
				if (strpos($elemText,"hoy")!==false){
					$todayMode=true;
				}
			}
			$i++;
		}
		return array('todayMode'=>$todayMode,'todayForecastTable'=>$out);
	}
	
	function getExtendedForecastTable($html,$todayMode){
		$out=null;
		$i=0;
		foreach($html->find('table') as $element) {
			$elemText = $element->plaintext;
			//$elemText = strtolower($elemText);
			$elemText = mb_strtolower($elemText, 'UTF-8');
			//$elemText = mb_convert_encoding($elemText, "UTF-8", "auto");
			if ($todayMode){
				if (hoyIsToday()){
					if (strlen($elemText)>1  &&  
						( strpos($elemText,mb_strtolower(getWeekdayName(2), "UTF-8"))!==false  &&  
						strpos($elemText,mb_strtolower(getWeekdayName(3), "UTF-8"))!==false  &&  
						strpos($elemText,mb_strtolower(getWeekdayName(4), "UTF-8"))!==false ) ){
							$out=$element;
					}
				}else{
					if (strlen($elemText)>1  &&  
						( strpos($elemText,mb_strtolower(getWeekdayName(1), "UTF-8"))!==false  &&  
						strpos($elemText,mb_strtolower(getWeekdayName(2), "UTF-8"))!==false  &&  
						strpos($elemText,mb_strtolower(getWeekdayName(3), "UTF-8"))!==false ) ){
							$out=$element;
					}
				}
			}else{
				$daynamePlus1=mb_strtolower(getWeekdayName(1), "UTF-8");
				$daynamePlus2=mb_strtolower(getWeekdayName(2), "UTF-8");
				$daynamePlus3=mb_strtolower(getWeekdayName(3), "UTF-8");
				if (strlen($elemText)>1  &&  
					( strpos($elemText,$daynamePlus1)!==false  &&  
					strpos($elemText,$daynamePlus2)!==false  &&  
					strpos($elemText,$daynamePlus3)!==false ) ){
						$out=$element;
				}
			} 
			$i++;
		}
		return $out;
	}
	
	function hoyIsToday(){
		global $day1Forecast,$todayForecastTable,$todayMode,$hoyIsToday;
		if ($hoyIsToday!=null){
			return $hoyIsToday;
		}
		$out=false;
		if ($day1Forecast==null  ||  sizeof($day1Forecast)==0){
			if ($todayForecastTable!= null  &&  $todayMode!=null){
				$day1Forecast=getDia1Forecast($todayForecastTable,$todayMode);
			}else{
				echo "hoyIsToday(): ERROR! todayForecastTable  or  todayMode  is null!";
				return null;
			}
		}
		if ($day1Forecast['dayweekNumber'] == date("w")){
			$out=true;
		}
		$hoyIsToday=$out;
		return $out;
	}
	
	function getWeekdayName($dayOffset=0){
		global $dayWeekNames;
		if ($dayOffset==0 || $dayOffset<0){
			$currentDayweekNumber = date("w");
			return $dayWeekNames[$currentDayweekNumber];
		}else{
			$dayWeekWithOffset=date('w', strtotime('+'.$dayOffset.' day'));
			return $dayWeekNames[$dayWeekWithOffset];
		}
	}
	
	function getAlertaCiudad($html){
		return null;
		//$out=$html->find('a',0)->href;
	}
	
	function getDayInfo($str){
		global $dayWeekNames;
		$out = array('dayName' => $str, 'dayNumber' => null);
		$i=0;
		foreach($dayWeekNames as $dayName){
			if (strpos(mb_strtolower($str,'UTF-8'),mb_strtolower($dayName,'UTF-8')) !== false) {
				$out['dayweekName'] = $dayName;
				$out['dayweekNumber'] = $i;
				break;
			}
			$i++;
		}
		return $out;
	}
	
	function getDia1Forecast($html,$todayMode=false){
		$dayInfo=getDayInfo($html->find('td',0)->plaintext);
		if ($todayMode){
			$out=array(   
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => null,
				'descripcionTardeNoche' => $html->find('table',0)->plaintext,
				'minTemp' => null,
				'maxTemp' => null,
				'iconoManiana' => null,
				'iconoTardeNoche' => $html->find('img',0)->src
			);
		}else{
			$out=array(   
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('td',5)->plaintext,
				'descripcionTardeNoche' => $html->find('td',6)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',0)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',1)->plaintext),
				'iconoManiana' => $html->find('img',0)->src,
				'iconoTardeNoche' => $html->find('img',1)->src
			);
		}
		$out['descripcionManiana']=trim($out['descripcionManiana']);
		$out['descripcionTardeNoche']=trim($out['descripcionTardeNoche']);
		return $out;
	}

	function getDia2Forecast($html,$todayMode=false){
		if ($todayMode){
			$dayInfo=getDayInfo($html->find('p',1)->plaintext);
			$out=array(   
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('table',1)->plaintext,
				'descripcionTardeNoche' => $html->find('table',2)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',1)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',2)->plaintext),
				'iconoManiana' => $html->find('img',1)->src,
				'iconoTardeNoche' => $html->find('img',2)->src
			);			
		}else{
			$dayInfo=getDayInfo($html->find('b',0)->plaintext);
			$out=array(
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('table',0)->plaintext,
				'descripcionTardeNoche' => $html->find('table',1)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',0)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',1)->plaintext),
				'iconoManiana' => $html->find('img',0)->src,
				'iconoTardeNoche' => $html->find('img',1)->src
			);
		}
		$out['descripcionManiana']=trim($out['descripcionManiana']);
		$out['descripcionTardeNoche']=trim($out['descripcionTardeNoche']);
		if (hoyIsToday()){
			$out['date']=date('c', strtotime("+1 day"));
		}else{
			$out['date']=date('c', strtotime("+0 day"));
		} 
		return $out;
	}
	
	function getDia3Forecast($html,$todayMode=false){
		$hoyIsToday=hoyIsToday();
		if ($todayMode){
			$dayInfo=getDayInfo($html->find('b',0)->plaintext);
			$out=array(
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('table',0)->plaintext,
				'descripcionTardeNoche' => $html->find('table',1)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',0)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',1)->plaintext),
				'iconoManiana' => $html->find('img',0)->src,
				'iconoTardeNoche' => $html->find('img',1)->src
			); 
		}else{
			$dayInfo=getDayInfo($html->find('b',1)->plaintext);
			$out=array(
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('table',2)->plaintext,
				'descripcionTardeNoche' => $html->find('table',3)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',2)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',3)->plaintext),
				'iconoManiana' => $html->find('img',2)->src,
				'iconoTardeNoche' => $html->find('img',3)->src
			);
		}
		$out['descripcionManiana']=trim($out['descripcionManiana']);
		$out['descripcionTardeNoche']=trim($out['descripcionTardeNoche']);
		if ($hoyIsToday){
			$out['date']=date('c', strtotime("+2 day"));
		}else{
			$out['date']=date('c', strtotime("+1 day"));
		}
		return $out;
	}
	
	function getDia4Forecast($html,$todayMode=false){
		$hoyIsToday=hoyIsToday();
		if ($todayMode){
			$dayInfo=getDayInfo($html->find('b',1)->plaintext);
			$out=array(
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('table',2)->plaintext,
				'descripcionTardeNoche' => $html->find('table',3)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',2)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',3)->plaintext),
				'iconoManiana' => $html->find('img',2)->src,
				'iconoTardeNoche' => $html->find('img',3)->src
			);
		}else{
			$dayInfo=getDayInfo($html->find('b',2)->plaintext);
			$out=array(
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => $html->find('table',4)->plaintext,
				'descripcionTardeNoche' => $html->find('table',5)->plaintext,
				'minTemp' => getOnlyNumbers($html->find('font',4)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',5)->plaintext),
				'iconoManiana' => $html->find('img',4)->src,
				'iconoTardeNoche' => $html->find('img',5)->src
			);
		}
		if ($hoyIsToday){
			$out['date']=date('c', strtotime("+3 day"));
		}else{
			$out['date']=date('c', strtotime("+2 day"));
		}
		$out['descripcionManiana']=trim($out['descripcionManiana']);
		$out['descripcionTardeNoche']=trim($out['descripcionTardeNoche']);
		return $out;
	}
	
	function getDia5Forecast($html,$todayMode=false){
		$out=null;
		$hoyIsToday=hoyIsToday();
		if ($todayMode){
			$dayInfo=getDayInfo($html->find('b',2)->plaintext);
			$out=array(
				'dayweekName' => $dayInfo['dayweekName'],
				'dayweekNumber' => $dayInfo['dayweekNumber'],
				'descripcionManiana' => trim($html->find('table',4)->plaintext),
				'descripcionTardeNoche' => trim($html->find('table',5)->plaintext),
				'minTemp' => getOnlyNumbers($html->find('font',4)->plaintext),
				'maxTemp' => getOnlyNumbers($html->find('font',5)->plaintext),
				'iconoManiana' => $html->find('img',4)->src,
				'iconoTardeNoche' => $html->find('img',5)->src
			);
		}
		if ($out != null){
			if ($hoyIsToday){
				$out['date']=date('c', strtotime("+4 day"));
			}else{
				$out['date']=date('c', strtotime("+3 day"));
			}
		}
		return $out;
	}
	
	function getOnlyNumbers($str){
		if (strlen($str)>0){
			$str = filter_var($str,FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		}
		return $str;
	}
	
	function get_string_between($string, $start, $end){
		$string = ' ' . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return '';
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return substr($string, $ini, $len);
	}
	
	$prov=$_GET['prov'];
	$action=$_GET['action'];
	$stationId=$_GET['stationId'];
	$prov="Buenos%20Aires";
	$city=$_GET['city'];
	$action="getforecast";
	$city="Pinamar";
	
	//$action='getcurrentweather';
	//$stationId="87663";
	
	if ($action=='getforecast'  &&  isset($prov)  &&  strlen($prov)>0  &&  isset($city)  &&  strlen($city)>0 ) {
		$outData=getForecastData($prov,$city);
		if ($outData){
			$outStatusCode="200";
			$outMsg="DONE!";
		}else{
			$outMsg='getForecastData() returned null';
		}
		//echo json_encode($outData);die();
		printResultInJson();
	}
	if ($action=='getcurrentweather'  &&  isset($stationId)  &&  strlen($stationId)>0 ) {
		$outData = getCurrentWeatherData($stationId);
		if ($outData){
			$outStatusCode="200";
			$outMsg="DONE!";
		}else{
			$outMsg='getCurrentWeatherData() returned null';
		}
		//echo json_encode($outData);die();
		printResultInJson();
	}
	if ($action=='getuvforecast'  &&  isset($city)  &&  strlen($city)>0 ) {
		$outData = getUVforecastData($city);
		if ($outData){
			$outStatusCode="200";
			$outMsg="DONE!";
		}else{
			$outMsg='getUVforecastData() returned null';
		}
		//echo json_encode($outData);die();
		printResultInJson();
	}
	

?>