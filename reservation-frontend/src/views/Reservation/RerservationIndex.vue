<script setup>
  import useReservations from '@/composables/reservations';
  import badge from '@/components/badge.vue';
  import mainBtn from '@/components/button.vue';
  import { onMounted } from 'vue';

  const { reservations,getReservations,destroyReservation } = useReservations()

  onMounted(() => getReservations())
</script>

<template>
  <div class="p-12">
    <div class="relative overflow-x-auto">
        <div class="flex justify-between mb-5">
          <div>
            <h1 class="font-bold">Reservations</h1>
            <p class="text-sm">List of the reservations</p>
          </div>
          <RouterLink :to="{name: 'RerservationCreate'}">
            <mainBtn :btn_type="3">Create Reservation</mainBtn> 
          </RouterLink>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        User
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Check In Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Check Out Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Num Of Guest
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Sub Amount (Rs)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Service Amount (Rs)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Discount (Rs)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Vat (Rs)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Net Amount (Rs)
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Paid Amount (Rs)
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
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="reservation in reservations" :key="reservation.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ reservation.id }}
                    </th>
                    <td class="px-6 py-4">
                      {{ reservation.user.name }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.check_in_date }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.check_out_date }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.number_of_guests }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.sub_amount }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.net_services_amt }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.discount }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.vat }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.net_amount }}
                    </td>
                    <td class="px-6 py-4">
                      {{ reservation.total_paid_amount }}
                    </td>
                    <td class="px-6 py-4">
                      <badge :content="reservation.status == 1 ? 'Active' : (reservation.status == 2 ? 'Pending' : 'Disable')" :type="reservation.status" />
                    </td>
                    <td>
                      <div class="flex">
                        <RouterLink :to="{name: 'RerservationEdit', params:{id:reservation.id}}" class="me-3">
                          <mainBtn :btn_type="2">Edit</mainBtn> 
                        </RouterLink>
                        <mainBtn :btn_type_status="reservation.status" @click="destroyReservation(reservation.id)">{{reservation.status ? 'Delete' : 'Active' }}</mainBtn>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>
