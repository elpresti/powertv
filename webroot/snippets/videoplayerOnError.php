<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>

<!-- cuando SI se puede cargar el videoplayer -->
<div id="dynamicCodeOfVideoPlayer">
          <div id="videoPlayer_wrapper" style="position: relative; display: block; width: 768px; height: 432px;"><object type="application/x-shockwave-flash" data="http://app-9exctsffji8vgma8.apprun0.codenvycorp.com/webroot/jwplayer/jwplayer.flash.swf" width="100%" height="100%" bgcolor="#000000" id="videoPlayer" name="videoPlayer" tabindex="0"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><param name="seamlesstabbing" value="true"><param name="wmode" value="opaque"></object><div id="videoPlayer_aspect" style="display: none;"></div><div id="videoPlayer_jwpsrv" style="position: absolute; top: 0px; z-index: 10;"></div></div>
        </div>
<!--    ///////////////////////////////    -->

<!-- cuando NO se puede cargar el videoplayer -->
<div id="dynamicCodeOfVideoPlayer">
          <div style="width: 768px; height: 432px; background-color: rgb(0, 0, 0); color: rgb(255, 255, 255); display: table; opacity: 1;" id="videoPlayer"><p style="vertical-align: middle; text-align: center; display: table-cell; font: 15px/20px Arial,Helvetica,sans-serif;">Error loading player:<br> No playable sources found</p></div>
        </div>
<!--    ///////////////////////////////    -->


document.getElementById('dynamicCodeOfVideoPlayer').getElementById('videoPlayer').getElementsByTagName("P")[0].value

</body>
</html>