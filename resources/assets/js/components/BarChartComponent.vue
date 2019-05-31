<template>
    <div>
        <input style="display: none;" id="input" :loadBarData="loadBarData">
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
                id: 'containerBar',
                optionData: {},
                option: {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '所有城市指标排名'
                    },
                    subtitle: {
                        text: '2019-05-30'
                    },
                    xAxis: {
                        categories: [
                            '2019-05-30'
                        ],
                        crosshair: true
                    },
                    yAxis: {
                        // min: 95,
                        // max:100,
                        title: {
                            text: '(%)'
                        }
                    },
                    tooltip: {
                        // head + 每个 point + footer 拼接成完整的 table
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.2f} %</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            borderWidth: 0
                        }
                    },
                    series: []
                }
            }
        },
        mounted() {
            new Highcharts.chart(this.id, this.option);
        },
        methods: {
            // test(){
            //     console.log(this.optionState)
            // }
            
        },
        watch:{
            optionState(){
                this.processLoadBarChart(this.optionState);
            },
            optionData(val) {
                // console.log(val);
                let self = this;
                this.chart = new Highcharts.chart(this.id, this.option);

                this.chart.setSize(parseInt(this.containerWidth), 400);

                this.chart.title.update({
                    'text': val.title
                });
                this.chart.subtitle.update({
                    'text': val.subtitle
                });
                this.chart.xAxis[0].categories = val.xAxis;

                _.forEach(val.series, (o) => {
                    self.chart.addSeries(o);
                });
                // console.log(self.chart.series)

            },
        },
        computed: {
            loadBarData: function () {
                switch (this.$store.getters.barChartStatus) {
                    case 1:
                        break;
                    case 2:
                        this.optionData = this.$store.getters.barChart;
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