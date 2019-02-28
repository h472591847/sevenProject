<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/bdmin.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="__PUBLIC__/Js/jQuery.js" ></script>
<script type="text/javascript" src="__PUBLIC__/Js/calendar/calendar.php?lang=zh-cn" ></script>
<LINK href="__PUBLIC__/Js/calendar/calendar.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/Js/calendar/calendar.js"></script>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function CheckAll(form)  {
  for (var i=0;i<form.elements.length;i++)    {
    var e = form.elements[i];
    if (e.name != 'chkall')       e.checked = form.chkall.checked; 
   }
  }
</script>
</HEAD>
<BODY>
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TR height=28>
    <TD height="22" >当前位置: 信息中心/信息管理</TD>
  </TR>
  <TR>
    <TD bgColor=#b1ceef height=1></TD></TR></TABLE>
<TABLE class="hs12pt" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="22" background="__PUBLIC__/images/title_bg2.jpg">&nbsp;&nbsp;&nbsp;<span class="baise12ct">信息管理</span></td>
  </tr>
</table>
  <TABLE class="hs12pt" width="100%" border=1 align=center cellPadding=2 cellSpacing=0 bordercolor="#175CB7" bordercolordark="#E3EFFB">
<form action="<?php echo U('Invested/index');?>" method="post">
    <TR>
      <TD height="25" colspan=12 align=center >项目名称:&nbsp;
      <select name="lid">
            <option value="">--- 请选择 ---</option>
          <?php if(is_array($group_loan_res)): foreach($group_loan_res as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if($page_param['lid'] == $v['id']): ?>selected="selected"<?php endif; ?>>id:<?php echo ($v["id"]); ?>---<?php echo ($v["loan_title"]); ?></option><?php endforeach; endif; ?>
      </select>&nbsp; &nbsp; 
      ID：<input type="text" name="id" value="<?php echo ($page_param['id']); ?>">&nbsp;&nbsp;&nbsp;
      投资人：&nbsp;
      <select name="uid">
            <option value="" >--- 请选择 ---</option>
          <?php if(is_array($group_user_res)): foreach($group_user_res as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if($page_param['uid'] == $v['id']): ?>selected="selected"<?php endif; ?>>id:<?php echo ($v["id"]); ?>---<?php echo ($v["user_name"]); ?></option><?php endforeach; endif; ?>
      </select>&nbsp; &nbsp; &nbsp; 
      
      日期区间：&nbsp; 
      <input type="text" name="k_start_time" value="<?php echo ($page_param["k_start_time"]); ?>" class="text" id="k_start_time"  onfocus="this.blur(); return showCalendar('k_start_time', '%Y-%m-%d', false, false, 'btn_start_time');">
      
      <input type="button" class="button" id="btn_start_time" value="选择时间" onclick="return showCalendar('k_start_time', '%Y-%m-%d', false, false, 'btn_start_time');" />
      <input type="button" class="button" value="清空时间" onclick="$('#k_start_time').val('');" />&nbsp; &nbsp;       
       - &nbsp; &nbsp; 
      <input type="text" name="k_end_time" id="k_end_time" value="<?php echo ($page_param["k_end_time"]); ?>" class="text" onfocus="this.blur(); return showCalendar('k_end_time', '%Y-%m-%d', false, false, 'btn_end_time');">
      <input type="button" class="button" id="btn_end_time" value="选择时间" onclick="return showCalendar('k_end_time', '%Y-%m-%d', false, false, 'btn_end_time');" />
      <input type="button" class="button" value="清空时间" onclick="$('#k_end_time').val('');" />         
      &nbsp; <input type="submit" value="开始查询">
      </TD>      
    </TR>
</form>  
  <tr>
    <td colspan="18" align="center">        
        统计数据-> 当前投资总额：<?php echo (format_price($in_count)); ?> &nbsp;&nbsp;&nbsp;使用奖励金额总额：<?php echo (format_price($in_bouns_money)); ?>&nbsp;&nbsp;&nbsp;总利息：<?php echo ($pure); ?>&nbsp;&nbsp;&nbsp;管理费总额：<?php echo (format_price($in_count_management_price)); ?>&nbsp;&nbsp;&nbsp;        
    </td>
  </tr>   
  <tr> <td colspan="11"><a href="<?php echo U('Invested/index',array('action'=>'export_excel','lid'=>$page_param['lid'],'uid'=>$page_param['uid'],'k_start_time'=>$page_param['k_start_time'],'k_end_time'=>$page_param['k_end_time']));?>">导出结果中的数据</a></td></tr>
  <TR>    
  <TD align="center" >ID</TD>
  <TD align="center" >项目名称</TD>
	<TD align="center" >借款额</TD>
  <TD align="center" >当前项目已投资总额</TD>
  <TD align="center" >还款总金额</TD>
  <TD align="center" >投资人</TD>
	<TD align="center" >投资金额</TD> 
  <TD align="center" >使用奖励金额</TD>  
	<TD align="center" >还款金额</TD>  
  <TD align="center" >还款状态</TD>  
	<TD align="center" >投资时间</TD>
  <TD align="center" >计息日</TD> 
  <TD align="center" >到期时间(还款日)</TD>  
  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">    
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <TD align="center"><?php echo ($v["loan_title"]); ?></TD>
    <TD align="center"><?php echo (format_price($v["loan_money"])); ?> 元</TD>
	  <TD align="center"><?php echo (format_price($v["invested_count"])); ?> 元</TD>  
    <TD align="center"><?php echo (format_price($v["repayment_count"])); ?> 元</TD>
    <TD align="center"><?php echo ($v["user_name"]); ?></TD>
	  <TD align="center"><?php echo (format_price($v["amount"])); ?> 元</TD>    
    <TD align="center"><?php if($v['bonus_money'] != 0): ?><font style="color:#F00"><?php echo ($v["bonus_money"]); ?></font> 元<?php else: ?>未使用<?php endif; ?></TD>    
	  <TD align="center"><?php echo (format_price($v["repayment_amount"])); ?> 元</TD>    
    <TD align="center"><?php if($v['status'] == 1): ?><font style="color:#4C9F2E">已还款</font><?php else: ?>未还款<?php endif; ?></TD>
    <TD align="center"><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></TD>
    <TD align="center"><?php if(empty($v["repay_confirm_time"])): ?>未确认<?php else: echo (date('Y-m-d H:i:s',$v["repay_confirm_time"])); endif; ?></TD>  
    <TD align="center"><?php if($v['deal_status'] == 4): echo (date('Y-m-d H:i:s',$v["expire_time"])); else: ?>未确认<?php endif; ?></TD>    
  </TR><?php endforeach; endif; ?>
  <TR>        
    <TD colspan="9" align="center" class="hs12pt"><?php echo ($page); ?></TD>      
    </TR></form>
  </TABLE>
</BODY></HTML>