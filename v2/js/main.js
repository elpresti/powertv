var fbFlipped=false;

function setAnimationVars(){
  $(document).ready(function(){
    $('a[data-test]').click(function(){
      var anim = $(this).attr('data-test');
      flipFacebookBox(anim);
    });
  });
}

function flipFacebookBox(){
  if (fbFlipped){
    //hide the back panel
    $("#back-fb-container").css('display', '');
    animateElement("rollOut","back-fb-container");
    setTimeout(function secondHalfOfFx1() {
      $('#back-fb-container').css('display', 'none');
      //show the front panel
      $("#front-fb-container").css('display', '');
      animateElement("rollIn","front-fb-container");
    }, 700);
    fbFlipped=false;
  }else {
    //hide the front panel
    $("#front-fb-container").css('display', '');
    animateElement("rollOut","front-fb-container");
    setTimeout(function secondHalfOfFx2() {
      $('#front-fb-container').css('display', 'none');
      //show the back panel
      $("#back-fb-container").css('display', '');
      animateElement("rollIn","back-fb-container");
    }, 700);
    fbFlipped=true;
  }
}

function animateElement(fxName,divId) {
		$('#'+divId).removeClass().addClass(fxName + ' animated').one('webkitAnimationEnd oAnimationEnd', function(){
			$(this).removeClass();
		}); 
}
