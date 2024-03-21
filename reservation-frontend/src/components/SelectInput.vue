<script setup>
import { onMounted,ref, defineProps, defineEmits,watch } from 'vue';
import Dropdown from 'primevue/dropdown';
import Multiselect from '@suadelabs/vue3-multiselect'

const { type,modelValue, options, name, value,label,index,can_edit } = defineProps({
    type: {
        type: String,
        required: false,
        default: 'single'
    },
    modelValue: {
        required: false,
    },
    options:{
        required: false,
    },
    name: {
        required: false,
    },
    value: {
        required: false,
        default: 'id'
    },
    label: {
        required: false,
        default: 'name'
    },
    index: {
        required: false,
    },
    can_edit: {
        required: false,
        default: false
    }
});
const emit  = defineEmits();
const selectedValue = ref(modelValue);
const index_val = ref(index);

watch(selectedValue, (v) => {
  emit('update:modelValue', v);
  console.log(selectedValue);
});


</script>

<template>
    <div class="card flex justify-content-center mt-1">
        <Dropdown
            v-if="type =='single'"
            v-model="selectedValue"
            @change="$emit('change', selectedValue,index_val)"
            :editable="can_edit"
            :options="options"
            :optionLabel= label
            :optionValue= value
            :placeholder="'Select a '+name"
            class="p-0.5 text-gray-700 text-sm md:w-full"
            filter
            showClear
        />
        <multiselect
            v-if="type =='multiple'"
            v-model="selectedValue"
            @select="test"
            :options="options"
            :multiple="true"
            :close-on-select="false"
            :clear-on-select="false"
            :placeholder="'Select a '+name"
            :preserve-search="true"
            :label = label
            :track-by= value
      >
    </multiselect>
    <!-- @select="$emit('change', selectedValue,index_val)" -->

    </div>
</template>


<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>



