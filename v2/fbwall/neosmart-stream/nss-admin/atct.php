<?php

include "../setup.php";

$hide_menu = true;
$no_channel_warning = true;	
$page = '';
$apiError = '';

$oauth_callback = $nss->getBaseURL().'nss-admin/atct.php';
	

//Sign in Button
if(trim($nss->get('twitter_consumer_key')=='') || trim($nss->get('twitter_consumer_secret')=='')){
	$apiError[0] = 'Could not connect to Twitter';
	$apiError[1] = '1. <a href="https://dev.twitter.com/apps/new" target="_blank">Create</a> an application on dev.twitter.com<br>2. Add consumer key and consumer secret from your app in your neosmart STREAM <a href="config.php#twitter-settings" target="_blank">Configuration &gt; Twitter settings</a>.';
}
elseif (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])){

	if(isset($_GET['redirect']) && $_GET['redirect'] == 'true'){
		
		/* Build TwitterOAuth object with client credentials. */
		$connection = new TwitterOAuth($nss->get('twitter_consumer_key'), $nss->get('twitter_consumer_secret'));
		 
		/* Get temporary credentials. */
		$request_token = $connection->getRequestToken($oauth_callback);
		
		/* Save temporary credentials to session. */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		
		switch ($connection->http_code) {
			case 200:
				/* Build authorize URL and redirect user to Twitter. */
				$url = $connection->getAuthorizeURL($token);
				header('Location: ' . $url); 
				die;
				break;
			default:
				/* Show notification if something went wrong. */
				$apiError[0] = 'Could not connect to Twitter';
				$apiError[1] = '1. <a href="https://dev.twitter.com/apps/new" target="_blank">Create</a> an application on dev.twitter.com<br>2. Add consumer key and consumer secret from your app in your neosmart STREAM <a href="config.php#twitter-settings" target="_blank">Configuration &gt; Twitter settings</a>.';
		}
	/*****************************************************
	 *CALLBACK
	  ***************************************************/
	}elseif(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){		
		/* If the oauth_token is old redirect to the connect page. */
		if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
		  $_SESSION['oauth_status'] = 'oldtoken';
		  $page = 'oldtoken';
		  
		  //TODO: Hier wird noch nichts gemacht
		  exit;
		}
		
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth($nss->get('twitter_consumer_key'), $nss->get('twitter_consumer_secret'), $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
		
		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $access_token;
		
		/* Remove no longer needed request tokens */
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $connection->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
			$_SESSION['status'] = 'verified';
			$nss->reload();
			
		} else {
			/* Save HTTP status for error dialog on connnect page.*/
			$page = 'connect';
		}
	/*****************************************************
	 *CONNECT
	  ***************************************************/
	}else{
		$page = 'connect';
	}
/*************************************************************
 *GET TWEETS
  ***********************************************************/
}else{
	$page = 'access';
	/* Get user access tokens out of the session. */
	$access_token_array = $_SESSION['access_token'];
}
?>


<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>neosmart STREAM Access Token Creator</title>
	<link href='../nss-includes/reset.css' type='text/css' rel='stylesheet' />
	<link href='style.css' type='text/css' rel='stylesheet' />
	<script type='text/javascript' src='../nss-includes/jquery.js'></script>
</head>
	<div id="nss-admin">
		<div class="nss-admin-header">
			<div class="center">
				<h1><a href="<?php echo NSS_WEBSITE_URL; ?>" target="_blank"><img src="neosmart-stream-logo.png" alt="neosmart STREAM" width="260" height="41"></a></h1>			
			</div><!--/.center-->
		</div>
		
		<div class="center">
			
			<?php if($apiError){ /***********************************************/ ?>
				<h2>Twitter API error</h2>
				<div class="nss-admin-container error">
					<div class="row"><?php echo $apiError[0]; ?></div>
					<div class="todo"><?php echo $apiError[1]; ?></div>
				</div>
			<?php  } else { ?>
				<h2>Twitter Access Token Creator</h2>
			<?php  } ?>
			<?php if($page == 'connect'){ ?>
			
				<form class="nss-admin-container">
					<div class="row">
						<a href="?redirect=true" class="submit">Sign in with Twitter</a>
					</div>
				</form>
				
			<?php }elseif($page == 'access'){ ?>
			
				<form class="nss-admin-form">
					<div class="row">
						<label>Access token</label>
						<div class="field-area">
							<div class="field-info">
								<span id="atct-token" class="info"><?php echo $access_token_array['oauth_token'];?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<label>Access token secret</label>
						<div class="field-area">
							<div class="field-info">
								<span id="atct-secret" class="info"><?php echo $access_token_array['oauth_token_secret'];?></span>
							</div>
						</div>
					</div>
				</form>
			
			<?php }else{
				echo $page;	
			}
			?>
			
		</div>
	</div><!--#nss-admin-->
</body>
</html>