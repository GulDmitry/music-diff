import {
    LOGIN_REQUEST,
    LOGIN_SUCCESS,
    // LOGIN_FAIL,
    LOGOUT_SUCCESS
} from '../constants/User'
import {
    REDIRECT
} from '../constants/Router'

export function login(payload) {
    return (dispatch) => {
        dispatch({
            type: LOGIN_REQUEST
        });
        setTimeout(() => {
            dispatch({
                type: LOGIN_SUCCESS,
                payload: {
                    name: payload.name,
                    isAuthenticated: true
                }
            });

            dispatch({
                type: REDIRECT,
                payload: {
                    method: 'replace', // push - add the page to history, replace - not.
                    nextUrl: '/admin'
                }
            })
        }, 1000)
    }
}

export function logout() {
    return {
        type: LOGOUT_SUCCESS
    }
}

export function handleLogin() {
    return function(dispatch) {
        dispatch({
            type: LOGIN_REQUEST
        });

        // Emulate an ajax call.
        setTimeout(() => {
            dispatch({
                type: LOGIN_SUCCESS,
                payload: 'Username'
            });
            // dispatch({
            //     type: LOGIN_FAIL,
            //     error: true,
            //     payload: new Error('Authorization error')
            // })
        }, 1000);
    }
}
