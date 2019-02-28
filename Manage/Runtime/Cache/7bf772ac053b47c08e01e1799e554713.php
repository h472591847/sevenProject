<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/admin.css" type="text/css" rel="stylesheet">
<SCRIPT language=javascript>
	function expand(el){
		childObj = document.getElementById("child" + el);
		if (childObj.style.display == 'none'){
			childObj.style.display = 'block';
		}else{
			childObj.style.display = 'none';}
		return;	}
</SCRIPT>
<style type="text/css">
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
</style>
</HEAD>
<BODY>
<TABLE class="hs12pt" height="100%" cellSpacing=0 cellPadding=0 width=170 
background=__PUBLIC__/images/menu_bg.jpg border=0>
  <TR>
    <TD vAlign=top align=middle>
      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TR>
          <TD height=10></TD></TR></TABLE>
      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(1) 
            href="javascript:void(0);">信息管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child1 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
      <?php if(in_array('Info/Info', $uri_ar) || empty($uri_ar)){ ?>
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Info/Info');?>" 
            target=main>添加信息</A></TD></TR>
        <?php } ?>
        <?php if(in_array('Info/Index', $uri_ar) || empty($uri_ar)){ ?>
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Info/Index');?>" 
            target=main>管理信息</A></TD></TR>
        <?php } ?>   
        <?php if(in_array('Category/Index', $uri_ar) || empty($uri_ar)){ ?>                 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Category/Index');?>" 
            target=main>分类管理</A></TD></TR>
        <?php } ?>   
        <?php if(in_array('Ad/Index', $uri_ar) || empty($uri_ar)){ ?>              
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Ad/Index');?>" 
            target=main>其他信息</A></TD></TR>   
        <?php } ?>   
        <?php if(in_array('Join/Index', $uri_ar) || empty($uri_ar)){ ?>                
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>          
          <TD><A class=menuChild
            href="<?php echo U('Join/Index');?>" 
            target=main>加盟申请列表</A></TD></TR> 
        <?php } ?>                                    
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>
      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(3) 
            href="javascript:void(0);">投资管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child3 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
      <?php if(in_array('Invested/Invested', $uri_ar) || empty($uri_ar)){ ?>         
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Invested/Invested');?>" 
            target=main>添加信息</A></TD></TR>
       <?php } ?>   
       <?php if(in_array('Invested/Index', $uri_ar) || empty($uri_ar)){ ?> 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Invested/Index');?>" 
            target=main>管理信息</A></TD></TR>    
       <?php } ?>                              
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>

      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(4) 
            href="javascript:void(0);">贷款管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child4 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
      <?php if(in_array('Loan/Loan', $uri_ar) || empty($uri_ar)){ ?>       
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Loan/Loan');?>" 
            target=main>添加信息</A></TD></TR>
      <?php } ?>  
      <?php if(in_array('Loan/Index', $uri_ar) || empty($uri_ar)){ ?>          
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Loan/Index');?>" 
            target=main>单项目列表</A></TD></TR>  
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Loan/Index',array('change_type'=>'product'));?>" 
            target=main>产品列表</A></TD></TR>              
      <?php } ?>  
      <?php if(in_array('LoanApply/Index', $uri_ar) || empty($uri_ar)){ ?>                                 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('LoanApply/Index');?>" 
            target=main>贷款申请列表</A></TD></TR>   
       <?php } ?>               
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>

      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(5) 
            href="javascript:void(0);">会员管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child5 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
<!--         <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Info/Info');?>" 
            target=main>添加信息</A></TD></TR> -->
        <?php if(in_array('Member/Index', $uri_ar) || empty($uri_ar)){ ?>   
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Member/Index');?>" 
            target=main>会员管理</A></TD></TR> 
        <?php } ?>
        <?php if(in_array('Lottery/Index', $uri_ar) || empty($uri_ar)){ ?>         
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Lottery/Index');?>" 
            target=main>大转盘设置</A></TD></TR>   
        <?php } ?>
        <?php if(in_array('Lottery/RecordIndex', $uri_ar) || empty($uri_ar)){ ?>                                                   
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Lottery/RecordIndex');?>" 
            target=main>大转盘中奖记录</A></TD></TR> 
        <?php } ?>
        <?php if(in_array('FeedBack/Index', $uri_ar) || empty($uri_ar)){ ?>    
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('FeedBack/Index');?>" 
            target=main>意见反馈列表</A></TD></TR> 
        <?php } ?> 
        <?php if(in_array('CodeAdmin/Index', $uri_ar) || empty($uri_ar)){ ?>    
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('CodeAdmin/Index');?>" 
            target=main>验证码管理</A></TD></TR>
        <?php } ?>                                                                      
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>
        <?php if(in_array('CodeAdmin/Index', $uri_ar) || empty($uri_ar)){ ?>    
        <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
          <TR height=22>
            <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
              class=menuParent onclick=expand(6) 
              href="javascript:void(0);">短信邮件</A></TD></TR>
          <TR height=4>
            <TD></TD></TR></TABLE>
        <TABLE class="hs12pt" id=child6 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
        width=150 border=0>
          <TR height=20>
            <TD align=middle width=30><IMG height=9 
              src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
            <TD><A class=menuChild
              href="<?php echo U('ManySmsSend/Send');?>" 
              target=main>群发短信</A></TD></TR>          
          <TR height=20>
            <TD align=middle width=30><IMG height=9 
              src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
            <TD><A class=menuChild
              href="<?php echo U('ManySmsSend/Index');?>" 
              target=main>短信群发列表</A></TD></TR>                                     
          <TR height=4>
            <TD colSpan=2></TD></TR></TABLE>
         <?php } ?> 
      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(7) 
            href="javascript:void(0);">资金管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child7 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
        <?php if(in_array('RechargeLog/Index', $uri_ar) || empty($uri_ar)){ ?> 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('RechargeLog/index');?>" 
            target=main>会员充值列表</A></TD></TR>
        <?php } ?>   
        <?php if(in_array('SerialRecord/Index', $uri_ar) || empty($uri_ar)){ ?> 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('SerialRecord/index');?>" 
            target=main>会员充值流水号列表</A></TD></TR> 
        <?php } ?>   
        <?php if(in_array('Withdrawal/Index', $uri_ar) || empty($uri_ar)){ ?>                       
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Withdrawal/Index');?>" 
            target=main>会员提现列表</A></TD></TR> 
        <?php } ?>  
        <!--
        <?php if(in_array('Withdrawal/PaymentResultList', $uri_ar) || empty($uri_ar)){ ?>                       
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('Withdrawal/PaymentResultList');?>" 
            target=main>代付请求记录列表</A></TD></TR> 
        <?php } ?>     
        -->     
        <?php if(in_array('DealLog/Index', $uri_ar) || empty($uri_ar)){ ?>                                   
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('DealLog/Index');?>" 
            target=main>会员资金交易列表</A></TD></TR>  
        <?php } ?>   
        <?php if(in_array('ScoreLog/Index', $uri_ar) || empty($uri_ar)){ ?>                                                
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('ScoreLog/Index');?>" 
            target=main>会员积分交易列表</A></TD></TR> 
        <?php } ?>                                                                                                             
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>

      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(2) 
            href="javascript:void(0);">用户管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child2 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
      <?php if(in_array('Rbac/Index', $uri_ar) || empty($uri_ar)){ ?> 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Rbac/Index');?>" 
            target=main>用户管理</A></TD></TR>
       <?php } ?>  
       <?php if(in_array('Rbac/Role', $uri_ar) || empty($uri_ar)){ ?> 
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Rbac/Role');?>" 
            target=main>角色管理</A></TD></TR>
       <?php } ?>  
       <?php if(in_array('Rbac/Node', $uri_ar) || empty($uri_ar)){ ?>             
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Rbac/Node');?>" 
            target=main>节点管理</A></TD></TR>
       <?php } ?>        
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>
      
      <TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width=150 border=0>
        <TR height=22>
          <TD style="PADDING-LEFT: 30px" background=__PUBLIC__/images/menu_bt.jpg><A 
            class=menuParent onclick=expand(0) 
            href="javascript:void(0);">系统管理</A></TD></TR>
        <TR height=4>
          <TD></TD></TR></TABLE>
      <TABLE class="hs12pt" id=child0 style="DISPLAY: none" cellSpacing=0 cellPadding=0 
      width=150 border=0>
       <?php if(in_array('System/System', $uri_ar) || empty($uri_ar)){ ?>        
         <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('System/System');?>" 
            target=main>系统配置</A></TD></TR>
        <?php } ?>   
        <?php if(in_array('MsgBox/index', $uri_ar) || empty($uri_ar)){ ?>          
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('MsgBox/index',array('web_status'=>1));?>" 
            target=main>前台操作列表</A></TD></TR>                
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild
            href="<?php echo U('MsgBox/index',array('web_status'=>2));?>" 
            target=main>后台操作列表</A></TD></TR>
        <?php } ?>   
       <?php if(in_array('UploadManage/Index', $uri_ar) || empty($uri_ar)){ ?>        
         <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('UploadManage/Index');?>" 
            target=main>图片管理器</A></TD></TR>
        <?php } ?>           
        <?php if(in_array('Database/sql', $uri_ar) || empty($uri_ar)){ ?>                               
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Database/sql');?>" 
            target=main>SQL操作</A></TD></TR> 
<!--         <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('CreateSitemap/index');?>" 
            target=main>生成网站地图</A></TD></TR>   -->        
        <?php } ?>   
        <?php if(in_array('Database/Index', $uri_ar) || empty($uri_ar)){ ?>                                              
        <TR height=20>
          <TD align=middle width=30><IMG height=9 
            src="__PUBLIC__/images/menu_icon.gif" width=9></TD>
          <TD><A class=menuChild 
            href="<?php echo U('Database/Index');?>" 
            target=main>数据库备份</A></TD></TR> 
        <?php } ?>  
        <TR height=4>
          <TD colSpan=2></TD></TR></TABLE>
</TD>
    <TD width=1 bgColor=#d1e6f7></TD></TR></TABLE></BODY></HTML>