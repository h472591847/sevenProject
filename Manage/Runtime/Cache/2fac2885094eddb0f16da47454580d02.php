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

/**
 * @todo  batch_payment 批量改变成打款完成状态
 */
function batch_payment(){
  var w_ids = "";
  $("[name='del[]']:checked").each(function(i){       
    if(i == 0){
      w_ids = $(this).val();
    }else{
      w_ids = w_ids + "," + $(this).val();          
    }    
  })  

  $.ajax({
    url: '<?php echo U("Withdrawal/BatchPayment");?>',
    type: 'POST',
    dataType: 'json',
    data: "w_ids="+w_ids,
    success:function(msg){
      if(msg.status == 1){
        alert(msg.info);
        location.href = msg.redirect;return false;
      }else{
        alert(msg.info);return false;
      }
      
    }
  })
  
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
<form action="<?php echo U('Withdrawal/index');?>" method="get">    
    <tr>
      <td colspan="14" height="30">
        会员帐号：<input type="text" name="user_name" size="10" value="<?php echo ($page_param["user_name"]); ?>">&nbsp; &nbsp;             
        提现ID：<input type="text" name="w_id" size="10" value="<?php echo ($page_param["w_id"]); ?>">&nbsp; &nbsp;             
        订单号(单笔批次号)：<input type="text" name="biz_no" size="10" value="<?php echo ($page_param["biz_no"]); ?>">&nbsp; &nbsp;             
        提现金额：<input type="text" name="money_start" size="5" value="<?php echo ($page_param["money_start"]); ?>"> - <input type="text" name="money_end" size="5" value="<?php echo ($page_param["money_end"]); ?>">&nbsp; &nbsp;         
        <select name="audit_status">
            <option value="" >--- 请选择 ---</option>                             
            <option value="0" <?php if($page_param['audit_status'] == '0'): ?>selected="selected"<?php endif; ?>>申请中</option>
            <option value="1" <?php if($page_param['audit_status'] == '1'): ?>selected="selected"<?php endif; ?>>通过</option>
            <option value="2" <?php if($page_param['audit_status'] == '2'): ?>selected="selected"<?php endif; ?>>不通过</option>
            <option value="3" <?php if($page_param['audit_status'] == '3'): ?>selected="selected"<?php endif; ?>>打款完成</option>
            <option value="4" <?php if($page_param['audit_status'] == '4'): ?>selected="selected"<?php endif; ?>>已代付</option>
        </select>&nbsp; &nbsp; &nbsp;             
        申请时间：&nbsp; 
        <input type="text" name="k_start_time" class="text" id="k_start_time" value="<?php if($page_param['k_start_time'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_start_time"])); endif; ?>"  onfocus="this.blur(); return showCalendar('k_start_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_start_time');">      
        <input type="button" class="button" id="btn_start_time" value="选择时间" onClick="return showCalendar('k_start_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_start_time');" />
        <input type="button" class="button" value="清空时间" onClick="$('#k_start_time').val('');" />       
         - 
        <input type="text" name="k_end_time" id="k_end_time" value="<?php if($page_param['k_end_time'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_end_time"])); endif; ?>" class="text" onFocus="this.blur(); return showCalendar('k_end_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_time');">
        <input type="button" class="button" id="btn_end_time" value="选择时间" onClick="return showCalendar('k_end_time', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_time');" />
        <input type="button" class="button" value="清空时间" onClick="$('#k_end_time').val('');" />         
        <br><br>审核时间：&nbsp; 
        <input type="text" name="k_start_audit_date" class="text" id="k_start_audit_date" value="<?php if($page_param['k_start_audit_date'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_start_audit_date"])); endif; ?>"  onfocus="this.blur(); return showCalendar('k_start_audit_date', '%Y-%m-%d %H:%M:%S', false, false, 'btn_start_audit_date');">      
        <input type="button" class="button" id="btn_start_audit_date" value="选择时间" onClick="return showCalendar('k_start_audit_date', '%Y-%m-%d %H:%M:%S', false, false, 'btn_start_audit_date');" />
        <input type="button" class="button" value="清空时间" onClick="$('#k_start_audit_date').val('');" />       
         - 
        <input type="text" name="k_end_audit_date" id="k_end_audit_date" value="<?php if($page_param['k_end_audit_date'] != ''): echo (date('Y-m-d H:i:s',$page_param["k_end_audit_date"])); endif; ?>" class="text" onFocus="this.blur(); return showCalendar('k_end_audit_date', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_audit_date');">
        <input type="button" class="button" id="btn_end_audit_date" value="选择时间" onClick="return showCalendar('k_end_audit_date', '%Y-%m-%d %H:%M:%S', false, false, 'btn_end_audit_date');" />
        <input type="button" class="button" value="清空时间" onClick="$('#k_end_audit_date').val('');" />         
        &nbsp; <input type="submit" value="开始查询">                  
      </td>
    </tr>
</form> 
<tr> 
<td colspan="14">
<a href="<?php echo U('Withdrawal/index',array('action'=>'export_excel','user_name'=>$page_param['user_name'],'money_start'=>$page_param['money_start'],'money_end'=>$page_param['money_end'],'audit_status'=>$page_param['audit_status'],'k_start_time'=>$page_param['k_start_time'],'k_end_time'=>$page_param['k_end_time'],'w_id'=>$page_param['w_id'],'biz_no'=>$page_param['biz_no']));?>">导出结果中的数据</a>
| <a href="<?php echo U('Withdrawal/index',array('action'=>'export_excel_payment','user_name'=>$page_param['user_name'],'money_start'=>$page_param['money_start'],'money_end'=>$page_param['money_end'],'audit_status'=>$page_param['audit_status'],'k_start_time'=>$page_param['k_start_time'],'k_end_time'=>$page_param['k_end_time'],'k_start_audit_date'=>$page_param['k_start_audit_date'],'k_end_audit_date'=>$page_param['k_end_audit_date'],'w_id'=>$page_param['w_id'],'biz_no'=>$page_param['biz_no']));?>">导出成打款表格</a> &nbsp;
<!--
<?php if($page_param['audit_status'] != ''): ?><input type="button" name="filter_500" onclick="location.href='<?php echo U('Withdrawal/index',array('audit_status'=>1,'p_limit'=>'0,80','user_name'=>$page_param['user_name'],'money_start'=>$page_param['money_start'],'money_end'=>$page_param['money_end'],'audit_status'=>$page_param['audit_status'],'k_start_time'=>$page_param['k_start_time'],'k_end_time'=>$page_param['k_end_time'],'k_start_audit_date'=>$page_param['k_start_audit_date'],'k_end_audit_date'=>$page_param['k_end_audit_date'],'w_id'=>$page_param['w_id'],'biz_no'=>$page_param['biz_no']));?>'" value="筛选已通过80条">  
<?php else: ?>
<input type="button" name="filter_500" onclick="location.href='<?php echo U('Withdrawal/index',array('audit_status'=>1,'p_limit'=>'0,80'));?>'" value="筛选已通过80条"><?php endif; ?>
<?php if($p_limit != ''): ?><span>本批打款总额：<?php echo ($wi_count); ?>元;本批打款总笔数：<?php echo ($count_rows); ?>笔</span>  
<form name="payment_form" method="post" action="<?php echo U('Withdrawal/Payment');?>">
<input type="hidden" name="wids" id="wids" value="<?php echo ($wids); ?>">
<input type="submit" name="start_payments" value="开始批量打款" id="start_payments">
</form><?php endif; ?>
-->
</td>
</tr>
  <TR>
    <TD width=5% height="25" align=center >选择</TD>
    <TD width="5%" align="center" >ID</TD>
    <TD align="center" >会员帐号</TD>
    <td align="center">批次号码</td>
	  <TD width="15%" align="center" >提现金额</TD>
	  <TD width="5%" align="center" >手续费</TD>
  	<TD width="10%" align="center" >申请时间</TD>
    <TD width="5%" align="center" >提现状态</TD> 
    <TD width="5%" align="center" >提现类型</TD>  
<!--     <TD width="5%" align="center" >代付状态</TD> 
    <TD width="5%" align="center" >代付说明</TD>  -->
    <!-- <TD width="5%" align="center" >代付订单号</TD> -->
  	<TD width="10%" align="center" >审核时间</TD>
    <TD width="10%" align="center" >操作</TD>    
  </TR>
<form action="" method="post">
<?php if(is_array($info)): foreach($info as $key=>$v): ?><TR onMouseOver="this.style.background='#d0e2f5';" onMouseOut="this.style.background='#E3EFFB';">
    <TD height="22" align=center ><input type="checkbox" name="del[]" value="<?php echo ($v["id"]); ?>"></TD>
    <TD align="center"><?php echo ($v["id"]); ?></TD>
    <TD align="center"><a href="<?php echo U('Withdrawal/index',array('uid'=>$v['uid']));?>"><?php echo ($v["user_info"]["user_name"]); ?></a></TD>
    <td align="center"><?php if($v['biz_no'] != ''): echo ($v["biz_no"]); else: ?>暂无<?php endif; ?></td>
	<TD align="center"><?php echo (format_price($v["withdrawal_price"])); ?></TD>
	<TD align="center"><?php echo (format_price($v["management_price"])); ?></TD>
	<TD align="center">
      <?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?>
  </TD>    
    <TD align="center">
      <?php switch($v["audit_status"]): case "0": ?><span style="color:#000">申请中</span><?php break;?>
          <?php case "1": ?><span style="color:#4C9F2E">通过</span><?php break;?>
          <?php case "2": ?><span style="color:#F00">不通过</span><?php break;?>                     
          <?php case "3": ?><span style="color:#4C9F2E">打款完成</span><?php break;?>  
          <?php case "4": ?><span style="color:#4C9F2E">已代付</span><?php break; endswitch;?>        
    </TD>    
	
    <td align="center">
    <?php switch($v["type"]): case "0": ?><a href="<?php echo U('Withdrawal/index',array('type'=>0));?>">正常</a><?php break;?>
      <?php case "1": ?><a href="<?php echo U('Withdrawal/index',array('type'=>1));?>" style="color:#F00">特殊提现</a><?php break; endswitch;?>
    </td>
<!--     <td align="center">
    <?php if($v['error_code'] != ''): if($v['error_code'] == '0000'): ?><font style="color:#F08;">代付成功</font>
      <?php else: ?>
        <font style="color:#F00;"><?php echo ($v["error_code"]); ?></font><?php endif; ?>
    <?php else: ?>
      <font style="color:#666;">未代付</font><?php endif; ?>
    </td> -->
<!--     <td align="center">
    <?php if($v['error_msg'] != ''): echo ($v["error_msg"]); ?>
    <?php else: ?>
    <font style="color:#666;">未代付</font><?php endif; ?>
    </td> -->
    <!-- <TD align="center"><?php if($v['biz_no'] != ''): echo ($v["biz_no"]); else: ?><font style="color:#666;">未代付</font><?php endif; ?></TD> -->
    <TD align="center"><?php if($v['audit_status'] != 0): echo (date('Y-m-d H:i:s',$v["audit_date"])); else: ?>未审核<?php endif; ?></TD>
    <TD align="center">
    <?php if($v["audit_status"] == 0): ?><input type="button" value="去审核" onClick="location.href='<?php echo U('Withdrawal/Withdrawal',array('upid'=>$v['id']));?>'">
    <?php else: ?>
    <a href="<?php echo U('Withdrawal/Withdrawal',array('upid'=>$v['id']));?>" style="color:#4C9F2E">已审核</a><?php endif; ?>
    </TD>
  </TR><?php endforeach; endif; ?>
  <TR>
    <TD height="22" align=center ><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></TD>
    <TD align="center" >全选</TD>
    <td align="center"><input type="button" name="batch_payments" value="批量改变成打款状态" onclick="batch_payment()"></td>
    <TD colspan="7" align="center" class="hs12pt"><?php if($p_limit == ''): echo ($page); endif; ?></TD>    
    <TD align="center" class="hs12"><input type="submit" value="确认删除" onClick="if (confirm('确定要删除吗？\r一旦删除无法恢复数据')){ this.form.action='<?php echo U('Withdrawal/InfoDelete');?>'; }else{ return false; }"></TD>
    </TR>    
    </form>
  </TABLE>
</BODY></HTML>