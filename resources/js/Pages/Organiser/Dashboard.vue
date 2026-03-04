<script setup lang="ts">
import Pagination from '@/Components/Display/Pagination.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { DefineComponent } from 'vue'
import { formatCurrency } from '@/useFormatCurrency'
import StatCard from '@/Components/Display/StatCard.vue';
import Button from '@/components/ui/button/Button.vue';


interface Stats {
  total_events: number
  total_registrations: number
  total_revenue: number
}

interface Event {
  id: number
  title: string
  start_datetime: string
  price: number
  registrations_count: number
  revenue: number
}

interface PaginatedEvents {
  data: Event[]
  links: any[]
}

defineOptions({
  layout: DashboardLayout
})

const props = defineProps<{
  stats: Stats,
  upcomingEvents: PaginatedEvents
}>()

const formatDate = (date: string) => {
  let cleanStr = date
    .replace('Z', '')
    .split('.')[0]  
  const localDate = cleanStr.replace(' ', 'T')
  return new Date(localDate).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  });
}

const create = () => {
  console.log("Ss");
  // form.get(route('event.create'))
}
</script>

<template>

  <Head title="Dashboard" />
  <div class="flex justify-end mb-5">
    <Link :href="route('event.create')">
      <Button variant="outline" size="default" class="bg-gray-200 hover:bg-gray-100 dark:bg-gray-700">+ New Event</Button>
    </Link>
  </div>
  
  <div v-if="stats.total_events === 0" class="py-12">
    <div class="max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-md sm:rounded-lg">
        <div class="p-6 text-gray-900">
          You're logged in!
        </div>
      </div>
    </div>
  </div>

  <div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <StatCard title="Total Events" :value="stats.total_events" />
      <StatCard title="Total Registrations" :value="stats.total_registrations" />
      <StatCard title="Total Revenue">
        {{ formatCurrency(stats.total_revenue) }}
      </StatCard>
    </div>

    <!-- Upcoming Events -->
    <Card>
      <CardHeader>
        <CardTitle>Upcoming Events</CardTitle>
      </CardHeader>

      <CardContent>
        <div v-if="upcomingEvents.data.length === 0">
          No upcoming events.
        </div>

        <div v-for="event in upcomingEvents.data" :key="event.id" class="flex justify-between border-b py-2">
          <div>
            <p class="font-medium">{{ event.title }}</p>
            <p class="text-sm text-muted-foreground">
              {{ formatDate(event.start_datetime) }}
            </p>
          </div>

          <div class="font-semibold">
            {{ formatCurrency(event.revenue ?? 0) }}
          </div>
        </div>

        <section v-if="upcomingEvents.data.length" class="w-full flex justify-center mt-6">
          <Pagination :links="upcomingEvents.links" />
        </section>
      </CardContent>
    </Card>

  </div>

</template>
