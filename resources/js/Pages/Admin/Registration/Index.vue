<template>
  <Box>
    <template #header>
      Registration Management
    </template>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mt-4">
      <select v-model="filterForm.event" @change="applyFilter" class="input-filter w-40">
        <option value="">All Events</option>
        <option v-for="event in events" :key="event.id" :value="event.id">{{ event.title }}</option>
      </select>

      <select v-model="filterForm.attendee" @change="applyFilter" class="input-filter w-40">
        <option value="">All Attendees</option>
        <option v-for="attendee in attendees" :key="attendee.id" :value="attendee.id">{{ attendee.name }}</option>
      </select>

      <select v-model="filterForm.status" @change="applyFilter" class="input-filter w-40">
        <option value="">All Status</option>
        <option value="confirmed">Confirmed</option>
        <option value="pending">Pending</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <!-- Registrations Table -->
    <div class="overflow-x-auto mt-4">
      <table class="w-full text-left table-auto border border-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2">Event</th>
            <th class="px-4 py-2">Attendee</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Registered At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="registration in registrations.data" :key="registration.id" class="border-b hover:bg-gray-50">
            <td class="pr-4 py-4">
              <Link :href="`/admin/event/${registration.event.id}`" class="hover:bg-indigo-100 mx-2 px-3 py-3 rounded-lg hover:underline">
                {{ registration.event.title }}
              </Link>
            </td>
            <td class="px-4 py-2">{{ registration.attendee.name }}</td>
            <td class="px-4 py-2">
              <StatusBadge v-if="registration.status != null" :status="registration.status"/>
            </td>
            <td class="px-4 py-2">{{ formatDateTime(registration.created_at) }}</td>
          </tr>

          <tr v-if="registrations.data.length === 0">
            <td colspan="4" class="text-center p-4 text-gray-500">
              No registrations found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <section v-if="registrations.data.length" class="w-full flex justify-center mt-8 mb-10">
      <Pagination :links="registrations.links" />
    </section>
  </Box>
</template>

<script setup>

import { ref, computed, watch, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Pagination from '@/Components/Display/Pagination.vue'
import { debounce } from 'lodash'
import Box from '@/Components/Display/Box.vue'
import { useDateFormat } from '@/useFormatDate'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import StatusBadge from '@/Components/Display/StatusBadge.vue'

const { formatDateTime } = useDateFormat()

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  registrations: Object,
  events: Array,
  attendees: Array,
  filters: Object
})

const filterForm = reactive({
  event: props.filters.event || '',
  attendee: props.filters.attendee || '',
  status: props.filters.status || ''
})

const applyFilter = debounce(() => {
  router.get(route('admin.registration.index'), filterForm, {
    preserveState: true,
    preserveScroll: true
  })
}, 500)

watch(filterForm, () => applyFilter(), { deep: true })

</script>