<?php
	include "../setup.php";
	$theme = empty($_REQUEST['theme']) ? false : $_REQUEST['theme'];
	$group = empty($_REQUEST['channel_group']) ? false : $_REQUEST['channel_group'];
	echo $nss->mergeChannels($theme,$group);
?>