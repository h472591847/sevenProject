var scrollPic_01 = new ScrollPic();
scrollPic_01.scrollContId   = "gdjyhj"; //内容容器ID
scrollPic_01.arrLeftId      = "gdjyhjl";//左箭头ID
scrollPic_01.arrRightId     = "gdjyhjr"; //右箭头ID
scrollPic_01.frameWidth     = 620;//显示框宽度
scrollPic_01.pageWidth      = 155; //翻页宽度
scrollPic_01.speed          = 20; //移动速度(单位毫秒，越小越快)
scrollPic_01.space          = 20; //每次移动像素(单位px，越大越快)
scrollPic_01.autoPlay       = true; //自动播放
scrollPic_01.autoPlayTime   = 4; //自动播放间隔时间(秒)
scrollPic_01.initialize(); //初始化        
								
$("#gdjyhj").find(".red:odd").css("background","#5d5d5d");    