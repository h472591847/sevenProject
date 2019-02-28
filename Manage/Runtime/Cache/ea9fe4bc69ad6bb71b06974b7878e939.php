<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/bdmin.css" type="text/css" rel="stylesheet">
</HEAD>
<script language="JavaScript">
function CheckAll(form)  {
  for (var i=0;i<form.elements.length;i++)    {
    var e = form.elements[i];
    if (e.name != 'chkall')       e.checked = form.chkall.checked; 
   }
}
//页面加载后执行
window.onload=function (){
	//去掉文本框缓存
	var list=document.getElementsByTagName("input");
	for(var i=0;i<list.length && list[i];i++){
		if(list[i].type=="text"){ 
			list[i].autocomplete='off';
		}
	}
}
</script>
<BODY>
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TR height=28>
    <TD height="22" >当前位置: 用户管理/用户管理</TD>
  </TR>
  <TR>
    <TD bgColor=#b1ceef height=1></TD></TR></TABLE>
<TABLE class="hs12pt" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="22" background="__PUBLIC__/images/title_bg2.jpg">&nbsp;&nbsp;&nbsp;<span class="baise12ct"><?php echo ($display_txt); ?>用户</span></td>
  </tr>
</table>
<TABLE class="hs12pt" width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#175CB7" bordercolordark="#E3EFFB">
<form name="form1" method="post" action="<?php echo U('Rbac/UserHandle');?>">
<input type="hidden" name="upid" value="<?php echo ($upid); ?>">
    <tr>
      <td width="20%" height="25" align="center" >账号:</td>
      <td width="80%"><input name="username" type="text" id="username" value="<?php echo ($edit_user["username"]); ?>" size="25" maxlength="20"></td>
    </tr>
    <tr>
      <td width="20%" height="25" align="center">密码:</td>
      <td width="80%"><input name="password" type="password" id="pas" value="" size="35" maxlength="200"><?php if(!empty($upid)): ?>注:留空为不修改<?php endif; ?></td>
    </tr>
    <tr>
      <td width="20%" height="25" align="center">重复密码:</td>
      <td width="80%"><input name="pass" type="password" id="pass" value="" size="35" maxlength="200"></td>
    </tr>
    <tr>
      <td width="20%" height="25" align="center">权限:</td>
      <td width="80%"><?php if($edit_user['username'] == C('RBAC_SUPERADMIN') and $upid): ?>超级管理员<?php else: if(is_array($role)): foreach($role as $key=>$v): ?><input name="role[]" type="checkbox" value="<?php echo ($v["id"]); ?>"<?php if(!empty($v['access'])): ?>checked<?php endif; ?>><?php echo ($v["name"]); ?>(<?php echo ($v["remark"]); ?>)<?php endforeach; endif; endif; ?></td>
    </tr>
    <tr>
      <td height="25" align="center" >开启状态<?php echo ($edit_user["lock"]); ?></td>
      <td><input name="lock" type="radio" value="0"<?php if(!empty($upid)): if($edit_user['lock']==0): ?>checked<?php endif; else: ?> checked<?php endif; ?>>
      开启
        <input name="lock" type="radio" value="1"<?php if($edit_user['lock']==1): ?>checked<?php endif; ?>>
      关闭</td>
    </tr>
    <tr>
      <td height="25" colspan="2" align="center"><input type="submit" value="确认<?php echo ($display_txt); ?>"></td>
    </tr>
    </form>
  </table>
<?php if(empty($upid)): ?><TABLE class="hs12pt" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="22" background="__PUBLIC__/images/title_bg2.jpg">&nbsp;&nbsp;&nbsp;<span class="baise12ct">信息管理</span></td>
  </tr>
</table>
  <TABLE class="hs12pt" width="100%" border=1 align=center cellPadding=2 cellSpacing=0 bordercolor="#175CB7" bordercolordark="#E3EFFB">  
  <TR>
    <TD height="25" colspan="2" align=center >选择</TD>
    <TD width="5%" align="center" >ID</TD>
    <TD width="15%" align="center" >账号</TD>
    <TD width="15%" align="center" >权限</TD>
    <TD width="15%" align="center" >最近登录时间</TD>
    <TD width="15%" align="center" >最近登录IP</TD>
    <TD width="10%" align="center" >状态</TD>
	<TD align="center" >操作</TD>
  </TR>
<form action="javascript:" method="post">
<?php if(is_array($user)): foreach($user as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD height="22" colspan="2" align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <TD align="center"><?php echo ($v["username"]); ?></TD>
    <TD align="center"><?php if($v['username'] != C('RBAC_SUPERADMIN')): if(is_array($v["role"])): foreach($v["role"] as $key=>$value): ?>[<?php echo ($value["remark"]); ?>]<?php endforeach; endif; else: ?>[超级管理员]<?php endif; ?></TD>
    <TD align="center"><?php if($v['logintime']): echo (date('Y-m-d H:i:s',$v["logintime"])); else: ?>暂未登录<?php endif; ?></TD>
    <TD align="center"><?php if($v['loginip']): echo ($v["loginip"]); else: ?>暂未登录<?php endif; ?></TD>
    <TD align="center"><?php if($v['lock']){ echo '关闭';}else{ echo '开启';} ?></TD>
	<TD align="center"><input type="button" value="修改" onClick="location.href='<?php echo U('Rbac/Index',array('upid'=>$v['id']));?>'"></TD>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD width="5%" height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD width="5%" align="center" >全选</TD>
    <TD colspan="6" align="left" class="hs12">&nbsp;</TD>
    <TD align="center" class="hs12"><input type="submit" value="删除选中" onClick="if (confirm('确定要删除吗？r一旦删除无法恢复数据')){ this.form.action='<?php echo U('Rbac/UserDelete');?>';}else{ return false;}"></TD>
    </TR>
</form>
  </TABLE><?php endif; ?>
</BODY></HTML>