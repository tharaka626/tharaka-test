<script setup>
  import InputLabel from '@/components/InputLabel.vue';
  import InputError from '@/components/InputError.vue';
  import TextInput from '@/components/TextInput.vue';
  import SelectInput from '@/components/SelectInput.vue';
  import mainBtn from '@/components/button.vue'
  import BackButton from '@/components/BackButton.vue';
  import RequiredIcon from '@/components/RequiredIcon.vue';
  import { onMounted,ref } from 'vue';
  import useRoomTypes from '@/composables/room_types';

  const { room_type,getRoomType,updateRoomType,errors } = useRoomTypes() 
  const props = defineProps(['id']);

  const selectOptions = [
    {
      id: 0,
      name: 'Disable',
    },
    {
      id: 1,
      name: 'Active',
    }
  ];

  const isMounted = ref(false);

  onMounted(async () => {await getRoomType(props.id); isMounted.value = true})

</script>

<template>
  <form class="bg-white max-w-4xl mx-auto p-4 grid justify-center" @submit.prevent="updateRoomType($route.params.id)">
    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Update Room Type</h2>
        <p class="mt-1 text-sm leading-6 text-gray-600"><RequiredIcon/> Required Fields</p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Name<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput v-model="room_type.name" />
            </div>
            <div v-for="error in errors.name" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Status<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-if="isMounted" v-model="room_type.status" :name="'status'" :options="selectOptions"/>
            </div>
            <div v-for="error in errors.status" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>

        </div>
        <div class="flex justify-end mt-10">
          <BackButton class="me-5"/>
          <mainBtn type="submit" :btn_type="1">Submit</mainBtn> 
        </div>

      </div>
    </div>
  </form>
</template>
