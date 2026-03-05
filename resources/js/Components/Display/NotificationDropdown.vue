<template>
  <div v-if="show" class="relative mt-10">
    <transition name="fade">
      <div
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
        <div class="p-4">
          <h3 class="font-bold mb-2 text-gray-700 dark:text-gray-300">Notifications</h3>

          <div>
            <div v-for="notification in notifications" :key="notification.id" class="p-2 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded flex items-start gap-2">
                <!-- Notification content -->
                <div class="flex-1">
                  <p class="text-sm text-gray-700 dark:text-gray-300" v-html="getNotificationMessage(notification)"></p>
                  <div class="flex justify-between">
                    <span class="text-xs text-gray-400 mt-1 block">{{ formatDateTime(notification?.created_at) }}</span>
                    <div v-if="!notification?.read_at" class="w-2 h-2 mt-1 bg-green-500 rounded-full flex-shrink-0"></div>
                  </div>
                </div>
              </div>
          </div>

          <div class="text-center mt-2">
            <Link :href="route('notification.index')" as="button" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
              View All
            </Link>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { useDateFormat } from '@/useFormatDate';
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

const { formatDateTime } = useDateFormat()
const props = defineProps({
  notifications: Array,
  show: Boolean,
  notificationCount: Number
})


const realNotificationCount = ref(props.notificationCount)
const emit = defineEmits(['update:show'])
const dropdownRef = ref<HTMLElement | null>(null)

function handleClickOutside(event: MouseEvent) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)
  ) {
    emit('update:show', false);
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside));
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside));

function getNotificationMessage(notification: any): string {
  switch (notification.type) {
    case 'App\\Notifications\\RegistrationConfirmedNotification':
      return `Your registration for <strong>${notification.data.title}</strong> has been confirmed!`;
    case 'App\\Notifications\\RegistrationCancelledNotification':
      return `Your registration for <strong>${notification.data.title}</strong> was cancelled.`;
    case 'App\\Notifications\\EventCancelledNotification':
      return `The event <strong>${notification.data.title}</strong> was cancelled.`;
    case 'App\\Notifications\\AttendeeRegistrationCancelled':
      return `<strong>${notification.data.attendee_name}</strong> cancelled their registration for <strong>${notification.data.title}</strong>.`;
    case 'App\\Notifications\\NewEventRegistration':
      return `<strong>${notification.data.attendee_name}</strong> registered for your event <strong>${notification.data.title}</strong>.`;
    default:
      return 'You have a new notification.';
  }
}

;


</script>
