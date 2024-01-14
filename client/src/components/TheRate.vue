<script>
import '../assets/main.css';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ButtonComponent from 'primevue/button';
import Divider from 'primevue/divider';
import Skeleton from 'primevue/skeleton';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Toast from 'primevue/toast';
import Validation from '@/components/ErrorMessage.vue';
import DialogComponent from 'primevue/dialog';
import Tag from 'primevue/tag';

export default {
  data() {
    return {
      errors: [],
      selected: {},
      loading: true,
      visible: false,
      deleteDialog: false,
      periods: [],
      period: {},
      rates: [],
      rate: {
        id: null,
        period_id: null,
        amount_price: null
      },
      editingRows: []
    }
  },
  async mounted() {
    await this.getRates()
    await this.getPeriods()
    this.loading = false
  },
  components: {
    DataTable,
    Column,
    ButtonComponent,
    Divider,
    Skeleton,
    InputNumber,
    Dropdown,
    Toast,
    Validation,
    DialogComponent,
    Tag
  },
  methods: {
    async getRates() {
      const response = await fetch('http://127.0.0.1:8000/api/rate', {
        method: 'GET'
      })

      const request = await response.json()
      if (request.status == 'success') {
        this.rates = request.rates
      } else {
        console.log('Some errors')
      }
    },
    async getPeriods() {
      const response = await fetch('http://127.0.0.1:8000/api/period', {
        method: 'GET'
      })

      const request = await response.json()
      if (request.status == 'success') {
        this.periods = request.periods
      } else {
        console.log('Some errors')
      }
    },
    async createRate() {
      const rate = new FormData()

      rate.append('period_id', this.period.id)
      rate.append('amount_price', this.rate.amount_price)

      const response = await fetch('http://127.0.0.1:8000/api/rate', {
        method: 'POST',
        body: rate
      })

      const request = await response.json()
      if (request.status == 'success') {
        this.rates.push(this.rate)
        this.visible = !this.visible
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        this.errors = request.errors;
        this.$toast.add({ severity: 'error', detail: 'Дачник не добавлен', life: 3000 })
      }
    },
    async onRowEditSave(event) {
      let { newData } = event;
      const rate = new FormData();
      rate.append('period_id', newData.period_id.id);
      rate.append('amount_price', newData.amount_price);

      const response = await fetch(`http://127.0.0.1:8000/api/rate/update/${newData.id}`, {
        method: 'POST',
        body: rate
      })

      const request = await response.json();

      if (request.status == 'success') {
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        });
      } else {
        this.errors = request.errors;
        this.$toast.add({ severity: 'error', detail: 'Данные не обновлены', life: 3000 });
      }
    },
    confirmDelete(event) {
      this.selected = event;
      this.deleteDialog = true;
    },
    async deleteRate(id) {
      const response = await fetch(`http://127.0.0.1:8000/api/rate/${id}`, {
        method: 'DELETE'
      });

      this.deleteDialog = false;

      const request = await response.json()
      if (request.status == 'success') {
        const index = this.rates.indexOf(id)
        this.rates.splice(index, 1)
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        });
      } else {
        this.errors = request.errors;
        this.$toast.add({ severity: 'error', detail: 'Тариф не удалён', life: 3000 });
      }
    }
  }
}
</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createRate()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="visible = !visible" />
      <Divider align="center" type="solid">
        <b>Создание нового тарифа</b>
      </Divider>
      <label for="period_id">Период</label>
      <Dropdown
        v-model="period"
        inputId="period_id"
        name="period_id"
        placeholder="Выберите период"
        :options="periods"
        optionLabel="month"
      />
      <Validation :errors="errors" field="period_id" />
      <label for="amount_price">Стоимость кубометра воды</label>
      <InputNumber
        class="p-input"
        type="number"
        placeholder="0.0"
        name="amount_price"
        v-model="rate.amount_price"
        inputId="minmaxfraction"
        :minFractionDigits="2"
        :maxFractionDigits="2"
      />
      <Validation :errors="errors" field="amount_price" />
      <ButtonComponent type="submit" label="Добавить" size="large" class="p-input" />
    </form>
  </div>
  <div class="card">
    <Toast />
    <div
      style="
        display: flex;
        flex: row nowrap;
        justify-content: end;
        align-items: center;
        margin-bottom: 1.5em;
      "
    >
      <ButtonComponent icon="pi pi-plus" size="large" rounded @click="visible = !visible" />
    </div>
    <DataTable
      :value="rates"
      paginator
      :rows="8"
      tableStyle="min-width: 50rem"
      stripedRows
      class="p-datatable"
      paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
      currentPageReportTemplate="{first} to {last} of {totalRecords}"
      v-model:editingRows="editingRows"
      editMode="row"
      @row-edit-save="onRowEditSave"
    >
      <Column field="id" sortable header="ID">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
      </Column>
      <Column field="period_id" header="Период">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
        <template #editor="{ data, field }">
          <Dropdown
            style="margin-bottom: 0;"
            v-model="data[field]"
            inputId="period_id"
            name="period_id"
            placeholder="Выберите период"
            :options="periods"
            optionLabel="month"
          />
          <Validation :errors="errors" field="period_id" />
        </template>
      </Column>
      <Column field="amount_price" sortable header="Цена тарифа ( &#x20bd; )">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" />
          <Validation :errors="errors" field="amount_price" />
        </template>
      </Column>
      <Column field="create_date" sortable header="Дата создания">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
      </Column>
      <Column
        :rowEditor="true"
        style="width: 10%; min-width: 8rem"
        bodyStyle="text-align:center"
        header="Редактировать"
      ></Column>
      <Column :exportable="false" style="min-width: 8rem" header="Удалить">
        <template #body="slotProps">
          <ButtonComponent
            v-if="slotProps.data.isInBill == false"
            icon="pi pi-trash"
            text
            severity="danger"
            @click="confirmDelete(slotProps.data)"
          />
          <Tag v-else severity="warning" value="В счёте"></Tag>
        </template>
      </Column>
    </DataTable>
  </div>
  <DialogComponent
    v-model:visible="deleteDialog"
    :style="{ width: '450px' }"
    header="Подтверждение"
    :modal="true"
  >
    <div class="confirmation-content">
      <span v-if="selected"
        >Вы хотите удалить тариф за <b>{{ selected.create_date }}</b
        >?</span
      >
    </div>
    <template #footer>
      <ButtonComponent
        style="margin-right: 1em"
        label="Да"
        severity="success"
        outlined
        icon="pi pi-check"
        @click="deleteRate(selected.id)"
      />
      <ButtonComponent
        label="Нет"
        severity="danger"
        outlined
        icon="pi pi-times"
        @click="deleteDialog = false"
      />
    </template>
  </DialogComponent>
</template>
