<template>
  <div class="flex items-start gap-6">
    <h1 class="text-3xl mb-4">Your Events</h1>
    <button type="button" @click="showFilters = !showFilters"
      class="px-4 py-2 border rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-800">
      {{ showFilters ? 'Hide Filters' : 'Filter' }}
    </button>
  </div>
  <section>
    <OrganiserEventFilter :filters="filters" :index-route="indexRoute" :show-filters="showFilters"/>
  </section>
  <section v-if="events.data.length" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
    <Box v-for="event in events.data" :key="event.id" :class="{ 'border-dashed' : event.deleted_at }">
      <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
        <div :class="{ 'opacity-25' : event.deleted_at }">
          <div class="flex items-start flex-wrap gap-3">
            <StatusBadge v-if="event.status != null" :status="event.status"/>
            <Badge v-if="event.category != null" variant="outline" class="btn-outline py-1 uppercase">{{ event.category }}</Badge>
          </div>
          <div class="xl:flex items-center gap-2">
            <Price :price="event.price" class="text-2xl font-medium" />
            <EventCapacity :event="event" />
          </div>

          <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            {{ event.title }}
          </h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Start : {{ formatDateTime(event.start_datetime) }}
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            End : {{ formatDateTime(event.end_datetime) }}
          </p>
          <EventLocation :event="event" />
        </div>

        <section>
          <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300">
            <a class="text-xs font-medium" :href="route('event.show', { event: event.id })" target="_blank">
              <Button variant="outline" class="btn-color">Preview</Button>
            </a>
            <Link class="text-xs font-medium" :href="route('event.edit', { event: event.id })">
              <Button variant="outline" class="btn-color">Edit</Button>
            </Link>
            <ConfirmDialog v-if="!event.deleted_at" title="Delete Event?" description="Are you sure? This will cancel the event and notify attendees."
              :onConfirm="() => confirmDelete(event.id)">
              <template #trigger>
                <Button variant="outline" class="btn-color font-medium">
                  Delete
                </Button>
              </template>
            </ConfirmDialog>
          </div>

          <div class="mt-2">
            <Link :href="route('event.image.create', {event: event.id})" class="block w-full btn-outline text-xs font-medium text-center">
              {{ event.images_count === 1 ? 'Image' : 'Images' }} ({{ event.images_count }})
            </Link>
          </div>

          <div class="mt-2">
            <Link :href="route('event.registration.index', {event: event.id})" class="block w-full btn-outline text-xs font-medium text-center">
              Registered ({{ event.registrations_count||0 }})
            </Link>
          </div>
        </section>
      </div>
    </Box>
  </section>

  <EmptyState v-else>No events yet</EmptyState>

  <section v-if="events.data.length" class="w-full flex justify-center mt-4 mb-4">
    <Pagination :links="events.links" />
  </section>
</template>

<script setup>
import Box from '@/Components/Display/Box.vue';
import EmptyState from '@/Components/Display/EmptyState.vue';
import EventCapacity from '@/Components/Display/EventCapacity.vue';
import EventLocation from '@/Components/Display/EventLocation.vue';
import Pagination from '@/Components/Display/Pagination.vue';
import Price from '@/Components/Display/Price.vue';
import StatusBadge from '@/Components/Display/StatusBadge.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useDateFormat } from '@/useFormatDate.js'
import OrganiserEventFilter from '@/Components/Display/OrganiserEventFilter.vue';
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/Components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { ref, watch } from 'vue';
import Badge from '@/Components/ui/badge/Badge.vue';

const { formatDateTime } = useDateFormat()

const props = defineProps({
  events: Object,
  filters: Object,
  indexRoute: String
})

defineOptions({
  layout: DashboardLayout
})

const confirmDelete = (eventId) => {
  router.delete(route('event.destroy', { event: eventId }))
}

const showFilters = ref(
  localStorage.getItem('events_filter_open') === 'true'
)

watch(showFilters, (value) => {
  localStorage.setItem('events_filter_open', value)
})

</script>