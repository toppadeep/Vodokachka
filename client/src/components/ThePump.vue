<script>
import '../assets/main.css'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ButtonComponent from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputNumber from 'primevue/inputnumber';
import Divider from 'primevue/divider';
import Skeleton from 'primevue/skeleton';
import Toast from 'primevue/toast';

export default {
    data() {
        return {
            loading: true,
            visible: false,
            defaultOption: 'Выберите период',
            periods: [],
            pumps: [],
            period: {},
            pump: {
                id: 'NEW',
                period_id: '',
                amount_volume: ''
            },
        }
    },
    async mounted() {
        await this.getPumps();
        await this.getPeriods();
        this.loading = false;
    },
    components: {
        DataTable,
        Column,
        ButtonComponent,
        Dropdown,
        InputNumber,
        Divider,
        Skeleton,
        Toast
    },
    methods: {
        async getPumps() {
            const response = await fetch('http://127.0.0.1:8000/api/pump', {
                method: 'GET'
            });

            const request = await response.json()
            if (request.status == 'success') {
                this.pumps = request.pumps
            } else {
                console.log('Happend some error')
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
                console.log('Happend some error')
            }
        },
        async createPump() {
            const pump = new FormData()
            pump.append('period_id', this.period.id);
            pump.append('amount_volume', this.pump.amount_volume);

            const response = await fetch('http://127.0.0.1:8000/api/pump', {
                method: 'POST',
                body: pump,
            });

            const request = await response.json()

            if (request.status == 'success') {
                this.visible = !this.visible;
                this.$toast.add({ severity: 'success', summary: 'Успешно', detail: request.response, life: 3000 });
            } else {
                this.visible = !this.visible;
                this.$toast.add({ severity: 'warning', detail: request.response, life: 3000 });
            }

        },
    },
}
</script>

<template>
    <div class="wrapperformCreate" v-if="visible">
        <form @submit.prevent="createPump()" class="formCreate">
            <ButtonComponent class="closeButton" label="Закрыть" @click="visible = !visible" />
            <Divider align="center" type="solid">
                <b>Внести новые показания счётчика</b>
            </Divider>
            <label for="period_id">Период</label>
            <Dropdown v-model="period" inputId="period_id" placeholder="Выберите период" :options="periods"
                optionLabel="month" />
            <!-- <label for="period_id">Период</label>
            <select name="period_id" v-model="pump.period_id">
                <option value="Выберите период" disabled> {{ defaultOption }} </option>
                <option v-for="period, index of periods" :key="index" :value="period.id">{{ period.begin_date + ' - ' +
                    period.end_date }}
                </option>
            </select> -->
            <label for="amount_volume">Показания на текущий период</label>
            <inputNumber class="p-input" type="number" placeholder="0.0" min="0" name="amount_volume"
                v-model="pump.amount_volume" inputId="minmaxfraction" :minFractionDigits="2" :maxFractionDigits="5" />
            <ButtonComponent type="submit" label="Добавить" size="large" class="p-input" />
        </form>
    </div>
    <div class="card">
        <Toast />
        <div style="display: flex; flex: row nowrap; justify-content: space-between; align-items: center">
            <Divider align="left" type="solid">
                <b>Показания счётчика</b>
            </Divider>
            <ButtonComponent label="Добавить" @click="visible = !visible" />
        </div>
        <DataTable :value="pumps" paginator :rows="5" stripedRows tableStyle="min-width: 50rem" class="p-datatable"
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
            <Column field="amount_volume" sortable header="Показания">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
