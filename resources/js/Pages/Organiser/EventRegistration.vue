<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'

import { Table, TableHeader, TableHead, TableRow, TableBody, TableCell } from '@/Components/ui/table'

import { Button } from '@/Components/ui/button'
import EmptyState from '@/Components/Display/EmptyState.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Card from '@/Components/ui/card/Card.vue';
import CardHeader from '@/Components/ui/card/CardHeader.vue';
import CardTitle from '@/Components/ui/card/CardTitle.vue';
import CardDescription from '@/Components/ui/card/CardDescription.vue';
import Pagination from '@/Components/Display/Pagination.vue';
import EventDetailCard from '@/Components/Display/EventDetailCard.vue';
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue';

defineOptions({
  layout: DashboardLayout
})

defineProps({
  registrations: Object,
  event: Array
})

const confirm = (id) => {
  router.patch(route('event.registration.confirm', id))
}

const cancel = (id) => {
  router.patch(route('event.registration.cancel', id))
}

const statusClass = (status) => {
  return {
    'border-yellow-300 text-yellow-600 bg-yellow-50':
      status === 'pending',

    'border-green-300 text-green-600 bg-green-50':
      status === 'confirmed',

    'border-red-300 text-red-600 bg-red-50':
      status === 'cancelled',
  }
}

</script>

<template>
  <Head title="Event Registration" />
  <Table v-if="registrations.data.length" class="mt-6">
    <TableHeader>
      <TableRow>
        <TableHead>Attendee</TableHead>
        <TableHead>Email</TableHead>
        <TableHead>Status</TableHead>
        <TableHead class="text-right">Actions</TableHead>
      </TableRow>
    </TableHeader>

    <TableBody>
      <TableRow
        v-for="registration in registrations.data"
        :key="registration.id"
      >
        <TableCell class="font-medium">
          {{ registration.attendee.name }}
        </TableCell>

        <TableCell>
          {{ registration.attendee.email }}
        </TableCell>

        <TableCell>
          <span
            class="px-2 py-1 text-xs rounded-md border"
            :class="statusClass(registration.status)"
          >
            {{ registration.status }}
          </span>
        </TableCell>

        <TableCell class="text-right space-x-2">
          <Button
            v-if="registration.status === 'pending'"
            size="sm" variant="secondary" class="confirm-button my-2"
            @click="confirm({registration: registration.id})"
          >
            Confirm
          </Button>

          <ConfirmDialog v-if="registration.status !== 'cancelled'" title="Cancel Registration?" description="Are you sure? This will cancel the registration and notify attendees."
            :onConfirm="() => cancel(registration.id)">
            <template #trigger>
              <Button size="sm" variant="destructive" class="cancel-button">
                Cancel
              </Button>
            </template>
          </ConfirmDialog>
        </TableCell>
      </TableRow>
    </TableBody>
  </Table>

  <EmptyState v-else class="mt-2 mb-10">No registrations yet</EmptyState>

  <section v-if="registrations.data.length" class="w-full flex justify-center mt-10 mb-14">
    <Pagination :links="registrations.links" />
  </section>

    <EventDetailCard :event="event" :registrations="registrations"></EventDetailCard>

</template>