<template>
  <h1 class="text-3xl mb-4">Your Notifications</h1>

  <section v-if="notifications.data.length" class="text-gray-700 dark:text-gray-400">
    <div v-for="notification in notifications.data" :key="notification.id" class="border-b border-gray-200 dark:border-gray-800 py-4 flex justify-between items-center">
      <div>
        <span v-if="notification.type === 'App\\Notifications\\OfferMade'">
          Offer <Price :price="notification.data.amount" /> for
          <Link
            :href="route('realtor.listing.show', {listing: notification.data.listing_id})" 
            class="text-indigo-600 dark:text-indigo-400"
          >listing</Link> was made
        </span>
      </div>
      <div>
        <Link v-if="!notification.read_at" :href="route('notification.seen', {notification: notification.id})" as="button" method="put" >
          <Button class="text-xs font-medium uppercase bg-gray-200 hover:bg-gray-100 dark:bg-gray-700">Mark as Read</Button>
        </Link>
      </div>
    </div>
  </section>

  <EmptyState v-else>No notifications yet!</EmptyState>

  <section v-if="notifications.data.length" class="w-full flex justify-center mt-8 mb-8">
    <Pagination :links="notifications.links" />
  </section>
</template>

<script setup lang="ts">
import EmptyState from '@/Components/Display/EmptyState.vue';
import Pagination from '@/Components/Display/Pagination.vue';
import Price from '@/Components/Display/Price.vue';
import Button from '@/Components/ui/button/Button.vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue';

const page = usePage()
const role = computed(() => page.props.auth.user.role)

defineProps({
  notifications: Object,
})

defineOptions({
  layout: (h, page) => {
    const role = page.props.auth?.user?.role
    console.log(role);
    const Layout =
      role === 'admin' || role === 'organiser'
        ? DashboardLayout 
        : MainLayout

    return h(Layout, page.props, () => page)
  }
})

</script>