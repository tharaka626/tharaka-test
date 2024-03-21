import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useServices() {

    const services = ref([])
    const service = ref([])
    const errors = ref({})
    const router = useRouter()

    const getServices = async() => {
        const response = await axios.get("services")
        services.value = response.data.data
    }

    const getService = async(id) => {
        const response = await axios.get("services/"+id)
        service.value = response.data.data
    }
    const getActiveService = async() => {
        const response = await axios.get("services/active")
        services.value = response.data.data
    }
    

    const storeService = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("services",data)
            await router.push({name:"ServiceIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const updateService = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.put("services/"+id, service.value)
            await router.push({name:"ServiceIndex"})

        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const destroyService = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        await axios.delete("services/"+id)
        await getServices()

    }

    return {
        service,
        services,
        getService,
        getServices,
        getActiveService,
        storeService,
        updateService,
        destroyService,
        errors
    }
}