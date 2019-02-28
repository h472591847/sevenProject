var options = {
    chart: {
        defaultSeriesType: 'spline',
         renderTo:'container'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        showEmpty: false
    },

    yAxis: {
        showEmpty: false
    },
		plotOptions:{
			series:{
				animation:false
			}
		},
    series: [{
        allowPointSelect: true,
        data: [ // use names for display in pie data labels
            ['1月',    500],
            ['2月',   1000],
            ['3月',     5000],
            ['4月',     100000],
            ['5月',      5000],
            ['6月',      2000],
            
        ],
        dataLabels:{
        	enabled:true
        },
        marker:{
        	enabled:true
        },
        showInLegend: true
    }]
};

$(function () {
    var chart = new Highcharts.Chart(options); 

    // Set Name
    var name = false;
    $('.change[index=name]').click(function() {
        options.series[0].name = name ? null : 'First';
        chart = new Highcharts.Chart(options);
        name = !name;
    });

    //Set Enable DataLabels
    var enableDataLabels = false;
    $('.change[index=data-labels]').click(function() {
        options.series[0].dataLabels.enabled = enableDataLabels;
        chart = new Highcharts.Chart(options);
        enableDataLabels = !enableDataLabels;
    });
    
    //Set Enable Markers 
    var enableMarkers = false;
    $('.change[index=markers]').click(function() {
        options.series[0].marker.enabled = enableMarkers;
        chart = new Highcharts.Chart(options);
        enableMarkers = !enableMarkers;
    });

    //Set Color
    var color = false;
    $('.change[index=color]').click(function() {
        options.series[0].color =  color ? null : Highcharts.getOptions().colors[1];
        chart = new Highcharts.Chart(options);
        color = !color;
    });

    // Set type
    $.each(['line', 'column', 'spline', 'area', 'areaspline', 'scatter', 'pie'], function (i, type) {
        $('.change[index=' + type+']').click(function () {
            options.chart.type =  type;
            chart = new Highcharts.Chart(options);
        });
    });
});				