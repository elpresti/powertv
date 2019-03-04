<?php
	header("Access-Control-Allow-Origin: *");
	//header("Access-Control-Allow-Origin: http://powerhd.com.ar");

	include("simple_html_dom.php"); //Basic HTML parsing with PHP	

	$outMsg="NO MESSAGE";
	$outStatusCode=500;
	$outData=null;
	
	function printResultInJson(){
		global $outMsg, $outStatusCode, $outData;
		$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
		$out = json_encode($arr);
		$out = str_replace("\\\\\\", "", $out);
		echo $out;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	function getForecastRawData($idSpot){		
//			$fullUrl="http://www.windguru.cz/es/index.php?sc=".$idSpot; //8158
			$fullUrl="https://www.windguru.cz/int/iapi.php?q=forecast_spot&id_spot=".$idSpot."&_mha=85ee0451";
//		    $htmlCode = file_get_html($fullUrl);
			$opts = array(
					'http'=>array(
							'header'=>"User-Agent:Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53\r\nReferer:https://www.windguru.cz/".$idSpot."\r\n"
					)
			);
			$context = stream_context_create($opts);
			$forecastSpotModelsData = file_get_html($fullUrl, false, $context);

			$dataObj = json_decode($forecastSpotModelsData);
			$dataItems = (array)$dataObj->{'tabs'};
			$conectionData=null;
			foreach ($dataItems as $dataItem){
				$weatherVarsOfModel = $dataItem->{'options'}->{'params'};
				if (in_array('WINDSPD',$weatherVarsOfModel)){
					$conectionData=$dataItem->{'id_model_arr'}[0];
					break;
				}
			}
			
			if (!is_null($conectionData)){
				$fullUrl = "https://www.windguru.net/int/iapi.php?q=forecast&id_model=".$conectionData->id_model."&initstr=".$conectionData->initstr."&id_spot=".$idSpot."&WGCACHEABLE=21600&cachefix=".$conectionData->cachefix."&_mha=d735afbd";
				$guruForecastData = file_get_html($fullUrl, false, $context);

				$dataObj = json_decode($guruForecastData);
				$dataItems = (array)$dataObj->{'fcst'};
				return $dataItems;
			}else{
				return false;
			}
			/*
			$data=null;
			$dataFound=false;
			$pos1=strpos($htmlCode,"wg_fcst_tab_data_1");
			if ($pos1>0){
				$pos2 = strpos($htmlCode,"{",$pos1);
				if ($pos2>0){
					$pos3 = strpos($htmlCode,";",$pos2);
					if ($pos3>0){
						$data=substr($htmlCode,$pos2,($pos3-$pos2) );
						$dataFound=true;
						//echo $data;die();
					}
				}
			}
			if ($dataFound){
				return $data;
			}else{
				return false;
			}*/
		    //return mb_convert_encoding($htmlCode, "iso-8859-1", "UTF-8");
	}
	
	function getRelevantData($dataItems){
		//$dataObj = json_decode($rawData);
		//$dataItems = (array)$dataObj->{'fcst'}->{'3'};
		$forecastData = array();
		$dayNum=1;
		$arrayItemId=0;
		while ($arrayItemId<sizeof($dataItems['hr_h'])){
			$dayInfo=array();
			$dayInfo['dayweekNumber'] = $dataItems['hr_weekday'][$arrayItemId];
			$dayInfo['dayweekName'] = null;
			$dayInfo['dayNumber'] = $dataItems['hr_d'][$arrayItemId];
			$forecastBy3Hours=array();
			while($arrayItemId<sizeof($dataItems['hr_h'])  &&  $dayInfo['dayNumber']==$dataItems['hr_d'][$arrayItemId]){
				$currentHourData = array(
					'windSpeed' => round(convertUnits($dataItems['WINDSPD'][$arrayItemId],'knots','kmph')),
					'gustSpeed' => round(convertUnits($dataItems['GUST'][$arrayItemId],'knots','kmph')),
					'windDirection' => $dataItems['WINDDIR'][$arrayItemId],
					'waveHeight' => $dataItems['HTSGW'][$arrayItemId],
					'wavePeriod' => $dataItems['PERPW'][$arrayItemId],
					'waveDirection' => $dataItems['DIRPW'][$arrayItemId],
					'airTemp' => $dataItems['TMPE'][$arrayItemId],
					'highClouds' => $dataItems['HCDC'][$arrayItemId],
					'midClouds' => $dataItems['MCDC'][$arrayItemId],
					'lowClouds' => $dataItems['LCDC'][$arrayItemId],
					'fallingRainBy3h' => $dataItems['APCP'][$arrayItemId],
					'relHumidity' => $dataItems['RH'][$arrayItemId],
					'seaLevelPressure' => $dataItems['SLP'][$arrayItemId],
					'freezingLevelHeight' => $dataItems['FLHGT'][$arrayItemId]
				);
				$forecastBy3Hours[$dataItems['hr_h'][$arrayItemId]] = $currentHourData;
				$arrayItemId++;
			}
			$dayInfo['forecastBy3Hours'] = $forecastBy3Hours;
			$forecastData['day'.$dayNum] = $dayInfo;
			$dayNum++;
		}
		return $forecastData;
		//echo json_encode($forecastData);die();
	}
	
	function convertUnits($value,$inputUnit,$outputUnit){
		if (empty($inputUnit)  ||  empty($outputUnit)){
			return $value;
		}
		$outputUnit=strtolower($outputUnit);
		switch (strtolower($inputUnit)) {
			case 'knots':
				if ($outputUnit=='kmph'){
					$value=$value*1.852;
				}
			break;
			case label2:
		}
		return $value;
	}
	
	$sc=$_GET['sc'];
	$sc = "8158";
	if (isset($sc)  &&  strlen($sc)>0  &&  is_numeric($sc) ) {
		$rawData=getForecastRawData($sc);
		if ($rawData){
			$outData = getRelevantData($rawData);
			if ($outData){
				$outStatusCode="200";
				$outMsg="DONE!";
			}else{
				$outMsg="getRelevantData() returned null";
			}
		}else{
			$outMsg='getForecastRawData() returned null';
		}
		printResultInJson();
	}

?>