<?php if(empty($page)) die; ?>
<iframe id="nss-admin-iframe" style="float:left;" src="<?php echo $page; ?>" width="100%" height="900"></iframe>
<script type="text/javascript">
if(typeof jQuery != 'undefined'){(function($){
	var $wpc = $('#wpbody-content');
	var $iframe = $('#nss-admin-iframe');
	$wpc.find('.update-nag').hide();
	$(window).resize(function(){
		var h = $(window).height()-28-65;
		if(h<500) h = 500;
		$iframe.height(h);
	}).trigger('resize');
})(jQuery)}
</script>