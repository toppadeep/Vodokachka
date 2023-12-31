<script>
import '../assets/main.css';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ButtonComponent from 'primevue/button';
import Divider from 'primevue/divider';
import Skeleton from 'primevue/skeleton';
import Toast from 'primevue/toast';
import DialogComponent from 'primevue/dialog';

export default {
  data() {
    return {
      loading: true,
      deleteDialog: false,
      priodSelected: {},
      visible: false,
      periods: [],
      period: {
        id: 'NEW',
        begin_date: '',
        end_date: ''
      },
      thead: [
        'ID',
        'Начало периода',
        'Конец периода',
        'Название периода',
        'Действия'
      ]
    }
  },
  async mounted() {
    await this.getPeriods();
    this.loading = false;
  },
  components: {
    DataTable,
    Column,
    ButtonComponent,
    Divider,
    Skeleton,
    Toast,
    DialogComponent,
  },
  methods: {
    async getPeriods() {
      const response = await fetch('http://127.0.0.1:8000/api/period', {
        method: 'GET'
      })

      const request = await response.json()
      if (request.status == 'success') {
        this.periods = request.periods
      } else {
        console.log('Happend some error')
      }
    },
    async createPeriod() {
      const period = new FormData()
      period.append('begin_date', this.period.begin_date)
      period.append('end_date', this.period.end_date)

      const response = await fetch('http://127.0.0.1:8000/api/period', {
        method: 'POST',
        body: period
      })

      const request = await response.json()
      if (request.status == 'success') {
        this.periods.push(this.period);
        this.visible = !this.visible;
        this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
      } else {
        this.$toast.add({ severity: 'warning', detail: 'Дачник не добавлен', life: 3000 });
      }
    },
    async onRowEditSave(event) {
      let { newData } = event;
      const period = new FormData();
      period.append('begin_date', newData.begin_date);
      period.append('end_date', newData.end_date);
  

      const response = await fetch((`http://127.0.0.1:8000/api/period/${newData.id}`), {
        method: 'PUT',
        body: period
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.periods.push(this.period);
        this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
      } else {
        this.$toast.add({ severity: 'warning', detail: 'Период не добавлен', life: 3000 });
      }
    },
    confirmDeletePeriod(period) {
      this.periodSelected = period;
      this.deleteDialog = true;
    },
    async deletePeriod(id) {

      const response = await fetch((`http://127.0.0.1:8000/api/period/${id}`), {
        method: 'DELETE',
      });

      this.deleteDialog = false;

      const request = await response.json()
      if (request.status == 'success') {
        const index = this.periods.indexOf(id)
        this.periods.splice(index, 1);
        this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
      } else {
        this.$toast.add({ severity: 'error', detail: 'Период не удалён', life: 3000 });
      }
    }
  }
}
</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createPeriod()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="visible = !visible" />
      <Divider align="center" type="solid">
        <b>Добавление нового периода</b>
      </Divider>

      <!-- <input type="datetime-local" v-model="period.begin_date" name="begin_date" /> -->
      <label for="begin_date">Начало периода</label>
      <input type="datetime-local" name="begin_date" id="begin_date" class="p-inputtext p-input"
        v-model="period.begin_date" value="-07:00">

      <label for="end_date">Конец периода</label>
      <input type="datetime-local" name="end_date" id="end_date" class="p-inputtext p-input" v-model="period.end_date"
        value="-07:00">

      <ButtonComponent type="submit" size="large" label="Добавить" style="width: 100%;" />
    </form>
  </div>
  <div class="card">
    <Toast />
    <div style="display: flex; flex: row nowrap; justify-content: space-between; align-items: center">
      <Divider align="left" type="solid">
        <b>Периоды</b>
      </Divider>
      <ButtonComponent label="Добавить" class="p-button" @click="this.visible = !this.visible" />
    </div>
    <div class="card">
      <DataTable :value="periods" paginator :rows="4" tableStyle="min-width: 50rem" stripedRows class="p-datatable"
        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
        currentPageReportTemplate="{first} to {last} of {totalRecords}" v-model:editingRows="editingRows" editMode="row"
        @row-edit-save="onRowEditSave">
        <Column field="id" header="ID">
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="begin_date" header="Начало периода">
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="end_date" header="Конец периода">
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="month" header="Период">
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center"></Column>
        <Column :exportable="false" style="min-width:8rem">
          <template #body="slotProps">
            <ButtonComponent icon="pi pi-trash" outlined severity="danger" @click="confirmDeletePeriod(slotProps.data)" />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
  <DialogComponent v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span v-if="periodSelected">Вы хотите удалить <b>{{ periodSelected.month }}</b>?</span>
    </div>
    <template #footer>
      <ButtonComponent label="Нет" icon="pi pi-times" text @click="deleteDialog = false" />
      <ButtonComponent label="Да" icon="pi pi-check" text @click="deletePeriod(periodSelected.id)" />
    </template>
  </DialogComponent>
</template>
