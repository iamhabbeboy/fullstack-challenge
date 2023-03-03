import {defineStore} from "pinia";

const BASE_URL = "http://localhost/";

export const useUserStore = defineStore("user", {
  state: () => ({
    users: [],
    loading: false,
    error: "",
  }),
  getters: {},
  actions: {
    async fetchData() {
      this.loading = true;
      try {
        this.users = await (await fetch(BASE_URL)).json();
      } catch (e) {
        this.error = (e as Error).message;
      } finally {
        this.loading = false;
      }
    },
    async fetchUserById(userId: number) {
      this.loading = true;
      try {
        this.users = await (await fetch(`${BASE_URL}${userId}`)).json();
      } catch (e) {
        this.error = (e as Error).message;
      } finally {
        this.loading = false;
      }
    },
  },
});
