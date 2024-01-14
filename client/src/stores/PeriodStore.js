import { defineStore } from 'pinia'

export const usePeriodStore = defineStore({
  id: 'period',
  state: () => ({
    periods: [],
  }),
  actions: {
    async getPeriods() {

        const response = await fetch('http://127.0.0.1:8000/api/period', {
          method: 'GET'
        })
        const request = await response.json();
        this.periods = request.periods;
      },
  },
})
