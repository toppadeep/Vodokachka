import {
  defineStore
} from 'pinia'

export const useIndexStore = defineStore({
  id: 'index',
  state: () => ({
    loading: true,
    visible: false,
    errors: [],
  }),
  actions: {
    openDialog() {
      this.visible = !this.visible
    },
    switchLoading() {
      this.loading = false
    }
  },
})