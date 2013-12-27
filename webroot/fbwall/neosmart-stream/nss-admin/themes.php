<?php
	$current_page = 'themes';
	include "header.inc.php";
	
	//Themes
	$themes = scandir('../nss-content/themes');
	
?>

	<h2>Themes</h2>
	<p class="intro">Just activate the theme you like or create a <a href="<?php echo NSS_WEBSITE_URL; ?>docs/themes/create-theme/" target="_blank">custom theme</a>.</p>
	<?php
		foreach ($themes as $key => $value){
			if($value!='.'&&$value!='..'&&is_dir(NSS_CONTENT_THEMES.$value)){
				
				$currentTheme 	= $value == $theme;
				$theme_name 	= $value;
				$theme_desc 	= '';
				$theme_author 	= '';
				$theme_version 	= '';
				
				//Daten auslesen
				$lines 			= explode("\n", implode('', file(NSS_CONTENT_THEMES.$value."/style.css")));
				foreach ($lines as $line) {
					$pos = strpos($line, 'Theme Name:');
					if($pos!==false) $theme_name = trim(substr($line,$pos+11));
					$pos = strpos($line, 'Description:');
					if($pos!==false) $theme_desc = trim(substr($line,$pos+12));
					$pos = strpos($line, 'Author:');
					if($pos!==false) $theme_author = trim(substr($line,$pos+7));
					$pos = strpos($line, 'Version:');
					if($pos!==false) $theme_version = trim(substr($line,$pos+8));
					$pos = strpos($line, 'Plugins:');
					if($pos!==false) $theme_plugins = trim(substr($line,$pos+8));
				}
				
				
				if($currentTheme){ /****************************************************/ ?>
				
					<div class="theme-box current-theme">
						
						<a href="theme-preview.php?theme=<?php echo $value; ?>" class="theme-preview">
							<img src="../nss-content/themes/<?php echo $value; ?>/preview.jpg" width="240" height="240" alt="<?php echo $value; ?>" />
						</a>
						<h5><?php echo $theme_name; ?></h5>
						<?php if(!empty($theme_desc)) { ?><div class="theme-desc"><?php echo $theme_desc; ?></div><?php } ?>
						<?php if(!empty($theme_author)) { ?>
							<div class="theme-meta">
								<?php if(!empty($theme_version)) { ?><div class="theme-version">Version: <?php echo $theme_version; ?></div><?php } ?>
								By: <?php echo $theme_author; ?>								
							</div>
						<?php } ?>
						
						<div class="theme-actions">
							<a href="theme-preview.php?theme=<?php echo $value; ?>" class="theme-preview-link">Live Preview</a>
							<b>Current Theme</b> 
						</div>
					</div>
				
				<?php }else{ /****************************************************/ ?>
				
					<div  class="theme-box">
						<a href="theme-preview.php?theme=<?php echo $value; ?>" class="theme-preview">
							<img src="../nss-content/themes/<?php echo $value; ?>/preview.jpg" width="240" height="240" alt="<?php echo $value; ?>" />
						</a>
						<h5><?php echo $theme_name; ?></h5>
						<?php if(!empty($theme_desc)) { ?><div class="theme-desc"><?php echo $theme_desc; ?></div><?php } ?>
						<?php if(!empty($theme_author)) { ?>
							<div class="theme-meta">
								<?php if(!empty($theme_version)) { ?><div class="theme-version">Version: <?php echo $theme_version; ?></div><?php } ?>
								By: <?php echo $theme_author; ?>
							</div>
						<?php } ?>
						
						<div class="theme-actions">
							<a href="theme-preview.php?theme=<?php echo $value; ?>" class="theme-preview-link">Live Preview</a>
							<form id="activate-<?php echo $value; ?>" method="post" class="theme-activate" action="#activate-<?php echo $value; ?>">
								<input type="hidden" name="action" value="update_theme">
								<input type="hidden" name="theme" value="<?php echo $value; ?>" />
								<input class='submit' type='submit' value='Activate'>
							</form>
						</div>
					</div>
					
				<?php }
			}
		}
	?>
<?php
	include "footer.inc.php";
?>