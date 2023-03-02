import { defineStore } from "pinia";
import type { User } from "@/UserType";

export const useUserStore = defineStore("user", {
  state: () => ({
    users: [],
    loading: false,
    error: "",
  }),
  getters: {
  },
  actions: {
    async fetchData() {
      this.loading = true;
      try {
        const url = "http://localhost/";
        this.users = await (await fetch(url)).json();
      } catch (e) {
        this.error = (e as Error).message;
      } finally {
        this.loading = false;
      }
    },
    async fetchUserById(userId: number) {
      this.loading = true;
      try {
        const url = `http://localhost/${userId}`;
        this.users = await (await fetch(url)).json();
      } catch (e) {
        this.error = (e as Error).message;
      } finally {
        this.loading = false;
      }
    },
  },
});
