<script lang="ts">
import WeatherCard from "@/components/WeatherCard.vue";
import { storeToRefs } from 'pinia'
import { useUserStore } from '../stores/user'
import { useRouter } from "vue-router";

export default {
  components: {
    WeatherCard,
  },
  setup() {
    const store = useUserStore();
    const { users, loading, error } = storeToRefs(store);
    const { fetchData } = useUserStore();

    if (!users.value.length) {
      fetchData();
    }

    setInterval(fetchData, 60 * 60 * 1000);

    const router = useRouter();

    const handleNavigation = (userId: number) => {
      if (!userId) {
        return;
      }
      router.push(`/${userId}`);
    }

    return {
      error,
      users,
      loading,
      handleNavigation,
    };
  },
};
</script>

<template>
  <main class="container mx-auto">
    <div>
      <h1 class="text-3xl py-2 text-cyan-600">User Info</h1>
    </div>
    <div v-if="loading && !users.users">Pinging the api...</div>
    <div v-if="error">{{ error }}</div>
    <section class="flex flex-wrap py-5 justify-between" v-if="users">
      <WeatherCard
        @handle-click="handleNavigation"
        v-for="(value, index) in users.users"
        :key="index"
        :payload="value"
      />
    </section>
  </main>
</template>
