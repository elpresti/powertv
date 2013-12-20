<?php
	$current_page = 'themes';
	$fullsize_content = true;
	include "header.inc.php";
	
	//Themes
	$meta = $nss->getThemeMeta('Theme Name:',$theme);
	$theme_name = $meta ? $meta : $theme;
	
	
	$themes = scandir(NSS_CONTENT_THEMES);
	$gt = !empty($_GET['theme']) ? trim($_GET['theme']) : false;
	if($gt){
		foreach ($themes as $key => $value){
			if($value!='.'&&$value!='..'&&is_dir(NSS_CONTENT_THEMES.$value)&&$value==$gt){
				$theme = $value;
				$theme_name = $value;
				//Daten auslesen
				$meta = $nss->getThemeMeta('Theme Name:',$value);
				if($meta) $theme_name = $meta;
			}
		}
	}
	
	
	// Include theme
	$nss->streamCSS($theme);

	
?>
	<div class="theme-preview-menu">
		<div class="center">
			<h2>Theme preview: <?php echo $theme_name; ?></h2>
			<div class="theme-preview-actions">
				<a href="?theme=<?php echo trim($_GET['theme']); ?>" class="button">Refresh page</a>
				<a href="?delete=cache&theme=<?php echo trim($_GET['theme']); ?>" class="button">Clean cache</a>
				<a href="themes.php" class="button">All themes</a>
			</div>
		</div>
	</div>
	<div class="nss-preview">
		<?php
			// Show stream
			$nss->show();
			$nss->streamJS();
		?>
	</div>
	
<?php
	include "footer.inc.php";
?>