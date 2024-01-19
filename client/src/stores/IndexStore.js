import { defineStore } from 'pinia'
export const useIndexStore = defineStore({
  id: 'index',
  state: () => ({
    visible: false
  }),
  actions: {
    openDialog() {
      this.visible = !this.visible
    }
  }
})
