<template>
  <Head title="Review" />
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">My Reviews</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-2">View and manage your event reviews</p>
    </div>

    <div v-if="review && review" class="space-y-6">
      <Card class="overflow-hidden">
        <CardContent class="p-0">
          <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 p-6">
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <Link :href="route('attendee.event.show', event.id)" class="group">
                  <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 transition-colors">
                    {{ event.title }}
                  </h3>
                </Link>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                  {{ formatDateTime(event.start_datetime) }}
                </p>
              </div>
              
              <div class="flex items-center gap-1 bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-sm">
                <Star 
                  v-for="i in 5" 
                  :key="i"
                  :class="i <= review.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'"
                  class="w-5 h-5"
                />
                <span class="ml-2 font-semibold text-gray-900 dark:text-gray-100">{{ review.rating }}</span>
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                  <User class="w-6 h-6 text-white" />
                </div>
              </div>
              
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <span class="font-semibold text-gray-900 dark:text-gray-100">You</span>
                  <span class="text-sm text-gray-500">•</span>
                  <span class="text-sm text-gray-500">{{ formatDate(review.created_at) }}</span>
                </div>
                
                <p v-if="review.comment" class="text-gray-700 dark:text-gray-300 leading-relaxed">
                  {{ review.comment }}
                </p>
                <p v-else class="text-gray-500 dark:text-gray-400 italic">
                  No comment provided
                </p>

                <div class="flex items-center gap-3 mt-4">
                  <Button 
                    variant="outline" 
                    size="sm"
                    @click="editReview(review)"
                  >
                    <Pencil class="w-4 h-4 mr-2" />
                    Edit
                  </Button>
                  <Button 
                    variant="destructive" 
                    size="sm"
                    @click="deleteReview(review.id)"
                  >
                    <Trash2 class="w-4 h-4 mr-2" />
                    Delete
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <div v-if="reviews && reviews.length" class="mt-8">
      <Pagination :links="pagination.links" />
    </div>
  </div>
</template>

<script setup lang="ts">
import Pagination from '@/Components/Display/Pagination.vue';
import { Card, CardContent } from '@/Components/ui/card';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, router } from '@inertiajs/vue3';

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