<script setup>
  import useVats from '@/composables/vats';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { vats,getVats } = useVats()

  onMounted(() => getVats())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Vats</h1>
            <p class="text-sm">List of the vats</p>
          </div>
          <RouterLink :to="{name: 'VatCreate'}">
            <mainBtn :btn_type="3">Create Vat</mainBtn> 
          </RouterLink>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precentage
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Start Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        End Date
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="vat in vats" :key="vat.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ vat.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ vat.precentage }}
                    </td>
                    <td class="px-6 py-4">
                      {{ vat.start_date }}
                    </td>
                    <td class="px-6 py-4">
                      {{ vat.end_date }}
                    </td>
                    
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
