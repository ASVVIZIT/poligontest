<template>
  <report-detail :is-edit="false" />
</template>

<script>
import ReportDetail from '../../components/ReportDetail';

export default {
  name: 'CreateForm',
  components: { ReportDetail },
};
</script>

