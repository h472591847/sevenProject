$(function(){
    assetCount();
    
    var chart = $('#CPIResultChart');
    if(chart.length > 0){
        var date = ['2013.3', '2013.4', '2013.5', '2013.6', '2013.7', '2013.8', '2013.9'];
        var items = [2.1, 2.4, 2.1, 2.7, 2.7, 2.6, 3.1];
        CPIChartResult(chart, date, items);
    }
    
});
function createAssetHTML(year,money,per){
    var html = '<h2>计算结果</h2>';
    html += '<div class="cpi-do">'+year+'年后你的财富将缩水为<span class="light-org">'+money+'</span>元，缩水'+per+'%<p>根据当前CPI指数推算，数据仅供参考</p></div>';
    return html;
};
function assetCount(){
    var asset = $('#myAsset');
    var year = $('#CountLength');
    var result = $('#CPIResult');
    var assetTip = $('#assetTip');
    var rest = /^\+?[1-9][0-9]*$/;　　//正整数 
    $('#submitButton').click(function(){
        var assetVal = parseInt(asset.val());
        var yearVal = parseInt(year.val());
        if(isNaN(assetVal*1) || assetVal*1 < 200) { // || rest.test(assetVal)
            assetTip.html('<i class="icons reg-error"></i>该数值须为不小于200！').show();
            assetTip.addClass('error');
            asset.addClass('inputErr');
            asset.select();
            asset.focus();
        } else {
            asset.removeClass('inputErr');
            assetTip.hide();
            var d = Math.pow(1 + 0.026, yearVal);
            var per = Util.numFormat(((assetVal - assetVal / d) / assetVal) * 100, -1);
            var money = Util.numFormat(assetVal / d, -1);
            
            result.html(createAssetHTML(yearVal,money,per));
        }
    });
    $('#resetButton').click(function(){
        asset.val('');
        year.find("option").eq(0).attr("selected", "selected")
    });
    
};
function CPIChartResult(goal, date, items){
    goal.highcharts({
        chart: {
            type: 'line',//line
            marginTop:50
        },
        title: {
            text: '单位(%)',
            align: 'left',  
            x: 15,
            style:{color:'#475058'}
        },
        subtitle: {
            enabled: true
        },
        xAxis: {
            categories: date,
            labels: {
                align: 'center',
                //rotation: -20,
                formatter: function() {
                    return this.value// +'%'
                }
            }
        },
        yAxis: {
            //max: 8,
            min: 0,
            //tickInterval: 2, // X轴的步进值，决定隔多少个显示一个
            title: {
                enabled: false,
            },
            labels: {
                align: 'right',  
                formatter: function() {
                    return this.value// +'%'
                }
            }
        },
        plotOptions: {
            line: {
                lineWidth: 1,
                dataLabels: {enabled: false},
                marker: {enabled: true},
                states:{
                    hover:{
                        lineWidth:1,
                        enabled:false//鼠标放上去线的状态控制
                    }
                }
            }
        },
        tooltip: {enabled: false},
        legend: { enabled: false},
        exporting: { enabled: false},
        credits: {enabled: false},
        series: [{
            name: 'CPI',
            color: '#18b160',
            data: items
        }]
    });
};    