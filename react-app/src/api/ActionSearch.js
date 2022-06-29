import { getTheme } from "./API";

export const getSearchItems = (callback) => {
    getTheme().get("/search")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};