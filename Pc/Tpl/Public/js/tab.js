
     
    var $div_li = $("div.tab_menu ul li");
	$div_li.click(function(){
        $(this).addClass("selected").siblings().removeClass("selected");
		var index = $div_li.index(this);//index() 方法返回指定元素相对于其他指定元素的 index 位置
		$("div.tab_box > div").eq(index).show().siblings().hide();
	});
	
