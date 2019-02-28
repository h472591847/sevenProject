var interval = 1000; 
function ShowCountDown(year,month,day,divname) 
{ 
var now = new Date(); 
var endDate = new Date(year, month-1, day); 
var leftTime=endDate.getTime()-now.getTime(); 
var leftsecond = parseInt(leftTime/1000); 
//var day1=parseInt(leftsecond/(24*60*60*6)); 
var day1=Math.floor(leftsecond/(60*60*24)); 
var hour=Math.floor((leftsecond-day1*24*60*60)/3600); 
var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60); 
var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60); 
var cc = document.getElementById(divname); 
cc.innerHTML = "距离本期募集结束:"+"<span class='recom_date'>"+day1+"</span>"+"天"+"<span class='recom_date'>"+hour+"</span>"+"小时"+"<span class='recom_date'>"+minute+"</span>"+"分"+"<span class='recom_date'>"+second+"</span>"+"秒"; 
} 
