<template>
  <div>
    <v-menu
      v-for="type in types"
      :key="type.id"
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
        <!-- <v-list>
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title>类型选择</v-list-tile-title>
              <v-list-tile-sub-title>不选默认全选</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
        <v-divider></v-divider> -->
        <v-list>
          <v-list-tile 
            v-for="city in system"
            :key="city.id"
          >
            <v-list-tile-action>
              <!-- <v-switch v-model="chooseSystem" :value="city.value" color="blue"></v-switch> -->
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
        clickType: 'national',
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
</script>