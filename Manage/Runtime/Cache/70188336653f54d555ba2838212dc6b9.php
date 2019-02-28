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
<form action="<?php echo U('SerialRecord/index');?>" method="post">
    <tr>
      <td colspan="11" height="30">
        用户名：<input type="text" name="user_name" size="10" value="<?php echo ($page_param["user_name"]); ?>">&nbsp; &nbsp;        
        订单号：<input type="text" name="serialID" size="10" value="<?php echo ($page_param["serialID"]); ?>">&nbsp; &nbsp;        
&nbsp; <input type="submit" value="开始查询">          
      </td>
    </tr>
</form> 

<tr> <td colspan="11"><a href="<?php echo U('SerialRecord/index',array('action'=>'export_excel'));?>">导出结果中的数据</a></td></tr>
  <TR>
    <TD width=5% height="25" align=center >选择</TD>
    <TD width="5%" align="center" >ID</TD>    
    <TD align="center">用户ID</TD>
    <TD align="center">请求流水号</TD>
    <TD align="center">充值金额</TD>
    <TD align="center">用户名</TD>
    <TD align="center">请求时间</TD>
  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD height="22" align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>    
    <td align="center"><?php echo ($v["user_id"]); ?></td>
    <td align="center"><?php echo ($v["serialID"]); ?></td>
    <td align="center"><?php echo (format_price($v["money"])); ?></td>
    <td align="center"><?php echo ($v["user_name"]); ?></td>
    <td align="center"><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></td>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD align="center" >全选</TD>
    <TD colspan="5" align="center" class="hs12pt"><?php echo ($page); ?></TD>    
    <!-- <TD align="center" class="hs12"><input type="submit" value="确认删除" onClick="if (confirm('确定要删除吗？\r一旦删除无法恢复数据')){ this.form.action='<?php echo U('RechargeLog/InfoDelete');?>'; }else{ return false; }"></TD> -->
    </TR></form>
  </TABLE>
</BODY></HTML>