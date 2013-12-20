<?php
	
	include "../setup.php";
	
	if(!$nss->is_logged_in($nss)){ die('error logged_out');}

	$channel = array_key_exists('channel',$_REQUEST) ? $_REQUEST['channel'] : '';
	$url = array_key_exists('url',$_REQUEST) ? $_REQUEST['url'] : '';
	$id = array_key_exists('id',$_REQUEST) ? $_REQUEST['id'] : '';
	$token = array_key_exists('token',$_REQUEST) ? $_REQUEST['token'] : '';
	$secret = array_key_exists('token',$_REQUEST) ? $_REQUEST['secret'] : '';
	$show_all = array_key_exists('show_all',$_REQUEST) && $_REQUEST['show_all']=='true';
	$limit = array_key_exists('limit',$_REQUEST) ? $_REQUEST['limit'] : 1;

	switch($channel){
		case 'facebook': /*****************************************************************************/
			$binding = $show_all ? 'feed' : 'posts';
			$graph = "https://graph.facebook.com/".$id."/".$binding."?limit=".$limit."&access_token=".$token;
			//echo $graph;
			$data = $nss->readData($graph);
			
			if($data == 'error') $nss->saveChannelTestToFileAndDie('facebook',$id,'<a href="'.$graph.'" target="_blank">API response</a>');
			$fbdata = @json_decode($data);
			if(isset($fbdata->{'error'})) $nss->saveChannelTestToFileAndDie('facebook',$id,'<a href="'.$graph.'" target="_blank">API response</a>');
			//Keine Einträge
			if(count($fbdata->data)===0) $nss->saveChannelTestToFileAndDie('facebook',$id,'No data - <a href="'.$graph.'" target="_blank">API response</a> - Try to increase limit!');
			$nss->saveChannelTestToFileAndDie('facebook',$id,'success');
			
		break;
		case 'twitter': /*****************************************************************************/
			$twitter = array(
				'id' 					=> $id,
				'limit'					=> $limit,
				'access_token' 			=> $token,
				'access_token_secret' 	=> $secret
			);
			echo $nss->readTwitterChannel($twitter);
			
		break;
		case 'nss': /*****************************************************************************/
			
			$nss_file = $nss->readData($url);
			$xml = new SimpleXMLElement($nss_file);
			$id = substr($url,strrpos($url,"/")+1);
			$id = urlencode($id);
			if(isset($xml[0]->item[0]->channel)){
				$nss->saveChannelTestToFileAndDie('nss',$id ,'success');
			}else{
				$nss->saveChannelTestToFileAndDie('nss',$id ,'error');
			}
			
		break;	
	}
	
?>