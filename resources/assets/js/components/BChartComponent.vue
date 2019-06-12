<template>
    <!-- https://www.highcharts.com.cn/docs/basic-drilldown -->
    <div>
        <input style="display: none;" id="input" :loadData="loadData">
        <v-layout>
            <v-flex xs12 sm12>
                <v-card>
                    <v-flex offset-sm10 pa-0>
                        <v-card-actions style="position: static;">
                            <v-flex xs12 sm6 class="py-2">
                                <v-btn-toggle v-model="chooseTimeDim" mandatory>
                                    <v-btn flat value="day" >
                                        2周
                                    </v-btn>
                                    <v-btn flat value="hour">
                                        24小时
                                    </v-btn>
                                </v-btn-toggle>
                            </v-flex>
                        </v-card-actions>

                    </v-flex>
                    <v-flex>
                        <v-container id="containerWidth">
                            <v-layout>
                                <v-flex xs12 id="lineChart">
                                    <div :id="id" :option="option"></div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-card>
                <v-card class="my-2">
                    <v-flex>
                        <v-container id="chartContainer">
                            <v-layout>
                                <v-flex :class="xsBar" id="barChart" style="display:none">
                                    <barChartComponent :optionState="optionState"></barChartComponent>
                                </v-flex>
                                <v-flex :class="xsTopCell" id="topCellTable" style="display:none">
                                    <topCellTableComponent :optionState="optionState"></topCellTableComponent>
                                </v-flex>
                                <v-flex :class="xsPie" id="pieChart" style="display:none">
                                    <pieChartComponent :optionState="optionState"></pieChartComponent>
                                </v-flex>
                                <v-flex :class="xsBubble" id="bubbleChart" style="display:none">
                                    <bubbleChartComponent :optionState="optionState"></bubbleChartComponent>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>
    import {
        common
    } from '../common.js';

    import barChartComponent from './BarChartComponent.vue';
    import topCellTableComponent from './TopCellTableComponent.vue';
    import pieChartComponent from './PieChartComponent.vue';
    import bubbleChartComponent from './BubbleChartComponent.vue';
    export default {
        mixins: [
            common
        ],
        components: {
            barChartComponent,
            pieChartComponent,
            bubbleChartComponent,
            topCellTableComponent
        },
        data() {
            let vm = this;
            return {
                xsBar:"xs12",
                xsTopCell:"xs6",
                xsPie:"xs4",
                xsBubble:"xs4",
                containerWidth: 0,
                chooseTimeDim: "day",
                chart: {},
                bSideBar: "indexoverview",
                operator: "mobile",
                city: "",
                type: "lte",
                card: "无线接通率",
                id: 'container',
                clickProvince: "national",
                province: "national",
                assessment: {
                    status: false
                },
                optionData: {},
                optionState:{},
                option: {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'line',
                    },
                    title: {
                        text: 'ENIQ-全国'
                    },
                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: '(%)'
                        },
                        // max: 100
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: false
                            },
                            cursor: 'pointer',
                            events: {
                                click: function (e) {
                                    vm.optionState = {
                                        card:vm.card,
                                        city:vm.city,
                                        operator:vm.operator,
                                        province:vm.province,
                                        timeDim:vm.chooseTimeDim,
                                        type:vm.type,
                                        clickTime:e.point.name,
                                        clickLineName:this.name
                                    };
                                    // console.log(vm.optionState);

                                    switchChartDisplay(vm.optionState,vm);
                                    $("#chartContainer")[0].scrollIntoView();
                                },
                            }
                        }
                    },
                    series: [
                    ],
                }
            }
        },
        mounted() {
            // new Highcharts.chart(this.id, this.option);
            Highcharts.addEvent(document.getElementById('container'), 'click', function (e) {
                if (e.hasOwnProperty('point') && e.target.point != null) {
                    // alert(event.target.point.name)
                }
            });
        },
        methods:{
        },
        created() {
            this.bus.$on('clickBSideBar', type => {
                this.bSideBar = type.bSideBar;
            });
            this.bus.$on('chooseOperator', type => {
                this.operator = type.operator;
            });
            this.bus.$on('chooseCity', type => {
                if (!type.isUpdateBChartVue) return;
                type.city.length === 0 ? this.city = "" : this.city = type.city;
                this.province = type.province;
            });
            this.bus.$on('chooseProvince', type => {
                if (!type.isUpdateBChartVue) return;
                this.province = type.province;
            });
            this.bus.$on('type', type => {
                this.type = type.type;
            });
            this.bus.$on('card', type => {
                this.card = type.card;
            });
            this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this
                .chooseTimeDim);
        },
        watch: {
            bSideBar() {
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            operator() {
                switchChartDisplay(null);
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            city() {
                switchChartDisplay(null);
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            province() {
                switchChartDisplay(null);
                // this.chooseTimeDim = (this.province === 'national')
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            type() {
                switchChartDisplay(null);
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            card() {
                switchChartDisplay(null);
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            chooseTimeDim() {
                this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province,
                    this.chooseTimeDim);
            },
            optionData(val) {
                let self = this;
                this.chart = new Highcharts.chart(this.id, this.option);

                this.chart.title.update({
                    'text': val.title
                });
                this.chart.subtitle.update({
                    'text': val.subtitle
                });

                let regionSeries = {};
                if (this.city !== '') {
                    regionSeries = val['city-series'];
                } else {
                    regionSeries = val['series'];
                }
                // console.log(regionSeries);
                _.forEach(regionSeries, (o) => {
                    if (self.province === 'national') {
                        o.visible = true;
                    } else if (self.province === o.spellName) {
                        o.visible = true;
                        //均值
                        if ((typeof val.assessmentPlots === 'string' && val.assessmentPlots === '禁用') || (
                                typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '禁用'
                                )) {
                            // let _thisO = o;
                            // o.data = _.filter(o.data, function (o) {
                            //     return o.y < _thisO.plots
                            // });
                            // o.data = _.forEach(o.data, function(o){ if(o.y>_thisO.plots){
                            //   o.y = null
                            // } });
                            //考核
                        } else if (typeof val.assessmentPlots === 'object' && val.assessmentPlots.status ===
                            '启用') {
                            o.data = _.filter(o.data, function (o) {
                                return o.y < val.assessmentPlots.assessment
                            });
                        }
                    } else if (self.city === o.spellName) {
                        o.visible = true;
                        if (typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用') {
                            o.data = _.filter(o.data, function (o) {
                                return o.y < val.assessmentPlots.assessment
                            });
                        }
                    } else {
                        o.visible = false;
                    }
                    self.chart.addSeries(o);
                });

                if (this.province !== 'national' && this.city === '') {
                    _.forEach(val['city-series'], (o) => {
                        o.visible = true;
                        if (typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用') {
                            o.data = _.filter(o.data, function (o) {
                                return o.y < val.assessmentPlots.assessment
                            });
                        }
                        self.chart.addSeries(o);
                    });
                }

                this.chart.yAxis[0].removePlotLine('plot-line');
                _.filter(regionSeries, (o) => {
                    if (self.province !== 'national' && self.province === o.spellName) {
                        // self.chart.addSeries(o);
                        o.visible = true;
                        self.chart.yAxis[0].addPlotLine({
                            value: o.plots,
                            width: 2,
                            color: 'green',
                            id: 'plot-line',
                            dashStyle: 'Dash',
                            label: {
                                text: '均值:' + /*o.name+': '+*/ o.plots + '%',
                                align: 'left',
                                x: 0,
                                style: {
                                    color: 'green'
                                }
                            },
                            zIndex: 999,
                            events: {
                                click: function () {
                                    //当标示线被单击时，触发的事件
                                },
                                mouseover: function () {
                                    //当标示线被鼠标悬停时，触发的事件
                                },

                                mouseout: function () {
                                    //当标示线被鼠标移出时，触发的事件
                                },

                                mousemove: function () {
                                    //当标示线被鼠标移动(时，触发的事件
                                }
                            }
                        });
                    }
                    if (typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用') {
                        self.chart.yAxis[0].addPlotLine({
                            value: val.assessmentPlots.assessment,
                            width: 2,
                            color: 'red',
                            id: 'plot-line',
                            dashStyle: 'LongDashDotDot',
                            label: {
                                text: '考核:' + /*o.name+': '+*/ val.assessmentPlots.assessment + '%',
                                align: 'right',
                                x: 0,
                                style: {
                                    color: 'red'
                                }
                            },
                            zIndex: 999,
                            events: {
                                click: function () {
                                    //当标示线被单击时，触发的事件
                                },
                                mouseover: function () {
                                    //当标示线被鼠标悬停时，触发的事件
                                },

                                mouseout: function () {
                                    //当标示线被鼠标移出时，触发的事件
                                },

                                mousemove: function () {
                                    //当标示线被鼠标移动(时，触发的事件
                                }
                            }
                        });
                    }
                });
            },
        },
        computed: {
            loadData: function () {
                switch (this.$store.getters.bChartStatus) {
                    case 1:
                        break;
                    case 2:
                        this.optionData = this.$store.getters.bChart;
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
            }
        }
    }

    function switchChartDisplay(optionState,vm)
    {
        if (!optionState) {
            document.getElementById("barChart").style.display= "none";
            document.getElementById("topCellTable").style.display= "none";
            document.getElementById("bubbleChart").style.display= "none";
            document.getElementById("pieChart").style.display= "none";

            return;
        }

        var provinces = [];
        provinces["jiangsu"] = "江苏";
        provinces["guangdong"] = "广东";
        provinces["hubei"] = "湖北";
        var province = optionState.province;
        var clickLineName = optionState.clickLineName;
        var city = optionState.city;

        if(province == "national") {
        //1) 全国页面
            // 1.1 点击全国趋势线，显示所有地市的指标排名bar图（在点击时间点）
            // 1.2 点击省级趋势线，显示该省所有地市指标排名bar图（在点击事件点）
            document.getElementById("barChart").style.display= "inline-block";
            vm.xsBar = "xs12";
            document.getElementById("topCellTable").style.display= "none";
            document.getElementById("bubbleChart").style.display= "none";
            document.getElementById("pieChart").style.display= "none";
        } else {
            if (city == "") {
                // 2）省级页面
                if (provinces[province] == clickLineName) {
                    // 2.1 点击省级趋势线，显示指标排名bar图，恶化小区分布饼图和失败次数气泡图
                    document.getElementById("barChart").style.display= "inline-block";
                    document.getElementById("pieChart").style.display= "inline-block";
                    document.getElementById("topCellTable").style.display= "none";
                    if (optionState.timeDim == "day") {
                        document.getElementById("bubbleChart").style.display= "inline-block";
                        vm.xsBar = "xs4";
                        vm.xsPie = "xs4";
                    } else {
                        document.getElementById("bubbleChart").style.display= "none";
                        vm.xsBar = "xs6";
                        vm.xsPie = "xs6";
                    }
                } else {
                    document.getElementById("barChart").style.display= "none";
                    document.getElementById("topCellTable").style.display= "none";
                    document.getElementById("bubbleChart").style.display= "none";
                    document.getElementById("pieChart").style.display= "none";
                }
            } else {
                // 3) 地市级页面
                // 3.1 点击地市指标趋势线
                // 左侧显示该时间点该地市的TOP30小区列表(小区名，失败次数)。
                // 右侧显示TOP30和其余小区失败次数在全网失败次数比例的饼图分布。
                document.getElementById("barChart").style.display= "none";
                document.getElementById("topCellTable").style.display= "inline-block";
                document.getElementById("pieChart").style.display= "inline-block";
                document.getElementById("bubbleChart").style.display= "none";

                vm.xsPie = "xs6";
            }
        }
    }
</script>