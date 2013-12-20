<?php
	$hide_menu = true;
	$no_channel_warning = true;	
	include "header.inc.php";
?>	
	<h2>Facebook Access Token Creator</h2>
	<?php if(array_key_exists('access_token',$_GET)){ ?>

	<form class="nss-admin-form">
		<div class="row">
			<label>Access Token</label>
			<div class="field-area">
				<div class="field-info">
					<span id="atc-access-token" class="info"><?php echo $_GET['access_token']; ?></span>
				</div>
			</div>
		</div>
		<div class="row">
			<label>Expires</label>
			<div class="field-area">
				<div class="field-info">
					<span id="atc-expires" class="info"><?php echo $_GET['expires']; ?></span>
				</div>
			</div>
		</div>
	</form>
	<?php } else { ?>
	<form class="nss-admin-form" action="https://api.neosmart-stream.com/index.php" method="post">
		<input type="hidden" name="return_url" value="<?php echo $nss->getNssRoot().'nss-admin/atc.php'?>">
		<input type="hidden" name="key" value="<?php echo $license_key; ?>">
		<input type="hidden" name="site" value="<?php echo $current_site; ?>">
		<input type="hidden" name="action" value="create_access_token">
		<div class="row">
			<input type="submit" class="submit" value="Create Access Token">
		</div>
	</form>
	
	<?php } ?>
<?php
	include "footer.inc.php";
?>