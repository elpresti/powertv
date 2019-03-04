var fbFlipped=false;
var showLivePlayer=true;
var deviceOs="desktop";
var deviceType="desktop";
var connectionErrorShown=false;
var reconnectAttempts=0;
var connectingErrorMsg="noErrorsYet";
var defaultConnectErrorMsg="noErrorsYet";
var urlReconnect="/?utm_source=webapp&utm_medium=autoReconnect&utm_campaign=powertv&connectingErrorMsg="+connectingErrorMsg;
var connectRetry = null;


var UrlParams = function () {
  // This function is anonymous, is executed immediately and the return value is assigned to urlParams!
  var query_string = {};
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    	// If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = pair[1];
    	// If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [ query_string[pair[0]], pair[1] ];
      query_string[pair[0]] = arr;
    	// If third or later entry with this name
    } else {
      query_string[pair[0]].push(pair[1]);
    }
  }
  return query_string;
} ();


function initScripts(altvideoplayer=false){
  setAnimationVars();
  if (altvideoplayer){
	  deviceType = "mobile";
	  deviceOs = null;
	  addVideoPlayer(deviceType, deviceOs);
  }else{
	  $.ajax({
		url : "snippets/browserDetection.php",
		dataType : "json",
		success : 
		function (data){
		  deviceType=data.device;
		  deviceOs=data.os;
		  addVideoPlayer(deviceType, deviceOs);
		}
	  });
  }
  
  //PlusGallery init
  $('#plusgallery').plusGallery();
  
}

function showPlayer(showIt){
  if (showIt){
    $("#dynamicCodeOfVideoPlayer").show();
  }else{
    $("#dynamicCodeOfVideoPlayer").hide();
  }
}

function autoplayMobileStream(){
	/*
  var rta = confirm("Para ver PowerHD en vivo presione Aceptar, o Cancelar para instalar el reproductor necesario o en caso de no poder vernos");
  if (rta==true){
    window.open("rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream","_self"); 
    //location.href = "rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream";
  }else{
    showLivePlayer=false;
  }
  */
  showLivePlayer=false;//auto-hide old player
}


function setAnimationVars(){  
  $(document).ready(function(){
    $('.socialWidgetsSlider').bxSlider({
      slideWidth: 600,
      minSlides: 1,
      maxSlides: 1,
      startSlide:0,
      auto:true,
      autoHover: true,
      pause: 7000,
      autoDelay:5000,
      autoControls: true
    });
    showPlayer(showLivePlayer);
  });

  $(function(){
    $('#pwrFbWall').fbWall({ 
        id:'radiopowerpinamar',
        accessToken:'CAAFF4Xs8Wd0BAKXXVN3vE9ymZAfjqVlF2cUK0HShZBVF2SngkZA3Ypm8nIlGJkOnNcXF13zB2wYqZCjZAxrMcvBGkEN0mpNJLZAcc12t7vt8hNGZAiEUH3Km6ctkZCiAdZCwha8DDuGopvUd3dZCLZAsh41gpPhhiCJXK4i8XhXYzrifIyX18LLuc80',
        showGuestEntries: true,
        showComments: true,
        max: 10,
        timeConversion: 24
    });
  });
  
  var addthis_config = {
     data_ga_property: 'UA-13020415-1',
     data_ga_social: true,
     data_track_addressbar: false,
     data_track_clickback: false
  };
  
  //animateElement("bounceIn","headerContainer");
//animate logo

}

function flipFacebookBox(fxIn,fxOut){
  if (fbFlipped){
    //hide the back panel
    $("#back-fb-container").css('display', '');
    animateElement(fxOut,"back-fb-container");
    setTimeout(function secondHalfOfFx1() {
      $('#back-fb-container').css('display', 'none');
      //show the front panel
      $("#front-fb-container").css('display', '');
      animateElement(fxIn,"front-fb-container");
    }, 700);
    fbFlipped=false;
  }else {
    //hide the front panel
    $("#front-fb-container").css('display', '');
    animateElement(fxOut,"front-fb-container");
    setTimeout(function secondHalfOfFx2() {
      $('#front-fb-container').css('display', 'none');
      //show the back panel
      $("#back-fb-container").css('display', '');
      animateElement(fxIn,"back-fb-container");
    }, 700);
    fbFlipped=true;
  }
}

function animateElement(fxName,divId) {
    var originalClassName=$('.'+divId).attr('class');
		$('#'+divId).removeClass().addClass(fxName + ' animated').one('webkitAnimationEnd oAnimationEnd', function(){
			$(this).removeClass();
      $(this).addClass(originalClassName);
		}); 
}

function addVideoPlayer(deviceType, deviceOS) {
    var divVideoPlayer = "";
    if (deviceType === "mobile") {
        if (deviceOS === "android") {
            $('#videoPlayer').css('display','none');
          	$('#videoPlayer_wrapper').css('display','none');
            divVideoPlayer = "<h3>Otra alternativa:</h3><div id='mobile_player_info' style='padding-top: 25px; padding-bottom: 25px;'>";
            divVideoPlayer += "<img src='img/powerHDandroidOS_exp.jpg' alt='Click aquí para vernos en vivo!' width='640' height='400' border='0' usemap='#Map' /><map name='Map'><area shape='rect' coords='18,14,341,385' href='rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream' alt='Click aquí para vernos en vivo!' target='_blank'>";
            divVideoPlayer += "<area shape='rect' coords='423,45,598,351' href='https://play.google.com/store/apps/details?id=org.videolan.vlc.betav7neon' target='_blank'></map></div>";
        } else {
            if (deviceOS === "ios") {
                $('#videoPlayer').css('display','none');
              	 $('#videoPlayer_wrapper').css('display','none');
                divVideoPlayer = "<h3>Otra alternativa:</h3><div id='mobile_player_info' style='padding-top: 25px; padding-bottom: 25px;'>";
                divVideoPlayer += "<img src='img/powerHDiOS_exp.jpg' alt='Click aquí para vernos en vivo!' width='640' height='400' border='0' usemap='#Map' /><map name='Map'><area shape='rect' coords='18,14,341,385' href='rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream' alt='Click aquí para vernos en vivo!' target='_blank'>";
                divVideoPlayer += "<area shape='rect' coords='423,45,598,351' href='https://itunes.apple.com/es/app/vlc-for-ios/id650377962' target='_blank'></map></div>";
            }
        }
divAltVideoPlayer = "<table width='100%'><tr><td align='center' width='100%'><video-js id='my_video_1' class='vjs-default-skin' controls preload='auto' autoplay='true' width='768' height='432' style='box-shadow: #666666 10px 10px 10px;'><source src='http://wowza.telpin.com.ar:1935/live-powerTV/power.stream/playlist.m3u8' type='application/x-mpegURL'></video-js>  <script> var player = videojs('my_video_1', { autoplay: true, poster:'http://radiopower.com.ar/powerhd/webroot/img/pwrHDclickToPlay.png' });	player.on('error', function(e) {       var error = player.error();	   console.log('error.code='+error.code+', error.type='+error.type+', error.message='+error.message);	   if (error.code){		   showCantConnectErrorUI();	   }	}); player.play();</script></td></tr></table>";
		$( divAltVideoPlayer ).insertBefore( "#dynamicCodeOfVideoPlayer" );
		
		$( divVideoPlayer ).insertBefore( "#dynamicCodeOfVideoPlayer" );
        //$("#dynamicCodeOfVideoPlayer").append(divVideoPlayer);
        autoplayMobileStream();
    }else {
      checkBrowserFlashReady();
    }
}

function addNoFlashMsg(){
   $('#videoPlayer').css('display','none');
   divVideoPlayer = "<div id='noFlashMsgContainer' class='noFlashMsgContainer'>";
   divVideoPlayer += "<a target='_blank' href='http://get.adobe.com/flashplayer/'><img src='img/flashPlayerLogo.png' alt='Click aquí para descargar Flash Player' width='147' height='144' border='0' /></a>";
   divVideoPlayer += "<p>Se necesita Adobe Flash Player para ver PowerHD.<br> <a target='_blank' href='http://get.adobe.com/flashplayer/'>Obtén la última versión de Flash Player</a></p></div>";
   $("#dynamicCodeOfVideoPlayer").append(divVideoPlayer); 
}


function doActionsWhileErrorConnecting(message){
  if (message !== undefined ){//onError
    console.log('Video Player Error Message >>> '+message);
    connectingErrorMsg=message;
  }
  reconnectVideoPlayer();
/*
  if (reconnectAttempts===0){
    connectRetry = setInterval(reconnectVideoPlayer, 16000);
  }else {
    reconnectVideoPlayer();
  }
*/
}

function isBrowserFlashEnabled(){
  var errorShown=false;
  var errorMsgTag = document.getElementById('videoPlayer');
  if(errorMsgTag !== undefined && errorMsgTag.getElementsByTagName("p") !== undefined && errorMsgTag.getElementsByTagName("p").length > 0){
    errorMsgTag = errorMsgTag.getElementsByTagName("p")[0].innerHTML.toLowerCase();
    if ( (errorMsgTag.indexOf('no playable sources found') != -1) || 
         ( (errorMsgTag.indexOf('error') != -1) && (errorMsgTag.indexOf('flash') != -1) )
      ){
        errorShown=true;
        console.log('Video Player Error Message >>> '+errorMsgTag);
        connectingErrorMsg=errorMsgTag;
        //getUrlReconnect();
    }
  }
  return (!(errorShown)); 
}

function checkBrowserFlashReady(){
  if (!(isBrowserFlashEnabled())){
      //console.log('Flash Player NO está instalado o está deshabilitado');
      alert('Flash Player no está instalado o está deshabilitado o su versión instalada es obsoleta');
      addNoFlashMsg();
    }else {
      //console.log('Flash Player SI está instalado y habilitado');
    }
}

function reconnectVideoPlayer(){
  if (reconnectAttempts<=3){
    reconnectAttempts=reconnectAttempts+1;
    console.log('Cantidad de intentos de reconexión: '+reconnectAttempts);
    jwplayer().play(true);
  }else{
      if (!connectionErrorShown){
        showConnectionProblemsMsg();
        connectionErrorShown=true;
      }
  }
}

function showConnectionProblemsMsg(){
   $('#videoPlayer').css('display','none');
   $('#videoPlayer_wrapper').css('display','none');
   divVideoPlayer = "<div id='connectionProblemsMsgContainer' class='connectionProblemsMsgContainer'>";
   divVideoPlayer += "<a href='"+getUrlReconnect()+"'><img src='img/imgConnectionProblems.png' alt='Click aquí para recargar la página ahora' width='120' height='119' border='0' /></a>";
   divVideoPlayer += "<p>No se pudo conectar al servidor de PowerHD.<br> <a href='"+getUrlReconnect()+"'>Vuelve a intentarlo en unos minutos</a> ó utiliza nuestro <a target='_blank' href='http://www.radiopower.com.ar/audioplayer/index.php?alternativo=1&utm_source=webapp&utm_medium=errorConnect&utm_campaign=powertv&connectingErrorMsg="+connectingErrorMsg+"'>reproductor de audio</a>.<br><a href='#youtubeWidgetContainer'>Ver Grabaciones</a></p></div>";
   $("#dynamicCodeOfVideoPlayer").append(divVideoPlayer);
   notificateConnectionProblems();
   alert("Error al intentar reproducir nuestra transmisión en vivo, te invitamos a revivir grabaciones de Momentos Power en el reproductor que está al pié de esta página e intentar nuevamente en unos minutos");
}

function getUrlReconnect(){
  urlReconnect="/?utm_source=webapp&utm_medium=autoReconnect&utm_campaign=powertv&connectingErrorMsg="+connectingErrorMsg;
  return urlReconnect;
}

function showPlayerStatus(){
  console.log(jwplayer().getState());
} 

function doActionsOnPlay(){
}

function personalizeFbWall(){
  $('#my-fb-like-box').children().children().css('height',$('#my-fb-like-box').children().children().height()-20);
}

function notificateConnectionProblems(){
    if (connectingErrorMsg != null && connectingErrorMsg.length > 3){
      $.ajax({
        url : "http://radiopower.com.ar/varios/notificateConnectionProblems.php?connectingErrorMsg="+connectingErrorMsg+"&defaultConnectErrorMsg="+defaultConnectErrorMsg,
        dataType : "json",
        success : 
          function (data){
            console.log("emailsSent: "+data.emailsSent);
            console.log("csvLog: "+data.csvLog);
          }
      });
    }
}

function addCssFixes(){
  if ($('.pgalbumthumb').size()==4){
    $('.pgalbumthumb').first().css('margin-left','40px');
  	 $('.pgalbumthumb').css('width','21%');
  }
}

/*
$(document).ready(function(){
  //console.log(videoPlayerVar.getState());
  //personalizeFbWall();
  }
)
*/

