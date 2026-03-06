<template>
  <div class="flex flex-col gap-6 w-full">
    <!-- Filter Box -->
    <Box>
      <template #header>
        Filter Events
      </template>
      <div class="mt-2">
        <select v-model="filterForm.organiser" class="input-filter w-40">
          <option value="">All Organiser</option>
          <option v-for="organiser in organisers" :key="organiser.id" :value="organiser.id">{{ organiser.name }}</option>
        </select>
      </div>
    </Box>

    <!-- Events List Box -->
    <Box v-if="events.data.length">
      <template #header>
        Events Moderation
      </template>
      <div class="overflow-x-auto mt-2">
        <table class="w-full text-left table-auto">
          <thead>
            <tr class="border-b dark:border-gray-700">
              <th class="px-4 py-2">Event</th>
              <th class="px-4 py-2">Organiser</th>
              <th class="px-4 py-2">Date</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="event in events.data" :key="event.id"
              class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
              <td class="px-4 py-2">{{ event.title }}</td>
              <td class="px-4 py-2">{{ event.organiser?.name }}</td>
              <td class="px-4 py-2 capitalize">{{ formatDateTime(event.start_datetime) }}</td>
              <td class="px-4 py-2">
                <StatusBadge v-if="event.status != null" :status="event.status"/>
              </td>
              <td class="px-4 py-2">
                <ConfirmDialog v-if="!event.deleted_at && event.status != 'cancelled'" title="Cancel Event?" description="Are you sure? This will cancel the event and notify attendees."
                  :onConfirm="() => cancelEvent(event.id)">
                  <template #trigger>
                    <Button variant="secondary" class="my-2 rounded px-5 py-1 confirm-button">
                      Force Cancel
                    </Button>
                  </template>
                </ConfirmDialog>
                <ConfirmDialog v-if="!event.deleted_at" title="Delete Event?" description="Are you sure? This will delete the event permenantly"
                  :onConfirm="() => deleteEvent(event.id)">
                  <template #trigger>
                    <Button variant="secondary" class="my-2 rounded px-5 py-1 cancel-button">
                      Delete
                    </Button>
                  </template>
                </ConfirmDialog>
              </td>
              
            </tr>
          </tbody>
        </table>
      </div>
    </Box>

    <EmptyState v-else class="mt-2 mb-10">No events yet</EmptyState>

    <section v-if="events.data.length" class="w-full flex justify-center mt-10 mb-14">
      <Pagination :links="events.links" />
    </section>
  </div>

</template>


<script setup>
import { reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Box from '@/Components/Display/Box.vue';
import Pagination from '@/Components/Display/Pagination.vue';
import EmptyState from '@/Components/Display/EmptyState.vue';
import { debounce } from 'lodash';
import { Button } from '@/components/ui/button';
import StatusBadge from '@/Components/Display/StatusBadge.vue';
import { useDateFormat } from '@/useFormatDate';
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue';

const { formatDateTime } = useDateFormat()

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  events: Object,
  organisers: Array,
  filters: Object
});

const filterForm = reactive({
  organiser: props.filters.organiser ?? ''
})

watch(
  filterForm, debounce(() => router.get(
    route('admin.event.index'),
    filterForm,
    { preserveState: true, preserveScroll: true },
  ), 1000),
)

function cancelEvent(id) {
  router.patch(route('admin.event.cancel', id))
}

function deleteEvent(id) {
  router.delete(route('admin.event.destroy', id))
}
</script>