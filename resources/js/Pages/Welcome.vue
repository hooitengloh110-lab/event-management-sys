<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CalendarDays, CalendarIcon, TicketIcon, TrendingUpIcon, UsersIcon } from 'lucide-vue-next';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                      <section class="gap-2 flex-col justify-center items-center">
                        <CalendarDays class="w-16 h-16 text-indigo-900" />
                      </section>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="$page.props.auth.user?.role === 'organiser' ? route('organiser.dashboard') : route('attendee.dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="pt-24 pb-16">
                  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                      <h1 class="text-5xl font-bold text-gray-900 mb-4">
                        Manage Your Events
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                          Effortlessly
                        </span>
                      </h1>
                      <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Create, manage, and track events with our comprehensive event management platform.
                        Perfect for organizers and attendees alike.
                      </p>
                      <div class="mt-8">
                        <Link 
                          href="#" 
                          class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-purple-700 transition-all"
                        >
                          Browse Events
                          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                          </svg>
                        </Link>
                      </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                      <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                          <CalendarIcon class="w-6 h-6 text-red-600" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Create Events</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                          Easily create and customize events with detailed information, pricing, and capacity management.
                          Set your event dates, locations, and categories in minutes.
                        </p>
                        <Link href="#" class="inline-flex items-center text-red-600 font-medium hover:text-red-700">
                          Get Started
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </Link>
                      </div>

                      <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                          <TicketIcon class="w-6 h-6 text-blue-600" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Manage Registrations</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                          Track attendee registrations in real-time. Confirm, cancel, or manage participant details
                          with our intuitive dashboard interface.
                        </p>
                        <Link href="#" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-700">
                          View Registrations
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </Link>
                      </div>

                      <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                          <TrendingUpIcon class="w-6 h-6 text-purple-600" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Track Analytics</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                          Monitor event performance with detailed analytics. Track registrations, attendance rates,
                          and revenue metrics all in one place.
                        </p>
                        <Link href="#" class="inline-flex items-center text-purple-600 font-medium hover:text-purple-700">
                          View Analytics
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </Link>
                      </div>

                      <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                          <UsersIcon class="w-6 h-6 text-green-600" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Attendee Portal</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                          Browse upcoming events, register with ease, and manage your event tickets.
                          Get notifications and updates about your registered events.
                        </p>
                        <Link href="#" class="inline-flex items-center text-green-600 font-medium hover:text-green-700">
                          Browse Events
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </Link>
                      </div>

                      <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                          <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                          </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Reviews & Ratings</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                          Collect feedback from attendees with our built-in review system.
                          Build credibility and improve future events based on participant insights.
                        </p>
                        <Link href="#" class="inline-flex items-center text-yellow-600 font-medium hover:text-yellow-700">
                          Read Reviews
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </Link>
                      </div>

                      <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                        <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-4">
                          <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                          </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Documentation</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                          Comprehensive guides and tutorials to help you get the most out of our platform.
                          Learn best practices for event management and attendee engagement.
                        </p>
                        <a href="#" class="inline-flex items-center text-pink-600 font-medium hover:text-pink-700">
                          Read Docs
                          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                          </svg>
                        </a>
                      </div>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                      <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 mb-2">500+</div>
                        <div class="text-gray-600">Events Created</div>
                      </div>
                      <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 mb-2">10K+</div>
                        <div class="text-gray-600">Registered Attendees</div>
                      </div>
                      <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 mb-2">4.8★</div>
                        <div class="text-gray-600">Average Rating</div>
                      </div>
                    </div>
                  </div>
                </main>

                <footer class="border-t border-gray-200 bg-white">
                  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <p class="text-center text-gray-500 text-sm">
                      EventHub v1.0.0 (Laravel v11.0 + Vue 3 + Inertia)
                    </p>
                  </div>
                </footer>
            </div>
        </div>
    </div>
</template>
