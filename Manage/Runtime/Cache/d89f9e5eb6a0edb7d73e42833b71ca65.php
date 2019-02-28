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
<form action="<?php echo U('RechargeLog/index');?>" method="post">
    <tr>
      <td colspan="11" height="30">
        用户名：<input type="text" name="user_name" size="10" value="<?php echo ($page_param["user_name"]); ?>">&nbsp; &nbsp;  
        支付流水号:<input type="text" name="orderNo" size="18" value="<?php echo ($page_param["orderNo"]); ?>">&nbsp; &nbsp;  
        按完成时间：&nbsp; 
        <input type="text" name="k_start_time" class="text" id="k_start_time"  onfocus="return showCalendar('k_start_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_start_time');" value="<?php if($page_param['k_start_time'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_start_time"])); endif; ?>">      
        <input type="button" class="button" id="btn_start_time" value="选择时间" onClick="return showCalendar('k_start_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_start_time');" />
        <input type="button" class="button" value="清空时间" onClick="$('#k_start_time').val('');" />&nbsp; &nbsp;       
         - &nbsp; &nbsp; 
        <input type="text" name="k_end_time" id="k_end_time" class="text" onFocus="return showCalendar('k_end_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_time');" value="<?php if($page_param['k_end_time'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_end_time"])); endif; ?>">
        <input type="button" class="button" id="btn_end_time" value="选择时间" onClick="return showCalendar('k_end_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_time');" />
        <input type="button" class="button" value="清空时间" onClick="$('#e_end_time').val('');" />     
        差异对比流水号组:
        <textarea name="text" rows="1" id="text"><?php echo ($page_param["text"]); ?></textarea>
&nbsp; <input type="submit" value="开始查询">          
      </td>
    </tr>
</form> 
<tr>
  <td colspan="11">统计数据-> 当前充值总额：<?php echo (format_price($count_amount)); ?></td>
</tr>
<tr> <td colspan="11"><a href="<?php echo U('RechargeLog/index',array('action'=>'export_excel','user_name'=>$page_param['user_name'],'orderNo'=>$page_param['orderNo'],'k_start_time'=>$page_param['k_start_time'],'k_end_time'=>$page_param['k_end_time']));?>">导出结果中的数据</a></td></tr>
  <TR>
    <TD width=5% height="25" align=center >选择</TD>
    <TD width="5%" align="center" >ID</TD>
    <TD align="center">商户订单号</TD>
    <TD align="center">支付流水号</TD>
    <TD align="center">处理结果码</TD>
    <TD align="center">状态码</TD>
    <TD align="center">订单金额</TD>
    <TD align="center">实付金额</TD>
    <TD align="center">收单时间</TD>
    <TD align="center">完成时间</TD> 
    <TD align="center">充值用户名</TD>
  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD height="22" align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <td align="center"><?php echo ($v["orderID"]); ?></td>
    <td align="center"><?php echo ($v["orderNo"]); ?></td>
    <td align="center"><?php echo ($v["resultCode"]); ?></td>
    <td align="center"><?php echo ($v["stateCode"]); ?></td>
    <td align="center"><?php echo (format_price($v["orderAmount"])); ?></td>
    <td align="center"><?php echo (format_price($v["payAmount"])); ?></td>
    <td align="center">
      <?php echo (format_order_time($v["acquiringTime"])); ?>
    </td>
    <td align="center"><?php echo (format_order_time($v["completeTime"])); ?></td>
    <td align="center"><a href=<?php echo U('RechargeLog/index',array('user_id'=>$v['user_id']));?>><?php echo ($v["user_name"]); ?></a></td>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD align="center" >全选</TD>
    <TD colspan="8" align="center" class="hs12pt"><?php echo ($page); ?></TD>    
    <TD align="center" class="hs12"><input type="submit" value="确认删除" onClick="if (confirm('确定要删除吗？\r一旦删除无法恢复数据')){ this.form.action='<?php echo U('RechargeLog/InfoDelete');?>'; }else{ return false; }"></TD>
    </TR></form>
  </TABLE>
</BODY></HTML>