const setThemeLoading = (dispatch, status) => {
    dispatch({
        type: "SET_LOADING",
        payload: status
    });
}

const setThemeItemMenu = (dispatch, state) => {
    dispatch({
        type: "SET_ITEM_MENU",
        payload: state
    });
}

export { setThemeLoading, setThemeItemMenu };