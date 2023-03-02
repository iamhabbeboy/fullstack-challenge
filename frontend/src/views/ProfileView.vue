<script lang="ts">
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import WeatherCard from "@/components/WeatherCard.vue";

export default {
  components: {
    WeatherCard,
  },
  setup() {
    const { users, loading, error } = storeToRefs(useUserStore());
    const { fetchUserById } = useUserStore();
    const route = useRoute();
    const router = useRouter();
    const userId = route.params.id;
    if (!userId) {
      router.push("/");
    }
    let getUserById = ref();

    if (userId && users.value && users.value.users) {
      getUserById.value = computed(() => users.value && users.value.users.find((user) => user.id === Number(userId)));
    }

    if (!getUserById.value) {
      fetchUserById(Number(userId));
    }

    return {
      userId,
      error,
      users,
      loading,
      getUserById,
    };
  },
};
</script>

<template>
  <main class="container mx-auto">
    <div>
      <RouterLink to="/" class="text-2xl">&laquo; Back</RouterLink>
    </div>
    <section class="w-9/12 mx-auto">
      <div v-if="loading">Pinging the api...</div>
      <div v-if="error">{{ error }}</div>
      <WeatherCard
        v-if="!loading && users"
        :payload="getUserById || users.users"
        has-full-details="true"
      />
    </section>
  </main>
</template>
