<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header">
        <a class="link-type link-title" target="_blank" href="https://doc.laravue.dev/guide/advanced/theme.html">
          {{ $t('theme.documentation') }}
        </a>
      </div>
      <div class="box-item">
        <span class="field-label">{{ $t('theme.change') }} : </span>
        <el-switch v-model="theme" />
        <code style="margin-top:15px;">{{ $t('theme.tips') }}</code>
      </div>
    </el-card>

    <div class="block">
      <el-button type="primary">
        {{ $t('i18nTheme.primary') }}
      </el-button>
      <el-button type="success">
        {{ $t('i18nTheme.success') }}
      </el-button>
      <el-button type="info">
        {{ $t('i18nTheme.info') }}
      </el-button>
      <el-button type="warning">
        {{ $t('i18nTheme.warning') }}
      </el-button>
      <el-button type="danger">
        {{ $t('i18nTheme.danger') }}
      </el-button>
    </div>

    <div class="block">
      <el-button type="primary" icon="el-icon-edit" />
      <el-button type="primary" icon="el-icon-share" />
      <el-button type="primary" icon="el-icon-delete" />
      <el-button type="primary" icon="el-icon-search">
        {{ $t('i18nTheme.search') }}
      </el-button>
      <el-button type="primary">
        {{ $t('i18nTheme.upload') }}
        <i class="el-icon-upload el-icon-right" />
      </el-button>
    </div>

    <div class="block">
      <el-tag
        v-for="tag in tags"
        :key="tag.type"
        :type="tag.type"
        class="tag-item"
        :label="tag.label"
        :value="tag.value"
      >
        {{ tag.value }} {{ tag.label }}
      </el-tag>

      <el-radio-group v-model="radio">
        <el-radio :label="3">
          {{ $t('i18nTheme.option') }} A
        </el-radio>
        <el-radio :label="6">
          {{ $t('i18nTheme.option') }} B
        </el-radio>
        <el-radio :label="9">
          {{ $t('i18nTheme.option') }} C
        </el-radio>
      </el-radio-group>
    </div>

    <div class="block">
      <el-slider v-model="slideValue" />
    </div>
  </div>
</template>

<script>
const viewName = 'i18nTheme';
import local from './local';
import { toggleClass } from '@/utils';
import '@/assets/custom-theme/index.css'; // the theme changed version element-ui css

export default {
  name: 'Theme',
  data() {
    return {
      theme: false,
      tags: [],
      value: '',
      slideValue: 50,
      radio: 3,
    };
  },
  computed: {
    lang: {
      get() {
        return this.$store.state.app.language;
      },
      set(lang) {
        this.$i18n.locale = lang;
        this.$store.dispatch('app/setLanguage', lang);
      },
    },
  },
  watch: {
    theme() {
      toggleClass(document.body, 'custom-theme');
    },
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
    this.setOptions(); // set default select options
  },
  methods: {
    setOptions() {
      this.tags = [
        {
          value: '1',
          label: 'Tag '.concat(this.$t('i18nTheme.one')),
        },
        {
          value: '2',
          label: 'Tag '.concat(this.$t('i18nTheme.two')),
        },
        {
          value: '3',
          label: 'Tag '.concat(this.$t('i18nTheme.three')),
        },
        {
          value: '4',
          label: 'Tag '.concat(this.$t('i18nTheme.four')),
        },
      ];
    },
  },
};
</script>

<style scoped>
.field-label{
  vertical-align: middle;
}

.box-card {
  width: 400px;
  max-width: 100%;
  margin: 20px auto;
}

.block {
  padding: 30px 24px;
}

.tag-item {
  margin-right: 15px;
}
</style>
