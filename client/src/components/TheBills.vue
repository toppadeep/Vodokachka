<script>
import { mapState, mapActions } from 'pinia'
import { useBillStore } from '@/stores/BillStore';
import '../assets/main.css';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ButtonComponent from 'primevue/button';
import Divider from 'primevue/divider';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Skeleton from 'primevue/skeleton';
import Toast from 'primevue/toast';
import { FilterMatchMode } from 'primevue/api';
import DialogComponent from 'primevue/dialog';
import Validation from '@/components/ErrorMessage.vue';

export default {
  data() {
    return {
      errors: [],
      selected: {},
      deleteDialog: false,
      loading: true,
      visible: false,
      currentMonth: null,
      periodsData: [],
      rates: [],
      pumps: [],
      residents: [],
      resident: {},
      periods: [],
      period: {},
      resident_names: [],
      bill: {
        id: null,
        resident_id: null,
        resident_area: null,
        period_id: null,
        amount_rub: null
      },
      filters: {
        month: { value: null, matchMode: FilterMatchMode.EQUALS }
      },
      editingRows: []
    }
  },
  computed: {
    ...mapState(useBillStore, ['bills']),
    totalArea: function () {
      return this.residents.reduce((sum, resident) => sum + resident.area, 0)
    },
    currentArea: function () {
      return this.resident.area
    },
    areaPercentCalc: function () {
      return Math.floor(this.totalArea / this.resident.area)
    },
    currentAreaPercent: function () {
      return ((1 / this.areaPercentCalc) * 100).toFixed(2)
    },
    priceForCurrentPeriod: function () {
      return this.rates
        .filter((rate) => rate.month == this.period.month)
        .map(function (obj) {
          return obj.amount_price
        })
    },
    totalVolumeWater: function () {
      return this.pumps
        .filter((pump) => pump.period_id == this.period.month)
        .map(function (obj) {
          return obj.amount_volume
        })
    },
    totalCurrentPrice: function () {
      return (
        (this.totalVolumeWater / 100) *
        this.currentAreaPercent *
        this.priceForCurrentPeriod
      ).toFixed(2)
    },
    currentPeriodbills: function () {
      return this.bills.filter((bill) => bill.period_id == this.period.currentMonth)
    }
  },
  async mounted() {
    await this.getResidents()
    await this.getPeriods()
    await this.getRates()
    await this.getPumps()
    await this.getBills()
    this.loading = false
  },
  components: {
    DataTable,
    Column,
    Divider,
    ButtonComponent,
    InputNumber,
    Dropdown,
    Skeleton,
    Toast,
    DialogComponent,
    Validation
  },
  methods: {
    ...mapActions(useBillStore, ['getBills']),
    async getPumps() {
      const response = await fetch('http://127.0.0.1:8000/api/pump', {
        method: 'GET'
      })

      const request = await response.json()
      this.pumps = request.pumps
    },
    async getResidents() {
      const response = await fetch('http://127.0.0.1:8000/api/resident', {
        method: 'GET'
      })

      const residents = await response.json()
      this.residents = residents.residents
    },
    async getPeriods() {
      const response = await fetch('http://127.0.0.1:8000/api/period', {
        method: 'GET'
      })

      const request = await response.json()
      this.periods = request.periods
      this.periodsData = request.periods.map(function (obj) {
        return obj.month
      })
    },
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
    async createBill() {
      const bill = new FormData()

      bill.append('resident_id', this.resident.id)
      bill.append('period_id', this.period.id)
      bill.append('amount_rub', this.bill.amount_rub)

      const response = await fetch('http://127.0.0.1:8000/api/bill', {
        method: 'POST',
        body: bill
      })

      const request = await response.json()

      if (request.status == 'success') {
        this.bills.push(this.bill)
        this.visible = !this.visible
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
      let { newData } = event;
      const bill = new FormData();
      bill.append('period_id', newData.period_id);
      bill.append('amount_rub', newData.amount_rub);

      const response = await fetch(`http://127.0.0.1:8000/api/bill/update/${newData.id}`, {
        method: 'POST',
        body: bill
      });

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
    confirmDelete(bill) {
      this.selected = bill;
      this.deleteDialog = true;
    },
    async deleteBill(id) {
      const response = await fetch(`http://127.0.0.1:8000/api/bill/${id}`, {
        method: 'DELETE'
      });

      this.deleteDialog = false;

      const request = await response.json()
      if (request.status == 'success') {
        const index = this.bills.indexOf(id)
        this.bills.splice(index, 1)
        this.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        });
      } else {
        this.errors = request.errors;
        this.$toast.add({ severity: 'error', detail: 'Счёт не исполнен', life: 3000 });
      }
    }
  }
}
</script>

<template>
  <div class="wrapperformCreate" v-if="visible">
    <form @submit.prevent="createBill()" class="formCreate">
      <ButtonComponent class="closeButton" label="Закрыть" @click="visible = !visible" />
      <Divider align="center" type="solid">
        <b>Создать счёт</b>
      </Divider>
      <label for="resident_id">Дачник</label>
      <Dropdown
        v-model="resident"
        inputId="resident_id"
        placeholder="Выберите дачника"
        :options="residents"
        optionLabel="fio"
      />
      <Validation :errors="errors" field="resident_id" />
      <Divider align="center" type="solid" v-if="resident.start_date">
        <b>Дата подключения дачника - {{ resident.start_date }}</b>
      </Divider>
      <Divider align="center" type="solid" v-if="resident.timeToConnected">
        <b>Прошло - {{ resident.timeToConnected }} подключения</b>
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
      <Divider align="center" type="solid">
        <b>Общая площадь участков - {{ totalArea }} куб<sup>3</sup></b>
      </Divider>
      <Divider align="center" type="solid">
        <b>Площадь участка дачника - {{ currentArea }} куб<sup>3</sup></b>
      </Divider>
      <Divider align="center" type="solid">
        <b>Доля участка дачника - 1 / {{ areaPercentCalc }} %</b>
      </Divider>
      <Divider align="center" type="solid">
        <b>Площадь в доли - {{ currentAreaPercent }} %</b>
      </Divider>
      <Divider align="center" type="solid">
        <b>
          Объём потраченной воды <sup>3</sup> за {{ period.month }} = {{ totalVolumeWater }} куб<sup
            >3</sup
          >
        </b>
      </Divider>
      <Divider align="center" type="solid">
        <b>
          Стоимость куб <sup>3</sup> за {{ period.month }} = {{ priceForCurrentPeriod }} &#8381;
        </b>
      </Divider>
      <Divider align="center" type="solid">
        <b style="font-weight: bold"> Итого к оплате - {{ totalCurrentPrice }} &#8381; </b>
      </Divider>
      <label for="amount_rub">Сумма к оплате (руб)</label>
      <InputNumber
        class="p-input"
        :placeholder="totalCurrentPrice"
        type="number"
        name="amount_rub"
        v-model="bill.amount_rub"
        inputId="minmaxfraction"
        :minFractionDigits="2"
        :maxFractionDigits="2"
      />
      <Validation :errors="errors" field="amount_rub" />
      <ButtonComponent type="submit" size="large" class="p-input" label="Выставить счёт" />
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
      v-model:filters="filters"
      filterDisplay="row"
      :globalFilterFields="['month']"
      :value="bills"
      paginator
      :rows="8"
      stripedRows
      tableStyle="min-width: 50rem"
      class="p-datatable"
      paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
      currentPageReportTemplate="{first} to {last} of {totalRecords}"
      v-model:editingRows="editingRows"
      editMode="row"
      @row-edit-save="onRowEditSave"
    >
      <Column field="id" sortable header="ID"> </Column>
      <Column field="resident_id" header="Дачник">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
      </Column>
      <Column field="period_start" sortable header="Дата подключения">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
      </Column> 
      <Column field="month" sortable header="Тариф за" :showFilterMenu="false">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
        <template #filter="{ filterModel, filterCallback }">
          <Dropdown
            v-model="filterModel.value"
            @change="filterCallback()"
            :options="periodsData"
            placeholder="Выберите период"
          >
          </Dropdown>
        </template>
        <template #editor="{ data, field }">
          <Dropdown
            v-model="data[field]"
            inputId="period_id"
            placeholder="Выберите период"
            :options="periods"
            optionLabel="month"
          />
          <Validation :errors="errors" field="period_id" />
        </template>
      </Column>
      <Column field="amount_rub" sortable header="Сумма к оплате">
        <template v-if="loading" #body>
          <Skeleton></Skeleton>
        </template>
        <template #editor="{ data, field }">
          <InputNumber
            class="p-input"
            type="number"
            name="amount_rub"
            v-model="data[field]"
            inputId="minmaxfraction"
            :minFractionDigits="2"
            :maxFractionDigits="2"
          />
          <Validation :errors="errors" field="amount_rub" />
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
        >Вы хотите удалить cчёт?</span
      >
    </div>
    <template #footer>
      <ButtonComponent
        style="margin-right: 1em"
        label="Да"
        severity="success"
        outlined
        icon="pi pi-check"
        @click="deleteBill(selected.id)"
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
