<template>
  <div class="app-container">
    <el-form v-if="user" :model="user">
      <el-row :gutter="25">
        <el-col :span="23">
          <user-profile :user="user" />
        </el-col>
      <!--  <el-col :span="6">
          <user-card :user="user" />
          <user-bio />
        </el-col> -->
      </el-row>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import UserProfile from './components/UserProfile';

const userResource = new Resource('users');
export default {
  name: 'EditUser',
  components: { UserProfile },
  data() {
    return {
      user: {
        onlineStatus: '',
      },
    };
  },
  watch: {
    '$route': 'getUser',
  },
  created() {
    const id = this.$route.params && this.$route.params.id;
    this.getUser(id);
  },
  methods: {
    async getUser(id) {
      const { data } = await userResource.get(id);
      this.user = data;
    },
  },
};
</script>
