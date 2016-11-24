import {
    LOGIN_REQUEST,
    LOGIN_SUCCES,
    // LOGIN_FAIL
} from '../constants/User'

export function handleLogin() {
    return function(dispatch) {
        dispatch({
            type: LOGIN_REQUEST
        });

        // Emulate an ajax call.
        setTimeout(() => {
            dispatch({
                type: LOGIN_SUCCES,
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
