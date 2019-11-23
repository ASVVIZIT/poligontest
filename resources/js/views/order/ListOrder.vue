<template>
  <div class="app-container">
    <v-app id="inspire">
      <v-card
        v-loading="loading"
        min-width="300"
        max-width="100%"
      >
        {{ list.silent }}
        <v-data-table
          v-loading="loading"
          color="primary"
          light
          dense
          fixed-header
          height="670"
          :headers="expand.TableHeader"
          :items="list"
          :single-expand="expand.singleExpand"
          :single-select="expand.singleSelect"
          :expanded.sync="expand.expanded"
          :sort-by="[ 'id' ]"
          :sort-desc="[true, false]"
          item-key="id"
          multi-sort
          show-expand
          show-select
          class="elevation-3"
          :rows-per-page-items="[500, 300, 100, 50, 20, 15, 5, -1]"
          :footer-props="{
            showFirstLastPage: true,
            firstIcon: 'mdi-arrow-collapse-left',
            lastIcon: 'mdi-arrow-collapse-right',
            prevIcon: 'mdi-minus',
            nextIcon: 'mdi-plus'
          }"
          :search="expand.search"
          :custom-filter="filterSearch"
          min-width="300"
          max-width="100%"
          style="width: 100%;"
        >

          <template v-slot:top>
            <v-dialog v-model="expand.dialog" min-width="550px" max-width="1100px">
              <template v-slot:activator="{ on }">
                <v-toolbar flat color="primary" dark height="75px">
                  <v-toolbar-title background-color="blue darken-1">Список заказов</v-toolbar-title>
                  <v-divider
                    class="mx-4"
                    inset
                    vertical
                  />
                  <v-spacer />
                  <v-btn big flat color="primary" dark class="mb-2" v-on="on">Новый заказ</v-btn>
                  <v-spacer />
                  <v-text-field
                    v-model="expand.search"
                    label="Поиск по заказам"
                    append-icon="search"
                    style="margin-left: 10px;margin-right: 10px"
                    hide-details
                  />
                  <v-spacer />
                  <div style="padding-left: 50px; padding-right: 80px">
                    <v-switch v-model="expand.singleExpand" label="Одиночный режим" />
                  </div>
                </v-toolbar>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                  <el-form ref="orderForm" :model="editedItemOrder" label-position="top">
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            v-model="editedItemOrder.id"
                            label="ID Заказа"
                            min="1"
                            step="1"
                            type="number"
                            :value="totalNew"
                          />
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            v-model="editedItemOrder.user_id"
                            label="ID Пользователя"
                            clearable
                            min="1"
                            step="1"
                            type="number"
                          />
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            v-show="editedItemOrder.order_id !== ''"
                            v-model="editedItemOrder.order_id"
                            label="ID Заказа"
                            clearable
                            min="1"
                            step="1"
                            type="number"
                          />
                        </v-col>
                        <v-col :span="10" cols="12" sm="12" md="12">
                          <label>Заказчик: </label>
                          <SelectOrdererDropdown v-model="orderersId" :options="orderersData" style="width: 80%" />
                        </v-col>
                        <v-col :span="10" cols="12" sm="12" md="12">
                          <label>Исполнитель: </label>
                          <SelectExecutorDropdown v-show="editedItemOrder.executor_id !== ''" v-model="editedItemOrder.executor_id" :options="executorsData" style="width: 80%" />
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <label>Статус заказа: </label>
                          <el-select v-model="editedItemOrder.status" :placeholder="$t('table.status')" clearable style="width: 110px" class="filter-item" @change="handleFilterReport">
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
                        </v-col>
                        <v-col cols="12" sm="6" md="4">
                          <v-text-field
                            v-model="editedItemOrder.sum"
                            label="Цена"
                            clearable
                            min="1"
                            step="1"
                            type="number"
                          />
                        </v-col>
                        <v-col cols="12" sm="12" md="12">
                          <v-text-field v-model="editedItemOrder.title" label="Заголовок" clearable />
                        </v-col>
                        <v-col cols="12" sm="12" md="12">
                          <v-textarea v-model="editedItemOrder.note" placeholder="Комментарий" :persistent-hint="true" label="Комментарий" clearable outlined filled hint="Ввод комментария не должен превышать СИМВОЛОВ" />
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-textarea v-model="editedItemOrder.content" label="Контент" :persistent-hint="true" clearable outlined filled hint="Ввод контента не должен превышать СИМВОЛОВ" />
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-textarea v-model="editedItemOrder.content_short" label="Контент короткий" :persistent-hint="true" clearable outlined filled hint="Ввод контента короткий не должен превышать СИМВОЛОВ" />
                        </v-col>
                      </v-row>
                    </v-container>
                  </el-form>
                </v-card-text>
                <v-card-actions>
                  <v-spacer />
                  <v-btn color="blue darken-1" text @click="close">Отмена</v-btn>
                  <v-btn color="green darken-2" text @click="save">Сохранить</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </template>
          <template v-slot:item.note="props">
            <v-edit-dialog
              style="width: 450px;"
              :return-value.sync="props.item.note"
              large
              persistent
              cancel-text="Отмена"
              save-text="Сохранить"
              @save="saveProp"
              @cancel="cancelProp"
              @open="openProp"
              @close="closeProp"
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
          <template v-slot:item.status="{ item }">
            <v-chip style="color: white; background-color: royalblue;" dark>{{ item.status }}</v-chip>
          </template>
          <template v-slot:item.title="{ item }">
            <div class="size">
              <span>{{ item.title }}</span>
            </div>
          </template>
          <template v-slot:item.created_at="{ item }">
            <div>
              <i v-if="item.created_at !== ''" class="el-icon-date" />
              <span>{{ item.created_at | parseTime('{d}/{m}/{y}') }}</span>
            </div>
            <div>
              <i v-if="item.created_at !== ''" class="el-icon-time" />
              <span>{{ item.created_at | parseTime('{h}:{i}:{s}') }}</span>
            </div>
          </template>
          <template v-slot:item.updated_at="{ item }">
            <div>
              <i v-if="item.updated_at !== ''" class="el-icon-date" />
              <span v-if="item.updated_at !== ''">
                {{ item.updated_at | parseTime('{d}/{m}/{y}') }}
              </span>
              <span v-else>
                нет года обновления
              </span>
            </div>
            <div>
              <i v-if="item.updated_at !== ''" class="el-icon-time" />
              <span v-if="item.updated_at !== ''">
                {{ item.updated_at | parseTime('{h}:{i}:{s}') }}
              </span>
              <span v-else>
                нет времени обновления
              </span>
            </div>
          </template>
          <template v-slot:item.deleted_at="{ item }">
            <div>
              <i v-if="item.deleted_at !== ''" class="el-icon-date" />
              <span v-if="item.deleted_at !== ''">
                {{ item.deleted_at | parseTime('{d}/{m}/{y}') }}
              </span>
              <span v-else>
                нет года обновления
              </span>
            </div>
            <div>
              <i v-if="item.deleted_at !== ''" class="el-icon-time" />
              <span v-if="item.deleted_at !== ''">
                {{ item.deleted_at | parseTime('{h}:{i}:{s}') }}
              </span>
              <span v-else>
                нет времени обновления
              </span>
            </div>
          </template>
          <template v-slot:item.action="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item)"
            >
              edit
            </v-icon>
            <v-icon
              small
              @click="deleteItem(item)"
            >
              delete
            </v-icon>
          </template>
          <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
          </template>
          <template v-slot:expanded-item="{ headers }">
            <td :colspan="headers.length">
              <div style="padding: 10px; align-content: center">
                <v-toolbar flat color="orange">
                  <v-toolbar-title>
                    Список отчётов в заказе
                  </v-toolbar-title>
                </v-toolbar>
                <br>
                <div>
                  <div class="filter-container" style="width: 85%" color="orange">
                    <el-input v-model="queryReport.keyword" :placeholder="$t('table.keyword')" clearable style="width: 250px;" class="filter-item" @keyup.enter.native="handleFilterReport" />
                    <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilterReport">
                      {{ $t('table.search') }}
                    </el-button>
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
                  <el-row style="background:#fff;padding:5px 5px 0;margin-bottom:2px;">
                    <el-col :xs="{span: 24}" :sm="{span: 24}" :md="{span: 24}" :lg="{span: 24}" :xl="{span: 24}" style="padding-right:2px;margin-bottom:2px;">
                      <el-table
                        ref="multipleTableReport"
                        v-loading="loadingReport"
                        :data="listReport"
                        :row-class-name="tableRowClassName"
                        border
                        stripe
                        min-height="300"
                        max-height="1700"
                        highlight-current-row
                        style="width: 1700px; z-index: 1"
                        :row-key="getRowKeys"
                        @selection-change="handleSelectionChange"
                      >
                        <el-table-column fixed prop="ID" type="selection" :reserve-selection="true" width="40" />
                        <el-table-column fixed align="center" label="ID" :sortable="true" :sort-method="SortIndex" min-width="110" width="110">
                          <template slot-scope="scope">
                            <span>{{ scope.row.index }}</span>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="ID Пользователя" :sortable="true" width="100">
                          <template slot-scope="scope">
                            <span>{{ scope.row.user_id }}</span>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="ID Заказов" width="150">
                          <template slot-scope="scope">
                            <span>{{ scope.row.order_id }}</span>
                          </template>
                        </el-table-column>
                        <el-table-column :label="$t('table.status')" width="125px">
                          <template slot-scope="scope">
                            <span>
                              <el-tag>{{ scope.row.status }}</el-tag>
                              <svg-icon v-for="n in +scope.row.status" :key="n" icon-class="star" class="meta-item__icon" />
                            </span>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="Заголовок" width="280">
                          <template slot-scope="scope">
                            <div class="size">
                              {{ scope.row.title }}
                            </div>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="content_short" width="280">
                          <template slot-scope="scope">
                            <div class="size">
                              <span>{{ scope.row.content_short }}</span>
                            </div>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="content" width="280">
                          <template slot-scope="scope">
                            <div class="size">
                              <span>{{ scope.row.content }}</span>
                            </div>
                          </template>
                        </el-table-column>
                        <el-table-column label="Цена" :sortable="true" min-width="100" max-width="120" width="120" align="center">
                          <template slot-scope="scope">
                            <span v-if="scope.row.price !== 0">
                              {{ scope.row && scope.row.price }}<img style="height: 14px; width: 14px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAA0klEQVRIie2TLQ7CQBBGXwlHABJwoPBVcAc8Csk51hEsEskVehhAoQiB4DB1xUzDhkyWKXSD6Usms5l295tvf6DhBxxQBCIHjsAa6MUQ8OMGTLVFWkaxRIkOMAfOQBfIgMG3DkIMgYf8t3n/aHUQ4gTsZDyLIQBwkNyPJTCWfIkhMAIWMs6qTHSED9m/RQVwR9kiDevd9+MKTKydWxfNgT2wEjeVcdjeQZC2UltKTpVaydYqkCg1S8faPBXNQdldysuFueMqOGo4g7pe8v8EGj7yBMNdS24PVQbTAAAAAElFTkSuQmCC">
                            </span>
                            <span v-else>
                              Бесплатно
                            </span>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="created_at_year" width="140">
                          <template slot-scope="scope">
                            <div>
                              <i v-if="scope.row.created_at !== ''" class="el-icon-date" />
                              <span>{{ scope.row.created_at | parseTime('{d}/{m}/{y}') }}</span>
                            </div>
                            <div>
                              <i v-if="scope.row.created_at !== ''" class="el-icon-time" />
                              <span>{{ scope.row.created_at | parseTime('{h}:{i}:{s}') }}</span>
                            </div>
                          </template>
                        </el-table-column>
                        <el-table-column align="center" label="updated_at_year" width="170">
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
                        <el-table-column align="center" label="deleted_at_year" width="170">
                          <template slot-scope="scope">
                            <div>
                              <i v-if="scope.row.deleted_at !== ''" class="el-icon-date" />
                              <span v-if="scope.row.deleted_at !== ''">
                                {{ scope.row.deleted_at | parseTime('{d}/{m}/{y}') }}
                              </span>
                              <span v-else>
                                нет года удаления
                              </span>
                            </div>
                            <div>
                              <i v-if="scope.row.deleted_at !== ''" class="el-icon-time" />
                              <span v-if="scope.row.deleted_at !== ''">
                                {{ scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
                              </span>
                              <span v-else>
                                нет времени удаления
                              </span>
                            </div>
                          </template>
                        </el-table-column>
                        <el-table-column fixed="right" align="center" label="Действия" width="250">
                          <template slot-scope="scope">
                            <router-link :to="'/reports/report/edit/'+scope.row.id">
                              <el-button type="primary" size="small" icon="el-icon-edit">Изменить</el-button>
                            </router-link>
                            <el-button
                              type="danger"
                              size="small"
                              icon="el-icon-delete"
                              @click.native.prevent="DeleteRow(scope.$index, list)"
                            >Удалить</el-button>
                          </template>
                        </el-table-column>
                      </el-table>
                    </el-col>
                  </el-row>
                  <pagination v-show="total>0" :total="total" :page.sync="queryReport.page" :limit.sync="queryReport.limit" align="left" style="margin-left: 0px" @pagination="getListReport" />
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
        <el-dialog :title="'Создание нового отчёта'" :visible.sync="dialogFormVisible">
          <div v-loading="reportCreating" class="form-container">
            <el-form ref="reportForm" :rules="rules" :model="newReport" label-position="left" label-width="150px" style="max-width: 500px;">
              <el-form-item :label="$t('order.id')" prop="id">
                <el-input v-model="newReport.id" />
              </el-form-item>
              <el-form-item :label="$t('order.report_id')" prop="report_id">
                <el-input v-model="newReport.report_id" />
              </el-form-item>
              <el-form-item :label="$t('order.title')" prop="title">
                <el-input v-model="newReport.title" />
              </el-form-item>
              <el-form-item :label="$t('order.price')" prop="price">
                <el-input v-model="newReport.price" />
              </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
              <el-button @click="dialogFormVisible = false">
                {{ $t('table.cancel') }}
              </el-button>
              <el-button type="primary" @click="createReport()">
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
import {
  SelectOrdererDropdown,
  SelectExecutorDropdown,
} from './components/Dropdown';
import { mapState } from 'vuex';

const orderResource = new OrderResource();
const reportResource = new ReportResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'OrderList',
  components: {
    Pagination,
    SelectOrdererDropdown,
    SelectExecutorDropdown,
  },
  directives: { waves, permission },
  data() {
    return {
      expand: {
        expanded: [],
        rowsPerPageItems: [5, 10, 20, 50, 100, 250, 500, 750, 1000],
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
        dialog: false,
        editedIndexOrder: -1,
        TableHeader: [
          {
            text: 'Развернуть...',
            width: '60',
            value: 'data-table-expand',
            filterable: false,
            sortable: false,
          },
          {
            isEditorBottom: 'true',
            text: 'Действия',
            width: '90',
            value: 'action',
            filterable: false,
            sortable: false,
          },
          {
            text: 'ID',
            width: '90',
            align: 'left',
            sortable: true,
            value: 'id',
          },
          { text: 'ID (Создатель заказа)', width: 210, value: 'user_id' },
          { text: 'ID Отчёта', width: 130, value: 'order_id' },
          { text: 'ID Исполниеля', width: 170, value: 'orderer_id' },
          { text: 'ID Заказчика', width: 160, value: 'executor_id' },
          { text: 'Статус', width: 150, value: 'status' },
          { text: 'Дата создания', width: 160, value: 'created_at' },
          { text: 'Дата изменения', width: 260, value: 'updated_at' },
          { text: 'Дата удаления', width: 260, value: 'deleted_at' },
          { text: 'Заголовок', width: 220, value: 'title' },
          { text: 'Комментарий', width: 120, value: 'note' },
          { text: 'Цена заказа', value: 'sum' },
        ],
      },
      list: null,
      listReport: null,
      total: 1,
      loading: true,
      loadingReport: true,
      downloading: false,
      getRowKeys(row) {
        return row.id;
      },
      orderCreating: false,
      reportCreating: false,
      query: {
        page: 1,
        limit: 10,
        keyword: '',
        role: '',
        status: '',
      },
      queryReport: {
        page: 1,
        limit: 5,
        keyword: '',
        status: '',
      },
      roles: ['admin', 'manager', 'editor', 'user', 'visitor'],
      status: ['1', '2', '3', '4', '5'],
      nonAdminRoles: ['editor', 'user', 'visitor'],
      newOrder: {},
      newReport: {},
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
        status: 0,
        title: '',
        note: '',
        sum: '',
      },
      currentReport: {
        id: '',
        user_id: '',
        order_id: '',
        report_id: '',
        status: 0,
        title: '',
        content: '',
        content_short: '',
        price: '',
      },
      editedItemOrder: {
        id: 0,
        user_id: 0,
        order_id: '',
        report_id: '',
        orderers_id: null,
        executor_id: 1,
        status: 0,
        title: '',
        content: '',
        content_short: '',
        sum: '',
      },
      defaultItemOrder: {
        id: 0,
        user_id: 0,
        order_id: '',
        report_id: '',
        status: 0,
        created_at: '',
        updated_at: '',
        deleted_at: '',
        title: '',
        content: '',
        content_short: '',
        sum: 0,
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
    ...mapState(
      {
        orderersData: state => state.orderers.data,
        executorsData: state => state.executors.data,
      }
    ),
    orderersOptions() {
      const result = [
        {
          orderer_id: '0',
          value: 'null',
          label: 'Выбирите заказчика',
          photo: '',
        },
      ];
      return result.concat(this.orderersData);
    },
    orderersId: {
      get() {
        if (!this.editedItemOrder.orderers_id) {
          console.log(this.editedItemOrder.orderers_id, !this.editedItemOrder.orderers_id);
          return 0;
        }
        return this.editedItemOrder.orderers_id;
      },
      set(value) {
        console.log(value);
        this.editedItemOrder.orderers_id = value;
      },
    },
    formTitle() {
      return this.editedIndexOrder === -1 ? 'Создание заказа' : 'Редактирование заказа';
    },
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
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  created() {
    this.getListOrder();
    this.getListReport();
    if (checkPermission(['manage permission'])) {
      this.getPermissions();
    }
    this.resetNewOrder();
  },
  mounted(){
    this.getListOrder();
  },
  activated(){
    this.getListOrder();
  },
  deactivated(){
    this.getListOrder();
  },
  methods: {
    created() {
      this.getListOrder();
      this.getListReport();
      this.resetNewOrder();
    },
    SortIndex: function(a, b) {
      return a.index < b.index;
    },
    DeleteRow(index, rows) {
      rows.splice(index, 1);
      this.getListReport();
    },
    mounted(){
      this.getListOrder();
    },
    activated(){
      this.getListOrder();
    },
    deactivated(){
      this.getListOrder();
    },
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTableReport.toggleRowSelection(row);
        });
      } else {
        this.$refs.multipleTableReport.clearSelection();
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
    handleCreateReport() {
      this.resetNewReport();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['reportForm'].clearValidate();
      });
    },
    handleDelete(id) {
      const index_id = id;
      this.$confirm('Это навсегда удалит заказ ' + id + ' . Продолжить?', 'Внимание!!!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        orderResource.destroy(id).then(response => {
          this.list.splice(index_id - 1, 1);
          this.$message({
            type: 'success',
            message: 'Удаление прошло успешно',
          });
          this.expand.snack = true;
          this.expand.snackColor = 'success';
          this.expand.snackText = 'Заказ успешно удален' + ' ' + (id);
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Удаление отменено',
        });
        this.expand.snack = true;
        this.expand.snackColor = 'success';
        this.expand.snackText = 'Удаление заказа' + ' ' + (id) + ' ' + 'отменено';
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
              this.expand.snack = true;
              this.expand.snackColor = 'success';
              this.expand.snackText = 'Введенные данные подготовлены для сохранения';
              console.log('Диалог введенния данных подготовлены для сохранения');
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
              this.expand.snack = true;
              this.expand.snackColor = 'error';
              this.expand.snackText = 'Введенние подготовленых данных прервано';
              console.log('Диалог введенния данных прерван');
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
    createReport() {
      this.$refs['reportForm'].validate((valid) => {
        if (valid) {
          this.reportCreating = true;
          reportResource
            .store(this.newReport)
            .then(response => {
              this.$message({
                message: 'Новый отчёт ' + this.newReport.id + ' (' + this.newReport.report_id + ') был успешно создан.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.expand.snack = true;
              this.expand.snackColor = 'success';
              this.expand.snackText = 'Введенные данные подготовлены для сохранения';
              console.log('Диалог введенния данных подготовлены для сохранения');
              this.resetNewReport();
              this.dialogFormVisible = false;
              this.handleFilterReport();
            })
            .catch(error => {
              this.$message({
                message: 'Ошибка - Новый отчёт ' + this.newReport.id + ' (' + this.newReport.report_id + ') не создан.',
                type: 'error',
                duration: 5 * 1000,
              });
              this.expand.snack = true;
              this.expand.snackColor = 'error';
              this.expand.snackText = 'Введенние подготовленых данных прервано';
              console.log('Диалог введенния данных прерван');
              console.log(error);
            })
            .finally(() => {
              this.reportCreating = false;
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
        title: '',
        note: '',
        sum: '',
      };
    },
    resetNewReport() {
      this.newReport = {
        id: '',
        user_id: '',
        order_id: '',
        report_id: '',
        status: '',
        created_at: '',
        updated_at: '',
        deleted_at: '',
        title: '',
        content: '',
        content_short: '',
        price: '',
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
    saveProp() {
      this.expand.snack = true;
      this.expand.snackColor = 'success';
      this.expand.snackText = 'Введенные данные подготовлены для сохранения';
      console.log('Диалог введенния данных подготовлены для сохранения');
    },
    cancelProp() {
      this.expand.snack = true;
      this.expand.snackColor = 'error';
      this.expand.snackText = 'Ввод отменен';
      console.log('Диалог вводa отменен');
    },
    openProp() {
      this.expand.snack = true;
      this.expand.snackColor = 'info';
      this.expand.snackText = 'Диалог ввода открыт';
      console.log('Диалог ввода открыт');
    },
    closeProp() {
      this.expand.snack = true;
      this.expand.snackColor = 'warning';
      this.expand.snackText = 'Диалог ввода закрыт';
      console.log('Диалог ввода закрыт');
    },
    editItem(item) {
      const index = this.list.indexOf(item);
      this.editedIndexOrder = this.list.indexOf(item);
      this.editedItemOrder = Object.assign({}, item);
      this.expand.dialog = true;
      this.expand.snack = true;
      this.expand.snackColor = 'success';
      this.expand.snackText = 'Заказ изменен' + ' ' + (index + 1);
      console.log('Диалог изменения завершен');
    },
    deleteItem(item) {
      const index = this.list.indexOf(item);
      this.handleDelete(index + 1);
      console.log('Диалог удаления завершен');
    },
    close() {
      this.expand.dialog = false;
      setTimeout(() => {
        this.editedItemOrder = Object.assign({}, this.defaultItemOrder);
        this.editedIndexOrder = -1;
      }, 300);
    },
    save() {
      if (this.editedIndexOrder > -1) {
        Object.assign(this.list[this.editedIndexOrder], this.editedItemOrder);
      } else {
        this.list.push(this.editedItemOrder);
      }
      this.createOrder();
      this.close();
      this.expand.snack = true;
      this.expand.snackColor = 'success';
      this.expand.snackText = 'Введенные данные подготовлены для сохранения';
      console.log('Диалог введенния данных подготовлены для сохранения');
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
.size,
.size span,
.container__text__cell div .size{
  white-space: nowrap; /* Отменяем перенос текста */
  overflow: hidden; /* Обрезаем содержимое */
  padding: 1px; /* Поля */
  text-overflow: ellipsis; /* Многоточие */
  max-width: 150px;
}
.size::after,
.size::after span,
.container__text__cell div .size::after
{
  content: ''; /* Выводим элемент */
  position: absolute; /* Абсолютное позиционирование */
  right: 0; top: 0; /* Положение элемента */
  width: 40px; /* Ширина градиента*/
  height: 100%; /* Высота родителя */
}
</style>
<style rel="stylesheet/scss" lang="scss" scoped>
  @import "~@/styles/mixin.scss";
  .createPost-container {
    position: relative;
    .createPost-main-container {
      padding: 0 45px 20px 50px;
      .postInfo-container {
        position: relative;
        @include clearfix;
        margin-bottom: 10px;
        .postInfo-container-item {
          float: left;
        }
      }
    }
    .word-counter {
      width: 40px;
      position: absolute;
      right: -10px;
      top: 0px;
    }
  }
</style>
<style>
  .createPost-container label.el-form-item__label {
    text-align: left;
  }
</style>
