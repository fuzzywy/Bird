<template>
    <div>
        <input style="display: none;" id="input" :loadData="loadData">
        <div class="text-xs-center" v-if="this.$store.getters.bCardsStatus!==2">
            <v-progress-circular indeterminate color="blue"></v-progress-circular>
        </div>
        <!-- <v-layout>
                <v-card> -->
        <v-flex xs12 sm12 pa-1 mt-1 mb-1 style="background-color: #fff;">
            <v-container v-if="this.$store.getters.bCardsStatus===2" grid-list-md>
                <!-- fluid -->
                <v-layout align-center justify-center row wrap>
                    <!-- row wrap -->
                    <v-flex v-for="item in cards" :key="item.id" v-bind="{ [`xs${item.flex}`]: true }" xl2 lg3 md3 sm6
                        xs12>
                        <v-card :color="item.color" dark hover @click="clickCard(item.type)" tag="div">
                            <v-card-title class="pt-2 pb-0" style="height: 26px;font-size:16px">
                                <span class="font-weight-light mx-auto">{{ item.type }}</span>
                            </v-card-title>
                            <v-card-text class="font-weight-bold pt-2"
                                style="text-align: center; height: 40px;font-size:16px">
                                {{ item.data }}
                                <v-icon class="mr-0" :color="item.class==='arrow_upward'? '#1296db':'#d81e06'">
                                    {{ item.class }}</v-icon>
                                <span class="subheading mr-0" style="margin-left: -8px;">{{ item.tend }}</span>
                            </v-card-text>
                            <v-card-title class="pt-2" style="font-size:12px">
                                <v-layout justify-center>
                                    上次更新: {{ item.time }}
                                </v-layout>
                            </v-card-title>
                            <!-- <v-card-title>
                            <v-layout
                            justify-center
                            >
                            <v-icon class="mr-2" :color="item.class==='icon-ali-jiantoushangsheng-blue'? '#1296db':'#d81e06'">{{ item.class }}</v-icon>
                            <span class="subheading mr-2">{{ item.tend }}</span>
                            </v-layout>
                        </v-card-title> -->
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-flex>
        <!-- </v-card>
        </v-layout> -->
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
                bSideBar: "indexoverview",
                operator: "mobile",
                province: "national",
                city: "",
                type: "lte",
                cards: [
                    // { id: 0, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '98.4%', tend: '0.03%', type: '无线接通率', flex: 3, time: '2019/03/13' },
                    // { id: 1, class: 'icon-ali-jiantouxiajiang-red', color: 'red', data: '8.4%', tend: '0.01%', type: '无线掉线率', flex: 3, time: '2019/03/13' },
                    // { id: 2, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '99.4%', tend: '0.05%', type: '切换成功率', flex: 3, time: '2019/03/13' },
                    // { id: 3, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '99.4%', tend: '0.05%', type: '切换成功率', flex: 3, time: '2019/03/13' }
                ]
            }
        },
        methods: {
            clickCard: function (type) {
                this.bus.$emit('card', {
                    card: type
                });
            }
        },
        created: function () {
            this.bus.$on('clickBSideBar', type => {
                this.bSideBar = type.bSideBar;
            });
            this.bus.$on('chooseOperator', type => {
                this.operator = type.operator;
            });
            // this.bus.$on('chooseCity', type=>{
            //   type.city.length===0?this.city="national":this.city=type.city;
            // });
            // this.bus.$on('chooseProvince', type=>{
            //   this.province = type.province;
            // });
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
            this.processloadBCards(this.bSideBar, this.operator, this.province, this.city, this.type);
        },
        computed: {
            loadData: function () {
                switch (this.$store.getters.bCardsStatus) {
                    case 1:
                        break;
                    case 2:
                        this.cards = this.$store.getters.bCards;
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
            }
        },
        watch: {
            bSideBar() {
                this.processloadBCards(this.bSideBar, this.operator, this.province, this.city, this.type);
            },
            operator() {
                this.processloadBCards(this.bSideBar, this.operator, this.province, this.city, this.type);
            },
            province() {
                this.processloadBCards(this.bSideBar, this.operator, this.province, this.city, this.type);
            },
            city() {
                this.processloadBCards(this.bSideBar, this.operator, this.province, this.city, this.type);
            },
            type() {
                this.processloadBCards(this.bSideBar, this.operator, this.province, this.city, this.type);
            }
        }
    }
</script>