<?php
$comment .= <<<EOD
	<div class="nss-facebook-comment">
		<div class="nss-facebook-comment-head">
			<a class="nss-facebook-comment-avatar" href="$extras_facebook_comments_author_link" target="_blank"><img src="$extras_facebook_comments_author_avatar" alt="X"></a>
		</div>
		<div class="nss-facebook-comment-body">
			<a class='nss-facebook-comments-author-name' href="$extras_facebook_comments_author_link" target="_blank" >$extras_facebook_comments_author_name</a> 			
			<div class='nss-facebook-comments-created'>$extras_facebook_comments_created</div>
			<div class='nss-facebook-comments-line'></div>
			<span class='nss-facebook-comments-author-name'>$extras_facebook_comments_content</span>
		</div>
	</div>
EOD;
?>