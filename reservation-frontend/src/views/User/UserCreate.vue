<script setup>
  import InputLabel from '@/components/InputLabel.vue';
  import InputError from '@/components/InputError.vue';
  import TextInput from '@/components/TextInput.vue';
  import mainBtn from '@/components/button.vue'
  import BackButton from '@/components/BackButton.vue';
  import RequiredIcon from '@/components/RequiredIcon.vue';
  import { reactive } from 'vue';
  import useUsers from '@/composables/users';

  const { storeUser,errors } = useUsers() 

  const form = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  })
  console.log(errors);
</script>

<template>
  <form class="bg-white max-w-4xl mx-auto p-4 grid justify-center" @submit.prevent="storeUser(form)">
    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Create User</h2>
        <p class="mt-1 text-sm leading-6 text-gray-600"><RequiredIcon/> Required Fields</p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Name<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput v-model="form.name" />
            </div>
            <div v-for="error in errors.name" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Email<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput v-model="form.email" />
            </div>
            <div v-for="error in errors.email" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
        </div>
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Password<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="password" v-model="form.password" />
            </div>
            <div v-for="error in errors.password" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Confirm Password<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="password" v-model="form.password_confirmation" />
            </div>
            <div v-for="error in errors.password_confirmation" :key="error" class="mt-2">
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
