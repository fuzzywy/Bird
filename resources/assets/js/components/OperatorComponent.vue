<template>
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <div class="text-xs-center" v-if="this.$store.getters.bOperatorsStatus!==2">
      <v-spacer></v-spacer>
      <v-progress-circular
        indeterminate
        color="blue"
      ></v-progress-circular>
    </div>
    <v-tabs v-if="this.$store.getters.bOperatorsStatus===2"
      flat
      fixed-tabs
      slider-color="blue"
      v-model="active"
    >
      <v-tab
        v-for="operator in operators"
        :key="operator.id"
        @click="operatorFix(operator.operator)"
      >
        {{ operator.name }}
      </v-tab>
    </v-tabs>
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
        active: 0,
        operator: 'mobile',
        operators: [ 
          // { id: 0, operator: 'mobile', name: '中国移动' },
          // { id: 1, operator: 'unicom', name: '中国联通' },
          // { id: 2, operator: 'telecommunications', name: '中国电信' }
        ]
      }
    },
    methods: {
      operatorFix: function(operator) {
        this.operator = operator;
        this.bus.$emit('chooseOperator', {
          operator: this.operator
        });
      }
    },
    created: function(){
      this.processloadBOperators();
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bOperatorsStatus) {
          case 1:
              break;
          case 2:
              this.operators = this.$store.getters.bOperators;
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