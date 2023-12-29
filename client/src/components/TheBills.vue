<script>
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


export default {
    data() {
        return {
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
                id: 'NEW',
                resident_id: null,
                resident_area: null,
                period_id: null,
                amount_rub: null,
            },
            filters: {
                month: { value: null, matchMode: FilterMatchMode.EQUALS },
            },
            bills: [],
        };
    },
    computed: {
        periodConfirm: function () {
            return this.periodDetected();
        },
        totalArea: function () {
            return this.residents.reduce((sum, resident) => sum + resident.area, 0);
        },
        currentArea: function () {
            return this.resident.area;
        },
        areaPercentCalc: function () {
            return Math.floor(this.totalArea / this.resident.area);
        },
        currentAreaPercent: function () {
            return (1 / this.areaPercentCalc * 100).toFixed(2);
        },
        priceForCurrentPeriod: function () {
            return this.rates.filter((rate) => rate.month == this.period.month).map(function (obj) {
                return obj.amount_price;
            });
        },
        totalVolumeWater: function () {
            return this.pumps.filter((pump) => pump.period_id == this.period.month).map(function (obj) {
                return obj.amount_volume;
            });
        },
        totalCurrentPrice: function () {
            return ((this.totalVolumeWater / 100) * this.currentAreaPercent * this.priceForCurrentPeriod).toFixed(2);
        },
        currentPeriodbills: function () {
            return this.bills.filter((bill) => bill.period_id == this.period.currentMonth);
        },
    },
    async mounted() {
        await this.getResidents();
        await this.getPeriods();
        await this.getRates();
        await this.getPumps();
        await this.getBills();
        this.loading = false;
    },
    components: {
        DataTable,
        Column,
        Divider,
        ButtonComponent,
        InputNumber,
        Dropdown,
        Skeleton,
        Toast
    },
    methods: {

        async getBills() {
            const response = await fetch('http://127.0.0.1:8000/api/bill', {
                method: 'GET'
            });

            const request = await response.json();
            this.bills = request.bills;
        },
        async getPumps() {
            const response = await fetch('http://127.0.0.1:8000/api/pump', {
                method: 'GET'
            });

            const request = await response.json();
            this.pumps = request.pumps;
        },
        async getResidents() {
            const response = await fetch('http://127.0.0.1:8000/api/resident', {
                method: 'GET'
            });

            const residents = await response.json();
            this.residents = residents.residents;
        },
        async getPeriods() {
            const response = await fetch('http://127.0.0.1:8000/api/period', {
                method: 'GET'
            });

            const request = await response.json();
            this.periods = request.periods;
            this.periodsData = request.periods.map(function (obj) {
                return obj.month;
            });
        },
        async getRates() {

            const response = await fetch('http://127.0.0.1:8000/api/rate', {
                method: 'GET',
            });

            const request = await response.json();
            if (request.status == 'success') {
                this.rates = request.rates;
            } else {
                console.log('Some errors')
            }

        },
        async createBill() {

            const bill = new FormData();

            bill.append('resident_id', this.resident.id);
            bill.append('period_id', this.period.id);
            bill.append('amount_rub', this.bill.amount_rub);


            const response = await fetch('http://127.0.0.1:8000/api/bill', {
                method: 'POST',
                body: bill,
            });

            const request = await response.json();

            if (request.status == 'success') {
                this.bills.push(this.bill);
                this.visible = !this.visible;
                this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
            } else {
                this.$toast.add({ severity: 'warning', detail: 'Дачник не добавлен', life: 3000 });
            }
        }
    },

};

</script>

<template>
    <div class="wrapperformCreate" v-if="visible">
        <form @submit.prevent="createBill()" class="formCreate">
            <ButtonComponent class="closeButton" label="Закрыть" @click="visible = !visible" />
            <Divider align="center" type="solid">
                <b>Создать счёт</b>
            </Divider>
            <label for="resident_id">Дачник</label>
            <Dropdown v-model="resident" inputId="resident_id" placeholder="Выберите дачника" :options="residents"
                optionLabel="fio" />
            <!-- <select name="resident_id" v-model="bill.resident_id">
                <option v-for="resident, index of residents" :key="index" :value="resident.id">{{ resident.fio + ' - Доля: '
                    + totalArea / 100 * resident.area + ' %' }}
                </option>
            </select> -->
            <label for="period_id">Период</label>
            <Dropdown v-model="period" inputId="period_id" placeholder="Выберите период" :options="periods"
                optionLabel="month" />
            <!-- <label for="period_id">Период</label>
            <select name="period_id" v-model="bill.period_id">
                <option v-for="period, index of periods" :key="index" :value="period.id">{{ period.begin_date + ' - ' +
                    period.end_date }}
                </option>
            </select> -->

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
                <b> Объём потраченной воды <sup>3</sup> за {{ period.month }} = {{ totalVolumeWater }} куб<sup>3</sup> </b>
            </Divider>
            <Divider align="center" type="solid">
                <b> Стоимость куб <sup>3</sup> за {{ period.month }} = {{ priceForCurrentPeriod }} &#8381; </b>
            </Divider>
            <Divider align="center" type="solid">
                <b style="font-weight: bold;"> Итого к оплате - {{ totalCurrentPrice }} &#8381; </b>
            </Divider>
            <label for="amount_rub">Сумма к оплате (руб)</label>
            <InputNumber class="p-input" :placeholder="totalCurrentPrice" type="number" min="0" step="0.01"
                name="amount_rub" v-model="bill.amount_rub" inputId="minmaxfraction" :minFractionDigits="2"
                :maxFractionDigits="5" />
            <ButtonComponent type="submit" size="large" class="p-input" label="Выставить счёт" />
        </form>
    </div>
    <div class="card">
        <Toast />
        <div style="display: flex; flex: row nowrap; justify-content: space-between; align-items: center">
            <Divider align="left" type="solid">
                <b>Счета</b>
            </Divider>
            <ButtonComponent label="Добавить" @click="visible = !visible" />
        </div>
        <DataTable v-model:filters="filters" filterDisplay="row" :globalFilterFields="['month']" :value="bills" paginator
            :rows="5" stripedRows tableStyle="min-width: 50rem" class="p-datatable"
            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
            currentPageReportTemplate="{first} to {last} of {totalRecords}">
            <Column field="id" sortable header="ID">

            </Column>
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
            <Column field="period_end" sortable header="Конец периода">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>
            <Column field="month" sortable header="Период" :showFilterMenu="false">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <Dropdown v-model="filterModel.value" @change="filterCallback()" :options="periodsData"
                        placeholder="Выберите период">
                    </Dropdown>
                </template>
            </Column>
            <Column field="amount_rub" sortable header="Сумма к оплате">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>
        </DataTable>
        <!-- <hr>
        <table summary="This table shows how to create responsive tables using RWD-Table-Patterns' functionality"
            class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="font-weight: bold;" v-for="th, index of thead" :key="index"> {{ th }} </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="bill, index of bills" :key="index">
                    <td>{{ bill.id }}</td>
                    <td>{{ bill.resident_id }}</td>
                    <td>{{ bill.period_start }} - {{ bill.period_end }}</td>
                    <td>{{ bill.amount_rub }}</td>
                    <td><img src="../assets/mode_edit_24px.png" alt=""></td>
                </tr>

            </tbody>
        </table> -->
    </div>
</template>
