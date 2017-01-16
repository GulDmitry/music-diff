import {
    GLOBAL_ALERT_ALERT,
    GLOBAL_ALERT_CLEAR,
} from '../constants/GlobalAlert'

const initialState = {};

export default function globalAlert(state = initialState, action) {
    switch (action.type) {
        case GLOBAL_ALERT_ALERT: {
            return {error: action.payload.error};
        }
        case GLOBAL_ALERT_CLEAR:
            return {...state, error: null};
        default:
            return state
    }
}
