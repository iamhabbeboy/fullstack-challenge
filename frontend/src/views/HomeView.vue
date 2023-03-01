<script lang="ts">
import Card from "@/components/Card.vue";

export default {
  components: {
    Card,
  },
  data: () => ({
    apiResponse: null,
  }),

  created() {
    this.fetchData();
  },

  methods: {
    async fetchData() {
      const url = "http://localhost/";
      this.apiResponse = await (await fetch(url)).json();
    },
  },
};
</script>

<template>
  <main class="container mx-auto">
    <div>
      <h3>Filter</h3>
    </div>
    <div v-if="!apiResponse">Pinging the api...</div>
    <section class="flex flex-wrap py-10 justify-between">
      <Card
        v-for="(value, index) in apiResponse.users"
        :key="index"
        :payload="value"
      />
    </section>
  </main>
</template>
