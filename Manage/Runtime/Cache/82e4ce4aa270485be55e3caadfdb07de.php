<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="__PUBLIC__/css/bdmin.css" type="text/css" rel="stylesheet">
</HEAD>
<BODY>
<form action="<?php echo U('System/UpdateSystem');?>" method="post" name="form_wrap" enctype="multipart/form-data">
<TABLE class="hs12pt" cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TR height=28>
    <TD height="22" >当前位置: <?php echo ($main_title); ?></TD>
  </TR>
  <TR>
    <TD bgColor=#b1ceef height=1></TD></TR></TABLE>
<TABLE class="hs12pt" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="22" background="__PUBLIC__/images/title_bg2.jpg">&nbsp;&nbsp;&nbsp;<span class="baise12ct"><?php echo ($main_title); ?>信息</span></td>
  </tr>
</table>
  <TABLE class="hs12pt" width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#175CB7" bordercolordark="#E3EFFB" >
    <tr>
      <td colspan="2" align="center"><b>SEO信息设置</b></td>
    </tr> 
    <tr>
      <td align="right" width="20%" >网站标题</td>
      <td><input type="text" name="WEB_TITLE" value="<?php echo (C("web_title")); ?>" size="150" maxlength="150" /></td>
    </tr>
    <tr>
      <td align="right">网站关键字</td>
      <td><textarea name="WEB_KEYWORDS" cols="150" rows="2" style="font-size:12px;"><?php echo (C("web_keywords")); ?></textarea></td>
    </tr>
    <tr>
      <td align="right">网站描述</td>
      <td><textarea name="WEB_DESCRIPTION" cols="150" rows="3" style="font-size:12px;"><?php echo (C("web_description")); ?></textarea></td>
    </tr>  
    <tr>
      <td align="right">地址</td>
      <td><input type="text" name="WEB_ADDRESS" value="<?php echo (C("web_address")); ?>" size="150" maxlength="130"/></td>
    </tr>  
    <tr>
      <td align="right">服务时间</td>
      <td><input type="text" name="WEB_TIME" value="<?php echo (C("web_time")); ?>" size="150" maxlength="130"/></td>
    </tr>  
    <tr>
      <td align="right">热线电话</td>
      <td><input type="text" name="WEB_TEL" value="<?php echo (C("web_tel")); ?>" size="150" maxlength="130"/></td>
    </tr>                   
    <tr>
      <td align="right">网站版权信息</td>
      <td><input type="text" name="WEB_COPYRIGHT" value="<?php echo (C("web_copyright")); ?>" size="150" maxlength="130"/></td>
    </tr> 
    <tr>
      <td align="right">会员首页问候语</td>
      <td><input type="text" name="WEB_WELCOME" value="<?php echo (C("web_welcome")); ?>" size="150" maxlength="130"/></td>
    </tr>     

    <tr>
      <td colspan="2" align="center"><b>会员提现手续费设置</b></td>
    </tr> 
    <tr>
      <td align="right">设置一</td>
      <td>
           &nbsp;<input type="text" name="section1_min" value="<?php echo (C("section1_min")); ?>" size="11" maxlength="11"/>
            - <input type="text" name="section1_max" value="<?php echo (C("section1_max")); ?>" size="11" maxlength="11"/> 
            手续费<input type="text" name="section1_money" value="<?php echo (C("section1_money")); ?>" size="11" maxlength="11"/>元
      </td>
    </tr>  
    <tr>
      <td align="right">设置二</td>
      <td>
           &nbsp;<input type="text" name="section2_min" value="<?php echo (C("section2_min")); ?>" size="11" maxlength="11"/>
            - <input type="text" name="section2_max" value="<?php echo (C("section2_max")); ?>" size="11" maxlength="11"/> 
            手续费<input type="text" name="section2_money" value="<?php echo (C("section2_money")); ?>" size="11" maxlength="11"/>元
      </td>
    </tr>  
    <tr>
      <td align="right">设置三</td>
      <td>
           &nbsp;<input type="text" name="section3_min" value="<?php echo (C("section3_min")); ?>" size="11" maxlength="11"/>
            - <input type="text" name="section3_max" value="<?php echo (C("section3_max")); ?>" size="11" maxlength="11"/> 
            手续费<input type="text" name="section3_money" value="<?php echo (C("section3_money")); ?>" size="11" maxlength="11"/>元
      </td>
    </tr> 
    <tr>
      <td align="right">特殊提现手续费设置</td>
      <td>
            &nbsp;<input type="text" name="fast_management" value="<?php echo (C("fast_management")); ?>" size="11" maxlength="11"/>请输入小数 如：0.005（千分之五）
            &nbsp;&nbsp;&nbsp;补充手续费<input type="text" name="replenish_management" value="<?php echo (C("replenish_management")); ?>" size="5" maxlength="11"/>元
            (特殊提现提现手续费 = 提现手续费率*提现金额(低于1000元按1000元算) + 补充手续费)            
      </td>
    </tr>  
    <tr>
      <td align="right">特殊提现最低金额</td>
      <td>
        &nbsp;&nbsp;<input type="text" name="min_special_withdrawal" value="<?php echo (C("min_special_withdrawal")); ?>" size="20" maxlength="11">元
      </td>
    </tr>  
    <tr>
      <td colspan="2" align="center"><b>积分奖励设置</b></td>
    </tr>     
    <tr>
      <td align="right">邀请奖励设置</td>
      <td>
            &nbsp;<input type="text" name="earning_score" value="<?php echo (C("earning_score")); ?>" size="11" maxlength="11"/> 积分
            &nbsp;&nbsp;&nbsp;年化收益比率:<input type="text" name="earning_money_rate" value="<?php echo (C("earning_money_rate")); ?>" size="5" maxlength="11"/> (小数)如: 1% = 0.01<br>      
            
      </td>
    </tr>  
    <tr>
      <td align="right">投资积分奖励一</td>
      <td>
           &nbsp;<input type="text" name="invest_section1_min" value="<?php echo (C("invest_section1_min")); ?>" size="11" maxlength="11"/>元
            - <input type="text" name="invest_section1_max" value="<?php echo (C("invest_section1_max")); ?>" size="11" maxlength="11"/> 元
            返<input type="text" name="invest_section1_score" value="<?php echo (C("invest_section1_score")); ?>" size="11" maxlength="11"/>分
      </td>
    </tr>  
    <tr>
      <td align="right">投资积分奖励二</td>
      <td>
           &nbsp;<input type="text" name="invest_section2_min" value="<?php echo (C("invest_section2_min")); ?>" size="11" maxlength="11"/>元
            - <input type="text" name="invest_section2_max" value="<?php echo (C("invest_section2_max")); ?>" size="11" maxlength="11"/> 元
            返<input type="text" name="invest_section2_score" value="<?php echo (C("invest_section2_score")); ?>" size="11" maxlength="11"/>分
      </td>
    </tr>  
    <tr>
      <td align="right">投资积分奖励三</td>
      <td>
           &nbsp;<input type="text" name="invest_section3" value="<?php echo (C("invest_section3")); ?>" size="11" maxlength="11"/>元以上
            返<input type="text" name="invest_section3_score" value="<?php echo (C("invest_section3_score")); ?>" size="11" maxlength="11"/>分
      </td>
    </tr> 
    <tr>
      <td colspan="2" align="center"><b>奖励金额设置</b></td>
    </tr>     
    <tr>
      <td align="right">奖励金额使用比例设置</td>
      <td>
            &nbsp;<input type="text" name="bonus_rate" value="<?php echo (C("bonus_rate")); ?>" size="11" maxlength="11"/> (小数)如: 1% = 0.01         
            
      </td>
    </tr>                                    
    <tr>
      <td colspan="2" align="center"><b>水印配置选项</b></td>
    </tr> 
    <tr>
      <td align="right">水印图路径</td>
      <td>      
      <input type="file"  name="file">
      <?php if(C('WATER_IMAGE') != ''): ?><a href="<?php echo (C("water_image")); ?>" title="点击查看大图" target="_blank">
            <img src="<?php echo (C("water_image")); ?>" width="32" height="32" border="0"> 
         </a><?php endif; ?>
      </td>
    </tr>  
    <tr>
      <td align="right">水印位置</td>
      <td><input type="text" name="WATER_POS" value="<?php echo (C("water_pos")); ?>" size="150" maxlength="30"/></td>
    </tr>  
    <tr>
      <td align="right">水印透明度</td>
      <td><input type="text" name="WATER_ALPHA" value="<?php echo (C("water_alpha")); ?>" size="150" maxlength="30"/></td>
    </tr>  
    <tr>
      <td align="right">JPEG图片压缩比</td>
      <td><input type="text" name="WATER_COMPRESSION" value="<?php echo (C("water_compression")); ?>" size="150" maxlength="30"/></td>
    </tr>    
    <tr>
      <td align="right">水印文字</td>
      <td><input type="text" name="WATER_TEXT" value="<?php echo (C("water_text")); ?>" size="150" maxlength="30"/></td>
    </tr>    
    <tr>
      <td align="right">水印文字旋转角色</td>
      <td><input type="text" name="WATER_ANGLE" value="<?php echo (C("water_angle")); ?>" size="150" maxlength="30"/></td>
    </tr>    
    <tr>
      <td align="right">水印文字大小</td>
      <td><input type="text" name="WATER_FONTSIZE" value="<?php echo (C("water_fontsize")); ?>" size="150" maxlength="30"/></td>
    </tr>    
    <tr>
      <td align="right">水印文字颜色</td>
      <td><input type="text" name="WATER_FONTCOLOR" value="<?php echo (C("water_fontcolor")); ?>" size="150" maxlength="30"/></td>
    </tr>    
<!--     <tr>
      <td align="right">水印文字字体文件(写入中文字时需使用支持中文的字体文件)</td>
      <td><input type="text" name="WATER_FONTFILE" value="<?php echo (C("water_fontfile")); ?>" size="150" maxlength="30"/></td>
    </tr>  -->   
    <tr>
      <td align="right">水印文字字符编码</td>
      <td><input type="text" name="WATER_CHARSET" value="<?php echo (C("water_charset")); ?>" size="150" maxlength="30"/></td>
    </tr>                                           
    <tr>
      <td align="center" colspan="2"><input type="submit" value="保存设置" /></td>
    </tr>
  </table>
</form>
</BODY></HTML>