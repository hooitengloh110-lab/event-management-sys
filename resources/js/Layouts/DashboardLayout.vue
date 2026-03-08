<script setup lang="ts">
import { computed, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { PageProps as InertiaPageProps, router } from '@inertiajs/core';
import NotificationDropdown from '@/Components/Display/NotificationDropdown.vue';

const showingNavigationDropdown = ref(false);
interface AppPageProps extends InertiaPageProps {
  flash: {
    success: string | null;
    error: string | null;
  };
}
const props = defineProps<{ notificationsBell: Notification[] }>()

const page = usePage<AppPageProps>();
const routeDifferentiate = page.props.routeDifferentiate
const role = page.props.auth.user?.role
const flashSuccess = computed(() => page.props.flash.success)
const flashError = computed(() => page.props.flash.error)
const user = computed(() => page.props.auth.user)
const notificationCount = computed(
  () => Math.min(page.props.auth.user.notificationCount, 9),
)
const notifications = computed(() => page.props.auth.user.notificationsBell)
const showNotification = ref(false)

const openNotifications = () => {
  showNotification.value = !showNotification.value;
  if (showNotification.value && notificationCount.value > 0) {
    router.post(route('notification.all-read'), {}, {
      preserveState: true,
      preserveScroll: true,
    });
    notificationCount.value = 0;
  }
}
</script>

<template>
    <div>
        <div class="min-h-screen">
            <nav
                class="border-b border-gray-100 bg-white"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="role === 'organiser' ? route('organiser.dashboard') : route('admin.dashboard')"
                                    :active="route().current(role == 'organiser' ? 'organiser.dashboard' : 'admin.dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    :href="role== 'organiser' ? route('event.index') : route('admin.event.index')"
                                    :active="route().current(role == 'organiser' ? 'event.index' : 'admin.event.index') || route().current('event.show') || route().current('event.image.create') || route().current('event.registration.index')"
                                >
                                    Event
                                </NavLink>
                                <NavLink v-if="role == 'admin'"
                                    :href="route('admin.user.index')"
                                    :active="route().current('admin.user.index')"
                                >
                                    User
                                </NavLink>
                                <NavLink v-if="role == 'admin'"
                                    :href="route('admin.registration.index')"
                                    :active="route().current('admin.registration.index')"
                                >
                                    Registration
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="flex items-center gap-2 ms-3">
                                <button v-if="user"
                                    class="text-gray-500 relative pr-2 py-2 text-lg" 
                                    @click="openNotifications"
                                >
                                    🔔
                                    <div v-if="notificationCount" class="absolute right-0 top-0 w-5 h-5 bg-red-700 dark:bg-red-400 text-white font-medium border border-white dark:border-gray-900 rounded-full text-xs text-center">
                                    {{ notificationCount }}
                                    </div>
                                  </button>
                                <NotificationDropdown :notifications="notifications.data ?? notifications" :show="showNotification" :notification-count="notificationCount"/>

                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }} 

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="role == 'organiser' ? route('organiser.dashboard') : route('admin.dashboard')"
                            :active="route().current(role == 'organiser' ? 'organiser.dashboard' : 'admin.dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('event.index')"
                            :active="route().current('event.index') || route().current('event.show') || route().current('event.image.create') || route().current('event.registration.index')"
                          >
                            Event
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="container mx-auto p-4 w-full pt-6">
                <div v-if="flashSuccess" class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
                {{ flashSuccess }}
                </div>
                <div v-if="flashError" class="mb-4 border rounded-md shadow-sm border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900 p-2 text-red-800">
                  {{ flashError }}
                </div>
                <slot> Default </slot>
            </main>
        </div>
    </div>
</template>
