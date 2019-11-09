<template>
  <div class="app-container">
    <el-form ref="formReport" :model="form" label-width="310px" style="padding-right: 50px">
      <el-header style="padding-left: 50px">
        <el-link><h2>Создание Отчёт ID 003423423423</h2></el-link>
      </el-header>
      <el-form-item label="Тип отчёта">
        <el-container style="padding: 12px">
          <el-aside width="200px" style="padding: 12px; align-items: center; justify-items: center">
            <!-- Aside content -->
            <el-radio-group v-model="form.resource">
              <el-radio label="Отчёт" />
              <el-radio label="Монтаж" />
              <el-radio label="Диагностика" />
              <el-radio label="Профилактика" />
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
      <el-form-item label="Краткое описание отчёта">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Дата и время создания отчёта">
        <el-col :span="8">
          <el-date-picker v-model="form.date1.selectedDate" type="date" :default-value="form.date1.defaultDate" placeholder="Выбирите дату отчёта" style="width: 100%;" />
          <el-tag style="text-align: center">
            {{ form.date1.defaultDate }}
          </el-tag>
          <el-tag style="text-align: center">
            {{ form.date1.selectedDate }}
          </el-tag>
        </el-col>
        <el-col :span="1" class="line">
          —
        </el-col>
        <el-col :span="6">
          <el-time-picker v-model="form.date1time.selectedTime" type="fixed-time" :default-value="form.date1time.defaultTime" placeholder="Выбирите время отчёта" style="width: 100%;" />
          <el-tag style="text-align: center">
            {{ form.date1time.selectedTime }}
          </el-tag>
          <el-tag style="text-align: center">
            {{ form.date1time.defaultTime }}
          </el-tag>
        </el-col>
      </el-form-item>
      <el-form-item label="Выбор заказчика и исполнителя" width="500px">
        <el-col :span="8">
          <el-select v-model="form.ModelValue" value-key="value" placeholder="Пожалуйста, выберите заказчика" style="width: 100%">
            <template slot="prefix"><img class="prefix" :src="form.ModelValue.photo"></template>
            <el-option v-for="item in form.select_1_id" :key="item.ModelValue" :label="item.label" :value="item">
              <img :src="item.photo"> {{ item.label }}
            </el-option>
          </el-select>
          <el-row>
            <el-marker style="padding-right: 12px;"> Вы выбрали </el-marker>
          </el-row>
          <el-row>
            <el-tag v-for="item in form.select_1_id" :key="item.ModelValue" :label="item.label" :value="item">
              <img :src="item.photo"> {{ item.label }}
            </el-tag>
          </el-row>
        </el-col>
        <el-col :span="1" class="line">
          —
        </el-col>
        <el-col :span="8">
          <el-select v-model="form.select_2_id" placeholder="Пожалуйста, выберите исполнителя отчёта" style="width: 100%">
            <el-option label="Электролаборатория ООО ЭлектроСити 1" value="Электролаборатория ООО ЭлектроСити 1" select />
            <el-option label="Электролаборатория ООО ЭлектроСити 2" value="Электролаборатория ООО ЭлектроСити 2" />
            <el-option label="Электролаборатория ООО ЭлектроСити 3" value="Электролаборатория ООО ЭлектроСити 3" />
          </el-select>
          <el-row>
            <el-marker style="padding-right: 12px;"> Вы выбрали </el-marker>
          </el-row>
          <el-row>
            <el-tag>
              <div v-if="form.select_2_id === ''"> Исполнитель не выбран </div>
              <div v-else> {{ form.select_2_id }} </div>
            </el-tag>
          </el-row>
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
          <el-checkbox :id="form.typechecked[0].id" label="Онлайн деятельность" :value="form.typechecked[0].value" :checked="form.typechecked[0].checked" />
          <el-checkbox :id="form.typechecked[1].id" label="Рекламная деятельность" :value="form.typechecked[1].value" :checked="form.typechecked[1].checked" />
          <el-checkbox :id="form.typechecked[2].id" label="Оффлайн деятельность" :value="form.typechecked[2].value" :checked="form.typechecked[2].checked" />
          <el-checkbox :id="form.typechecked[3].id" label="Простое знакомство с брендом" :value="form.typechecked[3].value" :checked="form.typechecked[3].checked" />
        </el-checkbox-group>
      </el-form-item>
      <el-form-item label="Выбранный вами тип деятельности">
        <el-row>
          <el-tag v-if="form.type[0] === 'Онлайн деятельность'" v-model="form.type">
            <div v-if="form.typechecked[0].id === 0">
              <div v-if="form.type[0] !== 'Онлайн деятельность'">
                не выбранно {{ form.typechecked[0].id }}
              </div>
              <div v-else> {{ form.type[0] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[0].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[0] !== 'Онлайн деятельность'" v-model="form.type">
            <div v-if="form.typechecked[0].id === 0">
              <div v-if="form.type[0] !== 'Онлайн деятельность'">
                не выбранно {{ form.typechecked[0].id }}
              </div>
              <div v-else> {{ form.type[0] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[0].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[1] === 'Рекламная деятельность'" v-model="form.type">
            <div v-if="form.typechecked[1].id === 1">
              <div v-if="form.type[1] !== 'Рекламная деятельность'">
                не выбранно {{ form.typechecked[1].id }}
              </div>
              <div v-else> {{ form.type[1] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[1].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[1] !== 'Рекламная деятельность'" v-model="form.type">
            <div v-if="form.typechecked[1].id === 1">
              <div v-if="form.type[1] !== 'Рекламная деятельность'">
                не выбранно {{ form.typechecked[1].id }}
              </div>
              <div v-else> {{ form.type[1] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[1].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[2] === 'Оффлайн деятельность'" v-model="form.type">
            <div v-if="form.typechecked[2].id === 2">
              <div v-if="form.type[2] !== 'Оффлайн деятельность'">
                не выбранно {{ form.typechecked[2].id }}
              </div>
              <div v-else> {{ form.type[2] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[2].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[2] !== 'Оффлайн деятельность'" v-model="form.type">
            <div v-if="form.typechecked[2].id === 2">
              <div v-if="form.type[2] !== 'Оффлайн деятельность'">
                не выбранно {{ form.typechecked[2].id }}
              </div>
              <div v-else> {{ form.type[2] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[2].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[3] === 'Простое знакомство с брендом'" v-model="form.type">
            <div v-if="form.typechecked[3].id === 3">
              <div v-if="form.type[3] !== 'Простое знакомство с брендом'">
                не выбранно {{ form.typechecked[3].id }}
              </div>
              <div v-else> {{ form.type[3] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[3].id }} </div>
          </el-tag>
          <el-tag v-if="form.type[3] !== 'Простое знакомство с брендом'" v-model="form.type">
            <div v-if="form.typechecked[3].id === 3">
              <div v-if="form.type[3] !== 'Простое знакомство с брендом'">
                не выбранно {{ form.typechecked[3].id }}
              </div>
              <div v-else> {{ form.type[3] }} </div>
            </div>
            <div v-else> - не выбранно {{ form.typechecked[3].id }} </div>
          </el-tag>
        </el-row>
      </el-form-item>
      <el-form-item label="Полное описание отчёта">
        <el-input
          v-model="form.desc.desktitel"
          type="textarea"
          :autosize="{ minRows: 3, maxRows: 10}"
          :placeholder="form.desc.placeholder"
          clearable
          maxlength="500"
          show-word-limit
          style="width: 50%"
        />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">
          Создать
        </el-button>
        <el-button @click="onCancel">
          Отмена
        </el-button>
        <el-button @click="onReset">
          Сбросить
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
        select_1_id: [
          {
            value: 'mcd',
            label: 'McDonald',
            photo: 'https://upload.wikimedia.org/wikipedia/commons/5/50/McDonald%27s_SVG_logo.svg',
          }, {
            value: 'kfc',
            label: 'KFC',
            photo: 'http://www.kfcku.com/themes/kfc_indonesia/images/kfc-indonesia-logo.png',
          }, {
            value: 'pizzahut',
            label: 'Pizza Hut',
            photo: 'https://vignette.wikia.nocookie.net/logopedia/images/b/b3/Pizza_Hut_Logo_2.png/revision/latest?cb=20161129133747',
          },
        ],
        ModelValue: null,
        select_2_id: '',
        date1: {
          selectedDate: new Date(),
          defaultDate: new Date('2019-02-22'),
        },
        date1time: {
          selectedTime: new Date(),
          defaultTime: new Date(),
        },
        date2: {
          selectedDate: new Date('2019-02-22'),
          defaultDate: new Date('2019-02-22'),
        },
        date2time: {
          selectedTime: new Date(),
          defaultTime: new Date(),
        },
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
            checked: false,
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
        desc: {
          autosize: '{ minRows: 2, maxRows: 4}',
          desktitel: 'Текст для примера описания деятельности',
          placeholder: 'Введите ваш текст для описания данной деятельности',
        },
      },
    };
  },
  created() {
    this.form.ModelValue = this.form.select_1_id[0];
  },
  methods: {
    onSubmit() {
      this.$message({
        message: 'Заказ id 00000 создан',
        type: 'success',
      });
    },
    onCancel() {
      this.$message({
        message: 'Отмена ввода!',
        type: 'warning',
      });
    },
    onReset() {
      this.$message({
        message: 'Опасно все данные формы будут сброшены!',
        type: 'danger',
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
img {
  width: 20px;
  height: 20px;
}

.prefix {
  margin-top: 10px;
}
</style>

