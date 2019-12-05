<template>
  <v-app id="inspire">
    <div class="app-container">
      <v-card
        class="mx-auto"
        max-width="100%"
      >
        <v-card-text>
          <div class="filter-container">
            <v-toolbar flat color="white" dark height="30px">
              <el-select v-model="query.role" :placeholder="$t('table.role')" clearable style="width: 110px; margin-left: 80px; margin-right: 10px;" class="filter-item" @change="handleFilter">
                <el-option v-for="item in roles" :key="item" :label="item | uppercaseFirst" :value="item" />
              </el-select>
              <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" clearable style="width: 950px; margin-right: 10px;" class="filter-item" @keyup.enter.native="handleFilter" />
              <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
                {{ $t('table.search') }}
              </el-button>
              <el-button class="filter-item" style="margin-left: 2px;" type="primary" icon="el-icon-plus" @click="handleCreate">
                {{ $t('table.add') }}
              </el-button>
              <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
                {{ $t('table.export') }}
              </el-button>
              <v-spacer />
            </v-toolbar>
          </div>

          <el-table
            v-loading="loading"
            :data="list"
            :default-sort="{prop: 'value', order: 'descending'}"
            :sortable="false"
            border
            fit
            highlight-current-row
            :sort-by.sync="sortBy"
            :sort-desc.sync="descending"
            :row-key="getRowKeys"
            :min-height="300"
            :max-height="700"
            style="width: 100%"
          >
            <el-table-column fixed="left" prop="ID" type="selection" :reserve-selection="true" width="45" />
            <el-table-column fixed="left" prop="value" :sortable="true" :sort-method="(a, b) => sortData(a, b, 'value')" :sort-orders="['descending']" align="center" label="ID" width="60">
              <template slot-scope="scope">
                <span>{{ scope.row.index }}</span>
              </template>
            </el-table-column>
            <el-table-column prop="roles" :sortable="true" column-key="roles" align="center" label="Роль" width="100">
              <template slot-scope="scope">
                <div v-show="scope.row.roles.join(', ') === ''">
                  <span>Нет роли</span>
                </div>
                <div v-show="scope.row.roles.join(', ') !== null">
                  <span>{{ scope.row.roles.join(', ') }}</span>
                </div>
              </template>
            </el-table-column>
            <el-table-column align="center" label="Аватар" width="90">
              <template slot-scope="scope">
                <router-link :to="'/administrator/users/edit/'+scope.row.id">
                  <v-avatar
                    class="profile"
                    color="grey"
                    size="60"
                  >
                    <v-img
                      :src="scope.row.avatar"
                      :lazy-src="scope.row.avatar"
                      alt="Аватарка"
                      aspect-ratio="1"
                    />
                  </v-avatar>
                </router-link>
              </template>
            </el-table-column>
            d
            <el-table-column align="center" label="Имя пользователя" width="160">
              <template slot-scope="scope">
                <div class="size_all">
                  <span>{{ scope.row.name }}</span>
                </div>
              </template>
            </el-table-column>

            <el-table-column
              align="center"
              prop="gender"
              :sortable="true"
              column-key="gender"
              label="Пол"
              width="120"
              :filters="[{text: 'Женщина', value: 'female'}, {text: 'Мужчина', value: 'male'}]"
              :filter-method="filterHandler"
            >
              <template slot-scope="scope">
                <div v-if="scope.row.gender === 'male'">
                  <div class="size">
                    <v-icon v-show="scope.row.gender !== null" color="blue">mdi mdi-gender-male</v-icon>
                    <span>Мужчина</span>
                  </div>
                </div>
                <div v-else-if="scope.row.gender === 'female'">
                  <div class="size">
                    <v-icon v-show="scope.row.gender !== null" color="pink">mdi mdi-gender-female</v-icon>
                    <span>Женщина</span>
                  </div>
                </div>
              </template>
            </el-table-column>

            <el-table-column align="left" label="E-mail" width="220">
              <template slot-scope="scope">
                <div>
                  <div class="container__text__cell">
                    <div class="center__text__cell">
                      <el-tooltip placement="right-start" style="right: 5px;" effect="light">
                        <v-icon v-show="scope.row.email !== null" color="primary">mdi mdi-email-outline</v-icon>
                        <div slot="content" style="min-width: 10px; max-width: 200px;">
                          <span> {{ scope.row && scope.row.email }} </span>
                        </div>
                      </el-tooltip>
                    </div>
                    <div class="size">
                      {{ scope.row && scope.row.email }}
                    </div>
                  </div>
                </div>
              </template>
            </el-table-column>

            <el-table-column align="left" label="Телефоны все" width="180">
              <template slot-scope="scope">
                <div style="display: inline-grid; position: relative; grid-row-gap: 50px; grid-template-columns: auto auto auto;">
                  <div class="container__text__cell">
                    <div class="center__text__cell">
                      <div style="display: inline-grid; padding-right: 4px">
                        <el-tooltip placement="left-start" style="right: 2px; top: 12px;" effect="light">
                          <div style="position: absolute; display: inline-flex">
                            <v-icon v-show="scope.row.phone1 !== null || scope.row.phone2 !== null || scope.row.phone3 !== null || scope.row.phone4 !== null" color="red">mdi-phone-in-talk</v-icon>
                          </div>
                          <div slot="content" style="min-width: 10px; max-width: 600px;">
                            <tr>
                              <td v-show="scope.row.phone1 !== null" style="margin-right: 5px;">  Телефон №1: <span> {{ scope.row && scope.row.phone1 }}; </span></td>
                              <td v-show="scope.row.phone2 !== null" style="margin-right: 5px;">  Телефон №2: <span> {{ scope.row && scope.row.phone2 }}; </span></td>
                            </tr>
                            <tr>
                              <td v-show="scope.row.phone3 !== null" style="margin-right: 5px;">  Телефон №3: <span> {{ scope.row && scope.row.phone3 }}; </span></td>
                              <td v-show="scope.row.phone4 !== null" style="margin-right: 5px;">  Телефон №4: <span> {{ scope.row && scope.row.phone4 }}; </span></td>
                            </tr>
                          </div>
                        </el-tooltip>
                      </div>
                    </div>
                    <div style="display: inline-grid;">
                      <div v-show="scope.row.phone1 !== null" class="size">
                        <span>{{ scope.row.phone1 }}</span>
                      </div>
                      <div v-show="scope.row.phone2 !== null" class="size">
                        <span>{{ scope.row.phone2 }}</span>
                      </div>
                    </div>
                    <div v-show="scope.row.phone3 !== null || scope.row.phone4 !== null" style="display: inline-grid; margin-left: 1px">
                      <div v-show="scope.row.phone3 !== null" class="size">
                        <span>{{ scope.row.phone3 }}</span>
                      </div>
                      <div v-show="scope.row.phone4 !== null" class="size">
                        <span>{{ scope.row.phone4 }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </el-table-column>

            <el-table-column align="left" label="Skype" width="180">
              <template slot-scope="scope">
                <div class="size">
                  <v-icon v-show="scope.row.skype !== null" color="green">mdi mdi-skype</v-icon>
                  <span>{{ scope.row.skype }}</span>
                </div>
              </template>
            </el-table-column>

            <el-table-column align="left" label="Адресс" width="320">
              <template slot-scope="scope">
                <div>
                  <div class="container__text__cell">
                    <div class="center__text__cell">
                      <el-tooltip placement="right-start" style="right: 5px;" effect="light">
                        <v-icon v-show="scope.row.address !== null" color="primary">mdi-tooltip-text</v-icon>
                        <div slot="content" style="min-width: 10px; max-width: 600px;">
                          <span> {{ scope.row && scope.row.address }} </span>
                        </div>
                      </el-tooltip>
                    </div>
                    <div class="size">
                      {{ scope.row && scope.row.address }}
                    </div>
                  </div>
                </div>
              </template>
            </el-table-column>
            <el-table-column
              align="left"
              label="Дата деактивации"
              prop="deleted_at"
              :sortable="true"
              column-key="deleted_at"
              width="220"
            >
              <template slot-scope="scope">
                <div>
                  <i v-if="scope.row.deleted_at !== ''" class="el-icon-date" />
                  <span v-if="scope.row.deleted_at !== ''">
                    {{ scope.row && scope.row.deleted_at | parseTime('{d}/{m}/{y}') }}
                  </span>
                  <span v-else>
                    нет года деактивации
                  </span>
                </div>
                <div>
                  <i v-if="scope.row.deleted_at !== ''" class="el-icon-time" />
                  <span v-if="scope.row.deleted_at !== ''">
                    {{ scope.row && scope.row.deleted_at | parseTime('{h}:{i}:{s}') }}
                  </span>
                  <span v-else>
                    нет времени деактивации
                  </span>
                </div>
              </template>
            </el-table-column>
            <el-table-column fixed="right" align="center" label="Действия" width="260">
              <template slot-scope="scope">
                <div v-if="scope.row.deleted_at === ''">
                  <router-link v-if="!scope.row.roles.includes('admin')" :to="'/administrator/users/edit/'+scope.row.id">
                    <el-button v-permission="['manage user']" type="primary" size="small" icon="el-icon-edit" />
                  </router-link>
                  <div v-else-if="scope.row.roles.includes('admin')">
                    <div>
                      <span> Для профиля Администратора </span>
                    </div>
                    <div>
                      <span> Изменения запрещены </span>
                    </div>
                  </div>
                  <el-button v-if="!scope.row.roles.includes('admin')" v-permission="['manage permission']" type="warning" size="small" icon="el-icon-edit" @click="handleEditPermissions(scope.row.id);">
                    Разрешений
                  </el-button>
                  <el-button v-if="scope.row.roles.includes('moderator') || scope.row.roles.includes('menadger') || scope.row.roles.includes('manager') || scope.row.roles.includes('editor') || scope.row.roles.includes('visitor') || scope.row.roles.includes('user')" v-permission="['manage user']" type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.name);" />
                </div>
                <div v-else-if="scope.row.deleted_at !== ''">
                  <div v-if="!scope.row.roles.includes('admin')" :to="'/administrator/users/edit/'+scope.row.id">
                    <el-button v-if="scope.row.roles.includes('moderator') || scope.row.roles.includes('menadger') || scope.row.roles.includes('manager') || scope.row.roles.includes('editor') || scope.row.roles.includes('visitor') || scope.row.roles.includes('user')" v-permission="['manage user']" type="success" size="small" icon="el-icon-refresh" @click="handleRestore(scope.row.id, scope.row.name);">
                      Актвировать
                    </el-button>
                    <el-button v-if="scope.row.roles.includes('moderator') || scope.row.roles.includes('menadger') || scope.row.roles.includes('manager') || scope.row.roles.includes('editor') || scope.row.roles.includes('visitor') || scope.row.roles.includes('user')" v-permission="['manage user']" type="danger" size="small" icon="el-icon-delete" @click="handleDeleteForever(scope.row.id, scope.row.name);">
                      Удалить
                    </el-button>
                  </div>
                  <div v-else-if="scope.row.roles.includes('admin')">
                    <div>
                      <span> Для профиля Администратора </span>
                    </div>
                    <div>
                      <span> Изменения запрещены </span>
                    </div>
                  </div>
                </div>
              </template>
            </el-table-column>
          </el-table>

          <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

          <el-dialog :visible.sync="dialogPermissionVisible" :title="'Edit Permissions - ' + currentUser.name">
            <div v-if="currentUser.name" v-loading="dialogPermissionLoading" class="form-container">
              <div class="permissions-container">
                <div class="block">
                  <el-form :model="currentUser" label-width="80px" label-position="top">
                    <el-form-item label="Menus">
                      <el-tree ref="menuPermissions" :data="normalizedMenuPermissions" :default-checked-keys="permissionKeys(userMenuPermissions)" :props="permissionProps" show-checkbox node-key="id" class="permission-tree" />
                    </el-form-item>
                  </el-form>
                </div>
                <div class="block">
                  <el-form :model="currentUser" label-width="80px" label-position="top">
                    <el-form-item label="Permissions">
                      <el-tree ref="otherPermissions" :data="normalizedOtherPermissions" :default-checked-keys="permissionKeys(userOtherPermissions)" :props="permissionProps" show-checkbox node-key="id" class="permission-tree" />
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

          <el-dialog :title="'Создать нового пользователя'" :visible.sync="dialogFormVisible">
            <div v-loading="userCreating" class="form-container">
              <el-form ref="userForm" :rules="rules" :model="newUser" label-position="left" label-width="170px" style="max-width: 500px;">
                <el-form-item :label="$t('user.role')" prop="role">
                  <el-select v-model="newUser.role" class="filter-item" placeholder="Выберите роль">
                    <el-option v-for="item in nonAdminRoles" :key="item" :label="item | uppercaseFirst" :value="item" />
                  </el-select>
                </el-form-item>
                <el-form-item :label="$t('user.name')" prop="name">
                  <el-input v-model="newUser.name" />
                </el-form-item>
                <el-form-item :label="$t('user.gender')" prop="gender">
                  <el-select v-model="newUser.gender" class="filter-item" placeholder="Выберите пол">
                    <el-option v-for="item in genderVulue" :key="item" :label="item | uppercaseFirst" :value="item" />
                  </el-select>
                </el-form-item>
                <el-form-item :label="$t('user.email')" prop="email">
                  <el-input v-model="newUser.email" />
                </el-form-item>
                <el-form-item :label="$t('user.password')" prop="password">
                  <el-input v-model="newUser.password" show-password />
                </el-form-item>
                <el-form-item :label="$t('user.confirmPassword')" prop="confirmPassword">
                  <el-input v-model="newUser.confirmPassword" show-password />
                </el-form-item>
              </el-form>
              <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">
                  {{ $t('table.cancel') }}
                </el-button>
                <el-button type="primary" @click="createUser()">
                  {{ $t('table.confirm') }}
                </el-button>
              </div>
            </div>
          </el-dialog>
        </v-card-text>
      </v-card>
    </div>
  </v-app>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import UserResource from '@/api/user';
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive
import permission from '@/directive/permission'; // Waves directive
import checkPermission from '@/utils/permission'; // Permission checking

const userResource = new UserResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'UserList',
  components: { Pagination },
  directives: { waves, permission },
  data() {
    const validateConfirmPassword = (rule, value, callback) => {
      if (value !== this.newUser.password) {
        callback(new Error('Пароли не совпадают!'));
      } else {
        callback();
      }
    };
    return {
      list: null,
      total: 1,
      loading: true,
      sortBy: 'id',
      getRowKeys(row) {
        return row.id;
      },
      descending: true,
      ascending: false,
      downloading: false,
      userCreating: false,
      query: {
        page: 1,
        limit: 16,
        keyword: '',
        status: '',
        role: '',
      },
      roles: ['admin', 'moderator', 'manager', 'editor', 'user', 'visitor', 'guest'],
      nonAdminRoles: ['moderator', 'manager', 'editor', 'user', 'visitor', 'guest'],
      nonModeratorRoles: ['manager', 'editor', 'user', 'visitor', 'guest'],
      nonManagerRoles: ['editor', 'user', 'visitor', 'guest'],
      genderVulue: ['male', 'female'],
      newUser: {},
      dialogFormVisible: false,
      dialogPermissionVisible: false,
      dialogPermissionLoading: false,
      currentUserId: 0,
      currentUser: {
        name: '',
        permissions: [],
        rolePermissions: [],
      },
      rules: {
        role: [{ required: true, message: 'Role is required', trigger: 'change' }],
        name: [{ required: true, message: 'Поле Имя обязательно для заполнения', trigger: 'blur' }],
        gender: [{ required: true, message: 'Поле пол обязательно для заполнения', trigger: 'blur' }],
        email: [
          { required: true, message: 'Поле Email обязательно для заполнения', trigger: 'blur' },
          { type: 'email', message: 'Пожалуйста, введите правильный адрес электронной почты', trigger: ['blur', 'change'] },
        ],
        password: [{ required: true, message: 'Поле Пароль обязательно для заполнения', trigger: 'blur' }],
        confirmPassword: [{ validator: validateConfirmPassword, trigger: 'blur' }],
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
      this.currentUser.permissions.role.forEach(permission => {
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

      tmp = this.menuPermissions.filter(permission => !this.currentUser.permissions.role.find(p => p.id === permission.id));
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
      this.currentUser.permissions.role.forEach(permission => {
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

      tmp = this.otherPermissions.filter(permission => !this.currentUser.permissions.role.find(p => p.id === permission.id));
      const userPermissions = {
        id: 0,
        name: 'Extra permissions',
        children: tmp,
        disabled: tmp.length === 0,
      };

      return [rolePermissions, userPermissions];
    },
    userMenuPermissions() {
      return this.classifyPermissions(this.userPermissions).menu;
    },
    userOtherPermissions() {
      return this.classifyPermissions(this.userPermissions).other;
    },
    userPermissions() {
      return this.currentUser.permissions.role.concat(this.currentUser.permissions.user);
    },
  },
  created() {
    this.resetNewUser();
    this.getList();
    if (checkPermission(['manage permission'])) {
      this.getPermissions();
    }
  },
  methods: {
    getAvatar(value){
      return this.avatar;
    },
    sortData(a, b, key) {
      return a.value > b.value ? 1 : a.value < b.value ? 0 : -1;
    },
    filterHandler(value, row, column) {
      const property = column['property'];
      return row[property] === value;
    },
    toggleOrder() {
      this.descending = !this.descending;
    },
    checkPermission,
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },

    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await userResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewUser();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    handleDelete(id, name) {
      this.$confirm('Это деактивирует пользователя ' + name + '. Продолжить?', 'Внимание!!!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        userResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Деактивация прошло успешно',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Деактивация отменена',
        });
      });
    },
    handleDeleteForever(id, name) {
      this.$confirm('Это навсегда удалит пользователя ' + name + '. Продолжить?', 'Внимание!!!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        userResource.destroy(id).then(response => {
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
    handleRestore(id, name) {
      this.$confirm('Это восстановит пользователя ' + name + '. Продолжить?', 'Внимание!!!', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        console.log(id);
        userResource.restore(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Восстановление прошло успешно',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        console.log(id);
        this.$message({
          type: 'info',
          message: 'Восстановление отменено',
        });
      });
    },
    async handleEditPermissions(id) {
      this.currentUserId = id;
      this.dialogPermissionLoading = true;
      this.dialogPermissionVisible = true;
      const found = this.list.find(user => user.id === id);
      const { data } = await userResource.permissions(id);
      this.currentUser = {
        id: found.id,
        name: found.name,
        permissions: data,
      };
      this.dialogPermissionLoading = false;
      this.$nextTick(() => {
        this.$refs.menuPermissions.setCheckedKeys(this.permissionKeys(this.userMenuPermissions));
        this.$refs.otherPermissions.setCheckedKeys(this.permissionKeys(this.userOtherPermissions));
      });
    },
    createUser() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
          this.newUser.roles = [this.newUser.role];
          this.userCreating = true;
          userResource
            .store(this.newUser)
            .then(response => {
              this.$message({
                message: 'Новый пользователь ' + this.newUser.name + ' (' + this.newUser.email + ') был успешно создан.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewUser();
              this.dialogFormVisible = false;
              this.handleFilter();
            })
            .catch(error => {
              this.$message({
                message: 'Ошибка - Номый пользователь ' + this.newUser.name + ' (' + this.newUser.email + ') не создан.',
                type: 'error',
                duration: 5 * 1000,
              });
              console.log(error);
            })
            .finally(() => {
              this.userCreating = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewUser() {
      this.newUser = {
        name: '',
        email: '',
        gender: '',
        password: '',
        confirmPassword: '',
        role: 'user',
      };
    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'user_id', 'name', 'gender', 'email', 'role'];
        const filterVal = ['index', 'id', 'name', 'gender', 'email', 'role'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'user-list',
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

      userResource.updatePermission(this.currentUserId, { permissions: checkedPermissions }).then(response => {
        this.$message({
          message: 'Разрешения были успешно обновлены',
          type: 'success',
          duration: 5 * 1000,
        });
        this.dialogPermissionLoading = false;
        this.dialogPermissionVisible = false;
      });
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
    min-width: 250px;
  }
  .clear-left {
    clear: left;
  }
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

.size_all,
.container__text__cell div .size_all {
  white-space: pre-line !important;
  overflow: hidden; /* Обрезаем содержимое */
  padding: 1px; /* Поля */
  text-overflow: ellipsis; /* Многоточие */
}
.size_all::after,
.container__text__cell div .size_all::after
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
