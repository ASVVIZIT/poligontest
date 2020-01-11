<template>
  <div class="md">
    <h1>Форма отправления Email уведомлений</h1>
    <div class="md-inner">
      <div class="md-editor">
        <div class="form-group">
          <label>Email <small>(Отправить кому))</small>
            <input v-model="form.email_to" type="text" class="form-control">
          </label>
        </div>
        <div class="form-group">
          <label>Тема <small>(Заголовок)</small></label>
          <div class="form-lookup">
            <label>
              <input v-model="form.subject" type="text" class="form-control">
            </label>
            <ul class="form-list">
              <li v-for="(value, key) in activeSubjectOptions" :key="key" draggable="true" title="Для добавления маркера перенесите его в поле тема в нужное место" @dragstart="onDrag(key, $event)">
                <span>{{ value }}</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="form-group">
          <label>Сообщение <small>(С интегрированными метками)</small></label>
          <div class="form-lookup">
            <label>
              <textarea v-model="form.message" class="form-control" />
            </label>
            <ul class="form-list">
              <li v-for="(value, key) in activeMessageOptions" :key="key" draggable="true" @dragstart="onDrag(key, $event)">
                {{ value }}
              </li>
            </ul>
          </div>
        </div>
        <div class="md-btn">
          <button class="btn btn-success" @click="send">Отправить</button>
        </div>
      </div>
      <div class="md-preview">
        <div class="md-subject">{{ replacedSubject }}</div>
        <div class="md-content" v-html="compiledMarkdown" />
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import axios from 'axios';
import MarkdownIt from 'markdown-it';

const md = new MarkdownIt({
  linkify: true,
});

export default {
  props: {
    url: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      form: {
        email_to: '',
        subject: '',
        message: '',
      },
      options: {
        variables: {
          subject: {},
          message: {},
        },
        replaceable: {},
      },
    };
  },
  computed: {
    activeSubjectOptions() {
      const active = {};

      Object.keys(this.options.variables.subject).forEach((key) => {
        if (this.form.subject.indexOf(key) <= 0) {
          active[key] = this.options.variables.subject[key];
        }
      });

      return active;
    },
    activeMessageOptions() {
      const active = {};

      Object.keys(this.options.variables.message).forEach((key) => {
        if (this.form.message.indexOf(key) <= 0) {
          active[key] = this.options.variables.message[key];
        }
      });

      return active;
    },
    replacedSubject() {
      return this.replaceVariables(this.form.subject || '');
    },
    compiledMarkdown() {
      const input = this.replaceVariables(this.form.message || '');
      return md.render(input);
    },
  },
  mounted() {
    this.fetch();
  },
  methods: {
    replaceVariables(input) {
      const lookup = this.options.replaceable || {};
      let updated = input;

      Object.keys(lookup).forEach((key) => {
        updated = updated.replace(new RegExp(key, 'g'), lookup[key]);
      });
      return updated;
    },
    onDrag(value, e) {
      e.dataTransfer.setData('text/plain', value);
    },
    fetch() {
      axios.get(this.url)
        .then((res) => {
          Vue.set(this.$data, 'form', res.data.form);
          Vue.set(this.$data, 'options', res.data.options);
        });
    },
    send() {
      const $html = this.form;
      axios.post(this.url, $html)
        .then((res) => {
          this.$message({
            message: 'Письмо успешно отправлено',
            type: 'success',
          });
        });
    },
  },
};
</script>
