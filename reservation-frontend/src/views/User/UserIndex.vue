<script setup>
  import useUsers from '@/composables/users';
  import badge from '@/components/badge.vue';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { users,getUsers,destroyUser } = useUsers()

  onMounted(() => getUsers())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Users</h1>
            <p class="text-sm">List of the users</p>
          </div>
          <RouterLink :to="{name: 'UserCreate'}">
            <mainBtn :btn_type="3">Create User</mainBtn> 
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
                        Email
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
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="user in users" :key="user.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ user.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ user.name }}
                    </td>
                    <td class="px-6 py-4">
                      {{ user.email }}
                    </td>
                    <td class="px-6 py-4">
                      <badge :content="user.status ? 'Active' : 'Disable'" :type="user.status" />
                    </td>
                    <td>
                      <div class="flex">
                        <RouterLink :to="{name: 'UserEdit', params:{id:user.id}}" class="me-3">
                          <mainBtn :btn_type="2">Edit</mainBtn> 
                        </RouterLink>
                        <mainBtn :btn_type_status="user.status" @click="destroyUser(user.id)">{{user.status ? 'Delete' : 'Active' }}</mainBtn>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
