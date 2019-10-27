<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-width="310px" style="padding-right: 50px">
      <el-header style="padding-left: 50px">
        <el-link><h2>Пример заголовка формы заполнения</h2></el-link>
      </el-header>
      <el-form-item label="Название деятельности">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Зона деятельности" width="500px">
        <el-col :span="5">
          <el-select v-model="form.region" placeholder="Пожалуйста, выберите вашу зону" style="width: 100%; padding-right: 10px">
            <el-option label="Зона первая Shanghai" value="Shanghai" />
            <el-option label="Зона вторая Beijing" value="Beijing" />
            <el-option label="Зона третья Russia" value="Russia" />
          </el-select>
        </el-col>
        <el-marker style="padding-right: 12px;"> Вы выбрали </el-marker>
        <el-tag>
          <div v-if="form.region === ''"> Зона не выбрана </div>
          <div v-else> {{ form.region }} </div>
        </el-tag>
      </el-form-item>
      <el-form-item label="Время активности">
        <el-col :span="5">
          <el-date-picker v-model="form.date1" type="date" placeholder="Выбрать дату" style="width: 100%;" />
        </el-col>
        <el-col :span="1" class="line">
          —
        </el-col>
        <el-col :span="3">
          <el-time-picker v-model="form.date2" type="fixed-time" placeholder="Выберите время" style="width: 100%;" />
        </el-col>
      </el-form-item>
      <el-form-item label="Мгновенная доставка">
        <el-switch v-model="form.delivery" />
        <el-marker style="padding-right: 12px;"> Вы выбрали </el-marker>
        <el-tag v-model="form.delivery">
          <div v-if="form.delivery === true"> Доставка: выбрана</div>
          <div v-else> Доставка: не выбрана </div>
        </el-tag>
      </el-form-item>
      <el-form-item label="Тип деятельности">
        <el-checkbox-group v-model="form.type">
          <el-checkbox :id="form.typechecked[0].id" v-model="form.type[0]" label="Онлайн деятельность" :value="form.typechecked[0].value" :checked="form.typechecked[0].checked" />
          <el-checkbox :id="form.typechecked[1].id" v-model="form.type[1]" label="Рекламная деятельность" :value="form.typechecked[1].value" :checked="form.typechecked[1].checked" />
          <el-checkbox :id="form.typechecked[2].id" v-model="form.type[2]" label="Оффлайн деятельность" :value="form.typechecked[2].value" :checked="form.typechecked[2].checked" />
          <el-checkbox :id="form.typechecked[3].id" v-model="form.type[3]" label="Простое знакомство с брендом" :value="form.typechecked[3].value" :checked="form.typechecked[3].checked" />
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="Выбранный вами тип деятельности">
        <el-row>
          <el-tag v-if="form.type[0] === 'Онлайн деятельность'" v-model="form.type">
            <div v-if="form.typechecked[0].id === 0">
              <div v-if="form.type[0] !== 'Онлайн деятельность'">
                {{ form.type[0] }}
              </div>
              <div v-else> не выбранно {{ form.typechecked[0].id }} </div>
            </div>
            <div v-else> - {{ form.type[0] }} </div>
          </el-tag>
          <el-tag v-if="form.type[1] === 'Рекламная деятельность'" v-model="form.type">
            <div v-if="form.typechecked[1].id === 1">
              <div v-if="form.type[1] !== 'Рекламная деятельность'">
                {{ form.type[1] }}
              </div>
              <div v-else> не выбранно {{ form.typechecked[1].id }} </div>
            </div>
            <div v-else> - {{ form.type[1] }} </div>
          </el-tag>
          <el-tag v-if="form.type[2] == 'Оффлайн деятельность'" v-model="form.type">
            <div v-if="form.typechecked[2].id === 2">
              <div v-if="form.type[2] !== 'Оффлайн деятельность'">
                не выбранно {{ form.typechecked[2].id }}
              </div>
              <div v-else> {{ form.type[2] }} </div>
            </div>
            <div v-else> - {{ form.type[2] }} </div>
          </el-tag>
          <el-tag v-if="form.type[3] === 'Простое знакомство с брендом'" v-model="form.type">
            <div v-if="form.typechecked[3].id === 3">
              <div v-if="form.type[3] !== 'Простое знакомство с брендом'">
                {{ form.type[3] }}
              </div>
              <div v-else> не выбранно {{ form.typechecked[3].id }} </div>
            </div>
            <div v-else> - {{ form.type[3] }} </div>
          </el-tag>
        </el-row>
      </el-form-item>
      <el-form-item label="Ресурсы">
        <el-container style="padding: 12px">
          <el-header width="300px">
            <!-- Header content -->
            <b>Тут можно написать заголовок</b>
          </el-header>
          <el-aside width="300px" style="padding: 12px; align-items: center; justify-items: center">
            <!-- Aside content -->
            <el-radio-group v-model="form.resource">
              <el-radio label="Спонсор" />
              <el-radio label="Место встречи" />
            </el-radio-group>
          </el-aside>
          <el-main height="300px">
            <el-marker style="padding-right: 12px;"> Вы выбрали </el-marker>
            <el-tag style="text-align: center">
              <div v-if="form.resource === ''"> Ресурсы не выбраны </div>
              <div v-else> {{ form.resource }} </div>
            </el-tag>
          </el-main>
        </el-container>
      </el-form-item>
      <el-form-item label="Форма деятельности">
        <el-input v-model="form.desc" type="textarea" :autosize="true" placeholder="Введите ваш текст для описания данной деятельности" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">
          Создать
        </el-button>
        <el-button @click="onCancel">
          Отмена
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: 'Тестовое название деятельности введено для примера',
        region: '',
        date1: '',
        date2: '',
        delivery: false,
        typechecked: [
          {
            id: 0,
            checked: false,
            value: 'Онлайн деятельность',
          },
          {
            id: 1,
            checked: false,
            value: 'Рекламная деятельность',
          },
          {
            id: 2,
            checked: true,
            value: 'Оффлайн деятельность',
          },
          {
            id: 3,
            checked: false,
            value: 'Простое знакомство с брендом',
          },
        ],
        type: [],
        resource: '',
        desc: 'Текст для примера описания деятельности',
      },
    };
  },
  methods: {
    onSubmit() {
      this.$message('submit!');
    },
    onCancel() {
      this.$message({
        message: 'cancel!',
        type: 'warning',
      });
    },
    change() {
      this.type.checked = !this.type.checked;
    },
  },
};
</script>

<style scoped>
.line{
  text-align: center;
}
</style>

