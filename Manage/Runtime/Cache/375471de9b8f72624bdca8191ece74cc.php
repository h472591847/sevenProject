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
<form action="<?php echo U('MsgBox/index');?>" method="post">
    <input type="hidden" name="web_status" value="<?php echo ($web_status); ?>">
    <tr>
      <td colspan="11" height="30">        
        <?php if($web_status == 2): ?>动作:
        <select name="kwz">
          <option value="">==请选择==</option>
          <?php if(is_array($kwz_list)): foreach($kwz_list as $key=>$v): ?><option value="<?php echo ($v["title"]); ?>" <?php if($page_param['kwz'] == $v['title']): ?>selected="selected"<?php endif; ?>><?php echo ($v["title"]); ?></option><?php endforeach; endif; ?>
        </select>
        &nbsp; &nbsp;         
        <?php else: ?>
        操作标题：<input type="text" name="kwz" value="<?php echo ($page_param["kwz"]); ?>" size="10">&nbsp; &nbsp;<?php endif; ?>
        <?php if($web_status == 2): ?>操作人：
        <select name="user_id">
          <option value="">==请选择==</option>
          <?php if(is_array($user_list)): foreach($user_list as $key=>$v): ?><option value="<?php echo ($v["user_id"]); ?>" <?php if($page_param['user_id'] == $v['user_id']): ?>selected="selected"<?php endif; ?>><?php echo ($v["username"]); ?></option><?php endforeach; endif; ?>
        </select>        
        &nbsp;<?php endif; ?>
        
        操作内容:<input type="text" name="content" value="<?php echo ($page_param["content"]); ?>" size="20">
        &nbsp;         
        
        操作时间：&nbsp; 
        <input type="text" name="k_start_time" class="text" id="k_start_time" value="<?php echo ($page_param["k_start_time"]); ?>"   onfocus="return showCalendar('k_start_time', '%Y-%m-%d', false, false, 'btn_start_time');">      
        <input type="button" class="button" id="btn_start_time" value="选择时间" onclick="return showCalendar('k_start_time', '%Y-%m-%d', false, false, 'btn_start_time');" />
        <input type="button" class="button" value="清空时间" onclick="$('#k_start_time').val('');" />       
         - 
        <input type="text" name="k_end_time" id="k_end_time" value="<?php echo ($page_param["k_end_time"]); ?>" class="text" onfocus="return showCalendar('k_end_time', '%Y-%m-%d', false, false, 'btn_end_time');">
        <input type="button" class="button" id="btn_end_time" value="选择时间" onclick="return showCalendar('k_end_time', '%Y-%m-%d', false, false, 'btn_end_time');" />
        <input type="button" class="button" value="清空时间" onclick="$('#e_end_time').val('');" />         
        &nbsp; <input type="submit" value="开始查询">          
      </td>
    </tr>
</form> 
  <TR>
    <TD align="center">选择</TD>
    <TD align="center">ID</TD>
    <TD align="center">操作标题</TD>
    <TD align="center">操作内容</TD>
    <TD align="center">操作人</TD>
    <TD align="center">操作时间</TD>
    <TD align="center">操作类型</TD>
    <TD align="center">执行SQL</TD>

  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <td align="center"><?php echo ($v["title"]); ?></td>
    <td align="center"><?php echo ($v["content"]); ?></td>
    <td align="center"><a href="<?php echo U('MsgBox/index',array('user_id'=>$v['user_id'],'web_status'=>$web_status));?>"><?php echo ($v["user_name"]); ?></a></td>
    <td align="center"><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></td>
    <td align="center">
    <?php switch($v["type"]): case "1": ?>账户操作<?php break;?>                     
        <?php case "2": ?>信息操作<?php break; endswitch;?>          
    </td>
    <td align="center" width="10%"><textarea name="sql" id="sql" cols="50" ><?php echo ($v["sql"]); ?></textarea></td>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD align="center" >全选<input type="hidden" name="web_status" value="<?php echo ($web_status); ?>"></TD>
    <TD colspan="5" align="center" class="hs12pt"><?php echo ($page); ?></TD>    
    <TD align="center" class="hs12"><input type="submit" value="确认删除" onClick="if (confirm('确定要删除吗？\r一旦删除无法恢复数据')){ this.form.action='<?php echo U('MsgBox/InfoDelete',array('web_status'=>$web_status));?>'; }else{ return false; }"></TD>
    </TR></form>
  </TABLE>
</BODY></HTML>