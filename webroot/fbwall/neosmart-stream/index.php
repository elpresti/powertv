<?php
	
	if(!isset($_SESSION)){session_start();}
	
/****************************************************************************
* Includes
*****************************************************************************/

	include 'setup.php';	
	
	$permissionError 	= $nss->testFilePermissions();
	$serverError 		= $nss->testServerSettings();	
	$nss_root 			= $nss->getBaseURL();

/****************************************************************************
* Globals
*****************************************************************************/
	
	//Password
	$admin_password = $nss->get('admin_password');
	
	//License
	$nss->isPro();
	$license_name = $nss->get('license_name');
	$license_owner = $nss->get('license_owner');
	$license_key = $nss->get('license_key');
	$license_sites_array = explode(',',$nss->get('license_sites'));
	$license_sites = preg_replace('/,/',',<br>',$nss->get('license_sites'));
	
	$license_code = $nss->get('license_code');
	$license_limit = intval($nss->get('license_limit'));
	$license_status = $nss->get('license_status');
	$current_site = $_SERVER['HTTP_HOST'];
	
	if($license_name=='') $license_name = '<span class="error">Missing license key</span>';
	if($license_status=='') $license_status = '<span class="error">Missing license key</span>';
	if($license_status=='valid'){
		if(strpos($license_sites,$current_site)===false){
			if(count($license_sites_array)<$license_limit){
				$license_status = '<span class="error">Activation is missing</span>';
			}else{
				$license_status = '<span class="error">Invalid</span> - You have activated the maximum limit of sites for this license key.<br>Please get a new license key or deactivate a site for this key.<br>You can do this within <a href="'.NSS_WEBSITE_URL.'/log-in/?redirect_to=my-account" target="_blank">Your Account</a>.';
			}
		}
	}

/****************************************************************************
* Action Handler
*****************************************************************************/

	$nss->dynAdminLogin();

	if(array_key_exists('action',$_POST)){
		switch($_POST['action'])
		{
			case 'update_base_url':
				$nss->saveBaseURL();
			break;
			case 'add_license_key':
				$status_license_key = $nss->addLicenseKey($_POST['license_key']);
			break;
			case 'login':
				$login_error = $nss->adminLogin();
			break;
		}
	}
	

/****************************************************************************
* Begin Template
*****************************************************************************/

?><!DOCTYPE HTML>
<html class="<?php if($nss->isPro()) echo("pro"); else echo("lite"); ?>">
<head>
	<meta charset="utf-8">
	<title>neosmart STREAM Admin</title>
	<link href='nss-admin/reset.css' type='text/css' rel='stylesheet' />
	<link href='nss-admin/style.css' type='text/css' rel='stylesheet' />
	<script type='text/javascript' src='nss-includes/jquery.js'></script>
	<script type='text/javascript' src='nss-admin/jquery.neosmart.stream.admin.js'></script>
	<script type="text/javascript">
		$(function(){
			$('#nss-admin').neosmartStreamAdmin();
		});
	</script>
</head>
<body>
	<div id="nss-admin">
		<div class="nss-admin-header">
			<div class="center">
				<h1><a href="<?php echo NSS_WEBSITE_URL; ?>" target="_blank"><img src="nss-admin/neosmart-stream-logo-<?php if($nss->isPro()) echo 'pro'; else echo 'lite'; ?>.png" alt="neosmart STREAM" width="323" height="41"></a></h1>					
			</div><!--/.center-->
		</div>
		
		<div class="center">
		
		<?php /*************************************************************************************************************/
			if(!empty($statusSaveBaseURL) || $nss->getNssRoot()===false){?>
				<h2>URL error</h2>
				<div class="nss-admin-container error">
					<div class="row">All files of neosmart STREAM must be within a folder <b>neosmart-stream</b></div>
					<div class="todo">Rename folder <b><?php echo $nss->getLastFolder(); ?></b> to <b>neosmart-stream</b></div>
				</div>
	
		<?php } elseif($serverError){ /***********************************************/ ?>
				<h2>Server error</h2>
				<div class="nss-admin-container error">
					<div class="row"><?php echo $serverError[0]; ?></div>
					<div class="todo"><?php echo $serverError[1]; ?></div>
				</div>
				
		<?php } elseif($permissionError){ /***********************************************/ ?>
				<h2>Permission error</h2>
				<?php foreach($permissionError as $pErr){ ?>
				<div class="nss-admin-container error">
					<div class="row"><?php echo $pErr[0]; ?></div>
					<div class="todo"><?php echo $pErr[1]; ?></div>
				</div>
				<?php } ?>
				
		<?php } elseif($nss->get('error')==2||$nss->get('error')==3){ /***********************************************/ ?>
			<h2>File error</h2>
			<div class="nss-admin-container error">
				<div class="row">neosmart STREAM core files were modified. One or more files are conflicted.</div>
				<div class="todo">Please <b>delete all files</b> of your conflicted installation and <a href="<?php echo NSS_WEBSITE_URL;?>downloads/" target="_blank">download</a> the latest version.</div>
			</div>
				
		<?php } elseif($nss_root!=$nss->getNssRoot() && $nss->is_default_password($admin_password)){ /***********************************************/ ?>
				<h2>URL conflict</h2>
				<div class="nss-admin-container error">
					<div class="row"><b><?php echo $nss_root; ?></b> and current url <br><b><?php echo $nss->getNssRoot(); ?></b> doesn't match</div>
					<div class="todo">
						<form method="post" style="float:left;">
							<input type="hidden" name="action" value="update_base_url">
							<input type="submit" class="submit" value="Update">
						</form>
						Click update to fix that issue
					</div>
				</div>				
				
		<?php } else{ /***********************************************/ ?>
			<h2>Installation successful</h2>
			<form class="nss-admin-form" method="post">
				<div class="row">
					<label>Software</label><div class="field-area"><span class="info"><?php echo $license_name; ?></span></div>
				</div>
				<div class="row">
					<label>Version</label><div class="field-area"><span class="info"><?php echo $nss->get('version'); ?></span></div>
				</div>
				<?php if($nss->isPro()): ?>
					<div class="row">
						<label>Licensee</label><div class="field-area"><span class="info"><?php echo $license_owner; ?></span></div>
					</div>	
				<?php endif; ?>
				<div class='row '>
					<label>Preview</label>
					<div class="field-area"><span class="info"><a href="<?php echo $nss->getNssRoot().NSS_CONTENT.'themes/'.$nss->get('theme').'/'; ?>" target="_blank">Preview</a></span></div>
				</div>
							
			</form>
			<h2>Login</h2>
			<?php if($nss_root!=$nss->getNssRoot()){ ?>
				<div class="nss-admin-container error">
					<div class="row"><strong>URL conflict detected</strong> - Login to fix that issue </div>
				</div>
			<?php } ?>
			<?php if(isset($login_error)){ ?>
				<div class="nss-admin-container error">
						<div class="row">This password is wrong!</div>
					</div>
			<?php } ?>
			<?php if(isset($_GET['error']) && $_GET['error']=='1'){ ?>
				<div class="nss-admin-container error">
						<div class="row">You have to login.</div>
					</div>
			<?php } ?>
			<?php if($nss->is_default_password($admin_password)){ ?>
				<div class="nss-admin-container warning">
					<div class="row"><b><a href="nss-admin/password.php">Set admin password</a></b> as soon as possible!</div>
				</div>
			<?php } ?>
			<?php if($nss->is_logged_in($nss)){ ?>
				<form class="nss-admin-form" method="post" action="nss-admin/">
					<div class='row'>
						<?php if($nss->get('plugin_mode')===false){ ?><a href="nss-admin/?logout=1" class="button">Log-out</a><?php } ?>
						<input class='submit' type='submit' value="Show admin area">
					</div>
				</form>
			<?php }elseif($nss->get('plugin_mode')){ ?>
				<div class="nss-admin-container warning">
					<div class="row"><b>Plugin Mode is enabled.</b></div>
					<div class="todo">Login to <a href="/wp-admin"><?php echo ucwords($nss->get('plugin_mode')); ?></a> to configure neosmart STREAM.</div>
				</div>
			<?php } else {?>
				<form class="nss-admin-form" method="post" action="index.php">
					<input type="hidden" name="action" value="login">
					<?php if(!$nss->is_default_password($admin_password)){ ?>
					<div class='row'>
						<label>Admin password</label><input name='admin_password' type='password' class='text' value=''>
					</div>
					<?php } ?>
					<div class='row'>
						<input class='submit' type='submit' value='Login'>
					</div>
	
				</form>
			<?php } ?>
			
		<?php /*************************************************************************************************************/
			} 
		?>
		<div class="footer">
			<div class="copyright">
				&copy; <?php echo date('Y'); ?> <a href="http://www.neosmart.de" target="_blank" title="neosmart GmbH - Webdesign &amp; Social Media">neosmart GmbH - Webdesign &amp; Social Media</a>
			</div>
			<a href="<?php echo NSS_WEBSITE_URL;?>docs/" target="_blank" title="neosmart STREAM - documentation">Documentation</a> | 
			<a href="<?php echo NSS_WEBSITE_URL;?>forums/" target="_blank" title="neosmart STREAM - forum">Forum</a>
		</div>
		</div><!--/.center-->
	</div><!--#nss-admin-->
</body>
</html>