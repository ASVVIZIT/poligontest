<template>
  <div class="content">
    <div class="content-inner">
      <compose-email :url="url" :html="html" />
    </div>
  </div>
</template>

<script>
import ComposeEmail from '../components/ComposeEmail/ComposeEmail';

export default {
  name: 'Email',
  components: { ComposeEmail },
  data() {
    return {
      url: '/api/email/create',
      html: this.html,
    };
  },
};
</script>

<style lang="scss" scoped>
  .content {
    font-family: helvetica;
    width: 90%;
    margin: 0 auto;
    padding: 20px 50px;
    //background: #f2f2f2;
  }

  .content-inner {
    font-size: 16px;
    line-height: 20px;
    padding: 5px;
    background: #fff;
    -webkit-box-shadow: 3px 3px 13px 4px rgba(106,136,156,1);
    -moz-box-shadow: 3px 3px 13px 4px rgba(106,136,156,1);
    box-shadow: 3px 3px 13px 4px rgba(106,136,156,1);
  }

  h1 {
    text-align: center;
    margin-bottom: 50px;
  }

  img {
    display: block;
    width: 35%;
    margin: 0 auto;
  }
</style>
