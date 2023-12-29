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

export default {
  data() {
    return {
      loading: true,
      deleteProductDialog: true,
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
    Toast
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
      <DataTable :value="residents" paginator :rows="5" tableStyle="min-width: 50rem" stripedRows class="p-datatable"
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
        <Column  style="min-width:8rem">
          <template #body>
            <ButtonComponent label="Удалить" outlined severity="danger"  />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
  <Dialog v-model:visible="deleteProductDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span v-if="product">Are you sure you want to delete <b>{{ resident.fio }}</b>?</span>
    </div>
    <template #footer>
      <ButtonComponent label="No" icon="pi pi-times" text @click="deleteProductDialog = false" />
      <ButtonComponent label="Yes" icon="pi pi-check" text @click="deleteProduct" />
    </template>
  </Dialog>
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