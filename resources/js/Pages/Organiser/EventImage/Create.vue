<template>
  <Head title="Event" />
  <Box>
    <template #header>Upload New Images</template>
    <form @submit.prevent="upload">
      <section class="flex items-center gap-2 my-4">
        <input
          class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:hover:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"
          type="file" multiple @input="addFiles"
        />
        <button
          type="submit"
          class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
          :disabled="!canUpload"
        >
          Upload
        </button>
        <button
          type="reset" class="btn-outline"
          @click="reset"
        >
          Reset
        </button>
      </section>
      <div v-if="imageErrors.length" class="input-error">
        <div v-for="(error, index) in imageErrors" :key="index">
          {{ error }}
        </div>
      </div>
    </form>
  </Box>

  <Box v-if="event.images.length" class="mt-4">
    <template #header>Current Event Images</template>
    <section class="mt-4 grid grid-cols-3 gap-4">
      <div
        v-for="image in event.images" :key="image.id" 
        class="flex flex-col justify-between"
      >
        <img :src="image.src" class="rounded-md" />
        <Link 
          :href="route('event.image.destroy', { event: props.event.id, image: image.id })"
          method="delete"
          as="button"
          class="mt-2 btn-outline text-xs mb-2"
        >
          Delete
        </Link>
        <ConfirmDialog v-if="!image.deleted_at && !image.is_cover" title="Update Cover Image?"
          description="Are you sure? This only can choose one image as Cover." :onConfirm="() => updateCover(event.id, image.id)">
          <template #trigger>
            <Button variant="outline" class="btn-color">
              {{ image.is_cover ? 'Cover Image' : 'Set As Cover' }}
            </Button>
          </template>
        </ConfirmDialog>
        <span v-else-if="image.is_cover"
          class="text-white bg-green-600 px-2 py-2 rounded text-xs text-center"
        >
          Cover Image
        </span>
      </div>
    </section>
  </Box>
</template>

<script setup >
import { computed } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import Box from '@/Components/Display/Box.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import ConfirmDialog from '@/Components/Display/ConfirmDialog.vue'
import { Button } from '@/components/ui/button'

defineOptions({
  layout: DashboardLayout
})

const maxImages = 6;
const props = defineProps({ event: Object })
const form = useForm({
  images: [],
})
const imageErrors = computed(() => Object.values(form.errors))
const canUpload = computed(() => form.images.length)
const upload = () => {
  form.post(
    route('event.image.store', { event: props.event.id }),
    {
      onSuccess: () => form.reset('images'),
    },
  )
}

const addFiles = (event) => {
  const files = event.target.files
  if(!files) return

  for (const image of files) {
    form.images.push(image)
  }
}

const reset = () => form.reset('images')

const updateCover = (eventId,imageId) => {
  router.patch(route('event.image.update', { event: eventId, image: imageId }))
}


</script>