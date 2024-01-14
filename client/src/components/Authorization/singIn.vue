<script>
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import ButtonComponent from 'primevue/button';

export default {
    data () {
        return {

        }
    },
    components: {
        InputText,
        InputNumber,
        ButtonComponent
    },
    methods: {
        logIN() {

        }
    }
}
</script>

<template>
    <form @submit.prevent="logIN()" class="card">
          <label for="fio">Efoaj;f</label>
          <InputText class="p-input" type="text" name="fio" />
          <Validation :errors="errors" field="fio" />
          <label for="area">Площадь огорода</label>
          <InputNumber
            class="p-input"
            id="area"
            name="area"
            inputId="minmaxfraction"
            :minFractionDigits="2"
            :maxFractionDigits="5"
          />
          <Validation :errors="errors" field="area" />
          <label for="start_date">Дата подключения</label>
          <input
            type="datetime-local"
            min="2024-01-01T00:00"
            max="2025-01-30T23:59"
            name="start_date"
            id="start_date"
            class="p-inputtext p-input"
          />
          <Validation :errors="errors" field="start_date" />
          <ButtonComponent type="submit" size="large" label="Добавить" style="width: 100%" />
        </form>
</template>