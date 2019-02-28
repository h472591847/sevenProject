$(function(){
			n=$('.line li').size();				
			var wh=100*n+"%";
			$('.line').width(wh);
			var lt=(100/n/4);
			var lt_li=lt+"%";
			$('.line li').width(lt_li);			
			var y=0;
			var w=n/2;						
			$(".line").swipe( {					
				swipeLeft:function() {
					if(y==-lt*w){
						alert('已经到最后');
					}else{
						y=y-lt;								
						var t=y+"%";									
						$(this).css({'-webkit-transform':"translate("+t+")",'-webkit-transition':'500ms linear'} );	
					}
				},
				swipeRight:function() {
					if(y==0){
						alert('已经到第一页')	
					}else{
						y=y+lt;
						var t=y+"%";
						$(this).css({'-webkit-transform':"translate("+t+")",'-webkit-transition':'500ms linear'} );	
					}
					
				}
			});
		});						