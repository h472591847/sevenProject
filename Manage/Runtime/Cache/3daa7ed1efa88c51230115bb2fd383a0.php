<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/bdmin.css" type="text/css" rel="stylesheet">
<script src="__PUBLIC__/js/jQuery.js"></script>
</HEAD>
<BODY>
<form action="<?php echo U('ManySmsSend/SendHandle');?>" method="post" onSubmit="return check()">
<input type="hidden" name="upid" value="<?php echo ($upid); ?>">
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TR height=28>
    <TD height="22" >当前位置: 信息中心/添加信息</TD>
  </TR>
  <TR>
    <TD bgColor=#b1ceef height=1></TD></TR></TABLE>
<TABLE class="hs12pt" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="22" background="__PUBLIC__/images/title_bg2.jpg">&nbsp;&nbsp;&nbsp;<span class="baise12ct"><?php echo ($display_txt); ?>信息</span></td>
  </tr>
</table>
  <TABLE class="hs12pt" width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#175CB7" bordercolordark="#E3EFFB" >
	  <tr>    
      <td width="20%" height="25" align="center" >发送目的号码:</td>
      <td colspan="5" align="left">
        <select name="web_user_id">
         <option value="0">发送给所有有效的前台会员用户</option> 
          <?php if(is_array($web_user_list)): foreach($web_user_list as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>">ID:【<?php echo ($v["id"]); ?>】用户名：【<?php echo ($v["user_name"]); ?>】-号码：【<?php echo ($v["phone"]); ?>】</option><?php endforeach; endif; ?>
        </select>
          * 默认不选择，发送给所有前台会员。
      </td>
    </tr>
    

    <tr>
      <td width="20%" height="25" align="center" >发送内容:</td>
      <td colspan="5"><textarea cols="55" rows="5" name="content"></textarea> *最多可输入60个字</td>
    </tr>
                                                      
<tbody>
    <tr>
      <td height="25" colspan="6" align="center"><input type="submit" value="确认发送"></td>
    </tr>
  </table>
</form>
</BODY></HTML>