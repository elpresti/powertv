<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Power TV - Radio Power Pinamar</title>

<link rel="stylesheet" type="text/css" href="css/main.css">

<script type="text/javascript" src="js/prefixfree.min.js"></script>

<script type="text/javascript" src="jwplayer/jwplayer.js"></script>


</head>

<body>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
    	<td valign="middle" align="center" height="200">
          <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
          	<div class="flipper">
          		<div class="front">
          			<!-- front content -->
                <img src="img/logoPwrHD_small.png" border="0" width="399" height="134" />
          		</div>
          		<div class="back">
          			<!-- back content -->
                <img src="img/logoPwrHD_small.png" border="0" width="399" height="134" />
          		</div>
          	</div>
          </div>
        </td>
    </tr>
	<tr>
    	<td valign="top" align="center">
            <div id="videoPlayer" >Cargando el reproductor...</div>
            <script type="text/javascript">
                jwplayer("videoPlayer").setup({
                    file: "rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream",
                    autostart: true,
					mute: false,
                    height: 400,
                    width: 640
                });
            </script>
        </td>
    </tr>

<?  if ($_REQUEST['utm_medium']=="fbtab"){  ?>
    <tr>
    	<td valign="middle" align="center">
          <p><br>En caso de no poder vernos debes hacer <a href="http://www.radiopower.com.ar/videoplayer/?utm_source=facebook&utm_medium=fbtab_fail_to_load&utm_campaign=powertv" target="_blank">click aqu&iacute;</a></p>
        </td>
    </tr>
<?  }  ?>

</table>


<!-- -----------------   google analytics   ---------------------  !-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-13020415-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!-- -----------------   fin google analytics   ---------------------  !-->

</body>
</html>
