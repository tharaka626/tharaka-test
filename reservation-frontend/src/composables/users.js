import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useUsers() {

    const users = ref([])
    const user = ref([])
    const errors = ref({})
    const router = useRouter()

    const getUsers = async() => {
        const response = await axios.get("users")
        users.value = response.data.data
    }

    const getUser = async(id) => {
        const response = await axios.get("users/"+id)
        user.value = response.data.data
    }

    
    const getActiveUsers = async() => {
        const response = await axios.get("users/active")
        users.value = response.data.data
    }

    const storeUser = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("users",data)
            await router.push({name:"UserIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const updateUser = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.put("users/"+id, user.value)
            await router.push({name:"UserIndex"})

        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const destroyUser = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        await axios.delete("users/"+id)
        await getUsers()

    }

    return {
        user,
        users,
        getUser,
        getUsers,
        getActiveUsers,
        storeUser,
        updateUser,
        destroyUser,
        errors
    }
}