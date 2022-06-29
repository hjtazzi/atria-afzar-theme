import { getTheme, postJWT, postTheme } from "./API";

const postLogin = (data, callback) => {
    getTheme().post("/login", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postSignon = (data, callback) => {
    postTheme().post("/signon", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postToken = (data, callback) => {
    postJWT().post("/token", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

export { postLogin, postSignon, postToken };