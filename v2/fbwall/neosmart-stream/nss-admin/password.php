<?php
	$current_page = 'password';
	include "header.inc.php";
?>

<h2 id="marker-translation">Password</h2>
<?php if(isset($passwordError)){ ?>
<div class="nss-admin-container error">
	<div class="row">Unsafe password - Please use a stronger one!</div>
</div>
<?php } ?>
<form class="nss-admin-form" method="post">
	<input type="hidden" name="action" value="update_password">
	<?php if(!$nss->is_default_password($nss->get('admin_password'))){ ?>
	<div class='row'>
		<label>Password</label>
		<div class="field-area">
			<input name='old_password' type='password' disabled="disabled" class='text' value='<?php echo $admin_password; ?>'>
		</div>
	</div>
	<?php } ?>
	<div class='row'>
		<label>New password</label>
		<div class="field-area">
			<input name='admin_password' type='password' class='text' value=''>
			<div class="field-info">Use a strong password with letters and numbers. Use more than four characters!</div>
		</div>
	</div>
	<div class='row'>
		<input class='submit' type='submit' value='Update password'>
	</div>
</form>

<?php
	include "footer.inc.php";
?>