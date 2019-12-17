<template>
  <el-card v-if="user.id">
    <el-tabs v-model="activeProfile" @tab-click="handleClick">
      <el-tab-pane v-loading="updating" label="Профиль" name="first">
        <v-app id="inspire">
          <div>
            <v-toolbar flat color="primary" dark>
              <v-toolbar-title>Данные пользователея</v-toolbar-title>
            </v-toolbar>
            <v-tabs
              v-model="tab.tab"
              background-color="blue darken-1"
              class="elevation-1"
              dark
              center-active
              :centered="tab.centered"
              :grow="tab.grow"
              :vertical="tab.vertical"
              :right="tab.right"
              :prev-icon="tab.prevIcon ? 'mdi-arrow-left-bold-box-outline' : undefined"
              :next-icon="tab.nextIcon ? 'mdi-arrow-right-bold-box-outline' : undefined"
              :icons-and-text="tab.icons"
              @click="handleClick"
            >
              <v-tabs-slider />
              <v-tab :key="1">
                <v-icon>mdi-account</v-icon>
                Профиль
              </v-tab>
              <v-tab :key="2">
                <v-icon>mdi-shield-refresh</v-icon>
                Безопасность
              </v-tab>
              <v-tab :key="3">
                <v-icon>mdi-lock-reset</v-icon>
                Сброс пароля
              </v-tab>
              <v-tab :key="4">
                <v-icon>mdi-city-variant-outline</v-icon>
                Адреса
              </v-tab>
              <v-tab :key="5">
                <v-icon>mdi-email</v-icon>
                Email's
              </v-tab>
              <v-tab :key="6">
                <v-icon>mdi-skype</v-icon>
                Мессенджеры
              </v-tab>
              <v-tab :key="7">
                <v-icon>mdi-phone-classic</v-icon>
                Телефон
              </v-tab>
              <v-tab :key="8">
                <v-icon>mdi-settings-outline</v-icon>
                Настройки
              </v-tab>
              <v-tab-item :key="1">
                <v-card>
                  <v-card-text>
                    <div class="user-profile-grid-main">
                      <div class="flex-column flex-column1">
                        <div class="user-profile user-profile-grid-avatar user-profile-grid-main-area1">
                          <div class="user-avatar box-center user-profile-grid-main-avatar-area1">
                            <div style="display: flex; justify-content: space-between;">
                              <div v-if="user.onlineStatus">
                                <v-icon v-show="user.onlineStatus !== null" class="icon-list-profile" color="green">mdi mdi-account-check-outline</v-icon>
                                <b style="color: #01c003">Онлайн</b>
                              </div>
                              <div v-else-if="!user.onlineStatus">
                                <v-icon v-show="user.onlineStatus !== null" class="icon-list-profile" color="red">mdi mdi-account-off-outline</v-icon>
                                <b style="color: #cc0027">Оффлайн</b>
                              </div>
                              <div>
                                Ваш ID: {{ user.id }}
                              </div>
                            </div>
                            <a @click="imagecropperShow=true">
                              <div v-show="user.avatar !== null" v-if="user.avatar" v-loading="avatarupdating">
                                <pan-thumb v-if="user.avatar" v-loading="avatarupdating" :image="user.avatar" :src="user.avatar" class="avatar-pan-thumb" :hoverable="false" />
                              </div>
                              <div v-show="user.avatar === null" v-loading="avatarupdating">
                                <pan-thumb v-loading="avatarupdating" :image="'/uploads/avatars/default/v0/default.png'" class="avatar-pan-thumb" :hoverable="false" />
                              </div>
                            </a>
                            <image-cropper
                              v-show="imagecropperShow"
                              :key="imagecropperKey"
                              :value.sync="imagecropperShow"
                              :params="params"
                              :headers="headers"
                              :width="250"
                              :height="250"
                              field="avatar"
                              ki="0"
                              :url="'/users/' + user.id + '/avatarupload'"
                              lang-type="ru"
                              :no-circle="false"
                              @close="close"
                              @crop-upload-success="cropUploadSuccess"
                              @crop-upload-fail="cropUploadFail"
                            />
                          </div>
                          <div class="box-center user-profile-grid-main-avatar-area2">
                            <div class="user-name text-center text-muted">
                              <i>Имя:</i><b> {{ user.name }} </b>
                            </div>
                            <div class="user-role text-center text-muted">
                              <i>Ваши текушие роли:</i><b> {{ getRole() }} </b>
                            </div>
                          </div>
                          <div class="box-center user-profile-grid-main-avatar-area3">
                            <el-button type="primary" icon="upload" style="bottom: 15px;" @click="imagecropperShow=true">
                              Выбирите свой аватар
                            </el-button>
                          </div>
                        </div>
                      </div>
                      <div class="flex-column flex-column2">
                        <div class="user-profile-grid-main-area2 user-profile-grid-parametrs">
                          <div class="user-profile-grid-main-parametrs-area1">
                            <el-form-item label="Ф.И.О. (Заполните ниже поля):" />
                            <div>
                              <label> {{ user.surname }} {{ user.firstname }} {{ user.patronymic }} </label>
                            </div>
                          </div>
                          <div class="user-profile-grid-main-parametrs-area2-1">
                            <el-form-item label="Фамилия">
                              <el-input v-model="user.surname" :disabled="user.role === 'admin'" />
                            </el-form-item>
                          </div>
                          <div class="user-profile-grid-main-parametrs-area2-2">
                            <el-form-item label="Имя">
                              <el-input v-model="user.firstname" :disabled="user.role === 'admin'" />
                            </el-form-item>
                          </div>
                          <div class="user-profile-grid-main-parametrs-area3">
                            <el-form-item label="Отчество">
                              <el-input v-model="user.patronymic" :disabled="user.role === 'admin'" />
                            </el-form-item>
                          </div>
                        </div>
                      </div>
                      <div class="flex-column flex-column3">
                        <div class="user-profile-grid-main-area3 user-profile-grid-parametrs">
                          <div class="user-profile-grid-main-parametrs-area1">
                            <div style="margin-right: 5px">
                              <el-form-item label="Пол">
                                <el-select v-model="user.gender" :disabled="user.role === 'admin'">
                                  <el-option v-for="item in genderVulue" :key="item.id" :label="item.name | uppercaseFirst" :value="item.value" />
                                </el-select>
                              </el-form-item>
                            </div>
                          </div>
                          <div class="user-profile-grid-main-parametrs-area2">
                            <div style="margin-right: 5px">
                              <el-form-item label="День рождения">
                                <el-date-picker
                                  v-model="user.birthday"
                                  type="date"
                                  format="dd.MM.yyyy"
                                  value-format="yyyy-MM-dd"
                                  placeholder="Дата дня рождения"
                                  :disabled="user.role === 'admin'"
                                />
                              </el-form-item>
                            </div>
                          </div>
                          <div class="user-profile-grid-main-parametrs-area3">
                            <div style="margin-right: 5px">
                              <el-form-item label="Семейное положение">
                                <el-select v-model="user.family_status" :disabled="user.role === 'admin'">
                                  <el-option v-for="item in familyStatusVulue" :key="item.id" :label="item.name | uppercaseFirst" :value="item.value" />
                                </el-select>
                              </el-form-item>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </v-card-text>
                  <div class="container-test">
                    <div>1</div>
                    <div>2</div>
                    <div>3</div>
                    <div>4</div>
                    <div>5</div>
                    <div>6</div>
                    <div>7</div>
                    <div>8</div>
                    <div>9</div>
                  </div>
                  <v-card-text>
                    <el-form-item>
                      <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitProfile">
                        Обновить профиль
                      </el-button>
                    </el-form-item>
                  </v-card-text>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="2">
                <v-card>
                  <v-card-text class="user-profile-grid-main-guard-menu">
                    <div class="user-profile user-profile-grid-main-area1 user-profile-grid-guard-menu">
                      <div class="user-profile-grid-main-avatar-area2">
                        <el-form-item label="Имя:">
                          <el-input v-model="user.name" :disabled="user.role === 'admin'" />
                        </el-form-item>
                        <el-form-item label="Email (Логин) для авторизации:">
                          <el-input v-model="user.email" :disabled="user.role === 'admin'" />
                        </el-form-item>
                        <el-form-item label="Пароль:">
                          <span class="svg-container">
                            <svg-icon icon-class="password" />
                          </span>
                          <span class="show-pwd" @click="showPwd">
                            <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
                          </span>
                          <el-input
                            key="passwordType"
                            v-model="user.password"
                            :type="passwordType"
                            placeholder="Введите пароль для его смены"
                            required
                            :disabled="user.role === 'admin'"
                          />
                        </el-form-item>
                        <el-form-item label="Пароль повторить:">
                          <span class="svg-container">
                            <svg-icon icon-class="password" />
                          </span>
                          <span class="show-pwd" @click="showPwdConfirm">
                            <svg-icon :icon-class="passwordTypeConfirm === 'password' ? 'eye' : 'eye-open'" />
                          </span>
                          <el-input
                            key="passwordType"
                            ref="repeatedPasswordEl"
                            v-model="user.confirmPassword"
                            :type="passwordTypeConfirm"
                            placeholder="Введите пароль повторно"
                            required
                            :disabled="user.role === 'admin'"
                          />
                        </el-form-item>
                      </div>
                    </div>
                  </v-card-text>
                  <v-card-text>
                    <el-form-item>
                      <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitAuthenticator">
                        Обновить данные авторизации
                      </el-button>
                    </el-form-item>
                  </v-card-text>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="3">
                <v-card>
                  <v-card-text>
                    <el-form-item label="Пароль:">
                      <span class="svg-container">
                        <svg-icon icon-class="password" />
                      </span>
                      <span class="show-pwd" @click="showPwd">
                        <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
                      </span>
                      <el-input
                        key="passwordType"
                        v-model="user.password"
                        :type="passwordType"
                        placeholder="Введите пароль для его смены"
                        required
                        :disabled="user.role === 'admin'"
                      />
                    </el-form-item>
                    <el-form-item label="Пароль повторить:">
                      <span class="svg-container">
                        <svg-icon icon-class="password" />
                      </span>
                      <span class="show-pwd" @click="showPwdConfirm">
                        <svg-icon :icon-class="passwordTypeConfirm === 'password' ? 'eye' : 'eye-open'" />
                      </span>
                      <el-input
                        key="passwordType"
                        ref="repeatedPasswordEl"
                        v-model="user.confirmPassword"
                        :type="passwordTypeConfirm"
                        placeholder="Введите пароль повторно"
                        required
                        :disabled="user.role === 'admin'"
                      />
                    </el-form-item>
                  </v-card-text>
                  <v-card-text>
                    <el-form-item>
                      <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitPassword">
                        Сменить пароль
                      </el-button>
                    </el-form-item>
                  </v-card-text>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="4">
                <v-card>
                  <el-form-item>
                    <v-card-text>
                      <el-form-item label="Адрес работа">
                        <el-input v-model="user.address1" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item label="Адрес личный">
                        <el-input v-model="user.address2" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item>
                        <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitAddress">
                          Обновить адресса
                        </el-button>
                      </el-form-item>
                    </v-card-text>
                  </el-form-item>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="5">
                <v-card>
                  <el-form-item>
                    <v-card-text>
                      <el-form-item label="Email для авторизации (нельзя оставить пустым)">
                        <el-input v-model="user.email" :disabled="user.role === 'admin'" title="Только для чтения" required readonly />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item label="Email личный №1">
                        <el-input v-model="user.email1" type="email" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item label="Email личный №2">
                        <el-input v-model="user.email2" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item>
                        <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitEmail">
                          Обновить Email-ы
                        </el-button>
                      </el-form-item>
                    </v-card-text>
                  </el-form-item>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="6">
                <v-card>
                  <el-form-item>
                    <v-card-text>
                      <el-form-item label="Скайп">
                        <el-input v-model="user.skype" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item>
                        <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitSkype">
                          Обновить данные о скайпе
                        </el-button>
                      </el-form-item>
                    </v-card-text>
                  </el-form-item>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="7">
                <v-card>
                  <el-form-item>
                    <v-card-text>
                      <el-form-item label="Телефон мобильный">
                        <el-input v-model="user.phone1" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item label="Телефон рабочий">
                        <el-input v-model="user.phone2" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item label="Телефон домашний">
                        <el-input v-model="user.phone3" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item label="Телефон запасной">
                        <el-input v-model="user.phone4" :disabled="user.role === 'admin'" />
                      </el-form-item>
                    </v-card-text>
                    <v-card-text>
                      <el-form-item>
                        <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitPhone">
                          Обновить телефоны
                        </el-button>
                      </el-form-item>
                    </v-card-text>
                  </el-form-item>
                </v-card>
              </v-tab-item>
              <v-tab-item :key="8">
                <v-card>
                  <v-card-text>
                    <v-row
                      justify="space-around"
                    >
                      <div>
                        <h2><p>Внимание!!! данный раздел в разработке он временный для примера</p></h2>
                        <h3><p>Тут будут поля для других настроек пользователя</p></h3>
                      </div>
                    </v-row>
                    <v-row
                      justify="space-around"
                    >
                      <v-switch
                        v-model="tab.icons"
                        class="mx-2"
                        label="Текст & Иконки снизу "
                      />
                      <v-switch
                        v-model="tab.centered"
                        class="mx-2"
                        label="Отцентровать табы"
                        :disabled="tab.vertical"
                      />
                      <v-switch
                        v-model="tab.grow"
                        class="mx-2"
                        label="Режим 'Grow' растянуть в ширину"
                      />
                      <v-switch
                        v-model="tab.vertical"
                        class="mx-2"
                        label="Табы вертикально или горизонтально"
                      />
                      <v-switch
                        v-model="tab.true"
                        class="mx-2"
                        label="Табы прижать направо"
                      />
                      <v-col cols="12">
                        <v-slider
                          v-model="tab.tabs"
                          min="0"
                          max="10"
                          label="Вариант выбора числа при помощи ползунка"
                        />
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-tab-item>
            </v-tabs>
          </div>
        </v-app>
      </el-tab-pane>
      <el-tab-pane label="Статистика входа" name="second">
        <div id="app">
          <v-app id="inspire">
            <v-card>
              <h2>В место данной таблицы будут выводиться данные авторизации и тип входа пользователя,  с какого устройства вошли и дата время, текушее состояние</h2>
              <v-card-title>
                <v-text-field
                  v-model="search"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                />
              </v-card-title>
              <v-data-table
                :headers="headers_2"
                :items="desserts"
                :search="search"
              />
            </v-card>
          </v-app>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Activity" name="third">
        <div class="user-activity">
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDkaQO69Fro8SZLTVZQ75JH2R0T-sn5yIA_lKGwvvgQ0R0BoQtUQ"
                alt="user image"
              >
              <span class="username text-muted">
                <a href="#">Iron Man</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Shared publicly - 7:30 PM today</span>
            </div>
            <p>
              Lorem ipsum represents a long-held tradition for designers,
              typographers and the like. Some people hate it and argue for
              its demise, but others ignore the hate as they create awesome
              tools to help create filler text for everyone from bacon lovers
              to Charlie Sheen fans.
            </p>
            <ul class="list-inline">
              <li>
                <a href="#" class="link-black text-sm">
                  <i class="el-icon-share" /> Share
                </a>
              </li>
              <li>
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="like" />Like
                </a>
              </li>
              <li class="pull-right">
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="comment" />Comments
                  (5)
                </a>
              </li>
            </ul>
            <el-input placeholder="Type a comment" />
          </div>
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMMN-8f9CQQ3MKJpboBJIqaiJ2Wus2Tf4w_vx9STtalxrY3qGJ"
                alt="user image"
              >
              <span class="username text-muted">
                <a href="#">Captain American</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Sent you a message - yesterday</span>
            </div>
            <p>
              Lorem ipsum represents a long-held tradition for designers,
              typographers and the like. Some people hate it and argue for
              its demise, but others ignore the hate as they create awesome
              tools to help create filler text for everyone from bacon lovers
              to Charlie Sheen fans.
            </p>
            <el-input placeholder="Response">
              <el-button slot="append">
                Send
              </el-button>
            </el-input>
          </div>
          <div class="post">
            <div class="user-block">
              <img
                class="img-circle img-bordered-sm"
                src="https://cdn3.iconfinder.com/data/icons/movies-3/32/daredevil-superhero-marvel-comics-mutant-avatar-512.png"
                alt="User Image"
              >
              <span class="username">
                <a href="#">Daredevil</a>
                <a href="#" class="pull-right btn-box-tool">
                  <i class="fa fa-times" />
                </a>
              </span>
              <span class="description">Posted 4 photos - 2 days ago</span>
            </div>
            <div class="user-images">
              <el-carousel :interval="6000" type="card" height="200px">
                <el-carousel-item v-for="item in carouselImages" :key="item">
                  <img :src="item" class="image">
                </el-carousel-item>
              </el-carousel>
            </div>
            <ul class="list-inline">
              <li>
                <a href="#" class="link-black text-sm">
                  <i class="el-icon-share" /> Share
                </a>
              </li>
              <li>
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="like" />Like
                </a>
              </li>
              <li class="pull-right">
                <a href="#" class="link-black text-sm">
                  <svg-icon icon-class="comment" />Comments
                  (5)
                </a>
              </li>
            </ul>
            <el-input placeholder="Type a comment" />
          </div>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Timeline" name="fourth">
        <div class="block">
          <el-timeline>
            <el-timeline-item timestamp="2019/4/17" placement="top">
              <el-card>
                <h4>Update Github template</h4>
                <p>tuandm committed 2019/4/17 20:46</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="2019/4/18" placement="top">
              <el-card>
                <h4>Update Github template</h4>
                <p>tonynguyen committed 2019/4/18 20:46</p>
              </el-card>
              <el-card>
                <h4>Update Github template</h4>
                <p>tuandm committed 2019/4/19 21:16</p>
              </el-card>
            </el-timeline-item>
            <el-timeline-item timestamp="2019/4/19" placement="top">
              <el-card>
                <h4>
                  Deploy
                  <a href="https://laravue.dev" target="_blank">laravue.dev</a>
                </h4>
                <p>tuandm deployed 2019/4/19 10:23</p>
              </el-card>
            </el-timeline-item>
          </el-timeline>
        </div>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import Resource from '@/api/resource';
import ImageCropper from '@/components/ImageCropper';
import PanThumb from '@/components/PanThumb';
const userResource = new Resource('users');

export default {
  components: { ImageCropper, PanThumb },
  props: {
    user: {
      passwordType: '',
      passwordTypeConfirm: '',
      updating: false,
      type: Object,
      default: () => {
        return {
          name: '',
          firstname: '',
          surname: '',
          patronymic: '',
          skype: '',
          phone1: '',
          phone2: '',
          phone3: '',
          phone4: '',
          address1: '',
          address2: '',
          gender: '',
          family_status: '',
          birthday: '',
          email: '',
          email1: '',
          email2: '',
          password: null,
          confirmPassword: null,
          avatar: '',
          onlineStatus: null,
          roles: [],
        };
      },
    },
  },
  data() {
    return {
      activeProfile: 'first',
      carouselImages: [
        'https://cdn.laravue.dev/photo1.png',
        'https://cdn.laravue.dev/photo2.png',
        'https://cdn.laravue.dev/photo3.jpg',
        'https://cdn.laravue.dev/photo4.jpg',
      ],
      imagecropperShow: false,
      imagecropperKey: 0,
      Url: null,
      avatar: '',
      onlineStatus: '',
      params: {
        token: '3453gdSDFSDFWR4r34535323GHKKLSGD5q35234YUIYUTHDGSE',
        name: 'avatar',
      },
      headers: {
        smail: '*_~',
      },
      updating: false,
      avatarupdating: false,
      // genderVulue: ['male', 'female'],
      genderVulue: [
        { id: 1, value: 'male', name: 'Мужской' },
        { id: 2, value: 'female', name: 'Женский' },
      ],
      // familyStatusVulue: ['unmarried', 'married', 'divorced'],
      familyStatusVulue: [
        { id: 1, value: 'unmarried', name: 'Не женат/Не замужем' },
        { id: 2, value: 'married', name: 'Женат/Замужем' },
        { id: 3, value: 'divorced', name: 'Разведен/Разведена' },
      ],
      passwordType: 'password',
      passwordTypeConfirm: 'password',
      tab: {
        tab: null,
        icons: false,
        centered: true,
        grow: true,
        vertical: false,
        prevIcon: true,
        nextIcon: true,
        right: false,
      },
      search: '',
      headers_2: [
        {
          text: 'Dessert (100g serving)',
          align: 'left',
          filterable: false,
          value: 'name',
        },
        { text: 'Calories', value: 'calories' },
        { text: 'Fat (g)', value: 'fat' },
        { text: 'Carbs (g)', value: 'carbs' },
        { text: 'Protein (g)', value: 'protein' },
        { text: 'Iron (%)', value: 'iron' },
      ],
      desserts: [
        {
          name: 'Frozen Yogurt',
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0,
          iron: '1%',
        },
        {
          name: 'Ice cream sandwich',
          calories: 237,
          fat: 9.0,
          carbs: 37,
          protein: 4.3,
          iron: '1%',
        },
        {
          name: 'Eclair',
          calories: 262,
          fat: 16.0,
          carbs: 23,
          protein: 6.0,
          iron: '7%',
        },
        {
          name: 'Cupcake',
          calories: 305,
          fat: 3.7,
          carbs: 67,
          protein: 4.3,
          iron: '8%',
        },
        {
          name: 'Gingerbread',
          calories: 356,
          fat: 16.0,
          carbs: 49,
          protein: 3.9,
          iron: '16%',
        },
        {
          name: 'Jelly bean',
          calories: 375,
          fat: 0.0,
          carbs: 94,
          protein: 0.0,
          iron: '0%',
        },
        {
          name: 'Lollipop',
          calories: 392,
          fat: 0.2,
          carbs: 98,
          protein: 0,
          iron: '2%',
        },
        {
          name: 'Honeycomb',
          calories: 408,
          fat: 3.2,
          carbs: 87,
          protein: 6.5,
          iron: '45%',
        },
        {
          name: 'Donut',
          calories: 452,
          fat: 25.0,
          carbs: 51,
          protein: 4.9,
          iron: '22%',
        },
        {
          name: 'KitKat',
          calories: 518,
          fat: 26.0,
          carbs: 65,
          protein: 7,
          iron: '6%',
        },
      ],
    };
  },
  watch: {
    value(data) {
      if (data && this.avatarupdating !== 1) {
        this.reset();
      }
    },
    confirmPassword: 'checkPasswordsEquality',
    password: 'checkPasswordsEquality',
  },
  methods: {
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = '';
      } else {
        this.passwordType = 'password';
      }
      this.$nextTick(() => {
        this.$refs.password;
      });
    },
    showPwdConfirm() {
      if (this.passwordTypeConfirm === 'password') {
        this.passwordTypeConfirm = '';
      } else {
        this.passwordTypeConfirm = 'password';
      }
      this.$nextTick(() => {
        this.$refs.confirmPassword;
      });
    },
    checkPasswordsEquality() {
      const { password, confirmPassword } = this.user;
      const { repeatedPasswordEl } = this.$refs;

      if (password !== confirmPassword) {
        repeatedPasswordEl.setCustomValidity(
          'Пароли должны совпадать',
        );
      } else {
        repeatedPasswordEl.setCustomValidity('');
      }
    },
    handleClick(tab, event) {
      console.log('Переключение панели ', tab, event);
    },
    getRole() {
      const roles = this.user.roles.map(value => this.$options.filters.uppercaseFirst(value));
      return roles.join(' | ');
    },
    reset() {
      this.avatarupdating = false;
    },
    cropUploadSuccess(data) {
      const selfuser = this.user;
      this.imagecropperKey = this.imagecropperKey + 1;
      this.avatarupdating = true;
      selfuser.avatar = data.avatar;
      selfuser.avatar = data.avatar + '?' + new Date().getTime();
      userResource
        .avatarupload(selfuser.id, selfuser.avatar)
        .then(response => {
          this.$message({
            message: 'Аватарка пользователя: ' + selfuser.firstname + ' ' + selfuser.surname + ' ' + selfuser.patronymic + ' ' + 'id: ' + selfuser.id + ' успешно обновлена',
            type: 'success',
            duration: 5 * 2000,
          });
          console.log('-------- cropUploadSuccess --------');
          data.onlineStatus = this.user.onlineStatus;
          console.log(data);
          selfuser.avatar = data.avatar + '?' + new Date().getTime();
          this.close();
        })
        .catch(error => {
          this.imagecropperShow = true;
          console.log(error);
        });
      selfuser.avatar = data.avatar + '?' + new Date().getTime();
      this.avatarupdating = false;
    },
    cropUploadFail(status, field, key) {
      console.log('-------- cropUploadFail --------');
      console.log(status);
      console.log('field: ' + field);
      console.log('key: ' + key);
      this.imagecropperShow = true;
    },
    close() {
      this.imagecropperShow = false;
    },
    onSubmitProfile() {
      const selfuser = this.user;
      this.updating = true;
      userResource
        .update(selfuser.id, selfuser, selfuser.firstname, selfuser.surname, selfuser.patronymic, selfuser.gender, selfuser.familyStatusVulue, selfuser.birthday)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о пользователе успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
      selfuser.avatar = selfuser.avatar + '?' + new Date().getTime();
    },
    onSubmitAddress() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.address1)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о адресе 1 успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
      userResource
        .update(this.user.id, this.user, this.address2)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о адресе 2 успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmitEmail() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.email1)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о email №1 успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
      userResource
        .update(this.user.id, this.user, this.email2)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о email №2 успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmitSkype() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.skype)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о скайпе успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmitPhone() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.phone1, this.phone2, this.phone3, this.phone4)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о телефонах успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmitAuthenticator() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.name, this.password)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о пользователе успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmitPassword() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.name, this.email, this.roles, this.password)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о пользователе успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmit() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.name, this.firstname, this.surname, this.patronymic, this.address1, this.address2, this.birthday, this.password)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Информация о пользователе успешно обновлена',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
.user-profile {
  height: 200px;
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}

@media screen and (max-width: 1920px) {
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(320px, 24%) minmax(360px, 70%) minmax(260px, 0.3fr);
    grid-template-areas: "user-profile-main-left user-profile-main-middle user-profile-main-right"
  }
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 768px){
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(320px, 24%) minmax(320px, 50%);
    grid-template-rows: 1fr 1fr;
    grid-template-areas: "user-profile-main-left user-profile-main-middle"
                         "user-profile-main-right user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 2;
    grid-row: 1;
  }

  .flex-column.flex-column3 {
    grid-column: 1/3;
    grid-row: 2;
  }

  // Левая часть
  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    background: #dff0f6;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 15px;
    height: 100%;
  }

  // Середина часть
  .user-profile-grid-main-area2 {
    grid-area: user-profile-main-middle;
    //background: #ddd249;
    border: 1px solid rgba(188, 19, 30, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 15px;
    height: 100%;}

  // Правая часть
  .user-profile-grid-main-area3 {
    grid-area: user-profile-main-right;
    //background: #ddd249;
    border: 1px solid rgba(0, 188, 12, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 15px;
    height: 100%;}
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-height: 1366px) and (max-width: 768px){
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(320px, 20%) minmax(320px, 50%);
    grid-template-rows: 1fr 1fr;
    grid-template-areas: "user-profile-main-left user-profile-main-middle"
                         "user-profile-main-right user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 2;
    grid-row: 1;
  }

  .flex-column.flex-column3 {
    grid-column: 1/3;
    grid-row: 2;
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 7px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 12px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 1366px) and (max-height: 768px){
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(320px, 20%) minmax(390px, 70%) minmax(260px, 0.3fr);
    grid-template-rows: 1fr 1fr;
    grid-template-areas: "user-profile-main-left user-profile-main-middle user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 2;
    grid-row: 1;
  }

  .flex-column.flex-column3 {
    grid-column: 3;
    grid-row: 1;
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 7px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 12px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 1024px) and (max-height: 768px){
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }

  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 8px;
    grid-template-columns: minmax(250px, 21%) minmax(280px, 40%) minmax(250px, 0.3fr);
    grid-template-areas: "user-profile-main-left user-profile-main-middle user-profile-main-right"
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 7px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 12px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-height: 823px) and (max-width: 411px) {
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(390px, 24%);
    grid-template-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left" "user-profile-main-middle" "user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 1;
    grid-row: 2;
  }

  .flex-column.flex-column3 {
    grid-column: 1;
    grid-row: 3;
  }
}

@media screen and (max-width: 823px) and (max-height: 411px) {
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(390px, 24%) minmax(320px, 50%);
    grid-template-rows: 1fr 1fr;
    grid-template-areas: "user-profile-main-left user-profile-main-middle"
    "user-profile-main-right user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 2;
    grid-row: 1;
  }

  .flex-column.flex-column3 {
    grid-column: 1/3;
    grid-row: 2;
  }
}

@media screen and (max-height: 736px) and (max-width: 414px) {
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(390px, 24%);
    grid-template-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left" "user-profile-main-middle" "user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 1;
    grid-row: 2;
  }

  .flex-column.flex-column3 {
    grid-column: 1;
    grid-row: 3;
  }
}

@media screen and (max-height: 812px) and (max-width: 375px) {
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(360px, 24%);
    grid-template-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left" "user-profile-main-middle" "user-profile-main-right";
  }

  .flex-column.flex-column1 {
    grid-column: 1;
    grid-row: 1;
  }

  .flex-column.flex-column2 {
    grid-column: 1;
    grid-row: 2;
  }

  .flex-column.flex-column3 {
    grid-column: 1;
    grid-row: 3;
  }
}

@media screen and (max-width: 812px) and (max-height: 375px){
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }

  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 3px;
    grid-template-columns: minmax(225px, 21%) minmax(250px, 40%) minmax(240px, 0.3fr);
    grid-template-areas: "user-profile-main-left user-profile-main-middle user-profile-main-right"
  }

  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 2px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 2px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 375px){
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: minmax(230px, 100%);
    grid-auto-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left"
    "user-profile-main-middle"
    "user-profile-main-right";
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 2px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 2px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 736px) and (max-height: 414px){

  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(390px, 1fr);
    grid-template-areas: "user-profile-main-left"
                         "user-profile-main-middle"
                         "user-profile-main-right";
  }
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }

  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: minmax(390px, 1fr);
    grid-auto-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left"
                          "user-profile-main-middle"
                           "user-profile-main-right";
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 2px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 2px !important;
  }

  .avatar-pan-thumb {
    height: 260px;
    width: 260px;
  }
}

@media screen and (max-width: 414px){
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: minmax(230px, 100%);
    grid-auto-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left"
    "user-profile-main-middle"
    "user-profile-main-right";
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 2px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 2px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 667px) and (max-height: 375px){

  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 15px;
    grid-template-columns: minmax(320px, 1fr);
    grid-template-areas: "user-profile-main-left"
                         "user-profile-main-middle"
                         "user-profile-main-right";
  }
  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }

  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: minmax(230px, 100%);
    grid-auto-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left"
    "user-profile-main-middle"
    "user-profile-main-right";
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 2px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 2px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

@media screen and (max-width: 320px){
  // Делим страницу основного профиля на две части левую и правую
  .user-profile-grid-main {
    display: grid;
    grid-gap: 5px;
    grid-template-columns: minmax(230px, 100%);
    grid-auto-rows: 1fr 1fr 1fr;
    grid-template-areas: "user-profile-main-left"
                         "user-profile-main-middle"
                         "user-profile-main-right";
  }
  .user-profile {
    height: 100px;
    .user-name {
      font-weight: bold;
    }
    .box-center {
      padding-top: 2px;
    }
    .user-role {
      padding-top: 10px;
      font-weight: 400;
      font-size: 8px;
    }
    .box-social {
      padding-top: 10px;
      .el-table {
        border-top: 1px solid #dfe6ec;
      }
    }
    .user-follow {
      padding-top: 10px;
    }
  }

  .user-profile-grid-main-area1 {
    grid-area: user-profile-main-left;
    //background: #72aedd;
    border: 1px solid rgba(16, 8, 188, 0.3);
    border-radius: 5px 5px 5px 5px;
    //background: #1e88e5; /* Цвет фона */
    box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
    padding: 2px;
    height: 100%;
  }

  .user-profile-grid-avatar {
    display: grid;
    grid-gap: 1px;
  }

  .v-card__text, .v-card__title {
    padding: 2px !important;
  }

  .avatar-pan-thumb {
    height: 200px;
    width: 200px;
  }
}

// Делим страницу основного профиля на две части левую и правую
.user-profile-grid-main-guard-menu {
  display: grid;
  grid-gap: 15px;
  grid-template-columns: minmax(350px, 35%) 1fr minmax(250px, 0.3fr);
  grid-template-areas: "user-profile-main-left user-profile-main-middle user-profile-main-right";
}

// Левая часть
.user-profile-grid-main-area1 {
  grid-area: user-profile-main-left;
  //background: #72aedd;
  border: 1px solid rgba(16, 8, 188, 0.3);
  border-radius: 5px 5px 5px 5px;
  //background: #1e88e5; /* Цвет фона */
  box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
  padding: 15px;
  height: 100%;
}

// Середина часть
.user-profile-grid-main-area2 {
  grid-area: user-profile-main-middle;
  //background: #ddd249;
  border: 1px solid rgba(188, 19, 30, 0.3);
  border-radius: 5px 5px 5px 5px;
  //background: #1e88e5; /* Цвет фона */
  box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
  padding: 15px;
  height: 100%;}

// Правая часть
.user-profile-grid-main-area3 {
  grid-area: user-profile-main-right;
  //background: #ddd249;
  border: 1px solid rgba(0, 188, 12, 0.3);
  border-radius: 5px 5px 5px 5px;
  //background: #1e88e5; /* Цвет фона */
  box-shadow: 2px 10px 12px rgba(0,0,0,0.3); /* Параметры тени */
  padding: 15px;
  height: 100%;}

.user-profile-grid-avatar {
  display: grid;
  grid-gap: 5px;
  grid-template-columns: 1fr;
  grid-template-areas: "user-profile-grid-avatar-top"
                       "user-profile-grid-avatar-middle"
                       "user-profile-grid-avatar-bottom";
}

.user-profile-grid-guard-menu {
  display: grid;
  grid-gap: 5px;
  grid-template-columns: 2fr;
  grid-template-areas: "user-profile-grid-avatar-top"
  "user-profile-grid-avatar-middle"
  "user-profile-grid-avatar-bottom";
}

// Верхння часть блока автараки
.user-profile-grid-main-avatar-area1 {
  grid-area: user-profile-grid-avatar-top;
  //background: #9d0;
}

// Средняя часть блока автараки
.user-profile-grid-main-avatar-area2 {
  grid-area: user-profile-grid-avatar-middle;
  //background: #d90;
}

// Нижнняя часть блока автараки
.user-profile-grid-main-avatar-area3 {
  grid-area: user-profile-grid-avatar-bottom;
  //background: #dd3e56;
}

.user-profile-grid-parametrs {
  display: grid;
  grid-gap: 5px;
  grid-template-columns: 2fr;
  grid-template-areas: "user-profile-grid-parametrs-top"
                       "user-profile-grid-parametrs-middle-1"
                       "user-profile-grid-parametrs-middle-2"
                       "user-profile-grid-parametrs-bottom";
}

// Верхння часть блока данных
.user-profile-grid-main-parametrs-area1 {
  grid-area: user-profile-grid-parametrs-top;
  //background: #40c1dd;
}
// Средняя часть блока данных
.user-profile-grid-main-parametrs-area2-1 {
  grid-area: user-profile-grid-parametrs-middle-1;
  //background: #989edd;
}
// Средняя часть 2 блока данных
.user-profile-grid-main-parametrs-area2-2 {
  grid-area: user-profile-grid-parametrs-middle-2;
  //background: #989edd;
}
// Нижнняя часть блока данных
.user-profile-grid-main-parametrs-area3 {
  grid-area: user-profile-grid-parametrs-bottom;
  //background: #989edd;
}

.container-test {
  border: 5px solid rgb(111,41,97);
  border-radius: .5em;
  padding: 10px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 20px;
  column-count: 3;
}

.container-test > div {
  padding: 10px;
  background-color: rgba(111,41,97,.3);
  border: 2px solid rgba(111,41,97,.5);
}

.container-test > div:nth-child(3n+1) {
  grid-row-end: span 2;
  background-color: rgba(193,225,237,.3);
  border: 2px solid rgba(193,225,237,.5);
}
</style>
