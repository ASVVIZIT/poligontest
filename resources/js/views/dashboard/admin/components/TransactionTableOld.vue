<template>
  <div>
    <el-table
      v-loading="loading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%; padding-top: 0px;"
    >
      <el-table-column label="ID" min-width="70" width="70" align="center">
        <template slot-scope="scope">
          {{ scope.row && scope.row.index }}
        </template>
      </el-table-column>
      <el-table-column label="Заказ #" min-width="100" max-width="150" width="150" align="left">
        <template slot-scope="scope">
          {{ scope.row && scope.row.order_id }}
        </template>
      </el-table-column>
      <el-table-column label="Комментарий" min-width="100" width="150" align="left">
        <template slot-scope="scope">
          {{ scope.row && scope.row.note }}
        </template>
      </el-table-column>
      <el-table-column label="Цена" min-width="50" width="90" align="center">
        <template slot-scope="scope">
          {{ scope.row && scope.row.sum }}
        </template>
      </el-table-column>
      <el-table-column label="Статус" min-width="50" width="90" align="center">
        <template slot-scope="scope">
          <el-tag>
            {{ scope.row && scope.row.status }}
          </el-tag>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" align="center" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/PaginationOrderDashboard'; // Secondary package based on el-pagination
import OrderResource from '@/api/order';
const orderResource = new OrderResource();

export default {
  name: 'OrderListDash',
  components: { Pagination },
  filters: {
    statusFilter(status) {
      const statusMap = {
        success: 'success',
        pending: 'danger',
      };
      return statusMap[status];
    },
    orderNoFilter(str) {
      return str;
    },
  },
  data() {
    return {
      list: null,
      total: 0,
      query: {
        page: 1,
        limit: 8,
      },
      loading: true,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
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
