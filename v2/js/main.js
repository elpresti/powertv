var fbFlipped=false;

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
