<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>管理中心</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/admin.css" type="text/css" rel="stylesheet">
<style type="text/css">
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
</style>
</HEAD>
<SCRIPT type=text/javascript>
//if(window.addEventListener){   alert("请尽量正确使用ie浏览器登录进行管理!\n否则可能会出现个别组件不支持、提交信息乱码等情况!"); }
function checklogin(obj){
	if(obj.username.value=="")	{
		alert("请输入登陆名");
		return false;	}
	if(obj.password.value=="")	{
		alert("请输入登陆密码");
		return false;	}
	return true;}
</SCRIPT>
<BODY>
<table class="hs12pt" height="100%" cellSpacing=0 cellPadding=0 width="100%" bgColor=#002779 
border=0>
  <TR>
    <TD align=middle>
      <table class="hs12pt" cellSpacing=0 cellPadding=0 width=468 border=0>
        <TR>
          <TD><IMG height=23 src="__PUBLIC__/images/login_1.jpg" 
          width=468></TD></TR>
        <TR>
          <TD><IMG height=147 src="__PUBLIC__/images/login_2.jpg" 
            width=468></TD></TR></TABLE>
      <table class="hs12pt" cellSpacing=0 cellPadding=0 width=468 bgColor=#ffffff border=0>
        <TR>
          <TD width=16><IMG height=122 src="__PUBLIC__/images/login_3.jpg" 
            width=16></TD>
          <TD align=middle>
            <table class="hs12pt" cellSpacing=0 cellPadding=0 width=230 border=0>
              <FORM name=form1 action="<?php echo U('Login/login','');?>" method=post onSubmit="return checklogin(this);">
              <TR height=5>
                <TD width=5></TD>
                <TD width=56></TD>
                <TD></TD></TR>
              <TR height=36>
                <TD></TD>
                <TD>用户名</TD>
                <TD><INPUT name=username id="username" 
                  style="BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid" size=24 
                  maxLength=20></TD></TR>
              <TR height=36>
                <TD>&nbsp; </TD>
                <TD>口　令</TD>
                <TD><INPUT 
                name=password 
                  type=password id="password" 
                  style="BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid" size=24 maxLength=20></TD></TR>
              <TR height=5>
                <TD colSpan=3></TD></TR>
              <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD><INPUT type=image height=18 width=70 
                  src="__PUBLIC__/images/bt_login.gif"></TD></TR></FORM></TABLE>
                  <a style=" color:#F00;">注：请使用IE浏览器进行相关操作！</a></TD>
          <TD width=16><IMG height=122 src="__PUBLIC__/images/login_4.jpg" 
            width=16></TD></TR></TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width=468 border=0>
        <TR>
          <TD><IMG height=16 src="__PUBLIC__/images/login_5.jpg" 
          width=468></TD></TR></TABLE>
      <TABLE cellSpacing=0 cellPadding=0 width=468 border=0>
        <TR>
          <TD align=right>&nbsp;</TD></TR></TABLE></TD></TR></TABLE></BODY></HTML>