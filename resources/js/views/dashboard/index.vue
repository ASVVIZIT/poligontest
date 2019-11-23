<template>
  <div class="dashboard-container">
    <component :is="currentRole" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import adminDashboard from './admin';
import moderatorDashboard from './moderator';
import editorDashboard from './editor';

export default {
  name: 'Dashboard',
  components: { adminDashboard, moderatorDashboard, editorDashboard },
  data() {
    return {
      currentRole: 'adminDashboard',
    };
  },
  computed: {
    ...mapGetters([
      'roles',
    ]),
  },
  created() {
    if (!this.roles.includes('admin')) {
      this.currentRole = 'editorDashboard';
    }
    if (this.roles.includes('moderator')) {
      this.currentRole = 'moderatorDashboard';
    }
  },
};
</script>
