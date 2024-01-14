import { defineStore } from 'pinia'

export const usePumpStore = defineStore({
  id: 'pump',
  state: () => ({
    pumps: [],
  }),
  actions: {
    
    async getPumps() {
        const response = await fetch('http://127.0.0.1:8000/api/pump', {
          method: 'GET'
        });
        
        const request = await response.json();
        this.pumps = await request.pumps;
      },
  },
})
