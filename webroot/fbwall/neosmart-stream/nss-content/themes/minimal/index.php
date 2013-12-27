<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>Preview</title>
	<?php 
		// Include neosmart STREAM to your PHP template
		include "../../../setup.php";
		
		// Include reset CSS (optional)
		$nss->includeFile('reset-css');
		
		// Include jQuery
		$nss->includeFile('jquery');
		
		// Include theme
		$nss->theme('minimal');
	?>
</head>
<body>
	<div id="nss-preview">
		<div id="nss-preview-container">
			<h1>minimal</h1>
			<div id="nss">
				<?php
					// Show stream
					$nss->show();
				?>
			</div>
		</div>
	</div>
</body>
</html>