<?php
//$spreadsheetData = file_get_contents('https://script.google.com/macros/s/AKfycbw9_xKBnH8kOmexyvmo4kXNIOYnPCKtuisTgJ7VkFYyCaRWQGf1XqiFFF6FIqaxHQwv/exec');
//echo "spreadsheetData: <br>".$spreadsheetData; die();

	date_default_timezone_set('America/Argentina/Buenos_Aires');

	$outMsg="NO MESSAGE";
	$outStatusCode=500;
	$outData=null;

	function printResultInJson2(){
		global $outMsg, $outStatusCode, $outData;
		$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
		$out = json_encode($arr);
		$out = str_replace("\\\\\\", "", $out);
		echo $out;
	}

	if ($_GET['action']=='getwinddata' ) {
		$outData = getWindData();
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
			$outMsg='getWindData() returned null';
		}
		//echo json_encode($outData);
		printResultInJson2();
		//echo print_r($outData,true);
		die();
	}


$dayWeekNames = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
$monthsNames = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

function getSpreadSheetDataIntoArray($ssUrl){
  $ssDataArray = array();
  $spreadsheetData = file_get_contents($ssUrl);
  $ssDataObj = json_decode($spreadsheetData);
  $entries = $ssDataObj->{'GoogleSheetData'};
  foreach($entries as $entry){
     $ssDataArray[$entry[0]] = $entry[1];
  }
  //echo '-------PRIMERO---------<pre>' . var_export($ssDataArray,true).'</pre>--------- FIN PRIMERO ---------';
  return $ssDataArray;
}

function getOrderedCandidatesByWinner($ssDataArray){
  //echo '<br>-------ORDEN ENTRADA---------<br><pre>' . var_export($ssDataArray,true).'</pre><br>--------- FIN ORDEN ENTRADA ---------<br>';
   $sortArray = array();
   foreach($ssDataArray as $ssDataItem){
      foreach($ssDataItem as $key=>$value){
          if(!isset($sortArray[$key])){
              $sortArray[$key] = array();
          }
          $sortArray[$key][] = $value;
      }
   }
	$orderby = "porcentaje_parcial"; //change this to whatever key you want from the array
	array_multisort($sortArray[$orderby],SORT_DESC,$ssDataArray);
  //echo '<br>-------ORDEN SALIDA---------<br><pre>' . var_export($ssDataArray,true).'</pre><br>--------- FIN ORDEN SALIDA ---------<br>';
  return $ssDataArray;
}


function img_data_uri($file,$mime=null) {
  if ($file == null){
    return null;
  }
  if ($mime == null){
    $tmp = substr($file, -6);//me traigo los ultimos caracteres
    $pos = strrpos($tmp, ".");
    if (!$pos){
      $fileExtension = 'png';//si en la URL no figura la extension, asigno valor por default
    }else{
      $fileExtension = substr($tmp,$pos+1);//busco el ultimo punto y traigo desde ahi hasta el final
    }
    $mime = 'image/'.strtolower($fileExtension);
  }
  $contents = file_get_contents($file);
  $base64   = base64_encode($contents);
  return ('data:' . $mime . ';base64,' . $base64);
}

//$spreadsheetData = file_get_contents('https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/1/public/values?alt=json-in-script');
////$spreadSheetUrl = 'https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/3/public/values?alt=json-in-script';
////$ssDataObj = getSpreadSheetDataIntoArray($spreadSheetUrl);
////echo json_encode($ssDataObj);
//echo "spreadsheetData: <br>".$spreadsheetData;
//echo "spreadsheetData: <br>".$spreadsheetData;
//$ssDataObj = json_decode($spreadsheetData);
//$ssDataObj = $ssDataObj->{'feed'}->{'entry'};
//echo "var_dump(json_decode: <br>".var_dump($spreadsheetData2,true);
//echo '<pre>' . var_export($ssDataObj, true) . '</pre>';
//echo "var_dump:<br><pre> ".var_export($ssDataObj,true)." </pre>";
//echo "<br>partido1:<br>".var_export($ssDataObj[0]->{'gsx$partido1'},true);
////die();
//echo var_dump();

function add_ajax_request_response_widget($url){
  $htmlOut = '
  	 <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
  	 <script type="text/javascript" src="ajaxRequestJavaServlet.js"></script>
    <div id="updateButtonsContainer" class="updateButtonsContainer">
      <button id="btnUpdateNation" type="button" onclick="updateWidgetNation()">Update Nacion</button>
      <button id="btnUpdateProvincia" type="button" onclick="updateWidgetProvince()">Update ProvinciaBA</button>
      <button id="btnUpdatePinamar" type="button" onclick="updateWidgetPinamar()">Update Pinamar</button>
      <p style="margin: 40px;">Status: <span id="statusMsg">LISTO</span></p>
      <button id="btnShowRequestInfo" class="btnShowRequestInfo" type="button" onclick="showRequestInfo()" >Ver info de envio</button>
      <button id="btnShowResponseInfo" class="btnShowResponseInfo" type="button" onclick="showResponseInfo()" >Ver info de Respuesta</button>
    </div>
    <form id="myAjaxRequestForm" class="myAjaxRequestForm">
          <fieldset>
              <legend>jQuery Ajax Form data Submit Request</legend>
                  <p>
                      <label for="countryCode">URL to do request:</label>
                      <input id="countryCode" type="text" name="countryCode" style="width:100%" value="'.$url.'" />
                  </p>
                  <p>
                      <input id="myButton" type="button" value="Submit" />
                  </p>
          </fieldset>
      </form>
      <div id="anotherSection" class="anotherSection">
          <fieldset>
              <legend>Response from jQuery Ajax Request</legend>
                   <div id="ajaxResponse"></div>
          </fieldset>
      </div>';
   return $htmlOut;
}

function getFilledWeatherDataObject(){
	global $dayWeekNames,$monthsNames;
	//set default data, then overwrite
	setlocale(LC_ALL,"es_ES");
	$data = array(
			'currentDataBgImg' => 'http://remtsoy.com/experiments/weather_widget/img/paris-sm.jpg',
			'cityName' => 'Pinamar',
			'nowDatetime' => $dayWeekNames[date('w')]." ".date('d')." de ".$monthsNames[date('n')-1]. " del ".date('Y').", ".strftime("%H:%M")." HS", //'Domingo, 27 de Julio, 18:30',
			'currentTemp' => '+25',
			'currentRealFeel' => '+20 Sensación',
			'currentRainAmountValue' => '743',
			'currentHumidityAmountValue' => '46',
			'currentWindAmountValue' => '16',
			'currentWindDirection' => 'SE',
			'currentAtmPressureValue' => '1014.93',
			'currentWeatherText' => 'Nublado y lluvioso',
			'forecastWindDirIcon' => 'http://radiopower.com.ar/seba/arrow-zero.png',
			'windForecastByHour' => array(
					'0' => array(
							'dateAndTime' => '14/01<br>09 HS',
							'windDirDegr' => '310',
							'windDirTxt' => 'Noroeste<br>(NO)',
							'windSpeed' => '18-25',
							'temp' => '+22.8'
					),
					'1' => array(
							'dateAndTime' => '14/01<br>12 HS',
							'windDirDegr' => '193',
							'windDirTxt' => 'Sursuroeste<br>(SSO)',
							'windSpeed' => '17-24',
							'temp' => '+21'
					),
					'2' => array(
							'dateAndTime' => '14/01<br>15 HS',
							'windDirDegr' => '45',
							'windDirTxt' => 'Noreste<br>(NE)',
							'windSpeed' => '16-26',
							'temp' => '+20'
					),
					'3' => array(
							'dateAndTime' => '14/01<br>18 HS',
							'windDirDegr' => '215',
							'windDirTxt' => 'Suroeste<br>(SO)',
							'windSpeed' => '14-18',
							'temp' => '+19.3'
					),
					'4' => array(
							'dateAndTime' => '14/01<br>21 HS',
							'windDirDegr' => '356',
							'windDirTxt' => 'Norte<br>(N)',
							'windSpeed' => '13-19',
							'temp' => '+18'
					),
					'5' => array(
							'dateAndTime' => '15/01<br>00 HS',
							'windDirDegr' => '124',
							'windDirTxt' => 'Sudeste<br>(SE)',
							'windSpeed' => '12-17',
							'temp' => '+18'
					),
					'6' => array(
							'dateAndTime' => '15/01<br>03 HS',
							'windDirDegr' => '254',
							'windDirTxt' => 'Oestesuroeste<br>(OSO)',
							'windSpeed' => '11-12',
							'temp' => '+17'
					)
			),
			'forecastByDay' => array(
					'0' => array(
							'dayName' => 'lunes',
							'dayweekNumber' => '1',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+30',
							'dayMinTemp' => '+21'
					),
					'1' => array(
							'dayName' => 'martes',
							'dayweekNumber' => '2',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+25',
							'dayMinTemp' => '+19'
					),
					'2' => array(
							'dayName' => 'miercoles',
							'dayweekNumber' => '3',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+24',
							'dayMinTemp' => '+18'
					),
					'3' => array(
							'dayName' => 'jueves',
							'dayweekNumber' => '4',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+27',
							'dayMinTemp' => '+20'
					),
					'4' => array(
							'dayName' => 'viernes',
							'dayweekNumber' => '5',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+30',
							'dayMinTemp' => '+21'
					),
					'5' => array(
							'dayName' => 'sabado',
							'dayweekNumber' => '6',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+24',
							'dayMinTemp' => '+14'
					),
					'6' => array(
							'dayName' => 'domingo',
							'dayweekNumber' => '0',
							'dayIcon' => 'http://www.free-icons-download.net/images/partly-cloudy-rain-icon-61626.png',
							'dayMaxTemp' => '+25',
							'dayMinTemp' => '+16'
					)
			)
	);

	$ssWINDGURUdataObj = getSpreadSheetDataIntoArray('https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/1/public/values?alt=json-in-script');

	$ssSMNdataObj = getSpreadSheetDataIntoArray('https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/2/public/values?alt=json-in-script');
	$ssTELPINdataObj = getSpreadSheetDataIntoArray('https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/3/public/values?alt=json-in-script');

	$index = 0;
	foreach($data['windForecastByHour'] as  &$windForecastItem){
		$guruTimeInfo = getGuruNextItemInfo($ssWINDGURUdataObj,$index);
		if ($guruTimeInfo['forecastBy3Hours'] == "3"){//skip 03hs data
			$index++;
			$guruTimeInfo = getGuruNextItemInfo($ssWINDGURUdataObj,$index);
		}
		$windForecastItem['dateAndTime']=$guruTimeInfo['dayNumber'].'/01<br>'.$guruTimeInfo['forecastBy3Hours'].' HS';
		$windForecastItem['windDirDegr']=$guruTimeInfo['windDirection'];
		$windDirectionInfo = degToCompass($guruTimeInfo['windDirection']);
		$windForecastItem['windDirTxt']=$windDirectionInfo['windLongCode'].'<br>('.$windDirectionInfo['windShortCode'].')';
		$windForecastItem['windSpeed']=$guruTimeInfo['windSpeed'].'-'.$guruTimeInfo['gustSpeed'];
		$guruTimeInfo['airTemp'] = str_replace(",",".",$guruTimeInfo['airTemp']);
		$windForecastItem['temp']=($guruTimeInfo['airTemp']>0) ? "+".round($guruTimeInfo['airTemp']) : round($guruTimeInfo['airTemp']);//'+22.8';
		$index++;
	}

	$index = 0;
	foreach ($ssSMNdataObj as $key => $smnDayInfo){
		if (empty($smnDayInfo)  ||  empty($smnDayInfo['dayweekName'])  ||  $smnDayInfo['dayweekName']=="NULL" ||  empty($smnDayInfo['maxTemp'])){
			continue;
		}
		$data['forecastByDay'][$index]['dayName'] = mb_strtolower($smnDayInfo['dayweekName']);
		$data['forecastByDay'][$index]['dayweekNumber'] = $smnDayInfo['dayweekNumber'];
		$data['forecastByDay'][$index]['dayIcon'] = getWeatherIcon($smnDayInfo['iconoTardeNoche']);
		$data['forecastByDay'][$index]['dayMaxTemp'] = ($smnDayInfo['maxTemp']>0) ? "+".round($smnDayInfo['maxTemp']) : round($smnDayInfo['maxTemp']);
		$data['forecastByDay'][$index]['dayMinTemp'] = ($smnDayInfo['minTemp']>0) ? "+".round($smnDayInfo['minTemp']) : round($smnDayInfo['minTemp']);

		$dow_number = date('w', strtotime("Sunday +{$smnDayInfo['dayweekNumber']} days"));

		$index++;
	}
	while ( $index < sizeof($data['forecastByDay']) ) {//fill empty days with random data
		if ($data['forecastByDay'][$index-1]['dayweekNumber']==6){
			$data['forecastByDay'][$index]['dayName']=mb_strtolower($dayWeekNames[0]);
			$data['forecastByDay'][$index]['dayweekNumber']=0;
		}else{
			$newDayWeekNum = $data['forecastByDay'][$index-1]['dayweekNumber']+1;
			$data['forecastByDay'][$index]['dayName']=mb_strtolower($dayWeekNames[$newDayWeekNum]);
			$data['forecastByDay'][$index]['dayweekNumber']=$newDayWeekNum;
		}
		$virtualDay['maxTemp'] = $data['forecastByDay'][$index-1]['dayMaxTemp'] + rand(0,1);
		$virtualDay['minTemp'] = $data['forecastByDay'][$index-1]['dayMinTemp'] + rand(-1,0);
		$data['forecastByDay'][$index]['dayMaxTemp'] = ($virtualDay['maxTemp']>0) ? "+".round($virtualDay['maxTemp']) : round($virtualDay['maxTemp']);
		$data['forecastByDay'][$index]['dayMinTemp'] = ($virtualDay['minTemp']>0) ? "+".round($virtualDay['minTemp']) : round($virtualDay['minTemp']);
		$data['forecastByDay'][$index]['dayIcon'] = getWeatherIcon(null);

		$index++;
	}

	$data['currentWeatherIcon'] = getWeatherIcon(null,true);
	$data['currentTemp']= ($ssTELPINdataObj['currentweather']['temperature']>0) ? "+".round($ssTELPINdataObj['currentweather']['temperature']) : round($ssTELPINdataObj['currentweather']['temperature']);//'+25'
	$data['currentRealFeel']= (($ssTELPINdataObj['currentweather']['realfeel']>0) ? "+".$ssTELPINdataObj['currentweather']['realfeel'] : $ssTELPINdataObj['currentweather']['realfeel']).' Sensación';
	$data['currentRainAmountValue']= $ssTELPINdataObj['currentweather']['fallenRain'];
	$data['currentHumidityAmountValue']= $ssTELPINdataObj['currentweather']['humedity'];
	$data['currentWeatherText']= $ssSMNdataObj['alertavigente']['weatherStatus'];
	if (!empty($ssTELPINdataObj['currentweather']['windSpeed']) &&  ((int)$ssTELPINdataObj['currentweather']['windSpeed']>0) ){
		$data['currentWindAmountValue']= $ssTELPINdataObj['currentweather']['windSpeed'];
	}else{
		if (!empty($ssSMNdataObj['alertavigente']['windSpeed']) &&  ((int)$ssSMNdataObj['alertavigente']['windSpeed']>0) ){
			$data['currentWindAmountValue']= $ssSMNdataObj['alertavigente']['windSpeed'];
		}
	}
	if (!empty($ssTELPINdataObj['currentweather']['windDirection']) &&  !(strpos($ssTELPINdataObj['currentweather']['windDirection'], '--') !== false) ){
		$data['currentWindDirection']= $ssTELPINdataObj['currentweather']['windDirection'];
	}else{
		if (!empty($ssSMNdataObj['alertavigente']['windDirection']) &&  !(strpos($ssSMNdataObj['alertavigente']['windDirection'], '--') !== false)  ){
			$data['currentWindDirection']= $ssSMNdataObj['alertavigente']['windDirection'];
		}
	}
	if (!empty($ssTELPINdataObj['currentweather']['pressure']) &&  ((int)$ssTELPINdataObj['currentweather']['pressure']>0) ){
		$data['currentAtmPressureValue']= $ssTELPINdataObj['currentweather']['pressure'];
	}else{
		if (!empty($ssSMNdataObj['alertavigente']['pressure']) &&  ((int)$ssSMNdataObj['alertavigente']['pressure']>0)  ){
			$data['currentAtmPressureValue']= $ssSMNdataObj['alertavigente']['pressure'];
		}
	}

	return $data;
}

function getGuruNextItemInfo($ssWINDGURUdataObj,$index){
	if ($index == 0){
		return $ssWINDGURUdataObj['day1'];
	}
	$i=1;
	foreach ($ssWINDGURUdataObj as $key => $guruTimeItemInfo){
		if ($i == $index){
			return $guruTimeItemInfo;
		}
		$i++;
	}
}

function getSMNNextItemInfo($ssSMNdataObj,$index){
	$i=0;
	foreach ($ssSMNdataObj as $key => $dayForecast){
		if ($i == $index){
			return $dayForecast;
		}
		$i++;
	}
}

function degToCompass($num) {
    //$val = floor(($num / 22.5) + 0.5);
	//$val = floor($num / 45);
	$val = round(($num / 45),0);
    //$arrShort = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
	$arrShort = array("N",     "NE",    "E",    "SE",    "S",    "SO",     "O",     "NO"	    );
	$arrLong = array("Norte","Noreste","Este","Sudeste","Sur","Sudoeste","Oeste","Noroeste"  );
    //return $arr[($val % 16)];
	$idOcteto=($val % 8);
	$out = array( "windShortCode" => $arrShort[$idOcteto], "windLongCode" => $arrLong[$idOcteto] );
	return $out;
}

function getWeatherIcon($iconUrlPath,$currentConditions=false){
	if ($currentConditions){ //default icons
		$out = "img/big_partly-cloudy-rain-icon.png";
	}else{
		$out = "img/small_partly-cloudy-rain-icon.png";
	}
	/*
	 gifs/header/iconos/chicos/tormenta.jpg

	 */
	//TODO
	return $out;
}

function getWindData(){

	$ssWINDGURUdataObj = getSpreadSheetDataIntoArray('https://spreadsheets.google.com/feeds/list/19-uxl3ziCXZ5QfSth3OG6NnuAnpfRvMesLQPaHXZ924/1/public/values?alt=json-in-script');

/*
	$index = 0;
	foreach($data['windForecastByHour'] as  &$windForecastItem){
		$guruTimeInfo = getGuruNextItemInfo($ssWINDGURUdataObj,$index);
		if ($guruTimeInfo['forecastBy3Hours'] == "3"){//skip 03hs data
			$index++;
			$guruTimeInfo = getGuruNextItemInfo($ssWINDGURUdataObj,$index);
		}
		$windForecastItem['dateAndTime']=$guruTimeInfo['dayNumber'].'/01<br>'.$guruTimeInfo['forecastBy3Hours'].' HS';
		$windForecastItem['windDirDegr']=$guruTimeInfo['windDirection'];
		$windDirectionInfo = degToCompass($guruTimeInfo['windDirection']);
		$windForecastItem['windDirTxt']=$windDirectionInfo['windLongCode'].'<br>('.$windDirectionInfo['windShortCode'].')';
		$windForecastItem['windSpeed']=$guruTimeInfo['windSpeed'].'-'.$guruTimeInfo['gustSpeed'];
		$guruTimeInfo['airTemp'] = str_replace(",",".",$guruTimeInfo['airTemp']);
		$windForecastItem['temp']=($guruTimeInfo['airTemp']>0) ? "+".round($guruTimeInfo['airTemp']) : round($guruTimeInfo['airTemp']);//'+22.8';
		$index++;
	}
*/
	$out = $ssWINDGURUdataObj;
	return $out;
}


?>
