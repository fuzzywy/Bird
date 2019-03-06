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
        id: 'container',
        option: {
          credits: {
            enabled: false
          },
          chart: {
            type: 'line'
          },
          title: {
            text: '点击点将相同分类的点也一起下钻'
          },
          xAxis: {
            type: 'category'
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
            name: '2010',
            data: [{
              name: 'Republican',
              y: 5,
              drilldown: 'republican-2010'
            }, {
              name: 'Democrats',
              y: 2,
              drilldown: 'democrats-2010'
            }, {
              name: 'Other',
              y: 4,
              drilldown: 'other-2010'
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
              id: 'republican-2010',
              name: 'Republican 2010',
              data: [
                ['East', 4],
                ['West', 2],
                ['North', 1],
                ['South', 4]
              ],
              events: {
                click:function(events){
                  alert(events.point.name);
                }
              }
            }, {
              type: 'column',
              id: 'democrats-2010',
              name: 'Republican 2010',
              data: [
                ['East', 6],
                ['West', 2],
                ['North', 2],
                ['South', 4]
              ],
              events: {
                click:function(events){
                  alert(events.point.name);
                }
              }
            }, {
              type: 'column',
              id: 'other-2010',
              name: 'Other 2010',
              data: [
                ['East', 2],
                ['West', 7],
                ['North', 3],
                ['South', 2]
              ],
              events: {
                click:function(events){
                  alert(events.point.name);
                }
              }
            }/*, {
              id: 'republican-2014',
              name: 'Republican 2014',
              data: [
                ['East', 2],
                ['West', 4],
                ['North', 1],
                ['South', 7]
              ]
            }, {
              id: 'democrats-2014',
              name: 'Democrats 2014',
              data: [
                ['East', 4],
                ['West', 2],
                ['North', 5],
                ['South', 3]
              ]
            }, {
              id: 'other-2014',
              name: 'Other 2014',
              data: [
                ['East', 7],
                ['West', 8],
                ['North', 2],
                ['South', 2]
              ]
            }*/]
          }
        }
      }
    },
    mounted() {
      new Highcharts.chart(this.id, this.option)
    }
  }
</script>