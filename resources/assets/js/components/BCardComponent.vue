<template>
  <div>
    <v-container  
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
            </v-card-text>
            <v-card-title>
                <v-layout
                  justify-center
                >
                  <v-icon class="mr-2" :color="item.class==='icon-ali-jiantoushangsheng-blue'? '#1296db':'#d81e06'">{{ item.class }}</v-icon>
                  <span class="subheading mr-2">{{ item.tend }}</span>
                </v-layout>
            </v-card-title>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        cards: [
          { id: 0, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '98.4%', tend: '0.03%', type: '无线接通率', flex: 3 },
          { id: 1, class: 'icon-ali-jiantouxiajiang-red', color: 'red', data: '8.4%', tend: '0.01%', type: '无线掉线率', flex: 3 },
          { id: 2, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '99.4%', tend: '0.05%', type: '切换成功率', flex: 3 },
          { id: 3, class: 'icon-ali-jiantoushangsheng-blue', color: 'green', data: '99.4%', tend: '0.05%', type: '切换成功率', flex: 3 }
        ]
      }
    },
    methods: {
      clickCard: function( type ) {
        alert(type);
      }
    }
  }
</script>