<script setup>
    import { ref,watch } from 'vue';

    const props = defineProps(['btn_type','btn_type_status']);

    const colors = ref('');

    switch (props.btn_type) {
        case 3:
            colors.value = 'bg-blue-100 text-blue-900 hover:bg-blue-200 focus-visible:ring-blue-500';
            break;
        case 1:
            colors.value = 'bg-green-100 text-green-900 hover:bg-green-200 focus-visible:ring-green-500';
            break;
        case 0:
            colors.value = 'bg-red-100 text-red-900 hover:bg-red-200 focus-visible:ring-red-500';
            break;
        case 2:
            colors.value = 'bg-orange-100 text-orange-900 hover:bg-orange-200 focus-visible:ring-orange-500';
            break;
        default:
            break;
    }
    switch (props.btn_type_status) {
        case 0:
            colors.value = 'bg-green-100 text-green-900 hover:bg-green-200 focus-visible:ring-green-500';
            break;
        case 1:
            colors.value = 'bg-red-100 text-red-900 hover:bg-red-200 focus-visible:ring-red-500';
            break;
        default:
            break;
    }

    watch(()=>props.btn_type_status, (newVal) => {
        switch (newVal) {
            case 0:
                colors.value = 'bg-green-100 text-green-900 hover:bg-green-200 focus-visible:ring-green-500';
                break;
            case 1:
                colors.value = 'bg-red-100 text-red-900 hover:bg-red-200 focus-visible:ring-red-500';
                break;
            default:
                break;
        }
    })
    

</script>

<template>
    <button
        type="button"
        :class="colors"
        class="inline-flex justify-center rounded-md border border-transparent  px-4 py-2 text-sm font-medium  focus:outline-none focus-visible:ring-2  focus-visible:ring-offset-2"
    >
        <slot />
    </button>

</template>