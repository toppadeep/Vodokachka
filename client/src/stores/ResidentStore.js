import { defineStore } from 'pinia'

export const useResidentStore = defineStore({
  id: 'resident',
  state: () => ({
    residents: [],
  }),
  actions: {
    
    async getResidents() {

        const response = await fetch('http://127.0.0.1:8000/api/resident', {
          method: 'GET'
        })
        const request = await response.json();
        this.residents = request.residents;
      },
  },
})
