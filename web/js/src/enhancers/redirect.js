import {
    REDIRECT
} from '../constants/Router'
import {history} from '../router/history'

const redirect = () => next => action => {
    if (action.type === REDIRECT) {
        history[action.payload.method](action.payload.nextUrl)
    }
    return next(action)
};

export default redirect;
