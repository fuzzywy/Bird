<template>
    <div style="background-color: #f5f5f5">
        <v-container fluid grid-list-xl pb-4>
            <v-layout row align-space-around justify-start fill-height>
                <v-flex shrink pa-1>
                    <!-- 网络概览布局 -->
                    <BirdSideBarComponent></BirdSideBarComponent>
                    <!-- <router-view name="BirdSideBarComponent"></router-view> -->
                </v-flex>
                <v-flex grow pa-1>
                    <!-- 右侧布局 -->
                    <v-container grid-list-md pa-0 mt-1>
                        <v-layout row wrap>
                            <v-flex xs12 pl-2 pr-2 pt-2 pb-1>
                                <!-- <v-layout row> -->
                                <BOperatorComponent></BOperatorComponent>
                            </v-flex>
                            <v-flex xs12 pl-2 pr-2 pt-0 pb-0>
                                <!-- <v-card> -->
                                <v-flex pl-1 pr-1 pt-0 pb-0 style="background-color: #fff;display: flex;">
                                    <v-flex xs2 pa-0>
                                        <BLocationNavComponent></BLocationNavComponent>
                                    </v-flex>
                                    <v-flex xs10 pa-0>
                                        <BTypeNavComponent></BTypeNavComponent>
                                    </v-flex>
                                </v-flex>
                                <!--   </v-card> -->
                            </v-flex>
                            <v-flex xs12 pl-2 pr-2 pt-0 pb-0>
                                <BCardComponent></BCardComponent>
                            </v-flex>
                            <v-flex xs12 pl-2 pr-2 pt-0 pb-0>
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