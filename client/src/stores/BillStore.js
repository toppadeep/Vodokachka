import { defineStore } from 'pinia'
export const useBillStore = defineStore({
  id: 'bill',
  state: () => ({
    bills: []
  }),
  actions: {
    async getBills() {
      const response = await fetch('http://localhost:8000/api/bill', {
        method: 'GET'
      })
      const request = await response.json()
      this.bills = await request.bills
    }
  }
})
