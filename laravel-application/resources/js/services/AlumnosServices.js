
import axios from 'axios'

export const GetStudentProfileInfo = async (tuition) => {
    try {
        const response = axios.get('/profile', {
            params: {
                tuition
            }
        });
        return response
    } catch(err){
        return err;
    }
}

export const GetStudents = async (elements, page, search, dayFrom, dayTo) => {
    try {
        const response = axios.get(`/admin/alumnos/cant/${elements}/pag/${page}/search/${search}/fecha-desde/${dayFrom}/fecha-hasta/${dayTo}`)

        return response;
    } catch(err){
        return err
    }
}

export const GetStudentsChecks = async (elements, page, search, dayFrom, dayTo) => {
    try {
        const response = axios.get(`/admin/alumnos-entradas/cant/${elements}/pag/${page}/search/${search}/fecha-desde/${dayFrom}/fecha-hasta/${dayTo}`)

        return response;
    } catch(err){
        return err
    }
}

export const UpdateProfilePicture = async (formData)=>{
    try {

        const response = axios.post('/profile', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
       // console.log("Estoy en el servicio:  "+formData.get('imagen'));
        return response;
    } catch(err){
        return err
    }
}
export const ImportStudents = async (formData) => {
    try {
        const response = axios.post('/admin/import_excel', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        return response;
    } catch(err){
        return err
    }
}

export const ExportStudents = async (dayFrom, dayTo) => {
    try {
        const response = axios.get(`/admin/export_excel/fecha-desde/${dayFrom}/fecha-hasta/${dayTo}`, { responseType: 'blob' });
        return response;
    } catch(err){
        return err
    }
}


export const ImportEntradas = async (formData) => {
    try {
        const response = axios.post('/admin/import_hours', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        return response;
    } catch(err){
        return err
    }
}

export const ExportEntradas = async (dayFrom, dayTo) => {
    try {
        const response = axios.get(`/admin/export_hours/fecha-desde/${dayFrom}/fecha-hasta/${dayTo}`, { responseType: 'blob' });
        return response;
    } catch(err){
        return err
    }
}


export const getEntradas = async (elements, page, dayFrom, dayTo) => {
    try {
        const response = axios.get(`/progreso_horas/cant/${elements}/pag/${page}/fecha-desde/${dayFrom}/fecha-hasta/${dayTo}`);

        return response;
    } catch(err){
        return err
    }
}



export const getProgresoGeneral = async (elements, dayFrom, dayTo) => {
    try {
        const response = axios.get(`/progreso_general/cant/${elements}/fecha-desde/${dayFrom}/fecha-hasta/${dayTo}`);

        return response;
    } catch(err){
        return err
    }
}

export const DeleteStudent = async (tuition) => {
    try {
        const response = axios.delete(`/admin/alumnos/${tuition}`);

        return response;
    } catch(err) {
        return err;
    }
}

export const DeleteOldData = async () => {
    try {
        const response = axios.delete('/admin/delete_old_data');

        return response;
    }catch(err) {
        return err;
    }
}
