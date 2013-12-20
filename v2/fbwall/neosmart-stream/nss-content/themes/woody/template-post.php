<?php
/************************************************************************
 * Joy data > Start
 ************************************************************************/
$out .= <<<EOD
	<div id="nss-item-$position" data-id="$id" data-timestamp="$timestamp" class="nss-item nss-$channel $item_class">
		<div class="nss-head">
			<a href="$author_link" class="nss-author-avatar" target="_blank"><img src="$author_avatar" alt="$author_name" width="32" height="32"></a>
			<a href="$author_link" class="nss-author-name" target="_blank">$author_name</a>
			<span class="nss-created">$created</span>	
		</div>
		<div class="nss-body">
			<div class="nss-content">$content
EOD;
/************************************************************************
 * Facebook > Status types
 ************************************************************************/
switch($extras_facebook_type){
	case 'photo':
		$out .= "<a class='nss-facebook-photo-link' href='$extras_facebook_link' target='_blank'><img class='nss-facebook-picture' src='$extras_facebook_image_720' alt='' border='0'></a>";
		$out .= "<div class='nss-facebook-name'>$extras_facebook_name</div>";
		$out .= "<div class='nss-facebook-caption'>$extras_facebook_caption</div>";
		$out .= "<div class='nss-facebook-description'>$extras_facebook_description</div>";
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
		$out .= "<div class='nss-facebook-question nss-facebook-story'>$extras_facebook_story</div>";
	break;
	case 'video':
		$out .= "<div class='nss-facebook-iframe'><iframe class='nss-facebook-video' width='600' height='400' src='$extras_facebook_source'></iframe></div>";
		$out .= "<div class='nss-facebook-name'>$extras_facebook_name</div>";
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
	break;
	case 'link':
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
		$out .= "<div class='nss-facebook-name'>$extras_facebook_name</div>";
		$out .= "<div class='nss-facebook-description'>$extras_facebook_description</div>";
		if($extras_facebook_image_720) $out .= "<a class='nss-facebook-photo-link' href=\"$extras_facebook_link\" target='_blank'><img class='nss-facebook-picture' src='$extras_facebook_image_720' alt=\"$extras_facebook_link\" border='0'></a>";
		else $out .= "<a class='nss-facebook-link' href='$extras_facebook_link' target='_blank'>$extras_facebook_link</a>";
	break;
	case 'status':
		$out .= "<div class='nss-facebook-status nss-facebook-story'>$extras_facebook_story</div>";
		$out .= "<div class='nss-facebook-description'>$extras_facebook_description</div>";
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
	break;
	case 'question':
		$out .= "<div class='nss-facebook-question nss-facebook-story'>$extras_facebook_story</div>";
	break;
}
/************************************************************************
 * Facebook > Comments
 ************************************************************************/
if($extras_facebook_count_comments>0){
$out .= <<<EOD
	<div class="nss-facebook-comments">
		<div class="nss-facebook-count-comments">Comments: $extras_facebook_count_comments</div>
		$extras_facebook_comments
	</div>
EOD;
}
/************************************************************************
 * Feedback > Facebook
 ************************************************************************/
if($is_facebook){
$out .= <<<EOD
		
		<div class="nss-feedback" data-object-id="$id">
			<span class="nss-feedback-link">
				<a>Like</a>
				<a>Comment</a>
				<a class="nss-close">Close</a>
			</span>
			<div class="nss-feedback-container"></div>
		</div>
		
EOD;
}
/************************************************************************
 * Feedback > Twitter
 ************************************************************************/
if($is_twitter){
	$out .= $extras_twitter_button_tweet;
}
/************************************************************************
 * Joy data > End
 ************************************************************************/
$out .= <<<EOD
			</div>
		</div>
		<div class="nss-clean"></div>
	</div>
EOD;

?>