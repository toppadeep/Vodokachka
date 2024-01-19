<script>
import { mapState, mapActions } from 'pinia'
import { useResidentStore } from '@/stores/ResidentStore'
import { useIndexStore } from '@/stores/IndexStore'
import '../assets/main.css'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ButtonComponent from 'primevue/button'
import Divider from 'primevue/divider'
import Skeleton from 'primevue/skeleton'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Toast from 'primevue/toast'
import DialogComponent from 'primevue/dialog'
import Validation from '@/components/ErrorMessage.vue'

export default {
  data() {
    return {
      errors: [],
      loading: true,
      deleteDialog: false,
      selected: {},
      resident: {
        id: 'NEW',
        fio: '',
        area: '',
        start_date: ''
      },
      periods: [],
      editingRows: []
    }
  },
  computed: {
    ...mapState(useIndexStore, ['visible']),
    ...mapState(useResidentStore, ['residents'])
  },
  async mounted() {
    await this.getResidents()
    await this.switchLoading()
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
    Validation
  },
  methods: {
    ...mapActions(useIndexStore, ['openDialog']),
    ...mapActions(useResidentStore, ['getResidents']),
    ...mapActions(useIndexStore, ['switchLoading']),
    switchLoading() {
      setTimeout(() => {
        this.loading = false
      }, 1000)
    },
    async onRowEditSave(event) {
      let { newData } = event
      const resident = new FormData()
      resident.append('fio', newData.fio)
      resident.append('area', newData.area)
      resident.append('start_date', newData.start_date)

      const response = await fetch(`http://localhost:8000/api/resident/update/${newData.id}`, {
        method: 'POST',
        body: resident
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
        this.getResidents()
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Данные не обновлены', life: 3000 })
      }
    },
    async createResidend() {
      const resident = new FormData()
      resident.append('fio', this.resident.fio)
      resident.append('area', this.resident.area)
      resident.append('start_date', this.resident.start_date)

      const response = await fetch('http://localhost:8000/api/resident', {
        method: 'POST',
        body: resident
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.openDialog()
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: 'Создано',
          life: 3000
        })
        this.getResidents()
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Дачник не добавлен', life: 3000 })
      }
    },
    confirmDelete(resident) {
      this.selected = resident
      this.deleteDialog = true
    },
    async deleteResident(id) {
      await this.axios.get('http://localhost:8000/sanctum/csrf-cookie')
      await this.axios
        .delete(`http://localhost:8000/api/resident/${id}`)
        .then((response) => {
          this.deleteDialog = false
          this.$toast.add({
            severity: 'success',
            summary: 'Успешно',
            detail: response.data.response,
            life: 3000
          })
          this.getResidents()
        })
        .catch(() => {
          this.$toast.add({ severity: 'error', detail: 'Дачник не удалён', life: 3000 })
        })
    }
  }
}
</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createResidend()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="openDialog()" />
      <Divider align="center" type="solid">
        <b>Добавление нового дачника</b>
      </Divider>
      <label for="fio">ФИО</label>
      <InputText class="p-input" type="text" inputId="fio" name="fio" v-model="resident.fio" />
      <Validation :errors="errors" field="fio" />
      <label for="area">Площадь огорода</label>
      <InputNumber
        class="p-input"
        v-model="resident.area"
        id="area"
        name="area"
        inputId="area"
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
        inputId="start_date"
        class="p-inputtext p-input"
        v-model="resident.start_date"
      />
      <Validation :errors="errors" field="start_date" />
      <ButtonComponent type="submit" size="large" label="Добавить" style="width: 100%" />
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
      <ButtonComponent icon="pi pi-plus" size="large" rounded @click="openDialog()" />
    </div>
    <div class="card">
      <DataTable
        :value="this.residents"
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
            <Validation :errors="errors" field="fio" />
          </template>
        </Column>
        <Column field="area" header="Площадь участка" sortable>
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <InputNumber v-model="data[field]" />
            <Validation :errors="errors" field="area" />
          </template>
        </Column>
        <Column field="start_date" header="Дата подключения" sortable>
          <template v-if="loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <input
              v-model="data[field]"
              type="datetime-local"
              min="2023-01-01T00:00"
              max="2025-12-30T23:59"
              name="start_date"
              id="start_date"
              class="p-inputtext p-input"
              style="margin: 0"
            />
            <Validation :errors="errors" field="start_date" />
          </template>
        </Column>
        <Column field="timeToConnected" header="Прошло дней с момента подключения" sortable>
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
              icon="pi pi-trash"
              text
              severity="danger"
              @click="confirmDelete(slotProps.data)"
            />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
  <DialogComponent
    v-model:visible="deleteDialog"
    :style="{ width: '450px' }"
    header="Подтверждение"
    :modal="true"
  >
    <div class="confirmation-content">
      <span v-if="selected"
        >Вы хотите удалить <b>{{ selected.fio }}</b
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
        @click="deleteResident(selected.id)"
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
