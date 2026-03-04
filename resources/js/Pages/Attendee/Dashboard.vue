<template>
  <Head title="Dashboard" />

  <section v-if="registrations.data.length" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
    <Box v-for="registration in registrations.data" :key="registration.id" :class="{ 'border-dashed' : registration.deleted_at }">
      <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
        <div :class="{ 'opacity-25' : registration.deleted_at }">
          <StatusBadge v-if="registration.status != null" :status="registration.status"/>
          <div class="xl:flex items-center gap-2">
            <Price :price="registration.event.price" class="text-2xl font-medium" />
            <EventCapacity :event="registration.event" />
          </div>

          <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            {{ registration.event.title }}
          </h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Start : {{ formatDateTime(registration.event.start_datetime) }}
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            End : {{ formatDateTime(registration.event.end_datetime) }}
          </p>
          <EventLocation :event="registration.event" />
        </div>

        <section>
          <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300">
            <a class="text-xs font-medium" :href="route('attendee.event.show', { event: registration.event.id})" target="_blank">
              <Button variant="outline" class="btn-color">Preview</Button>
            </a>
            <ConfirmDialog v-if="!registration.deleted_at && registration.status !== 'cancelled'" title="Delete Registration?" description="Are you sure? This will cancel the registration and notify organiser."
              :onConfirm="() => cancel(registration.id)">
              <template #trigger>
                <Button variant="outline" class="btn-color font-medium">
                  Cancel
                </Button>
              </template>
            </ConfirmDialog>
          </div>
        </section>
      </div>
    </Box>
  </section>

  <div v-else class="py-12">
    <div class="max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-md sm:rounded-lg">
        <div class="p-6 text-gray-900">
          You have not registered for any events yet.
        </div>
      </div>
    </div>
  </div>

  <div></div>
    
</template>

<script setup lang="ts">
import Box from '@/Components/Display/Box.vue';
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue';
import EventCapacity from '@/Components/Display/EventCapacity.vue';
import EventLocation from '@/Components/Display/EventLocation.vue';
import Price from '@/Components/Display/Price.vue';
import StatusBadge from '@/Components/Display/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useDateFormat } from '@/useFormatDate';
import { Head, Link, router } from '@inertiajs/vue3';

defineOptions({
  layout: MainLayout
})

const props = defineProps<{
  registrations: Object
}>()
const { formatDateTime } = useDateFormat()
const cancel = (id) => {
  router.patch(route('registration.cancel', id))
}
</script>
