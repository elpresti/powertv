<?php
	if(!isset($_SESSION)) session_start();

	require_once 'define.php';
	@include_once NSS_CONFIG_PROXY;
	
	if(defined('NSS_WP_PATH')) error_reporting(E_NOTICE | E_ERROR);
	else NSS_DEBUG ? error_reporting(E_ALL) : error_reporting(0);

	require_once NSS_ABSPATH.'nss-includes/twitter/OAuth.php';
	require_once NSS_ABSPATH.'nss-includes/twitter/twitteroauth.php';
	require_once NSS_ABSPATH."nss-core/NeosmartStream.php";
	
	$nss = new NeosmartStream();
	
	@include NSS_CONFIG_CONFIG;
	@include NSS_CONFIG_THEME;
	@include NSS_CONFIG_CHANNELS;
	@include NSS_CONFIG_GROUPS;
	@include NSS_CONFIG_TRANSLATE;
	@include NSS_CONFIG_CODE;
	@include NSS_CONFIG_LICENSE;
	@include NSS_CONFIG_BASE_URL;
	@include NSS_CONFIG_PASSWORD;
	@include NSS_CONFIG_PLUGIN;
	@include NSS_CONFIG_ERROR;
	@include NSS_CONFIG_FEEDBACK;
	
	setlocale (LC_TIME, $nss->get('locale_time'));
	
	$nss->init();
?>