import axios from "axios";

const site_url = localStorage.getItem("site_url");
const base = "/wp-json";
const namespace = "/aatheme/v1";

const getTheme = () => {
    return axios.create({
        baseURL: `${site_url}${base}${namespace}`
    });
};

const postTheme = () => {
    const auth_token = localStorage.getItem("auth_token");
    return axios.create({
        baseURL: `${site_url}${base}${namespace}`,
        headers: {
            Authorization: `Bearer ${auth_token}`,
        }
    });
};

const postWpTheme = () => {
    const auth_token = localStorage.getItem("auth_token");
    return axios.create({
        baseURL: `${site_url}${base}/wp/v2`,
        headers: {
            Authorization: `Bearer ${auth_token}`,
        }
    });
};

const postJWT = () => {
    return axios.create({
        baseURL: `${site_url}${base}/jwt-auth/v1`
    });
};

export { getTheme, postWpTheme, postTheme, postJWT };