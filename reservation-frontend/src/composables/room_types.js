import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useRoomTypes() {

    const room_types = ref([])
    const room_type = ref([])
    const errors = ref({})
    const router = useRouter()

    const getRoomTypes = async() => {
        const response = await axios.get("room_types")
        room_types.value = response.data.data
    }

    const getRoomType = async(id) => {
        const response = await axios.get("room_types/"+id)
        room_type.value = response.data.data
    }

    
    const getActiveRoomTypes = async() => {
        const response = await axios.get("room_types/active")
        room_types.value = response.data.data
    }

    const storeRoomType = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("room_types",data)
            await router.push({name:"RoomTypeIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const updateRoomType = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.put("room_types/"+id, room_type.value)
            await router.push({name:"RoomTypeIndex"})

        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const destroyRoomType = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        await axios.delete("room_types/"+id)
        await getRoomTypes()

    }

    return {
        room_type,
        room_types,
        getRoomType,
        getRoomTypes,
        getActiveRoomTypes,
        storeRoomType,
        updateRoomType,
        destroyRoomType,
        errors
    }
}