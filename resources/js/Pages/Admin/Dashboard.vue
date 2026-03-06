<template>

  <Head title="Dashboard" />

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <StatCard title="Total Users" :value="stats.total_users" />
    <StatCard title="Events This Month (Published)" :value="stats.events_this_month" />
    <StatCard title="Registrations This Month (Confirmed)" :value="stats.registrations_this_month" />
    <StatCard title="Revenue This Month" :value="stats.revenue_this_month" />

  </div>

</template>

<script setup lang="ts">
import StatCard from '@/Components/Display/StatCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({
  layout: DashboardLayout
})

interface Stats {
  total_users: number
  events_this_month: number
  registrations_this_month: number
  revenue_this_month: number
}

const props = defineProps<{
  stats: Stats
}>()
</script>