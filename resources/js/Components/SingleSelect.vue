<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import {
  Select,
  SelectTrigger,
  SelectContent,
  SelectGroup,
  SelectItem,
} from '@/Components/ui/select'

interface Option {
  label: string
  value: string | number
}

const props = defineProps<{
  modelValue?: string | number
  options: Option[]
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
}>()

// Internal value stored for v-model
const selected = ref(props.modelValue ?? '')

// Update internal value if parent changes
watch(() => props.modelValue, val => {
  selected.value = val ?? ''
})

// Emit changes to parent
watch(selected, val => {
  emit('update:modelValue', val)
})

// Computed: show the label in the trigger instead of the raw value
const selectedLabel = computed(() => {
  const opt = props.options.find(o => o.value === selected.value)
  return opt?.label || ''
})
</script>

<template>
  <Select v-model="selected">
    <SelectTrigger>
      <!-- Show the capitalized label -->
      <span>{{ selectedLabel || props.placeholder || 'Select...' }}</span>
    </SelectTrigger>
    <SelectContent>
      <SelectGroup>
        <SelectItem
          v-for="opt in props.options"
          :key="opt.value"
          :value="opt.value"
        >
          {{ opt.label }}
        </SelectItem>
      </SelectGroup>
    </SelectContent>
  </Select>
</template>