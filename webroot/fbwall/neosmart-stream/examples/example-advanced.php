<?php
	//Setup global neosmart STREAM Object ($nss)
	include "neosmart-stream/setup.php";
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Your Website</title>
	<?php 				
		//CSS - you can override theme config via parameter
		$nss->streamCSS('base');
	?>
</head>
<body>
	<div style="float:left;	width:50%;">
	<?php
		//HTML - set paramter 'all' to get all streams
		$nss->setGroup('all');
		$nss->show();
	?>
	</div>
	<div style="float:left;	width:50%;">
	<?php
		//HTML - you need to create a channel group "example" in your admin area
		$nss->setGroup('example');
		$nss->show();
	?>
	</div>
	<?php
		//JS
		$nss->includeFile('jquery.js');
		$nss->includeFile('jquery-migrate-dev.js');
		$nss->streamJS();
	?>
</body>
</html>