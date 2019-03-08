<template>
  <div>
    <input style="display: none;" id="input" :loadData="loadData">
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant.sync="mini"
      hide-overlay
      stateless
      height="-webkit-fill-available"
    >
      <v-toolbar flat class="transparent">
        <v-list class="pa-0">
          <v-list-tile avatar>
            <v-list-tile-action>
              <v-icon v-html="'$vuetify.icons.configuration'"></v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>网络概览</v-list-tile-title>
            </v-list-tile-content>

            <v-list-tile-action>
              <v-btn
                icon
                @click.stop="mini = !mini"
              >
                <v-icon>chevron_left</v-icon>
              </v-btn>
            </v-list-tile-action>
          </v-list-tile>
        </v-list>
      </v-toolbar>
      <v-list class="pt-0" dense>
        <v-divider></v-divider>
        <div class="text-xs-center" v-if="this.$store.getters.bSideBarItemsStatus!==2">
          <v-progress-circular
            indeterminate
            color="blue"
          ></v-progress-circular>
        </div>
        <v-tooltip bottom fixed v-if="this.$store.getters.bSideBarItemsStatus===2">
          <template v-slot:activator="{ on }">
            <v-list-tile
              v-for="(item, key) in items"
              :key="key"
              @click.stop="clickBSideBar(item)"
            >
              <v-list-tile-action>
                <v-icon v-on="on">{{ item.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </template>
          <span >tip</span>
        </v-tooltip>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>
<script>
  import {common} from '../common.js';
  export default {
    mixins: [
      common
    ],
    data () {
      return {
        drawer: true,
        items: [
          // { id: 0, title: '指标概览', icon: 'dashboard', routertag: 'indexoverview' },
          // { id: 1, title: '规模概览', icon: 'question_answer', routertag: 'scaleoverview' },
          // { id: 2, title: '负荷概览', icon: 'zoom_out_map', routertag: 'loadoverview' }
        ],
        mini: true
      }
    },
    methods: {
      clickBSideBar: function(item) {
        this.mini = true;
        this.bus.$emit('clickBSideBar', {
          bSideBar: item.routertag
        });
        // alert(item.routertag);
      }
    },
    created: function(){
      this.processloadBSideBarItems();
    },
    computed: {
      loadData: function(){
        switch(this.$store.getters.bSideBarItemsStatus) {
          case 1:
              break;
          case 2:
              this.items = this.$store.getters.bSideBarItems;
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