import { defineStore } from 'pinia'
export const useResidentStore = defineStore({
  id: 'resident',
  state: () => ({
    residents: [],
    errors: []
  }),

  actions: {
    async getResidents() {
      const response = await fetch('http://localhost:8000/api/resident', {
        method: 'GET'
      })
      const request = await response.json()
      this.residents = await request.residents
    }
  }
})
