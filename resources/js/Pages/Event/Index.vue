<template>
  <h1 class="text-3xl mb-4">Your Events</h1>
  <section>
    <OrganiserEventFilter :filters="filters" />
  </section>
  <section v-if="events.data.length" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
    <Box v-for="event in events.data" :key="event.id" :class="{ 'border-dashed' : event.deleted_at }">
      <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
        <div :class="{ 'opacity-25' : event.deleted_at }">
          <StatusBadge v-if="event.status != null" :status="event.status"/>
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
import { Button } from '@/Components/ui/button';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { useDateFormat } from '@/useFormatDate.js'
import OrganiserEventFilter from '@/Components/Display/OrganiserEventFilter.vue';
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/Components/ui/alert-dialog';

const { formatDateTime } = useDateFormat()

const props = defineProps({
  events: Object,
  filters: Object,
})

defineOptions({
  layout: DashboardLayout
})

const confirmDelete = (eventId) => {
  router.delete(route('event.destroy', { event: eventId }))
}

</script>