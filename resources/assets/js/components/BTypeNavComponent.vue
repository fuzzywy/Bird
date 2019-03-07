<template>
  <div>
    <v-tabs 
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
  export default {
    data () {
      return {
        active: 0,
        operator: 'eniq',
        operators: [ 
          { id: 0, operator: 'eniq', name: 'ENIQ' },
          { id: 1, operator: 'nbiot', name: 'NBIOT' },
          { id: 2, operator: 'volte', name: 'VOLTE' },
          { id: 3, operator: 'gsm', name: 'GSM' }
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
<!-- <template>
  <div>
    <v-container  
      grid-list-md
    >
      <v-layout align-center justify-center fill-height>
        <v-flex
          v-for="type in types"
          :key="type.id"
          xs1
        >
          <v-menu
            open-on-hover
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
              @click="typesFix(type.value)" 
            >
              {{ type.label }}
              <v-icon dark right v-if="clickType===type.value">check_circle</v-icon>
            </v-btn>
            <v-card v-if="type.value!=='volte' && type.value!=='gsm'">
              <v-list>
                <v-list-tile 
                  v-for="city in system"
                  :key="city.id"
                >
                  <v-list-tile-action>
                    <v-checkbox
                      v-model="chooseSystem"
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
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        types: [
          { id: 0, label: 'ENIQ', value: 'eniq', system: [ { id: 0, label: 'TDD', value: 'tdd' }, { id: 1, label: 'FDD', value: 'fdd' } ] },
          { id: 1, label: 'NBIOT', value: 'nbiot', system: [ { id: 0, label: 'TDD', value: 'tdd' }, { id: 1, label: 'FDD', value: 'fdd' } ] },
          { id: 2, label: 'VOLTE', value: 'volte', system: [ ] },
          { id: 3, label: 'GSM', value: 'gsm', system: [ ] }
        ],
        system: [],
        clickType: 'eniq',
        chooseSystem: [] //通过emit发出
      }
    },
    methods: {
      typesFix: function(value) {
        if( this.clickType !== value ) {
          this.chooseSystem = [];
        }
        this.clickType = value;
        this.system = this.types[_.findIndex(this.types, function(o) {return o.value == value})].system;
      }
    }
  }
</script> -->