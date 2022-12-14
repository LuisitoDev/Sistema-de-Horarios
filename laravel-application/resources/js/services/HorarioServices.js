import{ AxiosBase as axios } from "../utils/AxiosConfig";

export const Example = async () => {
    try {
        const response = await axios.get(`/prueba`);
        return response;
    }
    catch(err) {
        console.log(err);
        return err;
    }
}