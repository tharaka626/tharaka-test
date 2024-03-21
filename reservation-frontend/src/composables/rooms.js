import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useRooms() {

    const rooms = ref([])
    const available_services = ref([])
    const room = ref([])
    const errors = ref({})
    const router = useRouter()

    const getRooms = async() => {
        const response = await axios.get("rooms")
        rooms.value = response.data.data
    }

    const getRoom = async(id) => {
        const response = await axios.get("rooms/"+id)
        room.value = response.data.data
    }

    
    const getActiveRooms = async() => {
        const response = await axios.get("rooms/active")
        rooms.value = response.data.data
    }

    const getRoomAvServices = async(id) => {
        const response = await axios.get("rooms/"+id+"/available_services")
        available_services.value = response.data.data
    }

    const storeRoom = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("rooms",
            data
            ,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            )
            await router.push({name:"RoomIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const updateRoom = async(id,data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("rooms/"+id,
            data
            ,
            {   
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            )
            await router.push({name:"RoomIndex"})

        } catch (error) {
            console.log(error);
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const destroyRoom = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        await axios.delete("rooms/"+id)
        await getRooms()

    }


    return {
        room,
        rooms,
        available_services,
        getRoom,
        getRooms,
        getActiveRooms,
        getRoomAvServices,
        storeRoom,
        updateRoom,
        destroyRoom,
        errors
    }
}