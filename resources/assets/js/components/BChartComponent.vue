<template>
  <!-- https://www.highcharts.com.cn/docs/basic-drilldown -->
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <v-layout>
      <v-flex xs12 sm12>
        <v-card hover>
          <!-- <v-img
            src="https://cdn.vuetifyjs.com/images/cards/desert.jpg"
            aspect-ratio="2.75"
          ></v-img> -->
          <v-flex offset-sm10>
            <v-card-actions>
              <!-- <v-btn flat color="orange">Share</v-btn> -->
              <v-list>
                <v-list-tile>
                  <v-list-tile-action>
                    <v-switch v-model="messageeee" color="orange"></v-switch>
                  </v-list-tile-action>
                  <v-list-tile-title>24小时/2周</v-list-tile-title>
                </v-list-tile>
              </v-list>
              <!-- <v-switch v-model="message" color="orange"></v-switch> -->
            </v-card-actions>
          </v-flex>
          <div :id="id" :option="option" style="min-width:400px;height:400px;"></div>
          <!-- <v-card-title primary-title>
            <div>
              <h3 class="headline mb-0">Kangaroo Valley Safari</h3>
              <div> {{ card_text }} </div>
            </div>
          </v-card-title>

          <v-card-actions>
            <v-btn flat color="orange">Share</v-btn>
            <v-btn flat color="orange">Explore</v-btn>
          </v-card-actions> -->
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
  export default {
    mixins: [
      common
    ],
    data() {
      return {
        messageeee: false,
        chart: {},
        bSideBar: "indexoverview",
        operator: "mobile",
        city: "national",
        type: "eniq",
        card: "无线接通率",
        id: 'container',
        // option: {},
        option: {
          credits: {
            enabled: false
          },
          chart: {
            type: 'line'
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
          },
          plotOptions: {
            series: {
              borderWidth: 0,
              dataLabels: {
                enabled: true
              }
            }
          },
          series: [{
            name: '无线接通率',
            data: [/*{
              name: '2019030500',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030500'
            }*/]
          }],
          drilldown: {
            allowPointDrilldown: false, // 将此参数注释再下钻来对比查看效果
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
      this.chart = new Highcharts.chart(this.id, this.option);
      // chart.reflow();
      // this.chart = chart;
    },
    created() {
      this.bus.$on('clickBSideBar', type=>{ 
        this.bSideBar = type.bSideBar;
      });
      this.bus.$on('chooseOperator', type=>{
        this.operator = type.operator; 
      });
      this.bus.$on('chooseCity', type=>{
        type.city.length===0?this.city="national":this.city=type.city;
      });
      this.bus.$on('type', type=>{
        this.type = type.type;
      });
      this.bus.$on('card', type=>{
        this.card = type.card;
      });
      this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card);
    },
    watch: {
      bSideBar() {
        // alert(this.bSideBar)
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card);
      },
      operator() {
        // alert(this.operator)
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card);
      },
      city() {
        // alert(this.city)
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card);
      },
      type() {
        // alert(this.type)
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card);
      },
      card() {
        // alert(this.card);
        this.processLoadBChart(this.bSideBar, this.operator, this.city, this.type, this.card);
      },
      option(val) {
        this.chart.title.update({'text': val.title});
        this.chart.subtitle.update({'text': val.subtitle});
        // val.series: this.chart.update({ series: [{ 'name': 'asd', 'data': [{'name': '2019030500', 'y':Math.ceil(Math.random()*100),  'drilldown': '2019030500' }]}] });
        this.chart.update({series: val.series});
        // val.drilldown: [{ "type": 'column', "id": "2019031200", "name": '2019031200', "data": [['ss', 44],['dd',55]], "events": {click:JSON.stringify("function(events){alert(events.point.name);}")} }]
        this.chart.drilldown.update({ series: val.drilldown }); 
        Highcharts.addEvent(document.getElementById('container'), 'click', function(e) {
        	if( e.hasOwnProperty('point') ) {
        		alert(e.target.point.name)
        	}
        })
      }
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bChartStatus) {
          case 1:
              break;
          case 2:
              this.option = this.$store.getters.bChart;
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