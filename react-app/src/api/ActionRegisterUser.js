import { getTheme } from "./API";

const postRegister = (data, callback) => {
    getTheme().post("/register", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

export { postRegister };