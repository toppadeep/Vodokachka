<script>
import { mapState, mapActions } from 'pinia'
import { useIndexStore } from '@/stores/IndexStore'
import { usePumpStore } from '@/stores/PumpStore'
import { usePeriodStore } from '@/stores/PeriodStore'
import '../assets/main.css'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ButtonComponent from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import InputNumber from 'primevue/inputnumber'
import Divider from 'primevue/divider'
import Skeleton from 'primevue/skeleton'
import DialogComponent from 'primevue/dialog'
import Toast from 'primevue/toast'
import Validation from '@/components/ErrorMessage.vue'

export default {
  data() {
    return {
      errors: [],
      defaultOption: 'Выберите период',
      deleteDialog: false,
      selected: {},
      period: {},
      pump: {
        id: '',
        period_id: '',
        amount_volume: ''
      },
      editingRows: []
    }
  },
  computed: {
    ...mapState(useIndexStore, ['visible']),
    ...mapState(usePumpStore, ['pumps']),
    ...mapState(useIndexStore, ['loading']),
    ...mapState(usePeriodStore, ['periods'])
  },
  async mounted() {
    await this.getPumps();
    await this.switchLoading();
  },
  components: {
    DataTable,
    Column,
    ButtonComponent,
    Dropdown,
    InputNumber,
    Divider,
    Skeleton,
    Toast,
    Validation,
    DialogComponent
  },
  methods: {
    ...mapActions(useIndexStore, ['openDialog']),
    ...mapActions(usePumpStore, ['getPumps']),
    ...mapActions(usePeriodStore, ['getPeriods']),
    ...mapActions(useIndexStore, ['switchLoading']),
    async createPump() {
      const pump = new FormData()
      pump.append('period_id', this.period.id)
      pump.append('amount_volume', this.pump.amount_volume)

      const response = await fetch('http://127.0.0.1:8000/api/pump', {
        method: 'POST',
        body: pump
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.pumps.push(this.pump);
        this.openDialog();
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        });
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Показания не сохранены', life: 3000 })
      }
    },
    async onRowEditSave(event) {
      let { newData } = event
      const index = this.pumps.indexOf(newData.period_id.id)
      this.pumps[index + 1].amount_volume == newData.amount_volume
      const pump = new FormData()
      pump.append('amount_volume', newData.amount_volume)

      const response = await fetch(`http://127.0.0.1:8000/api/pump/update/${newData.id}`, {
        method: 'POST',
        body: pump
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Данные не обновлены', life: 3000 })
      }
    },
    confirmDelete(pump) {
      this.selected = pump
      this.deleteDialog = true
    },
    async deletePump(id) {
      const response = await fetch(`http://127.0.0.1:8000/api/pump/${id}`, {
        method: 'DELETE'
      })

      this.deleteDialog = false

      const request = await response.json()
      if (request.status == 'success') {
        const index = this.pumps.indexOf(id)
        this.pumps.splice(index, 1)
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Дачник не удалён', life: 3000 })
      }
    }
  }
}
</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createPump()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="openDialog()" />
      <Divider align="center" type="solid">
        <b>Внести новые показания счётчика</b>
      </Divider>
      <label for="period_id">Период</label>
      <Dropdown
        v-model="period"
        inputId="period_id"
        placeholder="Выберите период"
        :options="periods"
        optionLabel="month"
      />
      <Validation :errors="errors" field="period_id" />
      <label for="amount_volume">Показания на текущий период</label>
      <inputNumber
        class="p-input"
        type="number"
        placeholder="0.0"
        min="1"
        name="amount_volume"
        v-model="pump.amount_volume"
        inputId="minmaxfraction"
        :minFractionDigits="2"
        :maxFractionDigits="2"
      />
      <Validation :errors="errors" field="amount_volume" />
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
      <ButtonComponent icon="pi pi-plus" size="large" rounded @click="openDialog()" />
    </div>
    <DataTable
      :value="pumps"
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
      </Column>
      <Column field="amount_volume" sortable header="Показания">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" />
          <Validation :errors="errors" field="amount_volume" />
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
  <DialogComponent
    v-model:visible="deleteDialog"
    :style="{ width: '450px' }"
    header="Подтверждение"
    :modal="true"
  >
    <div class="confirmation-content">
      <span v-if="selected"
        >Вы хотите удалить показания за <b>{{ selected.period_id }}</b
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
        @click="deletePump(selected.id)"
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