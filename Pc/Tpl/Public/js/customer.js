$(document).ready(function() {
    $(".inT_wx").click(function(){
	    $(".wx_pop").fadeIn(500);
	});
	$(".wx_close").click(function(){
       $(".wx_pop").fadeOut(500);
	});
	
	$(".side ul li").hover(function(){
		$(this).find(".sidebox").stop().animate({"width":"124px"},200).css({"opacity":"1","filter":"Alpha(opacity=100)","background":"#e44343"})	
	},function(){
		$(this).find(".sidebox").stop().animate({"width":"54px"},200).css({"opacity":"0.8","filter":"Alpha(opacity=80)","background":"#000"})	
	});
	
	$(".cal_jsq").click(function(){
	    $(".calcul_l").fadeIn(700);
	}); 
	$(".js_close").click(function(){
       $(".calcul_l").fadeOut(700);
	});

	//理财计算器，计算处理
	$("#mid-dispose").click(function(event) {
		var $mid_money = $("#mid_money"); //金额
		var $mid_deadline = $("#mid_deadline"); //期限
		var $mid_rate = $("#mid_rate"); //利率
		var $mid_result = $("#mid_result"); //计算结果容器
		var $loan_deadline_type = $("#loan_deadline_type");//期限类型切换
		var number_reg = /^\d+$/;
		if(!number_reg.test($.trim($mid_money.val()))){
			alert('请输入正确的投资金额');$mid_money.focus();return false;
		}
		if(!number_reg.test($.trim($mid_deadline.val()))){
			alert('请输入正确的投资期限');$mid_deadline.focus();return false;
		}	
		if($.trim($mid_rate.val()) == ''){
			alert('请输入正确的年化利率');$mid_rate.focus();return false;
		}		
		//投资期限 天/月切换
		if($loan_deadline_type.val() == 1){
		//计算收益 = 本金 * 利率 / 365天 * 投资期限 + 本金
			var result = String(parseFloat($mid_money.val() * ($mid_rate.val()/100) / 365 * $mid_deadline.val()) + parseFloat($mid_money.val()));							
		}else if($("#loan_deadline_type").val() == 0){
		//计算收益 = 本金 * 利率 / 12个月 * 投资期限 + 本金
			var result = String(parseFloat($mid_money.val() * ($mid_rate.val()/100) / 12 * $mid_deadline.val()) + parseFloat($mid_money.val()));										
		}			
		result = (result.indexOf(".")>0) ? result.substring(0,result.indexOf(".") + 3) : result;//如果是小数，截取两位小数（不四舍五入）
		$mid_result.text(result); //输出到页面			
	});

});

//回到顶部
function goTop(){
	$('html,body').animate({'scrollTop':0},600);
	
	
}