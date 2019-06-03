<template>
    <div>
        <input style="display: none;" id="input" :loadPieData="loadPieData">
        <span :id="id" :option="option"></span>
    </div>
</template>
<script>
    import {
        common
    } from '../common.js';
    export default {
        mixins: [
            common
        ],
        props:{
            optionState: {}
        },
        data() {
            return {
                id: 'containerPie',
                optionData: {},
                option: {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: '恶化小区分布'
                    },
                    subtitle: {
                        text: '2019-05-30'
                    },
                    tooltip: {
                        pointFormat: '{series.name}:<b>{point.y}次</b> <br>占比:<b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        name: '失败次数',
                        colorByPoint: true,
                        data: []
                    }]
                }
            }
        },
        mounted() {
            new Highcharts.chart(this.id, this.option);
        },
        watch:{
            optionState(){
                this.processLoadPieChart(this.optionState);
            },
            optionData(val) {
                // console.log(val)
                let self = this;
                this.chart = new Highcharts.chart(this.id, this.option);

                this.chart.setSize(parseInt(this.containerWidth), 400);
                this.chart.subtitle.update({
                    'text': val.subtitle
                });
                self.chart.addSeries({
                        name: '失败次数',
                        colorByPoint: true,
                        data: val.series
                    });
            },
        },
        computed: {
            loadPieData: function () {
                switch (this.$store.getters.pieChartStatus) {
                    case 1:
                        break;
                    case 2:
                        this.optionData = this.$store.getters.pieChart;
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
            }
        }
    }
</script>