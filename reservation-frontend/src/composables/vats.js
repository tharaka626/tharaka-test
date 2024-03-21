import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useVats() {

    const vats = ref([])
    const vat = ref([])
    const errors = ref({})
    const router = useRouter()

    const getVats = async() => {
        const response = await axios.get("vats")
        vats.value = response.data.data
    }

    const getVat = async(id) => {
        const response = await axios.get("vats/"+id)
        vat.value = response.data.data
    }
    const getActiveVat = async() => {
        const response = await axios.get("vats/active")
        vat.value = response.data.data
    }

    const storeVat = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("vats",data)
            await router.push({name:"VatIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

   
    return {
        vat,
        vats,
        getVat,
        getVats,
        getActiveVat,
        storeVat,
        errors
    }
}