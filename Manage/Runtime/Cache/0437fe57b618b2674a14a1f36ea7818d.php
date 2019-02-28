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
<form action="<?php echo U('ScoreLog/index');?>" method="post">
    <tr>
      <td colspan="11" height="30">
        用户名：<input type="text" name="user_name" size="10" value="<?php echo ($page_param["user_name"]); ?>">&nbsp; &nbsp;       
        交易金额：<input type="text" name="score_start" value="<?php echo ($page_param["score_start"]); ?>" size="5"> - <input type="text" name="score_end" value="<?php echo ($page_param["score_end"]); ?>" size="5">&nbsp; &nbsp;         
        交易类型:<select name="type">
          <option value="" >--- 请选择 ---</option>      
          <option value="1" <?php if($page_param["type"] == '1'): ?>selected="selected"<?php endif; ?>>签到</option> 
          <option value="2" <?php if($page_param["type"] == '2'): ?>selected="selected"<?php endif; ?>>邀请注册</option> 
          <option value="3" <?php if($page_param["type"] == '3'): ?>selected="selected"<?php endif; ?>>认证操作</option> 
          <option value="4" <?php if($page_param["type"] == '4'): ?>selected="selected"<?php endif; ?>>投资</option> 
          <option value="5" <?php if($page_param["type"] == '5'): ?>selected="selected"<?php endif; ?>>系统操作</option> 
          <option value="6" <?php if($page_param["type"] == '6'): ?>selected="selected"<?php endif; ?>>积分兑换</option> 
          <option value="7" <?php if($page_param["type"] == '7'): ?>selected="selected"<?php endif; ?>>意见奖励</option>     
        </select>&nbsp; &nbsp;        
        交易时间：&nbsp; 
        <input type="text" name="k_start_time" class="text" id="k_start_time" value="<?php if($page_param['k_start_time'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_start_time"])); endif; ?>"  onfocus="return showCalendar('k_start_time', '%Y-%m-%d', false, false, 'btn_start_time');">      
        <input type="button" class="button" id="btn_start_time" value="选择时间" onclick="return showCalendar('k_start_time', '%Y-%m-%d', false, false, 'btn_start_time');" />
        <input type="button" class="button" value="清空时间" onclick="$('#k_start_time').val('');" />       
         - 
        <input type="text" name="k_end_time" id="k_end_time" value="<?php if($page_param['k_end_time'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_end_time"])); endif; ?>" class="text" onfocus="return showCalendar('k_end_time', '%Y-%m-%d', false, false, 'btn_end_time');">
        <input type="button" class="button" id="btn_end_time" value="选择时间" onclick="return showCalendar('k_end_time', '%Y-%m-%d', false, false, 'btn_end_time');" />
        <input type="button" class="button" value="清空时间" onclick="$('#e_end_time').val('');" />         
        &nbsp; <input type="submit" value="开始查询">          
      </td>
    </tr>
</form> 
  <TR>
    <TD width=5% height="25" align=center >选择</TD>
    <TD width="5%" align="center" >ID</TD>
    <TD align="center">交易时间</TD>
    <TD align="center">交易类型</TD>
    <TD align="center">单号</TD>
    <TD align="center">明细</TD>
    <TD align="center">剩余积分</TD>
    <TD align="center">积分</TD>
    <TD align="center">用户名</TD>
    <TD align="center">操作</TD>
  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD height="22" align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <td align="center"><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></td>
    <td align="center">
        <?php echo (get_score_log_type($v["type"])); ?>     
    </td>
    <td align="center"><?php echo (date('YmdHis',$v["create_time"])); ?></td>
    <td align="center" <?php if($v['is_label'] == 1): ?>style="color:#1B61B9;"<?php endif; ?>><?php echo ($v["detail"]); ?></td>
    <td align="center"><?php if($v['remain_score'] != ''): echo ($v["remain_score"]); else: ?>暂无<?php endif; ?></td>
    <td align="center">
    <?php if($v['pay_status'] == 1): ?><font style="color:#156430;font-weight:bold;">+</font>
    <?php else: ?>
    <font style="color:#F00;font-weight:bold;">-</font><?php endif; ?>
    <?php echo ($v["score"]); ?>
    </td>
    <td align="center"><a href="<?php echo U('ScoreLog/index',array('uid'=>$v['uid']));?>"><?php echo ($v["user_name"]); ?></a></td>
    <td align="center">
    <?php if($uid != ''): if($v['is_label'] == 0): ?><button  onClick="if (confirm('确定要设为已读状态吗？')){ this.form.action='<?php echo U('ScoreLog/IsLabel',array('uid'=>$uid,'id'=>$v['id'],'is_label'=>1));?>'; }else{ return false; }">未标记</button>
       <?php else: ?>
          <button   disabled="disabled">已标记</button><?php endif; endif; ?>
    </td>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD align="center" >全选</TD>
    <TD colspan="6" align="center" class="hs12pt"><?php echo ($page); ?></TD>    
    <TD align="center" class="hs12"><input type="submit" value="确认删除" onClick="if (confirm('确定要删除吗？\r一旦删除无法恢复数据')){ this.form.action='<?php echo U('ScoreLog/InfoDelete');?>'; }else{ return false; }"></TD>
    </TR></form>
  </TABLE>
</BODY></HTML>