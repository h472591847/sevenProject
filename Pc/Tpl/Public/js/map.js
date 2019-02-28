var hoverMap = $('.hoverMap');

var cityImg  = $('.cityImg');

var shop_list_panel = $('.shop_list_panel');
var map_city_list  = $('.map_city_list');

hoverMap.click(function(){

	now_id =  $(this).attr('id');
	
	cityImg.hide();
	$("#s_"+now_id).show();
	
	//要配置php文件才能正常演示，前端只能到这了
	$.ajax({
	
		url: "http://www.octmami.com/ajax_shop.php",
		data: 'city=' + now_id +'&time='+ Math.random(),
		type: 'get',
		cache: false,
		success : function(data){
			map_city_list.empty().html(data);
			map_city_list.find('.shop_list_panel').jScrollPane();
		}	
	});
	
	shop_list_panel.jScrollPane();	
	
});