<template>
  <Head title="Review" />
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">All Reviews</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-2">View all event reviews</p>
    </div>

    <div v-if="reviews && reviews.length" class="space-y-6">
      <Card v-for="review in reviews" :key="review.id" class="overflow-hidden">
        <CardContent class="p-0">
          <div class="p-6">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                  <User class="w-6 h-6 text-white" />
                </div>
              </div>
              
              
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <span class="font-semibold text-gray-900 dark:text-gray-100">{{ review.attendee_id === $page.props.auth.user.id ? 'You' : review.attendee.name}}</span>
                  <span class="text-sm text-gray-500">•</span>
                  <span class="text-sm text-gray-500">
                    {{ formatDateTime(review.created_at) }}
                  </span>
                </div>
                
                <p v-if="review.comment" class="text-gray-700 dark:text-gray-300 leading-relaxed">
                  {{ review.comment }}
                </p>
                <p v-else class="text-gray-500 dark:text-gray-400 italic">
                  No comment provided
                </p>
              </div>

              <div class="flex items-end">
                <div class="flex items-center gap-1 px-3 py-2">
                  <Star v-for="i in 5" :key="i"
                    :class="i <= review.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'" class="w-5 h-5" />
                  <span class="ml-2 font-semibold text-gray-900 dark:text-gray-100">{{ review.rating }}</span>
                </div>
              </div>

            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <Card v-else class="border-dashed">
      <CardContent class="flex flex-col items-center justify-center py-16">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
          <Star class="w-8 h-8 text-gray-400" />
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No reviews yet</h3>
        <p class="text-gray-600 dark:text-gray-400 text-center max-w-md mb-6">
          Be the first to review this event! If you attended, share your thoughts and experience.        </p>
        <Button as-child variant="secondary" class="btn-color">
          <Link :href="route('attendee.event.index')">
            Browse Events
          </Link>
        </Button>
      </CardContent>
    </Card>

    <div v-if="reviews && reviews.length" class="mt-8">
      <Pagination :links="reviews.links" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { Card, CardContent } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import Pagination from '@/Components/Display/Pagination.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Star, User, Pencil, Trash2 } from 'lucide-vue-next'
import { useDateFormat } from '@/useFormatDate'

const { formatDateTime, formatDate } = useDateFormat()

interface Review {
  id: number
  rating: number
  comment: string | null
  created_at: string
  event: {
    id: number
    title: string
    start_datetime: string
  }
}

const props = defineProps<{
  reviews: Review[]
  pagination?: {
    links: any[]
  }
  event: Object
}>()

defineOptions({
  layout: MainLayout
})

const editReview = (review: Review) => {
  router.visit(route('reviews.edit', review.id))
}

const deleteReview = (id: number) => {
  if (confirm('Are you sure you want to delete this review?')) {
    router.delete(route('reviews.destroy', id))
  }
}
</script>