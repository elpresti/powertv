<?php

function getSpreadSheetDataIntoArray($ssUrl){
  $ssDataArray = array();
  $spreadsheetData = file_get_contents($ssUrl);
  $spreadsheetData = substr($spreadsheetData, strpos($spreadsheetData, '{')); //remove no-json data
  $spreadsheetData = substr($spreadsheetData, 0, -2); //remove no-json data
  $ssDataObj = json_decode($spreadsheetData);
  $entries = $ssDataObj->{'feed'}->{'entry'};
  foreach($entries as $entry){
     $rowData=array();
     $i=1;
     foreach ($entry as $key => $value){
       if ($i>6){ //skip irrelevant data
         if ($i==7){
            $nombreParam = $value->{'$t'}; //save first column value to use as index
         }else{
         	$keyName = substr($key,strrpos($key, "$")+1);
            $ssDataArray[$keyName][$nombreParam]=$value->{'$t'};
         }
       }
       $i++;
     }
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

//$spreadsheetData = file_get_contents('https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/od6/public/values?alt=json-in-script');
//echo "spreadsheetData: <br>".$spreadsheetData;
//echo "spreadsheetData: <br>".$spreadsheetData;
//$ssDataObj = json_decode($spreadsheetData);
//$ssDataObj = $ssDataObj->{'feed'}->{'entry'};
//echo "var_dump(json_decode: <br>".var_dump($spreadsheetData2,true);
//echo '<pre>' . var_export($ssDataObj, true) . '</pre>';
//echo "var_dump:<br><pre> ".var_export($ssDataObj,true)." </pre>";
//echo "<br>partido1:<br>".var_export($ssDataObj[0]->{'gsx$partido1'},true);
//die();
//echo var_dump();

function add_ajax_request_response_widget($url){
  $htmlOut = '
  	 <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
  	 <script type="text/javascript" src="ajaxRequestJavaServlet.js"></script>
    <form id="myAjaxRequestForm">
          <fieldset>
              <legend>jQuery Ajax Form data Submit Request</legend>
                  <p>
                      <label for="countryCode">URL to do request:</label>
                      <input id="countryCode" type="text" name="countryCode" value="'.$url.'" />
                  </p>
                  <p>
                      <input id="myButton" type="button" value="Submit" />
                  </p>
          </fieldset>
      </form>
      <div id="anotherSection">
          <fieldset>
              <legend>Response from jQuery Ajax Request</legend>
                   <div id="ajaxResponse"></div>
          </fieldset>
      </div>';
   return $htmlOut;
}

?>