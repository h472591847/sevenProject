$(document).ready(function(){
				  //为表格的奇偶行设定不同的颜色
                  $("#tbody tr:even").addClass("even");
                  $("#tbody tr:odd").addClass("odd");
                  //鼠标移入该行和鼠标移除该行的事件
                  $(".tra_tab #tbody tr").mouseover(function(){
                       $(this).addClass("access");
                  }).mouseout(function(){
                       $(this).removeClass("access");
                  });

                //鼠标点击事件
                  $(".tra_tab #tbody tr").click(function(){
				     $(this).addClass("click").siblings("tr").removeClass("click");
				  });
				  
});