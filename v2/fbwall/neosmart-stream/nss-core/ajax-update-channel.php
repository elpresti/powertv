<?php
	if(array_key_exists('channel',$_GET)){
		include "../setup.php";
		$update = $nss->updateChannel($_GET['channel']);
		echo '{"channel":'.$_GET['channel'].',"status":"'.$update.'"}';
	}
?>