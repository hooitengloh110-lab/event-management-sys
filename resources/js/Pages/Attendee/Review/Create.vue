<template>
  <Head title="Review" />
  <div class="max-w-xl mx-auto">
    <Box class="p-8">
    <h1 class="text-xl font-semibold mb-4">
      Review: {{ event?.title }}
    </h1>

    <form @submit.prevent="submitReview">
      <label class="block mb-2">Rating</label>
      <select v-model="form.rating" class="border p-2 rounded w-full">
        <option disabled value="">Select rating</option>
        <option v-for="n in 5" :key="n" :value="n">
          {{ n }} Star
        </option>
      </select>
      <InputError class="mt-2" :message="form.errors.rating" />

      <label class="block mt-4 mb-2">Comment</label>
      <textarea
        v-model="form.comment"
        class="border p-2 rounded w-full"
        rows="4"
      ></textarea>
      <InputError class="mt-2" :message="form.errors.comment" />


      <div class="mt-6">
        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</PrimaryButton>
      </div>
    </form>
    </Box>
  </div>
</template>

<script setup lang="ts">
import Box from '@/Components/Display/Box.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Button from '@/components/ui/button/Button.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  event: Object
})
const form = useForm({
  rating: '',
  comment: ''
})

defineOptions({
  layout: MainLayout
})
const submitReview = () => {
  form.post(route('attendee.event.review.store', { event: props.event.id}))
}
</script>