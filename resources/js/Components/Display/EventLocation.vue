<template>
  <span>
    {{ event.location }}

  </span>
</template>

<script setup>
defineProps({
  event: Object,
})
</script>