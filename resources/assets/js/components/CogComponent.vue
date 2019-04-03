<template>
  <div style="background-color: white">
    <input style="display: none;" id="input" :loadData="loadData">
    <v-container fluid grid-list-xl>
      <v-layout row align-space-around justify-start fill-height>
        <v-flex
          shrink
          pa-0
        >
          <BirdSideBarComponent></BirdSideBarComponent>
        </v-flex>
        <v-flex
          grow
          pa-2
        >
          <v-container grid-list-md>
            <v-layout row wrap>
              <v-flex 
                xs12 pa-0
              >
                <v-toolbar flat color="white">
                  <v-toolbar-title>系统配置</v-toolbar-title>
                  <!-- <v-divider
                    class="mx-2"
                    inset
                    vertical
                  ></v-divider> -->
                  <v-spacer></v-spacer>
                  <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                      <v-btn color="primary" dark class="mb-2" v-on="on">New</v-btn>
                    </template>
                    <v-card>
                      <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                      </v-card-title>

                      <v-card-text>
                        <v-container grid-list-md>
                          <v-layout wrap>
                            <v-flex xs12 sm6 md4>
                              <!-- <v-text-field v-model="editedItem.location" label="地区"></v-text-field> -->
                              <v-select
                                v-model="editedItem.location"
                                :items="locationSelect"
                                label="地区"
                                return-object
                              >
                              </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                              <!-- <v-text-field v-model="editedItem.operator" label="运营商"></v-text-field> -->
                              <v-select
                                v-model="editedItem.operator"
                                :items="operatorSelect"
                                label="运营商"
                                return-object
                              > 
                              </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                              <!-- <v-text-field v-model="editedItem.system" label="制式"></v-text-field> -->
                              <v-select
                                v-model="editedItem.system"
                                :items="systemSelect"
                                label="制式"
                                return-object
                              > 
                              </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                              <!-- <v-text-field v-model="editedItem.index" label="指标"></v-text-field> -->
                              <v-select
                                v-model="editedItem.index"
                                :items="indexSelect"
                                label="指标"
                                return-object
                              > 
                              </v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                              <v-text-field v-model="editedItem.assessment" label="考核线"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                              <!-- <v-text-field v-model="editedItem.status" label="状态"></v-text-field> -->
                              <v-select
                                v-model="editedItem.status"
                                :items="statusSelect"
                                label="状态"
                                return-object
                              >
                              </v-select>
                            </v-flex>
                          </v-layout>
                        </v-container>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
                        <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-toolbar>
                <v-data-table
                  :headers="headers"
                  :items="desserts"
                  class="elevation-1"
                >
                  <template v-slot:items="props">
                    <!-- <td>{{ props.item.location.text }}</td>
                    <td>{{ props.item.operator.text }}</td>
                    <td>{{ props.item.system.text }}</td>
                    <td>{{ props.item.index.text }}</td>
                    <td>{{ props.item.assessment }}</td>
                    <td>{{ props.item.status.text }}</td> -->
                    <td>{{ props.item.location }}</td>
                    <td>{{ props.item.operator }}</td>
                    <td>{{ props.item.system }}</td>
                    <td>{{ props.item.index }}</td>
                    <td>{{ props.item.assessment }}</td>
                    <td>{{ props.item.status }}</td>
                    <td class="justify-center layout px-0">
                      <v-icon
                        small
                        class="mr-2"
                        @click="editItem(props.item)"
                      >
                        edit
                      </v-icon>
                      <v-icon
                        small
                        @click="deleteItem(props.item)"
                      >
                        delete
                      </v-icon>
                    </td>
                  </template>
                  <template v-slot:no-data>
                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                  </template>
                </v-data-table>
              </v-flex>
            </v-layout>
          </v-container>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
  import BirdSideBarComponent from './BirdSideBarComponent.vue';
  import { common } from '../common.js';
  export default {
    mixins: [
      common
    ],
    components: {
      BirdSideBarComponent
    },
    data: () => ({
      locationSelect: [ //'全国', '江苏省', '广东省', '南京市'
        // { 'value': 'national', 'text': '全国' },
        // { 'value': 'jiangsu', 'text': '江苏省' },
        // { 'value': 'guangdong', 'text': '广东省' },
        // { 'value': 'nanjing', 'text': '南京市' }
      ],
      operatorSelect: [ '中国移动', '中国联通', '中国电信'
        // { 'value': 'mobile', 'text': '中国移动' },
        // { 'value': 'unicom', 'text': '中国联通' },
        // { 'value': 'telecommunications', 'text': '中国电信' }
      ],
      systemSelect: [ 'LTE', 'VOLTE', 'GSM', 'NBIOT'
        // { 'value': 'lte', 'text': 'LTE' },
        // { 'value': 'volte', 'text': 'VOLTE' },
        // { 'value': 'gsm', 'text': 'GSM'},
        // { 'value': 'nbiot', 'text': 'nbiot' }
      ],
      indexSelect: [ '无线接通率', '无线掉线率'
        // { 'value': 'lowaccess', 'text': '无线接通率' },
        // { 'value': 'l1', 'text': '无线掉线率' }
      ],
      statusSelect: [ '启用', '禁用'
        // { 'value': 'on', 'text': '启用' }, 
        // { 'value': 'off', 'text': '禁用' } 
      ],
      dialog: false,
      headers: [
        {
          // text: 'Dessert (100g serving)',
          // align: 'left',
          // sortable: false,
          // value: 'name'
          text: '地区',
          value: 'location'
        },
        { text: '运营商', value: 'operator' },
        { text: '制式', value: 'system' },
        { text: '指标', value: 'index' },
        { text: '考核线', value: 'assessment' },
        { text: '状态', value: 'status' }
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        location: null,
        operator: null,
        system: null,
        index: null,
        assessment: null,
        status: null
      },
      defaultItem: {
        location: null,
        operator: null,
        system: null,
        index: null,
        assessment: null,
        status: null
      }
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New' : 'Edit'
      },
      loadData: function() {
        switch(this.$store.getters.bCogStatus) {
          case 1:
              break;
          case 2:
              let _this = this;
              this.desserts = this.$store.getters.bCog['data'];
              this.locationSelect = [];
              _.forEach( this.$store.getters.bCog['region'], (v)=>{_this.locationSelect.push(v.province);});
              this.indexSelect = [];
              _.forEach( this.$store.getters.bCog['kpi'], (v)=>{_this.indexSelect.push(v.kpi);});
              break;
          case 3:
              break;
          default:
              break;
        }
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      // this.initialize();
      this.processLoadCog();
    },

    methods: {
      initialize () {
        this.desserts = [
          // {
          //   location: { 'value': 'national', 'text': '全国' },
          //   operator: { 'value': 'mobile', 'text': '中国移动' },
          //   system: { 'value': 'lte', 'text': 'LTE' },
          //   index: { 'value': 'lowaccess', 'text': '无线接通率' },
          //   assessment: 98,
          //   status: { 'value': 'off', 'text': '禁用' }
          // },{
          //   location: { 'value': 'national', 'text': '全国' },
          //   operator: { 'value': 'mobile', 'text': '中国移动' },
          //   system: { 'value': 'lte', 'text': 'LTE' },
          //   index: { 'value': 'lowaccess', 'text': '无线接通率' },
          //   assessment: 98,
          //   status: { 'value': 'on', 'text': '启用' }
          // },{
          //   location: { 'value': 'national', 'text': '全国' },
          //   operator: { 'value': 'mobile', 'text': '中国移动' },
          //   system: { 'value': 'lte', 'text': 'LTE' },
          //   index: { 'value': 'lowaccess', 'text': '无线接通率' },
          //   assessment: 97,
          //   status: { 'value': 'on', 'text': '启用' }
          // }
        ]
      },

      editItem (item) {
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        const index = this.desserts.indexOf(item)
        confirm('Are you sure you want to delete this item?') && (this.processDelCog(item) || this.desserts.splice(index, 1))
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {
        // console.log(this.editedItem);
        this.processEditCog(this.editedItem);
        // let status = this.editedItem.status.value;
        // this.editedItem.status = this.editedItem.status.text;
        if (this.editedIndex > -1) {
          //编辑
          Object.assign(this.desserts[this.editedIndex], this.editedItem)
        } else {
          //新增
          this.desserts.push(this.editedItem)
        }
        this.close()
      }
    }
  }
</script>