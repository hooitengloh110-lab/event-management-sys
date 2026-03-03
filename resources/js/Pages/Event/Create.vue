<template>
  <div class="space-y-6 max-w-3xl mx-auto p-6 border rounded-lg px-8 py-8">
    <h1 class="text-2xl font-bold">Create Event</h1>
    
    <form @submit.prevent="submit">
      <div class="grid grids-cols-6 gap-4">
        <div class="grid col-span-6">
          <InputLabel for="title" value="Title" />
          <TextInput label="Title" v-model="form.title" />
          <InputError class="mt-2" :message="form.errors.title" />
        </div>

        <div class="grid col-span-6">
          <InputLabel for="description" value="Description" />
          <Textarea label="Description" v-model="form.description" />
          <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <div class="grid col-span-3">
          <InputLabel for="location" value="Location" />
          <TextInput label="Location" v-model="form.location"  />
          <InputError class="mt-2" :message="form.errors.location" />
        </div>

        <div class="grid col-span-3">
          <InputLabel for="category" value="Category" />
          <SingleSelect label="Category" v-model="form.category" :options="categoryOptions"  />
          <InputError class="mt-2" :message="form.errors.category" />
        </div>

        <div class="grid col-span-3">
          <InputLabel for="start_datetime" value="Start Datetime" />
          <TextInput type="datetime-local" label="Start Date/Time" v-model="form.start_datetime"  />
          <InputError class="mt-2" :message="form.errors.start_datetime" />
        </div>
        <div class="grid col-span-3">
          <InputLabel for="end_datetime" value="End Datetime" />
          <TextInput type="datetime-local" label="End Date/Time" v-model="form.end_datetime"  />
          <InputError class="mt-2" :message="form.errors.end_datetime" />
        </div>

        <div class="grid col-span-3">
          <InputLabel for="capacity" value="Capacity" />
          <TextInput type="number" label="Capacity" v-model.number="form.capacity"  />
          <InputError class="mt-2" :message="form.errors.capacity" />
        </div>

        <div class="grid col-span-3">
          <InputLabel for="price" value="Price" />
          <TextInput type="text" label="Price" v-model.number="form.price"  />
          <InputError class="mt-2" :message="form.errors.price" />
        </div>

        <div class="grid col-span-3">
          <InputLabel for="status" value="Status" />
          <SingleSelect label="Status" v-model="form.status" :options="statusOptions"  />
        </div>

        <div class="grid col-span-6 mt-1">
          <InputLabel for="images" value="Images" />
          <section class="flex items-cen ter gap-2 mt-2 mb-4">
            <input type="file" label="Images (max 6)" ref="fileInput" multiple @input="addFiles" class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:hover:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"/>
            <Button variant="outline" type="button" @click="reset">
              Reset
            </Button>
          </section>
          <div v-if="imageErrors.length" class="input-error">
            <div v-for="(error, index) in imageErrors" :key="index">
              {{ error }}
            </div>
          </div>
        </div>

        <div class="mt-2">
          <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Create</PrimaryButton>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import Button from '@/Components/ui/button/Button.vue'
import SingleSelect from '@/Components/SingleSelect.vue'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'

defineOptions({
    layout: DashboardLayout
})

const categoryOptions = [
    { label: "Technology", value: "technology" },
    { label: "Business", value: "business" },
    { label: "Sports", value: "sports" },
    { label: "Education", value: "education" },
    { label: "Music", value: "music" },
]

const statusOptions = [
    { label: "Draft", value: "draft" },
    { label: "Published", value: "published" },
]

const form = useForm({
  title: '',
  description: '',
  location: '',
  category: '',
  start_datetime: '',
  end_datetime: '',
  capacity: null,
  price: 0,
  status: 'draft',
  images: [],
})

const imageErrors = computed(() => {
  return Object.keys(form.errors)
    .filter(key => key.startsWith('images'))
    .map(key => form.errors[key])
})

const canUpload = computed(() => form.images.length)
const submit = () => {
  form.post(
    route('event.store'), {
      onSuccess: () => form.reset('images'),
    },
  )
}
const addFiles = (event) => {
  for (const image of event.target.files) {
    form.images.push(image)
  }
}
const fileInput = ref<HTMLInputElement | null>(null)
const reset = () => {
  form.images = []
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

</script>