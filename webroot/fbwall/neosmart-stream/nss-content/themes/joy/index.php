<?php 
	// Include neosmart STREAM to your PHP template
	include "../../../setup.php";
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Preview</title>
	<?php 		
		// Include reset CSS (optional)
		$nss->includeFile('reset.css');
		
		// Include jQuery
		$nss->includeFile('jquery.js');
		
		// Include theme
		$nss->theme('joy');
	?>
</head>
<body>
	<div id="nss-preview">
		<?php
			// Show stream
			$nss->show();
		?>
	</div>
</body>
</html>