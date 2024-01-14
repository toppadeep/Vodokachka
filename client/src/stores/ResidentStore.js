import { defineStore } from 'pinia'

export const useResidentStore = defineStore({
  id: 'resident',
  state: () => ({
    residents: [],
    errors: [],
  }),
  actions: {
    async getResidents() {
      const response = await fetch('http://127.0.0.1:8000/api/resident', {
        method: 'GET'
      })
      const request = await response.json()
      this.residents = request.residents
    },
    async onRowEditSave(event) {
      let { newData } = event
      const resident = new FormData()
      resident.append('fio', newData.fio)
      resident.append('area', newData.area)
      resident.append('start_date', newData.start_date)

      const response = await fetch(`http://127.0.0.1:8000/api/resident/update/${newData.id}`, {
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
      } else {
        this.errors = request.errors
        this.$toast.add({ severity: 'error', detail: 'Данные не обновлены', life: 3000 })
      }
    },
  }
})
