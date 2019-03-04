<template>
  <div>
    <!-- height="20px" -->
    <v-tabs 
      
      slider-color="yellow"
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
  export default {
    data () {
      return {
        active: 0,
        operator: 'mobile',
        operators: [ 
          { id: 0, operator: 'mobile', name: '中国移动' },
          { id: 1, operator: 'unicom', name: '中国联通' },
          { id: 2, operator: 'telecommunications', name: '中国电信' }
        ]
      }
    },
    methods: {
      operatorFix: function(operator) {
        this.operator = operator;
        this.bus.$emit('operator', this.operator);
      }
    }
  }
</script>