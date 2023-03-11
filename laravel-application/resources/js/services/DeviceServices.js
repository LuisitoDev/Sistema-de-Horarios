import axios from 'axios'

export const GetDevicesRequest = async(elements, page, selectedSchoolCycle) => {
    try {
        const response = axios.get(`/admin/solicitudes/dispositivos/cant/${elements}/pag/${page}/selected_school_cycle/${selectedSchoolCycle}`)

        return response;
    } catch(err){
        return err
    }
}

export const AcceptDeviceRequest = async(id) => {
    try {
        const body = {
            id
        }
        const response = axios.post(`/admin/solicitudes/dispositivos`, body)

        return response
    }catch(err) {
        return err
    }
}

export const RejectDeviceRequest = async(id) => {
    try {
        const data = {
            id
        }

        const response = axios.delete(`/admin/solicitudes/dispositivos`, {data})

        return response
    } catch(err) {
        return err
    }
}

export const SignInDevice = async(email) => {
    try {
        const body = {
            'email': email
        }

        const response = axios.post('/signin-device', body)

        return response

    } catch(err) {
        return err
    }
}
