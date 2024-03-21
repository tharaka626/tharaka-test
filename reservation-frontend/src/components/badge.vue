<script setup>
    const props = defineProps(['content','type']);
</script>

<template>
    <span v-if="props.type == '0'" class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">{{ props.content }}</span>
    <span v-if="props.type == '2'" class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ props.content }}</span>
    <span v-if="props.type == '1'" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ props.content }}</span>
  </template>  