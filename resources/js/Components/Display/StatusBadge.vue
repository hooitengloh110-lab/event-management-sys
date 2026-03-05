<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ status: string | null }>()

// Define styles for each status
const statusStyles: Record<string, string> = {
  published: 'border border-dashed border-green-300 text-green-500 dark:border-green-600 dark:text-green-600',
  draft: 'border border-dashed border-yellow-300 text-yellow-500 dark:border-yellow-600 dark:text-yellow-400',
  cancelled: 'border border-dashed border-red-300 text-red-500 dark:border-red-600 dark:text-red-400',
  pending: 'border border-dashed border-yellow-300 text-yellow-500 dark:border-yellow-600 dark:text-yellow-600',
  confirmed: 'border border-dashed border-green-300  text-green-500 dark:border-green-600 dark:text-green-600',  
}

// Compute the correct class dynamically
const badgeClass = computed(() => {
  return statusStyles[props.status ?? ''] ?? 'border border-dashed border-gray-300 text-gray-500 dark:border-gray-600 dark:text-gray-400'
})

// Format status text (capitalize)
const formattedStatus = computed(() => {
  if (!props.status) return ''
  return props.status.charAt(0).toUpperCase() + props.status.slice(1)
})
</script>

<template>
  <div
    v-if="status"
    class="text-xs font-bold uppercase p-1 inline-block rounded-md mb-2"
    :class="badgeClass"
  >
    {{ formattedStatus }}
  </div>
</template>