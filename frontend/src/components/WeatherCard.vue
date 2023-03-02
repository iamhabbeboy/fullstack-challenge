<script lang="ts">
import {computed, toRefs} from "vue";

export default {
  props: {
    payload: {
      type: Object,
      required: true,
    },
    hasFullDetails: {
      type: Boolean,
    },
  },
  setup(props, { emit }) {
    const { payload } = toRefs(props);
    const handleNavigationAction = () => {
      emit("handle-click", payload.value.id);
    };

    return {
      data: payload,
      handleNavigationAction,
    };
  },
};
</script>

<template>
  <div
    :class="`border hover:border-blue-300 cursor-pointer rounded-md ${hasFullDetails ? 'text-center' : 'w-3/12'} `"
    @click="handleNavigationAction"
  >
    <div class="p-3">
      <img v-if="data && data.weather && data.weather.weather" :src="`http://openweathermap.org/img/wn/${data.weather.weather[0].icon}@2x.png`"  alt=""/>
      <h3 class="text-2xl font-bold" v-if="data && data.weather">
        {{ data && data.weather && data.weather.weather[0].main }}
      </h3>
      <span v-if="hasFullDetails">
        <h2>Temperature: {{ data && data.weather.main.temp }}</h2>
        <h2>Humidity: {{ data && data.weather.main.humidity }}</h2>
        <h2>Clouds: {{ data && data.weather.clouds.all }}</h2>
        <h2>Timezone: {{ data && data.weather.timezone }}</h2>
      </span>
    </div>
    <div class="border-t p-3">
      <p>{{ data && data.name }}</p>
      <p v-if="hasFullDetails">{{ data && data.email }}</p>
    </div>
  </div>
</template>
