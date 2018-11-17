QueChart = {
    loadDataChart: function () {
        var self = this;
        var chart1 = $("#chart1").highcharts();
        var chart2 = $("#chart2").highcharts();
        var chart3 = $("#chart3").highcharts();
        var chart4 = $("#chart4").highcharts();
        $.ajax({
            url: '/site/data-chart',
            type: "GET",
            dataType: "json",
            success: function (response) {
                //chart1
                self.removeSeriesChart(chart1);
                self.addSeriesChart(chart1, response.dataChart1);
                //chart2
                chart2.xAxis[0].setCategories(response.dataChart2.categories);
                self.removeSeriesChart(chart2);
                self.addSeriesChart(chart2, response.dataChart2.series);
                //chart3
                self.removeSeriesChart(chart3);
                self.addSeriesChart(chart3, response.dataChart3.series);
                self.addDrilldownChart(chart3, response.dataChart3.subseries);
                //chart4
                self.removeSeriesChart(chart4);
                self.addSeriesChart(chart4, response.dataChart4.series);
                self.addDrilldownChart(chart4, response.dataChart4.subseries);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
        });
    },
    loadDataCountService: function () {
        $.ajax({
            url: '/site/data-count-service',
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('.panel-body .content-count').html(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
        });
    },
    removeSeriesChart: function (chart) {
        var seriesLength = chart.series.length;
        for (var i = seriesLength - 1; i > -1; i--) {
            chart.series[i].remove();
        }
    },
    addSeriesChart: function (chart, series) {
        $.each(series, function (index, value) {
            chart.addSeries(value);
        });
    },
    addDrilldownChart: function (chart, series) {
        $.each(series, function (index, value) {
            chart.options.drilldown.series[index] = value;
        });
    },
    loadData: function () {
        var self = this;
        self.loadDataCountService();
        self.loadDataChart();
    }
};

//Socket Events
$(function () {
    socket.on('register', (res) => {
        toastr.warning('#' + res.modelQue.que_num + '<p><i class="fa fa-user"></i> '+res.modelQue.pt_name+'</p>', 'คิวใหม่!', {
            "timeOut": 5000,
            "positionClass": "toast-top-right",
            "progressBar": true,
            "closeButton": true,
        });
        $.pjax.reload({container:"#pjax-dashboard"});  //Reload
        //QueChart.loadData();
    });
});