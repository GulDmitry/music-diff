import {
    GLOBAL_ALERT_CLEAR,
    GLOBAL_ALERT_ALERT,
} from '../constants/GlobalAlert'

export function clear() {
    return (dispatch) => {
        dispatch({type: GLOBAL_ALERT_CLEAR});
    }
}

export function showAlert(error) {
    return (dispatch) => {
        dispatch({
            type: GLOBAL_ALERT_ALERT,
            payload: {
                error: error,
            }
        });
    }
}
