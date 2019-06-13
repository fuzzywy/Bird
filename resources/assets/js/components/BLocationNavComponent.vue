<template>
    <div>
        <input style="display: none;" id="input" :loadData="loadData">
        <div class="text-xs-center" v-if="this.$store.getters.bRegionStatus!==2">
            <v-spacer></v-spacer>
            <v-progress-circular indeterminate color="blue"></v-progress-circular>
        </div>
        <div v-if="this.$store.getters.bRegionStatus===2">
            <!-- <span style="font-size:16px" class="ml-2">
                指标概览
            </span> -->
            <v-menu offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn color="#2196f3" dark v-on="on" class="mt-2 mb-2">
                        <v-icon v-html="'icon-ali-map-marker'"></v-icon>
                        {{ locationDim }}&nbsp;&nbsp;&nbsp;&nbsp;
                        <v-icon v-html="'icon-ali-jiantouxia'" style="font-size:12px"></v-icon>
                    </v-btn>
                </template>
                <v-container style="background-color: white;">
                    <v-layout wrap>
                        <v-flex xs12 sm12 md12 xl12 lg12 pa-0 mx-3>
                            <v-btn v-for="(province, key) in region" :key="key" @click="click(province)" dark
                                :outline="province.value!==buttonProvince" color="#2196f3">
                                {{ province.label }}
                            </v-btn>
                        </v-flex>
                        <v-flex xs12 sm12 pa-0>
                            <v-list>
                                <v-list-tile v-for="(province, p) in region" :key="p" v-if="province.cities.length>0">
                                    <v-btn v-for="(city, key) in province.cities" :key="key" dark
                                        @click="clickCity(city,province.value)" :outline="city.value!==buttonCities"
                                        color="#2196f3">
                                        {{ city.label }}
                                    </v-btn>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-menu>
        </div>
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
        data() {
            return {
                region: [
                    // { id: 0, label: '全国', value: 'national', cities: [] },
                    // { id: 1, label: '江苏省', value: 'jiangsu', cities: [ { id: 0, label: '南京', value: 'nanjing' }, { id: 1, label: '常州', value: 'changzhou' } ] },
                    // { id: 2, label: '湖北省', value: 'hubei', cities: [ { id: 0, label: '武汉', value: 'wuhan' }, { id: 1, label: '荆州', value: 'jingzhou' } ] },
                    // { id: 3, label: '广东省', value: 'guangdong', cities: [ { id: 0, label: '广州', value: 'guangzhou' }, { id: 1, label: '清远', value: 'qingyuan' } ] }
                ],
                cities: [],
                clickProvince: 'national',
                chooseCities: '', //通过emit发出
                buttonProvince: 'national',
                buttonCities: '',
                isUpdateBChartVue: true,
                locationDim: "全国",
            }
        },
        methods: {
            click: function (province) {
                this.clickProvince = province.value;
                this.buttonProvince = province.value;
                this.chooseCities = '';
                this.buttonCities = '';

                this.locationDim = province.label;
            },
            clickCity: function (city, province) {
                this.isUpdateBChartVue = true;
                this.chooseCities = city.value;
                this.buttonCities = city.value;
                this.buttonProvince = "";

                this.locationDim = city.label;
                this.clickProvince = province;
            }
        },
        created() {
            this.processloadBRegion();
        },
        computed: {
            loadData: function () {
                switch (this.$store.getters.bRegionStatus) {
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
            chooseCities: function () {
                this.bus.$emit('chooseCity', {
                    city: this.chooseCities,
                    province: this.clickProvince,
                    isUpdateBChartVue: this.isUpdateBChartVue
                });
            },
            clickProvince: function () {
                this.bus.$emit('chooseProvince', {
                    province: this.clickProvince,
                    isUpdateBChartVue: this.isUpdateBChartVue
                });
            }
        }
    }
</script>