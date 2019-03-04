<template>
  <div>
    <v-menu
      v-for="province in region"
      :key="province.id"
      bottom
      transition="scale-transition"
      origin="center center"
      offset-y
      :close-on-content-click="false"
      :nudge-width="200"
    >
      <v-btn 
        color="blue" 
        slot="activator"
        dark
        @click="regionFix(province.value)" 
      >
        {{ province.label }}
        <v-icon dark right v-if="clickProvince===province.value">check_circle</v-icon>
      </v-btn>
      <v-card v-if="province.value!=='national'">
        <!-- <v-list>
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title>城市选择</v-list-tile-title>
              <v-list-tile-sub-title>不选默认全选</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list> -->
       <!--  <v-divider></v-divider> -->
        <v-list>
          <v-list-tile 
            v-for="city in cities"
            :key="city.id"
          >
            <v-list-tile-action>
              <!-- <v-switch v-model="chooseCities" :value="city.value" color="blue"></v-switch> -->
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
      </v-card>
    </v-menu>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        region: [
          { id: 0, label: '全国', value: 'national', cities: [] },
          { id: 1, label: '江苏省', value: 'jiangsu', cities: [ { id: 0, label: '南京', value: 'nanjing' }, { id: 1, label: '常州', value: 'changzhou' } ] },
          { id: 2, label: '湖北省', value: 'hubei', cities: [ { id: 0, label: '武汉', value: 'wuhan' }, { id: 1, label: '荆州', value: 'jingzhou' } ] },
          { id: 3, label: '广东省', value: 'guangdong', cities: [ { id: 0, label: '广州', value: 'guangzhou' }, { id: 1, label: '清远', value: 'qingyuan' } ] }
        ],
        cities: [],
        clickProvince: 'national',
        chooseCities: [] //通过emit发出
      }
    },
    methods: {
      regionFix: function(value) {
        if( this.clickProvince !== value ) {
          this.chooseCities = [];
        }
        this.clickProvince = value;
        this.cities = this.region[_.findIndex(this.region, function(o) {return o.value == value})].cities;
      }
    }
  }
</script>