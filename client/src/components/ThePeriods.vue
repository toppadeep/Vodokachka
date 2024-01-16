<script>
import { mapState, mapActions } from 'pinia'
import { useIndexStore } from '@/stores/IndexStore'
import { usePeriodStore } from '@/stores/PeriodStore'
import '../assets/main.css'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ButtonComponent from 'primevue/button'
import Divider from 'primevue/divider'
import Skeleton from 'primevue/skeleton'
import Toast from 'primevue/toast'
import DialogComponent from 'primevue/dialog'
import Validation from '@/components/ErrorMessage.vue'
import Tag from 'primevue/tag'

export default {
  data() {
    return {
      errors: [],
      deleteDialog: false,
      selected: {},
      period: {
        id: '',
        begin_date: '',
        end_date: ''
      },
      editingRows: []
    }
  },
  computed: {
    ...mapState(useIndexStore, ['visible']),
    ...mapState(usePeriodStore, ['periods']),
    ...mapState(useIndexStore, ['loading'])
  },
  async mounted() {
    await this.getPeriods();
    await this.switchLoading();
  },
  components: {
    DataTable,
    Column,
    ButtonComponent,
    Divider,
    Skeleton,
    Toast,
    DialogComponent,
    Validation,
    Tag
  },
  methods: {
    ...mapActions(useIndexStore, ['openDialog']),
    ...mapActions(usePeriodStore, ['getPeriods']),
    ...mapActions(useIndexStore, ['switchLoading']),
    async createPeriod() {
      const period = new FormData()
      period.append('begin_date', this.period.begin_date)
      period.append('end_date', this.period.end_date)

      const response = await fetch('http://127.0.0.1:8000/api/period', {
        method: 'POST',
        body: period
      });

      const request = await response.json()
      if (request.status == 'success') {
        this.periods.push(this.period)
        this.openDialog();
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Дачник не добавлен', life: 3000 })
      }
    },
    async onRowEditSave(event) {
      let { newData } = event
      const period = new FormData()
      period.append('begin_date', newData.begin_date)
      period.append('end_date', newData.end_date)

      const response = await fetch(`http://127.0.0.1:8000/api/period/update/${newData.id}`, {
        method: 'POST',
        body: period
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.periods.push(this.period)
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Период не обновлён', life: 3000 })
      }
    },
    confirmDelete(period) {
      this.selected = period
      this.deleteDialog = true
    },
    async deletePeriod(id) {
      const response = await fetch(`http://127.0.0.1:8000/api/period/${id}`, {
        method: 'DELETE'
      })

      this.deleteDialog = false

      const request = await response.json()
      if (request.status == 'success') {
        const index = this.periods.indexOf(id)
        this.periods.splice(index, 1)
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        this.$toast.add({ severity: 'error', detail: 'Данный период нельзя удалить', life: 3000 })
      }
    }
  }
}
</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createPeriod()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="openDialog()" />
      <Divider align="center" type="solid">
        <b>Добавление нового периода</b>
      </Divider>
      <label for="begin_date">Начало периода</label>
      <input
        type="datetime-local"
        name="begin_date"
        inputId="begin_date"
        min="2024-01-01T00:00"
        max="2026-01-30T23:59"
        class="p-inputtext p-input"
        v-model="period.begin_date"
        value="-07:00"
      />
      <Validation :errors="errors" field="begin_date" />
      <label for="end_date">Конец периода</label>
      <input
        type="datetime-local"
        name="end_date"
        inputId="end_date"
        min="2024-01-01T00:00"
        max="2026-01-30T23:59"
        class="p-inputtext p-input"
        v-model="period.end_date"
        value="-07:00"
      />
      <Validation :errors="errors" field="end_date" />
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
        :value="periods"
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
        <Column field="id" header="ID">
          <template v-if="this.loading" #body>
            <Skeleton></Skeleton>
          </template>
        </Column>
        <Column field="begin_date" header="Начало периода">
          <template v-if="this.loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <input
              v-model="data[field]"
              type="datetime-local"
              min="2024-01-01T00:00"
              max="2025-12-30T23:59"
              name="begin_date"
              id="begin_date"
              class="p-inputtext p-input"
              style="margin: 0"
            />
            <Validation :errors="errors" field="begin_date" />
          </template>
        </Column>
        <Column field="end_date" header="Конец периода">
          <template v-if="this.loading" #body>
            <Skeleton></Skeleton>
          </template>
          <template #editor="{ data, field }">
            <input
              v-model="data[field]"
              type="datetime-local"
              min="2024-01-01T00:00"
              max="2025-12-30T23:59"
              name="end_date"
              id="end_date"
              class="p-inputtext p-input"
              style="margin: 0"
            />
            <Validation :errors="errors" field="end_date" />
          </template>
        </Column>
        <Column field="month" header="Период">
          <template v-if="this.loading" #body>
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
              v-if="slotProps.data.hasInRate == false"
              icon="pi pi-trash"
              text
              severity="danger"
              @click="confirmDelete(slotProps.data)"
            />
            <Tag v-else-if="slotProps.data.hasInRate == true" severity="warning" value="В тарифе"></Tag>
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
        >Вы хотите удалить <b>{{ selected.month }}</b
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
        @click="deletePeriod(selected.id)"
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
@/stores/Store