<template>
  <EventDetailCard :event="event"></EventDetailCard>
</template>

<script setup lang="ts">
import EventDetailCard from '@/Components/Display/EventDetailCard.vue';
import { Separator } from '@/Components/ui/separator';
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { useDateFormat } from '@/useFormatDate';
import { computed } from 'vue';

const { formatDateTime } = useDateFormat()

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  event: Object
})

</script>