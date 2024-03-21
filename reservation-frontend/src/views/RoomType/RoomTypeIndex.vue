<script setup>
  import useRoomTypes from '@/composables/room_types';
  import badge from '@/components/badge.vue';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { room_types,getRoomTypes,destroyRoomType } = useRoomTypes()

  onMounted(() => getRoomTypes())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Room Types</h1>
            <p class="text-sm">List of the room types</p>
          </div>
          <RouterLink :to="{name: 'RoomTypeCreate'}">
            <mainBtn :btn_type="3">Create Room Type</mainBtn> 
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
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="room_type in room_types" :key="room_type.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ room_type.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ room_type.name }}
                    </td>
                    <td class="px-6 py-4">
                      <badge :content="room_type.status ? 'Active' : 'Disable'" :type="room_type.status" />
                    </td>
                    <td>
                      <div class="flex">
                        <RouterLink :to="{name: 'RoomTypeEdit', params:{id:room_type.id}}" class="me-3">
                          <mainBtn :btn_type="2">Edit</mainBtn> 
                        </RouterLink>
                        <mainBtn :btn_type_status="room_type.status" @click="destroyRoomType(room_type.id)">{{room_type.status ? 'Delete' : 'Active' }}</mainBtn>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
