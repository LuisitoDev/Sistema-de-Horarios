import axios from 'axios'

export const SignIn = async(username, password)=> {
    try{
        const body = {
            username,
            password
        }

        const response = axios.post('/admin/signin', body);
        return response;
    }catch(err){
        return err;
    }
}


export const SignOut = async()=> {
    try{
        const response = axios.post('/admin/logout');
        return response;
    }catch(err){
        return err;
    }
}


export const GetSchoolCycles = async()=> {
    try{
        const response = axios.get('/admin/ciclo-escolar');
        return response;
    }catch(err){
        return err;
    }
}

// !DEPRECATED
export const SetSchoolCycles = async(formData)=> {
    try{
        const response = axios.post('/admin/set-id-ciclo-escolar', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        return response;
    }catch(err){
        return err;
    }
}
