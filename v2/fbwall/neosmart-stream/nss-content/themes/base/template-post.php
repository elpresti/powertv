<?php
/************************************************************************
 * Base data > Start
 ************************************************************************/
$out .= <<<EOD
	<div id="nss-item-$position" data-id="$id" data-timestamp="$timestamp" class="nss-item nss-$channel $item_class">
		<div class="nss-head">
			<a href="$author_link" class="nss-author-avatar" target="_blank" rel="nofollow"><img src="$author_avatar" alt="$author_name" width="32" height="32"></a>
			<a href="$author_link" class="nss-author-name" target="_blank" rel="nofollow">$author_name</a>
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
		$out .= "<a class='nss-facebook-photo' href='$extras_facebook_link' target='_blank' rel='nofollow'><img class='nss-facebook-picture' src='$extras_facebook_image_720' alt='' border='0'></a>";
		$out .= "<div class='nss-facebook-name'>$extras_facebook_name</div>";
		$out .= "<div class='nss-facebook-caption'>$extras_facebook_caption</div>";
		$out .= "<div class='nss-facebook-description'>$extras_facebook_description</div>";
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
		$out .= "<div class='nss-facebook-question nss-facebook-story'>$extras_facebook_story</div>";
	break;
	case 'video':
		$out .= "<div class='nss-facebook-iframe'><iframe class='nss-facebook-video' width='640' height='480' src='$extras_facebook_source'></iframe></div>";
		$out .= "<div class='nss-facebook-name'>$extras_facebook_name</div>";
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
	break;
	case 'link':
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
		$out .= "<div class='nss-facebook-caption-box'>";
		$out .= "<a class='nss-facebook-name' href='$extras_facebook_link' target='_blank' rel='nofollow'>$extras_facebook_name</a>";
		$out .= "<div class='nss-facebook-description'>$extras_facebook_description</div>";
		if($extras_facebook_image_720){
			$out .= "<a class='nss-facebook-photo' href=\"$extras_facebook_link\" target='_blank' rel='nofollow'><img class='nss-facebook-picture' src='$extras_facebook_image_720' alt=\"$extras_facebook_link\" border='0'></a>";
		}
		$out .= "</div>";
	break;
	case 'status':
		$out .= "<div class='nss-facebook-status nss-facebook-story'>$extras_facebook_story</div>";
		$out .= "<div class='nss-facebook-description'>$extras_facebook_description</div>";
		$out .= "<div class='nss-facebook-message'>$extras_facebook_message</div>";
	break;
	case 'question':
		$out .= "<div class='nss-facebook-question nss-facebook-story'>$extras_facebook_story</div>";
	break;
	case 'event':
		$out .= "<div class='nss-facebook-story'>$extras_facebook_story</div>";
		$out .= "<div class='nss-facebook-event'>";
		$out .= "	<div class='nss-facebook-event-header'><a class='nss-facebook-event-name' href='$extras_facebook_link' target='_blank' rel='nofollow'>$extras_facebook_event_name</a></div>";
		$out .= "	<div class='nss-facebook-event-body'>";
		$out .= "		<div class='nss-facebook-event-description'>$extras_facebook_event_description</div>";
		$out .= "		<div class='nss-facebook-meta'>";
		$out .= "			<span class='nss-facebook-event-time'>$extras_facebook_event_start_time</span> ";
		if($extras_facebook_event_location){
			$out .= "			<span class='nss-facebook-event-location'>$extras_facebook_event_location</span>";
		}
		$out .= "		</div>";
		$out .= "	</div>";
		$out .= "</div>";
	break;
}

/************************************************************************
 * Facebook > Comments
 ************************************************************************/
if($extras_facebook_count_comments>0){
$comments = $extras_facebook_count_comments>1 ? 'comments' : 'comment';
$out .= <<<EOD
	<div class="nss-facebook-comments-count">
		<a href="javascript://" class="nss-facebook-show-comments">$extras_facebook_count_comments $comments</a>
	</div>
	<div class="nss-facebook-comments">
		$extras_facebook_comments
	</div>
EOD;
}
/************************************************************************
 * /nss-body /nss-content
 ************************************************************************/
$out .= <<<EOD
			</div>
		</div>
EOD;
/************************************************************************
 * Feedback > Facebook
 ************************************************************************/
if($is_facebook && $this->get('feedback_item')){
$out .= <<<EOD
		<div class="nss-feedback" data-object-id="$id">
			<span class="nss-feedback-link">
				<a class="nss-feedback-link-fb">Like</a>
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
 * Base data > End
 ************************************************************************/
$out .= <<<EOD
		<div class="nss-clean"></div>
	</div>
EOD;
?>