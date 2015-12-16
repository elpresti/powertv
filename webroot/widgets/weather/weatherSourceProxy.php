<?php

	/**********************************************************************************
	
		INITIALIZATION

	**********************************************************************************/

	$lastAPIqueryTimestampFileName="tmp/lastAPIqueryTimestamp.dat";
	$lastAPIqueryContentFileName="tmp/lastAPIqueryContent.json";
	// catch source number
	// catch update interval or use default


	// Control of Reference Access
	header("Access-Control-Allow-Origin: *"); //allow any domain
	//header("Access-Control-Allow-Origin: http://mydomain.com");
	



	/**********************************************************************************
	
		FUNCTIONS

	**********************************************************************************/


	function getContentFromFile($fileName){
		$out=0;
		if (file_exists($fileName)) {
			$fh = fopen($fileName,'r');
			while ($line = fgets($fh)) {
				$out=$line;
			}
			fclose($fh);
		}
		return $out;
	}
	
	function saveContentToFile($content,$fileName){
	  $txt="$content";
	  $file=fopen($fileName,'w');
	  $records=count(file($fileName));
	  fwrite($file,$txt);
	  return fclose($file);
	}
	
	function timeElapsedEnough2makeNewApiCall(){
		global $lastAPIqueryTimestampFileName;
		$makeNewCall=false;
		$elapsedTime=0;
		$lastAPIqueryTimestamp = getContentFromFile($lastAPIqueryTimestampFileName);
		if ( $lastAPIqueryTimestamp == 0 ) {
			$makeNewCall=true;
		}else{
			$elapsedTime = abs(time() - $lastAPIqueryTimestamp);
			if ( $elapsedTime >  (60 * 60 * 6) /* (6 hours) */ ){
				$makeNewCall=true;
			}
		}
		//echo "<br>Now timestamp: ". time() . "<br>Last API read timestamp: " . $lastAPIqueryTimestamp . "<br>Elapsed Timestamp since last request: " . $elapsedTime . "<br>shouldImakeAnewApiCall(): " . ($makeNewCall ? 'true' : 'false');
		return $makeNewCall;
	}
	
	
	function getLatestCurrencies($productFilter,$language){
		global $lastAPIqueryTimestampFileName,$lastAPIqueryContentFileName;
		$currencies = null;
		if ($productFilter != null  && strlen($productFilter)>0 ){
			global $currencies4doppler;
		}else{
			$productFilter=null; //if no argument was specified, retrieve ALL currencies
		}
		// check if (now-lastAPIqueryTime)>Xhs OR currenciesNeverRead, then do a new API request or uses the cached data
		$jsonOut = getContentFromFile($lastAPIqueryContentFileName);
		if ( timeElapsedEnough2makeNewApiCall() ||  ($jsonOut === 0) ){
			require_once('lib/JSON.php');
			$json = new Services_JSON(); //if PHP Version is >=5.2, it's no necessary to use this Library

			//read JSON data from the API and put each output in an array
			$jsonOfCurrenciesNames = file_get_contents('https://openexchangerates.org/api/currencies.json?app_id=e7a0366f7e2c4ec5be78f04c560c7774');
			$jsonOfCurrenciesRates = file_get_contents('http://openexchangerates.org/api/latest.json?app_id=e7a0366f7e2c4ec5be78f04c560c7774');
			$ratesObject = $json->decode($jsonOfCurrenciesRates);
			$namesObject = $json->decode($jsonOfCurrenciesNames);
			$rates = (array)$ratesObject->rates;
			$names = (array)$namesObject;

			//iterates the array to build and return only one array (in JSON) with all the information
			$currencies = array();
			reset($rates);
			while (list($keyName, $value) = each($rates)) {
				$currName = $names[$keyName];
				$symbol = getCurrencySymbol("$keyName");
				$currencies[$keyName] = array('currencyName' => "$currName",'rateValue' => "$value",'currencySymbol' => "$symbol");
			}
			$jsonOut = $json->encode($currencies);
			saveContentToFile($jsonOut,$lastAPIqueryContentFileName);
			saveContentToFile(time(),$lastAPIqueryTimestampFileName);
		}
		if ($productFilter != null  || $language != null){
			if ($currencies == null){
				require_once('lib/JSON.php');
				$json = new Services_JSON(); //if PHP Version is >=5.2, it's no necessary to use this Library
				$allCurrenciesObject = $json->decode($jsonOut);
				$outCurrencies = (array)$allCurrenciesObject;
			}else{
				$outCurrencies = $currencies;
			}
			if ( $productFilter != null ) {
				if ( (strtoupper($productFilter) == "DOPPLER") ){
					$outCurrencies = getOnlyRequestedCurrencies($outCurrencies,$currencies4doppler);
				}
			}
			if ( $language != null ) {
				$outCurrencies = getTranslatedCurrencyNames($outCurrencies,$language);
			}
			$jsonOut = $json->encode($outCurrencies);
		}
		return $jsonOut;
	}


	function getOnlyRequestedCurrencies($allCurrencies, $currencyCodesOfRequested){
		$currenciesOut = null;
		if (count($currencyCodesOfRequested) > 0){
			$necessaryCurrencies = array();
			foreach ($currencyCodesOfRequested as $code){
				if (is_object($allCurrencies[$code])){
					$rateValue = $allCurrencies[$code]->rateValue;
					$currencyName = $allCurrencies[$code]->currencyName;
					$symbol = $allCurrencies[$code]->currencySymbol;
				}else{
					$rateValue = $allCurrencies[$code]['rateValue'];
					$currencyName = $allCurrencies[$code]['currencyName'];
					$symbol = $allCurrencies[$code]['currencySymbol'];
				}
				$necessaryCurrencies[$code] = array('currencyName' => "$currencyName",'rateValue' => "$rateValue",'currencySymbol' => "$symbol");
			}
			$currenciesOut = $necessaryCurrencies;
		}
		return $currenciesOut;
	}


	function getTranslatedCurrencyNames($currencies,$language){
		global $currencyNameSpanish;
		if ( (strtoupper($language) == "ES") ){
			foreach ($currencies as $currencyCode => $currencyInfo){
				if ( array_key_exists("$currencyCode",$currencyNameSpanish) ) {
					if (is_object($currencies[$currencyCode])) {
						$currencies[$currencyCode]->currencyName = $currencyNameSpanish[$currencyCode];
					}else{
						$currencyInfo['currencyName'] = $currencyNameSpanish[$currencyCode];
						$currencies[$currencyCode] = $currencyInfo;
					}
				}
			}
		}
		return $currencies;
	}


	function getCurrencySymbol($currencyCode){
		global $currenciesSymbols;
		$symbolOut="$"; //default symbol
		$currencyCode=strtoupper($currencyCode);
		if (array_key_exists($currencyCode, $currenciesSymbols) ){
			$symbolOut=$currenciesSymbols[$currencyCode];
		}
		return $symbolOut;
	}

	/**********************************************************************************
	
		REQUEST CHECKS

	**********************************************************************************/

	if ( isset($_GET['getLatestCurrencies']) &&  (strtoupper($_GET['getLatestCurrencies']) == "TRUE") ){
		$productFilter=null;
		$language=null;
		if ( isset($_GET['productFilter']) &&  (strtoupper($_GET['productFilter']) == "DOPPLER") ){
			$productFilter="DOPPLER";
		}
		if ( isset($_GET['language']) &&  (strtoupper($_GET['language']) == "ES") ){
			$language="ES";
		}
		echo getLatestCurrencies($productFilter,$language);
	}
	//echo getLatestCurrencies(null,null);

?>
