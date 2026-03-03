<template>
  <Card>
    <CardHeader>
      <CardTitle>{{ title }}</CardTitle>
    </CardHeader>
    <CardContent>
      <p class="text-3xl font-bold">
        <slot>{{ value }}</slot>
      </p>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '../ui/card';

defineProps<{
  title: string
  value?: string | number
}>()
</script>