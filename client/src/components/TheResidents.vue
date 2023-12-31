<script>
import '../assets/main.css';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ButtonComponent from 'primevue/button';
import Divider from 'primevue/divider';
import Skeleton from 'primevue/skeleton';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Toast from 'primevue/toast';
import DialogComponent from 'primevue/dialog';

export default {
  data() {
    return {
      loading: true,
      deleteDialog: false,
      residentSelected: {},
      resident: {
        id: 'NEW',
        fio: '',
        area: '',
        start_date: ''
      },
      visible: false,
      residents: [],
      periods: [],
      editingRows: [],
    }
  },
  async mounted() {
    await this.getResidents();
    this.loading = false;
  },
  components: {
    DataTable,
    Column,
    ButtonComponent,
    Divider,
    Skeleton,
    InputText,
    InputNumber,
    Toast,
    DialogComponent,
  },
  methods: {
    async getResidents() {
      const response = await fetch('http://127.0.0.1:8000/api/resident', {
        method: 'GET'
      });

      const request = await response.json()
      if (request.status == 'success') {
        this.residents = request.residents;
      };

    },
    async onRowEditSave(event) {
      let { newData } = event;
      const resident = new FormData();
      resident.append('fio', newData.fio);
      resident.append('area', newData.area);
      resident.append('start_date', newData.start_date);

      const response = await fetch((`http://127.0.0.1:8000/api/resident/${newData.id}`), {
        method: 'PUT',
        body: resident
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.residents.push(this.resident);
        this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
      } else {
        this.$toast.add({ severity: 'warning', detail: 'Дачник не добавлен', life: 3000 });
      }
    },
    async createResidend() {
      const resident = new FormData()
      resident.append('fio', this.resident.fio)
      resident.append('area', this.resident.area)
      resident.append('start_date', this.resident.start_date)

      const response = await fetch('http://127.0.0.1:8000/api/resident', {
        method: 'POST',
        body: resident
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.residents.push(this.resident);
        this.visible = !this.visible;
        this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
      } else {
        this.$toast.add({ severity: 'warning', detail: 'Дачник не добавлен', life: 3000 });
      }
    },
    confirmDeleteResident(resident) {
      this.residentSelected = resident;
      this.deleteDialog = true;
    },
    async deleteResident(id) {

      const response = await fetch((`http://127.0.0.1:8000/api/resident/${id}`), {
        method: 'DELETE',
      });

      this.deleteDialog = false;

      const request = await response.json()
      if (request.status == 'success') {
        const index = this.residents.indexOf(id)
        this.residents.splice(index, 1);
        this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
      } else {
        this.$toast.add({ severity: 'error', detail: 'Дачник не удалён', life: 3000 });
      }
    }
  },
}

</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createResidend()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="visible = !visible" />
      <Divider align="center" type="solid">
        <b>Добавление нового дачника</b>
      </Divider>
      <label for="fio">ФИО</label>
      <InputText class="p-input" type="text" name="fio" v-model="resident.fio" />
      <label for="area">Площадь огорода</label>
      <InputNumber class="p-input" v-model="resident.area" id="area" name="area" inputId="minmaxfraction"
        :minFractionDigits="2" :maxFractionDigits="5" />
      <label for="start_date">Дата подключения</label>
      <input type="datetime-local" name="start_date" id="start_date" class="p-inputtext p-input"
        v-model="resident.start_date" value="-07:00">
      <ButtonComponent type="submit" size="large" label="Добавить" style="width: 100%;" />
    </form>
  </div>
  <div class="card">
    <Toast />
    <div style="display: flex; flex: row nowrap; justify-content: space-between; align-items: center">
      <Divider align="left" type="solid">
        <b>Дачники</b>
      </Divider>
      <ButtonComponent label="Добавить" @click="visible = !visible" />
    </div>
    <div class="card">
      <DataTable :value="residents" paginator :rows="4" tableStyle="min-width: 50rem" stripedRows class="p-datatable"
        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
        currentPageReportTemplate="{first} to {last} of {totalRecords}" v-model:editingRows="editingRows" editMode="row"
        @row-edit-save="onRowEditSave">
        <Column field="id" header="ID" sortable>
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="fio" header="ФИО">
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <InputText v-model="data[field]" />
          </template>
        </Column>
        <Column field="area" header="Площадь участка" sortable>
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <InputNumber v-model="data[field]" />
          </template>
        </Column>
        <Column field="start_date" header="Дата подключения" sortable>
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <input v-model="data[field]" type="datetime-local" name="start_date" id="start_date"
              class="p-inputtext p-input" style="margin: 0;">
          </template>
        </Column>
        <Column field="timeToConnected" header="Прошло дней с момента подключения" sortable>
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center"></Column>
        <Column :exportable="false" style="min-width:8rem">
          <template #body="slotProps">
            <ButtonComponent icon="pi pi-trash" outlined severity="danger" @click="confirmDeleteResident(slotProps.data)" />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
  <DialogComponent v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Подтверждение" :modal="true">
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span v-if="residentSelected">Вы хотите удалить <b>{{ residentSelected.fio }}</b>?</span>
    </div>
    <template #footer>
      <ButtonComponent label="Нет" icon="pi pi-times" text @click="deleteDialog = false" />
      <ButtonComponent label="Да" icon="pi pi-check" text @click="deleteResident(residentSelected.id)" />
    </template>
  </DialogComponent>
  <!-- <table summary="This table shows how to create responsive tables using RWD-Table-Patterns' functionality"
      class="table table-bordered table-hover">
      <thead>
        <tr>
          <th style="font-weight: bold" v-for="(th, index) of thead" :key="index">{{ th }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(resident, index) of residents" :key="index">
          <td>{{ resident.id }}</td>
          <td>{{ resident.fio }}</td>
          <td>{{ resident.area }}</td>
          <td>{{ resident.start_date }}</td>
          <td>{{ resident.timeToConnected + ' подключения' }}</td>
          <td><img src="../assets/mode_edit_24px.png" alt="" /></td>
        </tr>
      </tbody>
    </table> -->
</template>
