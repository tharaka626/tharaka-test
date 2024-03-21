<script setup>
  import { mapGetters, mapActions } from "vuex";
  import InputLabel from '@/components/InputLabel.vue';
  import InputError from '@/components/InputError.vue';
  import TextInput from '@/components/TextInput.vue';
  import DateInput from '@/components/DateInput.vue';
  import useRooms from '@/composables/rooms';
  import { reactive,onMounted,ref } from 'vue';
  import mainBtn from '@/components/button.vue'
  import TextAreaInput from '@/components/TextAreaInput.vue';
  import axios from "axios"
  import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'
axios.defaults.baseURL = "http://127.0.0.1:8000/api/"
  const { rooms,getActiveRooms } = useRooms()
  const isOpen = ref(false)
  const errors = ref({})
  const form = reactive({
        check_in_date: "",
        check_out_date: "",
    })
    const form2 = reactive({
        room_id: null,
        user_id: null,
        check_in_date:"",
        check_out_date: "",
        additional_request: "",
        number_of_guests: "",
    })

    function closeModal() {
  isOpen.value = false
}


function openModal(id) {
    form2.check_in_date = form.check_in_date,
    form2.check_out_date = form.check_in_date,
    form2.room_id = id,
  isOpen.value = true
}
const bookNow = async(data) => {
    await axios.post("reservations/front",data)
  isOpen.value = true

}
</script>

<template>
<div class="relative isolate px-6 pt-14 lg:px-8">
    <div class="mx-auto max-w-2xl">
      <div class="text-center">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Find your next stay</h1>
        <p class="mt-6 text-lg leading-8 text-gray-600">Search low prices on rooms...</p>
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <InputLabel>Check In Date<RequiredIcon/></InputLabel>
                <div class="mt-2">
                    <DateInput
                        id="check_in_date"
                        class="mt-1 block w-full"
                        v-model="form.check_in_date"
                    />
                </div>
                <div v-for="error in errors.check_in_date" :key="error" class="mt-2">
                <InputError       
                    :message="error" 
                />
                </div>
            </div>
            <div class="sm:col-span-3">
                <InputLabel>Check Out Date<RequiredIcon/></InputLabel>
                <div class="mt-2">
                    <DateInput
                        id="check_in_date"
                        class="mt-1 block w-full"
                        v-model="form.check_out_date"
                    />
                </div>
                <div v-for="error in errors.check_out_date" :key="error" class="mt-2">
                <InputError       
                    :message="error" 
                />
                </div>
            </div>
        </div>
        <div class="mt-10 flex items-center justify-center gap-x-6">
          <a href="#" @click="getActiveRooms" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Find Rooms</a>
        </div>
      </div>

      <ul role="list" class="divide-y divide-gray-100" >
            <li class="flex justify-between gap-x-6 py-5 border p-5 my-3" v-for="room in rooms" :key="room">
                <div class="flex min-w-0 gap-x-4">
                <img class="h-32 w-48 flex-none bg-gray-50" :src="$hostname+room.main_image" alt="">
                <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900">{{ room.name }}</p>
                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ room.room_type.name}}</p>
                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">Size - {{ room.room_size}}</p>
                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">Beds </p>
                    <ul class="ms-5 list-disc" v-for="bed in room.bed_types" :key="bed">
                        <li class="text-xs text-gray-500">{{ bed.name }}</li>
                    </ul>
                    
                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ room.description}}</p>
                </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-gray-900">Rs {{ room.total_amt }}</p>
                <mainBtn    @click="openModal(room.id)" type="button" :btn_type="1">Book</mainBtn> 
                </div>
            </li>
        </ul>

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                class="flex min-h-full items-center justify-center p-4 text-center"
                >
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                    <DialogPanel
                    class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                    >
                    <DialogTitle
                        as="h3"
                        class="text-lg font-medium leading-6 text-gray-900"
                    >
                        Book This Room
                    </DialogTitle>
                    <form @submit.prevent="bookNow(form2)">

                        <div class="mt-10 w-full grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel>User<RequiredIcon/></InputLabel>
                                <div class="mt-2">
                                <TextInput class="bg-gray-100" v-model="form2.user_id" disabled/>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel>Room<RequiredIcon/></InputLabel>
                                <div class="mt-2">
                                    <TextInput class="bg-gray-100" v-model="form2.room_id" disabled/>
                                </div>
                            </div>
    
                            </div>
                        <div class="mt-10 w-full grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel>Check In Date<RequiredIcon/></InputLabel>
                                <div class="mt-2">
                                <TextInput class="bg-gray-100" v-model="form2.check_in_date" disabled/>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel>Check Out Date<RequiredIcon/></InputLabel>
                                <div class="mt-2">
                                    <TextInput class="bg-gray-100" v-model="form2.check_out_date" disabled/>
                                </div>
                            </div>
    
                            </div>
                        <div class="mt-10">
                            <InputLabel>Additional Request<RequiredIcon/></InputLabel>
                            <div class="mt-2">
                                <TextAreaInput v-model="form2.additional_request" />
                            </div>
                            <div v-for="error in errors.additional_request" :key="error" class="mt-2">
                                <InputError       
                                :message="error" 
                                />
                            </div>
                        </div>
    
                        <div class="mt-4">
                            <button
                            type="button"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                            @click="closeModal"
                            >
                            Book Now
                            </button>
                        </div>
                    </form>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
            </Dialog>
        </TransitionRoot>
    </div>

  </div>
  </template>
  
  
  