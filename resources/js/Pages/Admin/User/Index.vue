<template>
  <Head title="User" />
  <div class="flex flex-col gap-6 w-full">
    <Box>
      <template #header>
        Filter Users
      </template>
      <div class="mt-2">
        <select v-model="filterForm.role" class="input-filter w-40">
          <option value="">All Roles</option>
          <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
        </select>
      </div>
    </Box>

    <Box v-if="users.data.length">
      <template #header>
        Users
      </template>
      <div class="overflow-x-auto mt-2">
        <table class="w-full text-left table-auto">
          <thead>
            <tr class="border-b dark:border-gray-700">
              <th class="px-4 py-2">Name</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Role</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users.data" :key="user.id"
              class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
              <td class="px-4 py-2">{{ user.name }}</td>
              <td class="px-4 py-2">{{ user.email }}</td>
              <td class="px-4 py-2 capitalize">{{ user.role }}</td>
              <td class="px-4 py-2">
                <span
                  :class="user.is_active ? 'bg-green-100 text-green-800 px-3 py-2 rounded-lg' : 'bg-red-100 text-red-800 px-2 py-1 rounded'">
                  {{ user.is_active ? 'Active' : 'Suspended' }}
                </span>
              </td>
              <td class="px-4 py-2">
                <Button variant="secondary" class="my-2 rounded px-5 py-1"
                  :class="user.is_active ? 'cancel-button' : 'confirm-button'" @click="toggleStatus(user)">
                  {{ user.is_active ? 'Suspend' : 'Activate' }}
                </Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Box>

    <EmptyState v-else class="mt-2 mb-10">No users yet</EmptyState>

    <section v-if="users.data.length" class="w-full flex justify-center mt-10 mb-14">
      <Pagination :links="users.links" />
    </section>
  </div>

</template>


<script setup>
import { reactive, ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Box from '@/Components/Display/Box.vue';
import Pagination from '@/Components/Display/Pagination.vue';
import EmptyState from '@/Components/Display/EmptyState.vue';
import { debounce } from 'lodash';
import { Button } from '@/components/ui/button';

defineOptions({
  layout: DashboardLayout
})

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object
});

const filterRole = ref('');

const filterForm = reactive({
  role: props.filters.role ?? ''
})

watch(
  filterForm, debounce(() => router.get(
    route('admin.user.index'),
    filterForm,
    { preserveState: true, preserveScroll: true },
  ), 1000),
)

function applyFilter() {
  router.get(route('admin.user.index'), { role: filterRole.value });
}

function toggleStatus(user) {
  router.post(route('admin.user.toggle', user.id));
}
</script>