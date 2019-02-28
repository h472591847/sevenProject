$(document).ready(function() {
    //var visual = $(window).height(); 
	/*; 
	*/
	$(window).scroll(function(){
		  var scrol = $(document).scrollTop();
		  //$(".rockets").text(scrol);
		  if(scrol < 200){
		     $(".hb_one").fadeOut(500);
		  }
		  else if(scrol > 200 || scrol < 500){
		     $(".hb_one").show().addClass("animated bounce");
		  }
		  
		  if(scrol < 600){
			 $(".hb_two").fadeOut(500);
		  }
		  else if(scrol > 600 || scrol < 1000){
		     $(".hb_two").show().addClass("animated flipInX");
		  }
		  
		  if(scrol < 850){
			 $(".rockets").fadeOut(500);
		  }
		  else if(scrol > 850 || scrol < 1200){
		     $(".rockets").show().addClass("animated bounceInLeft");
		  }
		  
	});
	
	$(".lucky_wheel").click(function(){
	    $(".result").show();
	});
	
	$("#resultBtn").click(function(){
		$(".result").hide();
	});
	
	
});