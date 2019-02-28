<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link href="__PUBLIC__/css/style.css?<?php echo rand(0,1000);?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $(".tz_jl li:odd").css("background","#f2f2f2");
   $(".tz_jl").css("font-size","12px");
});

</script>
<style>
body{ background:#ffffff;}
.tz_one,.tz_two,.tz_three{ font-size:8pt;}
</style>
</head>
<body>
         <?php if($lid >= 21): ?><div class="tz_record">
            <h4 class="tz_h">投资记录</h4>
            <div class="tz_jl">
               <h4>
                  <span class="tz_one">投资时间</span>
                  <span class="tz_two">投资人</span>
                  <span class="tz_three">投资金额</span>
               </h4>
               <ul>
               <?php if(empty($invested_list)): ?><li><center>暂无数据!</center></li>
               <?php else: ?>
                  <?php if(is_array($invested_list)): foreach($invested_list as $key=>$v): ?><li>
                     <span class="tz_one"><?php echo (date('Y-m-d H:i:s',$v["create_time"])); ?></span>
                     <span class="tz_two"><?php echo ($v["user_name"]); ?></span>
                     <span class="tz_three"><?php echo (format_price($v["amount"])); ?></span>
                  </li><?php endforeach; endif; endif; ?>
               </ul>               
               <p class="page"><?php echo ($page); ?></p>
            </div>
         </div><?php endif; ?>
         
         
</body>
</html>