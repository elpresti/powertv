<?php
	include "../setup.php";
	$theme = empty($_REQUEST['theme']) ? false : $_REQUEST['theme'];
	$group = empty($_REQUEST['channel_group']) ? false : $_REQUEST['channel_group'];
	$update = $nss->updateRequired($theme,$group) ? 'true' : 'false';
	echo '{"cache_time":'.$nss->get('cache_time').',"channel_count":'.$nss->get('channel_count').',"update":"'.$update.'"}';
?>