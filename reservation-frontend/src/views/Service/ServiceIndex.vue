<script setup>
  import useServices from '@/composables/services';
  import badge from '@/components/badge.vue';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { services,getServices,destroyService } = useServices()

  onMounted(() => getServices())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Services</h1>
            <p class="text-sm">List of the services</p>
          </div>
          <RouterLink :to="{name: 'ServiceCreate'}">
            <mainBtn :btn_type="3">Create Service</mainBtn> 
          </RouterLink>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="service in services" :key="service.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ service.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ service.name }}
                    </td>
                    <td class="px-6 py-4">
                      <badge :content="service.status ? 'Active' : 'Disable'" :type="service.status" />
                    </td>
                    <td>
                      <div class="flex">
                        <RouterLink :to="{name: 'ServiceEdit', params:{id:service.id}}" class="me-3">
                          <mainBtn :btn_type="2">Edit</mainBtn> 
                        </RouterLink>
                        <mainBtn :btn_type_status="service.status" @click="destroyService(service.id)">{{service.status ? 'Delete' : 'Active' }}</mainBtn>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
