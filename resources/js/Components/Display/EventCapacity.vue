<template>
  <div class="flex items-center gap-2">
    <p class="font-bold">{{ available }}</p> Seats

    <p v-if="available != 0" class="flex text-red-500">
      - {{ available }} available
    </p>
    <p v-else class="flex text-red-500">
      Fully registered !
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  event: Object,
})
const cancelled = computed(() => props.event?.cancelled_registrations_count ?? 0)
const registered = computed(() => {
  const total = (props.event?.registrations_count || props.registrations?.data.length || props.event.registrations?.length)?? 0
  console.log(total, cancelled.value);
  return total - cancelled.value
})
const available = computed(() => {
  const cap = props.event?.capacity ?? 0
  return cap - registered.value
})
</script>