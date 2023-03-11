import axios from "axios";

export const AxiosBase = axios.create({
    baseURL: process.env.MIX_LARAVEL_SERVER_BASE_URL
});