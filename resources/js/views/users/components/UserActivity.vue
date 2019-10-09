<template>
  <el-card v-if="user.name">
    <el-tabs v-model="activeActivity" @tab-click="handleClick">
      <el-tab-pane v-loading="updating" label="Профиль" name="first">
        <v-app id="inspire">
          <div>
            <!--   <v-row

              justify="space-around"
            >
              <v-switch
                v-model="tab.icons"
                class="mx-2"
                label="Text + icons"
              ></v-switch>
              <v-switch
                v-model="tab.centered"
                class="mx-2"
                label="Centered"
                :disabled="tab.vertical"
              ></v-switch>
              <v-switch
                v-model="grow"
                class="mx-2"
                label="Grow"
              ></v-switch>
              <v-switch
                v-model="tab.vertical"
                class="mx-2"
                label="Vertical"
              ></v-switch>
              <v-switch
                v-model="tab.right"
                class="mx-2"
                label="Right"
              ></v-switch>
              <v-col cols="12">
                <v-slider
                  v-model="tab.tabs"
                  min="0"
                  max="10"
                  label="Tabs number"
                ></v-slider>
              </v-col>
            </v-row> -->
            <v-toolbar flat color="primary" dark>
              <v-toolbar-title>Данные пользователея</v-toolbar-title>
            </v-toolbar>
            <v-tabs
              v-model="tab"
              background-color="blue darken-1"
              class="elevation-1"
              dark
              :centered="tab.centered"
              :grow="tab.grow"
              :vertical="tab.vertical"
              :right="tab.right"
              :left="tab.left"
              :prev-icon="tab.prevIcon ? 'mdi-arrow-left-bold-box-outline' : undefined"
              :next-icon="tab.nextIcon ? 'mdi-arrow-right-bold-box-outline' : undefined"
              :icons-and-text="tab.icons"
            >
              <v-tabs-slider />
              <v-tab>
                <v-icon :key="1">mdi-account</v-icon>
                Профиль
              </v-tab>
              <v-tab>
                <v-icon :key="2">mdi-lock</v-icon>
                Безопасность
              </v-tab>
              <v-tab>
                <v-icon :key="3">mdi-city-variant-outline</v-icon>
                Сброс пароля
              </v-tab>
              <v-tab>
                <v-icon :key="4">mdi-city-variant-outline</v-icon>
                Адрес
              </v-tab>
              <v-tab>
                <v-icon :key="5">mdi-city-variant-outline</v-icon>
                Телефон
              </v-tab>
              <v-tab>
                <v-icon :key="6">mdi-settings-outline</v-icon>
                Настройки
              </v-tab>
              <v-tab-item>
                <v-card>
                  <v-card-text>
                    <el-form-item label="Имя">
                      <el-input v-model="user.firstname" :disabled="user.role === 'admin'" />
                    </el-form-item>
                    <el-form-item label="Фамилия">
                      <el-input v-model="user.surname" :disabled="user.role === 'admin'" />
                    </el-form-item>
                    <el-form-item label="Отчество">
                      <el-input v-model="user.patronymic" :disabled="user.role === 'admin'" />
                    </el-form-item>
                    <el-form-item label="День рождения">
                      <el-date-picker
                        v-model="user.birthday"
                        :type="date"
                        format="dd.MM.yyyy"
                        value-format="yyyy-MM-dd"
                        placeholder="Дата дня рождения"
                        :disabled="user.role === 'admin'"
                      />
                    </el-form-item>
                  </v-card-text>
                  <v-card-text>
                    <el-form-item>
                      <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmitProfile">
                        Обновить профиль
                      </el-button>
                    </el-form-item>
                  </v-card-text>
                </v-card>
              </v-tab-item>
              <v-tab-item>
                <v-card>
                  <v-card-text>
                    <el-form-item label="Полное имя Ф.И.О.">
                      <el-input v-model="user.name" :disabled="user.role === 'admin'" />
                    </el-form-item>
                    <el-form-item label="Имя">
                      <el-input v-model="user.firstname" :disabled="user.role === 'admin'" />
                    </el-form-item>
                    <el-form-item label="Email">
                      <el-input v-model="user.email" :disabled="user.role === 'admin'" />
                    </el-form-item>
                    <el-form-item label="Пароль">
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
                        :disabled="user.role === 'admin'"
                      />
                    </el-form-item>
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
              <v-tab-item>
                <v-card>
                  <v-card-text>
                    <el-form-item label="Пароль">
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
              <v-tab-item>
                <v-card>
                  <el-form-item>
                    <p>Внимание!!! данный раздел в разработке</p>
                    <v-card-text>
                      тут будут поля для адресов
                    </v-card-text>
                  </el-form-item>
                </v-card>
              </v-tab-item>
              <v-tab-item>
                <v-card>
                  <p>Внимание!!! данный раздел в разработке</p>
                  <v-card-text>
                    тут будут поля для телефонов
                  </v-card-text>
                </v-card>
              </v-tab-item>
              <v-tab-item>
                <v-card>
                  <p>Внимание!!! данный раздел в разработке</p>
                  <v-card-text>
                    тут будут поля для других настроек пользователя
                  </v-card-text>
                </v-card>
              </v-tab-item>
            </v-tabs>
          </div>
        </v-app>
      </el-tab-pane>
      <el-tab-pane label="Activity" name="second">
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
      <el-tab-pane label="Timeline" name="third">
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
const userResource = new Resource('users');

export default {
  props: {
    user: {
      passwordType: '',
      updating: false,
      type: Object,
      default: () => {
        return {
          name: '',
          firstname: '',
          surname: '',
          patronymic: '',
          birthday: '',
          email: '',
          password: '',
          avatar: '',
          roles: [],
        };
      },
    },
  },
  data() {
    return {
      activeActivity: 'first',
      carouselImages: [
        'https://cdn.laravue.dev/photo1.png',
        'https://cdn.laravue.dev/photo2.png',
        'https://cdn.laravue.dev/photo3.jpg',
        'https://cdn.laravue.dev/photo4.jpg',
      ],
      updating: false,
      passwordType: 'password',
      tab: {
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        icons: true,
        centered: false,
        grow: true,
        vertical: true,
        prevIcon: false,
        nextIcon: false,
        right: false,
        left: false,
      },
    };
  },
  methods: {
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = '';
      } else {
        this.passwordType = 'password';
      }
      this.$nextTick(() => {
        this.$refs.password.focus();
      });
    },
    handleClick(tab, event) {
      console.log('Switching tab ', tab, event);
    },
    onSubmitProfile() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.firstname, this.surname, this.patronymic, this.birthday)
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
    onSubmitAuthenticator() {
      this.updating = true;
      userResource
        .update(this.user.id, this.user, this.password)
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
        .update(this.user.id, this.user, this.password)
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
        .update(this.user.id, this.user, this.firstname, this.surname, this.patronymic, this.birthday, this.password)
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
</style>
