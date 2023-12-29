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

export default {
    data() {
        return {
            loading: true,
            visible: false,
            periods: [],
            period: {},
            rates: [],
            rate: {
                id: null,
                period_id: null,
                amount_price: null,
            },
        }
    },
    async mounted() {
        await this.getRates();
        await this.getPeriods();
        this.loading = false;
    },
    components: {
        DataTable,
        Column,
        ButtonComponent,
        Divider,
        Skeleton,
        InputNumber,
        Dropdown,
        Toast
    },
    methods: {
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
        async getPeriods() {
            const response = await fetch('http://127.0.0.1:8000/api/period', {
                method: 'GET',
            });

            const request = await response.json();
            if (request.status == 'success') {
                this.periods = request.periods;
            } else {
                console.log('Some errors')
            }
        },
        async createRate() {

            const rate = new FormData();

            rate.append('period_id', this.period.id);
            rate.append('amount_price', this.rate.amount_price);

            const response = await fetch('http://127.0.0.1:8000/api/rate', {
                method: 'POST',
                body: rate,
            });

            const request = await response.json();
            if (request.status == 'success') {
                this.rates.push(this.rate);
                this.visible = !this.visible;
                this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
            } else {
                this.$toast.add({ severity: 'warning', detail: 'Дачник не добавлен', life: 3000 });
            }
        },
    },
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
            <Dropdown v-model="period" inputId="period_id" name="period_id" placeholder="Выберите период" :options="periods"
                optionLabel="month" />
            <!-- <select name="period_id" v-model="rate.period_id">
                <option v-for="period, index of periods" :key="index" :value="period.id">{{ period.begin_date + ' - ' +
                    period.end_date }}
                </option>
            </select> -->
            <label for="amount_price">Стоимость кубометра воды</label>
            <InputNumber class="p-input" type="number" placeholder="0.0" min="0" name="amount_price"
                v-model="rate.amount_price" inputId="minmaxfraction" :minFractionDigits="2" :maxFractionDigits="5" />
            <ButtonComponent type="submit" label="Добавить" size="large" class="p-input" />
        </form>
    </div>
    <div class="card">
        <Toast />
        <div style="display: flex; flex: row nowrap; justify-content: space-between; align-items: center">
            <Divider align="left" type="solid">
                <b>Тарифы</b>
            </Divider>
            <ButtonComponent label="Добавить" @click="visible = !visible" />
        </div>
        <DataTable :value="rates" paginator :rows="5" stripedRows tableStyle="min-width: 50rem" class="p-datatable"
            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
            currentPageReportTemplate="{first} to {last} of {totalRecords}">
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
            <Column field="amount_price" sortable header="Цена тарифа ( &#x20bd; )">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>
            <Column field="create_date" sortable header="Дата создания">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>
        </DataTable>
        <!-- <table summary="This table shows how to create responsive tables using RWD-Table-Patterns' functionality"
            class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="font-weight: bold" v-for="(th, index) of thead" :key="index">{{ th }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(rate, index) of rates" :key="index">
                    <td>{{ rate.id }}</td>
                    <td>{{ rate.period_id }}</td>
                    <td>{{ rate.amount_price }}</td>
                    <td>{{ rate.create_date }}</td>
                    <td><img src="../assets/mode_edit_24px.png" alt="" /></td>
                </tr>
            </tbody>
        </table> -->
    </div>
</template>
