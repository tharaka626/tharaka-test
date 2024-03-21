<script setup>
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";
import { ref,computed } from 'vue';

const url2 = ref(null);
const cropperRef = ref(null);
const emit = defineEmits(['update:form_image']);

const { url,form_image } = defineProps({
    url: {
        type: String,
        required: true,
        default: null
    },
    form_image: {
        type: [Array, Boolean],
        required:true,
    },
});


    const setCropImage = () => {
        const { canvas } = cropperRef.value.getResult();
        const cropResult = cropperRef.value.getResult();

        url2.value = cropResult.canvas.toDataURL("image/jpeg");
        canvas.toBlob(async blob => {
            // img_val.value = blob
            emit('update:form_image', blob);
        }, "image/png");

    }


</script>

<template>
    <div v-if="url" class="py-3 max-2xl">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <p class="text-gray-400 text-sm mt-2">Crop the image</p>
            <p class="text-gray-400 text-sm mt-2">Preview Image</p>
        </div>
        <div class="flex items-center p-2">
            <div class="w-2/4 mt-4 h-96">
                <cropper
                    class="w-full h-96"
                    :src="url"
                    :resizeImage="{ wheel: false }"
                    ref="cropperRef"
                />
                <div class="flex justify-end mt-2">
                    <button type="button" @click="setCropImage" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-slate-200 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-500">crop</button>
                </div>
            </div>
            <img
                v-if="url2"
                :src="url2"
                class="w-2/4 mt-4 h-1/4 ms-2"
                alt="Image preview"
            />
        </div>
    </div>
</template>

