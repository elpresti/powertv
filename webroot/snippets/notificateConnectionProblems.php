<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Origin: http://powerhd.com.ar");
 

$emailsSent=false;
$csvLog=false;

function emailSendAllowed(){
  //pending, checks last time an email was sent and makes a datediff to ensure that time elapsed is greater than a value
  return true;
}

function getCommonSolutions($connectingErrorMsg){
   $connectingErrorMsg=strtolower($connectingErrorMsg);
   if (strpos($connectingErrorMsg, "id not found on server") !== false){
     $soluciones[0]="Telpin OK. Audicom NO.";
     $soluciones[1]="Verificar en nuestra web que el problema persiste y, en dicho caso, probar reiniciando el streamer de Audicom Video con el botón previsto.";
   }else{
     if (strpos($connectingErrorMsg, "error loading stream: could not connect to server") !== false){
       $soluciones[0]="Servidor de Telpin CAIDO. Todos sus streams tambien deberían estarlo";
       $soluciones[1]="Verificar que el problema persiste y en dicho caso enviar un email a Pali o quien corresponda de la cooperativa.";
     }else{
         $soluciones[0]="Problema desconocido. Solución desconocida";
     }
   }
   return $soluciones;
}


function logToCsv($archivo,$Date,$errorMsg){
  $texto="$Date,$errorMsg\n";
  $archivo=fopen($archivo.".csv",'a');
  $registros=count(file($archivo.".csv"));
  if ($registros==0){
    fwrite($archivo,"Date,errorMsg\n");
  }
  fwrite($archivo,$texto);
  fclose($archivo);
}

function sendEmail($subject,$emailTo,$from,$senderEmail,$connectingErrorMsg) {
	global $emailsSent;
	$cabeceras = "Content-type: text/html;\n";
	$cabeceras .= "From: ". $from ." <". $senderEmail .">\n";
	$cabeceras .= "MIME-Version: 1.0\n";
	$codigohtml = "Siendo las <strong>".date("H:i:s Y-m-d")."</strong>";
	$codigohtml.= " se notifica que este usuario no ha podido establecer conexión con el servidor de PowerHD, ";
	$codigohtml.= "y el reproductor del sitio arrojó el siguiente mensaje:<br><u>".$connectingErrorMsg."</u>\n <br> <br>";
	$codigohtml.= "<strong>Lista de acciones sugeridas para solucionar el inconveniente:<br>";
	$sugerencias = getCommonSolutions($connectingErrorMsg);
	foreach ($sugerencias as $sugerencia){
	   $codigohtml.="- ".$sugerencia."<br>";
	}
	$codigohtml.= "<br>&nbsp;<br>Este es un mensaje de notificación enviado desde powerhd.com.ar y disparado por un usuario que estaba mirando en vivo y en un momento perdió conexión, intentó reconectar y no lo logró.<br><br>";
	$emailsSent=mail($emailTo,$subject,$codigohtml,$cabeceras);
}


$connectingErrorMsg = (isset($_GET['connectingErrorMsg']))  ?  $connectingErrorMsg = $_GET['connectingErrorMsg']  :  $connectingErrorMsg = " ";
$defaultConnectErrorMsg=(isset($_GET['defaultConnectErrorMsg']))  ?  $defaultConnectErrorMsg = $_GET['defaultConnectErrorMsg']  :  $defaultConnectErrorMsg = " ";

$subject = "PowerTV no está funcionando";
$from   = "PowerHD Web App";
$senderEmail = "info@radiopower.com.ar";

if ( (strlen($connectingErrorMsg) > 3) && (strcasecmp($connectingErrorMsg,$defaultConnectErrorMsg) != 0) ){
	$destinationMailList= array();
	array_push($destinationMailList, "elpresti@gmail.com");
	//array_push($destinationEmailList, "sprestifilippo@gmail.com");
	if (emailSendAllowed()){
	  foreach ($destinationMailList as &$receiver) {
		 sendEmail($subject,$receiver,$from,$senderEmail,$connectingErrorMsg);
	  }
	  //logToCsv($archivo,$Date,$errorMsg);
	  //$csvLog=true;
	}else{
	  //logToCsv($archivo,$Date,$errorMsg);
	  //$csvLog=true;
	}
}

$emailsSentOut = ($emailsSent) ? 'true' : 'false';
$csvLogOut = ($csvLog) ? 'true' : 'false';

echo '{"emailsSent" : "'.$emailsSentOut.'", "csvLog" : "'.$csvLogOut.'"}';


?>