<template>
    <div>
        <input style="display: none;" id="input" :loadBubbleData="loadBubbleData">
        <div :id="id" :option="option"></div>
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
        props: {
            optionState: {}
        },
        data() {
            return {
                id: 'containerBubble',
                optionData: {},
                option: {
                    chart: {
                        type: 'bubble',
                        zoomType: 'xy'
                    },
                    title: {
                        text: '失败次数分布',
                        style: {
                            fontSize: '16px'
                        }
                    },
                    subtitle: {
                        text: '2019-05-30'
                    },
                    yAxis: {
                        title: {
                            text: '过去3天内的恶化频次'
                        }
                    },
                    tooltip: {
                        pointFormat: '过去3天内的恶化频次:<b>{point.y}次</b> <br> 当天内总失败次数: <b>{point.z}次</b>'
                    },
                    series: []
                }
            }
        },
        mounted() {
            new Highcharts.chart(this.id, this.option);
        },
        watch: {
            optionState() {
                this.processLoadBubbleChart(this.optionState);
            },
            optionData(val) {
                // console.log(val)
                let self = this;
                this.chart = new Highcharts.chart(this.id, this.option);

                // this.chart.setSize(parseInt(this.containerWidth), 400);
                this.chart.subtitle.update({
                    'text': val.subtitle
                });
                this.chart.title.update({
                    'text': val.clickLineName+"-失败次数分布"
                });
                _.forEach(val.series, (o) => {
                    self.chart.addSeries(o);
                });
            },
        },
        computed: {
            loadBubbleData: function () {
                switch (this.$store.getters.bubbleChartStatus) {
                    case 1:
                        break;
                    case 2:
                        this.optionData = this.$store.getters.bubbleChart;
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