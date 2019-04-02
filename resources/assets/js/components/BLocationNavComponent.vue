<template>
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <div class="text-xs-center" v-if="this.$store.getters.bRegionStatus!==2">
      <v-spacer></v-spacer>
      <v-progress-circular
        indeterminate
        color="blue"
      ></v-progress-circular>
    </div>
    <div v-if="this.$store.getters.bRegionStatus===2">
      <!-- <v-menu 
        v-for="(province, key) in region"
        :key="key"
        open-on-hover
        bottom
        transition="scale-transition"
        origin="center center"
        offset-y
        :close-on-content-click="false"
        :nudge-width="200"
      > -->
      
          
      <v-card flat>
        <v-container>
          <v-layout row wrap>
            <v-flex xs6 sm12 md12 xl12 lg12 pa-0>
              <v-btn 
                v-for="(province, key) in region"
                :key="key"
                @click="click(province.value)"
                dark
                :outline="province.value!==buttonProvince" color="blue"
              >
                {{ province.label }}
              </v-btn>
            </v-flex>
            <v-flex xs12 sm12 pa-0
              v-if="clickProvince!=='national'">
              <v-btn 
                v-for="(city, key) in cities"
                :key="key" 
                dark
                @click="clickCity(city.value)"
                :outline="city.value!==buttonCities" color="blue"
              >
                {{ city.label }}
              </v-btn>
            </v-flex>
            <!-- <v-flex xs12 sm12 pa-0>
              <v-breadcrumbs :items="items" divider=">"></v-breadcrumbs>
            </v-flex> -->
          </v-layout>
        </v-container>
      </v-card>
          
        <!-- <v-card v-if="province.value!=='national'">
          <v-list>
            <v-list-tile 
              v-for="(city, key) in cities"
              :key="key"
            >
              <v-list-tile-action>
                <v-checkbox
                  v-model="chooseCities"
                  color="blue"
                  :value="city.value"
                  hide-details
                ></v-checkbox>
              </v-list-tile-action>
              <v-list-tile-title>{{ city.label }}</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-card> -->
      <!-- </v-menu> -->
    </div>
  </div>
</template>
<script>
  import { common } from '../common.js';
  export default {
    mixins: [
      common
    ],
    data () {
      return {
        region: [
          // { id: 0, label: '全国', value: 'national', cities: [] },
          // { id: 1, label: '江苏省', value: 'jiangsu', cities: [ { id: 0, label: '南京', value: 'nanjing' }, { id: 1, label: '常州', value: 'changzhou' } ] },
          // { id: 2, label: '湖北省', value: 'hubei', cities: [ { id: 0, label: '武汉', value: 'wuhan' }, { id: 1, label: '荆州', value: 'jingzhou' } ] },
          // { id: 3, label: '广东省', value: 'guangdong', cities: [ { id: 0, label: '广州', value: 'guangzhou' }, { id: 1, label: '清远', value: 'qingyuan' } ] }
        ],
        cities: [],
        clickProvince: 'national',
        chooseCities: 'national', //通过emit发出
        buttonProvince: 'national',
        buttonCities: 'national',
        isUpdateBChartVue: true
      }
    },
    methods: {
      click: function(value) {
        if( value === "national" ) {
          this.clickProvince = "national";
          this.chooseCities = "national";
          this.buttonProvince = "national";
          this.buttonCities = "national";
          this.cities = [{value:"national"}];
        } else {
          let cities = this.region[_.findIndex(this.region, function(o) {return o.value == value})].cities;
          this.clickProvince = value;
          this.cities = cities;

          this.buttonProvince = value;
        }
      },
      clickCity: function(value) {
        this.isUpdateBChartVue = true;
        this.chooseCities = value;
        this.buttonCities = value;
      }
    },
    created() {
      this.processloadBRegion();
      this.bus.$on('clickProvinceFromBChartVue', city=>{
        this.chooseCities = city.city;
        this.isUpdateBChartVue = city.isUpdateBChartVue;
        this.buttonCities = city.city;
        let buttonProvince;
        _.forEach(this.region, function(value, key){
            if(value.value === city.city) {
              buttonProvince=value.value;
              return;
            }
        });
        this.buttonProvince = buttonProvince;
      });
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bRegionStatus) {
          case 1:
              break;
          case 2:
              this.region = this.$store.getters.bRegion;
              break;
          case 3:
              break;
          default:
              break;
        }
      }
    },
    watch: {
      chooseCities: function() {
        this.bus.$emit('chooseCity', {
          city: this.chooseCities,
          province: this.clickProvince,
          isUpdateBChartVue: this.isUpdateBChartVue
        });
      }
    }
  }
</script>