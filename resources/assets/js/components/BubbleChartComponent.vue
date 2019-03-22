<template>
  <span :id="id" :option="option"></span>
</template>
<script>
  export default {
    data() {
      return {
        id: 'containerBubble',
        option: {
          chart: {
            type: 'bubble',
            zoomType: 'xy'
          },
          title: {
            text: 'Highcharts 气泡图'
          },
          series: [{
            name:'数据列 1',
            // 每个气泡包含三个值，x，y，z；其中 x，y用于定位，z 用于计算气泡大小
            data: [[97, 36, 79], [94, 74, 60], [68, 76, 58], [64, 87, 56], [68, 27, 73], [74, 99, 42], [7, 93, 87], [51, 69, 40], [38, 23, 33], [57, 86, 31]]
          }, {
            name:'数据列 2',
            data: [[25, 10, 87], [2, 75, 59], [11, 54, 8], [86, 55, 93], [5, 3, 58], [90, 63, 44], [91, 33, 17], [97, 3, 56], [15, 67, 48], [54, 25, 81]]
          }, {
            name:'数据列 3',
            data: [[47, 47, 21], [20, 12, 4], [6, 76, 91], [38, 30, 60], [57, 98, 64], [61, 17, 80], [83, 60, 13], [67, 78, 75], [64, 12, 10], [30, 77, 82]]
          }]
        }
      }
    },
    mounted() {
      new Highcharts.chart(this.id, this.option);
    }
  }
</script>