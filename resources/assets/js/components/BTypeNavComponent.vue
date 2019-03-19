<template>
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <div class="text-xs-center" v-if="this.$store.getters.bTypesStatus!==2">
      <v-spacer></v-spacer>
      <v-progress-circular
        indeterminate
        color="blue"
      ></v-progress-circular>
    </div>
    <v-tabs v-if="this.$store.getters.bTypesStatus===2"
      flat
      fixed-tabs
      slider-color="blue"
      v-model="active"
    >
      <v-tab
        v-for="type in types"
        :key="type.id"
        @click="typeFix(type.type)"
      >
        {{ type.name }}
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
        type: 'lte',
        types: [ 
          // { id: 0, type: 'eniq', name: 'ENIQ' },
          // { id: 1, type: 'nbiot', name: 'NBIOT' },
          // { id: 2, type: 'volte', name: 'VOLTE' },
          // { id: 3, type: 'gsm', name: 'GSM' }
        ]
      }
    },
    methods: {
      typeFix: function(type) {
        this.type = type;
        this.bus.$emit('type', {
          type: this.type
        });
      }
    },
    created: function(){
      this.processloadBTypes();
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bTypesStatus) {
          case 1:
              break;
          case 2:
              this.types = this.$store.getters.bTypes;
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