<?php
/************************************************************************************************************************************
 *	neosmart STREAM - core class
 *
 *	@copyright:			neosmart GmbH
 *	@licence:			https://neosmart-stream.de/legal/license-agreement/
 *	@documentation:		https://neosmart-stream.de/docs/
 *	@version:			1.6.0
 *	
 *  IT IS EXPLICITLY FORBIDDEN TO EDIT THIS FILE.
 ************************************************************************************************************************************/

class NeosmartStream{
	
	private $config = array(
		'version_major'					=> 1,
		'version_minor'					=> 6,
		'version_revision'				=> 0,
		'admin_password'				=> '21232f297a57a5a743894a0e4a801fc3',
		'admin_email'					=> '',
		'base_urls'						=> '',
		'debug_mode'					=> false,
		'cache_time'					=> 60,
		'cache_auto_refresh'			=> true,
		'cache_auto_refresh_time'		=> 60,
		'cache_time_profile'			=> 86400,
		'date_time_format'				=> '%d %B %Y, %H:%M',
		'theme'							=> 'base',
		'channel_group'					=> 'all',
		'error_no_data'					=> 'No data found. Please check your configuration.',
		'default_limit'					=> 5,
		'license_name'					=> 'neosmart STREAM Lite',
		'license_version'				=> 'lite',
		'license_owner'					=> '',
		'license_key'					=> '0000-0000-0000-0000',
		'license_sites'					=> '',
		'license_status'				=> '',
		'license_message'				=> '',
		'code'							=> '4fedf51e1e100397a0231cf3fbd33c83',
		'plugin_mode'					=> false,
		'error'							=> 0,
		'show_admin_link'				=> false,
		'locale_time'					=> 'en_US',
		'fb_api_lang'					=> 'en_US',
		'feedback_header'				=> true,
		'feedback_header_fb_like'		=> true,
		'feedback_header_fb_send'		=> true,
		'feedback_header_fb_post'		=> true,
		'feedback_header_twitter_follow'=> 1,
		'feedback_item'					=> true,
		'feedback_item_fb_like'			=> true,
		'feedback_item_fb_comment'		=> true,
		'feedback_item_retweet'			=> 1,
		'intro_fadein'					=> 700,
		'facebook_blacklist'			=> 'likes a post, on their own, likes their own, person who shared it may not have permission to share it with you, are now friends, likes a photo, is now friends with',
		'facebook_internal_limit'		=> 30,
		'twitter_consumer_key' 			=> '',
		'twitter_consumer_secret' 		=> ''
	);
	private $channel_list 				= array();	
	private $group_list 				= array();	
	private $profiles 	 				= array();	

	
/****************************************************************************
 * It is explicitly forbidden to remove the branding. Any hiding or 
 * disguising of the branding, or using any other method to avoid the 
 * showing of the branding is a breach of the terms of the license agreement!
 ****************************************************************************/
 
	private $el = array(
		'a' 	=> "<div style='color:#888888!important; font-family:\"lucida grande\", tahoma, verdana, arial, sans-serif; font-size:11px!important;overflow:visible!important;display:block!important;visibility:visible!important;opacity:1!important' data-id='nss-ad'>",
		'_a' 	=> "</div>",
		'd' 	=> "<div style='display:block;width:auto;padding:5px 10px;overflow:hidden;'>",
		'_d' 	=> "</div>",
		'e'		=> "<div style='font-family:\"lucida grande\", tahoma, verdana, arial, sans-serif; font-size:11px!important;display:block;width:auto;padding:5px 10px;overflow:hidden;background-color:#c00;color:#fff;'>",
		'sp' 	=> "<a title='neosmart STREAM - Social Media Plugin - Facebook, Twitter, Wordpress Support' target='_blank' href='https://neosmart-stream.de/' style='text-decoration:none;color:#888888!important;'><span style='color:#ff7800!important;background:url(nss-root/nss-core/nss-icon.png) no-repeat 0 center!important;display:inline-block!important;padding:2px 2px 4px 22px!important'>neosmart STREAM</span> - Social Plugin</a>",
		'dmi'	=> "<div class='nss-debug-mode-info'></div>"
	);

/**************************************************************************
 * Construct
 **************************************************************************/
 
	function __construct() {
		$https = array_key_exists("HTTPS",$_SERVER) && $_SERVER['HTTPS']!='off';
		$this->set('https',$https);
	}
	
/**************************************************************************
 * Init
 **************************************************************************/
 
	public function init() {

	}
   
   
/**************************************************************************
 * Getter and Setter
 * @since 1.0
 **************************************************************************/
 
	public function set($parameter,$value){
		$this->config[$parameter] = $value;
	}
	
	public function get($parameter){
		if($parameter=='channel_count'){
			return count($this->channel_list);
		}
		elseif($parameter=='channel_list'){
			return $this->channel_list;
		}
		elseif($parameter=='group_list'){
			return $this->group_list;
		}
		elseif($parameter=='version'){
			return $this->config['version_major'].'.'.$this->config['version_minor'].'.'.$this->config['version_revision'];
		}
		elseif($parameter=='nss_website'){
			return NSS_WEBSITE_URL;
		}
		return array_key_exists($parameter,$this->config)?$this->config[$parameter]:false;
	}
	
	public function getPath(){
		return get_include_path();
	}
	
	public function getChannelField($id,$value){
		foreach($this->channel_list as $c) if($c['id']==$id) return $c[$value];
	}
	
	public function setGroup($id){
		//TODO: überprüfen ob Gruppe überhaupt existiert
		if(isset($id)) $this->set('channel_group',$id);
	}
	
	public function getSafeURL($url){
		if($this->get('https')){
			$url = str_replace('http:','https:',$url);
		}else{
			$url = str_replace('https:','http:',$url);
		}
		$url = str_replace('autoplay=1','autoplay=0',$url);
		return $url;
	}
	
	
/**************************************************************************
 * Base URL
 * @since 1.0
 * @update 1.5.3
 **************************************************************************/
 
 	public function getBaseURL(){
		
		$base_urls = explode(',',$this->get('base_urls'));
		$host = $_SERVER['HTTP_HOST'];
		$base = false;
		
		foreach($base_urls as $u){
			$base_host = parse_url($u,PHP_URL_HOST);
			if($host==$base_host) $base = $u;
		}
		
		//$test = array_intersect($base_urls,array($current_url));
		//$test = array_values($test);

		if($base===false){
			$current_nss_folder = $this->getNssRoot();
			if($current_nss_folder){
				array_push($base_urls,$current_nss_folder);
				$this->saveBaseURL(implode(',',$base_urls));
				$url = $current_nss_folder;
			}else{
				//Base URL nicht vorhanden und kann aktuell auch nicht erkannt werden
				return false;
			}
				
		}else{
			$url = $base;	
		}
		
		if($this->get('https')){
			$url = str_replace('http:','https:',$url);
		}else{
			$url = str_replace('https:','http:',$url);
		}
		return $url;
	}
	
	function saveBaseURL($urls){
		$fh = @fopen(NSS_CONFIG_BASE_URL, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss))die;";
		$data .= "\n$"."nss->set('base_urls','".$urls."');";
		$data .= "\n?>";
		if($fh){
			fwrite($fh, $data);
			fclose($fh);
			$this->set('base_urls',$urls);
			return true;
		}else{
			if($this->getNssRoot()){
				//Im Adminbereich wird der Ordner nss-cache automatisch erzeugt
				header('Location: '.$this->getNssRoot());
				die;	
			}
			$this->reload('?error=file_permissions');
		}
	}
	
	function getNssRoot($type=false){
		$https = array_key_exists("HTTPS",$_SERVER) && $_SERVER['HTTPS']!='off';
		$protocol = $https ? "https://" : "http://";
		$host = $_SERVER['HTTP_HOST'];
		$pos = strrpos($_SERVER['SCRIPT_NAME'],'/neosmart-stream/');
		$path = substr($_SERVER['SCRIPT_NAME'],0,$pos+17);
		if($pos===false) return false;
		if($type=='host') return $host;
		else return $protocol.$host.$path;
	}
	
/**************************************************************************
 * Theme Meta auslesen
 * @since 1.0
 **************************************************************************/
 
	public function getThemeMeta($meta,$theme){
		$data = false;
		$lines 	= explode("\n", implode('', file(NSS_CONTENT_THEMES.$theme."/style.css")));
		foreach ($lines as $line) {
			$pos = strpos($line, $meta);
			if($pos!==false) $data = trim(substr($line,$pos+strlen($meta)));
		}
		return $data;
	}

/**************************************************************************
 * Current Host is Part of Base-Url?
 * @since 1.0
 * @update 1.5.3
 **************************************************************************/
 	
	function hostIsPartOfBaseURL(){
		$host = $_SERVER['HTTP_HOST'];
		//die('nix:'.$this->getBaseURL());
		$baseURL = parse_url($this->getBaseURL(),PHP_URL_HOST);
		return $host===$baseURL;
	}
	
	private function hostIsPartOfSiteArray(){
		$host = $_SERVER['HTTP_HOST'];
		$sites = explode(',',$this->get('license_sites'));
		foreach($sites as $site) if($host==$site) return true;
		return false;
	}
	
/**************************************************************************
 * Syntax test for license
 * @since 1.0
 **************************************************************************/
 	
	function testLicenseSyntax(){
		$key = $this->get('license_key');
		if(empty($key)) return false;
		if(strlen($key)!=19) return false;
		if(count(explode('-',$key))!=4) return false;
		if($this->get('license_status')!='valid') return false;
		return true;
	}

/**************************************************************************
 * Add group
 * @since 1.5.1
 **************************************************************************/
 
	function addGroup($new_group){
		if(is_array($new_group)) array_push($this->group_list,$new_group);
	}
	
/**************************************************************************
 * Add Channel
 * @since 1.0
 * @changed 1.5
 **************************************************************************/
 	
	function addChannel($new_channel){
		if(is_array($new_channel)) array_push($this->channel_list,$new_channel);
	}
	
	function addChannelDeprecated($type,$b,$c='',$d=3,$e='true',$f=0){
		$new_channel = array();
		switch(strtolower($type)){
			case 'facebook':
				$new_channel['type'] 					= 'facebook';
				$new_channel['id']			 			= $b;
				$new_channel['access_token'] 			= $c;
				$new_channel['limit'] 					= $d;
				$new_channel['show_all']			 	= $e;
				$new_channel['access_token_expires'] 	= $f;
				
			break;
			case 'twitter':
				$new_channel['type'] 			= 'twitter';
				$new_channel['id'] 				= $b;
				$new_channel['limit'] 			= $c;
			break;
			default: //nss
				$new_channel['type'] 			= 'nss';

				
				$tmp = substr($b,strrpos($b,"/")+1);
				$tmp = urlencode($tmp);
			
				$new_channel['id']				= $tmp;
				$new_channel['url'] 			= $b;
			break;
		}
		array_push($this->channel_list,$new_channel);
	}

/**************************************************************************
 * Init
 **************************************************************************/
 	
	function initStream(){
		return $this->updateRequired() ? $this->updateCache() : $this->show();
	}
 
 
/**************************************************************************
 * Update
 **************************************************************************/
 
	function updateRequired($theme='base',$group='all'){
		$now = time();
		$update = false;
		$cache_file = NSS_ABSPATH."nss-content/cache/".$theme."-".$group.".html";
		
		if(!is_dir(NSS_ABSPATH."nss-content/cache/")){
			mkdir(NSS_ABSPATH."nss-content/cache/",0755);
			$update = true;
		}
		elseif(!file_exists($cache_file)){
			$update = true;
		}
		elseif($now-filemtime($cache_file) >= $this->get('cache_time')){
			$update = true;
		}
		elseif(filemtime(NSS_ABSPATH.'nss-config/nss-config.php')-filemtime($cache_file)>0
			|| filemtime(NSS_ABSPATH.'nss-config/nss-channels.php')-filemtime($cache_file)>0
			|| filemtime(NSS_ABSPATH.'nss-config/nss-feedback.php')-filemtime($cache_file)>0
			|| filemtime(NSS_CONFIG_BASE_URL)-filemtime($cache_file)>0
			|| filemtime(NSS_ABSPATH.'nss-config/nss-translate.php')-filemtime($cache_file)>0
		){
			$update = true;
		}
		
		return $update;
	}
	
	function isChannelUpToDate($file,$cache_time='cache_time'){
		$now = time();
		$ft = @filemtime($file);
		$channelConfig = @filemtime(NSS_ABSPATH.'nss-config/nss-channels.php');
		if(!$ft){
			//Datei nicht vorhanden
			return false;
		}
		elseif($now-$ft >= $this->get($cache_time)){
			//Datei älter als Cache Time
			return false;
		}
		elseif($channelConfig >= $ft){
			//Datei älter als Channel Config
			return false;
		}
		else{
			//Datei is aktuell
			return true;
		}
	}
	
/**************************************************************************
 * Update Info für den Anwender (wird im Backend angezeigt)
 **************************************************************************/
 	
	function getLastUpdate(){
		$p = $this->isPro() ? '-p' : '';
		$cache_file = NSS_CONTENT_CACHE.$this->get('theme').'-all'.$p.'.html';
		$ft = @filemtime($cache_file);
		if(!$ft) return "Never";
		return strftime($this->get('date_time_format'), $ft);
	}


/**************************************************************************
 * Read Cache
 **************************************************************************/
 
	function show($echo = true){
		$html = '';
		$cache_handle = '';
		$chancel = '';
		$admin_link = $this->htmlWrap('admin_link','Admin');
		//Error
		if($this->get('error')!==0){
			if($this->get('error')==2) $message = '<b>File error:</b> a file has been manually adjusted and caused a serious error. Please provide your license key again.';
			else $message = '<b>File error:</b> one or more files are conflicted. <a href="'.NSS_WEBSITE_URL.'downloads/" style="color:#fff;" target="_blank">Download</a> the latest version.';
			$chancel .= $this->el['e']."neosmart STREAM - ".$message.$this->el['_d'];
			if($echo){
				echo $chancel;
				return;
			}
			else return $chancel;
		}
		if(!$this->hostIsPartOfBaseURL()){
			$chancel .= $this->el['d']."<span style='color:#fff;background-color:#c00;padding:10px;display:inline-block;'><strong>neosmart STREAM</strong> - Base URL is missing - Open your admin area to solve this issue.</span>".$this->el['_d'];
			if($echo){
				echo $chancel;
				return;
			}
			else return $chancel;
		}
		
		//Prüfen ob Channel Gruppe vorhanden ist
		if($this->get('channel_group')!='all' && !$this->groupExists($this->get('channel_group'))){
			$chancel .= $this->el['d']."<span style='color:#fff;background-color:#c00;padding:10px;display:inline-block;'><strong>neosmart STREAM</strong> - Invalid channel group!</span>".$this->el['_d'];
			if($echo){
				echo $chancel;
				return;
			}
			else return $chancel;
		}
		
		$html = $this->deb($html);

		//Cache
		if($this->get('channel_count')!=0){
			$p = $this->isPro() ? '-p' : '';
			$cache_handle .= @file_get_contents(NSS_CONTENT_CACHE.$this->get('theme').'-'.$this->get('channel_group').$p.'.html');
			//Info, falls noch kein Cache vorhanden ist
			if(!$cache_handle) $cache_handle = '<div class="nss-first-load"><span>streaming data ...</span></div>';
		}else{
			$cache_handle = '<div class="nss-warning"><b>neosmart STREAM:</b> No data to display. Open admin area and <b>add a channel!</b></div>';
		}
		

		$html .= $cache_handle;

		if($this->get('show_admin_link')) $html .= $admin_link;
		
		$class = $this->isPro() ? 'nss-pro' : 'nss-lite';
		if($this->get('debug_mode')) $class .= ' nss-debug';
		
		$theme = $this->get('theme');
		$plugins = $this->getThemeMeta('Plugins:',$theme);
		$masonry = strpos($plugins,'masonry') ? 'true' : 'false';
		$fb_app_id = $this->get('fb_app_id');
		$intro_fadein = $this->get('intro_fadein');
		$auto_refresh = $this->get('cache_auto_refresh') ? 'true' : 'false';		
		
		$html = "<div class='nss nss-load ".$class."' data-fb-lang='".$this->get('fb_api_lang')."' data-fadein='".$intro_fadein."' data-masonry='".$masonry."' data-cache='".$this->get('cache_time')."' data-theme='".$theme."' data-path='".$this->getBaseURL()."' data-group='".$this->get('channel_group')."' data-auto-refresh='".$auto_refresh."' data-refresh-time='".$this->get('cache_auto_refresh_time')."'>".$this->el['dmi'].$html.'</div>';
		
		//Check for update
		$this->checkForUpdate();
		$html = '<style>.nss-pro,.nss-lite{display:none;}</style>'.$html;
		/*$html .= '<script>var nsstmp = document.getElementById("nss"); nsstmp.className = nsstmp.className+" nss-load";</script>';*/
	
		if($echo){
			echo $html;
			return;
		}
		else return $html;
	}
	
	private function groupExists($id){
		foreach($this->group_list as $g){
			if($g['id']==$id) return true;
		}
		return false;
	}
	
	private function deb($html){
		if($this->get('debug_mode')){
			if($this->get('channel_count')==0){
				$html = $this->htmlWrap('notice','No Channels to display. <a href="'.$this->getBaseURL().'">Login</a> and add a channel!');
				$this->cleanDir(NSS_CONTENT_CACHE);
			}else{
				if($this->updateRequired($this->get('theme'))){
					//Info wird über jQuery ausgegeben
				}
			}	
		}
		return $html;
	}
	
	private function wrap($html){
		$feedback = '';
		
		@include_once NSS_ABSPATH.'nss-content/themes/'.$this->get('theme').'/template-wrap.php';
		
		if($this->isPro()){	
			 include "template-feedback.php";
		}else{
			$html = $this->el['a'].$this->el['d'].$this->el['sp'].$this->el['_d'].$this->el['_a'].$html;
			$html = preg_replace('/nss-root\//',$this->getBaseURL(),$html);
		}
		$html = '<div class="nss-stream">'.$feedback.$html.'</div>';
		return $html;
	}
	
	
	function readFile($filename){
		$cache_handle = @file_get_contents(NSS_CONTENT_CACHE.$filename);
		return $cache_handle;
	}
	
/**************************************************************************
 * Clean Cache
 **************************************************************************/
 	 
function cleanDir($dir=false) {
    $mydir = @opendir($dir);
	if(!$mydir) return false;
	
	while(false !== ($file = readdir($mydir))) {
		if($file != "." && $file != "..") {
			chmod($dir.$file, 0777);
			if(is_dir($dir.$file)) {
				chdir('.');
				$this->cleanDir($dir.$file.'/');
				rmdir($dir.$file) or DIE("couldn't delete $dir$file<br />");
			}
			else
				unlink($dir.$file) or DIE("couldn't delete $dir$file<br />");
		}
	}
	closedir($mydir);
	return true;
}

	
/**************************************************************************
 * Wrap HTML
 **************************************************************************/
	
	function htmlWrap($type,$content){
		$notice="display:block;padding:5px;padding:5px 10px;font-size:13px;border-bottom:1px solid #d8d8a4;background-color:#ffffe0;color:#555;";
		$error="display:block;padding:5px;padding:5px 10px;font-size:13px;border-bottom:1px solid #d8d8a4;background-color:#c00;color:#FFFFFF;;";
		switch($type){
			case 'notice':
				$html = '<div style="'.$notice.'">'.$content.'</div>';
			break;
			case 'error':
				$html = '<div style="'.$error.'">'.$content.'</div>';
			break;
			case 'admin_link':
				$html = '<div class="nss-admin-link"><a href="'.$this->getBaseURL().'">'.$content.'</a></div>';
			break;
			default:
				$html = $content;
			break;
		}
		return $html;
	}
	
	
/**************************************************************************
 * Update Channel
 **************************************************************************/
 	
	function updateChannel($k){		
		
		switch($this->channel_list[$k]['type']){
			case 'facebook':
				$filename = NSS_CONTENT_CACHE.'facebook_'.$this->channel_list[$k]['id'].'.xml';
				if($this->isChannelUpToDate($filename)){
					$response = 'up-to-date';
				}
				else{
					$response = $this->readFacebookChannel($this->channel_list[$k]['id'],$this->channel_list[$k]['access_token'],$this->channel_list[$k]['limit'],$this->channel_list[$k]['show_all']);
				}
			break;
			case 'twitter':
				$filename = NSS_CONTENT_CACHE.'twitter_'.$this->channel_list[$k]['id'].'.xml';
				if($this->isChannelUpToDate($filename)){
					$response = 'up-to-date';
				}
				else{
					$response  = $this->readTwitterChannel($this->channel_list[$k]);
				}
			break;
			default:
				$response = $this->readChannel($this->channel_list[$k]);
			break;
		}
		
		//Profil Info
		$this->readChannelProfile($this->channel_list[$k]);
		
		return $response;
	}
	
	function readChannelProfile($channel){
		
		$error 			= false;
		$file 			= NSS_CONTENT_CACHE.$channel['type'].'_'.$channel['id'].'_profile.xml';
		$username 		= $channel['id'];
		$link			= '';
		$extras			= '';
		$avatar 		= '';
		
		//Abbruch wenn Cache aktuell
		if($this->isChannelUpToDate($file,'cache_time_profile')){
			return true;	
		}
		
		
		switch($channel['type']){			
			case 'facebook':
				$id = $channel['id'];
				$url = "https://graph.facebook.com/".$channel['id']."?access_token=".$channel['access_token'];
				$avatar = "https://graph.facebook.com/".$channel['id']."/picture";
				$data = $this->readData($url);
				$fbdata = json_decode($data);		
				if($data=='error' || isset($fbdata->{'error'})) $error = true;
				else{
					$username = isset($fbdata->name) ? $fbdata->name : $fbdata->username;
					$screenname = $username;
					$link = isset($fbdata->link) ? $fbdata->link : 'https://www.facebook.com/'.$fbdata->owner->id;
					$fb_type = isset($fbdata->likes) ? 'page' : 'user';
					if(empty($fbdata->username) && empty($fbdata->username)) $fb_type = 'group';
					$extras .= "\n\t\t<type>".$fb_type."</type>";
					if($fb_type=='page') $extras .= "\n\t\t<likes>".$fbdata->likes."</likes>";
					$extras .= "\n\t";
				}
			break;
			case 'twitter':
				$connection = new TwitterOAuth($this->get('twitter_consumer_key'), $this->get('twitter_consumer_secret'), $channel['access_token'], $channel['access_token_secret']);
				$parameters['screen_name'] = $channel['id'];
				$user = $connection->get('users/show',$parameters);
				$avatar = $user->profile_image_url_https;
				$username = $user->name;
				$id = $user->id_str;
				$screenname = $user->screen_name;
			break;
		}	

		if($error===false){
			$data = "<?xml version='1.0'?>";
			$data .= "<profile>";
			$data .= "\n\t<channel>".$channel['type']."</channel>";
			$data .= "\n\t<id>".$id."</id>";
			$data .= "\n\t<username>".$username."</username>";
			$data .= "\n\t<screenname>".$screenname."</screenname>";
			$data .= "\n\t<link>".$link."</link>";
			$data .= "\n\t<avatar>".$avatar."</avatar>";
			$data .= "\n\t<extras>".$extras."</extras>";
			$data .= "\n</profile>";
			$fh = fopen($file, 'w');
			fwrite($fh, $data);
			fclose($fh);
		}
	}


/**************************************************************************
 * Update Cache
 **************************************************************************/
 	
	function updateCache(){
		$data = '';
		for($k=0;$k<count($this->channel_list);$k++){
			switch($this->channel_list[$k]['type']){
				case 'facebook':
					$this->readFacebookChannel($this->channel_list[$k]['id'],$this->channel_list[$k]['access_token'],$this->channel_list[$k]['limit'],$this->channel_list[$k]['show_all']);
					if($response != 'error') $data .= $response;
				break;
				case 'twitter':
					$data .= $this->readTwitterChannel($this->channel_list[$k]);
				break;
				default:
					$data .= $this->readChannel($this->channel_list[$k]);
				break;
			}
		}
		return $data=='' ? $this->show(): $this->sortData($data);
	}

/**************************************************************************
 * Save Status of a Channel to local file
 **************************************************************************/
 	
	function saveChannelTestToFile($type,$id,$status){
		$file = NSS_ABSPATH."nss-content/cache/".$type.'_'.$id.'_status.xml';
		if($status=='success'){
			$data = 	'<span class="status active">active</span>';
			$return = 	'<span class="status active">active</span>';
		}
		else{
			$data = 	'<span class="status inactive">error</span>';
			$return = 	'<span class="status inactive">Error: '.$status.'</span>';
		}
		$fh = fopen($file, 'w');
		fwrite($fh, $data);
		fclose($fh);
		return $return;
	}
	
	function saveChannelTestToFileAndDie($type,$id,$status){
		$data = $this->saveChannelTestToFile($type,$id,$status);
		die($data);
	}
	
/**************************************************************************
 * Überprüfung ob ein bestimmter Channel in der aktuellen Channel Group enthalten ist
 **************************************************************************/
 	
	private function isPartOfGroup($id){
		$current = $this->get('channel_group');
		if($current=='all'){
			return true;
		}
		
		foreach($this->group_list as $group){
			if($group['id']==$current){
				$str = $group['channels'];
				foreach(explode(',',$str) as $channel){
					if($channel==$id) return true;	
				}
			}
		}
		
		return false;
	}
	
/**************************************************************************
 * Merge and Sort Data
 * wird via AJAX ausgelöst
 **************************************************************************/
	
	function mergeChannels($theme,$group=false){
		if(!empty($theme)) $this->set('theme',$theme);
		if(!empty($group)) $this->set('channel_group',$group);
		
		$data = '';
		for($k=0;$k<count($this->channel_list);$k++){
			
			if(!$this->isPartOfGroup($this->channel_list[$k]['id'])) continue;
			$file = $this->readFile($this->channel_list[$k]['type'].'_'.$this->channel_list[$k]['id'].'.xml');
			if($file){
				$file = preg_replace("/<\?xml version='1.0'\?>/","",$file);
				$start = strpos($file,"<nss>")+5;
				$end = strpos($file,"</nss>")-$start;
				$file = substr($file,$start,$end);
				$data .= $file;
				
				//Profil auslesen
				$tmp = $this->readFile($this->channel_list[$k]['type'].'_'.$this->channel_list[$k]['id'].'_profile.xml');
				$xml = simplexml_load_string($tmp);
				$this->profiles[$this->channel_list[$k]['type'].'_'.$xml->id] = $xml;
				
			}
		}
		return $this->sortData($data);
	}
	
	function sortData($data){
		$el = '';
		function filter_xml($matches) { 
			return trim(htmlspecialchars($matches[1])); 
		} 
		$data = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', 'filter_xml', $data);
		$xmlObj = simplexml_load_string("<?xml version='1.0'?><nss>".$data."</nss>");
		
		$arrXml = $this->changeObjectsToArrays($xmlObj);
		
		$item = $arrXml['item'];		
		if(isset($item['channel'])){$item = array($item);}
		
		foreach($this->el as $key => $value){ $el .= $value;}
		if(md5($el)!='2bc21c7347f9e1810b928afdb89aa738'){
			$fh = fopen(NSS_CONFIG_ERROR, 'w');	
			fwrite($fh, "<?php $"."nss->set('error',3); ?>");
			fclose($fh);
			return false;	
		}
		
		//If more than one
		if(!@array_key_exists('created',$item)){
			foreach($item as $c=>$key) {
				$sort_created[] = $key['created'];
			}
		}
		
		array_multisort($sort_created, SORT_DESC,$item );
		return $this->insertDataIntoTemplate($item);
	}
	
	function changeObjectsToArrays($data, $skip = array()){
		$arrayData = array();
		
		if (is_object($data)) $data = get_object_vars($data);
		if (is_array($data)) {
			foreach ($data as $index => $value) {
				if (is_object($value) || is_array($value)) {
					$value = $this->changeObjectsToArrays($value, $skip);
				}
				if (in_array($index, $skip)) {
					continue;
				}
				$arrayData[$index] = $value;
			}
		}
		return $arrayData;
	}
	
	
/**************************************************************************
 * Insert data into template
 **************************************************************************/
 
	function insertDataIntoTemplate($item){
		$out = '';
		
		for($position=0;$position<count($item);$position++){
			$nss = $item[$position];
		
			$channel = $nss['channel'];
			$is_facebook = $channel=='facebook';
			$is_twitter = $channel=='twitter';
			$is_default = !$is_facebook && !$is_twitter;
			$item_class = '';
						
			$timestamp = strtotime($nss['created']);
			$id = is_array($nss['id']) ? 'noid' : $nss['id'];
			$id .= '_'.$timestamp;

			$created = $this->transformDate($nss['created']);
			$updated = $this->transformDate($nss['updated']);
			$content = is_array($nss['content']) ? '' : $this->autoLink($nss['content']);
			
			$author = $nss['author'];
			$author_id = $nss['author']['id'];
			$author_name = $nss['author']['name'];
			$author_link = $nss['author']['link'];
			$author_avatar = $nss['author']['avatar'];
			
			//HTTPS Image Fix
			if($is_twitter){
				$author_avatar = $this->profiles[$channel.'_'.$author_id]->avatar;				
			}
			
			$location_address = isset($nss['location']['address']) ? $nss['location']['address'] : '';
			$location_latitude = isset($nss['location']['latitude']) ? $nss['location']['latitude'] : '';
			$location_longitude = isset($nss['location']['longitude']) ? $nss['location']['longitude'] : '';
			
			//Facebook
			$extras_facebook_type = $is_facebook ? $nss['extras']['facebook']['type'] : '';
			$extras_facebook_source = !$is_facebook || is_array($nss['extras']['facebook']['source']) ? '' : $this->getSafeURL($nss['extras']['facebook']['source']);
			$extras_facebook_description = !$is_facebook || is_array($nss['extras']['facebook']['description']) ? '' : $this->autoLink($nss['extras']['facebook']['description']);
			$extras_facebook_caption = !$is_facebook || is_array($nss['extras']['facebook']['caption']) ? '' : $nss['extras']['facebook']['caption'];
			$extras_facebook_picture = !$is_facebook || is_array($nss['extras']['facebook']['picture']) ? '' : $nss['extras']['facebook']['picture'];
			
			//Facebook Images
			$extras_facebook_image_2048 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_2048']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_2048'];
			$extras_facebook_image_960 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_960']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_960'];
			$extras_facebook_image_720 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_720']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_720'];
			$extras_facebook_image_600 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_600']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_600'];
			$extras_facebook_image_480 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_480']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_480'];
			$extras_facebook_image_320 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_320']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_320'];
			$extras_facebook_image_130 = !$is_facebook || empty($nss['extras']['facebook']['images']['image_130']) ? $extras_facebook_picture : $nss['extras']['facebook']['images']['image_130'];
			
			//Facebook Events
			$extras_facebook_event_name = !$is_facebook || empty($nss['extras']['facebook']['event']['name']) ? '' : $nss['extras']['facebook']['event']['name'];
			$extras_facebook_event_description = !$is_facebook || empty($nss['extras']['facebook']['event']['description']) ? '' : $nss['extras']['facebook']['event']['description'];
			$extras_facebook_event_start_time = !$is_facebook || empty($nss['extras']['facebook']['event']['start_time']) ? '' : $this->transformDate($nss['extras']['facebook']['event']['start_time']);
			$extras_facebook_event_end_time = !$is_facebook || empty($nss['extras']['facebook']['event']['end_time']) ? '' : $this->transformDate($nss['extras']['facebook']['event']['end_time']);
			$extras_facebook_event_location = !$is_facebook || empty($nss['extras']['facebook']['event']['location']) ? '' : $nss['extras']['facebook']['event']['location'];
			
			//Rest			
			$extras_facebook_link = !$is_facebook || is_array($nss['extras']['facebook']['link']) ? '' : $nss['extras']['facebook']['link'];
			$extras_facebook_name = !$is_facebook || is_array($nss['extras']['facebook']['name']) ? '' : $nss['extras']['facebook']['name'];
			$extras_facebook_message = !$is_facebook || is_array($nss['extras']['facebook']['message']) ? '' : $this->autoLink($nss['extras']['facebook']['message']);
			
			$extras_facebook_privacy_description = !$is_facebook || is_array($nss['extras']['facebook']['privacy']['description']) ? '' : $nss['extras']['facebook']['privacy']['description'];
			$extras_facebook_privacy_value = !$is_facebook || is_array($nss['extras']['facebook']['privacy']['value']) ? '' : $nss['extras']['facebook']['privacy']['value'];
			
			//TODO: Überprüfen ob noch vorhanden? Counts wurden entfernt
			$extras_facebook_count_likes = !$is_facebook || is_array($nss['extras']['facebook']['count']['likes']) ? '' : $nss['extras']['facebook']['count']['likes'];
			$extras_facebook_count_shares = !$is_facebook || is_array($nss['extras']['facebook']['count']['shares']) ? '' : $nss['extras']['facebook']['count']['shares'];
			
			$extras_facebook_story = !$is_facebook || is_array($nss['extras']['facebook']['story']) ? '' : $nss['extras']['facebook']['story'];
			$extras_facebook_icon = !$is_facebook || is_array($nss['extras']['facebook']['icon']) ? '' : $nss['extras']['facebook']['icon'];
			$extras_facebook_object_id = !$is_facebook || is_array($nss['extras']['facebook']['object_id']) ? '' : $nss['extras']['facebook']['object_id'];
			
			$extras_facebook_application_name = !$is_facebook || is_array($nss['extras']['facebook']['application']['name']) ? '' : $nss['extras']['facebook']['application']['name'];
			$extras_facebook_application_id = !$is_facebook || is_array($nss['extras']['facebook']['application']['id']) ? '' : $nss['extras']['facebook']['application']['id'];
			
			$extras_facebook_count_comments = !$is_facebook ? 0 : count($nss['extras']['facebook']['comments']);
			if($extras_facebook_count_comments>0){
				$tmp = $nss['extras']['facebook']['comments'];
				if(isset($tmp['comment'][0])) $extras_facebook_count_comments = count($tmp['comment']);				
			}

			//Comments
			if($extras_facebook_count_comments>1){
				$comment = '';
				for($d=0;$d<$extras_facebook_count_comments;$d++){
					$extras_facebook_comments_author_name = $tmp['comment'][$d]['author']['name'];
					$extras_facebook_comments_author_id = $tmp['comment'][$d]['author']['id'];
					$extras_facebook_comments_author_link = $tmp['comment'][$d]['author']['link'];
					$extras_facebook_comments_author_avatar = $tmp['comment'][$d]['author']['avatar'];
					$extras_facebook_comments_content = $tmp['comment'][$d]['content'];
					$extras_facebook_comments_created = $this->transformDate($tmp['comment'][$d]['created']);
					include '../nss-content/themes/'.$this->get('theme').'/template-comment.php';
				}
				$extras_facebook_comments = $comment;
			}else if($extras_facebook_count_comments==1){
				$comment = '';
				$extras_facebook_comments_author_name = $tmp['comment']['author']['name'];
				$extras_facebook_comments_author_id = $tmp['comment']['author']['id'];
				$extras_facebook_comments_author_link = $tmp['comment']['author']['link'];
				$extras_facebook_comments_author_avatar = $tmp['comment']['author']['avatar'];
				$extras_facebook_comments_content = $tmp['comment']['content'];
				$extras_facebook_comments_created = $this->transformDate($tmp['comment']['created']);
				 
				include '../nss-content/themes/'.$this->get('theme').'/template-comment.php';
				$extras_facebook_comments = $comment;
			}else{
				$extras_facebook_comments = '';
			}
			
			//Twitter Buttons
			$extras_twitter_button_tweet = '';
			if($this->get('feedback_item_retweet')>0){
				$twitter_data_count = $this->get('feedback_item_retweet')==2 ? 'horizontal' : 'none';
				$extras_twitter_button_tweet .= "<div class='nss-feedback' data-object-id='$id'>";
				$extras_twitter_button_tweet .= '<a href="https://twitter.com/share" class="twitter-share-button" target="_blank" data-via="'.$author_name.'" data-url="false" data-text="'.strip_tags($content).'" data-count="'.$twitter_data_count.'">Tweet</a>';
				$extras_twitter_button_tweet .= '</div>';
			}
			
			if($is_facebook) $item_class .= ' nss-facebook-type-'.$extras_facebook_type;
			$item_class = trim($item_class);
			$tmp = false;
			include '../nss-content/themes/'.$this->get('theme').'/template-post.php';
		}
		$out = $this->wrap($out);
		return $this->saveCache($out);
	}
	
/**************************************************************************
 * Save Cache
 **************************************************************************/
	
	function saveCache($out){
		$p = $this->isPro() ? '-p' : '';
		$cache_file = NSS_CONTENT_CACHE.$this->get('theme').'-'.$this->get('channel_group').$p.'.html';
		$fh = fopen($cache_file, 'w');
		fwrite($fh, $out);
		fclose($fh);
		return $out;
	}
	
	function saveFile($filename,$content){
		$cache_file = NSS_CONTENT_CACHE.$filename;
		$fh = fopen($cache_file, 'w');
		fwrite($fh, $content);
		fclose($fh);
	}
	
/**************************************************************************
 * Read Data
 **************************************************************************/
 
 	function readData($url){
 		$parsedUrl = parse_url($url);
		$data = null;

		//CURL
		if  (in_array  ('curl', get_loaded_extensions())){
				
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if(defined("NSS_PROXY")&&defined("NSS_PROXY_PORT")&&trim(NSS_PROXY)!=''){
				curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_NTLM);
				curl_setopt($ch, CURLOPT_PROXY, NSS_PROXY); 
				curl_setopt($ch, CURLOPT_PROXYPORT, NSS_PROXY_PORT); 
			}
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec ($ch);
			curl_close ($ch);
			//print_r($data);
			if($data) return $data;
		}

		//fsockopen
		$fp = @fsockopen ('ssl://'.$parsedUrl['host'] , 443, $errno, $errstr, 30);  
		if ($fp){
			fputs($fp, "GET /".$parsedUrl['path']."?".$parsedUrl['query']."/ HTTP/1.1\r\n"); 
			fputs($fp, "Host: ".$parsedUrl['host']."\r\n");
			fputs($fp, "Referer: ".$parsedUrl['host']."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			while (!feof($fp)){
				$data = fgets($fp);
			}
			fclose($fp);
			if($data) return $data;	
		} 

		//file_get_contents
		$data = @file_get_contents($url);
		if($data) return $data;

		//Anymore alternatives?
		
		//Error
		return 'error';
		
	}
	 
	function readFacebookChannel($id,$access_token,$limit=false,$show_all=true){
		if(!$limit) $limit = $this->get('default_limit');
		$binding = $show_all===true ? 'feed' : 'posts';
		$url = "https://graph.facebook.com/".$id."/".$binding."?limit=".$this->get('facebook_internal_limit')."&access_token=".$access_token;
		$data = $this->readData($url);
		$error = false;
		
		if($data == 'error') $error = true;
		$fbdata = json_decode($data);
		
		if(isset($fbdata->{'error'})) $error = true;
		
		if($error){
			$cached_data = $this->readFile('facebook_'.$id.'.xml');
			if($cached_data){
				$this->saveChannelTestToFile('facebook',$id,'error');
				return 'cache';
			}
			else{
				$this->saveChannelTestToFile('facebook',$id,'error');
				return 'error';
			}
		}else{		
			return $this->convertFacebookChannel($id,$fbdata,$access_token,$limit);
		}
	}	
	
	function convertFacebookChannel($id,$fbdata,$access_token,$limit){ 
	
		$posts = $fbdata->{'data'};
		$output = "";
		$counter = 0;
		
		for($k=0;$k< count($posts);$k++){
			$p 				= $posts[$k];
			$object_json 	= false;
			$event_json 	= false;
			$type 			= isset($p->type) ? $p->type : '';
			$tmp_id 		= explode('_',$p->id);
			$page_id 		= $tmp_id[0];
			$object_id 		= $tmp_id[1];
			$video_src 		= false;
			
			//Bilder über Object ID lesen
			if($type=='photo'&&isset($p->object_id)){
				$url = "https://graph.facebook.com/".$p->object_id."?access_token=".$access_token;
				$object_data = $this->readData($url);
				$object_json = json_decode($object_data);
			}
			
			//Event über Object ID lesen
			if($type=='link' && strpos($p->link,'facebook.com/events/')){
				$type = 'event';
				$url = "https://graph.facebook.com/".$object_id."?access_token=".$access_token;
				$event_data = $this->readData($url);
				$event_json = json_decode($event_data);
			}
			
			//Video Url anpassen
			if($type=='video'){
				$video_src = $p->source;
				if(strpos($video_src,'akamaihd.net/')!==false) $video_src = "https://www.facebook.com/video/embed?video_id=".$object_id;
			}
			
			//Break bei bestimmten Schlüsselwörtern
			if($type=='status'){
				$blacklist = explode(',',$this->get('facebook_blacklist'));
				$continue_loop = false;
				foreach($blacklist as $break_on){
					if(isset($p->story) && strpos(trim($p->story),trim($break_on))) $continue_loop = true;
					if(isset($p->message) && strpos(trim($p->message),trim($break_on))) $continue_loop = true;
					if(isset($p->description) && strpos(trim($p->description),trim($break_on))) $continue_loop = true;
					if(isset($p->caption) && strpos(trim($p->caption),trim($break_on))) $continue_loop = true;
				}
				if($continue_loop) continue;
			}
			
			//Abbruch wenn Limit erreicht wurde
			if($counter>=$limit) break;
			$counter++;
			
			$output .= "<item>";
			$output .= "\n\t<channel>facebook</channel>";	
			$output .= "\n\t<id>"; if(isset($p->id)) $output .= $p->id; $output .= '</id>';
			$output .= "\n\t<created>".$this->transformDate($p->created_time,'c').'</created>';
			$output .= "\n\t<updated>"; if(isset($p->updated_time)) $output .= $this->transformDate($p->updated_time); $output .='</updated>';
			$output .= "\n\t<content></content>";
			$output .= "\n\t<author>";
				$output .= "\n\t\t<id>"; if(isset($p->from->id)) $output .= $p->from->id; $output .='</id>';
				$output .= "\n\t\t<name>"; if(isset($p->from->name)) $output .= $this->cdata($p->from->name); $output .='</name>';
				$output .= "\n\t\t<link>"; if(isset($p->from->id)) $output .= "http://www.facebook.com/".$p->from->id; $output .='</link>';
				$output .= "\n\t\t<avatar>"; if(isset($p->from->id)) $output .= "https://graph.facebook.com/".$p->from->id.'/picture'; $output .='</avatar>';		 			
			$output .= "\n\t</author>";
			$output .= "\n\t<location></location>";
			$output .= "\n\t<extras>";
				$output .= "\n\t\t<facebook>";
					$output .= "\n\t\t\t<type>"; $output .= $type; $output .= '</type>';	
					$output .= "\n\t\t\t<source>"; if(isset($video_src)) $output .= $this->cdata($video_src); $output .= '</source>';	
					$output .= "\n\t\t\t<caption>"; if(isset($p->caption)) $output .= $this->cdata($p->caption); $output .= '</caption>';	
					$output .= "\n\t\t\t<description>"; if(isset($p->description)) $output .= $this->cdata($p->description); $output .= '</description>';
					$output .= "\n\t\t\t<picture>"; if(isset($p->picture)) $output .= $this->cdata($p->picture); $output .= '</picture>';
					$output .= "\n\t\t\t<link>"; if(isset($p->link)) $output .= $this->cdata($p->link); $output .= '</link>';
					$output .= "\n\t\t\t<name>"; if(isset($p->name)) $output .= $this->cdata($p->name); $output .= '</name>';	
					$output .= "\n\t\t\t<message>"; if(isset($p->message)) $output .= $this->cdata($p->message); $output .= '</message>';						
					$output .= "\n\t\t\t<privacy>";
						$output .= "\n\t\t\t\t<description>"; if(isset($p->privacy->description)) $output .= $this->cdata($p->privacy->description); $output .='</description>';
						$output .= "\n\t\t\t\t<value>"; if(isset($p->privacy->value)) $output .= $p->privacy->value; $output .='</value>';
					$output .= "\n\t\t\t</privacy>";
					$output .= "\n\t\t\t<count>";
						$output .= "\n\t\t\t\t<comments>"; $output .= isset($p->comments->count) ? $p->comments->count : '0'; $output .='</comments>';
						$output .= "\n\t\t\t\t<likes>"; $output .= isset($p->likes->count) ? $p->likes->count : '0'; $output .='</likes>';
						$output .= "\n\t\t\t\t<shares>"; $output .= isset($p->shares->count) ? $p->shares->count : '0'; $output .='</shares>';
					$output .= "\n\t\t\t</count>";
					$output .= "\n\t\t\t<story>"; if(isset($p->story)) $output .= $this->cdata($p->story); $output .= '</story>';			
					$output .= "\n\t\t\t<icon>"; if(isset($p->icon)) $output .= $this->cdata($p->icon); $output .='</icon>';
					$output .= "\n\t\t\t<object_id>"; if(isset($p->object_id)) $output .= $p->object_id; $output .='</object_id>';
					
					$output .= "\n\t\t\t<images>";
						 if(isset($object_json->images[0]->source)) $output .= "\n\t\t\t\t<image_2048>".$this->cdata($object_json->images[0]->source)."</image_2048>";
						 if(isset($object_json->images[1]->source)) $output .= "\n\t\t\t\t<image_960>".$this->cdata($object_json->images[1]->source)."</image_960>";
						 if(isset($object_json->images[2]->source)) $output .= "\n\t\t\t\t<image_720>".$this->cdata($object_json->images[2]->source)."</image_720>";
						 if(isset($object_json->images[3]->source)) $output .= "\n\t\t\t\t<image_600>".$this->cdata($object_json->images[3]->source)."</image_600>";
						 if(isset($object_json->images[4]->source)) $output .= "\n\t\t\t\t<image_480>".$this->cdata($object_json->images[4]->source)."</image_480>";
						 if(isset($object_json->images[5]->source)) $output .= "\n\t\t\t\t<image_320>".$this->cdata($object_json->images[5]->source)."</image_320>";
						 if(isset($object_json->images[8]->source)) $output .= "\n\t\t\t\t<image_130>".$this->cdata($object_json->images[8]->source)."</image_130>";
					$output .= "\n\t\t\t</images>";
					
					$output .= "\n\t\t\t<event>";
						if($type=='event'){
							if(isset($event_json->name)){
								$output .= "\n\t\t\t\t<name>".$this->cdata($event_json->name)."</name>";
								if(isset($event_json->description)) $output .= "\n\t\t\t\t<description>".$this->cdata($event_json->description)."</description>";
								if(isset($event_json->start_time)) $output .= "\n\t\t\t\t<start_time>".$event_json->start_time."</start_time>";
								if(isset($event_json->end_time)) $output .= "\n\t\t\t\t<end_time>".$event_json->end_time."</end_time>";
								if(isset($event_json->location)) $output .= "\n\t\t\t\t<location>".$this->cdata($event_json->location)."</location>";
							}else{
								if(isset($p->name)) $output .= "\n\t\t\t\t<name>".$this->cdata($p->name)."</name>";
								if(isset($p->description)) $output .= "\n\t\t\t\t<description>".$this->cdata($p->description)."</description>";
							}
						}
						
						// if(isset($event_json->privacy)) $output .= "\n\t\t\t\t<privacy>".$event_json->privacy."</privacy>";
					$output .= "\n\t\t\t</event>";
					
					
					$output .= "\n\t\t\t<application>";
						$output .= "\n\t\t\t\t<name>"; if(isset($p->application->name)) $output .= $this->cdata($p->application->name); $output .='</name>';
						$output .= "\n\t\t\t\t<id>"; if(isset($p->application->id)) $output .= $p->application->id; $output .='</id>';
					$output .= "\n\t\t\t</application>";
					$output .= "\n\t\t\t<comments>";
					
						if(isset($p->comments->data) && count($p->comments->data)>0){
							for($c=0;$c<count($p->comments->data);$c++){
								$output .= "\n\t\t\t\t<comment>";
								$output .= "\n\t\t\t\t\t<author>";
									$output .= "\n\t\t\t\t\t\t<id>".$p->comments->data[$c]->from->id."</id>";
									$output .= "\n\t\t\t\t\t\t<name>".$this->cdata($p->comments->data[$c]->from->name)."</name>";
									$output .= "\n\t\t\t\t\t\t<link>http://www.facebook.com/profile.php?id=".$p->comments->data[$c]->from->id."</link>";
									$output .= "\n\t\t\t\t\t\t<avatar>https://graph.facebook.com/".$p->comments->data[$c]->from->id."/picture</avatar>";
								$output .= "\n\t\t\t\t\t</author>";
								$output .= "\n\t\t\t\t\t<content>".$this->cdata($p->comments->data[$c]->message)."</content>";
								$output .= "\n\t\t\t\t\t<created>".$p->comments->data[$c]->created_time."</created>";
								$output .= "\n\t\t\t\t</comment>";
							}
						}
					
					$output .= "\n\t\t\t</comments>";
				$output .= "\n\t\t</facebook>";
			$output .= "\n\t</extras>";
			$output .= "\n</item>\n";	
			

		}

		$output = "<?xml version='1.0'?>\n<nss>\n".$output."</nss>";
		
		$this->saveFile('facebook_'.$id.'.xml',$output);
		$this->saveChannelTestToFile('facebook',$id,'success');
		return 'success';
	}
	
	
/**************************************************************************
 * Read Twitter Channel
 * @since 	1.0
 * @update 	1.5
 * @todo 	HTTPS Avatar über SSL aufrufen
 **************************************************************************/
 
 	function readTwitterChannel($channel){
	
		$id 					= $channel['id'];
		$limit 					= $channel['limit'];
		$access_token 			= $channel['access_token'];
		$access_token_secret 	= $channel['access_token_secret'];
		
		if(!$limit) $limit = $this->get('default_limit');

		$connection = new TwitterOAuth($this->get('twitter_consumer_key'), $this->get('twitter_consumer_secret'), $access_token, $access_token_secret);

		$parameters['count'] = $limit;
		$parameters['screen_name'] = $id;
		$tweet = $connection->get('statuses/user_timeline',$parameters);	

		if(isset($tweet->errors)){
			return 'Twitter error '.$tweet->errors[0]->code.': '.$tweet->errors[0]->message;
		}elseif(empty($tweet)){
			return 'Twitter error: No data';
		}
		$output = '';

		foreach($tweet as $t){	
			//if($this->get('https')){	$avatar = $this->cdata("https://api.twitter.com/1/users/profile_image?screen_name=".strtolower($t->user->screen_name)."&size=normal");}
			//else{						$avatar = $this->cdata($t->user->profile_image_url);}	
			
			$avatar = $this->cdata($t->user->profile_image_url);	
			$output .= "\t<item>
			<channel>twitter</channel>
			<id>".$t->id_str."</id>
			<created>".$this->transformDate($t->created_at,'c')."</created>
			<updated>false</updated>
			<content>".$this->cdata($t->text)."</content>
			<author>
				<id>".$t->user->id_str."</id>
				<name>".$t->user->name."</name>
				<link>".$this->cdata("https://twitter.com/".$t->user->screen_name)."</link>
				<avatar>".$avatar."</avatar>
			<location>
				<adress></adress>
				<latitude></latitude>
				<longitude></longitude>
			</location>
			</author>
			<extra>
				<twitter>
					<retweeted>".$t->retweeted."</retweeted>
					<count>
						<retweet_count>".$t->retweet_count."</retweet_count>
						<friends_count>".$t->user->friends_count."</friends_count>
						<followers_count>".$t->user->followers_count."</followers_count>
						<statuses_count>".$t->user->statuses_count."</statuses_count>
					</count>
					<lang>".$t->user->lang."</lang>
				</twitter>
			</extra>
		</item>";
		}
		
		$output = "<?xml version='1.0'?>\n<nss>\n".$output."</nss>";
	
		$this->saveFile('twitter_'.$id.'.xml',$output);
		$this->saveChannelTestToFile('twitter',$id,'success');
		return 'success';
			
	}
	
/**************************************************************************
 * Read NSS Channel
 * @since 	1.0
 * @update 	1.6
 **************************************************************************/
 
 	function readChannel($channel){
		$xml = $this->readData($channel['url']);
		if(!$xml) return 'error';
		$this->saveFile('nss_'.$channel['id'].'.xml',$xml);
		$this->saveChannelTestToFile('nss',$channel['id'],'success');
		return 'success';
	}
	
/**************************************************************************
 * Test NSS
 * @since 1.1
 * @update 1.6
 **************************************************************************/
 
 	function testNSS(){
		$c = $this->get('code');
		
		if($c!=md5('nss'.$this->get('license_key').$this->get('license_version').$this->get('license_status').$this->get('license_limit').$this->get('license_sites'))){
			
			//Deprecated since 1.6 ------------------------------------
			$l = file_get_contents(NSS_CONFIG_CODE_DEPRECATED);
			if($l==md5('nss'.$this->get('license_key').$this->get('license_version').$this->get('license_status'))){
				return true;	
			}
			//die;
			
			@unlink(NSS_CONFIG_LICENSE);
			@unlink(NSS_CONFIG_CODE);
			$fh = fopen(NSS_CONFIG_ERROR, 'w');	
			fwrite($fh, "<?php if(!isset($"."nss)) die; $"."nss->set('error',2); ?>");
			fclose($fh);
			return false;
		}else{
			return true;	
		}
	}
	
 /**************************************************************************
 * Template Output: Optionale Ausgabe JS oder CSS
 * @since 1.1
 **************************************************************************/
 
 	function includeFile($file){
		$ext = substr($file,strrpos($file,'.')+1);
		switch($ext){
			case 'js':
				echo "<script type='text/javascript' src='".$this->getBaseURL().NSS_INCLUDES.$file."'></script>\n";
			break;
			case 'css':
				echo "<link href='".$this->getBaseURL().NSS_INCLUDES.$file."' type='text/css' rel='stylesheet' />\n";
			break;
		}	
	}

/**************************************************************************
 * Template Output: Ausgabe aller Theme-Files
 * @since 1.1
 * @update 1.5.2
 **************************************************************************/
 
	function theme($theme=false){
		if(empty($theme)) $theme = $this->get('theme');
		else $this->set('theme',$theme);
		
		$plugins = $this->getThemeMeta('Plugins:',$theme);
		$masonry = strpos($plugins,'masonry') ? 'true' : 'false';
		$fb_app_id = $this->get('fb_app_id');
		$intro_fadein = $this->get('intro_fadein');
		$auto_refresh = $this->get('cache_auto_refresh') ? 'true' : 'false';
		
		echo "<script type='text/javascript' src='".$this->getBaseURL().NSS_CORE."jquery.neosmart.stream.js'></script>\n";
		echo "<link href='".$this->getBaseURL().NSS_CORE."core.css' type='text/css' rel='stylesheet' />\n";
		echo "<link href='".$this->getBaseURL().NSS_CONTENT.'themes/'.$theme."/style.css' type='text/css' rel='stylesheet' />\n";
		echo "<script type='text/javascript'>(function(window){window.onload=function(){jQuery(function(){jQuery('#nss').neosmartStream({introFadeIn:".$intro_fadein.",masonry:".$masonry.",cache_time:".$this->get('cache_time').",theme:'".$theme."',path:'".$this->getBaseURL()."',channel_group:'".$this->get('channel_group')."',auto_refresh:".$auto_refresh.",auto_refresh_time:".$this->get('cache_auto_refresh_time')."})})}})(window);</script>\n";
	}
	
	function themeWordpress($theme=false){
		if(empty($theme)) $theme = $this->get('theme');
		else $this->set('theme',$theme);
		
		$plugins = $this->getThemeMeta('Plugins:',$theme);
		$masonry = strpos($plugins,'masonry') ? 'true' : 'false';	
		$auto_refresh = $this->get('cache_auto_refresh') ? 'true' : 'false';	
		
		wp_enqueue_style('neosmart-stream-core',NSS_WP_URL.NSS_CORE.'core.css',array(),false,'screen');
		wp_enqueue_style('neosmart-stream',NSS_WP_URL.'nss-content/themes/'.$theme.'/style.css',array('neosmart-stream-core'),false,'screen');
		wp_enqueue_script('jquery');
		if($masonry) wp_enqueue_script('jquery-masonry',NSS_WP_URL.'nss-includes/jquery-masonry.js',array('jquery'),'2.1.6');
		wp_enqueue_script('neosmart-stream',NSS_WP_URL.'nss-core/jquery.neosmart.stream.js',array('jquery'),'1.1');
		
		return "";
	}


/**************************************************************************
 * STREAM CSS + JS
 * @since 1.6
 **************************************************************************/
 	
	public function streamCSS($theme=false){
		if(empty($theme)) $theme = $this->get('theme');
		else $this->set('theme',$theme);
		
		echo "<link href='".$this->getBaseURL().NSS_CORE."core.css' type='text/css' rel='stylesheet' />\n";
		echo "<link href='".$this->getBaseURL().NSS_CONTENT.'themes/'.$theme."/style.css' type='text/css' rel='stylesheet' />\n";
	}
	
	public function streamJS(){
		$theme = $this->get('theme');
		
		$plugins = $this->getThemeMeta('Plugins:',$theme);
		$masonry = strpos($plugins,'masonry') ? 'true' : 'false';
		$fb_app_id = $this->get('fb_app_id');
		$intro_fadein = $this->get('intro_fadein');
		$auto_refresh = $this->get('cache_auto_refresh') ? 'true' : 'false';
		
		echo "<script type='text/javascript' src='".$this->getBaseURL().NSS_CORE."jquery.neosmart.stream.js'></script>\n";
	}

	
/**************************************************************************
 * Überprüfung auf Updates
 * @since 1.2
 **************************************************************************/
 	
	public function checkForUpdate(){

		$file = NSS_ABSPATH."nss-content/cache/latest_version.txt";
		$ft = @filemtime($file);
		$day = 60*60*24*1;
		
		if(!$ft || $ft+$day<time()){
			$license = $this->apiRequest('latest_version');
			
			if($license->type=='latest_version'){
				$version = $license[0]->message;
				$fh = @fopen($file, 'w');
				if($fh){
					fwrite($fh, $version);
					fclose($fh);	
				}
				else{
					//File error	
				}
				return true;
			}elseif($license->type=='error' && $license->status==5){
				$this->logError(5);
				return false;
			}else{
				//Server error	
				return false;
			}
		}
		return true;
	}

/**************************************************************************
 * API-Kommunikation
 * @since 1.2
 * @update 1.3.1: readData verwenden
 **************************************************************************/
 
	public function apiRequest($action,$key=false,$extra=array()){
		if(!$key) $key = $this->get('license_key');
		$query = NSS_API_URL.'index.php?key='.$key
			.'&site='.$_SERVER['HTTP_HOST'].'&action='.$action.'&https='.$this->get('https')
			.'&return_url='.urlencode($this->getBaseURL()).'&request_url='.urlencode($_SERVER['REQUEST_URI']);
		//echo $query;
		foreach($extra as $key => $value) $query .= '&'.$key.'='.$value;
		$response = $this->readData($query);
		if(!$response||$response=='error'){
			$response = "<data><type>error</type><status>33</status><message>PHP cURL does not work properly (empty response). Please check your server configuration.</message></data>";
		}
		$response = new SimpleXMLElement($response);
		return $response;
	}

/**************************************************************************
 * Error Log
 * @since 1.2
 **************************************************************************/
 
	public function logError($errorCode){
		$fh = fopen(NSS_CONFIG_ERROR, 'w');	
		fwrite($fh, "<?php $"."nss->set('error',".$errorCode."); ?>");
		fclose($fh);
		switch($errorCode){
			case 5:
				@unlink(NSS_CONFIG_LICENSE);
				@unlink(NSS_CONFIG_CODE);	
			break;	
		}
	}


/**************************************************************************
 * Little Helpers ...
 * @since 1.0
 * @update 1.5.1
 **************************************************************************/
 
	function transformDate($date,$format='auto') {
		$time = strtotime($date);
		if($format=='auto') $format = $this->get('date_time_format');
		if($format=='iso'){
			return $date;
		}if($format=='c'){
			return date($format, $time);
		}else{
			return strftime($format, $time);
		}
	}
	
	function autoLink($string) {
		$pattern = "/((((http[s]?:\/\/)|(ftp[s]?:\/\/)|(www\.))([a-z][-a-z0-9]*\.)?)[-a-z0-9]+\.[a-z]+(\/[a-z0-9._\/~#&=;%+?-]*)*)/is";
		$string = preg_replace($pattern, " <a href='$1' target='_blank' rel='nofollow'>$1</a>", $string);
		$string = preg_replace("/href='www/", "href='http://www", $string);
		return $string;
	}
	
	function escapeString($str){
		$str = str_replace('&amp;','&',$str);
		return htmlspecialchars($str);
	}
	
	function cdata($string){
		if(strlen($string)==0) return '';
		$string = $this->escapeString($string);
		$nl = array('/\r\n/',"/\n/", "/\r/");
		$re = '<br/>';
		return "<![CDATA[".preg_replace($nl,$re,$string)."]]>";
	}
	
/*****************************************************************************************
 * ADMIN AREA
 * @since 1.6.0
 *****************************************************************************************/


/*****************************************************************************************
 * Update
 *****************************************************************************************/
  
 	public function downloadLatest(){
		//Download
		return copy(NSS_UPDATE_FILE,NSS_CONTENT_UPDATE.'latest.zip');
	}
 
	public function installLatest(){
		$zip = new ZipArchive;
		if($zip->open(NSS_CONTENT_UPDATE.'latest.zip') === TRUE){			
			$files = array();
			for($i = 0; $i < $zip->numFiles; $i++) {
				$entry = $zip->getNameIndex($i);
				if(strstr($entry,'neosmart-stream/')){
					array_push($files,$entry);
				}
			}
			$zip->extractTo(NSS_CONTENT_UPDATE, array_slice($files,1));
			$zip->close();
			
			//Alte Dateien löschen
			$this->cleanDir(NSS_ABSPATH.NSS_ADMIN);
			$this->cleanDir(NSS_ABSPATH.NSS_CORE);
			$this->cleanDir(NSS_ABSPATH.NSS_INCLUDES);			
			
			//Kopieren
			$this->copyAll(NSS_CONTENT_UPDATE.'neosmart-stream/',substr(NSS_ABSPATH,0,-1));
			
			//Temp. Dateien löschen
			$this->cleanDir(NSS_CONTENT_UPDATE);
			return "success";
		}
		else{
			//DOWNLOAD FEHLGESCHLAGEN
			return "error";
		}
	}
	
	function copyAll($path,$dst){
		$dir = opendir($path);
		if(!is_dir($dst))@mkdir($dst);
		while(false !== ( $file = readdir($dir))){
			if(($file != '.') && ($file != '..')){
				if(is_dir($path . '/' . $file)){
					$this->copyAll($path . '/' . $file,$dst . '/' . $file);
				}else{
					copy($path . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
	
/*****************************************************************************************
 * Pro
 *****************************************************************************************/
 
	public function isPro(){
		if($this->get('license_version')=='pro'){
			if($this->hostIsPartOfSiteArray()){
				return true;	
			}
			$this->set('license_name','neosmart STREAM Lite');
			$this->set('license_version','lite');
		}
		if($this->get('license_owner')) $this->set('license_owner','');
		return false;
	}
	
/*****************************************************************************************
 * Installation
 *****************************************************************************************/
 
	function getLastFolder(){
		$url 	= $_SERVER['SCRIPT_NAME'];
		$pos 	= strrpos($url,'/index.php');
		$path 	= substr($url,0,$pos);
		$sPos	= strrpos($path,'/')+1;
		$folder = substr($path,$sPos);
		return $folder;
	}
	
/****************************************************************************
* Dateizugriffsrechte überprüfen
*****************************************************************************/

	function testFilePermissions(){
		
		$errors = array();
		
		if(!is_dir(NSS_ABSPATH.NSS_CONFIG)){ 	mkdir(NSS_ABSPATH.NSS_CONFIG, 0775);}
		if(!is_dir(NSS_CONTENT_CACHE)){ 		mkdir(NSS_CONTENT_CACHE, 0775);}
		if(!is_dir(NSS_CONTENT_UPDATE)){ 		mkdir(NSS_CONTENT_UPDATE, 0775);}
		
		if(!is_writable(NSS_ABSPATH.NSS_CONFIG)){
			array_push($errors,
				array(	'<b>'.NSS_ABSPATH.NSS_CONFIG.'</b> is not writeable',
						'Set permission for <b>nss-config</b> to <b>0755</b> (chmod)'
				)
			);
		}

		if(!is_writable(NSS_CONTENT_CACHE)){
			array_push($errors,
				array(	'<b>'.NSS_CONTENT_CACHE.'</b> is not writeable',
						'Set permission for <b>cache</b> to <b>0755</b> (chmod)'
				)
			);
		}
		
		if(!is_writable(NSS_CONTENT_UPDATE)){
			array_push($errors,
				array(	'<b>'.NSS_CONTENT_UPDATE.'</b> is not writeable',
						'Set permission for <b>update</b> to <b>0755</b> (chmod)'
				)
			);
		}
		
		if(count($errors)) return $errors;
		
		$files = array(
			NSS_CONFIG_BASE_URL,
			NSS_CONFIG_CHANNELS,
			NSS_CONFIG_GROUPS,
			NSS_CONFIG_CONFIG,
			NSS_CONFIG_PROXY,
			NSS_CONFIG_THEME,
			NSS_CONFIG_LICENSE,
			NSS_CONFIG_PASSWORD,
			NSS_CONFIG_TRANSLATE,
			NSS_CONFIG_FEEDBACK
		);

		foreach($files as $f){
			if(!file_exists($f)){
				$fh = fopen($f, 'w');
				if($fh){
					fwrite($fh, '');
					fclose($fh);
					chmod($f, 0775);
				}else{
					//Error
				}
			}
			$perm = substr(sprintf('%o', fileperms($f)), -4);
			if($perm!='0777'&&$perm!='0775'&&$perm!='0755'){
				chmod($f,0755);
			}
		}
		return false;
	}
	

/****************************************************************************
* Dateizugriffsrechte überprüfen
*****************************************************************************/

	function testServerSettings(){
		$fopen = ini_get('allow_url_fopen');
		if($fopen!=1 && $fopen!='On')
			return array('allow_url_fopen is disabled','Enable <b>allow_url_fopen</b> in your <b>php.ini</b>');
		//if(ini_get('openssl')!=1)
			//return array('openssl is disabled','Enable <b>openssl</b> in your <b>php.ini</b>');
		return false;
	}

 
 
/*****************************************************************************************
 * Functions
 *****************************************************************************************/
 	
	function activatePluginMode($plugin){
		$fh = fopen(NSS_CONFIG_PLUGIN, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss))die;";
		$data .= "\n$"."nss->set('plugin_mode','".$plugin."');";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
	}
	
	function removeLicenseKey(){

		@unlink(NSS_CONFIG_LICENSE);
		@unlink(NSS_CONFIG_ERROR);
		@unlink(NSS_CONFIG_CODE);
		$this->cleanDir(NSS_CONTENT_CACHE);
		//unset($_SESSION['nss_admin_password']);
		//$this->redirectTo('?error=3');
		$this->reload();
	}
	
	public function cleanCache(){
		$this->cleanDir(NSS_CONTENT_CACHE);
	}
	
	function redirectTo($path){
		header('Location: '.$this->getBaseURL().$path);
		die;
	}
	
	function saveBoolean($key){
		if(empty($_POST[$key])) return 'false';
		if(trim($_POST[$key])=='') return 'false';
		else return 'true';
	}

	function updateConfig(){
		$config_file = NSS_ABSPATH."nss-config/nss-config.php";
		$fadein = intval($_POST['intro_fadein']);
		$facebook_internal_limit = max(intval($_POST['facebook_internal_limit']),3);
		$cache_time = max(intval($_POST['cache_time']),10);
		$cache_auto_refresh_time = max(intval($_POST['cache_auto_refresh_time']),30);
		$cache_time_profile = max(intval($_POST['cache_time_profile']),60);
		$fh = fopen($config_file, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss)) die;";
		$data .= "\n$"."nss->set('debug_mode',".$this->saveBoolean('debug_mode').");";
		$data .= "\n$"."nss->set('show_admin_link',".$this->saveBoolean('show_admin_link').");";
		$data .= "\n$"."nss->set('cache_time','".$cache_time."');";
		$data .= "\n$"."nss->set('cache_auto_refresh',".$this->saveBoolean('cache_auto_refresh').");";
		$data .= "\n$"."nss->set('cache_auto_refresh_time','".$cache_auto_refresh_time."');";
		$data .= "\n$"."nss->set('locale_time','".trim($_POST['locale_time'])."');";
		$data .= "\n$"."nss->set('cache_time_profile','".$cache_time_profile."');";
		$data .= "\n$"."nss->set('date_time_format','".trim($_POST['date_time_format'])."');";
		$data .= "\n$"."nss->set('intro_fadein','".$fadein."');";
		$data .= "\n$"."nss->set('facebook_blacklist',\"".trim(str_replace('"',"'",$_POST['facebook_blacklist']))."\");";
		$data .= "\n$"."nss->set('facebook_internal_limit','".$facebook_internal_limit."');";
		$data .= "\n$"."nss->set('twitter_consumer_key','".trim($_POST['twitter_consumer_key'])."');";
		$data .= "\n$"."nss->set('twitter_consumer_secret','".trim($_POST['twitter_consumer_secret'])."');";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
		$this->cleanCache();
		$this->reload('?saved=1');
	}
	
	function updateProxy(){
		$config_file = NSS_ABSPATH."nss-config/nss-proxy.php";
		$fh = fopen($config_file, 'w');
		$data = '<?php ';
		$data .= "\ndefine('NSS_PROXY','".trim($_POST['proxy'])."');";
		$data .= "\ndefine('NSS_PROXY_PORT',".intval($_POST['proxy_port']).");";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
		$this->reload('?saved=1');
	}
	
	function updateFeedback(){
		$file = NSS_ABSPATH."nss-config/nss-feedback.php";	
		$fh = fopen($file, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss)) die;";
		$data .= "\n$"."nss->set('fb_api_lang','".trim($_POST['fb_api_lang'])."');";
		$data .= "\n$"."nss->set('feedback_header',".$this->saveBoolean('feedback_header').");";
		$data .= "\n$"."nss->set('feedback_header_fb_like',".$this->saveBoolean('feedback_header_fb_like').");";
		$data .= "\n$"."nss->set('feedback_header_fb_send',".$this->saveBoolean('feedback_header_fb_send').");";
		$data .= "\n$"."nss->set('feedback_header_fb_post',".$this->saveBoolean('feedback_header_fb_post').");";
		$data .= "\n$"."nss->set('feedback_item',".$this->saveBoolean('feedback_item').");";
		$data .= "\n$"."nss->set('feedback_item_fb_like',".$this->saveBoolean('feedback_item_fb_like').");";
		$data .= "\n$"."nss->set('feedback_item_fb_comment',".$this->saveBoolean('feedback_item_fb_comment').");";
		$data .= "\n$"."nss->set('feedback_header_twitter_follow',".trim($_POST['feedback_header_twitter_follow']).");";
		$data .= "\n$"."nss->set('feedback_item_retweet',".trim($_POST['feedback_item_retweet']).");";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
		$this->cleanCache();
		$this->reload('?saved=1');
	}
	
	function updateTheme(){
		$file = NSS_ABSPATH."nss-config/nss-theme.php";
		$fh = fopen($file, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss)) die;";
		$data .= "\n$"."nss->set('theme','".trim($_POST['theme'])."');";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
		$this->reload();
	}
	
	function updatePassword($password,$reload=true,$md5=true){
		
		$password = trim($password);
		if(strlen($password)<3){
			return 'Unsafe password';
		}
		if($md5) $password = md5($password);
		
		$config_file = NSS_ABSPATH."nss-config/nss-password.php";
		$fh = fopen($config_file, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss)) die;";
		$data .= "\n//DON'T EDIT THIS FILE";
		$data .= "\n$"."nss->set('admin_password','".$password."');";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
		$_SESSION['nss_admin_password'] = $password;
		
		if($reload) $this->reload('?saved=1');
	}
	
	function updateChannels(){
		$fh = fopen(NSS_CONFIG_CHANNELS, 'w');
		$data = "<?php \n";
		$data .= "if(!isset($"."nss)) die;";
		$data .= stripslashes($_POST['channels']);
		$data .= "?>";
		fwrite($fh, $data);
		fclose($fh);
		$this->cleanCache();
		die('CHANNELS_SAVED');
	}
	
	function updateGroups($groups){
		$fh = fopen(NSS_CONFIG_GROUPS, 'w');
		$data = "<?php \n";
		$data .= "if(!isset($"."nss)) die;\n";
		$data .= $groups;
		$data .= "?>";
		fwrite($fh, $data);
		fclose($fh);
		die('GROUPS_SAVED');
	}
	
	function updateTranslation(){
		$file = NSS_ABSPATH."nss-config/nss-translate.php";
		$fh = fopen($file, 'w');
		$data = '<?php ';
		$data .= "if(!isset($"."nss)) die;";
		$data .= "\n$"."nss->set('error_no_data','".trim($_POST['error_no_data'])."');";
		$data .= "\n?>";
		fwrite($fh, $data);
		fclose($fh);
		$this->reload('?saved=1');
	}
	
	function is_logged_in($allow_default=true){
		if($this->is_default_password($this->get('admin_password')) && $allow_default){
			$_SESSION['nss_admin_password'] = md5('admin');			
		}
		elseif(empty($_SESSION['nss_admin_password'])){
			return false;	
		} 
		$state = $_SESSION['nss_admin_password'] == $this->get('admin_password');
		return $state;
	}
	
	function is_default_password($admin_password){
		return $admin_password == '21232f297a57a5a743894a0e4a801fc3';	//md5 Hash of 'admin'
	}
	
	function reload($params=''){
		header('Location: '.$_SERVER['PHP_SELF'].$params);
		die;
	}
	
	function cl(){
		if(!$this->testNSS()){
			$this->apiRequest('file_conflict');
			header('Location: '.$this->getNssRoot().'?error=2');
			die;
		}else{
			return false;	
		}
	}
	
	function afl(){
		if(filemtime(NSS_ABSPATH."nss-config/nss-license.php")	== intval(file_get_contents(NSS_CONFIG_CODE))) return true;
		else false;
	}

/****************************************************************************
* Check for Updates once a day
*****************************************************************************/
	
	function is_update_available(){
		if(!$this->checkForUpdate()) return false;
		
		$file = NSS_ABSPATH."nss-content/cache/latest_version.txt";
		$latest_version = @file_get_contents($file);
		$current_version = $this->get('version');		
		$output = '(Version '.$current_version.')';
		
		$v = explode('.',$latest_version);
		$sv = array(intval($v[0]),intval($v[1]),intval($v[2])); 
		$cv = array($this->get('version_major'),$this->get('version_minor'),$this->get('version_revision'));		
		
		if(isset($latest_version) && 
		(
			$sv[0]>$cv[0] 
			|| ($sv[0]==$cv[0] && $sv[1]>$cv[1])
			|| ($sv[0]==$cv[0] && $sv[1]==$cv[1] && $sv[2]>$cv[2])
		)){
			return '<b>Update Info: <a target="_blank" href="'.$this->get('nss_website').'downloads/">neosmart STREAM '.$latest_version.'</a> is available!</b> <a href="'. $this->getNssRoot().'nss-admin/update.php" class="submit">Update here</a>';	
		}
		return false;
	}
	
/****************************************************************************
* Get Channel Status
*****************************************************************************/
	
	function getChannelStatus($type,$id){
		$status = @file_get_contents(NSS_ABSPATH."nss-content/cache/".$type.'_'.$id.'_status.xml');
		if(!$status) return '<span class="warning status">untested</span>';
		return $status;
	}

/****************************************************************************
* Admin Login
*****************************************************************************/
	
	function adminLogin(){
		$_SESSION['nss_admin_password'] = md5($_POST['admin_password']);
		if($this->is_logged_in()){
			header('Location: nss-admin/');
			die;
		}else{
			return 'Wrong Password';	
		}
	}
	
	function dynAdminLogin(){
		if(isset($_GET['dynpw'])){
			$_SESSION['nss_admin_password'] = $_GET['dynpw'];
			return true;
		}
		return false;
	}
	
/****************************************************************************
* Add Licence Key
*****************************************************************************/
	
	function addLicenseKey($key){

		$key = trim($key);
		
		if(strlen($key)!=19){
			return 'Error: This license key is invalid';
		}
		$license = $this->apiRequest('validate_key',$key);	
		return $this->saveLicense($license);
	}

/****************************************************************************
* Site deaktivieren
*****************************************************************************/

	public function deactivateSite($site){
		$extra = array('dsite'=>urlencode($site));
		$license = $this->apiRequest('deactivate_site',false,$extra);
		return $this->saveLicense($license);
	}

/****************************************************************************
* Lizenz speichern
* @update: 1.6 - code wird nun durch verify ersetzt
*****************************************************************************/

	private function saveLicense($license){
		//Success
		if(!empty($license) && $license->type=='license'){
			
			$fh = fopen(NSS_CONFIG_LICENSE, 'w');
			
			$data = '<?php ';
			$data .= "if(!isset($"."nss))die;";
			$data .= "\n//DON'T EDIT THIS FILE";
			$data .= "\n$"."nss->set('license_status','".$license->status."');";
			$data .= "\n$"."nss->set('license_name','".$license->name."');";
			$data .= "\n$"."nss->set('license_owner','".$license->owner."');";
			$data .= "\n$"."nss->set('license_key','".$license->key."');";
			$data .= "\n$"."nss->set('license_limit','".$license->limit."');";
			$data .= "\n$"."nss->set('license_sites','".$license->sites."');";
			$data .= "\n$"."nss->set('license_message','".$license->message."');";
			$data .= "\n$"."nss->set('license_version','".$license->version."');";
			$data .= "\n?>";
			
			fwrite($fh, $data);
			fclose($fh);
			
			$fh = fopen(NSS_CONFIG_CODE, 'w');
			$data = '<?php ';
			$data .= "if(!isset($"."nss))die;";
			//$data .= "\n$"."nss->set('code','".$license->code."');";
			$data .= "\n$"."nss->set('code','".$license->verify."');";
			$data .= "\n?>";
			
			fwrite($fh, $data);
			fclose($fh);
			
			@unlink(NSS_CONFIG_ERROR);
			//die('end > saveLicense');
			$this->reload();
		}
		
		//Error
		if(!empty($license) && $license->type=='error'){
			return 'Error: '.$license->message;
		}
		
		//No Connection
		return 'Error: The API is not available yet or the settings on your server are wrong. Please read the documentation or try again later.';
	}


/****************************************************************************
* Total Reset
*****************************************************************************/

	function totalReset(){
		$this->cleanDir();
		$this->cleanDir(NSS_ABSPATH.NSS_CONFIG);
		$this->removeLicenseKey();
	}
	

 
}
?>