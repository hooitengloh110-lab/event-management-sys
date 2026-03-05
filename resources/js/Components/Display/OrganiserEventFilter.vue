<template>
  <form v-if="showFilters == true" class="flex items-center flex-wrap gap-3 mb-6 border p-6 rounded-md">

    <!-- Keyword -->
    <div class="flex gap-2 w-80">
      <input
        v-model="filterForm.keyword"
        type="text"
        placeholder="Search event ..."
        class="input-filter w-full"
      />
    </div>

    <!-- Price Range -->
    <div class="flex gap-2">
      <input
        v-model="filterForm.priceFrom"
        type="number"
        placeholder="Min Price"
        class="input-filter w-full"
      />
      <input
        v-model="filterForm.priceTo"
        type="number"
        placeholder="Max Price"
        class="input-filter w-full"
      />
    </div>

    <!-- Capacity -->
    <input
      v-model="filterForm.capacity"
      type="number"
      placeholder="Minimum Capacity"
      class="input-filter w-60"
    />

    <!-- Date Range -->
    <div class="flex gap-2 w-80">
      <input
        v-model="filterForm.dateFrom"
        type="date"
        class="input-filter w-full"
      />
      <input
        v-model="filterForm.dateTo"
        type="date"
        class="input-filter"
      />
    </div>

    <!-- Category -->
    <div class="flex gap-2">
      <input
        v-model="filterForm.category"
        type="text"
        placeholder="Search Category ..."
        class="input-filter w-full"
      />
    </div>

    <!-- Free Only -->
    <label class="flex items-center gap-2">
      <input type="checkbox" v-model="filterForm.freeOnly" class="checkbox-custom"/>
      Free Only
    </label>

    <div class="flex flex-nowrap items-center gap-2">
      <input id="deleted" v-model="filterForm.deleted" type="checkbox"
        class="checkbox-custom" />
      <label for="deleted">Deleted</label>
    </div>

    <!-- Sorting -->
    <div class="flex gap-2 min-w-[320px]">
      <select v-model="filterForm.by" class="input-filter min-w-[140px]">
        <option value="created_at">Added</option>
        <option value="price">Price</option>
        <option value="start_datetime">Event Date</option>
      </select>

      <select v-model="filterForm.order" class="input-filter min-w-[150px]">
        <option
          v-for="option in sortOptions"
          :key="option.value"
          :value="option.value"
        >
          {{ option.label }}
        </option>
      </select>
    </div>

  </form>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import { reactive, watch, computed, ref } from 'vue'
import { debounce } from 'lodash'
import TextInput from '../TextInput.vue'
import SingleSelect from '../SingleSelect.vue'
import InputLabel from '../InputLabel.vue'

const sortLabels = {
  created_at: [
    {
      label: 'Latest',
      value: 'desc',
    },
    {
      label: 'Oldest',
      value: 'asc',
    },
  ],
  price: [
    {
      label: 'Pricey',
      value: 'desc',
    },
    {
      label: 'Cheapest',
      value: 'asc',
    },
  ],
  start_datetime: [
    {
      label: 'Earlist',
      value: 'asc',
    },
    {
      label: 'Latest',
      value: 'desc',
    },
  ],
}

const categoryOptions = [
    { label: "Technology", value: "technology" },
    { label: "Business", value: "business" },
    { label: "Sports", value: "sports" },
    { label: "Education", value: "education" },
    { label: "Music", value: "music" },
]

const sortOptions = computed(() => sortLabels[filterForm.by])

const props = defineProps({
  filters: Object,
  indexRoute: String,
  showFilters: Boolean
})

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
})

watch(
  filterForm, debounce(() => router.get(
    route(props.indexRoute),
    filterForm,
    {preserveState: true, preserveScroll: true},
  ), 1000),
)
</script>