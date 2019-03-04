<template>
  <div>
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
            <!-- <v-list-tile-avatar>
              <img src="/85.jpg"/>
            </v-list-tile-avatar> -->
            <v-list-tile-action>
              <!-- <v-icon>question_answer</v-icon> -->
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
        <v-list-tile
          v-for="item in items"
          :key="item.title"
          @click="clickBSideBar(item)"
        >
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        drawer: true,
        items: [
          { id: 0, title: '指标概览', icon: 'dashboard', routertag: 'indexoverview' },
          { id: 1, title: '规模概览', icon: 'question_answer', routertag: 'scaleoverview' },
          { id: 2, title: '负荷概览', icon: 'zoom_out_map', routertag: 'loadoverview' }
        ],
        mini: true,
        right: null
      }
    },
    methods: {
      clickBSideBar: function(item) {
        this.bus.$emit('clickBSideBar', item.routertag);
        // alert(item.routertag)
      }
    }
  }
</script>