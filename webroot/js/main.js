var fbFlipped=false;
var deviceOs="desktop";
var deviceType="desktop";

function initScripts(){
  setAnimationVars();
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
  
  //YoutubeTV Player init
  $(window).load(function(){
				$('#youtubePwrPlayer').ytv({
					user: 'radiopowertv', 
          playlist: 'PL2QEFy5VLgyLCvYkCEnaC9yU5hJkPXgxd',
					accent: '#d51c18',
          annotations: true,
          chainVideos: true
				}); 
	});
  
  //PlusGallery init
  $('#plusgallery').plusGallery();
  
}

function autoplayMobileStream(){
  var rta = confirm("Para ver PowerHD en vivo presione Aceptar"); 
  if (rta==true){
    window.open("rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream","_self"); 
    //location.href = "rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream";
  }
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
  });

  $(function(){
    $('#pwrFbWall').fbWall({ 
        id:'radiopowerpinamar',
        accessToken:'CAAFF4Xs8Wd0BAEvuudcutY4WLauzbOQZBTRxZAnsyNE99lAadYTyETvIOOPfDHzUtqbZAg0BXrNYodBS6rLrrhP74lRfmGNMNTFx7SxChVEQTnytTRpyZArrPZCNDQWqPyldQvzj9Si3FzeMMG4oZAFWlKAgeydZBY0USZAAb4cZCAsJaGFdP6ZAzSTdcKhSmidK4ZD',
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
  
  //animateElement("bounceIn","headerContainer");//animate logo
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
            divVideoPlayer = "<div id='mobile_player_info' style='padding-top: 25px; padding-bottom: 25px;'>";
            divVideoPlayer += "<img src='img/powerHDandroidOS_exp.jpg' alt='Click aquí para vernos en vivo!' width='640' height='400' border='0' usemap='#Map' /><map name='Map'><area shape='rect' coords='18,14,341,385' href='rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream' alt='Click aquí para vernos en vivo!' target='_blank'>";
            divVideoPlayer += "<area shape='rect' coords='423,45,598,351' href='https://play.google.com/store/apps/details?id=org.videolan.vlc.betav7neon' target='_blank'></map></div>";
        } else {
            if (deviceOS === "ios") {
                $('#videoPlayer').css('display','none');
                divVideoPlayer = "<div id='mobile_player_info' style='padding-top: 25px; padding-bottom: 25px;'>";
                divVideoPlayer += "<img src='img/powerHDiOS_exp.jpg' alt='Click aquí para vernos en vivo!' width='640' height='400' border='0' usemap='#Map' /><map name='Map'><area shape='rect' coords='18,14,341,385' href='rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream' alt='Click aquí para vernos en vivo!' target='_blank'>";
                divVideoPlayer += "<area shape='rect' coords='423,45,598,351' href='https://itunes.apple.com/es/app/vlc-for-ios/id650377962' target='_blank'></map></div>";
            }
        }
        autoplayMobileStream();
    }
    $("#dynamicCodeOfVideoPlayer").append(divVideoPlayer);
}

function doActionsWhileErrorConnecting(){
  alert("Error al intentar reproducir nuestra transmisión en vivo, te invitamos a revivir grabaciones de Momentos Power en el reproductor que está al pié de esta página e intentar nuevamente en unos minutos");
}

