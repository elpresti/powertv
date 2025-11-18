<?php

//ini_set('display_errors', 1);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

header('Content-Type: text/html; charset=utf-8');

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: http://powerhd.com.ar");

include("simple_html_dom.php"); //Basic HTML parsing with PHP	

date_default_timezone_set('America/Argentina/Buenos_Aires');

$outMsg="NO MESSAGE";
$outStatusCode=500;
$outData=null;
$monthsNames = array( 'Enero' => 1, 'Febrero' => 2, 'Marzo' => 3, 'Abril' => 4, 'Mayo' => 5, 'Junio' => 6, 'Julio' => 7,
					'Agosto' => 8, 'Septiembre' => 9, 'Octubre' => 10, 'Noviembre' => 11, 'Diciembre' => 12 );
$MAX_FILE_SIZE = 200 * 1024; // 200 KB
$HTML_FILE_PATH = __DIR__ . "/telpinWeatherData.html";
	
	function printResultInJson(){
		global $outMsg, $outStatusCode, $outData;
		//$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
		$arr = array(
		    'statusCode' => $outStatusCode,
		    'msg' => mb_convert_encoding($outMsg, 'UTF-8', 'ISO-8859-1'),
		    'outData' => json_encode($outData)
		);
		$out = json_encode($arr);
		$out = str_replace("\\\\\\", "", $out);
		echo $out;
	}

	//parse_str($_SERVER['QUERY_STRING'], $params);

	
	function getCurrentWeatherData(){
		global $HTML_FILE_PATH;
		
		/* //BLOCKED FROM REMOTE SERVER:
		$fullUrl="http://eltiempo.telpin.com.ar/infocel.htm";
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		); 
		$fullHtmlCode = file_get_html($fullUrl, false, stream_context_create($arrContextOptions));
		*/

		if (!file_exists($HTML_FILE_PATH)) {
			error_log("Error: The local weather data file does not exist.");
			return null;
		}

		$fullHtmlCode = file_get_html($HTML_FILE_PATH);
		if (!$fullHtmlCode) {
			error_log("Error: Failed to parse the local weather data file.");
			return null;
		}
	
		$htmlCode = $fullHtmlCode->find('table', 1);
		
		//printHtmlRelevantData($htmlCode);die();
		
		$out = array(
			'datetime' => getDatetime($htmlCode),
			'temperature' => getTemperature($htmlCode),
			'humedity' => getHumedity($htmlCode),
			'realfeel' => getRealFeel($htmlCode),
			'windDirection' => getWindDirection($htmlCode),
			'windSpeed' => getWindSpeed($htmlCode),
			'fallenRain' => getFallenRain($fullHtmlCode),
			'pressure' => getPressure($htmlCode)
		);
		return $out;
	}
	
	function getDatetime($htmlCode){
		global $monthsNames;
		$out = null;
		$strDatetime = $htmlCode->find('span',0)->plaintext;
		$strDatetime = str_replace("Datos de la hora","",$strDatetime);
		if (!empty($strDatetime)  &&  strlen($strDatetime)>0){
			$strTmp = trim($strDatetime);
			$strTmp=explode("del",$strTmp);
			$timeStr = trim($strTmp[0]);
			
			$dateStr = explode("de",trim($strTmp[1]));
			$yyyy=trim($strTmp[2]);
			$dd=trim($dateStr[0]);
			$mm=$monthsNames[trim($dateStr[1])];
			$dateStr=$dd."-".$mm."-".$yyyy;
			$dateTimeStr = $dateStr." ".$timeStr;

			$datetime = date_create_from_format('j-m-Y H:i',$dateTimeStr);
			$out = date_format($datetime,'Y-m-d H:i');
		}
		return $out;
	}
	
	function getTemperature($htmlCode){
		$out = null;
		$strTmp = $htmlCode->find('span',1)->plaintext;
		$strTmp = str_replace($htmlCode->find('b',0)->plaintext,"",$strTmp);
		if (!empty($strTmp)  &&  strlen($strTmp)>0){
			$out = getOnlyNumbers(trim(str_replace(",",".",$strTmp)));
		}
		return $out;
	}
	
	function getHumedity($htmlCode){
		$out = null;
		$strTmp = $htmlCode->find('span',2)->plaintext;
		$strTmp = str_replace($htmlCode->find('b',1)->plaintext,"",$strTmp);
		if (!empty($strTmp)  &&  strlen($strTmp)>0){
			$out = getOnlyNumbers(trim(str_replace(",",".",$strTmp)));
		}
		return $out;
	}
	
	function getRealFeel($htmlCode){
		$out = null;
		$strTmp = $htmlCode->find('span',3)->plaintext;
		$strTmp = str_replace($htmlCode->find('b',2)->plaintext,"",$strTmp);
		if (!empty($strTmp)  &&  strlen($strTmp)>0){
			$out = getOnlyNumbers(trim(str_replace(",",".",$strTmp)));
		}
		return $out;
	}
	
	function getWindSpeed($html){
		$out = null;
		$strTmp = $html->find('span',4)->plaintext;
		$strTmp = str_replace($html->find('b',3)->plaintext,"",$strTmp);
		if (!empty($strTmp)  &&  strlen($strTmp)>0){
			$strTmp=explode("a",strtolower($strTmp));
			$strTmp = trim($strTmp[1]);
			$out = getOnlyNumbers(trim(str_replace(",",".",$strTmp)));
		}
		return $out;
	}
	
	function getWindDirection($html){
		$out = null;
		$strTmp = $html->find('span',4)->plaintext;
		$strTmp = str_replace($html->find('b',3)->plaintext,"",$strTmp);
		if (!empty($strTmp)  &&  strlen($strTmp)>0){
			$strTmp=explode("a",strtolower($strTmp));
			$out = strtoupper(trim($strTmp[0]));
		}
		return $out;
	}
	
	function getFallenRain($html){
		$out = null;
		try{
			$strTmp = $html->find('script',0)->innertext;
			$strTmp = preg_replace("/\s+/", "", $strTmp); //remove all kind of spaces
			$out1 = 0;
			$fallenRain1 = get_string_between($strTmp, 'rfall=', ';');
			if (!empty($fallenRain1)  &&  strlen($fallenRain1)>0){
				$out1 = getOnlyNumbers(trim(str_replace(",",".",$fallenRain1)));
				if (empty($out1)  ||  strlen($out1)==0){
					$out1 = 0.2;
				}
			}
			$out2 = 0;
			$fallenRain2 = get_string_between($strTmp, 'rfallY=', ';');
			if (!empty($fallenRain2)  &&  strlen($fallenRain2)>0){
				$out2 = getOnlyNumbers(trim(str_replace(",",".",$fallenRain2)));
				if (empty($out2)  ||  strlen($out2)==0){
					$out2 = 0.2;
				}
			}
			$out = round($out1 + $out2);
		}catch(Exception $e){
			$out = 0.1;
		}
		return $out;
	}
	
	function getPressure($html){
		$out = null;
		$strTmp = $html->find('span',6)->plaintext;
		$strTmp = str_replace($html->find('b',5)->plaintext,"",$strTmp);
		if (!empty($strTmp)  &&  strlen($strTmp)>0){
			$out = getOnlyNumbers(trim(str_replace(",",".",$strTmp)));
		}
		return $out;
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
		foreach($htmlCodeMainDiv->find('head') as $element) {
			echo '<br><strong>head element '.$i.':</strong><br>'.$element->plaintext.'<br><br>';
			$i++;
		}
		foreach($htmlCodeMainDiv->find('script') as $element) {
			echo '<br><strong>script element '.$i.':</strong><br>'.$element->innertext.'<br><br>';
			$i++;
		}
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
	
	function executeSaveHtmlInfo($html_data) {
		global $MAX_FILE_SIZE, $HTML_FILE_PATH;
		
		$decodedData = base64_decode($html_data, true);

		if ($decodedData === false) {
			http_response_code(400);
			echo "Error: Invalid data received.";
			exit;
		}

		if (strlen($decodedData) > $MAX_FILE_SIZE) {
			http_response_code(413);
			echo "Error: The file exceeds the maximum allowed size.";
			exit;
		}

		if (file_put_contents($HTML_FILE_PATH, $decodedData) !== false) {
			echo "File successfully saved.";
		} else {
			http_response_code(500);
			echo "Error: Failed to save the file.";
		}
	}
	
	$action=$_GET['action'];
	//$action = 'getcurrentweather';//just for DEV
	if ($action=='getcurrentweather' ) {
		$outData = getCurrentWeatherData();
		if ($outData){
			$nullItems=0;
			foreach($outData as $item){
				if ($item==null){
					$nullItems++;
				}
			}
			if ($nullItems<3){
				$outStatusCode="200"; 
				$outMsg="DONE!";
			}else{
				$outMsg="Many null items, something went wrong";
			}
		}else{
			$outMsg='getCurrentWeatherData() returned null';
		}
		//echo json_encode($outData);die();
		printResultInJson();
	}
	
	if ($action == 'save_html_info' ) {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['html_data'])) {
			executeSaveHtmlInfo($_POST['html_data']);
		} else {
			http_response_code(400);
			echo "Error: Unauthorized access.";
		}
	}
	

	
?>
