import {
    LOGIN_REQUEST,
    LOGIN_SUCCESS,
    LOGIN_FAIL,
    LOGOUT_SUCCESS
} from '../constants/User'

const initialState = {
    name: JSON.parse(window.localStorage.getItem('rr_user')) || '',
    error: '',
    isAuthenticated: false
};


export default function user(state = initialState, action) {
    switch(action.type) {
        case LOGIN_REQUEST:
            return {};
        case LOGIN_SUCCESS:
            return {...state, name: action.payload.name, isAuthenticated: action.payload.isAuthenticated};
        case LOGIN_FAIL:
            return { ...state, error: action.payload.message };
        case LOGOUT_SUCCESS:
            return {...state};
        default:
            return state
    }
}
