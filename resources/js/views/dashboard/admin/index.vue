<template>
  <div class="dashboard-editor-container">
    <v-expansion-panels
      :focusable="true"
    >
      <v-expansion-panel>
        <order-statistics-group @handleSetLineChartData="handleSetLineChartData" />
        <v-expansion-panel-header class="panel-header-customization"><h3>Таблица поступивших заказов </h3>
          <template v-slot:actions>
            <v-icon color="teal">$expand</v-icon>
          </template>
        </v-expansion-panel-header>
        <v-expansion-panel-content style="display: inline;position: relative">
          <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
            <el-col :xs="{span: 24}" :sm="{span: 24}" :md="{span: 24}" :lg="{span: 24}" :xl="{span: 24}" style="padding-right:8px;margin-bottom:30px;">
              <order-table />
            </el-col>
          </el-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-expansion-panel>
        <!-- <github-corner style="position: absolute; top: 0px; border: 0; right: 0;" />-->
        <panel-group @handleSetLineChartData="handleSetLineChartData" />
        <v-expansion-panel-header class="panel-header-customization"><h3>Графики Сводки</h3></v-expansion-panel-header>
        <v-expansion-panel-content style="display: inline;position: relative">
          <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
            <line-chart :chart-data="lineChartData" />
          </el-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-expansion-panel>
        <v-expansion-panel-header class="panel-header-customization"><h3>Графики общей статистики</h3></v-expansion-panel-header>
        <v-expansion-panel-content style="display: inline;position: relative">
          <el-row :gutter="32">
            <el-col :xs="24" :sm="24" :lg="8">
              <div class="chart-wrapper">
                <h3>Эффектиность направлений</h3>
                <br>
                <raddar-chart />
              </div>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="8">
              <div class="chart-wrapper">
                <h3>Процентное соотнощение направлений</h3>
                <br>
                <pie-chart />
              </div>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="8">
              <div class="chart-wrapper">
                <h3>Эффектиность бригад или групп</h3>
                <br>
                <bar-chart />
              </div>
            </el-col>
          </el-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-expansion-panel>
        <v-expansion-panel-header class="panel-header-customization"><h3>Задания и личная статистика</h3></v-expansion-panel-header>
        <v-expansion-panel-content style="display: inline;position: relative">
          <el-row :gutter="8">
            <el-col :xs="{span: 24}" :sm="{span: 24}" :md="{span: 24}" :lg="{span: 12}" :xl="{span: 12}" style="padding-right:8px;margin-top:20px;margin-bottom:20px;">
              <transaction-table />
            </el-col>
            <el-col :xs="{span: 24}" :sm="{span: 12}" :md="{span: 12}" :lg="{span: 6}" :xl="{span: 6}" style="margin-top:20px;margin-bottom:30px;">
              <todo-list />
            </el-col>
            <el-col :xs="{span: 24}" :sm="{span: 12}" :md="{span: 12}" :lg="{span: 6}" :xl="{span: 6}" style="margin-top:20px;margin-bottom:30px;">
              <box-card />
            </el-col>
          </el-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </div>
</template>

<script>
// import GithubCorner from '@/components/GithubCorner';
import OrderStatisticsGroup from './components/OrderStatisticsGroup';
import PanelGroup from './components/PanelGroup';
import LineChart from './components/LineChart';
import RaddarChart from './components/RaddarChart';
import PieChart from './components/PieChart';
import BarChart from './components/BarChart';
import TransactionTable from './components/TransactionTable';
import OrderTable from './components/OrderTable';
import TodoList from './components/TodoList';
import BoxCard from './components/BoxCard';

const lineChartData = {
  newOrders: {
    expectedData: [20, 70, 161, 230, 130, 400, 310],
    actualData: [18, 82, 120, 154, 162, 320, 225],
  },
  newVisitis: {
    expectedData: [100, 120, 161, 134, 105, 160, 165],
    actualData: [120, 82, 91, 154, 162, 140, 145],
  },
  messages: {
    expectedData: [200, 192, 120, 144, 160, 130, 140],
    actualData: [180, 160, 151, 106, 145, 150, 130],
  },
  purchases: {
    expectedData: [80, 100, 121, 104, 105, 90, 100],
    actualData: [120, 90, 100, 138, 142, 130, 130],
  },
  shoppings: {
    expectedData: [130, 140, 141, 142, 145, 150, 160],
    actualData: [120, 82, 91, 154, 162, 140, 130],
  },
};

export default {
  name: 'DashboardAdmin',
  components: {
    // GithubCorner,
    OrderStatisticsGroup,
    OrderTable,
    PanelGroup,
    LineChart,
    RaddarChart,
    PieChart,
    BarChart,
    TransactionTable,
    TodoList,
    BoxCard,
  },
  data() {
    return {
      lineChartData: lineChartData.newVisitis,
    };
  },
  methods: {
    handleSetLineChartData(type) {
      this.lineChartData = lineChartData[type];
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 12px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 6px 12px 0;
    margin-bottom: 32px;
  }
}
.chart-wrapper {
  background: #fff;
  padding: 6px 12px 0;
  margin-bottom: 22px;
}
.panel-header-customization {
  border-top: 0.05em solid lightgrey;
  border-bottom: 2px solid cornflowerblue;
}
</style>
