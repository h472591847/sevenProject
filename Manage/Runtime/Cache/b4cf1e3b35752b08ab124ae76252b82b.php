<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/bdmin.css" type="text/css" rel="stylesheet">
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
    <TD height="22" >当前位置: 大转盘/信息管理</TD>
  </TR>
  <TR>
    <TD bgColor=#b1ceef height=1></TD></TR></TABLE>
<TABLE class="hs12pt" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="22" background="__PUBLIC__/images/title_bg2.jpg">&nbsp;&nbsp;&nbsp;<span class="baise12ct">信息管理</span></td>
  </tr>
</table>
  <TABLE class="hs12pt" width="100%" border=1 align=center cellPadding=2 cellSpacing=0 bordercolor="#175CB7" bordercolordark="#E3EFFB">
<!-- <form action="" method="post">
<input type="hidden" name="pid" value="<?php echo ($pid); ?>">
    <TR>
      <TD height="25" colspan="2" align=center >按类别查看：</TD>
      <TD height="25" colspan="3" align=center >
        <select class="text" onChange="MM_jumpMenu('self',this,0)">
          <option value="<?php echo U('Info/Index');?>">-查看全部信息-</option>
  <?php if(is_array($cate)): foreach($cate as $key=>$v): ?><option value="<?php echo U('Info/Index',array('pid'=>$v['id']));?>" <?php if(I('pid')==$v['id']){ echo 'selected="selected"';} ?> ><?php echo ($v["html"]); echo ($v["name"]); ?></option><?php endforeach; endif; ?>
      </select></TD>
      <TD height="25" align=center >模糊查询:</TD>
      <TD width="10%" height="25" align=center ><input name="ss" type="text" class="text" style="color:#999999" onFocus="this.style.color='#000000';if(this.value=='请输入查询标题')this.value='';" onBlur="if(this.value==''){this.value='请输入查询标题';this.style.color='#999999';}" value="<?php echo ($ss); ?>" size="20" maxlength="50"></TD>
      <TD height="25" align=center ><input type="submit" value="查询"></TD>
    </TR>
</form>   -->
  <TR>
    <TD width=5% height="25" align=center >选择</TD>
    <TD width="5%" align="center" >ID</TD>
    <TD align="center" >奖品名称</TD>
	  <TD width="15%" align="center" >最小角度</TD>
	  <TD width="5%" align="center" >最大角度</TD>
	  <TD width="10%" align="center" >中奖概率</TD>
    <TD align="center" >中奖名额</TD>
    <TD width="10%" align="center" >操作</TD>
  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD height="22" align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <TD align="center"><?php echo ($v["lt_prize"]); ?></TD>
  	<TD align="center"><?php echo ($v["lt_min"]); ?></TD>
  	<TD align="center"><?php echo ($v["lt_max"]); ?></TD>
  	<TD align="center"><?php echo ($v["lt_v"]); ?>%</TD>
    <TD align="center"><?php echo ($v["lt_allowed"]); ?>名</TD>
    <TD align="center"><input type="button" value="设置" onClick="location.href='<?php echo U('Lottery/LotterySet',array('upid'=>$v['id']));?>'"></TD>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD align="center" >全选</TD>
    <TD colspan="5" align="center" class="hs12pt"><?php echo ($page); ?></TD>    
    <TD align="center" class="hs12"><input type="submit" value="确认删除" onClick="if (confirm('确定要删除吗？\r一旦删除无法恢复数据')){ this.form.action='<?php echo U('Lottery/InfoDelete');?>'; }else{ return false; }"></TD>
    </TR></form>
  </TABLE>
</BODY></HTML>