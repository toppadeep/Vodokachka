import { defineStore } from 'pinia'

export const useRateStore = defineStore({
  id: 'rate',
  state: () => ({
    rates: [],
  }),
  actions: {
    
    async getRates() {

        const response = await fetch('http://127.0.0.1:8000/api/rate', {
          method: 'GET'
        })
        const request = await response.json();
        this.rates = request.rates;
      },
  },
})
