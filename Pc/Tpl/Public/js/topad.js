// JScript 文件
function TopAd()
{
    var strTopAd="";
	
	//定义小图片内容
    var topSmallBanner="<div><a href=\"http://www.p2p222.com/index.php/Lottery/index.html\" target=_blank><img src=\"/Pc/Tpl/Public/images/top_ad_small.jpg\" /></a></div>";
	
	//判断在那些页面上显示大图变小图效果，非这些地址只显示小图（或FLASH）
    if (location == "#" || location == "#" || location == "#" || true)
    {
		//定义大图内容
        strTopAd="<div id=adimage style=\"width:1000px\margin: 0 auto\">"+
                    "<div id=adBig><a href=\"http://www.p2p222.com/index.php/Lottery/index.html/\" " + 
                    "target=_blank><img title=YUANXIN "+
                    "src=\"/Pc/Tpl/Public/images/top_ad_big.jpg\" " +
                    "border=0></A></div>"+
                    "<div id=adSmall style=\"display: none\">";
        //strTopAd+=  topFlash;     
		strTopAd+=  topSmallBanner;  
        strTopAd+=  "</div></div>";
    }
    else
    {
        //strTopAd+=topFlash;
		strTopAd+=  topSmallBanner;  
    }
    strTopAd+="<div style=\"height:7px; clear:both;overflow:hidden\"></div>";
    return strTopAd;
}
function showImage()
{
    $("#adBig").slideUp(1000,function(){$("#adSmall").slideDown(1000);});
}

