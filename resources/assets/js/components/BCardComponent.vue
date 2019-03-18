<template>
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <div class="text-xs-center" v-if="this.$store.getters.bCardsStatus!==2">
      <v-progress-circular
        indeterminate
        color="blue"
      ></v-progress-circular>
    </div>
    <v-container v-if="this.$store.getters.bCardsStatus===2" 
      grid-list-md
    ><!-- fluid -->
      <v-layout align-center justify-center fill-height row wrap> <!-- row wrap -->
        <v-flex
          v-for="item in cards"
          :key="item.id"
          v-bind="{ [`xs${item.flex}`]: true }"
        >
          <v-card   
            :color="item.color"
            dark    
            hover
            @click="clickCard(item.type)"
            tag="div"
          >
            <v-card-title style="height: 30px; padding-bottom: 10px;">
              <span class="title font-weight-light mx-auto">{{ item.type }}</span>
            </v-card-title>
            <v-card-text class="headline font-weight-bold" style="text-align: center; height: 50px">
              {{ item.data }}
              <v-icon class="mr-0" :color="item.class==='arrow_upward'? '#1296db':'#d81e06'">{{ item.class }}</v-icon>
              <span class="subheading mr-0" style="margin-left: -8px;">{{ item.tend }}</span>
            </v-card-text>
            <v-card-title>
              <v-layout
                justify-center
              >
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
        bSideBar: "indexoverview",
        operator: "mobile",
        city: "national",
        type: "eniq",
        cards: [
          // { id: 0, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '98.4%', tend: '0.03%', type: '无线接通率', flex: 3, time: '2019/03/13' },
          // { id: 1, class: 'icon-ali-jiantouxiajiang-red', color: 'red', data: '8.4%', tend: '0.01%', type: '无线掉线率', flex: 3, time: '2019/03/13' },
          // { id: 2, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '99.4%', tend: '0.05%', type: '切换成功率', flex: 3, time: '2019/03/13' },
          // { id: 3, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '99.4%', tend: '0.05%', type: '切换成功率', flex: 3, time: '2019/03/13' }
        ]
      }
    },
    methods: {
      clickCard: function( type ) {
        this.bus.$emit('card', {
          card: type
        });
      }
    },
    created: function(){
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
      this.processloadBCards();
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bCardsStatus) {
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
        this.processloadBCards();
      },
      operator() {
        this.processloadBCards();
      },
      city() {
        this.processloadBCards();
      },
      type() {
        this.processloadBCards();
      }
    }
  }
</script>