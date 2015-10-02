<?php
	include('util.php');
	parse_str($_SERVER['QUERY_STRING'], $params);
	//$spreadSheetUrl = 'https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/1/public/values?alt=json-in-script';//Hoja 1: MUNI-RESUMEN
	$spreadSheetUrl = 'https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/3/public/values?alt=json-in-script';//Hoja 3: PROV-RESUMEN
	$ssDataObj = getSpreadSheetDataIntoArray($spreadSheetUrl);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
  	 <link rel="stylesheet" href="styles.css">
  	 <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
  	 <!-- <script type='text/javascript' src='scripts.js'></script> -->
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
                <p>JEFE DE GOBIERNO</p>
            </div>
            <div class="votationType">
                <p>PASO 2015</p>
            </div>
            <div class="examinedStationsContainer">
                <p>ESCRUTADO: <span id="examinedStations"><?php echo $ssDataObj['totalescrutado']['nombre_partido'] ?>%</span> | INFO: <?php date_default_timezone_set('America/Argentina/Buenos_Aires'); echo date('H:i:s', time()); ?>&nbsp;HS</p>
            </div>
        </div>
        <div class="centerContainer">
            <div class="pgmLogo">
                
            </div>
            <div id="candidateBox1" class="candidateInfoContainer">
                <div class="politicalGroupName">
                    <p><?php echo $ssDataObj['partido1']['nombre_partido']; ?></p>
                </div>
                <div class="groupChiefImage">
                    <img src="<?php echo img_data_uri($ssDataObj['partido1']['URL_imagen']); ?>" />  
                </div>
                <div class="groupChiefSurname">
                    <p><?php echo $ssDataObj['partido1']['apellido_lider'] ?></p>
                </div>
                <div class="groupPercentAmount">
                    <p><?php echo $ssDataObj['partido1']['porcentaje_parcial'] ?></p>
                </div>
            </div>
            <div id="candidateBox2" class="candidateInfoContainer">
                <div class="politicalGroupName">
                    <p><?php echo $ssDataObj['partido2']['nombre_partido'] ?></p>
                </div>
                <div class="groupChiefImage">
                    <img src="<?php echo img_data_uri($ssDataObj['partido2']['URL_imagen']); ?>" />  
                </div>
                <div class="groupChiefSurname">
                    <p><?php echo $ssDataObj['partido2']['apellido_lider'] ?></p>
                </div>
                <div class="groupPercentAmount">
                    <p><?php echo $ssDataObj['partido2']['porcentaje_parcial'] ?></p>
                </div>
            </div>
            <div id="candidateBox3" class="candidateInfoContainer">
                <div class="politicalGroupName">
                    <p><?php echo $ssDataObj['partido3']['nombre_partido'] ?></p>
                </div>
                <div class="groupChiefImage">
                    <img src="<?php echo img_data_uri($ssDataObj['partido3']['URL_imagen']); ?>" />  
                </div>
                <div class="groupChiefSurname">
                    <p><?php echo $ssDataObj['partido3']['apellido_lider'] ?></p>
                </div>
                <div class="groupPercentAmount">
                    <p><?php echo $ssDataObj['partido3']['porcentaje_parcial'] ?></p>
                </div>
            </div>
        </div>
        <div class="footerContainer">
        </div>
    <div>
	   <?php 
        if ($params['alaire']=='true'){
           $url = "http://31.220.50.30:37021/mediamsg/mediaMsgController.php?action=getanduploadurlimage&outfilename=voteStatsWidget";
           echo add_ajax_request_response_widget($url);
        }
		?>
</body>
</html>