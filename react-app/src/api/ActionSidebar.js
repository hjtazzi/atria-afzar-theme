import { getTheme } from "./API";

export const getSidebarItems = (callback) => {
    getTheme().get("/sidebar")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};