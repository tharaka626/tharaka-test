<script setup>
  import useBedTypes from '@/composables/bed_types';
  import badge from '@/components/badge.vue';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { bed_types,getBedTypes,destroyBedType } = useBedTypes()

  onMounted(() => getBedTypes())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Bed Types</h1>
            <p class="text-sm">List of the bed types</p>
          </div>
          <RouterLink :to="{name: 'BedTypeCreate'}">
            <mainBtn :btn_type="3">Create Bed Type</mainBtn> 
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
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="bed_type in bed_types" :key="bed_type.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ bed_type.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ bed_type.name }}
                    </td>
                    <td class="px-6 py-4">
                      <badge :content="bed_type.status ? 'Active' : 'Disable'" :type="bed_type.status" />
                    </td>
                    <td>
                      <div class="flex">
                        <RouterLink :to="{name: 'BedTypeEdit', params:{id:bed_type.id}}" class="me-3">
                          <mainBtn :btn_type="2">Edit</mainBtn> 
                        </RouterLink>
                        <mainBtn :btn_type_status="bed_type.status" @click="destroyBedType(bed_type.id)">{{bed_type.status ? 'Delete' : 'Active' }}</mainBtn>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
