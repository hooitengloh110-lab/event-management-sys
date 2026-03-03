<template>
  <AlertDialog>
    <AlertDialogTrigger asChild>
    <slot name="trigger"></slot>
  </AlertDialogTrigger>

    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ title }}</AlertDialogTitle>
        <AlertDialogDescription>{{ description }}</AlertDialogDescription>
      </AlertDialogHeader>

      <div class="flex justify-end gap-2 mt-4">
        <AlertDialogCancel>{{ cancelText }}</AlertDialogCancel>
        <AlertDialogAction @click="onConfirm" class="hover-confirm">{{ confirmText }}</AlertDialogAction>
      </div>
    </AlertDialogContent>
  </AlertDialog>
</template>

<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '../ui/alert-dialog';

const props = defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
  confirmText: { type: String, default: 'Confirm' },
  cancelText: { type: String, default: 'Cancel' },
  onConfirm: { type: Function as () => void, required: true },
});

const { title, description, confirmText, cancelText, onConfirm } = props;
</script>