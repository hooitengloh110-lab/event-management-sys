<template>
  <Head title="Event" />
  <EventDetailCard :event="event"></EventDetailCard>
</template>

<script setup lang="ts">
import EventDetailCard from '@/Components/Display/EventDetailCard.vue';
import { Separator } from '@/Components/ui/separator';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useDateFormat } from '@/useFormatDate';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const { formatDateTime } = useDateFormat()

defineOptions({
  layout: MainLayout
})

const props = defineProps({
  event: Object
})

</script>