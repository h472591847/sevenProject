<?php 
  date_default_timezone_set("PRC");
  //echo '<pre>'.print_r($_GET,true);
  $link_url = $_GET['link_url'];
  $now_time = $_GET['now_time'];
  $user_name = $_GET['user_name'];  
  $action = $_GET['action']; 
  $host = $_GET['host'];
  $web_title = $_GET['web_title'];
  $tpl_path = $host.'/Public/Templates/Mail/';
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo '密码找回邮件-'.$web_title;?></title>
</head>
<style>
body{ font-family:"微软雅黑"; font-size:14px; margin:0; padding:0; color:#646464;word-break:break-all;}
div,h3,p,span,a{ padding:0; margin:0;}
.spacer{ clear:both;}
.circle{ width:580px; border:1px solid #d7d7d7; box-shadow:2px 2px 2px #dddddd; -moz-box-shadow:2px 2px 2px #dddddd;-ms-box-shadow:2px 2px 2px #dddddd; margin:0 auto; margin-top:40px; background:#f2f2f2;}
.top{ height:80px; border-bottom:3px solid #e44343; padding:10px 0 0 10px;}
.center{ padding:20px 20px; line-height:30px;}
.center h3{ line-height:50px;}
.center span{ color:#e44343;}
.center p{ text-indent:2em; margin:10px 0;}
.aa{ display:block;}
.bo{ display:block; text-align:right; margin-top:10px;}
.bottom{ background:#e4e4e4; padding:15px 20px;}
.bottom p{ float:left; text-align:center; font-size:12px;}
.tel{  margin-top:15px;}
.sina{ margin-left:50px;}
.wx{ margin-left:20px;}
</style>

<body>
<div class="circle">
   <div class="top"><img src="<?php echo $tpl_path;?>/images/logo.jpg" /></div>
   <div class="center">
      <h3>亲爱的<?php echo $web_title;?>用户（<span><?php echo $user_name;?></span>），您好！</h3>
      <p>感谢您使用<?php echo $web_title;?>(<a href="<?php echo $host;?>"><?php echo $host;?></a>)，您于 <?php echo date('Y-m-d H:i:s',$now_time);?> 提交了<span><?php echo $user_name;?></span>的<?php echo $action;?>申请。请点击下面的链接以确认：
         <a href="<?php echo $link_url;?>" class="aa"><?php echo $link_url;?></a>
      </p>
      <p>如果链接无法点击，请将它拷贝到浏览器地址栏中直接访问。</p>
      <p>
        任何问题，请与我们的客服专员沟通。客服电话：400-657-0826</p>
      <p>请勿直接回复本邮件。谢谢您的配合。</p>
      
      <p class="bo">
        <?php echo $web_title;?><br />
        <?php echo date('Y',$now_time).'年'.date('m',$now_time).'月'.date('d',$now_time).'日';?>
      </p>
   </div>
   <div class="bottom">
     <p class="tel"><img src="<?php echo $tpl_path;?>images/tel.png" /></p>
     <p class="sina"><img src="<?php echo $tpl_path;?>images/bot_sina.jpg" /><br />新浪微博</p>
     <p class="wx"><img src="<?php echo $tpl_path;?>images/bot_wx.jpg" /><br />腾讯微信</p>
     <div class="spacer"></div>
   </div>
</div>
</body>
</html>
