import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useBedTypes() {

    const bed_types = ref([])
    const bed_type = ref([])
    const errors = ref({})
    const router = useRouter()

    const getBedTypes = async() => {
        const response = await axios.get("bed_types")
        bed_types.value = response.data.data
    }

    const getBedType = async(id) => {
        const response = await axios.get("bed_types/"+id)
        bed_type.value = response.data.data
    }

    
    const getActiveBedTypes = async() => {
        const response = await axios.get("bed_types/active")
        bed_types.value = response.data.data
    }


    const storeBedType = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("bed_types",data)
            await router.push({name:"BedTypeIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const updateBedType = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.put("bed_types/"+id, bed_type.value)
            await router.push({name:"BedTypeIndex"})

        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const destroyBedType = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        await axios.delete("bed_types/"+id)
        await getBedTypes()

    }

    return {
        bed_type,
        bed_types,
        getBedType,
        getBedTypes,
        getActiveBedTypes,
        storeBedType,
        updateBedType,
        destroyBedType,
        errors
    }
}