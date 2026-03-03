<template>
  <div>
    <span class="font-bold">{{ event.capacity }}</span> Seats

  </div>
</template>

<script setup>
defineProps({
  event: Object,
})
</script>