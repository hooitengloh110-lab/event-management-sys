<template>
  <Card>
    <CardHeader>
      <div class="flex items-center gap-6">
        <h1 class="text-3xl font-bold">{{ event.title }}</h1>
        <StatusBadge v-if="event.status != null" :status="event.status"/>
      </div>
    </CardHeader>

    <div v-if="event.images.length" class="w-full px-8 pb-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 w-full">
        <img v-for="image in event.images" :key="image.id" :src="image.src" class="rounded-lg object-cover h-48 w-full" />
      </div>
    </div>
    <div v-else class="flex flex-col items-center justify-center py-12 text-center">
      <Image class="w-10 h-10 pb-3 text-gray-300"></Image>
      <p class="text-sm text-gray-500">No images available for this event</p>
    </div>
    <Separator />

    <CardHeader>
      <h6>Description</h6>
      <CardDescription>{{ event.description }}</CardDescription>
    </CardHeader>

    <Separator />
    <CardContent class="pt-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
            <MapPinIcon class="w-5 h-5 text-blue-600" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground mb-1">Location</p>
            <p class="text-sm font-medium">{{ event.location }}</p>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
            <TagIcon class="w-5 h-5 text-purple-600" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground mb-1">Category</p>
            <Badge variant="secondary">{{ event.category }}</Badge>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
            <CalendarIcon class="w-5 h-5 text-green-600" />
          </div>
          
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground mb-1">Starts</p>
            <p class="text-sm font-medium">{{ formatDateTime(event.start_datetime) }}</p>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
            <ClockIcon class="w-5 h-5 text-red-600" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground mb-1">Ends</p>
            <p class="text-sm font-medium">{{ formatDateTime(event.end_datetime) }}</p>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
            <UsersIcon class="w-5 h-5 text-orange-600" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-muted-foreground mb-1">Capacity</p>
            <div class="flex items-center gap-2">
              <p class="flex text-sm font-medium">{{ registered || registrations.data.length }} / {{ event.capacity }} registered</p>
              <p v-if="available != 0" class="flex text-red-500">
               {{ available }} available
              </p>
              <p v-else class="flex text-red-500">
                Fully registered !
              </p>
            </div>
          </div>
        </div>

        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
            <DollarSignIcon class="w-5 h-5 text-yellow-600" />
          </div>
          <div class="flex min-w-0 gap-3">
            <div class="flex flex-col">
            <p class="text-sm font-medium text-muted-foreground mb-1">Price</p>
            <p class="text-xl font-bold">{{ formatCurrency(event.price) }}</p>
            </div>
            <div class="flex flex-col">
              <p class="text-sm font-medium text-muted-foreground mb-1">Revenue</p>
              <p class="text-xl font-semibold text-green-600">{{ formatCurrency(revenue) }}</p>
            </div>
          </div>
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import Box from '@/Components/Display/Box.vue';
import StatusBadge from '@/Components/Display/StatusBadge.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { formatCurrency } from '@/useFormatCurrency';
import { useDateFormat } from '@/useFormatDate';
import { CalendarIcon, ClockIcon, DollarSignIcon, Image, ImageIcon, Images, ImagesIcon, MapPinIcon, TagIcon, UsersIcon } from 'lucide-vue-next';
import { computed } from 'vue';

const { formatDateTime } = useDateFormat()

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  event: Object,
  registrations: Object
})

const cancelled = computed(() => props.event?.cancelled_registrations_count ?? 0)
const registered = computed(() => {
  const total = (props.event?.registrations_count || props.registrations?.data.length)?? 0
  return total - cancelled.value
})
const available = computed(() => {
  const cap = props.event?.capacity ?? 0
  return cap - registered.value
})

const revenue = computed(() => props.event?.price * props.event?.confirm_registrations_count)
</script>