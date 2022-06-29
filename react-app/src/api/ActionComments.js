import { getTheme, postTheme, postWpTheme } from "./API";

const getCommentsItems = (postID, callback) => {
    if (postID) {
        getTheme().get(`/comments/${postID}`)
            .then(res => callback(true, res.data))
            .catch(err => callback(false, err));
    } else {
        return callback(false, "null");
    }
};

const postNewComment = (data, callback) => {
    postTheme().post("/comments", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postCreateComment = (data, callback) => {
    postWpTheme().post("/comments", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

export { getCommentsItems, postNewComment, postCreateComment };