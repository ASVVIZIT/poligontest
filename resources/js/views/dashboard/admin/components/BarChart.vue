<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';

const animationDuration = 6000;

export default {
  props: {
    className: {
      type: String,
      default: 'chart',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '300px',
    },
  },
  data() {
    return {
      chart: null,
    };
  },
  mounted() {
    this.initChart();
    this.__resizeHandler = debounce(() => {
      if (this.chart) {
        this.chart.resize();
      }
    }, 100);
    window.addEventListener('resize', this.__resizeHandler);
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    window.removeEventListener('resize', this.__resizeHandler);
    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');

      this.chart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { // Axis indicator, axis trigger is valid
            type: 'shadow', // The default is a straight line, which can be selected as: 'line' | 'shadow'
          },
        },
        grid: {
          top: 10,
          left: '2%',
          right: '2%',
          bottom: '3%',
          containLabel: true,
        },
        xAxis: [{
          type: 'category',
          data: ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресение'],
          axisTick: {
            alignWithLabel: true,
          },
        }],
        yAxis: [{
          type: 'value',
          axisTick: {
            show: false,
          },
        }],
        series: [{
          name: 'Бригада A',
          type: 'bar',
          stack: 'Число часов',
          barWidth: '60%',
          data: [65, 62, 200, 334, 390, 567, 220],
          animationDuration,
        }, {
          name: 'Бригада B',
          type: 'bar',
          stack: 'Число часов',
          barWidth: '60%',
          data: [80, 52, 200, 666, 567, 330, 220],
          animationDuration,
        }, {
          name: 'Бригада C',
          type: 'bar',
          stack: 'Число часов',
          barWidth: '60%',
          data: [30, 67, 367, 334, 390, 456, 567],
          animationDuration,
        }],
      });
    },
  },
};
</script>
