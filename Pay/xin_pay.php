<?php 
  session_start();
  if(empty($_SESSION['user_id'])){
    echo '页面请求超时';die;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Pay Page</title>
</head>
<body oncontextmenu="return false">
<form action="https://www.hnapay.com/website/pay.htm" method="post" name="orderForm" style="display:none;">
	  <input type="hidden" name="version"  value="2.6">版本<br>
	  <input type="hidden" name="serialID" id="serialID"  value="">请求流水号<br>
	  <input type="hidden" name="submitTime" id="submitTime"  value="">订单提交时间<br>
	  <input type="hidden" name="failureTime" id="failureTime"  value="">订单失效时间<br>
	  <input type="hidden" name="customerIP"  value="www.p2p222.com[122.143.14.176]">客户下单域名及IP<br>
	  <input type="hidden" name="orderDetails" id="orderDetails"  value=",<?php echo $_REQUEST['money'] * 100;?>,金投资,金会员充值,1">订单明细信息<br>
	  <input type="hidden" name="totalAmount"  value="<?php echo $_REQUEST['money'] * 100;?>">订单总金额<br>
	  <input type="hidden" name="type"  value="1000">及时支付<br>
	  <input type="hidden" name="buyerMarked"  value="">付款方新生账户号<br>
	  <input type="hidden" name="payType"  value="BANK_B2C">付款方式 ALL：全部（默认），ACCT_RMB：账户支付，BANK_B2C：网银B2C支付，BANK_B2B：网银B2B支付<br>
	  <input type="hidden" name="orgCode"  value="">目标资金机构代码<br>
	  <input type="hidden" name="currencyCode"  value="1">交易币种 1：人民币（默认）,2：预付卡（选择用预付费卡支付时，可选）,3：授信额度<br>
	  <input type="hidden" name="directFlag"  value="0"> 是否直连 0：非直连 （默认）,1：直连<br>
	  <input type="hidden" name="borrowingMarked"  value="0"> 资金来源借贷标识  0：无特殊要求（默认）,1：只借记,2：只贷记<br>
	  <input type="hidden" name="couponFlag"  value="1"> 优惠券标识 1：可用 （默认）,0：不可用<br>
	  <input type="hidden" name="platformID"  value=""> 平台商id <br>
	  <input type="hidden" name="returnUrl"  value="http://www.p2p222.com/index.php/Member/incharge_msg"> 商户回调地址<br>
	  <input type="hidden" name="noticeUrl"  value="http://www.p2p222.com/index.php/xinMsg/incharge_done"> 商户通知地址<br>
	  <!-- <input type="hidden" name="partnerID"  value="10000000029">  商户id<br> -->
      <input type="hidden" name="partnerID"  value="10007643050">
	  <!-- <input type="hidden" name="remark"  value="<?php echo $_REQUEST["user_id"].",".$_REQUEST["user_name"].",".$_REQUEST["user_pwd"].",".$_REQUEST["slt_bank_name"];?>">备注扩展字段<br> -->
	  <input type="hidden" name="remark"  value="<?php echo rawurlencode($_REQUEST["dd"]).",".rawurlencode($_REQUEST["slt_bank_name"]);?>">备注扩展字段<br>
      <input type="hidden" name="charset"  value="1">编码方式<br>
	  <input type="hidden" name="signType"  value="2"> 签名类型 1：RSA 方式（推荐）,2：MD5 方式 <br>
      <textarea name="signMsg" id="signMsg" cols="50" rows="10"></textarea>生成签名字符串
</form>
<script type="text/javascript">
	var myDate = new Date();
	document.getElementById("serialID").value=myDate.getTime();
    data_serialID = myDate.getTime();//请求支付流水号
	xx=document.getElementById("orderDetails")
	 xx.value = document.getElementById("serialID").value.substr(5) + "1" + xx.value ;
	var myDate = new Date();
    myDate.getYear();       //获取当前年份(2位)
    myDate.getFullYear();   //获取完整的年份(4位,1970-????)
    myDate.getMonth();      //获取当前月份(0-11,0代表1月)
    myDate.getDate();       //获取当前日(1-31)
    myDate.getDay();        //获取当前星期X(0-6,0代表星期天)
    myDate.getTime();       //获取当前时间(从1970.1.1开始的毫秒数)
    myDate.getHours();      //获取当前小时数(0-23)
    myDate.getMinutes();    //获取当前分钟数(0-59)
    myDate.getSeconds();    //获取当前秒数(0-59)
    myDate.getMilliseconds();   //获取当前毫秒数(0-999)
    myDate.toLocaleDateString();    //获取当前日期
    var mytime=myDate.toLocaleTimeString();    //获取当前时间
    myDate.toLocaleString();       //获取日期与时间
	var x = myDate.getFullYear()+'';
	var y = myDate.getFullYear()+1+'';
    if(myDate.getMonth()+1<10){
    	x += '0'+(myDate.getMonth()+1);
    	y += '0'+(myDate.getMonth()+1);
    }
    else{
    	x+=myDate.getMonth()+1;
    	y+=myDate.getMonth()+1;
    }
    
    if(myDate.getDate()<10){
    	x += '0'+myDate.getDate();
    	y += '0'+myDate.getDate();
    }
    else{
    	x +=myDate.getDate();
    	y +=myDate.getDate();
    }
    
    if(myDate.getHours()<10){
    	x += '0'+myDate.getHours();
    	y += '0'+myDate.getHours();
    }
    else{
    	x +=myDate.getHours();
    	y +=myDate.getHours();
    }
    
    if(myDate.getMinutes()<10){
    	x += '0'+myDate.getMinutes();
    	y += '0'+myDate.getMinutes();
    }
    else{
    	x +=myDate.getMinutes();
    	y +=myDate.getMinutes();
    }
    
    if(myDate.getSeconds()<10){
    	x += '0'+myDate.getMinutes();
    	y += '0'+myDate.getMinutes();
    }
    else{
    	x +=myDate.getSeconds();
    	y +=myDate.getSeconds();
    }
    if(x.length = 15){
		x = x.substr(0, 14)
	}
	if(y.length = 15){
		y = y.substr(0, 14)
	}
    document.getElementById("submitTime").value = x;
	document.getElementById("failureTime").value = y;
//ajax方法 
var httpRequest = false;
function createRequest(url,value ){
httpRequest = false;
if( window.XMLHttpRequest ){
httpRequest = new XMLHttpRequest();
if( httpRequest.overrideMimeType ){ httpRequest.overrideMimeType( "text/xml" ); }
}
else if( window.ActiveXObject ){
try{ httpRequest = new ActiveXObject( "Msxml2.XMLHTTP" ); }
catch(e){
try{ httpRequest = new ActiveXObject( "Microsoft.XMLHTTP" ); }
catch(e){ alert("您的浏览器不支持AJAX！"); return false;}
}
}
if(!httpRequest){ alert( '无法创建XMLHTTP类实例' ); return false; }
httpRequest.onreadystatechange = alertContents;// 立即执行数据回收
httpRequest.open( "POST", url, true );
httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");// POST 方法必须有的函数1 => 传输数据需要用setRequestHeader() 来添加 HTTP 头
httpRequest.send( 'data='+value); // post 方法必须有的函数2 => 传值的方法 .send( 'name=value' );
}
function alertContents(){
if( httpRequest.readyState == 4 ){ if( httpRequest.status == 200 ){ document.orderForm.signMsg.value = httpRequest.responseText; }else{ alert( 'error' ); } }
}
//方法结束
            var version = document.orderForm.version.value;
			var serialID = document.orderForm.serialID.value;
			var submitTime = document.orderForm.submitTime.value;
			var failureTime = document.orderForm.failureTime.value;
			var customerIP = document.orderForm.customerIP.value;
			var orderDetails = document.orderForm.orderDetails.value;
			var totalAmount = document.orderForm.totalAmount.value;
			var type = document.orderForm.type.value;
			var buyerMarked = document.orderForm.buyerMarked.value;
			var payType = document.orderForm.payType.value;
			var orgCode = document.orderForm.orgCode.value;
			var currencyCode = document.orderForm.currencyCode.value;
			var directFlag = document.orderForm.directFlag.value;
			var borrowingMarked = document.orderForm.borrowingMarked.value;
			var couponFlag = document.orderForm.couponFlag.value;
			var platformID = document.orderForm.platformID.value;
			var returnUrl = document.orderForm.returnUrl.value;
			var noticeUrl = document.orderForm.noticeUrl.value;
			var partnerID = document.orderForm.partnerID.value;
			var remark = document.orderForm.remark.value;
			var charset = document.orderForm.charset.value;
			var signType = document.orderForm.signType.value;
			lgstr = 
			"version="+version
			 +"&serialID="+serialID
			 +"&submitTime="+submitTime
			 +"&failureTime="+failureTime
			 +"&customerIP="+customerIP
			 +"&orderDetails="+orderDetails
			 +"&totalAmount="+totalAmount
			 +"&type="+type
			 +"&buyerMarked="+buyerMarked
			 +"&payType="+payType
			 +"&orgCode="+orgCode
			 +"&currencyCode="+currencyCode
			 +"&directFlag="+directFlag
			 +"&borrowingMarked="+borrowingMarked
			 +"&couponFlag="+couponFlag
			 +"&platformID="+platformID
			 +"&returnUrl="+returnUrl
			 +"&noticeUrl="+noticeUrl
			 +"&partnerID="+partnerID
			 +"&remark="+remark
			 +"&charset="+charset
			 +"&signType="+signType;

ly_str = "serialID=" + orderDetails 
          + "&user_id=<?php echo rawurlencode($_REQUEST['dd']);?>"
          + "&user_name=<?php echo $_REQUEST['user_name'];?>"
          + "&money=<?php echo $_REQUEST['money'];?>"
          ;
createRequest( '/index.php/xinMsg/write_serialid_record.html', encodeURIComponent(ly_str));
createRequest( 'generate.php', encodeURIComponent(lgstr) ); 
function temp(){
	if(document.getElementById('signMsg').value!=''){
		document.orderForm.submit();
	}
}
window.onload=function (){ setInterval('temp()',1500); }
</script>
发送请求中...
</body>
</html>