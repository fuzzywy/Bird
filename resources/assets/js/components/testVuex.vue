<style>

</style>

<template>
    <div id="home">
        <span v-show="cafesLoadStatus == 1">Loading</span>
        <span v-show="cafesLoadStatus == 2">{{ cafe }}Cafes loaded successfully!</span>
        <span v-show="cafesLoadStatus == 3">Cafes loaded unsuccessfully!</span>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                cafe: '123'
            }
        },
        created() {
            this.$store.dispatch( 'loadTest' )
        },
        /**
         * 定义组件的计算属性
         */
        computed: {
            // 获取 cafes 加载状态
            cafesLoadStatus(){
                // alert(this.$store.getters.getCafeLoadStatus)
                this.cafe = this.$store.getters.getCafe;
                return this.$store.getters.getCafeLoadStatus;
            },

            // 获取 cafes
            cafes(){
                // alert(this.$store.getters.getCafe)
                this.cafe = this.$store.getters.getCafe;
                // return this.$store.getters.getCafe;
            }
        }
    }
</script>