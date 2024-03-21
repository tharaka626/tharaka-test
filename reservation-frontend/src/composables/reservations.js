import { ref } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

axios.defaults.baseURL = "http://127.0.0.1:8000/api/"

export default function useReservations() {

    const reservations = ref([])
    const available_services = ref([])
    const reservation = ref([])
    const errors = ref({})
    const router = useRouter()

    const getReservations = async() => {
        const response = await axios.get("reservations")
        reservations.value = response.data.data
    }

    const getReservation = async(id) => {
        const response = await axios.get("reservations/"+id)
        reservation.value = response.data.data
    }

    const getRoomAvServices = async(id) => {
        const response = await axios.get("reservations/"+id+"/available_services")
        available_services.value = response.data.data
    }

    const storeReservation = async(data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("reservations",
            data
            // ,
            // {
            //     headers: {
            //         'Content-Type': 'multipart/form-data'
            //     }
            // }
            )
            await router.push({name:"RerservationIndex"})
        } catch (error) {
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const updateReservation = async(id,data) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        try {
            await axios.post("reservations/"+id,
            data
            // ,
            // {   
            //     headers: {
            //         'Content-Type': 'multipart/form-data'
            //     }
            // }
            )
            await router.push({name:"RerservationIndex"})

        } catch (error) {
            console.log(error);
            if (error.response.status == 422) {
                errors.value = error.response.data.errors
            }
        }
    }

    const destroyReservation = async(id) => {
        if (!window.confirm("Are you sure?")) {
            return;
        }
        await axios.delete("reservations/"+id)
        await getReservations()

    }


    return {
        reservation,
        reservations,
        available_services,
        getReservations,
        getReservation,
        getRoomAvServices,
        storeReservation,
        updateReservation,
        destroyReservation,
        errors
    }
}