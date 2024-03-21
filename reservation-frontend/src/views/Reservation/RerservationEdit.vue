<script setup>
  import axios from "axios"
  import InputLabel from '@/components/InputLabel.vue';
  import InputError from '@/components/InputError.vue';
  import TextInput from '@/components/TextInput.vue';
  import TextAreaInput from '@/components/TextAreaInput.vue';
  import mainBtn from '@/components/button.vue'
  import BackButton from '@/components/BackButton.vue';
  import RequiredIcon from '@/components/RequiredIcon.vue';
  import { reactive,onMounted,ref,onUpdated } from 'vue';
  import useRooms from '@/composables/rooms';
  import useRoomTypes from '@/composables/room_types';
  import useBedTypes from '@/composables/bed_types';
  import SelectInput from '@/components/SelectInput.vue';
  import ImageCropper from '@/components/imageCropper.vue'
  import ImageCropper2 from '@/components/imageCropper.vue'
  import PlusIcon from '@/components/plusIcon.vue';
  import RemoveIcon from '@/components/removeIcon.vue';
  import miniTable from '@/components/miniTable.vue';

  const { room,getRoom,updateRoom,errors,available_services,getRoomAvServices } = useRooms() 
  const { room_types,getRoomTypes } = useRoomTypes()
  const { bed_types,getBedTypes } = useBedTypes()

  const props = defineProps(['id']);

  const url = ref(null);
  const coverInputRef = ref(null);
  const isMounted = ref(false);

  const form = reactive({
      _method: 'PUT',
      name:"",
      room_type_id: null,
      description: "",
      number_of_guests: "",
      counter: "",
      room_size: "",
      breakfirst_included: null,
      main_image: null,
      main_image_path: null,
      price_amt: "",
      net_services_amt: "",
      total_amt: "",
      room_bed_types: null,
      current_room_services: [],
      room_services: [{
        service: null,
        description: "",
        price: "",
      }],
      room_other_images_path:[],
      room_other_images: [{
        refer:ref(null),
        img:"",
        url:null
      }],
      status: null,
    })

  onMounted(async () => {
    await getRoom(props.id); 
    isMounted.value = true
    console.log(room);

    form.name = room.value.name
    form.room_type_id = room.value.room_type_id
    form.description = room.value.description
    form.number_of_guests = room.value.number_of_guests
    form.counter = room.value.total_count
    form.room_size = room.value.room_size
    form.breakfirst_included = room.value.breakfirst_included
    form.price_amt = room.value.price_amt_decimal
    form.net_services_amt = room.value.net_services_amt_decimal
    form.total_amt = room.value.total_amt_decimal
    form.room_bed_types = room.value.bed_types
    form.status = room.value.status
    form.main_image_path = room.value.main_image
    form.room_other_images_path = room.value.other_images
    form.current_room_services = room.value.services
    getRoomTypes();
    getBedTypes();
    getRoomAvServices(props.id);
  })
  form.name = 'fdfdf'
  const destroyRoomService = async(id) => {
      if (!window.confirm("Are you sure to remove room service?")) {
          return;
      }
      await axios.delete("rooms/service/"+id)
      await getServAg()
  }

  const destroyRoomOtherImage = async(id) => {
      if (!window.confirm("Are you sure to remove this other image?")) {
          return;
      }
      await axios.delete("rooms/other_images/"+id)
      await getOImgAg()
  }


  const getServAg = async() => {
    await getRoom(props.id); 
    form.current_room_services = room.value.services
  }

  const getOImgAg = async() => {
    await getRoom(props.id); 
    form.room_other_images_path = room.value.other_images
  }


  const previewImage = (e) => {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        url.value = e.target.result;
    }
    reader.readAsDataURL(file);

    console.log(url.value);
  }

  const previewImage_other = (e,index) => {

    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        form.room_other_images[index].url = e.target.result;
    }
    reader.readAsDataURL(file);

    console.log(url.value);
  }

  const addField = (type) => {
      if (type == 'service') {
        form.room_services.push({
          service: null,
          description: "",
          price: "",
        });
      }
      if (type == 'other') {
        form.room_other_images.push({
            refer:ref(null),
            img:"",
            url:null
        });
      }

  }

  const removeField = (type) => {
    if (type == 'service') {
      if (form.room_services.length > 1) {
          form.room_services.pop();
        }
    }
    if (type == 'other') {
      if (form.room_other_images.length > 1) {
          form.room_other_images.pop();
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
    }
  ];

  const selectOptions2 = [
    {
      id: 0,
      name: 'No',
    },
    {
      id: 1,
      name: 'Yes',
    }
  ];

  const updateTotalServiceAmt = () => {
      let total_service_amt = 0;

      form.current_room_services.forEach(element => {
        total_service_amt += +element.price_decimal
      });

      form.room_services.forEach(element => {
          total_service_amt += +element.price
      });

      form.net_services_amt = total_service_amt
      updateTotalNetAmt();
  }

  const updateTotalNetAmt = () => {
      form.total_amt = +form.price_amt + +form.net_services_amt
  }

  const resetService = (key) => {
    form.room_services[key].description = ""
    form.room_services[key].price = ""
    updateTotalServiceAmt()
  }


</script>

<template>
  <form class="bg-white max-w-4xl mx-auto p-4 grid justify-center" @submit.prevent="updateRoom($route.params.id,form)">
    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Update Room</h2>
        <p class="mt-1 text-sm leading-6 text-gray-600"><RequiredIcon/> Required Fields</p>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Name<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput class="w-full" v-model="form.name" />
            </div>
            <div v-for="error in errors.name" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Room Type<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-if="isMounted" v-model="form.room_type_id" :name="'Room Type'" :options="room_types"/>
            </div>
            <div v-for="error in errors.status" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
        </div>

        <div class="mt-10">
          <InputLabel>Description<RequiredIcon/></InputLabel>
          <div class="mt-2">
            <TextAreaInput v-model="form.description" />
          </div>
          <div v-for="error in errors.description" :key="error" class="mt-2">
            <InputError       
              :message="error" 
            />
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
            <InputLabel>Room Size<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput class="w-full" v-model="form.room_size" />
            </div>
            <div v-for="error in errors.room_size" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>

        </div>

        <div class="my-6">
            <label
                for="current_main_image"
                class="block mb-2 text-sm font-medium text-gray-900"
            >
                Current Main Image
            </label>


            <img
                :src="$hostname+form.main_image_path"
                class="w-full mt-4 h-96"
                alt="Current Main Image"
            />
        </div>

        <div class="mt-10 w-100">
            <label
                for="cover_image"
                class="block mb-2 text-sm font-medium text-gray-900"
            >
                Main Image
            </label>


            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                    </svg>
                    <div class="mt-4 flex justify-center text-sm leading-6 text-gray-600">
                        <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span>Upload a file</span>

                        <input
                            class="sr-only"
                            type="file"
                            id="file-upload" name="file-upload"
                            ref="coverInputRef"
                            @change="previewImage"
                        />
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs leading-5 text-gray-600">PNG, JPG up to 2MB - <span class="text-red-500"> Recommend size - 1350*384 px</span></p>
                </div>
            </div>

            <div class="max-w-3xl">
              <ImageCropper v-model:form_image="form.main_image" :url="url" />
            </div>

            <div v-for="error in errors.main_image" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>

        </div>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Is breakfirst included?<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-if="isMounted" v-model="form.breakfirst_included" :name="'breakfirst include status'" :options="selectOptions2"/>
            </div>
            <div v-for="error in errors.breakfirst_included" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Status<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <SelectInput v-if="isMounted" v-model="form.status" :name="'status'" :options="selectOptions"/>
            </div>
            <div v-for="error in errors.status" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>

        </div>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <InputLabel>Room Bed Types</InputLabel>
            <div class="mt-2">
              <SelectInput v-if="isMounted" v-model="form.room_bed_types" :type="'multiple'" :name="'bed_type'" :options="bed_types"/>
            </div>
            <div v-for="error in errors.room_bed_types" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
          <div class="sm:col-span-3">
            <InputLabel>Room Count<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=1 class="w-full" v-model="form.counter" />
            </div>
            <div v-for="error in errors.counter" :key="error" class="mt-2">
              <InputError       
                :message="error" 
              />
            </div>
          </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-6 ">
            <InputLabel for="current_services_list" value="Current Services List"/>

            <miniTable>
                <template #thead>
                    <tr>
                        <th scope="col" class="px-6 py-3">Service</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Delete</th>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="(current_room_service,index) in form.current_room_services" :key="index">
                        <td class="px-5 py-2 font-normal text-gray-900 dark:text-white whitespace-nowrap w-20">
                          {{ current_room_service.name }}
                        </td>
                        <td class="px-5 py-2 font-normal text-gray-900 dark:text-white whitespace-nowrap">
                          <TextAreaInput v-model="current_room_service.description" />
                          <div v-for="error in errors['current_room_services.'+index+'.description']" :key="error" class="mt-2">
                            <InputError       
                              :message="error" 
                            />
                          </div>
                        </td>
                        <td class="px-5 py-2 font-normal text-gray-900 dark:text-white whitespace-nowrap">
                          <TextInput class="w-full" type="number" min=0 v-model="current_room_service.price_decimal" 
                            @keyup="updateTotalServiceAmt"
                            />
                            <div v-for="error in errors['current_room_services.'+index+'.price_decimal']" :key="error" class="mt-2">
                              <InputError       
                                :message="error" 
                              />
                            </div>
                        </td>
                        <td class="px-5 py-2 font-normal text-gray-900 dark:text-white whitespace-nowrap">
                          <mainBtn :btn_type_status="1" @click="destroyRoomService(current_room_service.rs_id);">Delete</mainBtn>
                        </td>

                    </tr>
                </template>
            </miniTable>

        </div>

        <div class="mt-10">
              <div class="flex my-4">
                  <InputLabel for="room_services" value="Room Services"/>

                  <PlusIcon class="ms-5" @click="addField('service')"/>

                  <RemoveIcon class="ms-5" @click="removeField('service')" v-if="form.room_services.length > 1"/>

              </div>
              <div class="grid grid-cols-1 gap-2 md:grid-cols-12 p-2 border rounded-md my-3 bg-sky-100" v-for="(service,index) in form.room_services" :key="index">
                <div class="sm:col-span-3">
                  <InputLabel>Service</InputLabel>
                  <div class="mt-2">
                    <SelectInput v-model="service.service" :name="'service'" :options="available_services" @change="resetService(index)"/>
                  </div>
                  <div v-for="error in errors['room_services.'+index+'.service']" :key="error" class="mt-2">
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
                  <div v-for="error in errors['room_services.'+index+'.description']" :key="error" class="mt-2">
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
                  <div v-for="error in errors['room_services.'+index+'.price']" :key="error" class="mt-2">
                    <InputError       
                      :message="error" 
                    />
                  </div>
                </div>
              </div>
        </div>

        <div class="my-6">
            <label
                for="current_main_image"
                class="block mb-2 text-sm font-medium text-gray-900"
            >
                Current Other Images
            </label>

            <div class="flex flex-wrap" >
              <div v-for="room_other_images_path in form.room_other_images_path" :key="room_other_images_path">
                <div class="ms-5">
                  <mainBtn :btn_type_status="1" @click="destroyRoomOtherImage(room_other_images_path.id);">Delete</mainBtn>
                  <img
                      :src="$hostname+room_other_images_path.image"
                      class="w-48 mt-4 h-24"
                      alt="Current Other Image"
                  />
                </div>
              </div>
            </div>
        </div>

        <div class="my-6">
            <div class="flex">
                <label
                    for="cover_image"
                    class="block mb-2 text-sm font-medium text-gray-900"
                >
                    Other Images
                </label>
                <PlusIcon class="ms-5" @click="addField('other')"/>

                <RemoveIcon class="ms-5" @click="removeField('other')" v-if="form.room_other_images.length > 1"/>
            </div>


            <div class="mb-5 border p-3" v-for="(other_image,index) in form.room_other_images" :key="index">

                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                        </svg>
                        <div class="mt-4 flex justify-center text-sm leading-6 text-gray-600">
                            <label :for="'file-upload'+index" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                            <span>Upload a file</span>
                            <!-- <input id="file-upload2" name="file-upload2" type="file" class="sr-only" ref="coverInputRef2"
                                    @change="previewImage2"> -->

                            <input
                                class="sr-only"
                                type="file"
                                :id="'file-upload'+index" :name="'file-upload'+index"
                                :ref="other_image.refer"
                                @change="previewImage_other($event,index)"
                            />
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs leading-5 text-gray-600">PNG, JPG up to 2MB - <span class="text-red-500"> Recommend size - 1350*384 px</span></p>
                    </div>
                </div>

                <div class="max-w-3xl">
                  <ImageCropper2 v-model:form_image="other_image.img" :url="other_image.url" />
                </div>


                <div v-for="error in errors.other_image" :key="error" class="mt-2">
                  <InputError       
                    :message="error" 
                  />
                </div>
            </div>

        </div>

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-9">
          <div class="sm:col-span-3">
            <InputLabel>Room Price<RequiredIcon/></InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full" v-model="form.price_amt" @keyup="updateTotalNetAmt"/>
            </div>
            <div v-for="error in errors.price_amt" :key="error" class="mt-2">
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
            <InputLabel>Total Net Price</InputLabel>
            <div class="mt-2">
              <TextInput type="number" min=0 class="w-full bg-slate-200" v-model="form.total_amt" readonly/>
            </div>
            <div v-for="error in errors.total_amt" :key="error" class="mt-2">
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
