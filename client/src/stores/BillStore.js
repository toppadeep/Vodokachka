import { defineStore } from 'pinia'

export const useBillStore = defineStore({
  id: 'bill',
  state: () => ({
    bills: [],
  }),
  actions: {
    
    async getBills() {

        const response = await fetch('http://127.0.0.1:8000/api/bill', {
          method: 'GET'
        })
        const request = await response.json();
        this.bills = request.bills;
      },
  },
})
