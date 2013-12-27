<?php
	$current_page = 'update';
	include "header.inc.php";
	
	$file = NSS_ABSPATH."nss-content/cache/latest_version.txt";
	$latest_version = @file_get_contents($file);
	$current_version = $nss->get('version');	
?>

<?php if($update_available){ ?>
	<h2>Update available</h2>
	<form class="nss-admin-form nss-update-form" method="post" onsubmit="return false;">
		<div class='row' data-step="0">
			<p>Your version <?php echo $current_version; ?> is outdated.</p>
		</div>
		<div class='row' data-step="0">
			<input id="install-latest" class='submit' type='submit' value='Install Update <?php echo $latest_version; ?>'>
		</div>
		<div class='row' data-step="1">
			<p class="loading">Download latest version ...</p>
		</div>
		<div class='row' data-step="2">
			<p class="loading">Installation is running ...</p>
		</div>
		<div class='row' data-step="3">
			<p class="loading">Installation successfull! Reload page ...</p>
		</div>
		<div class='row' data-step="4">
			<p>Download error - please try again, later.</p>
		</div>
	</form>
<?php } else { ?>
	<h2>Up-to-date!</h2>
	<div class="nss-admin-form saved">
		<div class='row'>
			<p class="lead"><b>You are using the latest version!</b></p>
			<p>Version: <?php echo $current_version; ?></p>
		</div>
	</div>
<?php } ?>

<?php
	include "footer.inc.php";
?>