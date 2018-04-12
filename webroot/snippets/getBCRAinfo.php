<?php
	header('Content-Type: text/html; charset=utf-8');
	
	header("Access-Control-Allow-Origin: *");
	//header("Access-Control-Allow-Origin: http://powerhd.com.ar");
	
	include("simple_html_dom.php"); //Basic HTML parsing with PHP	

	date_default_timezone_set('America/Argentina/Buenos_Aires');

// >>>>> EXAMPLE OF USE: getBCRAinfo.php?action=getvarvalues&dateFrom=20180119&dateTo=20180129&varToGet=CER
	
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

	
	function getVarOfPeriod($varToGet,$dateFrom,$dateTo){
		//dateFrom and dateTo input format: YYYYMMDD
		//dateFrom and dateTo required format: DD/MM/YYYY
		//$dateFromFormated = date("d/m/Y",$
		$dateFromFormated = DateTime::createFromFormat('Ymd',$dateFrom);
		$dateFromFormated = $dateFromFormated->format('d/m/Y');
		$dateToFormated = DateTime::createFromFormat('Ymd',$dateTo);
		$dateToFormated = $dateToFormated->format('d/m/Y');
		//echo " <br> dateTo=".$dateTo." <br> dateToFormated=".$dateToFormated." <br> dateFrom=".$dateFrom." <br> dateFromFormated=".$dateFromFormated." <br> ";
		$params = "?desde=".$dateFromFormated."&hasta=".$dateToFormated."&primeravez=1&alerta=5";
		if ($varToGet == "CER"){
			$params .="&fecha=Fecha_Cer&campo=Cer";
		}else{
			if ($varToGet == "UVA"){
				$params .="&fecha=Fecha_Cvs&campo=Cvs";
			}else{
				if ($varToGet == "USDARS"){
					$params .="&fecha=Fecha_Ref&campo=Tip_Camb_Ref";
				}
			}
		}
		//$fullUrlEncoded="http://www.bcra.gov.ar/PublicacionesEstadisticas/Principales_variables_datos.asp".urlencode($params);
		$fullUrlNotEncoded="http://www.bcra.gov.ar/PublicacionesEstadisticas/Principales_variables_datos.asp".$params;
		//echo " <br> fullUrlEncoded=".$fullUrlEncoded." <br> fullUrlNotEncoded=".$fullUrlNotEncoded." <br> ";

		$htmlCode = file_get_html($fullUrlNotEncoded);
		$htmlMainTable = $htmlCode->find('table[id=tabla]', 0);
		$tableValues = array();
		$i=0;
		foreach($htmlMainTable->find('td') as $element) {
			$tableValues[$i] = $element->plaintext;
			$i++;
		}
		$tableValues = array_reverse($tableValues);
		$i=0;
		$out = array();
		while ($i < sizeof($tableValues)){
			$out[$tableValues[$i+1]] = $tableValues[$i];
			$i=$i+2;
		}
		//printHtmlRelevantData($htmlCode);die();
		
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
	}
	
	$action=$_GET['action'];
	$dateFrom=$_GET['dateFrom'];
	$dateTo=$_GET['dateTo'];
	$varToGet = $_GET['varToGet'];
	
	if ($action=='getvarvalues'  &&  !empty($dateFrom)  &&  !empty($dateTo) ) {
		$outData = getVarOfPeriod($varToGet,$dateFrom,$dateTo);
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
			$outMsg='getVarOfPeriod() returned null';
		}
		//echo json_encode($outData);die();
		printResultInJson();
	}
	

?>
