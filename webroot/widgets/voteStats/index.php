<?php
	include('util.php');
	parse_str($_SERVER['QUERY_STRING'], $params);
	if ($params['provincia']=='true'){
     $spreadSheetUrl = 'https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/3/public/values?alt=json-in-script';//Hoja 3: PROV-RESUMEN
   }else{
     $spreadSheetUrl = 'https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/1/public/values?alt=json-in-script';//Hoja 1: MUNI-RESUMEN
   }
	if ($params['nacion']=='true'){
     $spreadSheetUrl = 'https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/4/public/values?alt=json-in-script';//Hoja 4: NACION-RESUMEN
   }
	if ( is_numeric($params['maxCandidatesToShow']) ){
     $maxCandidatesToShow = $params['maxCandidatesToShow'];
   }else{
     $maxCandidatesToShow = 4;
   }
	$ssDataObj = getSpreadSheetDataIntoArray($spreadSheetUrl);
	$ssDataObj = getOrderedCandidatesByWinner($ssDataObj);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
  	 <link rel="stylesheet" href="styles.css">
  	 <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
  	 <script type='text/javascript' src='scripts.js'></script>
</head>
<body>
  <?php
///$spreadsheetData = file_get_contents('https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/od6/public/values?alt=json-in-script');
//echo "spreadsheetData: <br>".$spreadsheetData;
///$spreadsheetData = substr($spreadsheetData, strpos($spreadsheetData, '{'));
///$spreadsheetData = substr($spreadsheetData, 0, -2);
//echo "spreadsheetData: <br>".$spreadsheetData;
///$ssDataObj = json_decode($spreadsheetData);
///$ssDataObj = $ssDataObj->{'feed'}->{'entry'};
//echo "var_dump(json_decode: <br>".var_dump($spreadsheetData2,true);
//echo '<pre>' . var_export($ssDataObj, true) . '</pre>';
///echo "var_dump:<br><pre> ".var_export($ssDataObj,true)." </pre>";
///echo "<br>partido1:<br>".var_export($ssDataObj[0]->{'gsx$partido1'},true);
///die();
//echo var_dump();
	?>
    <div class="mainContainer">
        <div class="headerContainer">
            <div class="votationName">
                <p><?php echo $ssDataObj['txttipoeleccion']['nombre_partido'] ?></p>
            </div>
            <div class="votationType">
                <p><?php echo $ssDataObj['txttituloeleccion']['nombre_partido'] ?></p>
            </div>
            <div class="examinedStationsContainer">
                <p>ESCRUTADO: <span id="examinedStations"><?php echo $ssDataObj['totalescrutado']['nombre_partido'] ?>%</span> | INFO: <?php date_default_timezone_set('America/Argentina/Buenos_Aires'); echo date('H:i', time()); ?>&nbsp;HS</p>
            </div>
        </div>
        <div class="centerContainer">
            <div class="pgmLogo">
                <img class="imgLogoPgm" src="http://radiopower.com.ar/seba/pwrLogoElecciones2015.png" border="0" width="190" height="110">
            </div>
            <?php
            	$i=0;
            	foreach($ssDataObj as $ssDataItem){
                 $i++;
                 if ($i>$maxCandidatesToShow){
                   break;
                 }
                 $htmlBox='
                 		<div id="candidateBox" class="candidateInfoContainer">
                        <div class="politicalGroupName">
                            <p>'.$ssDataItem['nombre_partido'].'</p>
                        </div>
                        <div class="groupChiefImage">
                            <img src="'.img_data_uri($ssDataItem['URL_imagen']).'" />  
                        </div>
                        <div class="groupChiefSurname">
                            <p>'.$ssDataItem['apellido_lider'].'</p>
                        </div>
                        <div class="groupPercentAmount">
                            <p>'.$ssDataItem['porcentaje_parcial'].'</p>
                        </div>
                     </div>
                 ';
                 echo $htmlBox;
               }
            ?>
            <div class="widgetMainTextContainer"><span class="widgetMainText">RESULTADOS<br>PARCIALES</span>
            </div>
        </div>
        <div class="footerContainer">
        </div>
    <div>
	   <?php 
        if (isset($params['alaire'])  &&  $params['alaire']=='true'){
           $baseUrl = "http://radiopower.com.ar/powerhd/webroot/widgets/voteStats/";
		   //$baseUrl = "http://radiopower.com.ar/powerhd/webroot/widgets/voteStats/";
           //$baseUrl = "http://runnerp12.codenvycorp.com:53658/webroot/widgets/voteStats/";
           $urlParam = urlencode($baseUrl."?provincia=true&maxCandidatesToShow=4");// URL of website to capture image
			  $crops = null;
			  $outfilenameParam = "voteStatsWidgetProvincia";
			  $scaleoutParam = null;
           $url = "http://201.219.68.21:5280/mediamsg/mediaMsgController.php?action=getanduploadurlimage&outfilename=".$outfilenameParam."&url=".$urlParam;
           echo add_ajax_request_response_widget($url);
        }
?>
</body>
</html>