<script setup>
defineProps({
    value: {
        type: String,
    },
    sub_class: {
        type: String,
        default: 'font-medium'
    }
});
</script>

<template>
    <label class="block text-sm text-gray-700" :class="sub_class">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
