<?php

	date_default_timezone_set('America/Argentina/Buenos_Aires');
	header('Content-Type: text/html; charset=utf-8');
	header("Access-Control-Allow-Origin: *");
	
	//$_SERVER['QUERY_STRING'] = "&fbsources=Pinamar24,powerpinamar&maxvideosbysource=10&datefrom=2020-10-22&dateto=2020-10-23&accesstoken=[VER_DOC_DATOS_PWR]";

	parse_str($_SERVER['QUERY_STRING'], $params);
	$fbsources= isset($_GET['fbsources']) ? $_GET['fbsources'] : $params['fbsources'];
	$maxvideosbysource = isset($_GET['maxvideosbysource']) ? $_GET['maxvideosbysource'] : $params['maxvideosbysource'];
	$datefrom = isset($_GET['datefrom']) ? $_GET['datefrom'] : $params['datefrom'];
	$dateto = isset($_GET['dateto']) ? $_GET['dateto'] : $params['dateto'];
	$accesstoken = isset($_GET['accesstoken']) ? $_GET['accesstoken'] : $params['accesstoken'];
	$out="";
	$videoUrlsArray = [];
	if (empty($fbsources)) {
		echo "ERROR--EMPTY_SOURCE_1";
		die();
	}else{
		$fbsources=explode(",",$fbsources);
		if (empty($fbsources) ||  sizeof($fbsources)<1 ){
			echo "ERROR--EMPTY_SOURCE_2";
			die();
		}
	}
	if (empty($accesstoken)){
		echo "ERROR--EMPTY_ACCESS_TOKEN";
		die();
	}
	if (empty($maxvideosbysource)){
		$maxvideosbysource=6;
	}
	$urlBase = "https://facebook.com";
	foreach($fbsources as $fbsource){
		//build target URL
		$urlTarget="https://graph.facebook.com/v7.0/";
		$urlTarget.=$fbsource;
		$urlTarget.="/videos?";
		if (!empty($datefrom)){
			$urlTarget.="&since=".$datefrom;
		}
		if (!empty($dateto)){
			$urlTarget.="&until=".$dateto;
		}
		if (!empty($maxvideosbysource)){
			$urlTarget.="&limit=".$maxvideosbysource;
		}
		$urlTarget.="&filter=stream";
		$urlTarget.="&access_token=".$accesstoken;
		//$urlTarget.="&fields=length%2Ctitle%2Cdescription%2Cpermalink_url%2Ccomments.limit(0).summary(1)%2Clikes.limit(0).summary(1)%2Creactions.limit(0).summary(1)%2Ccreated_time";
		$urlTarget.="&fields=length%2Ctitle%2Cdescription%2Cpermalink_url%2Ccomments.limit(0).summary(1)%2Clikes.limit(0).summary(1)%2Ccreated_time";
		$data = file_get_contents($urlTarget);
		$data = json_decode($data, true);
		if (!empty($data) && array_key_exists('data', $data) && !empty($data['data'])){
			foreach ($data['data'] as $item){
				if (!in_array($urlBase.$item['permalink_url'], $videoUrlsArray)) {
					$videoUrlsArray[] = $urlBase.$item['permalink_url'];
				}
			}
		}
	}
	$out = implode("\n", $videoUrlsArray);
	echo $out;
	
	
//$maxAmountOfVideos=30;
//$dateFrom="2018-01-14";
//$dateTo="2018-01-17";
//$access_token="CAAWN8apLaFgBACMT1UnNI9Fus4lWMTR8mhvuXH378gOUK7OlaHr3jabhA6PeT7B09iYqVm6DRnrSqz2v4W0AlCdsu1Ecwl9C1JFyoxOmMRYFYvVZCcZCTus8kgLbZAGBkPhWfpmwBKw6KZCqUPcgN4A2OJxPh5lfn2xIFKm8mGPKiLdMAMWVsNYOYdpBmswZD";
//$urlTarget="https://graph.facebook.com/v2.11/Pinamar24/videos?since=".$dateFrom."&until=".$dateTo."&filter=stream&limit=".$maxAmountOfVideos."&fields=length%2Ctitle%2Cdescription%2Cpermalink_url%2Ccomments.limit(0).summary(1)%2Clikes.limit(0).summary(1)%2Creactions.limit(0).summary(1)%2Ccreated_time&access_token=".$access_token;
//$data = file_get_contents($urlTarget);
//$data = json_decode($data, true);
//$out="";
//if (!empty($data) && array_key_exists('data', $data) && !empty($data['data'])){
//	$urlBase = "https://facebook.com";
//	foreach ($data['data'] as $item){
//		$out.=$urlBase.$item['permalink_url']."\n";
//	}
//}
//echo print_r($data,true);
//echo $out;
?>
