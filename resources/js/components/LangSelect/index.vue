<template>
  <el-dropdown trigger="click" class="international" @command="handleSetLanguage">
    <div>
      <svg-icon class-name="international-icon" icon-class="language" />
    </div>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item :disabled="language==='vi'" command="vi">
        Tiếng Việt
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='ru'" command="ru">
        Русский
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='en'" command="en">
        English
      </el-dropdown-item>
      <el-dropdown-item :disabled="language==='zh'" command="zh">
        中文
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import local from './local';
const viewName = 'i18Login';

export default {
  name: 'I18n',
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  watch: {
    lang() {
      this.setOptions();
    },
  },
  created() {
    if (!this.$i18n.getLocaleMessage('ru')[viewName]) {
      this.$i18n.mergeLocaleMessage('ru', local.ru);
      this.$i18n.mergeLocaleMessage('en', local.en);
      this.$i18n.mergeLocaleMessage('zh', local.zh);
      this.$i18n.mergeLocaleMessage('vi', local.vi);
    }
  },
  methods: {
    handleSetLanguage(lang) {
      this.$i18n.locale = lang;
      this.$store.dispatch('app/setLanguage', lang);
      this.$message({
        message: ''.concat(this.$t('i18Login.title'),
          ' ',
          this.$t('i18Login.success'),
          ' ',
          this.$t('i18Login.language'),
        ),
        type: 'success',
      });
    },
  },
};
</script>

<style scoped>
.international-icon {
  font-size: 20px;
  cursor: pointer;
  vertical-align: -5px!important;
}
</style>

