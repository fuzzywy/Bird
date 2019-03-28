<template>
  <div style="background-color: white">
    <v-container fluid grid-list-xl>
      <v-layout row align-space-around justify-start fill-height>
        <v-flex
          shrink
          pa-0
        >
          <!-- 网络概览布局 -->
          <BirdSideBarComponent></BirdSideBarComponent>
          <!-- <router-view name="BirdSideBarComponent"></router-view> -->
        </v-flex>
        <v-flex
          grow
          pa-0
        >
          <!-- 右侧布局 -->
          <v-container grid-list-md>
            <v-layout row wrap>
              <v-flex 
                xs12 pa-2
              >
              <!-- <v-layout row> -->
                <BOperatorComponent></BOperatorComponent>
              </v-flex>
              <v-flex 
                xs12>
                <BLocationNavComponent></BLocationNavComponent>
              </v-flex>
              <v-divider></v-divider>
              <v-flex 
                xs12>
                <BTypeNavComponent></BTypeNavComponent>
              </v-flex>
              <v-flex 
                xs12>
                <BCardComponent></BCardComponent>
              </v-flex>
              <v-flex 
                xs12>
                <BChartComponent></BChartComponent>
              </v-flex>
            </v-layout>
          </v-container>
        </v-flex>
      </v-layout>
    </v-container>
    <BFooterComponent></BFooterComponent>
  </div>
</template>

<script>
  import BirdSideBarComponent from './BirdSideBarComponent.vue';
  import BOperatorComponent from './OperatorComponent.vue';
  import BLocationNavComponent from './BLocationNavComponent.vue';
  import BTypeNavComponent from './BTypeNavComponent.vue';
  import BCardComponent from './BCardComponent.vue';
  import BChartComponent from './BChartComponent.vue';
  import BFooterComponent from './BFooterComponent.vue';
  export default {
    data() {
        return {
            
        }
    },
    components: {
      BirdSideBarComponent,
      BOperatorComponent,
      BLocationNavComponent,
      BTypeNavComponent,
      BCardComponent,
      BChartComponent,
      BFooterComponent
      // BKpiCardComponent,
      // BLineChartComponent
    },
  }
</script>
