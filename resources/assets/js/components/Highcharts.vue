<template>
    <!-- <div class="x-bar"> -->
        <div :id="id" :option="option"></div>
    <!-- </div> -->
</template>
<script>
    export default {
        // 验证类型
        props: {
            id: {
                type: String
            },
            option: {
                type: Object
            }
        },
        mounted() {
            window.Highcharts.chart(this.id, this.option)
        }
    }
</script>