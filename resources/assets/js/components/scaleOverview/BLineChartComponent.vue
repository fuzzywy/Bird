<template>
    <!-- <div class="hello">
        <div class="charts"> -->
    <div v-show="show == 2">
        <div v-show="bLineChart == 1">Loading highchart</div>
        <div :id="id" :option="option" v-show="bLineChart == 2" class="col-12" style="min-width: 400px; padding: 0px;"></div>
        <div v-show="bLineChart == 3">Highchart loaded unsuccessfully!</div>
    </div>
    
        <!-- </div>
    </div> -->
</template>
<script>
    export default {
        data() {
            return {
                id: 'container',
                city: '全省',
                overview: 'indexoverview',
                types: "LTE",
                charts: {},
                cardId: 0,
                option: {
                    chart: {
                        type: 'line',
                        // width:1234,  
                        // height:500,  
                        marginRight:10//留一点地儿给y轴上刻度的显示  
                    },
                    title: {
                        text: '月平均气温'//表头文字
                    },
                    subtitle: {
                        text: '数据来源: WorldClimate.com'//表头下文字
                    },
                    xAxis: {//x轴显示的内容
                        categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
　　　　　　　　　　　　　　   plotbands:[{//可以显示一个方块，如果需要的话可以更改透明度和颜色
　　　　　　　　　　　　　　　　　from:4.5,
　　　　　　　　　　　　　　　　　to:6.5,
　　　　　　　　　　　　　　　　　color:'rgba(68,170,213,0)'//透明度和颜色
　　　　　　　　　　　　　　　  }]
                         },
 
                    yAxis: {//y轴显示的内容
                        categories:[],
                        title: {
                            text: '气温 (°C)'
                        },
                        alternateGridColor: '#FDFFD5'
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true // 开启数据标签
                            },
                            enableMouseTracking: false // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                        },
                        series: {
                            events: {
                                legendItemClick: function(e) {
                                    /*var target = e.target; 
                                        console.log(target === this);
                                        */
                                    var index = this.index;
                                    var series = this.chart.series;
                                    if(!series[index].visible) {
                                        for(var i=0;i<series.length;i++) {
                                            var s = series[i];
                                            i===index ? s.show() : s.hide();
                                        }
                                    }
                                    return false;
                                }
                            }
                        }
                    },
                    series: [{//两条数据
                        name: '东京',
                        data: [99.93,99.94,99.74,99.99,99.98,99.93,99.95,99.92,99.96,99.95,99.95,99.93],
                        visible:true
                    }, {
                        name: '伦敦',
                        data: [0.35,0.37,0.39,0.33,0.31,0.25,0.40,0.17,0.32,0.28,0.29,0.27],
                        visible:true
                    },{
                        name: '伦敦1',
                        data: [98.50,98.49,98.68,98.79,98.69,98.54,97.43,98.72,98.58,98.68,98.70,98.69],
                        visible:true
                    }]
                }
            }
        },
        /*mounted() {
            // window.Highcharts.chart(this.id, this.option)
            new Highcharts.chart(this.id, this.option)
        },*/
        computed: {
            bLineChart() {
                this.option = this.$store.getters.getbLineChart
                return this.$store.getters.getbLineChartStatus;
            },
            show() {
                if( this.overview == 'indexoverview' ) {
                    return 2;
                }
            }
        },
        watch: {
            option: function(val) {
                let charts = this.charts

                while (charts.series.length > 0) {
                    charts.series[0].remove(true);
                }
                
                charts.setTitle(val.title, val.subtitle)
                charts.yAxis[0].setTitle(val.ytitle)
                charts.xAxis[0].setTitle(val.xtitle)                    

                charts.xAxis[0].categories = val.xcategories;
                charts.yAxis[0].categories = val.ycategories;

                for ( let i = 0;  i < val.ydata.length; i++) {
                    if ( this.cardId == i ) {
                        val.ydata[i]['visible'] = true
                    } else {
                        val.ydata[i]['visible'] = false
                    }
                    let series = charts.addSeries(val.ydata[i])
                }
                /*for (let i = val.ydata.length - 1; i >= 0; i--) {
                    if ( this.cardId == i ) {
                        val.ydata[i]['visible'] = true
                    } else {
                        val.ydata[i]['visible'] = false
                    }
                    let series = charts.addSeries(val.ydata[i])  
                }*/
            }   
        },
        mounted() {
            var charts = new Highcharts.chart(this.id, this.option)
            charts.reflow();
            this.charts = charts
            /*this.$store.dispatch( 'loadBLineChartStatus', {
                type: this.types,
                city: this.city,
                overview: this.overview
            })*/
            /*this.bus.$on('leftClick', overview => {
                this.overview = overview
            })
            this.bus.$on('cityClick', city => {
                this.city = city
            })*/
            this.bus.$on('getTabsId', id=> {
                this.cardId = id
                if (this.overview == 'indexoverview') {
                    this.$store.dispatch( 'loadBLineChartStatus', {
                        type: this.types,
                        city: this.city,
                        overview: this.overview
                    })
                }
            })
            this.bus.$on('getTabsType', types=> {
                this.types = types
            })
            this.bus.$on('leftClickData', overview => {
                // this.overview = overview
                // var obj = this.option
                // var id = this.id
                this.overview = overview

                // this.charts.splice(0, this.charts.length)
                // this.charts = charts
                if (this.overview == 'indexoverview') {
                    this.$store.dispatch( 'loadBLineChartStatus', {
                        type: this.types,
                        city: this.city,
                        overview: this.overview
                    })
                }
                
                /*axios.get('getcharts', {
                    params: {
                        data: this.types,
                        city: this.city,
                        overview: this.overview
                    }
                })
                .then(function(response) {
                    //clear data
                    while (charts.series.length > 0) {
                        charts.series[0].remove(true);
                    }
                    charts.setTitle(response.data.title, response.data.subtitle)
                    charts.yAxis[0].setTitle(response.data.ytitle)
                    charts.xAxis[0].setTitle(response.data.xtitle)                    

                    charts.xAxis[0].categories = response.data.xcategories;
                    charts.yAxis[0].categories = response.data.ycategories;

                    for (var i = response.data.ydata.length - 1; i >= 0; i--) {
                        var series = charts.addSeries(response.data.ydata[i])  
                    }
                })
                .catch(function(error) {
                    console.log(error)
                })*/
            })
            this.bus.$on('cityClickData', city => {
                // this.city = city
                // var obj = this.option
                // var id = this.id
                this.city = city

                if (this.overview == 'indexoverview') {
                    this.$store.dispatch( 'loadBLineChartStatus', {
                        type: this.types,
                        city: this.city,
                        overview: this.overview
                    })
                }
                /*axios.get('getcharts', {
                    params: {
                        data: this.types,
                        city: this.city,
                        overview: this.overview
                    }
                })
                .then(function(response) {
                    //clear data
                    while (charts.series.length > 0) {
                        charts.series[0].remove(true);
                    }
                    charts.setTitle(response.data.title, response.data.subtitle)
                    charts.yAxis[0].setTitle(response.data.ytitle)
                    charts.xAxis[0].setTitle(response.data.xtitle)                    

                    charts.xAxis[0].categories = response.data.xcategories;
                    charts.yAxis[0].categories = response.data.ycategories;

                    for (var i = response.data.ydata.length - 1; i >= 0; i--) {
                        var series = charts.addSeries(response.data.ydata[i])  
                    }
                    charts.reflow();
                })
                .catch(function(error) {
                    console.log(error)
                })*/
            })
            this.bus.$on('getTabsData', types => {
                // var obj = this.option
                // var id = this.id
                this.types = types

                if (this.overview == 'indexoverview') {
                    this.$store.dispatch( 'loadBLineChartStatus', {
                        type: this.types,
                        city: this.city,
                        overview: this.overview
                    })
                }
                /*axios.get('getcharts' , {
                    params: {
                        data: this.types,
                        city: this.city,
                        overview: this.overview
                    }
                })
                .then(function(response) {
                    //clear data
                    while (charts.series.length > 0) {
                        charts.series[0].remove(true);
                    }
                    charts.setTitle(response.data.title, response.data.subtitle)
                    charts.yAxis[0].setTitle(response.data.ytitle)
                    charts.xAxis[0].setTitle(response.data.xtitle)                    

                    charts.xAxis[0].categories = response.data.xcategories;
                    charts.yAxis[0].categories = response.data.ycategories;
                    // charts.yAxis[0].tickPositions = response.data.ycategories;
                    // console.log(charts)
                    for (var i = response.data.ydata.length - 1; i >= 0; i--) {
                        var series = charts.addSeries(response.data.ydata[i])  
                    }
                    charts.reflow();
                })
                .catch(function(error) {
                    console.log(error)
                })*/
            })
        }
        /*components: {
            XChart
        }*/
    }
</script>