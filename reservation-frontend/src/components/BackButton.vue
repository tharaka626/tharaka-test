<script setup>

const back = () => {
    window.history.back();
}
</script>

<template>
    <button
        type="button"
        @click="back"
        class="bg-gray-100 text-gray-900 hover:bg-gray-200 focus-visible:ring-gray-500 inline-flex justify-center rounded-md border border-transparent  px-4 py-2 text-sm font-medium  focus:outline-none focus-visible:ring-2  focus-visible:ring-offset-2"
    >
        Go Back
    </button>
</template>
