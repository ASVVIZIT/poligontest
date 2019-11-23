<template>
  <v-app id="inspire">
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
    <div class="text-center pt-2" style="margin-top: 5px;margin-bottom: 10px">
      <el-button type="success" size="mini" @click="toggleSelection([list[1], list[2]])">Выбрать 1 2 индексы заказов таблицы</el-button>
      <el-button type="danger" size="mini" @click="toggleSelection()">Очистить выбор</el-button>
      <el-button type="warning" size="mini" @click="resetDateFilter">Сбросить фильтр по дате</el-button>
      <el-button type="danger" size="mini" @click="clearFilter">Сбросить все фильтры</el-button>
      <el-button type="primary" size="mini" @click="toggleOrder">Toggle sort order</el-button>
      <el-button type="primary" size="mini" @click="nextSort">Sort next column</el-button>
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
      @expand-change="unaFunc"
      @selection-change="handleSelectionChange"
    >
      <el-table-column fixed label="" width="4">
        <template>
          <span hidden><a>...</a></span>
          <!-- <el-tag type="text" @click="toogleExpand(scope.row)"><a>...</a></el-tag>-->
        </template>
      </el-table-column>
      <el-table-column fixed type="expand" width="0" style="z-index: 1000">
        <template slot-scope="props">
          <el-form label-position="left" inline class="table-expand">
            <el-form-item label="Name of commodity">
              <div>
                {{ props.row.index }}
                Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text Text
              </div>
              <div>
                <span> <el-tag> Заголовок</el-tag> {{ props.row.title }} </span>
              </div>
              <div>
                <span> <el-tag> Комментарий</el-tag> {{ props.row.note }} </span>
              </div>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column fixed prop="ID" type="selection" :reserve-selection="true" width="45" />
      <el-table-column fixed prop="value" label="ID" :sortable="true" :sort-method="(a, b) => sortData(a, b, 'value')" :sort-orders="['descending']" min-width="70" width="70" align="center">
        <template slot-scope="scope">
          {{ scope.row && scope.row.index }}
        </template>
      </el-table-column>
      <el-table-column prop="title" label="Заголовок" column-key="title" :sortable="true" min-width="150" max-width="200" width="200" align="left">
        <template slot-scope="scope">
          <div>
            <div class="container__text__cell">
              <div class="center__text__cell">
                <el-tooltip placement="right-start" style="right: 5px;" effect="light">
                  <v-icon color="primary">mdi-tooltip-text</v-icon>
                  <div slot="content" style="min-width: 10px; max-width: 600px;">
                    <span> {{ scope.row && scope.row.title }} </span>
                  </div>
                </el-tooltip>
              </div>
              <div class="size">
                {{ scope.row && scope.row.title }}
              </div>
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Комментарий" column-key="note" :sortable="true" :min-width="120" :max-width="150" :width="150" align="left">
        <template slot-scope="scope">
          <div>
            <div class="container__text__cell">
              <div class="center__text__cell">
                <el-tooltip placement="right-start" style="right: 5px;" effect="light">
                  <v-icon color="primary">mdi-tooltip-text</v-icon>
                  <div slot="content" style="min-width: 10px; max-width: 600px;">
                    <span> {{ scope.row && scope.row.note }} </span>
                  </div>
                </el-tooltip>
              </div>
              <div class="size">
                <div class="size">
                  {{ scope.row && scope.row.note }}
                </div>
              </div>
            </div>
            <el-badge :value="'Статус ' + scope.row.status" type="primary" style="display: block; position: absolute; top: 7px; right: 30px; z-index: 2000">
              <div hidden>hidden</div>
            </el-badge>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="sum" label="Цена" column-key="sum" :sortable="true" min-width="100" max-width="120" width="120" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.sum !== 0">
            {{ scope.row && scope.row.sum }}<img style="height: 14px; width: 14px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAA0klEQVRIie2TLQ7CQBBGXwlHABJwoPBVcAc8Csk51hEsEskVehhAoQiB4DB1xUzDhkyWKXSD6Usms5l295tvf6DhBxxQBCIHjsAa6MUQ8OMGTLVFWkaxRIkOMAfOQBfIgMG3DkIMgYf8t3n/aHUQ4gTsZDyLIQBwkNyPJTCWfIkhMAIWMs6qTHSED9m/RQVwR9kiDevd9+MKTKydWxfNgT2wEjeVcdjeQZC2UltKTpVaydYqkCg1S8faPBXNQdldysuFueMqOGo4g7pe8v8EGj7yBMNdS24PVQbTAAAAAElFTkSuQmCC">
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
        max-width="130"
        width="130"
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
        fixed="left"
        align="center"
        prop="created_at"
        label="Дата создания"
        column-key="created_at"
        :sortable="true"
        :filters="[{text: '2018-12-29', value: '2018-12-29T04:12:20.000000Z'}, {text: '2018-12-29', value: '2018-12-29T02:12:18.000000Z'}, {text: '2017-10-28', value: '2017-10-28T06:10:49.000000Z'}]"
        :filter-method="filterHandler"
        width="170"
      >
        <template slot-scope="scope">
          <div>
            <i class="el-icon-date" />
            <span>{{ scope.row.created_at | parseTime('{d}/{m}/{y}') }}</span>
          </div>
          <div>
            <i class="el-icon-time" />
            <span>{{ scope.row.created_at | parseTime('{h}:{i}:{s}') }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="updated_at"
        column-key="updated_at"
        :sortable="true"
        label="Дата обновления"
        width="180"
      >
        <template slot-scope="scope">
          <div>
            <i v-if="scope.row.updated_at !== ''" class="el-icon-date" />
            <span v-if="scope.row.updated_at !== ''">
              {{ scope.row.updated_at | parseTime('{d}/{m}/{y}') }}
            </span>
            <span v-else>
              нет года обновления
            </span>
          </div>
          <div>
            <i v-if="scope.row.updated_at !== ''" class="el-icon-time" />
            <span v-if="scope.row.updated_at !== ''">
              {{ scope.row.updated_at | parseTime('{h}:{i}:{s}') }}
            </span>
            <span v-else>
              нет времени обновления
            </span>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        align="center"
        prop="deleted_at"
        column-key="deleted_at"
        :sortable="true"
        label="Дата удаления"
        width="200"
      >
        <template slot-scope="scope">
          <div>
            <i v-if="scope.row.deleted_at !== ''" class="el-icon-date" />
            <span v-if="scope.row.deleted_at !== ''">
              {{ scope.row.deleted_at | parseTime('{d}/{m}/{y}') }}
            </span>
            <span v-else>
              нет года обновления
            </span>
          </div>
          <div>
            <i v-if="scope.row.deleted_at !== ''" class="el-icon-time" />
            <span v-if="scope.row.deleted_at !== ''">
              {{ scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
            </span>
            <span v-else>
              нет времени обновления
            </span>
          </div>
        </template>
      </el-table-column>
      <el-table-column fixed="right" align="center" label="Действия" width="230" style="border-left: 1px solid lightgrey">
        <template slot-scope="scope">
          <div style="border-left: 1px solid lightgrey">
            <router-link :to="'/orders/order/edit/'+scope.row.id">
              <el-button type="primary" size="small" icon="el-icon-edit">Изменить</el-button>
            </router-link>
            <el-button
              type="danger"
              size="small"
              icon="el-icon-delete"
              @click.native.prevent="DeleteRow(scope.$index, list)"
            />
          </div>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" align="center" @pagination="getListOrder" />
  </v-app>
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
  computed: {
    tooltipText: function() {
      // put your logic here to change the tooltip text
      return this.title;
    },
  },
  created() {
    this.getListOrder();
    this.resetNewOrder();
  },
  methods: {
    toogleExpand(row) {
      const table = this.$refs.multipleTableOrder;
      table.toggleRowExpansion(row);
    },
    unaFunc: function(row, expanded) {
      if (document.getElementsByClassName('el-table__expand-icon').length > 0) {
        if (expanded) {
          document.getElementsByClassName('el-table__expand-icon el-table__expand-icon--expanded')[0].click();
        } else {
          document.getElementsByClassName('el-table__expand-icon el-table__expand-icon--expanded')[0].click();
        }
      }
    },
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
        title: '',
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
      let index = this.headers.findcreatedIndex(h => h.value === this.sortBy);
      index = (index + 1) % this.headers.length;
      this.sortBy = this.headers[index].value;
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
          'title',
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
          'title',
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
.size,
.container__text__cell div .size{
  white-space: nowrap; /* Отменяем перенос текста */
  overflow: hidden; /* Обрезаем содержимое */
  padding: 1px; /* Поля */
  text-overflow: ellipsis; /* Многоточие */
}
.size::after,
.container__text__cell div .size::after
{
  content: ''; /* Выводим элемент */
  position: absolute; /* Абсолютное позиционирование */
  right: 0; top: 0; /* Положение элемента */
  width: 40px; /* Ширина градиента*/
  height: 100%; /* Высота родителя */
}
.container__text__cell {
  display: flex;
}
.container__text__cell div {
  flex-grow: 1;
  width: 100%;
}
.container__text__cell .center__text__cell {
  flex-grow: 0;
  width: 40px;
}
</style>
