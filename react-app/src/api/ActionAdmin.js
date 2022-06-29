import { getTheme, postTheme } from "./API";

const postSideMenu = (data, callback) => {
    postTheme().post("/sidemenu", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getEditProfile = (callback) => {
    postTheme().get("/editprofile")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const putEditProfile = (data, callback) => {
    postTheme().put("/editprofile", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
}

const putEditPassword = (data, callback) => {
    postTheme().put("/editpass", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
}

const getTickets = (callback) => {
    postTheme().get("/tickets")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const putReadedTicket = (data, callback) => {
    postTheme().put("/tickets", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postNewTicket = (data, callback) => {
    postTheme().post("/tickets", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getReceivedTickets = (callback) => {
    postTheme().get("/received_tickets")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postNewReceivedTicket = (data, callback) => {
    postTheme().post("/received_tickets", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getAuthorPosts = (postType, callback) => {
    postTheme().get(`/author_posts/${postType}`)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getEditUsers = (callback) => {
    postTheme().get("/editusers")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const putEditUser = (data, callback) => {
    postTheme().put("/editusers", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getProductRoot = (postID, callback) => {
    getTheme().get(`/product/${postID}`)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postCartItem = (postID, callback) => {
    postTheme().post(`/product/${postID}`)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getShoppingCart = (callback) => {
    postTheme().get("/cart")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const deleteCartItem = (row, callback) => {
    postTheme().delete(`/cart/${row}`)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getUserOrder = (callback) => {
    postTheme().get("/order")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const getPayments = (callback) => {
    postTheme().get("/payments")
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

const postCreatePayment = (data, callback) => {
    postTheme().post("/payments", data)
        .then(res => callback(true, res.data))
        .catch(err => callback(false, err))
};

export {
    postSideMenu,
    getEditProfile,
    putEditProfile,
    putEditPassword,
    getTickets,
    putReadedTicket,
    postNewTicket,
    getReceivedTickets,
    postNewReceivedTicket,
    getAuthorPosts,
    getEditUsers,
    putEditUser,
    getProductRoot,
    postCartItem,
    getShoppingCart,
    deleteCartItem,
    postCreatePayment,
    getUserOrder,
    getPayments,
};