<template>
  <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden transition hover:shadow-lg">
    
    <!-- Event Image -->
    <img
      :src="event.image ?? '/placeholder.jpg'"
      class="w-full h-48 object-cover"
      alt="Event Image"
    />

    <div class="p-4 space-y-2">
      <!-- Title -->
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
        {{ event.title }}
      </h3>

      <!-- Date -->
      <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ formatDate(event.event_date) }}
      </p>

      <!-- Location -->
      <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ event.location }}
      </p>

      <!-- Price -->
      <p class="font-bold text-primary">
        RM {{ event.price }}
      </p>

      <!-- Button -->
      <Link
        :href="route('events.show', event.id)"
        class="block text-center bg-primary text-white py-2 rounded-lg hover:opacity-90 transition"
      >
        View Details
      </Link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Event {
  id: number
  title: string
  event_date: string
  location: string
  price: number
  image?: string
}

const props = defineProps<{
  event: Event
}>()

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString()
}
</script>