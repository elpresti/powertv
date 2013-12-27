<?php
	//Setup global neosmart STREAM Object ($nss)
	include "../setup.php";
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Your Website</title>
	<?php
		//CSS
		$nss->streamCSS();
		
		//JavaScript
		$nss->includeFile('jquery.js');
		$nss->streamJS();
	?>
</head>
<body>
	<?php
		//HTML Output
		$nss->show();
	?>
</body>
</html>