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
        </v-flex>
        <v-flex
          grow
          pa-0
        >
          <!-- 右侧布局 -->
          <!-- <v-layout row> -->
              <BOperatorComponent></BOperatorComponent>
              <!-- <v-divider></v-divider> -->
            <!-- <v-flex d-flex xs12> -->
              <BLocationNavComponent></BLocationNavComponent>
            <!-- </v-flex> -->
            <v-divider></v-divider>
            <!-- <v-flex d-flex xs12> -->
              <BTypeNavComponent></BTypeNavComponent>
             <!--  <v-divider></v-divider> -->
              <BCardComponent></BCardComponent>
              <!-- <v-divider></v-divider> -->
              <BChartComponent></BChartComponent>
            <!-- </v-flex> -->
            <!-- <v-flex d-flex xs12> -->
              <!-- <v-card color="purple" dark>
                <v-card-title primary class="title">Other</v-card-title>
                <v-card-text>Other</v-card-text>
              </v-card> -->
            <!-- </v-flex> -->
          <!-- </v-layout> -->
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
