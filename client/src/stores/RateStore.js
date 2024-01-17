import {
  defineStore
} from 'pinia'

export const useRateStore = defineStore({
  id: 'rate',
  state: () => ({
    rates: [],
  }),
  actions: {

    async getRates() {

      const response = await fetch('http://localhost:8000/api/rate', {
        method: 'GET'
      })
      const request = await response.json();
      this.rates = request.rates;
    },
  },
})