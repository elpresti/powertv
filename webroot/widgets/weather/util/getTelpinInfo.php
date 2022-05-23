<?php
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
	
	function printResultInJson(){
		global $outMsg, $outStatusCode, $outData;
		$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
		$out = json_encode($arr);
		$out = str_replace("\\\\\\", "", $out);
		echo $out;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	
	function getCurrentWeatherData(){
		$fullUrl="http://eltiempo.telpin.com.ar/infocel.htm";
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		); 
		$fullHtmlCode = file_get_html($fullUrl, false, stream_context_create($arrContextOptions));
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
			$fallenRain = get_string_between($strTmp, 'rfall=', ';');
			if (!empty($fallenRain)  &&  strlen($fallenRain)>0){
				$out = getOnlyNumbers(trim(str_replace(",",".",$fallenRain)));
				if (empty($out)  ||  strlen($out)==0){
					$out = 0.2;
				}
			}
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
	
	$action=$_GET['action'];
	$action = 'getcurrentweather';//QUITAR esto!
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
	

?>
