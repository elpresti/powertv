<?php
	$current_page = 'translation';
	include "header.inc.php";
	
	$error_no_data = $nss->get('error_no_data');
?>

<h2 id="marker-translation">Translation</h2>
<?php include "status.license.inc.php"; ?>
<form class="nss-admin-form" method="post">
	<input type="hidden" name="action" value="update_translation">
	<div class='nss-admin-form-row'><label>Error: no data</label><input name='error_no_data' type='text' class='text' value='<?php echo $error_no_data; ?>'></div>
	<div class='nss-admin-form-row'>
		<a id='cancel-2' href='?cancel=2#marker-translation' class='cancel button'>Cancel changes</a>
		<input class='submit' type='submit' value='Save Translation'>
	</div>
</form>

<?php
	include "footer.inc.php";
?>