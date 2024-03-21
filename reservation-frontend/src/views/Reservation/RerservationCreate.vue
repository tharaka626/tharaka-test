<script setup>
  import InputLabel from '@/components/InputLabel.vue';
  import InputError from '@/components/InputError.vue';
  import TextInput from '@/components/TextInput.vue';
  import DateInput from '@/components/DateInput.vue';
  import TextAreaInput from '@/components/TextAreaInput.vue';
  import mainBtn from '@/components/button.vue'
  import BackButton from '@/components/BackButton.vue';
  import RequiredIcon from '@/components/RequiredIcon.vue';
  import { reactive,onMounted,ref } from 'vue';
  import useReservations from '@/composables/reservations';
  import useUsers from '@/composables/users';
  import useRooms from '@/composables/rooms';
  import useVats from '@/composables/vats';
  import useServices from '@/composables/services';
  import SelectInput from '@/components/SelectInput.vue';
  import PlusIcon from '@/components/plusIcon.vue';
  import RemoveIcon from '@/components/removeIcon.vue';


  const { storeReservation,errors } = useReservations() 

  const { services,getActiveService } = useServices()
  const { users,getActiveUsers } = useUsers()
  const { rooms,getActiveRooms } = useRooms()
  const { vat,getActiveVat } = useVats()


  onMounted(() => {
    getActiveService();
    getActiveUsers();
    getActiveRooms();
    getActiveVat()
  })
console.log(services);

  const form = reactive({
    user_id: null,
    check_in_date: "",
    check_out_date: "",
    additional_request: "",
    number_of_guests: "",
    payment_status: null,
    sub_amount: "",
    net_services_amt: "",
    discount: "",
    vat: "",
    net_amount: "",
    total_paid_amount: "",
    reservation_details: [{
      room: null,
      description: ""
    }],
    reservation_services: [{
      service: null,
      description: "",
      price: "",
    }],
    reservation_payment_detials: [{
      payment_method: null,
      payment_status: null,
      paid_amount: "",
    }],
    status: null,
  })

  const addField = (type) => {
      if (type == 'service') {
        form.reservation_services.push({
          service: null,
          description: "",
          price: "",
        });
      }
      if (type == 'room') {
        form.reservation_details.push({
          room: null,
          description: ""
        });
      }
      if (type == 'payment') {
        form.reservation_payment_detials.push({
          payment_method: null,
          payment_status: null,
          paid_amount: "",
        });
      }

  }

  const removeField = (type) => {
    if (type == 'service') {
      if (form.reservation_services.length > 1) {
          form.reservation_services.pop();
        }
    }
    if (type == 'room') {
      if (form.reservation_details.length > 1) {
          form.reservation_details.pop();
        }
    }
    if (type == 'payment') {
      if (form.reservation_payment_detials.length > 1) {
          form.reservation_payment_detials.pop();
        }
    }

  }

  const selectOptions = [
    {
      id: 0,
      name: 'Disable',
    },
    {
      id: 1,
      name: 'Active',
    },
    {
      id: 2,
      name: 'Pending',
    }
  ];

  const payment_statuses = [
    {
      id: 0,
      name: 'Not Paid',
    },
    {
      id: 1,
      name: 'Paid',
    },
    {
      id: 2,
      name: 'Advanced Paid',
    },
    {
      id: 3,
      name: 'Pending',
    }
  ];

  const payment_methods = [
    {
      id: 0,
      name: 'Cash',
    },
    {
      id: 1,
      name: 'Bank',
    },
    {
      id: 2,
      name: 'Card',
    }
  ];
  const payment_statuses2 = [
    {
      id: 0,
      name: 'Additional',
    },
    {
      id: 1,
      name: 'Full',
    },
    {
      id: 2,
      name: 'Advanced',
    }
  ];

  const updateTRAmt = (val) => {
      let total_room_amt = 0;
      rooms.value.forEach(element => {
        if (element.id == val) {
          total_room_amt += +element.total_amt_decimal
        }
      });

      form.sub_amount = total_room_amt
      updateTotalNetAmt();
  }

  const updateTotalServiceAmt = () => {
      let total_service_amt = 0;

      form.reservation_services.forEach(element => {
          total_service_amt += +element.price
      });

      form.net_services_amt = total_service_amt
      updateTotalNetAmt();
  }

  const updateTotalPaidAmt = () => {
      let total_paid_amt = 0;

      form.reservation_payment_detials.forEach(element => {
        total_paid_amt += +element.paid_amount
      });

      form.total_paid_amount = total_paid_amt
      updateTotalNetAmt();
  }

  const updateTotalNetAmt = () => {
      let vat_prec = vat.value.precentage

      form.vat = (+form.sub_amount + +form.net_services_amt) * +vat_prec /100

      form.net_amount = (+form.sub_amount + +form.net_services_amt + +form.vat) - +form.discount
  }



</script>

<template>
  <form class="bg-white max-w-4xl mx-auto p-4 grid justify-center" @submit.prevent="storeReservation(form)">
    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Create Reservation</h2>
        <p class="mt-1 text-sm leading-6 text-gray-600"><RequiredIcon/> Required Fields</p>

        <div class="mt-10 w-60 mx-auto">
            <InputLabel>User<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-model="form.user_id" :name="'User'" :options="users"/>
            </div>
            <div v-for="error in errors.user_id" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
        </div>

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
                    id="check_out_date"
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

        <div class="mt-10">
          <InputLabel>Additional Request<RequiredIcon/></InputLabel>
          <div class="mt-2">
            <TextAreaInput v-model="form.additional_request" />
          </div>
          <div v-for="error in errors.additional_request" :key="error" class="mt-2">
            <InputError       
              :message="error" 
            />
          </div>
        </div>



        <div class="mt-10">
              <div class="flex my-4">
                  <InputLabel for="reservation_details" value="Reservation Details"/>

                  <PlusIcon class="ms-5" @click="addField('room')"/>

                  <RemoveIcon class="ms-5" @click="removeField('room')" v-if="form.reservation_details.length > 1"/>

              </div>
              <div class="grid grid-cols-1 gap-2 md:grid-cols-12 p-2 border rounded-md my-3 bg-sky-100" v-for="(reservation_detail,index) in form.reservation_details" :key="index">
                <div class="sm:col-span-3">
                  <InputLabel>Room</InputLabel>
                  <div class="mt-2">
                    <SelectInput v-model="reservation_detail.room" :name="'room'" :options="rooms" @change="updateTRAmt"/>
                  </div>
                  <div v-for="error in errors['reservation_details.'+index+'.room']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
                <div class="sm:col-span-6">
                  <InputLabel>Description</InputLabel>
                  <div class="mt-2">
                    <TextAreaInput v-model="reservation_detail.description" />
                  </div>
                  <div v-for="error in errors['reservation_details.'+index+'.description']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
               
              </div>
        </div>

        <div class="mt-10">
              <div class="flex my-4">
                  <InputLabel for="room_services" value="Reservation Services"/>

                  <PlusIcon class="ms-5" @click="addField('service')"/>

                  <RemoveIcon class="ms-5" @click="removeField('service')" v-if="form.reservation_services.length > 1"/>

              </div>
              <div class="grid grid-cols-1 gap-2 md:grid-cols-12 p-2 border rounded-md my-3 bg-sky-100" v-for="(service,index) in form.reservation_services" :key="index">
                <div class="sm:col-span-3">
                  <InputLabel>Service</InputLabel>
                  <div class="mt-2">
                    <SelectInput v-model="service.service" :name="'service'" :options="services" />
                  </div>
                  <div v-for="error in errors['reservation_services.'+index+'.service']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
                <div class="sm:col-span-6">
                  <InputLabel>Description</InputLabel>
                  <div class="mt-2">
                    <TextAreaInput v-model="service.description" />
                  </div>
                  <div v-for="error in errors['reservation_services.'+index+'.description']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
                <div class="sm:col-span-3">
                  <InputLabel>Price</InputLabel>
                  <div class="mt-2">
                    <TextInput class="w-full" type="number" min=0 v-model="service.price" 
                    @keyup="updateTotalServiceAmt"
                    />
                  </div>
                  <div v-for="error in errors['reservation_services.'+index+'.price']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
              </div>
        </div>

        <div class="mt-10">
              <div class="flex my-4">
                  <InputLabel for="room_services" value="Reservation Payments"/>

                  <PlusIcon class="ms-5" @click="addField('payment')"/>

                  <RemoveIcon class="ms-5" @click="removeField('payment')" v-if="form.reservation_payment_detials.length > 1"/>

              </div>
              <div class="grid grid-cols-1 gap-2 md:grid-cols-12 p-2 border rounded-md my-3 bg-sky-100" v-for="(payment,index) in form.reservation_payment_detials" :key="index">
                <div class="sm:col-span-3">
                  <InputLabel>Payment Method</InputLabel>
                  <div class="mt-2">
                    <SelectInput v-model="payment.payment_method" :name="'payment_method'" :options="payment_methods"/>
                  </div>
                  <div v-for="error in errors['reservation_payment_detials.'+index+'.payment_method']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
                <div class="sm:col-span-6">
                  <InputLabel>Payment status</InputLabel>
                  <div class="mt-2">
                    <SelectInput v-model="payment.payment_status" :name="'payment_status'" :options="payment_statuses2"/>
                  </div>
                  <div v-for="error in errors['reservation_payment_detials.'+index+'.payment_status']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
                <div class="sm:col-span-3">
                  <InputLabel>paid_amount</InputLabel>
                  <div class="mt-2">
                    <TextInput class="w-full" type="number" min=0 v-model="payment.paid_amount" 
                    @keyup="updateTotalPaidAmt"
                    />
                  </div>
                  <div v-for="error in errors['reservation_payment_detials.'+index+'.paid_amount']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
              </div>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-9">
          <div class="sm:col-span-3">
            <InputLabel>Sub Amount<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full" v-model="form.sub_amount" @keyup="updateTotalNetAmt"/>
            </div>
            <div v-for="error in errors.sub_amount" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Addtional Services Net Price</InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full" v-model="form.net_services_amt" @keyup="updateTotalNetAmt" />
            </div>
            <div v-for="error in errors.net_services_amt" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Discount</InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full" v-model="form.discount" @keyup="updateTotalNetAmt"/>
            </div>
            <div v-for="error in errors.discount" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>

        </div>
        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-9">
          <div class="sm:col-span-3">
            <InputLabel>Vat Amount</InputLabel>({{ vat.precentage+'%' }})
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full bg-slate-200" v-model="form.vat" readonly />
            </div>
            <div v-for="error in errors.vat" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Total Net Amount</InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full bg-slate-200" v-model="form.net_amount" readonly/>
            </div>
            <div v-for="error in errors.net_amount" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Total Paid Amount</InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full bg-slate-200" v-model="form.total_paid_amount" readonly/>
            </div>
            <div v-for="error in errors.total_paid_amount" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>

        </div>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Number of Guests<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=1 class="w-full" v-model="form.number_of_guests" />
            </div>
            <div v-for="error in errors.number_of_guests" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Payment Status<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-model="form.payment_status" :name="'payment_status'" :options="payment_statuses"/>
            </div>
            <div v-for="error in errors.payment_status" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>

        </div>


        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Status<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-model="form.status" :name="'status'" :options="selectOptions"/>
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
