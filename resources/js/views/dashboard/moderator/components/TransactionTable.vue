<template>
  <div>
    <div class="filter-container" style="width: 100%" color="white">
      <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" clearable style="width: 250px;" class="filter-item" @keyup.enter.native="handleFilterOrder" />
      <el-select v-model="query.status" :placeholder="$t('table.status')" clearable style="width: 110px" class="filter-item" @change="handleFilterOrder">
        <el-option v-for="item in status" :key="item" :label="item | uppercaseFirst" :value="item">
          <span>
            <div v-if="item === '1'">
              <svg-icon v-for="n in +item" :key="n" icon-class="star" class="meta-item__icon" />
            </div>
            <div v-else-if="item === '2'">
              <svg-icon v-for="n in +item" :key="n" icon-class="star" class="meta-item__icon" />
            </div>
            <div v-else-if="item === '3'">
              <svg-icon v-for="n in +item" :key="n" icon-class="star" class="meta-item__icon" />
            </div>
            <div v-else-if="item === '4'">
              <svg-icon v-for="n in +item" :key="n" icon-class="star" class="meta-item__icon" />
            </div>
            <div v-else-if="item === '5'">
              <svg-icon v-for="n in +item" :key="n" icon-class="star" class="meta-item__icon" />
            </div>
          </span>
        </el-option>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" size="medium" @click="handleFilterOrder">
        {{ $t('table.search') }}
      </el-button>
      <router-link :to="'/orders/order/create'">
        <el-button type="primary" class="el-button filter-item el-button--primary el-button--medium" size="medium" icon="el-icon-edit">
          Добавить заказ
        </el-button>
      </router-link>
      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" size="medium" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
    </div>
    <el-table
      ref="multipleTableOrder"
      v-loading="loading"
      :data="list"
      :row-class-name="tableRowClassName"
      :default-sort="{prop: 'value', order: 'descending'}"
      border
      stripe
      highlight-current-row
      :sortable="true"
      :sort-by.sync="sortBy"
      :sort-desc.sync="descending"
      style="width: 100%; padding-top: 0;"
      :row-key="getRowKeys"
      @selection-change="handleSelectionChange"
    >
      <el-table-column fixed prop="ID" type="selection" :reserve-selection="true" width="40" />
      <el-table-column fixed prop="value" label="ID" :sortable="true" :sort-method="(a, b) => sortData(a, b, 'value')" :sort-orders="['descending']" min-width="70" width="70" align="center">
        <template slot-scope="scope">
          {{ scope.row && scope.row.index }}
        </template>
      </el-table-column>
      <el-table-column prop="order_id" label="Заказ id#" column-key="order_id" :sortable="true" min-width="100" max-width="150" width="150" align="left">
        <template slot-scope="scope">
          {{ scope.row && scope.row.order_id }}
        </template>
      </el-table-column>
      <el-table-column label="Комментарий" :min-width="200" :max-width="250" :width="250" align="left">
        <template slot-scope="scope">
          <div>
            {{ scope.row && scope.row.note }}
          </div>
          <el-badge :value="'Статус ' + scope.row.status" type="primary" />
        </template>
      </el-table-column>
      <el-table-column prop="sum" label="Цена" column-key="sum" :sortable="true" min-width="100" max-width="120" width="120" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.sum !== 0">
            {{ scope.row && scope.row.sum }}<img style="height: 16px; width: 16px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAA0klEQVRIie2TLQ7CQBBGXwlHABJwoPBVcAc8Csk51hEsEskVehhAoQiB4DB1xUzDhkyWKXSD6Usms5l295tvf6DhBxxQBCIHjsAa6MUQ8OMGTLVFWkaxRIkOMAfOQBfIgMG3DkIMgYf8t3n/aHUQ4gTsZDyLIQBwkNyPJTCWfIkhMAIWMs6qTHSED9m/RQVwR9kiDevd9+MKTKydWxfNgT2wEjeVcdjeQZC2UltKTpVaydYqkCg1S8faPBXNQdldysuFueMqOGo4g7pe8v8EGj7yBMNdS24PVQbTAAAAAElFTkSuQmCC">
          </span>
          <span v-else>
            Бесплатно
          </span>
        </template>
      </el-table-column>
      <el-table-column
        align="left"
        prop="status"
        label="Статус"
        column-key="status"
        :sortable="true"
        :filters="[{text: 'Статус 1', value: '1'}, {text: 'Статус 2', value: '2'}, {text: 'Статус 3', value: '3'}, {text: 'Статус 4', value: '4'}, {text: 'Статус 5', value: '5'}]"
        :filter-method="filterHandler"
        min-width="50"
        max-width="160"
        width="160"
        style="padding-left: 10px"
      >
        <template slot-scope="scope">
          <el-tag>
            {{ scope.row && scope.row.status }}
          </el-tag>
          <svg-icon v-for="n in +scope.row.status" :key="n" icon-class="star" class="meta-item__icon" />
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="created_at"
        label="Дата создания"
        column-key="created_at"
        :sortable="true"
        width="160"
      >
        <template slot-scope="scope">
          <i class="el-icon-date" />
          <span>{{ scope.row.created_at | parseTime('{d}/{m}/{y}') }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Время создания" width="160">
        <template slot-scope="scope">
          <i class="el-icon-time" />
          <span>{{ scope.row.created_at | parseTime('{h}:{i}:{s}') }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Год обновления" width="190">
        <template slot-scope="scope">
          <i v-if="scope.row.updated_at !== ''" class="el-icon-date" />
          <span v-if="scope.row.updated_at !== ''">
            {{ scope.row.updated_at | parseTime('{d}/{m}/{y}') }}
          </span>
          <span v-else>
            нет года обновления
          </span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Время обновления" width="190">
        <template slot-scope="scope">
          <span v-if="scope.row.updated_at !== ''">
            {{ scope.row.updated_at | parseTime('{h}:{i}:{s}') }}
          </span>
          <span v-else>
            нет времени обновления
          </span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Год удаления" width="160">
        <template slot-scope="scope">
          <span v-if="scope.row.deleted_at !== ''">
            {{ scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
          </span>
          <span v-else>
            нет года удаления
          </span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Время удаления" width="180">
        <template slot-scope="scope">
          <span v-if="scope.row.deleted_at !== ''">
            {{ scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
          </span>
          <span v-else>
            нет времени удаления
          </span>
        </template>
      </el-table-column>
      <el-table-column fixed="right" align="center" label="Действия" width="120">
        <template slot-scope="scope">
          <router-link :to="'/orders/order/edit/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit" />
          </router-link>
          <el-button
            type="danger"
            size="small"
            icon="el-icon-delete"
            @click.native.prevent="DeleteRow(scope.$index, list)"
          />
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" align="center" @pagination="getListOrder" />
  </div>
</template>

<script>
import Pagination from '@/components/PaginationOrderDashboard'; // Secondary package based on el-pagination

import waves from '@/directive/waves'; // Waves directive

import OrderResource from '@/api/order';
const orderResource = new OrderResource();

export default {
  name: 'OrderListDash',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      if (status !== '' && status !== null) {
        const statusMap = {
          '1': 'pending approval',
          '2': 'In Sales',
          '3': 'has refused approval',
          '4': 'No sale',
        };
        return statusMap[status];
      }
      return '—';
    },
  },
  data() {
    return {
      list: null,
      listdata: null,
      filterData: [],
      total: 1,
      search: '',
      sortBy: 'id',
      descending: false,
      query: {
        page: 1,
        limit: 8,
        keyword: '',
        status: '',
      },
      newOrder: {},
      loading: true,
      downloading: false,
      getRowKeys(row) {
        return row.id;
      },
      selectedData: [],
      status: ['1', '2', '3', '4', '5'],
    };
  },
  created() {
    this.getListOrder();
    this.resetNewOrder();
  },
  methods: {
    resetNewOrder() {
      this.newOrder = {
        id: '',
        user_id: '',
        order_id: '',
        report_id: '',
        orderer_id: '',
        executor_id: '',
        status: '',
        created_at: '',
        updated_at: '',
        deleted_at: '',
        note: '',
        sum: '',
      };
    },
    created() {
      this.getListOrder();
      this.resetNewOrder();
    },
    DeleteRow(index, rows) {
      rows.splice(index, 1);
      this.getListOrder();
    },
    handleFilterOrder() {
      this.query.page = 1;
      this.getListOrder();
    },
    sortData(a, b, key) {
      return a.value > b.value ? 1 : a.value < b.value ? -1 : 0;
    },
    toggleOrder() {
      this.descending = !this.descending;
    },
    nextSort() {
      let index = this.list.findIndex(h => h.value === this.sortBy);
      index = (index + 1) % this.list.length;
      this.sortBy = this.list[index].value;
      console.log(this.descending);
    },
    resetDateFilter() {
      this.$refs.multipleTableOrder.clearFilter('updated_at');
    },
    clearFilter() {
      this.$refs.multipleTableOrder.clearFilter();
    },
    filterHandler(value, row, column) {
      const property = column['property'];
      return row[property] === value;
    },
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTableOrder.toggleRowSelection(row);
        });
      } else {
        this.$refs.multipleTableOrder.clearSelection();
      }
    },
    handleSelectionChange(rows) {
      this.selectedData = [];
      if (rows) {
        rows.forEach(row => {
          if (row) {
            this.selectedData.push(row.id);
          }
        });
      }
      console.log(this.selectedData);
    },
    tableRowClassName({ row }) {
      if (row.status === '5') {
        return 'success-row';
      } else if (row.status === '2') {
        return 'finish-row';
      } else if (row.status === '1') {
        return 'warning-row';
      }
      return '';
    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = [
          'id',
          'user_id',
          'order_id',
          'report_id',
          'orderer_id',
          'executor_id',
          'status',
          'created_at',
          'updated_at',
          'deleted_at',
          'note',
          'sum',
        ];
        const filterVal = [
          'index',
          'user_id',
          'order_id',
          'report_id',
          'orderer_id',
          'executor_id',
          'status',
          'created_at',
          'updated_at',
          'deleted_at',
          'note',
          'sum',
        ];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'order-id' + this.list.orderer_id + '-list',
        });
        this.downloading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    formatJsonFilter(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    async getListOrder() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await orderResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
.el-table .call {
  box-sizing: border-box !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
  white-space: normal !important;
  word-break: keep-all !important;
  line-height: 20px !important;
  padding-left: 10px !important;
  padding-right: 10px !important;
}
.el-badge {
  top: 5px;
}
</style>
