<template>
  <el-select v-model="ModelValue2" value-key="value" placeholder="Пожалуйста, выберите исполнителя заказа" style="width: 400px">
    <template slot="prefix">
      <div v-if="value !== ''">
        <img class="prefix" :src="ModelValue2.photo">
      </div>
    </template>
    <el-option v-for="item in select_2_id" :key="item.ModelValue2" :label="item.label" :value="item">
      <div>
        <img v-if="item.photo !== ''" :src="item.photo">
        <span>
          {{ item.label }}
        </span>
      </div>
    </el-option>
  </el-select>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
      default: 0,
      type: Number,
    },
    options: {
      required: true,
      default: () => [],
      type: Array,
    },
  },
  data() {
    return {
    };
  },
  computed: {
    ModelValue2: {
      get() {
        return this.getOrder(this.value);
      },
      set(val) {
        this.$emit('input', val);
      },
    },
  },
  created() {
    this.ModelValue = this.select_2_id[0];
  },
};
</script>

<style scoped>
img {
  width: 20px;
  height: 20px;
}

.prefix {
  margin-top: 10px;
  top: 5px;
}
</style>
