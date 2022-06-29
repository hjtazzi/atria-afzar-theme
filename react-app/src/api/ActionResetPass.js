import { getTheme } from "./API";

const postSendResetPass = (data, callback) => {
    getTheme().post("/sendresetpass", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postChangePass = (data, callback) => {
    getTheme().post("/changepass", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

export { postSendResetPass, postChangePass };