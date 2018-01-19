<?php

	date_default_timezone_set('America/Argentina/Buenos_Aires');
	header('Content-Type: text/html; charset=utf-8');
	header("Access-Control-Allow-Origin: *");
	
	parse_str($_SERVER['QUERY_STRING'], $params);
	
	//get_fb_videos.php?&fbsources=infobae,teleshowcom&maxvideosbysource=10&datefrom=2018-01-16&dateto=2018-01-18&accesstoken=XXXXXXXXX
	
	
	$fbsources=$_GET['fbsources'];
	$maxvideosbysource=$_GET['maxvideosbysource'];
	$datefrom=$_GET['datefrom'];
	$dateto=$_GET['dateto'];
	$accesstoken=$_GET['accesstoken'];
	$out="";
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
		$urlTarget="https://graph.facebook.com/v2.11/";
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
		$urlTarget.="&fields=length%2Ctitle%2Cdescription%2Cpermalink_url%2Ccomments.limit(0).summary(1)%2Clikes.limit(0).summary(1)%2Creactions.limit(0).summary(1)%2Ccreated_time";
		$data = file_get_contents($urlTarget);
		$data = json_decode($data, true);
		if (!empty($data) && array_key_exists('data', $data) && !empty($data['data'])){
			foreach ($data['data'] as $item){
				$out.=$urlBase.$item['permalink_url']."\n";
			}
		}
	}
	echo $out;
	
?>
