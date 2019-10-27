<template>
  <el-table :data="list" border fit highlight-current-row style="width: 100%">
    <el-table-column
      v-loading="loading"
      align="center"
      label="ID"
      width="65"
      element-loading-text="Pleas be patient！"
    >
      <template slot-scope="scope">
        <span>{{ scope.row.id }}</span>
      </template>
    </el-table-column>

    <el-table-column width="180px" align="center" label="Дата">
      <template slot-scope="scope">
        <span>{{ scope.row.timestamp | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
      </template>
    </el-table-column>

    <el-table-column min-width="130px" label="Заголовок">
      <template slot-scope="scope">
        <span>{{ scope.row.title }}</span>
        <el-tag>{{ scope.row.type }}</el-tag>
      </template>
    </el-table-column>
    <el-table-column width="90px" label="Страна">
      <template slot-scope="scope">
        <el-tag>{{ scope.row.type }}</el-tag>
      </template>
    </el-table-column>
    <el-table-column width="110px" align="center" label="Автор">
      <template slot-scope="scope">
        <span>{{ scope.row.author }}</span>
      </template>
    </el-table-column>

    <el-table-column width="120px" label="Важно">
      <template slot-scope="scope">
        <svg-icon v-for="n in +scope.row.importance" :key="n" icon-class="star" />
      </template>
    </el-table-column>

    <el-table-column align="center" label="Показания" width="120">
      <template slot-scope="scope">
        <span>{{ scope.row.pageviews }}</span>
      </template>
    </el-table-column>

    <el-table-column class-name="status-col" label="Статус" width="110">
      <template slot-scope="scope">
        <el-tag :type="scope.row.status | statusFilter">
          {{ scope.row.status }}
        </el-tag>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import { fetchList } from '@/api/article';

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    type: {
      type: String,
      default: 'RU',
    },
  },
  data() {
    return {
      list: null,
      listQuery: {
        page: 1,
        limit: 10,
        type: this.type,
        sort: '+id',
      },
      loading: false,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      this.$emit('create'); // for test
      const { data } = await fetchList(this.listQuery);
      this.list = data.items;
      this.loading = false;
    },
  },
};
</script>

