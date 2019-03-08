<template>
  <!-- https://www.highcharts.com.cn/docs/basic-drilldown -->
  <div>
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
  export default {
    data() {
      return {
        messageeee: false,
        chart: {},
        bSideBar: "indexoverview",
        operator: "mobile",
        city: "national",
        id: 'container',
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
            data: [{
              name: '2019030500',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030500'
            }, {
              name: '2019030501',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030501'
            }, {
              name: '2019030502',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030502'
            }, {
              name: '2019030503',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030503'
            }, {
              name: '2019030504',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030504'
            }, {
              name: '2019030505',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030505'
            }, {
              name: '2019030506',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030506'
            }, {
              name: '2019030507',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030507'
            }, {
              name: '2019030508',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030508'
            }, {
              name: '2019030509',
              y: Math.ceil(Math.random()*100),
              drilldown: '2019030509'
            }, {
              name: '20190305010',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305010'
            }, {
              name: '20190305011',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305011'
            }, {
              name: '20190305012',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305012'
            }, {
              name: '20190305013',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305013'
            }, {
              name: '20190305014',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305014'
            }, {
              name: '20190305015',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305015'
            }, {
              name: '20190305016',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305016'
            }, {
              name: '20190305017',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305017'
            }, {
              name: '20190305018',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305018'
            }, {
              name: '20190305019',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305019'
            }, {
              name: '20190305020',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305020'
            }, {
              name: '20190305021',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305021'
            }, {
              name: '20190305022',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305022'
            }, {
              name: '20190305023',
              y: Math.ceil(Math.random()*100),
              drilldown: '20190305023'
            }]
          }/*, {
            name: '2014',
            data: [{
              name: 'Republican',
              y: 4,
              drilldown: 'republican-2014'
            }, {
              name: 'Democrats',
              y: 4,
              drilldown: 'democrats-2014'
            }, {
              name: 'Other',
              y: 4,
              drilldown: 'other-2014'
            }]
          }*/],
          drilldown: {
            allowPointDrilldown: false, // 将此参数注释再下钻来对比查看效果
            series: [{
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
            }, {
              type: 'column',
              id: '2019030501',
              name: '2019030501',
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
            }, {
              type: 'column',
              id: '2019030502',
              name: '2019030502',
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
            }, {
              type: 'column',
              id: '2019030503',
              name: '2019030503',
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
            }, {
              type: 'column',
              id: '2019030504',
              name: '2019030504',
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
            }, {
              type: 'column',
              id: '2019030505',
              name: '2019030505',
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
            }, {
              type: 'column',
              id: '2019030506',
              name: '2019030506',
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
            }, {
              type: 'column',
              id: '2019030507',
              name: '2019030507',
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
            }, {
              type: 'column',
              id: '2019030508',
              name: '2019030508',
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
            }, {
              type: 'column',
              id: '2019030509',
              name: '2019030509',
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
            }, {
              type: 'column',
              id: '2019030510',
              name: '2019030510',
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
            }, {
              type: 'column',
              id: '2019030511',
              name: '2019030511',
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
            }, {
              type: 'column',
              id: '2019030512',
              name: '2019030512',
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
            }, {
              type: 'column',
              id: '2019030513',
              name: '2019030513',
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
            }, {
              type: 'column',
              id: '2019030514',
              name: '2019030514',
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
            }, {
              type: 'column',
              id: '2019030515',
              name: '2019030515',
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
            }, {
              type: 'column',
              id: '2019030516',
              name: '2019030516',
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
            }, {
              type: 'column',
              id: '2019030517',
              name: '2019030517',
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
            }, {
              type: 'column',
              id: '2019030518',
              name: '2019030518',
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
            }, {
              type: 'column',
              id: '2019030519',
              name: '2019030519',
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
            }, {
              type: 'column',
              id: '2019030520',
              name: '2019030520',
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
            }, {
              type: 'column',
              id: '2019030521',
              name: '2019030521',
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
            }, {
              type: 'column',
              id: '2019030522',
              name: '2019030522',
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
            }, {
              type: 'column',
              id: '2019030523',
              name: '2019030523',
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
            }]
          }
        }
      }
    },
    mounted() {
      this.chart = new Highcharts.chart(this.id, this.option);
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
    },
    watch: {
      bSideBar() {
        // alert(this.bSideBar)
      },
      operator() {
        // alert(this.operator)
      },
      city() {
        // alert(this.city)
      }
    }
  }
</script>