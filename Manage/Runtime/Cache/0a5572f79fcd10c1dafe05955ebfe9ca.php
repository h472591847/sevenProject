<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/bdmin.css" type="text/css" rel="stylesheet">
<script src="__PUBLIC__/js/jQuery.js"></script>
</HEAD>
<BODY>
<form action="<?php echo U('Lottery/DoLotterySet');?>" method="post" onSubmit="return check()">
<input type="hidden" name="upid" value="<?php echo ($upid); ?>">
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TR height=28>
    <TD height="22" >当前位置: 大转盘/设置信息</TD>
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
      <td width="20%" height="25" align="right" >奖项:</td>
      <td>
        <input name="lt_prize" type="text" class="text" id="lt_prize" size="80" value="<?php echo ($edit_info["lt_prize"]); ?>" maxlength="400">         
      </td>
      <td align="left" height="40">&nbsp;（奖品名称）</td> 
    </tr>
    <tr>
      <td width="20%" height="25" align="right" >最小角度:</td>
      <td>
        <input name="lt_min" type="text" class="text" id="lt_min" size="80" value="<?php echo ($edit_info["lt_min"]); ?>" maxlength="400">         
      </td>
      <td align="left" height="40">&nbsp;(大于0度 小于360度) 多个角度用,分隔</td>    
    </tr>
    <tr>
      <td width="20%" height="25" align="right" >最大角度:</td>
      <td>
        <input name="lt_max" type="text" class="text" id="lt_max" size="80" value="<?php echo ($edit_info["lt_max"]); ?>" maxlength="400">         
      </td>
      <td align="left" height="40">&nbsp;最大角度 (大于0度 小于360度) 多个角度用,分隔</td>      
    </tr>
    <tr>
      <td width="20%" height="25" align="right" >中奖概率:</td>
      <td>
        <input name="lt_v" type="text" class="text" id="lt_v" size="80" value="<?php echo ($edit_info["lt_v"]); ?>" maxlength="400">         
      </td>
      <td align="left" height="40" width="50%">&nbsp;（单位 %）</td>
    </tr>
    <tr>
      <td width="20%" height="25" align="right" >中奖名额:</td>
      <td>
        <input name="lt_allowed" type="text" class="text" id="lt_allowed" size="80" value="<?php echo ($edit_info["lt_allowed"]); ?>" maxlength="400">         
      </td>
      <td align="left" height="40">&nbsp;</td>
    </tr>            
    <tr>
      <td height="25" colspan="3" align="center"><input type="submit" value="确认">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="reset" value="重填"></td>
    </tr>
  </table>
</form>
</BODY></HTML>