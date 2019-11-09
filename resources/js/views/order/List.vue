<template>
  <div class="app-container">
    <v-app>
      <v-card>
        <v-data-table
          dense
          :headers="expand.TableHeader"
          :items="list"
          :single-expand="expand.singleExpand"
          :single-select="expand.singleSelect"
          :expanded.sync="expand.expanded"
          :sort-by="[ 'id', 'created_at', 'user_id' ]"
          :sort-desc="[true, false]"
          item-key="id"
          :group-by="[ 'user_id' ]"
          :group-desc="[false, true]"
          show-group-by
          multi-sort
          show-expand
          show-select
          class="elevation-1"
          :footer-props="{
            showFirstLastPage: true,
            firstIcon: 'mdi-arrow-collapse-left',
            lastIcon: 'mdi-arrow-collapse-right',
            prevIcon: 'mdi-minus',
            nextIcon: 'mdi-plus'
          }"
          :search="expand.search"
          :custom-filter="filterSearch"
        >
          {{ list.order_id }}
          <template v-slot:top>
            <v-toolbar flat color="withe">
              <v-toolbar-title>Список заказов</v-toolbar-title>
              <v-spacer />
              <v-text-field
                v-model="expand.search"
                label="Поиск по заказам"
                append-icon="search"
                hide-details
              />
              <router-link :to="'/orders/order/create'">
                <el-button type="primary" size="small" icon="el-icon-edit">
                  Создать заказ
                </el-button>
              </router-link>
              <v-spacer />
              <v-switch v-model="expand.singleExpand" label="Одиночный режим" />
            </v-toolbar>
          </template>
          <template v-slot:item.note="props">
            <v-edit-dialog
              style="width: 450px;"
              :return-value.sync="props.item.note"
              large
              persistent
              cancel-text="Отмена"
              save-text="Сохранить"
              @save="save"
              @cancel="cancel"
              @open="open"
              @close="close"
            >
              <div>
                <v-icon small>edit</v-icon>
                {{ props.item.note }}
              </div>
              <template v-slot:input>
                <br>
                <v-text-field
                  slot="input"
                  v-model="props.item.note"
                  :loading="loading"
                  :rules="expand.max100chars(props.item.note)"
                  dense
                  :label="expand.label"
                  :hint="expand.hint"
                  :outlined="expand.outlined"
                  :placeholder="expand.placeholder"
                  :persistent-hint="expand.persistentHint"
                  :counter="expand.counterEn ? expand.counter : false"
                  autofocus
                  @value="props.item.note"
                  @change="val => props.item.note = val"
                />
              </template>
            </v-edit-dialog>
          </template>
          <template v-slot:expanded-item="{ headers }">
            <td :colspan="headers.length">
              <div style="padding: 20px; align-content: center">
                <v-toolbar flat color="withe">
                  <v-toolbar-title>
                    Список отчётов в заказе
                  </v-toolbar-title>
                </v-toolbar>
                <div>
                  <div class="filter-container">
                    <el-input v-model="queryReport.keyword" :placeholder="$t('table.keyword')" style="width: 250px;" class="filter-item" @keyup.enter.native="handleFilterReport" />
                    <el-select v-model="queryReport.status" :placeholder="$t('table.status')" clearable style="width: 110px" class="filter-item" @change="handleFilterReport">
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
                    <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilterReport">
                      {{ $t('table.search') }}
                    </el-button>
                    <router-link :to="'/reports/report/create'">
                      <el-button type="primary" class="el-button filter-item el-button--primary el-button--medium" size="medium" icon="el-icon-edit">
                        Добавить отчёт
                      </el-button>
                    </router-link>
                    <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
                      {{ $t('table.add') }}
                    </el-button>
                    <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
                      {{ $t('table.export') }}
                    </el-button>
                  </div>
                  <pagination v-show="total>0" :total="total" :page.sync="queryReport.page" :limit.sync="queryReport.limit" align="left" style="margin-left: 20px" @pagination="getListReport" />

                  <el-table v-loading="loadingReport" :data="listReport" :row-class-name="tableRowClassName" border stripe highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="120">
                      <template slot-scope="scope">
                        <span>{{ scope.row.index }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="user_id" width="70">
                      <template slot-scope="scope">
                        <span>{{ scope.row.user_id }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="order_id" width="150">
                      <template slot-scope="scope">
                        <span>{{ scope.row.order_id }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column :label="$t('table.status')" width="110px">
                      <template slot-scope="scope">
                        <span>
                          {{ scope.row.status }}
                          <svg-icon v-for="n in +scope.row.status" :key="n" icon-class="star" class="meta-item__icon" />
                        </span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="title" width="280">
                      <template slot-scope="scope">
                        <span>{{ scope.row.title }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="price" width="80">
                      <template slot-scope="scope">
                        <span>{{ scope.row.price }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="Actions" width="220">
                      <template slot-scope="scope">
                        <router-link :to="'/reports/report/edit/'+scope.row.id">
                          <el-button type="primary" size="small" icon="el-icon-edit">
                            Изменить
                          </el-button>
                        </router-link>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="created_at_year" width="130">
                      <template slot-scope="scope">
                        <span>{{ scope.row.created_at | parseTime('{d}/{m}/{y}') }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="created_at_time" width="130">
                      <template slot-scope="scope">
                        <span>{{ scope.row.created_at | parseTime('{h}:{i}:{s}') }}</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="updated_at_year" width="130">
                      <template slot-scope="scope">
                        <span v-if="scope.row.updated_at !== ''">
                          {{ scope.row.updated_at | parseTime('{d}/{m}/{y}') }}
                        </span>
                        <span v-else>
                          нет года обновления
                        </span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="updated_at_time" width="160">
                      <template slot-scope="scope">
                        <span v-if="scope.row.updated_at !== ''">
                          {{ scope.row.updated_at | parseTime('{h}:{i}:{s}') }}
                        </span>
                        <span v-else>
                          нет времени обновления
                        </span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="deleted_at_year" width="160">
                      <template slot-scope="scope">
                        <span v-if="scope.row.deleted_at !== ''">
                          {{ scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
                        </span>
                        <span v-else>
                          нет года удаления
                        </span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="deleted_at_time" width="140">
                      <template slot-scope="scope">
                        <span v-if="scope.row.deleted_at !== ''">
                          {{ scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
                        </span>
                        <span v-else>
                          нет времени удаления
                        </span>
                      </template>
                    </el-table-column>
                  </el-table>
                  <pagination v-show="total>0" :total="total" :page.sync="queryReport.page" :limit.sync="queryReport.limit" align="left" style="margin-left: 20px" @pagination="getListReport" />
                </div>
              </div>
            </td>
          </template>
        </v-data-table>
        <v-snackbar v-model="expand.snack" :timeout="3000" :color="expand.snackColor">
          {{ expand.snackText }}
          <v-btn text @click="expand.snack = false">Закрыть</v-btn>
        </v-snackbar>
        <el-dialog :visible.sync="dialogPermissionVisible" :title="'Edit Permissions - ' + currentOrder.name">
          <div v-if="currentOrder.name" v-loading="dialogPermissionLoading" class="form-container">
            <div class="permissions-container">
              <div class="block">
                <el-form :model="currentOrder" label-width="80px" label-position="top">
                  <el-form-item label="Menus">
                    <el-tree ref="menuPermissions" :data="normalizedMenuPermissions" :default-checked-keys="permissionKeys(orderMenuPermissions)" :props="permissionProps" show-checkbox node-key="id" class="permission-tree" />
                  </el-form-item>
                </el-form>
              </div>
              <div class="block">
                <el-form :model="currentOrder" label-width="80px" label-position="top">
                  <el-form-item label="Permissions">
                    <el-tree ref="otherPermissions" :data="normalizedOtherPermissions" :default-checked-keys="permissionKeys(orderOtherPermissions)" :props="permissionProps" show-checkbox node-key="id" class="permission-tree" />
                  </el-form-item>
                </el-form>
              </div>
              <div class="clear-left" />
            </div>
            <div style="text-align:right;">
              <el-button type="danger" @click="dialogPermissionVisible=false">
                {{ $t('permission.cancel') }}
              </el-button>
              <el-button type="primary" @click="confirmPermission">
                {{ $t('permission.confirm') }}
              </el-button>
            </div>
          </div>
        </el-dialog>
        <el-dialog :title="'Create new order'" :visible.sync="dialogFormVisible">
          <div v-loading="orderCreating" class="form-container">
            <el-form ref="OrderForm" :rules="rules" :model="newOrder" label-position="left" label-width="150px" style="max-width: 500px;">
              <el-form-item :label="$t('order.id')" prop="id">
                <el-input v-model="newOrder.id" />
              </el-form-item>
              <el-form-item :label="$t('order.report_id')" prop="report_id">
                <el-input v-model="newOrder.email" />
              </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
              <el-button @click="dialogFormVisible = false">
                {{ $t('table.cancel') }}
              </el-button>
              <el-button type="primary" @click="createOrder()">
                {{ $t('table.confirm') }}
              </el-button>
            </div>
          </div>
        </el-dialog>
      </v-card>
    </v-app>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination

import Resource from '@/api/resource';
import OrderResource from '@/api/order';
import ReportResource from '@/api/report';

import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import checkPermission from '@/utils/permission'; // Permission checking

const orderResource = new OrderResource();
const reportResource = new ReportResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'OrderList',
  components: { Pagination },
  directives: { waves, permission },
  data() {
    return {
      expand: {
        expanded: [],
        singleExpand: true,
        singleSelect: false,
        accordion: false,
        popout: false,
        inset: false,
        multiple: true,
        disabled: false,
        readonly: false,
        focusable: false,
        placeholder: '',
        outlined: true,
        counterEn: true,
        counter: 100,
        label: 'Введите комментарий к заказу',
        hint: 'Не более 100 символов',
        persistentHint: false,
        snack: false,
        snackColor: '',
        snackText: '',
        max100chars: v => v.length <= 100 || 'Слишком длинное значение!',
        search: '',
        TableHeader: [
          {
            text: 'Отчёты...',
            value: 'data-table-expand',
            filterable: false,
            sortable: false,
          },
          {
            isEditorBottom: 'true',
            text: 'Действия',
            value: 'data-btn',
            filterable: false,
            sortable: false,
          },
          {
            text: 'ID',
            align: 'left',
            sortable: true,
            value: 'id',
          },
          { text: 'ID Пользователя (Создатель заказа)', value: 'user_id' },
          { text: 'ID Отчёта', value: 'order_id' },
          { text: 'ID Исполниеля', value: 'orderer_id' },
          { text: 'ID Всех отчётов в заказе', value: 'report_id' },
          { text: 'ID Заказчика', value: 'executor_id' },
          { text: 'Статус', value: 'status' },
          { text: 'Дата создания', value: 'created_at' },
          { text: 'Дата изменения', value: 'updated_at' },
          { text: 'Дата удаления', value: 'deleted_at' },
          { text: 'Комментарий', value: 'note' },
          { text: 'Цена заказа', value: 'sum' },
        ],
        items: [
          {
            id: 1,
            user_id: 15,
            report_id: 7378678524234,
            orderer_id: 734234224234,
            executor_id: 23246234,
            status: 3,
            order_id: 15,
            created_at: '2017-02-21 04:02:46',
            updated_at: '',
            deleted_at: '',
            note: 'Пояснение к заказу 1',
          },
          {
            id: 2,
            user_id: 17,
            report_id: 87567524234,
            orderer_id: 345347524234,
            executor_id: 7878247834,
            status: 4,
            order_id: 34,
            created_at: '2018-02-18 11:02:31',
            updated_at: '',
            deleted_at: '',
            note: 'Пояснение к заказу 2',
          },
          {
            id: 3,
            user_id: 5,
            report_id: 867867524234,
            orderer_id: 3453678524234,
            executor_id: 787827834,
            status: 5,
            order_id: 15,
            created_at: '2018-08-04 09:08:11',
            updated_at: '',
            deleted_at: '',
            note: 'Пояснение к заказу 3',
          },
          {
            id: 4,
            user_id: 9,
            report_id: 87457874234,
            orderer_id: 345367524234,
            executor_id: 764524234,
            status: 5,
            order_id: 334,
            created_at: '2011-03-04 02:03:53',
            updated_at: '2011-04-14 01:03:53',
            deleted_at: '',
            note: 'Пояснение к заказу 4',
          },
          {
            id: 5,
            user_id: 12,
            report_id: 867868645678234,
            orderer_id: 3453467824234,
            executor_id: 7876782454,
            status: 5,
            order_id: 354,
            created_at: '2015-03-17 11:03:05\n',
            updated_at: '',
            deleted_at: '',
            note: 'Пояснение к заказу 5',
          },
        ],
      },
      list: null,
      listReport: null,
      total: 1,
      loading: false,
      loadingReport: false,
      downloading: false,
      orderCreating: false,
      query: {
        page: 1,
        limit: 50000,
        keyword: '',
        role: '',
        status: '',
      },
      queryReport: {
        page: 1,
        limit: 3,
        keyword: '',
        status: '',
      },
      roles: ['admin', 'manager', 'editor', 'user', 'visitor'],
      status: ['1', '2', '3', '4', '5'],
      nonAdminRoles: ['editor', 'user', 'visitor'],
      newOrder: {},
      dialogFormVisible: false,
      dialogPermissionVisible: false,
      dialogPermissionLoading: false,
      currentOrderId: 0,
      currentOrder: {
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
      },
      rules: {
        role: [{ required: true, message: 'Role is required', trigger: 'change' }],
        name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
      },
      permissionProps: {
        children: 'children',
        label: 'name',
        disabled: 'disabled',
      },
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
    };
  },
  computed: {
    normalizedMenuPermissions() {
      let tmp = [];
      this.currentOrder.permissions.role.forEach(permission => {
        tmp.push({
          id: permission.id,
          name: permission.name,
          disabled: true,
        });
      });
      const rolePermissions = {
        id: -1, // Just a faked ID
        name: 'Inherited from role',
        disabled: true,
        children: this.classifyPermissions(tmp).menu,
      };

      tmp = this.menuPermissions.filter(permission => !this.currentOrder.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0, // Faked ID
        name: 'Extra menus',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    normalizedOtherPermissions() {
      let tmp = [];
      this.currentOrder.permissions.role.forEach(permission => {
        tmp.push({
          id: permission.id,
          name: permission.name,
          disabled: true,
        });
      });
      const rolePermissions = {
        id: -1,
        name: 'Inherited from role',
        disabled: true,
        children: this.classifyPermissions(tmp).other,
      };

      tmp = this.otherPermissions.filter(permission => !this.currentOrder.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0,
        name: 'Extra permissions',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    orderMenuPermissions() {
      return this.classifyPermissions(this.orderPermissions).menu;
    },
    orderOtherPermissions() {
      return this.classifyPermissions(this.orderPermissions).other;
    },
    orderPermissions() {
      return this.currentOrder.permissions.role.concat(this.currentOrder.permissions.user);
    },
  },
  created() {
    this.resetNewOrder();
    this.getListOrder();
    if (checkPermission(['manage permission'])) {
      this.getPermissions();
    }
  },
  methods: {
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex === 1) {
        return 'warning-row';
      } else if (rowIndex === 3) {
        return 'success-row';
      }
      return '';
    },
    filterSearch(value, search, item) {
      return value != null &&
        search != null &&
        typeof value === 'string' &&
        value.toString().toLocaleLowerCase().indexOf(search) !== -1;
    },
    checkPermission,
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
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
    async getListReport() {
      const { limit, page } = this.queryReport;
      this.loadingReport = true;
      const { data, meta } = await reportResource.list(this.queryReport);
      this.listReport = data;
      this.listReport.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loadingReport = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getListOrder();
    },
    handleFilterReport() {
      this.queryReport.page = 1;
      this.getListReport();
    },
    handleCreate() {
      this.resetNewOrder();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['orderForm'].clearValidate();
      });
    },
    handleDelete(id, report_id) {
      this.$confirm('Это навсегда удалит заказ ' + id + ' ' + report_id + ' . Продолжить?', 'Внимание!!!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        orderResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Удаление прошло успешно',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Удаление отменено',
        });
      });
    },
    async handleEditPermissions(id) {
      this.currentOrderId = id;
      this.dialogPermissionLoading = true;
      this.dialogPermissionVisible = true;
      const found = this.list.find(user => user.id === id);
      const { data } = await orderResource.permissions(id);
      this.currentOrder = {
        id: found.id,
        name: found.name,
        permissions: data,
      };
      this.dialogPermissionLoading = false;
      this.$nextTick(() => {
        this.$refs.menuPermissions.setCheckedKeys(this.permissionKeys(this.orderMenuPermissions));
        this.$refs.otherPermissions.setCheckedKeys(this.permissionKeys(this.orderOtherPermissions));
      });
    },
    createOrder() {
      this.$refs['orderForm'].validate((valid) => {
        if (valid) {
          this.orderCreating = true;
          orderResource
            .store(this.newOrder)
            .then(response => {
              this.$message({
                message: 'Новый заказ ' + this.newOrder.id + ' (' + this.newOrder.order_id + ') был успешно создан.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewOrder();
              this.dialogFormVisible = false;
              this.handleFilter();
            })
            .catch(error => {
              this.$message({
                message: 'Ошибка - Новый заказ ' + this.newOrder.id + ' (' + this.newOrder.order_id + ') не создан.',
                type: 'error',
                duration: 5 * 1000,
              });
              console.log(error);
            })
            .finally(() => {
              this.orderCreating = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
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
        note: '',
        sum: '',
      };
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
          filename: 'order-id' + this.orderer_id + '-list',
        });
        this.downloading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    permissionKeys(permissions) {
      return permissions.map(permssion => permssion.id);
    },
    classifyPermissions(permissions) {
      const all = []; const menu = []; const other = [];
      permissions.forEach(permission => {
        const permissionName = permission.name;
        all.push(permission);
        if (permissionName.startsWith('view menu')) {
          menu.push(this.normalizeMenuPermission(permission));
        } else {
          other.push(this.normalizePermission(permission));
        }
      });
      return { all, menu, other };
    },

    normalizeMenuPermission(permission) {
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name.substring(10)), disabled: permission.disabled || false };
    },

    normalizePermission(permission) {
      const disabled = permission.disabled || permission.name === 'manage permission';
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name), disabled: disabled };
    },

    confirmPermission() {
      const checkedMenu = this.$refs.menuPermissions.getCheckedKeys();
      const checkedOther = this.$refs.otherPermissions.getCheckedKeys();
      const checkedPermissions = checkedMenu.concat(checkedOther);
      this.dialogPermissionLoading = true;

      orderResource.updatePermission(this.currentOrderId, { permissions: checkedPermissions }).then(response => {
        this.$message({
          message: 'Разрешения были успешно обновлены',
          type: 'success',
          duration: 5 * 1000,
        });
        this.dialogPermissionLoading = false;
        this.dialogPermissionVisible = false;
      });
    },
    save() {
      this.expand.snack = true;
      this.expand.snackColor = 'success';
      this.expand.snackText = 'Введенны данные подготовлены для сохранения';
    },
    cancel() {
      this.expand.snack = true;
      this.expand.snackColor = 'error';
      this.expand.snackText = 'Ввод отмененн';
    },
    open() {
      this.expand.snack = true;
      this.expand.snackColor = 'info';
      this.expand.snackText = 'Диалог ввода открыт';
    },
    close() {
      console.log('Диалог ввода закрыт');
    },
  },
};
</script>

<style lang="scss" scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
.dialog-footer {
  text-align: left;
  padding-top: 0;
  margin-left: 150px;
}
.app-container {
  flex: 1;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
  .block {
    float: left;
    min-width: 220px;
  }
  .clear-left {
    clear: left;
  }
}
.el-table .warning-row {
  background: oldlace;
}
.el-table .success-row {
  background: #f0f9eb;
}
</style>
