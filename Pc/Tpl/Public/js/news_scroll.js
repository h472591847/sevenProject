
function getStyle(obj,name)
{
	if(obj.currentStyle)
	{
		return obj.currentStyle[name]
	}
	else
	{
		return getComputedStyle(obj,false)[name]
	}
}

function getByClass(oParent,nClass)
{
	var eLe = oParent.getElementsByTagName('*');
	var aRrent  = [];
	for(var i=0; i<eLe.length; i++)
	{
		if(eLe[i].className == nClass)
		{
			aRrent.push(eLe[i]);
		}
	}
	return aRrent;
}

function startMove(obj,att,add)
{
	clearInterval(obj.timer)
	obj.timer = setInterval(function(){
	   var cutt = 0 ;
	   if(att=='opacity')
	   {
		   cutt = Math.round(parseFloat(getStyle(obj,att)));
	   }
	   else
	   {
		   cutt = Math.round(parseInt(getStyle(obj,att)));
	   }
	   var speed = (add-cutt)/4;
	   speed = speed>0?Math.ceil(speed):Math.floor(speed);
	   if(cutt==add)
	   {
		   clearInterval(obj.timer)
	   }
	   else
	   {
		   if(att=='opacity')
		   {
			   obj.style.opacity = (cutt+speed)/100;
			   obj.style.filter = 'alpha(opacity:'+(cutt+speed)+')';
		   }
		   else
		   {
			   obj.style[att] = cutt+speed+'px';
		   }
	   }
	   
	},30)
}

  window.onload = function()
  {
	  var oDiv = document.getElementById('playBox');
	  var oPre = getByClass(oDiv,'pre')[0];
	  var oNext = getByClass(oDiv,'next')[0];
	  var oUlBig = getByClass(oDiv,'oUlplay')[0];
	  var aBigLi = oUlBig.getElementsByTagName('li');
	  var oDivSmall = getByClass(oDiv,'smalltitle')[0]
	  var aLiSmall = oDivSmall.getElementsByTagName('li');
	  
	  function tab()
	  {
	     for(var i=0; i<aLiSmall.length; i++)
	     {
		    aLiSmall[i].className = '';
	     }
	     aLiSmall[now].className = 'thistitle'
	     startMove(oUlBig,'left',-(now*aBigLi[0].offsetWidth))
	  }
	  var now = 0;
	  for(var i=0; i<aLiSmall.length; i++)
	  {
		  aLiSmall[i].index = i;
		  aLiSmall[i].onclick = function()
		  {
			  now = this.index;
			  tab();
		  }
	 }
	  oPre.onclick = function()
	  {
		  now--
		  if(now ==-1)
		  {
			  now = aBigLi.length;
		  }
		   tab();
	  }
	   oNext.onclick = function()
	  {
		   now++
		  if(now ==aBigLi.length)
		  {
			  now = 0;
		  }
		  tab();
	  }
	  var timer = setInterval(oNext.onclick,3000) //滚动间隔时间设置
	  oDiv.onmouseover = function()
	  {
		  clearInterval(timer)
	  }
	   oDiv.onmouseout = function()
	  {
		  timer = setInterval(oNext.onclick,3000) //滚动间隔时间设置
	  }
  }
  
  
//tab
$(document).ready(function() {
     
    var $div_li = $(".New_tabmenu ul li");
	$div_li.hover(function(){
        $(this).addClass("New_sel").siblings().removeClass("New_sel");
		var index = $div_li.index(this);//index() 方法返回指定元素相对于其他指定元素的 index 位置
		$("div.New_tabbox > .New_box").eq(index).show().siblings().hide();
	});
	
	  
	  
  var timer;
    $(function(){
        $(window).scroll(function(){
            clearInterval(timer);
            var topScroll=getScroll();
            var topDiv="300px";
            var top=topScroll-1130;
			var scrol = $(document).scrollTop();
			//$(".Cal_fir").text(topScroll);
			if(topScroll>1150 && topScroll<1570){
				//timer=setInterval(function(){
						//$(".test").css("top", top+"px");
						 $(".scroll_news").animate({"top":top},20);
				//},100)
			}
			if(topScroll>1570){
				//timer=setInterval(function(){
						//$(".test").css("top", top+"px");
						 $(".scroll_news").animate({"top":"445px"},20);
				//},100)
				//$(".scroll_news").css("top","440px");
		    }
			if(topScroll<1150){
				//timer=setInterval(function(){
						//$(".test").css("top", top+"px");
						 $(".scroll_news").animate({"top":"0px"},20);
				//},100)
			    //$(".scroll_news").css("top","0px");
			}
        })
    })
    function getScroll(){
             var bodyTop = 0;  
             if (typeof window.pageYOffset != 'undefined') {  
                     bodyTop = window.pageYOffset;  
             } else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {  
                     bodyTop = document.documentElement.scrollTop;  
             }  
             else if (typeof document.body != 'undefined') {  
                     bodyTop = document.body.scrollTop;  
             }  
             return bodyTop
    }
	
	
	
});
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 