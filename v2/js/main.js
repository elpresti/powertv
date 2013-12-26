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
}

function setAnimationVars(){
  $(document).ready(function(){
    $('a[data-test]').click(function(){
      var anim = $(this).attr('data-test');
      flipFacebookBox("fadeIn","fadeOut");
    });
  });
  fbFlipped=false;
  $("#back-fb-container").css('display','none');
  
  $(document).ready(function(){
    $('.socialWidgetsSlider').bxSlider({
      slideWidth: 600,
      minSlides: 1,
      maxSlides: 1,
      startSlide:2
      //,slideMargin: 10
    });
  });
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
            divVideoPlayer += "<img src='img/powerHDandroidOS_exp.png' alt='Click aquí para vernos en vivo!' width='640' height='400' border='0' usemap='#Map' /><map name='Map'><area shape='rect' coords='18,14,341,385' href='rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream' alt='Click aquí para vernos en vivo!' target='_blank'>";
            divVideoPlayer += "<area shape='rect' coords='423,45,598,351' href='https://play.google.com/store/apps/details?id=org.videolan.vlc.betav7neon' target='_blank'></map></div>";
        } else {
            if (deviceOS === "ios") {
                $('#videoPlayer').css('display','none');
                divVideoPlayer = "<div id='mobile_player_info' style='padding-top: 25px; padding-bottom: 25px;'>";
                divVideoPlayer += "<img src='img/powerHDiOS_exp.png' alt='Click aquí para vernos en vivo!' width='640' height='400' border='0' usemap='#Map' /><map name='Map'><area shape='rect' coords='18,14,341,385' href='rtmp://wowza.telpin.com.ar:1935/live-powerTV/power.stream' alt='Click aquí para vernos en vivo!' target='_blank'>";
                divVideoPlayer += "<area shape='rect' coords='423,45,598,351' href='https://itunes.apple.com/es/app/vlc-for-ios/id650377962' target='_blank'></map></div>";
            }
        }
    }
    $("#dynamicCodeOfVideoPlayer").append(divVideoPlayer);
}

