<template>
  <!-- https://www.highcharts.com.cn/docs/basic-drilldown -->
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <v-layout>
      <v-flex xs12 sm12>
        <v-card hover>
          <v-flex offset-sm10 pa-0 v-if="drilldownProvince">
            <v-card-actions style="position: static;">
              <v-list style="position: absolute; right: 50px; top: 10px; z-index: 999;">
                <v-list-tile>
                  <v-list-tile-action>
                    <v-switch v-model="chooseTimeDim" color="orange"></v-switch>
                  </v-list-tile-action>
                  <v-list-tile-title>24小时/2周</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-card-actions>
          </v-flex>
          <v-flex>
            <v-container id="containerWidth">
              <v-layout>
                <v-flex :class="xsline">
                  <div :id="id" :option="option"></div>
                </v-flex>
                <v-flex xs4 v-if="xsline==='xs4'">
                  <bubbleChartComponent></bubbleChartComponent>
                </v-flex>
                <v-flex xs4 v-if="xsline==='xs4'">
                  <pieChartComponent></pieChartComponent>
                </v-flex>
              </v-layout>
            </v-container>
          </v-flex>
        </v-card>
      </v-flex>
    </v-layout>
    <!-- <v-layout>
      <v-flex xs12 sm6 offset-sm3> -->
        <!-- <v-card>
          <v-card-actions>
              <v-btn flat color="orange">Share</v-btn>
          </v-card-actions>
          <v-card-title primary-title>
            <div :id="id" :option="option" style="min-width:400px;height:400px;"></div>
          </v-card-title>
        </v-card> -->
      <!-- </v-flex>
    </v-layout> -->
  </div>
</template>
<script>
  import { common } from '../common.js';

  import pieChartComponent from './PieChartComponent.vue';
  import bubbleChartComponent from './BubbleChartComponent.vue';
  export default {
    mixins: [
      common
    ],
    components: {
      pieChartComponent,
      bubbleChartComponent
    },
    data() {
      let vm = this;
      return {
        xsline: "xs12",
        drilldownProvince: true,
        containerWidth: 0,
        chooseTimeDim: false,
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
        option: {
          credits: {
            enabled: false
          },
          chart: {
            type: 'line',
            //width: 1399,
            events: {
              //上钻
              drillup: function(e) {
                vm.drilldownProvince = true;
                vm.xsline="xs12";
              }
            }
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
                click: function(e) {
                  // alert(
                  //   this.name + ' 被点击了\n' +
                  //   '最近点：' + event.point.category + '\n' +  
                  //   'Alt 键: ' + event.altKey + '\n' +
                  //   'Ctrl 键: ' + event.ctrlKey + '\n' +
                  //   'Meta 键（win 键）： ' + event.metaKey + '\n' +
                  //   'Shift 键：' + event.shiftKey
                  // );
                  // if( typeof(e.point.drillup) != "undefined"  )
                    // console.log(e.point)
                  //下钻
                  if( typeof(e.point.drilldown) != "undefined"  )
                  {
                    vm.drilldownProvince = false;
                    if (this.name === "全国") {
                      vm.xsline = "xs12";
                    }else{
                      vm.xsline = "xs4";
                    }
                    vm.clickProvince = e.point.drilldown.split('-')[1];
                    // vm.chart.redraw();
                    // vm.chart.reflow();
                    // document.getElementsByClassName("highcharts-container")[0].style.width="500px"
                    //document.getElementsByClassName("highcharts-container")[0].style.width = '100px';/*parseInt(document.getElementsByClassName('highcharts-container')[0].style.width)/3 + "px";*/
                    // console.log(e)
                    // alert(parseInt(document.getElementsByClassName('highcharts-container')[0].style.width)/3)
                  }
                },
              }
            }
          },
          series: [/*{
            name: '无线接通率',
            data: [{
              name: '2019030500',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030500'
            }]
          }*/],
          drilldown: {
            allowPointDrilldown: true, // 将此参数注释再下钻来对比查看效果
            drillUpText: '<< Terug naar {series.name}',
            drillUpButton: {
              relativeTo: 'spacingBox',
              position: {
                y: 0,
                x: 0
              },
              theme: {
                fill: 'white',
                'stroke-width': 1,
                stroke: 'silver',
                r: 1,
                states: {
                  hover: {
                    fill: '#cdcdcd'
                  },
                  select: {
                    stroke: '#cdcdcd',
                    fill: '#cdcdcd'
                  }
                }
              }
            }
            /*series: [{
              type: 'column',
              id: '2019030500',
              name: '2019030500-无线接通率',
              data: [
                ['江苏省', Math.ceil(Math.random()*100)],
                ['广东省', Math.ceil(Math.random()*100)],
                ['湖北省', Math.ceil(Math.random()*100)]
              ],
              events: {
                click:function(events){
                  alert(events.point.name);
                }
              }
            }]*/
          }
        }
      }
    },
    mounted() {
      // new Highcharts.chart(this.id, this.option);
      Highcharts.addEvent(document.getElementById('container'), 'click', function(e){
        if( e.hasOwnProperty('point') && e.target.point != null) {
          alert(event.target.point.name)
        }
      });
    },
    created() {
      this.bus.$on('clickBSideBar', type=>{ 
        this.bSideBar = type.bSideBar;
      });
      this.bus.$on('chooseOperator', type=>{
        this.operator = type.operator; 
      });
      this.bus.$on('chooseCity', type=>{
        if( !type.isUpdateBChartVue ) return;
        type.city.length===0?this.city="":this.city=type.city;
        this.province = type.province;
      });
      this.bus.$on('chooseProvince', type=>{
        if( !type.isUpdateBChartVue ) return;
        this.province = type.province;
      });
      this.bus.$on('type', type=>{
        this.type = type.type;
      });
      this.bus.$on('card', type=>{
        this.card = type.card;
      });
      this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
    },
    watch: {
      xsline() {
        if( this.xsline == "xs4" ) {
          this.containerWidth = parseInt(document.getElementById("containerWidth").scrollWidth);
          this.chart.setSize(parseInt(this.containerWidth/3), 400);
        } else {
          this.chart.setSize(parseInt(this.containerWidth), 400);
        }
        // console.log(this.option.chart.width)
        // alert(parseInt(document.getElementsByClassName('highcharts-container')[0].style.width)/3)
        // this.chart.chartWidth = parseInt(document.getElementsByClassName('highcharts-container')[0].style.width)/3
        // alert(this.option.chart.width)
        // this.option.chart.width = parseInt(this.option.chart.width/3)
        // alert(this.option.chart.width)
        // this.chart.reflow()
        // this.option.chart.width = parseInt(document.getElementsByClassName('highcharts-container')[0].style.width)/3
        // alert(this.chart.chartWidth )
      },
      bSideBar() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      operator() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      city() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      province() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      type() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      card() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      chooseTimeDim() {
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card, this.province, this.chooseTimeDim);
      },
      optionData(val) {
        let self = this;
        this.chart = new Highcharts.chart(this.id, this.option);

        this.xsline = 'xs12';
        this.chart.setSize(parseInt(this.containerWidth), 400);
        
        this.chart.title.update({'text': val.title});
        this.chart.subtitle.update({'text': val.subtitle});
        // val.series: this.chart.update({ series: [{ 'name': 'asd', 'data': [{'name': '2019030500', 'y':Math.ceil(Math.random()*100),  'drilldown': '2019030500' }]}] });
        // this.chart.update({series: val.series[0]});
        // val.series.reverse();
        // console.log('city:',this.city, 'province:',this.province)

        let regionSeries =  {};
        if( this.city !== '' ) {
          regionSeries = val['city-series'];
        } else {
          regionSeries = val['series'];
        }
        // console.log(regionSeries);
        _.forEach(regionSeries, (o) => {
          if ( self.province === 'national' ) {
            o.visible = true;
          }else if( self.province === o.spellName ) {
            o.visible = true;
            //均值
            if ( (typeof val.assessmentPlots === 'string' && val.assessmentPlots === '禁用') || (typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '禁用') ) {
              let _thisO = o; 
              o.data = _.filter(o.data, function(o){ return o.y<_thisO.plots });
              // o.data = _.forEach(o.data, function(o){ if(o.y>_thisO.plots){
              //   o.y = null
              // } });
            //考核
            }else if ( typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用' ) {
              o.data = _.filter(o.data, function(o){ return o.y<val.assessmentPlots.assessment });
            }
          }else if( self.city === o.spellName ) {
            o.visible = true;
            if ( typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用' ) {
              o.data = _.filter(o.data, function(o){ return o.y<val.assessmentPlots.assessment });
            }
          }else {
            o.visible = false;
          }
          self.chart.addSeries(o);
        });

        if( this.province !== 'national' && this.city === '' ) {
          _.forEach( val['city-series'], (o)=>{
            o.visible = true;
            if ( typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用' ) {
              o.data = _.filter(o.data, function(o){ return o.y<val.assessmentPlots.assessment });
            }
            self.chart.addSeries(o);
          });
        }

        // this.chart.redraw();
        this.chart.yAxis[0].removePlotLine('plot-line');
        _.filter(regionSeries, (o)=>{
          if( self.province !== 'national' && self.province === o.spellName ) {
            // self.chart.addSeries(o);
            o.visible = true;
            self.chart.yAxis[0].addPlotLine({
              value: o.plots,
              width:2,
              color: 'green',
              id: 'plot-line',
              dashStyle: 'Dash',
              label:{
                text: '均值:'+/*o.name+': '+*/o.plots+'%',
                align:'left',
                x:0,
                style: {
                  color: 'green'
                }
              },
              zIndex: 999,
              events: {
                click:function(){
                    //当标示线被单击时，触发的事件
                },
                mouseover:function(){
                    //当标示线被鼠标悬停时，触发的事件
                },

                mouseout:function(){
                    //当标示线被鼠标移出时，触发的事件
                },

                mousemove:function(){
                    //当标示线被鼠标移动(时，触发的事件
                }
              }
            });
          }
          if( typeof val.assessmentPlots === 'object' && val.assessmentPlots.status === '启用' ) {
              self.chart.yAxis[0].addPlotLine({
                value: val.assessmentPlots.assessment,
                width: 2,
                color: 'red',
                id: 'plot-line',
                dashStyle: 'LongDashDotDot',
                label:{
                  text: '考核:'+/*o.name+': '+*/val.assessmentPlots.assessment+'%',
                  align:'right',
                  x:0,
                  style: {
                    color: 'red'
                  }
                },
                zIndex: 999,
                events: {
                  click:function(){
                      //当标示线被单击时，触发的事件
                  },
                  mouseover:function(){
                      //当标示线被鼠标悬停时，触发的事件
                  },

                  mouseout:function(){
                      //当标示线被鼠标移出时，触发的事件
                  },

                  mousemove:function(){
                      //当标示线被鼠标移动(时，触发的事件
                  }
                }
              });
            }
        });

        // val.drilldown: [{ "type": 'column', "id": "2019031200", "name": '2019031200', "data": [['ss', 44],['dd',55]], "events": {click:JSON.stringify("function(events){alert(events.point.name);}")} }]
        this.chart.drilldown.update({ series: val.drilldown });
        // this.chart.drilldown.update({ series: [{ "type": 'column', "id": "2019032000-national", "name": '1', "data": [['ss', 44],['dd',55]] }, { "type": 'pie', "id": "2019032001-national", "name": '1', "size": 100, "center": [100, 80], "data": [['ssd', 44],['ddd',55]] }] });
        // this.chart.drilldown.update({ series: [{ "type": 'column', "id": "2019032000-national", "name": '1', "data": [['ss', 44],['dd',55]] }, { "type": 'pie', "id": "2019032001-national", "name": '1', "size": 100, "center": [100, 80], "data": [['ssd', 44],['ddd',55]] }] });
      },
      // clickProvince(city) {
      //   this.bus.$emit('clickProvinceFromBChartVue', {
      //     city: city,
      //     isUpdateBChartVue: false
      //   });
      // }
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bChartStatus) {
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
</script>