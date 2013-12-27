<?php
	if(!isset($_SESSION)){session_start();}
	
/****************************************************************************
* Includes
*****************************************************************************/

	include '../setup.php';	

	$permissionError 	= $nss->testFilePermissions();
	$serverError 		= $nss->testServerSettings();
	
/****************************************************************************
* Login/Logout
*****************************************************************************/
	
	$nss->dynAdminLogin();
	
	if(array_key_exists('logout',$_REQUEST)){
		$_SESSION['nss_admin_password'] = false;
	}
	if(!$nss->is_logged_in($nss)){
		header('Location: '.$nss->getNssRoot().'?error=1');
		die;
	}
	$nss->cl();
	$nss->isPro();
	
/****************************************************************************
* Global Vars
*****************************************************************************/
	
	//Password
	$admin_password = $nss->get('admin_password');
	
	//Abspath
	$protocol = array_key_exists('HTTPS',$_SERVER) ? "https://" : "http://";
	$absolute_path = substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/nss-admin/')+1);
	
	//Config
	$nss_root = $nss->getBaseURL();
	$cache_time = $nss->get('cache_time');
	$cache_auto_refresh_time = $nss->get('cache_auto_refresh_time');
	$debug_mode = $nss->get('debug_mode');
	$facebook_feedback = $nss->get('facebook_feedback');
	$date_time_format = $nss->get('date_time_format');
	$theme = $nss->get('theme');
	$error_no_data = $nss->get('error_no_data');
	$channel_count = $nss->get('channel_count');
	
	//Update available?
	$update_available = $nss->is_update_available();
	
	//License
	$license_name = $nss->get('license_name');
	$license_owner = $nss->get('license_owner');
	$license_key = $nss->get('license_key');
	$license_sites_array = explode(',',$nss->get('license_sites'));
	asort($license_sites_array);
	$license_sites = preg_replace('/,/',',<br>',$nss->get('license_sites'));
	
	$license_code = $nss->get('license_code');
	$license_limit = intval($nss->get('license_limit'));
	$license_status = $nss->get('license_status');
	$current_site = $_SERVER['HTTP_HOST'];
	
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
* Actions
*****************************************************************************/

	if(array_key_exists('action',$_POST)){
		switch($_POST['action']){
			case 'update_base_url':
				$nss->saveBaseURL('../');
			break;
			case 'update_config':
				$nss->updateConfig();
			break;
			case 'update_proxy':
				$nss->updateProxy();
			break;
			case 'update_feedback':
				$nss->updateFeedback();
			break;
			case 'update_password':
				$passwordError = $nss->updatePassword($_POST['admin_password']);
			break;
			case 'update_translation':
				$nss->updateTranslation();
			break;
			case 'update_theme':
				$nss->updateTheme();
			break;
			case 'update_channels':
				$nss->updateChannels();
			break;
			case 'total_reset':
				$total_reset = $nss->totalReset();
			break;
			case 'add_license_key':
				$status_license_key = $nss->addLicenseKey($_POST['license_key']);
			break;
			case 'deactivate_site':
				$nss->deactivateSite($_POST['site']);
			break;
		}
	}
	
	if(array_key_exists('delete',$_GET) && $_GET['delete']=='key'){
		$nss->removeLicenseKey();
	}
	
	if(array_key_exists('delete',$_GET) && $_GET['delete']=='cache'){
		$nss->cleanCache();
	}
	
	if(array_key_exists('update',$_GET) && $_GET['update']=='1'){
		$nss->update();
	}
	
/****************************************************************************
* Template > Header
*****************************************************************************/
	
?><!DOCTYPE HTML>
<html class="<?php if($nss->isPro()) echo("pro"); else echo("lite"); ?>">
<head>
	<meta charset="utf-8">
	<title>neosmart STREAM Admin</title>
	<?php $nss->includeFile('reset.css'); ?>
	<link href='style.css' type='text/css' rel='stylesheet' />
	<?php $nss->includeFile('jquery.js'); ?>
	<?php $nss->includeFile('jquery-migrate.js'); ?>
	<?php $nss->includeFile('jquery-masonry.js'); ?>
	<script type='text/javascript' src='jquery.neosmart.stream.admin.js'></script>
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
				<?php if(!$nss->is_default_password($admin_password) && $nss->get('plugin_mode')===false){ ?>
					<a id="logout" class="nss-admin-logout button" href="index.php?logout=1">Logout</a>
				<?php } ?>
				<?php if($nss->get('plugin_mode')=='wordpress'){ ?>
					<a id="admin-refresh" class="button" href="javascript://" onClick="window.location.reload()">Refresh</a>
				<?php } ?>
				<h1><a href="<?php echo NSS_WEBSITE_URL; ?>" target="_blank"><img src="neosmart-stream-logo-<?php if($nss->isPro()) echo 'pro'; else echo 'lite'; ?>.png" alt="neosmart STREAM" width="323" height="41"></a></h1>			
				<?php if(!isset($hide_menu)){ ?>
				
				<div class="nss-admin-menu">
					<a href="index.php" class="button<?php if($current_page=='overview') echo " current-page"; ?>">Overview</a>
					<a href="channels.php" class="button<?php if($current_page=='channels') echo " current-page"; ?>">Channels</a>
					<a href="themes.php" class="button<?php if($current_page=='themes') echo " current-page"; ?>">Themes</a>
					<a href="config.php" class="button<?php if($current_page=='config') echo " current-page"; ?>">Configuration</a>
					<a href="feedback.php" class="button<?php if($current_page=='feedback') echo " current-page"; ?>">Feedback</a>
					<a href="license.php" class="button<?php if($current_page=='license') echo " current-page"; ?>">License</a>
					<?php if($nss->get('plugin_mode')===false){ ?><a href="password.php" class="button<?php if($current_page=='password') echo " current-page"; ?>">Password</a><?php } ?>
					
				</div>
				<?php } ?>
				
			</div><!--/.center-->
		</div>
		
	<div class="nss-admin-body">
		<div class="center">
			<?php if($nss->is_default_password($admin_password) || ($channel_count===0 && !isset($no_channel_warning))){ ?>
				<h2>Warning</h2>
			<?php } ?>
			<?php if($nss->is_default_password($admin_password)){ ?>
				<div class="nss-admin-container warning">
					<div class="row"><b>Empty admin password:</b> <a href="password.php">Set admin password</a> as soon as possible!</div>
				</div>
			<?php } ?>
			<?php if($channel_count===0 && !isset($no_channel_warning)){ ?>
			<div class="nss-admin-container warning">
				<div class="row"><b>No data to display:</b> <a href="channels.php">Add a channel</a></div>
			</div>
			<?php } ?>
			
			
			<?php if($serverError){ /***********************************************/ ?>
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
			<?php }  ?>
			
			
			<?php if($update_available && $current_page!='update' ){ ?>
				<div class="nss-admin-container warning">
					<div class="row"><?php echo $update_available; ?></div>
				</div>
			<?php } ?>
			
			<noscript>
				<div class="nss-admin-container error">
					<div class="row"><b>Javascript is disabled!</b></div>
					<div class="todo">You have to <b>activate Javascript</b> to use all features.</div>
				</div>
			</noscript>
			
			<?php if(!empty($_GET['saved'])){ ?>
				<div class="nss-admin-container saved">
					<div class="row">Changes are saved</div>
				</div>
			<?php } ?>
			
			<?php if(isset($fullsize_content)){ ?></div><?php } ?>