<script setup>
import { defineEmits, ref,watch } from 'vue';
import Calendar from 'primevue/calendar';

const { modelValue,max_date,min_date } = defineProps({
    modelValue: {
        type: Date,
        required: true,
    },
    max_date: {
        type: Date,
        required: false
    },
    min_date: {
        type: Date,
        required: false
    },
    disabled: {
        type: Boolean,
        required: false,
        default:false
    },
    width: {
        type: String,
        required: false,
        default:'auto'
    }
});
const emit  = defineEmits();

let selectedValue = ref(modelValue);

watch(selectedValue, (v) => {
    console.log(v);
    if (v == null) {
        console.log('dd');
        emit('update:modelValue', '');
    } else {
        const date = new Date(v.getTime() - v.getTimezoneOffset() * 60000)
        emit('update:modelValue', date);
        emit('onAction')
    }
});



</script>

<template>
    <!-- <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    /> -->
    <Calendar
        v-model="selectedValue"
        :minDate="min_date"
        :maxDate="max_date"
        showIcon
        iconDisplay="input"
        dateFormat="yy-mm-dd"
        showButtonBar
        :disabled="disabled"
        :inputStyle="'width:'+width"
    />
</template>

