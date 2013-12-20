<?php 
	$current_page = 'channels';
	$no_channel_warning = true;
	include "header.inc.php";
?>

<h2 id="marker-channels">Channels</h2>
<div class="nss-admin-container warning not-saved" style="display:none">
	<div class="row">Your changes are not saved, yet.</div>
</div>
<form id="channels" class="nss-admin-form" method="post" data-test="auto">
	<input type="hidden" name="action" value="update_channels">

	<div class="row ">
		
			<table cellpadding="0" cellspacing="0" class="inline-table" width="100%">
				<tr>
					<th>Username (ID)</th>
					<th>Status</th>
					<th>Access token expires in</th>
					<th>Edit</th>
				</tr>
				<?php foreach ($nss->get('channel_list') as $channelArray => $channel){
						$type = $channel['type'];
				?>
					<tr>
						<td class="channel-<?php echo $type; ?>">
							<?php echo $channel['id']; ?>
						</td>
						<td><?php echo $nss->getChannelStatus($type,$channel['id']); ?></td>
						<td width="270">
							<?php if($type=='facebook'){ ?>
								<input type="hidden" value="<?php echo $channel['access_token_expires']; ?>" name="access_token_expires">
								<input type="hidden" value="<?php echo $channel['access_token']; ?>" name="access_token">
								<span class="expires_in_real_time"></span>
							<?php } elseif($type=='twitter') { ?>
								<i style="color:#999;font-style:italic;">Never</i>
							<?php } else { ?>
								<i style="color:#999;font-style:italic;">Access token is not required</i>
							<?php } ?>
						</td>
						<td><a href="javascript://" data-id="<?php echo $channel['id']; ?>" class="button edit-channel">Edit</a></td>
					</tr>
				<?php } ?>
			</table>
	
	</div>
	
<?php
$k = 0;
foreach ($nss->get('channel_list') as $channelArray => $channel){
	$type = $channel['type'];
	$display = isset($_GET['pos'])&&$_GET['pos']==$k ? 'block' : 'none';
	echo '<div class="nss-admin-channel channel-id-'.$k.'" data-channel="'.$type.'" style="display:'.$display.'"><h3>'.$type.'</h3>';
	switch($type){
		case 'facebook': /******************************************************************************/ ?>
			<div class='nss-admin-form-row'>
				<label>Id</label>
				<div class="field-area">
					<input type="text" value='<?php echo $channel['id']; ?>' name="id" class="text">
					<div class="field-info">Your Facebook ID or Vanity-URL</div>
				</div>
			</div>
			<div class='nss-admin-form-row'>
				<label>Access token</label>
				<div class="field-area">
					<input type="text" value="<?php echo $channel['access_token']; ?>" name="access_token" class="text">
					<div class="field-info">To access your Facebook data you have to enter a valid access token.<br />Create your own access token or <b>just use the Access Token Creator</b>.</div>
					<div><a class="button atc-button" data-id="<?php echo $k; ?>" href="javascript://">Open Access Token Creator</a></div>
				</div>
			</div>
			
			<div class='nss-admin-form-row' <?php if(!intval($channel['access_token_expires'])>0){ echo " style='display:none;' "; } ?>>
				<label>Access Token expires</label>
				<div class="field-area">
					<input type="hidden" value="<?php echo $channel['access_token_expires']; ?>" name="access_token_expires">
					<span class="expires_in_real_time"></span>
				</div>
			</div>
			
			<div class='nss-admin-form-row'>
				<label>Limit</label>
				<div class="field-area">
					<input type='text' class='text' name='limit' value='<?php echo $channel['limit'] ?>' style="width:200px;">
					<div class="field-info">How many posts you want to show?</div>
				</div>
			</div>
			<div class='nss-admin-form-row'>
				<label>Show all posts</label>
				<div class="field-area">
					<select name="show_all">
						<option value="true" <?php if($channel['show_all']=='true') echo " selected " ?>>Yes, show all posts!</option>
						<option value="false" <?php if($channel['show_all']!='true') echo " selected " ?>>No, show only my posts!</option>
					</select>
				</div>
			</div>
			<div class='nss-admin-form-row'>
				<label>Status</label>
				<div class="field-area channel-status">
					<?php echo $nss->getChannelStatus('facebook',$channel['id']); ?>
				</div>
			</div>
		<?php break;
		case 'twitter': /******************************************************************************/ ?>
			<div class='nss-admin-form-row'>
				<label>Screen Name</label>
				<div class="field-area">
					<input type='text' class='text' name='id' value='<?php echo $channel['id']; ?>'>
					<div class="field-info">Your Twitter Screen Name</div>
				</div>
			</div>
			<div class='row'>
				<label>Access token</label>
				<div class="field-area">
					<input type='text' class='text' name='access_token' value='<?php echo $channel['access_token']; ?>'>
				</div>
			</div>
			<div class='row'>
				<label>Access token secret</label>
				<div class="field-area">
					<input type='text' class='text' name='access_token_secret' value='<?php echo $channel['access_token_secret']; ?>'>
					<div class="field-info">To access your Twitter data you have to enter a valid access token and secret.<br />Create your own access token or <b>just use the Access Token Creator</b>.</div>
					<div><a class="button atct-button" data-id="<?php echo $k; ?>" href="javascript://">Open Access Token Creator</a></div>
				</div>
			</div>			
			<div class='nss-admin-form-row'>
				<label>Limit</label>
				<div class="field-area">
					<input type='text' class='text' name='limit' value='<?php echo $channel['limit'] ?>' style="width:200px;">
					<div class="field-info">How many posts you want to show?</div>
				</div>
			</div>
			<div class='nss-admin-form-row'>
				<label>Status</label>
				<div class="field-area channel-status">
					<?php echo $nss->getChannelStatus('twitter',$channel['id']); ?>
				</div>
			</div>
		<?php break;
		case 'nss': /************************************************************************************/ ?>
			<div class='nss-admin-form-row'>
				<label>URL</label>
				<input type='text' class='text' name='url' value='<?php echo $channel['url']; ?>'>
			</div>
			<div class='nss-admin-form-row'>
				<label>Status</label>
				<div class="field-area channel-status">
					<?php
						$id = substr($channel['url'],strrpos($channel['url'],"/")+1);
						$id = urlencode($id);
						echo $nss->getChannelStatus('nss',$id);
					?>
				</div>
			</div>
		<?php break;
	}?>
	
		<div class="nss-admin-form-row">
			<span class="nss-admin-test button">Test</span>
			<span class="nss-admin-remove button">Remove</span>
		</div>
		<div class='nss-admin-form-row hl'>
			<a href='?cancel' class='cancel button'>Cancel changes</a>
			<a class='submit save-channels' href='#channel-start' data-pos="<?php echo $k; ?>">Save channels</a>
		</div>
	</div>
<?php
$k++;
}
?>
	<div id="add-channel-error" class="nss-admin-container error"></div>
	<div id="nss-admin-add-channel" class="nss-admin-channel <?php echo "channel-id-".$k; ?>" data-channel="new">
		<h3>Add new channel</h3>
		<div class='nss-admin-form-row'>
			<label>Type</label>
			<select id="new-channel-type" name="new_channel_type" class="nss-admin-new-channel-type">
				<option value="">- select -</option>
				<option value="facebook">Facebook</option>
				<option value="twitter">Twitter</option>
				<option value="nss">NSS</option>
			</select>
			
		</div>
		<div id="nss-admin-add-channel-facebook" class="new-channel-container">
			<input type="hidden" name="limit" value="3">
			<input type="hidden" name="show_all" value="true">
			<div class="nss-admin-form-row hl">
				<label>Id</label>
				<div class="field-area">
					<input type="text" value="" name="id" class="text">
					<div class="field-info">Your Facebook Id</div>
				</div>
			</div>
			<div class="nss-admin-form-row">
				<label>Access token</label>
				<div class="field-area">
					<input type="text" value="" name="access_token" class="text">
					<div class="field-info">To access your Facebook data you have to enter a valid access token.<br />Create your own access token or <b>just use the Access Token Creator</b>.</div>
					<div><a class="button atc-button" data-id="<?php echo $k; ?>" href="javascript://">Open Access Token Creator</a></div>
				</div>
			</div>
			<div class="nss-admin-form-row" style="display:none">
				<label>Access token expires</label>
				<div class="field-area">
					<input type="hidden" name="access_token_expires" class="text"> <span class="expires_in_real_time"></span>
				</div>
			</div>
			
			<div class="nss-admin-form-row hl">
				<a href='?cancel' class='cancel button'>Cancel</a>
				<span class="nss-admin-add-channel button">Add channel</span>
			</div>
		</div>
		
		<div id="nss-admin-add-channel-twitter" class="new-channel-container">
			<input type="hidden" name="limit" value="3">
			<div class="nss-admin-form-row hl">
				<label>Screen Name</label>
				<div class="field-area">
					<input type="text" value="" name="id" class="text">
					<div class="field-info">Your Twitter Screen Name</div>
				</div>
			</div>
			<div class='row'>
				<label>Access token</label>
				<div class="field-area">
					<input type='text' class='text' name='access_token' value=''>
				</div>
			</div>
			<div class='row'>
				<label>Access token secret</label>
				<div class="field-area">
					<input type='text' class='text' name='access_token_secret' value=''>
					<div class="field-info">To access your Twitter data you have to enter a valid access token and secret.<br />Create your own access token or <b>just use the Access Token Creator</b>.</div>
					<div><a class="button atct-button" data-id="<?php echo $k; ?>" href="javascript://">Open Access Token Creator</a></div>
				</div>
			</div>	
			<div class="nss-admin-form-row hl">
				<a href='?cancel' class='cancel button'>Cancel</a>
				<span class="nss-admin-add-channel button">Add channel</span>
			</div>
		</div>
		
		<div id="nss-admin-add-channel-nss" class="new-channel-container">
			<input type="hidden" name="limit" value="3">
			<div class="nss-admin-form-row hl">
				<label>URL</label>
				<div class="field-area">
					<input type="text" value="" name="url" class="text">
					<div class="field-info">Absolute URL to your NSS channel</div>
				</div>
			</div>			
			<div class="nss-admin-form-row hl">
				<a href='?cancel' class='cancel button'>Cancel</a>
				<span class="nss-admin-add-channel button">Add channel</span>
			</div>
		</div>
		
		
	</div>
	
</form>

<h2 id="marker-groups">Channel groups <i class="badge">Pro Version</i></h2>
<div class="nss-admin-container warning pro-warning" style="display:none">
	<div class="row"><b>Channel groups are disabled!</b></div>
	<div class="todo"><a href="https://neosmart-stream.de/get-a-license/" target="_blank"><b>Update to Pro Version</b></a> to use channel groups.</div>
</div>
<form id="groups" class="nss-admin-form" method="post" data-test="auto">
	<input type="hidden" name="action" value="update_channels">

	<div class="row ">
		<table width="100%" cellspacing="0" cellpadding="0" class="inline-table">
			<tbody>
				<tr>
					<th>Group (ID)</th>
					<th>Channels</th>
					<th>Edit</th>
				</tr>
				<?php foreach ($nss->get('group_list') as $groupArray => $group){	?>
				<tr>
					<td><?php echo $group['id']; ?></td>
					<td>
						<?php foreach(explode(',',$group['channels']) as $channel_id) echo '<div class="nss-group-channel nss-icon-'.$nss->getChannelField($channel_id,'type').'">'.$channel_id.'</div>'; ?>
					</td>
					<td><a class="button nss-edit-group" data-id="<?php echo $group['id']; ?>" href="javascript://">Edit</a></td>
				</tr>
				<?php } ?>				
			</tbody>
		</table>
	</div>
	
	<?php foreach ($nss->get('group_list') as $groupArray => $group){	?>
		<div class="nss-admin-channel nss-channel-group" data-id="<?php echo $group['id']; ?>">
			<h3>Edit group</h3>
			<div class='row'>
				<label>ID</label>
				<div class="field-area">
					<input type='text' class='text' name='group_name' value="<?php echo $group['id']; ?>">
					<div class="field-info">Set a unique ID for this group.</div>				
				</div>
			</div>
			<div class='nss-admin-form-row'>
				<label>Channels</label>
				<div class="field-area">
					<input type='hidden' class="text" name='group_channel_list' value="<?php echo $group['channels']; ?>">
					<div class="nss-group-table"></div>
					<select class="select-group-channel">
						<option value="">- add -</option>
						<?php foreach ($nss->get('channel_list') as $channelArray => $channel){
								$type = $channel['type']; 
								echo "<option value='".$channel['id']."'>".$channel['id']."</option>";
						} ?>
					</select>
				</div>		
			</div>
		
			<div class="row">
			
				<span class="nss-admin-remove-group button">Remove</span>
				<a href='?cancel' class='cancel button'>Cancel</a>
				<span class="nss-save-groups button submit ">Save group</span>
			</div>
		</div><!-- .nss-channel-group -->
	<?php } ?>	

	<div class="nss-admin-channel nss-channel-group" data-id="new">
		<h3>Add new group</h3>
		<div class='row'>
			<label>ID</label>
			<div class="field-area">
				<input type='text' class='text' name='group_name' maxlength="10">
				<div class="field-info">Set a unique ID for this group. Only lower case and no whitespace.</div>				
			</div>
		</div>
		<div class='nss-admin-form-row'>
			<label>Channels</label>
			<div class="field-area">
				<input type='hidden' class="text" name='group_channel_list'>
				<div class="nss-group-table"></div>
				<select class="select-group-channel">
					<option value="">- add -</option>
					<?php foreach ($nss->get('channel_list') as $channelArray => $channel){
							$type = $channel['type']; 
							echo "<option value='".$channel['id']."'>".$channel['id']."</option>";
					} ?>
				</select>
			</div>		
		</div>
	
		<div class="row">
			<a href='?cancel' class='cancel button'>Cancel</a>
			<span class="nss-save-groups button submit ">Save group</span>
		</div>
	</div><!-- .nss-channel-group -->
	
	<div class="row">
		<span id="nss-new-group" class="button" data-id="new">New group</span>
	</div>
		
</form>


<?php include "footer.inc.php"; ?>