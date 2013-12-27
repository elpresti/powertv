<?php
	$current_page = 'license';
	include "header.inc.php";

?>
	<h2>Your license</h2>	
	
	<form class="nss-admin-form" method="post">
		<div class="row">
			<label>License</label><span class="info"><strong><?php echo $license_name; ?></strong></span>
		</div>
		<?php if($nss->isPro()): ?>
		<div class="row">
			<label>Licensee</label><span class="info"><?php echo $license_owner; ?></span>
		</div>
		<div class="row">
			<label>License key</label><span class="info"><?php echo $license_key; ?></span>
		</div>
		<?php endif; ?>
		<div class="row">
			<label>Copyright</label><span class="info">&copy; <?php echo date('Y'); ?> neosmart GmbH</span>
		</div>
		<div class="row">
			<label>Legal</label><span class="info"><a href="../license.pdf">Licensing agreement</a></strong></span>
		</div>
	</form>
	
	<h2>Pro Version</h2>
	<?php if($nss->isPro()){ /*****************************************************************************/ ?>
		<div class="nss-admin-form">
		
		
			<div class='row'>
				<label>Activated sites</label>
				<div class="field-area">
					<?php echo count($license_sites_array); ?> of <?php echo $nss->get('license_limit');?>
					<div class="field-info">Your license key is valid for <?php echo $nss->get('license_limit');?> sites.</div>
				</div>
			</div>
			<div class='row'>
				<table cellpadding="0" cellspacing="0" class="inline-table" width="100%">
					<tbody>
						<tr>
							<th>Activated sites</th>
							<th width="100">Deactivate</th>
						</tr>
						<?php 
							$siteIsActivated = false;
							foreach($license_sites_array as $site){
								$current = $_SERVER['HTTP_HOST']==$site ? 'current-site' : '';
								if($_SERVER['HTTP_HOST']==$site) $siteIsActivated = true;
						?>
							<tr>
								<td><span class="site <?php echo $current;?>"><i>http://</i><?php echo $site; ?></span></td>
								<td>
									<form method="post">
										<input type="hidden" name="action" value="deactivate_site" />
										<input type="hidden" name="site" value="<?php echo $site; ?>" />
										<input type="submit" class="button" value="Deactivate" />
									</form>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="row">
				<a href="?delete=key" class="button">Delete Key</a>
			</div> 
			<?php if(!$siteIsActivated){ //Deprecated seit 1.6. / Anweisung wird nicht mehr geöffnet ?>
				<div class="row">
					<span id="nss-new-group" class="submit" data-id="new">Activate this site</span>
				</div>
			<?php } ?>
			
		</div>
	<?php }else { /*****************************************************************************/ ?>
		<?php if(isset($status_license_key)){ ?>
			<div class="nss-admin-container error">
				<div class="row"><?php echo $status_license_key; ?></div>
			</div>
		<?php }elseif(!$nss->get('license_owner')){ ?>
			<div class="nss-admin-container warning">
				<div class="todo"><a href="https://neosmart-stream.de/get-a-license/" target="_blank"><b>Update to Pro Version</b></a> to use all features of neosmart STREAM.</div>
			</div>
		<?php } ?>
		<form class="nss-admin-form" method="post">
			<input type="hidden" name="action" value="add_license_key">
			
			<?php if($nss->isPro()){ ?>
				<div class='row'>
					<label>Activated sites</label>
					<div class="field-area">
						<?php echo count($license_sites_array); ?> of <?php echo $nss->get('license_limit');?>
						<div class="field-info">Your license key is valid for <?php echo $nss->get('license_limit');?> sites.</div>
					</div>
				</div>
				
			<?php } ?>
			<div class='row'>
				<label>Pro license</label>
				<div class="field-area">
					<?php if($nss->get('license_owner')){ ?>
						<input name='license_key' type='hidden' value='<?php echo $nss->get('license_key'); ?>'>
						<input type='text' class='text' value='<?php echo $nss->get('license_key'); ?>' disabled="disabled" required="required">
					<?php } else { ?>
						<input name='license_key' type='text' class='text' value='<?php if(isset($_POST['license_key'])) echo $_POST['license_key']; ?>' placeholder="Enter your pro license key" required="required">
					<?php } ?>
					<div class="field-info">
						Unleash the power of neosmart STREAM Pro. <a href="<?php echo NSS_WEBSITE_URL;?>get-a-license/" target="_blank">Get a pro license!</a>
					</div>
				</div>
			</div>
			
			<div class='row'>
				<?php if($nss->isPro()){ ?><a href="?delete=key" class="button">Delete Key</a><?php } ?>
				<input class='submit' type='submit' value='Activate pro license'>
			</div>
			
		</form>
	<?php } /*****************************************************************************/ ?>
	
<?php
	include "footer.inc.php";
?>