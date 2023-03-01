<script lang="ts">
import { toRefs } from "vue";

export default {
  props: {
    payload: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const { payload } = toRefs(props);
    console.log(payload);
    return { data: payload };
  },
};
</script>

<template>
  <div class="border p-3 rounded-md w-3/12">
    <h3 class="text-2xl font-bold">
      {{ data.longitude }}, {{ data.latitude }}
    </h3>
    <h1>Weather: 30F</h1>
    <h2>Humidity: 50%</h2>
    <p>{{ data.name }} d</p>
  </div>
</template>
