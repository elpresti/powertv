<?php
	$current_page = 'config';
	include "header.inc.php";	
?>

	<h2 id="marker-config">Configuration</h2>
	<form class="nss-admin-form" method="post">
		<input type="hidden" name="action" value="update_config">
		<input type='hidden' name='nss_root' value='<?php echo $nss->getNssRoot(); ?>'>
		<div class='row'>
			<label>Debug mode</label>
			<div class="field-area">
				<input type="radio" value="1" name="debug_mode" <?php if($nss->get('debug_mode')===true) echo "checked='checked'"; ?>/> On
				<input type="radio" value="0" name="debug_mode" <?php if($nss->get('debug_mode')===false) echo "checked='checked'"; ?>/> Off
				<div class="field-info">If debug mode is on, you see warnings and errors on top of your stream.</div>
			</div>
		</div>
		<div class='row hl'>
			<label>Admin Link</label>
			<div class="field-area">
				<input type="radio" value="1" name="show_admin_link" <?php if($nss->get('show_admin_link')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="show_admin_link" <?php if($nss->get('show_admin_link')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display an admin link at the bottom of your stream?</div>
			</div>
		</div>
		<h3>Date / Time</h3>
		<div class='row'>
			<label>Locale time</label>
			<div class="field-area">
				<input name='locale_time' type='text' class='text' value='<?php echo $nss->get('locale_time'); ?>'>
				<div class="field-info">Set locale information via PHP <a href="http://www.php.net/manual/en/function.setlocale.php" target="_blank">setlocale</a>.<br /><b>Default: en_US</b></div>
			</div>
		</div>
		<div class='row hl'>
			<label>Date format</label>
			<div class="field-area">
				<input name='date_time_format' type='text' class='text' value='<?php echo $date_time_format; ?>'>
				<div class="field-info">Set date/time format via PHP <a href="http://php.net/manual/en/function.strftime.php" target="_blank">strftime</a>.
					<br /><b>Default: %d %B %Y, %H:%M</b></div>
			</div>
		</div>
		<h3>Cache time</h3>
		<div class='row'>
			<label>Cache time</label>
			<div class="field-area">
				<input name='cache_time' type='number' class='text' min="10" value='<?php echo $cache_time; ?>'>
				<div class="field-info">Time to wait in seconds before neosmart STREAM trys to refresh the cache on page reload.<br /><b>Default: 60</b> (once a minute, minimum: 10)</div>
			</div>
		</div>
		<div class='row hl'>
			<label>Auto-refresh</label>
			<div class="field-area">
				<input type="radio" value="1" name="cache_auto_refresh" <?php if($nss->get('cache_auto_refresh')===true) echo "checked='checked'"; ?>/> On
				<input type="radio" value="0" name="cache_auto_refresh" <?php if($nss->get('cache_auto_refresh')===false) echo "checked='checked'"; ?>/> Off
				<div class="field-info">Would you like that neosmart STREAM trys to refresh the cache without page reload? (AJAX request)</div>
			</div>
		</div>
		<div class='row'>
			<label>Auto-refresh time</label>
			<div class="field-area">
				<input name='cache_auto_refresh_time' type='number' class='text' min="30" value='<?php echo $cache_auto_refresh_time; ?>'>
				<div class="field-info">Time to wait in seconds before neosmart STREAM trys to refresh the cache without page reload. (AJAX request)<br /><b>Default: 60</b> (once a minute, minimum: 30)</div>
			</div>
		</div>
		<div class='row hl'>
			<label>Profile cache</label>
			<div class="field-area">
				<input name='cache_time_profile' type='number' class='text' min="60" value='<?php echo $nss->get('cache_time_profile'); ?>'>
				<div class="field-info">Time to wait in seconds, before neosmart STREAM trys to refresh the profile cache.<br /><b>Default: 86400</b> (once a day, minimum: 60)</div>
			</div>
		</div>
		<h3>Animation time</h3>
		<div class='row'>
			<label>Intro</label>
			<div class="field-area">
				<input name='intro_fadein' type='number' class='text' min="0" value='<?php echo $nss->get('intro_fadein'); ?>'>
				<div class="field-info">Animation time in milliseconds for fadeIn your stream.<br /><b>Default: 700</b></div>
			</div>
		</div>
		<h3>Facebook settings</h3>
		<div class='row'>
			<label>Blacklist</label>
			<div class="field-area">
				<textarea name='facebook_blacklist' class='text'><?php echo $nss->get('facebook_blacklist'); ?></textarea>
				<div class="field-info">Hide posts which contains a word of this blacklist (comma separated). You can use this option to prevent Facebook from showing status updates in your stream.
					<br /><b>Default: 'likes a post, on their own, likes their own, person who shared it may not have permission to share it with you, are now friends, likes a photo, is now friends with'</b></div>
			</div>
		</div>
		<div class='row'>
			<label>Internal limit</label>
			<div class="field-area">
				<input name='facebook_internal_limit' type='number' class='text' min="3" max="50" value='<?php echo $nss->get('facebook_internal_limit'); ?>'>
				<div class="field-info">Internal limit of Facebook posts to load. This limit must be higher than your "real" limit which you set in your channel configuration because some filter operations like the <b>blacklist</b> remove posts without increasing the "real" limit.<br /><b>Default: 30</b></div>
			</div>
		</div>
		<h3 id="twitter-settings">Twitter settings</h3>
		<div class='row'>
			<label>Consumer key</label>
			<div class="field-area">
				<input name='twitter_consumer_key' type="text" class='text' value="<?php echo $nss->get('twitter_consumer_key'); ?>" />
				<div class="field-info"><a href="https://dev.twitter.com/apps/new" target="_blank">Create a Twitter app</a> and enter your consumer key.</div>
			</div>
		</div>
		<div class='row'>
			<label>Consumer secret</label>
			<div class="field-area">
				<input name='twitter_consumer_secret' type='text' class='text' value='<?php echo $nss->get('twitter_consumer_secret'); ?>'>
				<div class="field-info"><a href="https://dev.twitter.com/apps/new" target="_blank">Create a Twitter app</a> and enter your consumer secret.</div>
			</div>
		</div>
		
		<div class='row hl'>
			<a id='cancel-1' href='<?php echo $_SERVER['PHP_SELF']; ?>' class='cancel button'>Cancel changes</a>
			<input class='submit' type='submit' value='Save configuration'>
		</div>
	</form>
	
	<h2 id="marker-config">Proxy</h2>
	<form class="nss-admin-form" method="post">
		<input type="hidden" name="action" value="update_proxy">
		<div class='row'>
			<label>Proxy</label>
			<div class="field-area">
				<input name='proxy' type="text" class='text' value="<?php if(defined("NSS_PROXY")) echo NSS_PROXY; ?>" />
				<div class="field-info">If you need to use a proxy for your server, you can add your proxy here.
					<br /><b>Example: http://your-proxy.your-domain.com</b></div>
			</div>
		</div>
		<div class='row'>
			<label>Port</label>
			<div class="field-area">
				<input name='proxy_port' type="number" class='text' value="<?php if(defined("NSS_PROXY_PORT")) echo NSS_PROXY_PORT; ?>" />
				<div class="field-info">Add your proxy port here.
					<br /><b>Example: 80</b></div>
			</div>
		</div>
		<div class='row hl'>
			<a href='<?php echo $_SERVER['PHP_SELF']; ?>' class='cancel button'>Cancel changes</a>
			<input class='submit' type='submit' value='Save proxy'>
		</div>
	</form>
	
	<h2 id="marker-reset">Total reset</h2>	
	<form  class="nss-admin-form" method="post">
		<input type="hidden" name="action" value="total_reset">
		<div class='row'>
			Deactivate license key for this site, delete all configuration files, all channels and all cache files (delivery status)
			<div class="field-info">CAUTION: Process can not be undone!</div>
		</div>
		<div id="pre-reset" class='row hl'>
			<a href='javascript://' onclick="$('#total-reset').show();$('#pre-reset').hide();" class='cancel button'>Total Reset</a>
		</div>
		<div id="total-reset" class='row hl' style="display:none;">
			<div class="warning inline-status">Do you really want to do this? CAUTION: Process can not be undone!</div>
			<a href='javascript://' onclick="$('#pre-reset').show();$('#total-reset').hide();" class='cancel button'>Cancel</a>
			<input class='submit' type='submit' value='Total Reset'>
		</div>
	</form>
	
<?php
	include "footer.inc.php";
?>