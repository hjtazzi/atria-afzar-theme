import { getTheme } from "./API";

const getTermItems = (taxonomy, term_id, callback) => {
    if (taxonomy && term_id) {
        getTheme().get(`/taxonomy/${taxonomy}/term/${term_id}`)
            .then(res => callback(true, res.data))
            .catch(err => callback(false, err));
    } else {
        return callback(false, "null");
    }
}

export { getTermItems };