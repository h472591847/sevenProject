<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/admin.css" type="text/css" rel="stylesheet">
<style type="text/css">
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
</style>
</HEAD>
<BODY>
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TR height=28>
    <TD background=images/title_bg1.jpg></TD></TR>
  <TR>
    <TD bgColor=#b1ceef height=1></TD></TR>
  <TR height=20>
    <TD background=images/shadow_bg.jpg></TD></TR></TABLE>
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="90%" align=center border=0>
  <TR height=100>
    <TD align=middle width=100><IMG height=100 src="__PUBLIC__/images/admin_p.gif" 
      width=90></TD>
    <TD width=60>&nbsp;</TD>
    <TD>
      <TABLE class="hs12pt" height=100 cellSpacing=0 cellPadding=0 width="100%" border=0>
        
        <TR>
          <TD width="64" align="right">当前时间：</TD>
          <TD width="315" align="left"><div id="time"></div>
<script language="javascript" type="text/javascript">
 window.onload=function (){
  setInterval("document.getElementById('time').innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);
 }
</script></TD>
        </TR>
        <TR>
          <TD colspan="2" style="FONT-WEIGHT: bold; FONT-SIZE: 16px"><?php echo $_SESSION['username']; ?></TD></TR>
        <TR>
  <TD colspan="2">欢迎进入网站管理中心！<!-- <a style="color:#F00">注：请正确使用IE浏览器进行相关操作！</a> --></TD></TR></TABLE></TD></TR>
  <TR>
    <TD colSpan=3 height=10></TD></TR></TABLE>
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="95%" align=center border=0>
  <TR height=20>
    <TD></TD></TR>
  <TR height=22>
    <TD style="PADDING-LEFT: 20px; FONT-WEIGHT: bold; COLOR: #ffffff" 
    align=middle background=images/title_bg2.jpg>您的相关信息</TD></TR>
  <TR bgColor=#ecf4fc height=12>
    <TD></TD></TR>
  <TR height=20>
    <TD></TD></TR></TABLE>
<TABLE class="hs12pt" cellSpacing=0 cellPadding=2 width="95%" align=center border=0>
  <TR>
    <TD align=right width=10%>登陆帐号：</TD>
    <TD style="COLOR: #880000"><?php echo (session('username')); ?></TD></TR>
  <TR>
    <TD align=right>管理权限：</TD>
    <TD style="COLOR: #880000">暂无</TD></TR>
  <TR>
    <TD align=right>登录时间：</TD>
    <TD style="COLOR: #880000"><?php echo (date('Y-m-d H:i:s',$user_info["logintime"])); ?></TD></TR>
  <TR>
    <TD align=right>登录 I P：</TD>
    <TD style="COLOR: #880000"><?php echo ($user_info["loginip"]); ?></TD></TR>
  <TR>
    <TD align=right>开发QQ：</TD>
    <TD style="COLOR: #880000">
      <a href="http://wpa.qq.com/msgrd?v=3&uin=356989639&site=qq&menu=yes" target="_blank">356989639</a> , 
      <a href="http://wpa.qq.com/msgrd?v=3&uin=472591847&site=qq&menu=yes" target="_blank">472591847</a>
    </TD></TR>

  <TR>
    <TD align=right>申请贷款：</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('LoanApply/index');?>">当前共<span style="font-weight:bold"> <?php echo ($loan_apply_count); ?> </span> 条前台申请的贷款</a></TD></TR>
  <TR>
    <TD align=right>进行中项目：</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('Loan/index');?>">当前共<span style="font-weight:bold"> <?php echo ($loan_count); ?> </span> 条项目正在进行</a></TD></TR>
  <TR>
    <TD align=right>满标项目：</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('Loan/index');?>">当前共<span style="font-weight:bold"> <?php echo ($full_loan_count); ?></span> 条满标可还款项目</a></TD></TR>
  <TR>
    <TD align=right>即将到期:</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('Loan/index');?>">当前共<span style="font-weight:bold"> <?php echo ($end_count); ?></span> 条项目即将到期(3天内)</a></TD></TR>    
  <TR>
    <TD align=right>成交项目：</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('Loan/index');?>">当前共<span style="font-weight:bold"> <?php echo ($loan_over_count); ?> </span>条成交（已还清）项目</a></TD></TR>
  <TR>
    <TD align=right>成交总额：</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('Loan/index');?>">当前成交总金额：<span style="font-weight:bold"> <?php echo (format_price($loan_over_money["money"])); ?> 元 </span></a></TD></TR> 
  <TR>
    <TD align=right>充值总额：</TD>
    <TD style="COLOR: #880000"><a href="<?php echo U('RechargeLog/index');?>">当前会员充值总额：<span style="font-weight:bold"> <?php echo (format_price($recharge["money"])); ?> 元 </span></a></TD></TR>                          
</TABLE></BODY></HTML>