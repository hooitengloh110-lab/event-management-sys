<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Badge } from '@/Components/ui/badge'
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from '@/Components/ui/select'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { 
  Search, 
  Calendar, 
  MapPin, 
  Users, 
  DollarSign,
  Filter,
  X
} from 'lucide-vue-next'
import { formatCurrency } from '@/useFormatCurrency'
import { useDateFormat } from '@/useFormatDate'
import MainLayout from '@/Layouts/MainLayout.vue'
import Pagination from '@/Components/Display/Pagination.vue'
import { debounce, filter } from 'lodash'
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue'

const { formatDateTime } = useDateFormat()

interface Event {
  id: number
  title: string
  description: string
  location: string
  category: string
  start_datetime: string
  end_datetime: string
  capacity: number
  price: number
  status: string
  registrations_count: number
  organiser: {
    id: number
    name: string
  }
}

interface Props {
  events: {
    data: Event[]
    links: any[]
  }
  categories: string[]
  filters: {
    search?: string
    category?: string
    price?: string
    date?: string
    sort?: string
  }
}

const props = defineProps<Props>()
const page = usePage()

const filterForm = reactive({
  keyword: props.filters.keyword ?? '',
  category: props.filters.category ?? '',
  priceFrom: props.filters.priceFrom ?? '',
  priceTo: props.filters.priceTo ?? '',
  capacity: props.filters.capacity ?? '',
  freeOnly: props.filters.freeOnly ?? false,
  dateFrom: props.filters.dateFrom ?? '',
  dateTo: props.filters.dateTo ?? '',
  deleted: props.filters.deleted ?? false,
  by: props.filters.by ?? 'created_at',
  order: props.filters.order ?? 'desc',
  date: props.filters.date ?? '',
  price: props.filters.price ?? ''
})

const hasFilters = computed(() => {
  return (
    filterForm.keyword ||
    filterForm.category ||
    filterForm.price ||
    filterForm.date
  )
})

const clearFilters = () => {
  filterForm.keyword = ''
  filterForm.category = ''
  filterForm.price = ''
  filterForm.date = ''
  filterForm.sort = 'date'
}

watch(
  filterForm,
  debounce(() => {
    router.get(
      route('public.event.index'),
      filterForm,
      {
        preserveState: true,
        preserveScroll: true,
        replace: true
      }
    )
  }, 500),
  { deep: true }
)

const getSpotsLeft = (event: Event) => {
  return event.capacity - event.registrations_count
}

function checkLogin(eventId) {
  if (!page.props.auth?.user) {
    // alert("Please login first before registering this event.");
    // router.visit(route('login'));
    return
  }
}

defineOptions({
  layout: MainLayout
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-600 to-indigo-100 text-white py-12 rounded-tl-lg rounded-tr-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-2">Discover Events</h1>
        <p class="text-blue-100">Find and join amazing events happening near you</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
      <Card class="shadow-lg">
        <CardContent class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <Input
                  v-model="filterForm.keyword"
                  placeholder="Search events..."
                  class="pl-10"
                />
              </div>
            </div>

            <!-- Category -->
            <Select v-model="filterForm.category" >
              <SelectTrigger>
                <SelectValue placeholder="Category" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All Categories</SelectItem>
                <SelectItem v-for="cat in categories" :key="cat" :value="cat">
                  {{ cat }}
                </SelectItem>
              </SelectContent>
            </Select>

            <!-- Price -->
            <Select v-model="filterForm.price" >
              <SelectTrigger>
                <SelectValue placeholder="Price" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All Prices</SelectItem>
                <SelectItem value="free">Free</SelectItem>
                <SelectItem value="paid">Paid</SelectItem>
              </SelectContent>
            </Select>

            <!-- Date -->
            <Select v-model="filterForm.date">
              <SelectTrigger>
                <SelectValue placeholder="Date" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All Dates</SelectItem>
                <SelectItem value="today">Today</SelectItem>
                <SelectItem value="tomorrow">Tomorrow</SelectItem>
                <SelectItem value="this_week">This Week</SelectItem>
                <SelectItem value="this_month">This Month</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Sort and Clear -->
          <div class="flex items-center justify-between mt-4 pt-4 border-t">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Sort by:</span>
              <Select v-model="filterForm.sort" >
                <SelectTrigger class="w-40">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="date">Upcoming</SelectItem>
                  <SelectItem value="popular">Most Popular</SelectItem>
                  <SelectItem value="price_low">Price: Low to High</SelectItem>
                  <SelectItem value="price_high">Price: High to Low</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <Button
              v-if="hasFilters"
              variant="ghost"
              size="sm"
              @click="clearFilters"
            >
              <X class="w-4 h-4 mr-2" />
              Clear Filters
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Events Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div v-if="events.data.length" class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 gap-6">
        <Card
          v-for="event in events.data"
          :key="event.id"
          class="flex overflow-hidden hover:shadow-lg transition-shadow cursor-pointer group"
          @click="router.visit(route('public.event.show', event.id))"
        >
          <CardHeader class="p-0 w-56 flex-shrink-0">
            <div class="h-full bg-gradient-to-br from-gray-500 to-indigo-100 flex items-center justify-center">
              <img v-if="event.images.length > 0" :src="event.images[0].src" class="rounded-lg object-cover h-full w-full" />
              <Calendar v-if="event.images.length === 0" class="w-12 h-12 text-white opacity-50" />
            </div>
          </CardHeader>

          <CardContent class="p-6 flex-1">
            <div class="flex justify-between">
              <Badge variant="secondary" class="mb-3">
                {{ event.category }}
              </Badge>
              <ConfirmDialog v-if="!event.deleted_at" title="Warning !" description="You need to login first then can register event"
                :onConfirm="() => checkLogin(event.id)">
                <template #trigger>
                  <Button variant="outline" @click.stop class="btn-color font-medium confirm-button">
                    Register
                  </Button>
                </template>
              </ConfirmDialog>
            </div>

            <h3 class="text-xl font-bold mb-2 group-hover:text-blue-600 transition-colors">
              {{ event.title }}
            </h3>

            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
              {{ event.description }}
            </p>

            <div class="space-y-2 mb-4">
              <div class="flex items-center gap-2 text-sm text-gray-600">
                <Calendar class="w-4 h-4" />
                <span>{{ formatDateTime(event.start_datetime) }}</span>
              </div>

              <div class="flex items-center gap-2 text-sm text-gray-600">
                <MapPin class="w-4 h-4" />
                <span>{{ event.location }}</span>
              </div>

              <div class="flex items-center gap-2 text-sm text-gray-600">
                <Users class="w-4 h-4" />
                <span>By {{ event.organiser.name }}</span>
              </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
              <div class="flex items-center gap-1">
                <span class="text-lg font-bold">
                  {{ event.price === 0 ? 'Free' : formatCurrency(event.price) }}
                </span>
              </div>

              <div class="text-sm">
                <span
                  :class="getSpotsLeft(event) > 0 ? 'text-green-600' : 'text-red-600'"
                  class="font-medium"
                >
                  {{ getSpotsLeft(event) > 0 ? `${getSpotsLeft(event)} spots left` : 'Sold Out' }}
                </span>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Empty State -->
      <Card v-else class="border-dashed">
        <CardContent class="py-16 text-center">
          <Search class="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h3 class="text-lg font-semibold mb-2">No events found</h3>
          <p class="text-gray-600 mb-6">
            Try adjusting your filters to find more events
          </p>
          <Button outline="outline" @click="clearFilters" class="btn-color text-gray-200 hover:text-black">Clear Filters</Button>
        </CardContent>
      </Card>

      <!-- Pagination -->
      <div v-if="events.data.length" class="mt-8">
        <Pagination :links="events.links" />
      </div>
    </div>
  </div>
</template>