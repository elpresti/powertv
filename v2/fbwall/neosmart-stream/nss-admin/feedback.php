<?php

	$current_page = 'feedback';
	include "header.inc.php";	
	
	/*$xml = simplexml_load_file('https://www.facebook.com/translations/FacebookLocales.xml');
	$fb_locales = '';
	foreach($xml->locale as $locale){
		$fb_locales .= "<br>array('".$locale->codes->code->standard->representation."','".$locale->englishName."'),";
	}
	echo 'array('.$fb_locales.')';*/

	$fb_locales = array(
		array('af_ZA','Afrikaans'),
		array('ar_AR','Arabic'),
		array('az_AZ','Azerbaijani'),
		array('be_BY','Belarusian'),
		array('bg_BG','Bulgarian'),
		array('bn_IN','Bengali'),
		array('bs_BA','Bosnian'),
		array('ca_ES','Catalan'),
		array('cs_CZ','Czech'),
		array('cy_GB','Welsh'),
		array('da_DK','Danish'),
		array('de_DE','German'),
		array('el_GR','Greek'),
		array('en_GB','English (UK)'),
		array('en_PI','English (Pirate)'),
		array('en_UD','English (Upside Down)'),
		array('en_US','English (US)'),
		array('eo_EO','Esperanto'),
		array('es_ES','Spanish (Spain)'),
		array('es_LA','Spanish'),
		array('et_EE','Estonian'),
		array('eu_ES','Basque'),
		array('fa_IR','Persian'),
		array('fb_LT','Leet Speak'),
		array('fi_FI','Finnish'),
		array('fo_FO','Faroese'),
		array('fr_CA','French (Canada)'),
		array('fr_FR','French (France)'),
		array('fy_NL','Frisian'),
		array('ga_IE','Irish'),
		array('gl_ES','Galician'),
		array('he_IL','Hebrew'),
		array('hi_IN','Hindi'),
		array('hr_HR','Croatian'),
		array('hu_HU','Hungarian'),
		array('hy_AM','Armenian'),
		array('id_ID','Indonesian'),
		array('is_IS','Icelandic'),
		array('it_IT','Italian'),
		array('ja_JP','Japanese'),
		array('ka_GE','Georgian'),
		array('km_KH','Khmer'),
		array('ko_KR','Korean'),
		array('ku_TR','Kurdish'),
		array('la_VA','Latin'),
		array('lt_LT','Lithuanian'),
		array('lv_LV','Latvian'),
		array('mk_MK','Macedonian'),
		array('ml_IN','Malayalam'),
		array('ms_MY','Malay'),
		array('nb_NO','Norwegian (bokmal)'),
		array('ne_NP','Nepali'),
		array('nl_NL','Dutch'),
		array('nn_NO','Norwegian (nynorsk)'),
		array('pa_IN','Punjabi'),
		array('pl_PL','Polish'),
		array('ps_AF','Pashto'),
		array('pt_BR','Portuguese (Brazil)'),
		array('pt_PT','Portuguese (Portugal)'),
		array('ro_RO','Romanian'),
		array('ru_RU','Russian'),
		array('sk_SK','Slovak'),
		array('sl_SI','Slovenian'),
		array('sq_AL','Albanian'),
		array('sr_RS','Serbian'),
		array('sv_SE','Swedish'),
		array('sw_KE','Swahili'),
		array('ta_IN','Tamil'),
		array('te_IN','Telugu'),
		array('th_TH','Thai'),
		array('tl_PH','Filipino'),
		array('tr_TR','Turkish'),
		array('uk_UA','Ukrainian'),
		array('vi_VN','Vietnamese'),
		array('zh_CN','Simplified Chinese (China)'),
		array('zh_HK','Traditional Chinese (Hong Kong)'),
		array('zh_TW','Traditional Chinese (Taiwan)')
	);
		
?>

	<h2 id="marker-config">Feedback  <i class="badge">Pro Version</i></h2>
	<?php if($nss->get('license_version')!='pro'){ ?>
	<div class="nss-admin-container warning">
		<div class="row"><b>Feedback features are disabled!</b></div>
		<div class="todo"><a href="license.php" target="_blank"><b>Update to Pro Version</b></a> to use all feedback features.</div>
	</div>
	<?php } ?>
	
	<form class="nss-admin-form" method="post">
		<input type="hidden" name="action" value="update_feedback">
		<input type='hidden' name='nss_root' value='<?php echo $nss->getNssRoot(); ?>'>
		<!--div class='nss-admin-form-row'>
			<label>FB API Language</label>
			<div class="field-area">
				<select name="fb_api_lang">
					<?php
						foreach($fb_locales as $locale){
							$selected = $nss->get('fb_api_lang')==$locale[0] ? ' selected="selected"' : '';
							echo '<option value="'.$locale[0].'"'.$selected.'>'.$locale[1].'</option>';
						}
					?>
				</select>
			</div>
		</div-->
		<h3>Header</h3>
		<div class='row'>
			<label>Header Feedback</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_header" <?php if($nss->get('feedback_header')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_header" <?php if($nss->get('feedback_header')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a feeback header on top of your stream?</div>
			</div>
		</div>
		<div class='row'>
			<label>FB Like Button</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_header_fb_like" <?php if($nss->get('feedback_header_fb_like')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_header_fb_like" <?php if($nss->get('feedback_header_fb_like')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Facebook Like Button?</div>
			</div>
		</div>
		<!--div class='row'>
			<label>FB Send Button*</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_header_fb_send" <?php if($nss->get('feedback_header_fb_send')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_header_fb_send" <?php if($nss->get('feedback_header_fb_send')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Facebook Send Button?<br /><b>*requires FB Like Button</b></div>
			</div>
		</div-->
		<div class='row'>
			<label>FB Post Button</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_header_fb_post" <?php if($nss->get('feedback_header_fb_post')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_header_fb_post" <?php if($nss->get('feedback_header_fb_post')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Facebook Post Button?</div>
			</div>
		</div>
		<div class='row'>
			<label>Twitter Follow Button</label>
			<div class="field-area">
				<input type="radio" value="2" name="feedback_header_twitter_follow" <?php if($nss->get('feedback_header_twitter_follow')==2) echo "checked='checked'"; ?>/> Button &amp; Count
				<input type="radio" value="1" name="feedback_header_twitter_follow" <?php if($nss->get('feedback_header_twitter_follow')==1) echo "checked='checked'"; ?>/> Button
				<input type="radio" value="0" name="feedback_header_twitter_follow" <?php if($nss->get('feedback_header_twitter_follow')==0) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Twitter Follow Button?</div>
			</div>
		</div>
		<h3>Item</h3>
		<div class='row'>
			<label>Facebook Feedback</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_item" <?php if($nss->get('feedback_item')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_item" <?php if($nss->get('feedback_item')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display feeback options for every item?</div>
			</div>
		</div>
		<!--div class='row'>
			<label>FB Like Button</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_item_fb_like" <?php if($nss->get('feedback_item_fb_like')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_item_fb_like" <?php if($nss->get('feedback_item_fb_like')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Like Button for every Facebook item?</div>
			</div>
		</div>
		<div class='row'>
			<label>FB Comment</label>
			<div class="field-area">
				<input type="radio" value="1" name="feedback_item_fb_comment" <?php if($nss->get('feedback_item_fb_comment')===true) echo "checked='checked'"; ?>/> Show
				<input type="radio" value="0" name="feedback_item_fb_comment" <?php if($nss->get('feedback_item_fb_comment')===false) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Comment Button for every Facebook item?</div>
			</div>
		</div-->
		<div class='row'>
			<label>Tweet Button</label>
			<div class="field-area">
				<input type="radio" value="2" name="feedback_item_retweet" <?php if($nss->get('feedback_item_retweet')==2) echo "checked='checked'"; ?>/> Button &amp; Count
				<input type="radio" value="1" name="feedback_item_retweet" <?php if($nss->get('feedback_item_retweet')==1) echo "checked='checked'"; ?>/> Button
				<input type="radio" value="0" name="feedback_item_retweet" <?php if($nss->get('feedback_item_retweet')==0) echo "checked='checked'"; ?>/> Hide
				<div class="field-info">Would you like to display a Twitter Tweet Button for every Twitter item?</div>
			</div>
		</div>
		
		<div class='row hl'>
			<a id='cancel-1' href='<?php echo $_SERVER['PHP_SELF']; ?>' class='cancel button'>Cancel changes</a>
			<input class='submit' type='submit' value='Save feedback'>
		</div>
	</form>
	
<?php
	include "footer.inc.php";
?>