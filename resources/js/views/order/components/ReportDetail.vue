<template>
  <div class="createPost-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container" label-position="top">
      <sticky :class-name="'sub-navbar '+postForm.status">
        <el-row>
          <el-col :span="6">
            <div hidden>1</div>
          </el-col>
          <el-col :span="7">
            <div hidden>2</div>
          </el-col>
          <el-col :span="6">
            <el-dropdown trigger="click">
              <el-button plain>
                Дополнительно
                <i class="el-icon-caret-bottom el-icon--right" />
              </el-button>
              <el-dropdown-menu slot="dropdown" class="no-padding no-border" style="align-items: center; width: 500px">
                <CommentDropdown v-model="postForm.comment_disabled" />
                <PlatformDropdown v-model="postForm.platforms" />
                <SourceUrlDropdown v-model="postForm.source_uri" />
              </el-dropdown-menu>
            </el-dropdown>
          </el-col>
          <el-col :span="5" style="padding-right: 50px">
            <el-button
              v-loading="loading"
              style="margin-left: 10px;"
              type="success"
              @click="submitForm"
            >
              Сохранить отчёт
            </el-button>
            <el-button v-loading="loading" type="warning" @click="draftForm">
              Черновик
            </el-button>
          </el-col>
        </el-row>
      </sticky>

      <div class="createPost-main-container">
        <el-row>
          <el-col :span="24">
            <el-form-item style="margin-bottom: 40px;" prop="Заголовок">
              <MDinput v-model="postForm.title" :maxlength="100" name="name" required>
                Заголовок
              </MDinput>
            </el-form-item>

            <div class="postInfo-container">
              <el-row>
                <el-col :span="6">
                  <el-form-item label-width="190px" label="Пользователь:" class="postInfo-container-item">
                    <el-select
                      v-model="postForm.user_id"
                      :remote-method="getRemoteUserList"
                      filterable
                      remote
                      placeholder="Поиск пользователя"
                    >
                      <el-option
                        v-for="(item,index) in userListOptions"
                        :key="item+index"
                        :label="item"
                        :value="item"
                      />
                    </el-select>
                  </el-form-item>
                </el-col>

                <el-col :span="7">
                  <el-form-item
                    label-width="185px"
                    label="Дата создания отчёта:"
                    class="postInfo-container-item"
                  >
                    <el-date-picker
                      v-model="postForm.created_at"
                      type="datetime"
                      format="yyyy-MM-dd HH:mm:ss"
                      placeholder="Выбирите дату и время"
                    />
                  </el-form-item>
                </el-col>

                <el-col :span="5">
                  <el-form-item
                    label-width="120px"
                    label="Статус отчёта:"
                    class="postInfo-container-item"
                  >
                    <el-rate
                      v-model="postForm.status"
                      :max="5"
                      :colors="['#99A9BF', '#F7BA2A', '#FF9900']"
                      :low-threshold="1"
                      :high-threshold="5"
                      style="margin-top:8px;"
                    />
                  </el-form-item>
                </el-col>
              </el-row>
            </div>
          </el-col>
        </el-row>

        <el-form-item style="margin-bottom: 40px;" label-width="190px" label="Краткое описание:">
          <el-input
            v-model="postForm.content_short"
            :rows="5"
            type="textarea"
            class="article-textarea"
            autosize
            placeholder="Пожайлуста введите содержание"
          />
          <span v-show="contentShortLength" class="word-counter">{{ contentShortLength }} Введено символов</span>
        </el-form-item>

        <el-form-item prop="content" style="margin-bottom: 30px;" label-width="190px" label="Полное описание:">
          <Tinymce ref="editor" v-model="postForm.content" :height="600" />
        </el-form-item>

        <el-form-item prop="image_uri" style="margin-bottom: 30px;" label-width="190px" label="Загрузка картинки:">
          <Upload v-model="postForm.image_uri" :height="600" />
        </el-form-item>
      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Upload from '@/components/Upload/SingleImage';
import MDinput from '@/components/MDinput';
import Sticky from '@/components/Sticky'; // Sticky header
import { validURL } from '@/utils/validate';
import { fetchReport } from '@/api/report';
import { userSearch } from '@/api/search';
import {
  CommentDropdown,
  PlatformDropdown,
  SourceUrlDropdown,
} from './Dropdown';
import { mapState } from 'vuex';

const defaultForm = {
  status: '0',
  title: '',
  created_at: new Date(),
  content: '',
  content_short: '',
  source_uri: '',
  image_uri: '',
  display_time: undefined,
  id: undefined,
  platforms: ['a-platform'],
  comment_disabled: false,
  importance: 0,
};

export default {
  name: 'ReportDetail',
  components: {
    Tinymce,
    MDinput,
    Upload,
    Sticky,
    CommentDropdown,
    PlatformDropdown,
    SourceUrlDropdown,
  },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        this.$message({
          message: rule.field + ' is required',
          type: 'error',
        });
        callback(new Error(rule.field + ' is required'));
      } else {
        callback();
      }
    };
    const validateSourceUri = (rule, value, callback) => {
      if (value) {
        if (validURL(value)) {
          callback();
        } else {
          this.$message({
            message: 'External URL is invalid.',
            type: 'error',
          });
          callback(new Error('External URL is invalid.'));
        }
      } else {
        callback();
      }
    };
    return {
      postForm: Object.assign({}, defaultForm),
      loading: false,
      userListOptions: [],
      rules: {
        image_uri: [{ validator: validateRequire }],
        title: [{ validator: validateRequire }],
        content: [{ validator: validateRequire }],
        source_uri: [{ validator: validateSourceUri, trigger: 'blur' }],
      },
      tempRoute: {},
    };
  },
  computed: {
    ...mapState(
      {
        orderersData: state => state.orderers.data,
        executorsData: state => state.executors.data,
      }
    ),
    contentShortLength() {
      return this.postForm.content_short.length;
    },
    lang() {
      return this.$store.getters.language;
    },
  },
  created() {
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
      this.fetchReport(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
    }

    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      fetchReport(id)
        .then(response => {
          this.postForm = response.data;
          // Just for test
          this.postForm.title += `   Отчёт Id:${this.postForm.id}`;
          this.postForm.content_short += `   Отчёт Id:${this.postForm.id}`;

          // Set tagsview title
          this.setTagsViewTitle();
        })
        .catch(err => {
          console.log(err);
        });
    },
    setTagsViewTitle() {
      const title =
        this.lang === 'ru'
          ? 'Изменить отчёт'
          : this.lang === 'vi'
            ? 'Chỉnh sửa'
            : 'Edit report'; // Should move to i18n
      const route = Object.assign({}, this.tempRoute, {
        title: `${title}-${this.postForm.id}`,
      });
      this.$store.dispatch('updateVisitedView', route);
    },
    submitForm() {
      this.postForm.display_time = parseInt(this.display_time / 2000);
      console.log(this.postForm);
      this.$refs.postForm.validate(valid => {
        if (valid) {
          this.loading = true;
          this.$notify({
            title: 'Success',
            message: 'Отчёт has been published successfully',
            type: 'success',
            duration: 2000,
          });
          this.postForm.status = 'published';
          this.loading = false;
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    draftForm() {
      if (
        this.postForm.content.length === 0 ||
        this.postForm.title.length === 0
      ) {
        this.$message({
          message: 'Пожалуйста, введите необходимый заголовок и содержание',
          type: 'warning',
        });
        return;
      }
      this.$message({
        message: 'Успешно сохранено',
        type: 'success',
        showClose: true,
        duration: 1000,
      });
      this.postForm.status = '0';
    },
    getRemoteUserList(query) {
      userSearch(query).then(response => {
        if (!response.data.items) {
          return;
        }
        this.userListOptions = response.data.items.map(v => v.name);
      });
    },
  },
};
</script>

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
.createPost-container label.el-form-item__label {
  text-align: left;
}
</style>
