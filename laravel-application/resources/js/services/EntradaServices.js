import axios from 'axios'

export const RegistarHoraEntrada = async (tuition = null) => {
    try {
        let data = {}
        if(tuition)
            data.tuition = tuition

        const response = axios.post(`/entrada/registrar-hora-entrada`, data);
        return response;
    }
    catch(err) {
        console.log(err);
        return err;
    }
}

export const RegistarHoraSalida = async (tuition = null) => {
    try {
         let data = {}
        if(tuition)
            data.tuition = tuition

        const response = axios.put(`/entrada/registrar-hora-salida`, data);
        return response;
    }
    catch(err) {
        console.log(err);
        return err;
    }
}

export const ObtenerEntradasDiarias = async (tuition = null) => {
    let data = {}
        if(tuition)
            data.tuition = tuition
    console.log('tuition', tuition)
    try {
        const response = axios.get(`/entrada/obtener-entrada-diaria`, {
            params: {
                tuition: data.tuition
            }
        });
        return response;
    }
    catch(err) {
        console.log(err);
        return err;
    }
}
