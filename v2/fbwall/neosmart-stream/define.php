<?php
	
	$nssPhpVersion = explode('.',phpversion());
	if($nssPhpVersion[0]>5 || ($nssPhpVersion[0]==5 && $nssPhpVersion[1]>=2)){
		//PHP version: OK	
	}
	else die('Your server is running PHP version '.phpversion().' but neosmart STREAM requires at least PHP version 5.2.');
	
	//Absolute Software Path
	define('NSS_ABSPATH', 				dirname(__FILE__).DIRECTORY_SEPARATOR);
	
	//Config
	define('NSS_CONFIG', 				'nss-config/');
	define('NSS_CONFIG_BASE_URL', 		NSS_ABSPATH.NSS_CONFIG.'nss-base-url.php');
	define('NSS_CONFIG_CHANNELS', 		NSS_ABSPATH.NSS_CONFIG.'nss-channels.php');
	define('NSS_CONFIG_GROUPS', 		NSS_ABSPATH.NSS_CONFIG.'nss-groups.php');
	define('NSS_CONFIG_CONFIG', 		NSS_ABSPATH.NSS_CONFIG.'nss-config.php');
	define('NSS_CONFIG_PROXY', 			NSS_ABSPATH.NSS_CONFIG.'nss-proxy.php');
	define('NSS_CONFIG_THEME', 			NSS_ABSPATH.NSS_CONFIG.'nss-theme.php');
	define('NSS_CONFIG_LICENSE', 		NSS_ABSPATH.NSS_CONFIG.'nss-license.php');
	define('NSS_CONFIG_PASSWORD', 		NSS_ABSPATH.NSS_CONFIG.'nss-password.php');
	define('NSS_CONFIG_TRANSLATE', 		NSS_ABSPATH.NSS_CONFIG.'nss-translate.php');
	define('NSS_CONFIG_ERROR', 			NSS_ABSPATH.NSS_CONFIG.'nss-error.php');
	define('NSS_CONFIG_CODE', 			NSS_ABSPATH.NSS_CONFIG.'nss-code2.php');
	define('NSS_CONFIG_PLUGIN', 		NSS_ABSPATH.NSS_CONFIG.'nss-plugin.php');
	define('NSS_CONFIG_FEEDBACK', 		NSS_ABSPATH.NSS_CONFIG.'nss-feedback.php');
	
	//Content
	define('NSS_CONTENT', 				'nss-content/');
	define('NSS_CONTENT_CACHE', 		NSS_ABSPATH.NSS_CONTENT.'cache/');
	define('NSS_CONTENT_UPDATE', 		NSS_ABSPATH.NSS_CONTENT.'update/');
	define('NSS_CONTENT_THEMES', 		NSS_ABSPATH.NSS_CONTENT.'themes/');
	
	//Core
	define('NSS_CORE', 					'nss-core/');
	
	//Includes
	define('NSS_INCLUDES', 				'nss-includes/');
	
	//URLs
	define('NSS_WEBSITE_URL', 			'https://neosmart-stream.de/');
	define('NSS_API_URL', 				'https://api.neosmart-stream.com/');
	define('NSS_UPDATE_FILE',			NSS_WEBSITE_URL.'wp-content/downloads/latest.zip');
	
	//Admin
	define('NSS_ADMIN', 				'nss-admin/');
	
	//DEBUG
	define('NSS_DEBUG', 				false);
?>