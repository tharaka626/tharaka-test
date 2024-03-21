<script setup>
  import useRooms from '@/composables/rooms';
  import badge from '@/components/badge.vue';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { rooms,getRooms,destroyRoom } = useRooms()

  onMounted(() => getRooms())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Rooms</h1>
            <p class="text-sm">List of the rooms</p>
          </div>
          <RouterLink :to="{name: 'RoomCreate'}">
            <mainBtn :btn_type="3">Create Room</mainBtn> 
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
                        Room Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Num Of Guest
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Is Breakfirst Included
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Price (Rs)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Used Count/Total Count
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
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="room in rooms" :key="room.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ room.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ room.name }}
                    </td>
                    <td class="px-6 py-4">
                      {{ room.room_type.name }}
                    </td>
                    <td class="px-6 py-4">
                      {{ room.number_of_guests }}
                    </td>
                    <td class="px-6 py-4">
                      {{ room.breakfirst_included ? 'Yes' : 'No' }}
                    </td>
                    <td class="px-6 py-4">
                      {{ room.total_amt }}
                    </td>
                    <td class="px-6 py-4">
                      {{ room.used_count }} / {{ room.total_count }}
                    </td>
                    <td class="px-6 py-4">
                      <badge :content="room.status ? 'Active' : 'Disable'" :type="room.status" />
                    </td>
                    <td>
                      <div class="flex">
                        <RouterLink :to="{name: 'RoomEdit', params:{id:room.id}}" class="me-3">
                          <mainBtn :btn_type="2">Edit</mainBtn> 
                        </RouterLink>
                        <mainBtn :btn_type_status="room.status" @click="destroyRoom(room.id)">{{room.status ? 'Delete' : 'Active' }}</mainBtn>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
