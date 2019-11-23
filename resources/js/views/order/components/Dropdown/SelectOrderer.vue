<template>
  <el-select v-model="ModelValue" value-key="orderer_id" placeholder="Пожалуйста, выберите заказчика" style="width: 320px;">
    <template slot="prefix">
      <div v-show="ModelValue.photo !== ''" style="padding-right: 12px" class="parent__selector">
        <el-tag class="child__selector prefix tag_prefix_selector"> ID {{ ModelValue.orderer_id }} </el-tag>
        <img class="child__selector prefix" :src="ModelValue.photo">
      </div>
    </template>
    <el-option v-for="(item, index) in options" :key="index" :label="item.label" :value="item" style="padding-left: 2px;">
      <div class="parent__option__selector">
        <el-tag v-show="item.orderer_id !== '0'" class="child__option__selector tag_selector"> ID {{ item.orderer_id }} </el-tag>
        <img v-if="item.photo !== ''" :id="item.orderer_id" :src="item.photo" :label="item.label" class="child__option__selector">
        <a :value="item.value" class="child__option__selector">{{ item.label }}</a>
      </div>
    </el-option>
  </el-select>
</template>

<script>
import { mapGetters } from 'vuex';

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
    ...mapGetters({
      getOrderers: 'orderers/getById',
    }),
    ModelValue: {
      get() {
        if (!this.value){
          return '';
        }
        return this.getOrderers(this.value);
      },
      set(val) {
        console.log(val);
        this.$emit('input', Number(val.orderer_id));
      },
    },
  },
  created() {
    //
  },
};
</script>

<style scoped>
img {
  width: 26px;
  height: 26px;
  margin-top: 5px;
}
.el-select > .el-input {
  padding-left: 40px !important;
}

.prefix {
  margin-top: 10px;
  top: 0;
}
.tag_selector {
  left: 30px;
}
.tag_prefix_selector {
  left: 20px;
}

a {
  top: 5;
  left: 80px;
  padding-left: 0px;
  line-height: 30px;
}

.parent__selector
{
  position: relative;
  border: 0px solid black;
}
.child__selector
{
  position: absolute;
  display: inline-block;
}
.inner {
  left: 80px;
}
.parent__option__selector
{
  position: relative;
  border: 0px solid black;
}
.child__option__selector
{
  position: absolute;
  display: inline-block;
}

</style>
