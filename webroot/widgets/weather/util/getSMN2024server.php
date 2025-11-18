<?php
//https://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMN2024server.php?action=save
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header('Content-Type: application/json');

$action = $_GET['action'];
$getrawdata = $_GET['getrawdata'];
$smnHttpResponseFilePath = 'smnHttpResponseContent_received.json';

function printResultInJson($outMsg, $outStatusCode, $outData){
	//$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
	$arr = array('statusCode' => $outStatusCode, 'msg' => $outMsg, 'outData' => $outData); //probaremos sin json_encode
	$out = json_encode($arr);
	//$out = str_replace("\\\\\\", "", $out);
	echo $out;
	//echo json_encode($outData);
}

function getRelevantData($forecastData){
	$outData = [];
	//echo print_r($forecastData,true);die();
	foreach ($forecastData['forecast'] as $dayForecast) {
		$dateStrtotimeFriendlyFormat = date("Y-m-d", strtotime(str_replace('/', '-', $dayForecast['date'])));//dayForecast['date'] is in DD/MM/YYYY format, I need to change it
		setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'Spanish_Spain');// Force Locale config to Spanish
		$dayName = strftime("%A", strtotime($dateStrtotimeFriendlyFormat));// Get DayName in Spanish
		$dayWeekNumber = date("N", strtotime($dayForecast['date']));//get DayWeek number

		$early_morningIcon = null;
		$early_morningDescription = null;
		$early_morningRainProbRange = null;
		$early_morningWindDirection = null;
		$early_morningWindDeg = null;
		$early_morningSpeedRange = null;
		$early_morningRain06h = null;
		if (!empty($dayForecast['early_morning']) && array_key_exists('weather', $dayForecast['early_morning']) && !empty($dayForecast['early_morning']['weather'])) {
			$early_morningIcon = 'https://www.smn.gob.ar/sites/all/themes/smn/img/weather-icons/' . $dayForecast['early_morning']['weather']['id'] . '.png';
			$early_morningDescription = $dayForecast['early_morning']['weather']['description'];
			$early_morningRainProbRange = $dayForecast['early_morning']['rain_prob_range'][0] . "-" . $dayForecast['early_morning']['rain_prob_range'][1];
			$early_morningWindDirection = $dayForecast['early_morning']['wind']['direction'];
			$early_morningWindDeg = $dayForecast['early_morning']['wind']['deg'];
			$early_morningSpeedRange = $dayForecast['early_morning']['wind']['speed_range'][0] . "-" . $dayForecast['early_morning']['wind']['speed_range'][1];
			$early_morningRain06h = $dayForecast['early_morning']['rain06h'];
		}
		$morningIcon = null;
		$morningDescription = null;
		$morningRainProbRange = null;
		$morningWindDirection = null;
		$morningWindDeg = null;
		$morningSpeedRange = null;
		$morningRain06h = null;
		if (!empty($dayForecast['morning']) && array_key_exists('weather', $dayForecast['morning']) && !empty($dayForecast['morning']['weather'])) {
			$morningIcon = 'https://www.smn.gob.ar/sites/all/themes/smn/img/weather-icons/' . $dayForecast['morning']['weather']['id'] . '.png';
			$morningDescription = $dayForecast['morning']['weather']['description'];
			$morningRainProbRange = $dayForecast['morning']['rain_prob_range'][0] . "-" . $dayForecast['morning']['rain_prob_range'][1];
			$morningWindDirection = $dayForecast['morning']['wind']['direction'];
			$morningWindDeg = $dayForecast['morning']['wind']['deg'];
			$morningSpeedRange = $dayForecast['morning']['wind']['speed_range'][0] . "-" . $dayForecast['morning']['wind']['speed_range'][1];
			$morningRain06h = $dayForecast['morning']['rain06h'];
		}
		$afternoonIcon = null;
		$afternoonDescription = null;
		$afternoonRainProbRange = null;
		$afternoonWindDirection = null;
		$afternoonWindDeg = null;
		$afternoonSpeedRange = null;
		$afternoonRain06h = null;
		if (!empty($dayForecast['afternoon']) && array_key_exists('weather', $dayForecast['afternoon']) && !empty($dayForecast['afternoon']['weather'])) {
			$afternoonIcon = 'https://www.smn.gob.ar/sites/all/themes/smn/img/weather-icons/' . $dayForecast['afternoon']['weather']['id'] . '.png';
			$afternoonDescription = $dayForecast['afternoon']['weather']['description'];
			$afternoonRainProbRange = $dayForecast['afternoon']['rain_prob_range'][0] . "-" . $dayForecast['afternoon']['rain_prob_range'][1];
			$afternoonWindDirection = $dayForecast['afternoon']['wind']['direction'];
			$afternoonWindDeg = $dayForecast['afternoon']['wind']['deg'];
			$afternoonSpeedRange = $dayForecast['afternoon']['wind']['speed_range'][0] . "-" . $dayForecast['afternoon']['wind']['speed_range'][1];
			$afternoonRain06h = $dayForecast['afternoon']['rain06h'];
		}
		$nightIcon = null;
		$nightDescription = null;
		$nightRainProbRange = null;
		$nightWindDirection = null;
		$nightWindDeg = null;
		$nightSpeedRange = null;
		$nightRain06h = null;
		if (!empty($dayForecast['night']) && array_key_exists('weather', $dayForecast['night']) && !empty($dayForecast['night']['weather'])) {
			$nightIcon = 'https://www.smn.gob.ar/sites/all/themes/smn/img/weather-icons/' . $dayForecast['night']['weather']['id'] . '.png';
			$nightDescription = $dayForecast['night']['weather']['description'];
			$nightRainProbRange = $dayForecast['night']['rain_prob_range'][0] . "-" . $dayForecast['night']['rain_prob_range'][1];
			$nightWindDirection = $dayForecast['night']['wind']['direction'];
			$nightWindDeg = $dayForecast['night']['wind']['deg'];
			$nightSpeedRange = $dayForecast['night']['wind']['speed_range'][0] . "-" . $dayForecast['night']['wind']['speed_range'][1];
			$nightRain06h = $dayForecast['night']['rain06h'];
		}
		$relevantDayInfo = [
			'date' => $dayForecast['date'],
			'dayweekName' => $dayName,
			'dayweekNumber' => $dayWeekNumber,
			'minTemp' => $dayForecast['temp_min'],
			'maxTemp' => $dayForecast['temp_max'],
			'minHumidity' => $dayForecast['humidity_min'],
			'maxHumidity' => $dayForecast['humidity_max'],
			'earlyMorningIcon' => $early_morningIcon,
			'earlyMorningDescription' => $early_morningDescription,
			'earlyMorningRainProbRange' => $early_morningRainProbRange,
			'earlyMorningWindDirection' => $early_morningWindDirection,
			'earlyMorningWindDeg' => $early_morningWindDeg,
			'earlyMorningSpeedRange' => $early_morningSpeedRange,
			'earlyMorningRain06h' => $early_morningRain06h,
			'morningIcon' => $morningIcon,
			'morningDescription' => $morningDescription,
			'morningRainProbRange' => $morningRainProbRange,
			'morningWindDirection' => $morningWindDirection,
			'morningWindDeg' => $morningWindDeg,
			'morningSpeedRange' => $morningSpeedRange,
			'morningRain06h' => $morningRain06h,
			'afternoonIcon' => $afternoonIcon,
			'afternoonDescription' => $afternoonDescription,
			'afternoonRainProbRange' => $afternoonRainProbRange,
			'afternoonWindDirection' => $afternoonWindDirection,
			'afternoonWindDeg' => $afternoonWindDeg,
			'afternoonSpeedRange' => $afternoonSpeedRange,
			'afternoonRain06h' => $afternoonRain06h,
			'nightIcon' => $nightIcon,
			'nightDescription' => $nightDescription,
			'nightRainProbRange' => $nightRainProbRange,
			'nightWindDirection' => $nightWindDirection,
			'nightWindDeg' => $nightWindDeg,
			'nightSpeedRange' => $nightSpeedRange,
			'nightRain06h' => $nightRain06h
		];
		$outData[]= $relevantDayInfo;
	}
	return $outData;
}
	
if ($action == "save") {
	$jsonData = file_get_contents("php://input");

	// Verificar si se ha recibido contenido
	if (empty($jsonData)) {
		http_response_code(400);
		exit("Error: El contenido del JSON está vacío.");
	}

	// Verificar el tamaño del JSON
	if (strlen($jsonData) > 200 * 1024) { // 200 KB
		http_response_code(400);
		exit("Error: El tamaño del JSON excede el tamaño maximo permitido.");
	}

	// Verificar si ya existe un archivo y si fue modificado en los últimos 2 minutos
	if (file_exists($smnHttpResponseFilePath) && time() - filemtime($smnHttpResponseFilePath) < 120) {
		http_response_code(400);
		exit("Error: Ya se ha recibido un JSON similar en los últimos minutos.");
	}

	// Intentar guardar el contenido JSON en un archivo
	if (file_put_contents($smnHttpResponseFilePath, $jsonData) !== false) {
		echo "El archivo JSON fue guardado correctamente en: " . $smnHttpResponseFilePath;
	} else {
		http_response_code(500);
		echo "Error al intentar guardar el archivo JSON.";
	}
}

if ($action == "getrawdata") {
	$outMsg="NO MESSAGE";
	$outStatusCode=500;
	$outData=null;
	
	// Verificar si el archivo JSON existe y no está vacío
	if (file_exists($smnHttpResponseFilePath) && filesize($smnHttpResponseFilePath) > 0) {
		// Leer el contenido del archivo JSON
		$jsonData = file_get_contents($smnHttpResponseFilePath);

		// Devolver el contenido JSON como respuesta	
		if ($jsonData){
			$outStatusCode = "200";
			$outMsg = "DONE!";
			$outData = $jsonData;
		}else{
			$outMsg = 'JSON file data is empty';
		}
	} else {
		// Manejar el caso en el que el archivo no exista o esté vacío
		if (!file_exists($smnHttpResponseFilePath)) {
			$outMsg = "Error: El archivo JSON no existe.";
		} elseif (filesize($smnHttpResponseFilePath) === 0) {
			$outMsg = "Error: El archivo JSON está vacío.";
		}
	}
	printResultInJson($outMsg, $outStatusCode, $outData);
}

if ($action == "get") {
	$outMsg="NO MESSAGE";
	$outStatusCode=500;
	$outData=null;
	
	// Verificar si el archivo JSON existe y no está vacío
	if (file_exists($smnHttpResponseFilePath) && filesize($smnHttpResponseFilePath) > 0) {
		// Leer el contenido del archivo JSON
		$jsonData = file_get_contents($smnHttpResponseFilePath);
		$forecastDataArray = json_decode($jsonData, true);
		$relevantData = getRelevantData($forecastDataArray);

		// Devolver el contenido JSON como respuesta	
		if ($relevantData){
			$outStatusCode = "200";
			$outMsg = "DONE!";
			$outData = $relevantData;
		}else{
			$outMsg = 'JSON file data is empty';
		}
	} else {
		// Manejar el caso en el que el archivo no exista o esté vacío
		if (!file_exists($smnHttpResponseFilePath)) {
			$outMsg = "Error: El archivo JSON no existe.";
		} elseif (filesize($smnHttpResponseFilePath) === 0) {
			$outMsg = "Error: El archivo JSON está vacío.";
		}
	}
	printResultInJson($outMsg, $outStatusCode, $outData);
}
