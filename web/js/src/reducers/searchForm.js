import {
    SEARCH_FORM_RENDER,
    SEARCH_FORM_ERRORS,
} from '../constants/SearchForm'

const initialState = {};

export default function artist(state = initialState, action) {
    switch (action.type) {
        case SEARCH_FORM_RENDER:
            return {};
        case SEARCH_FORM_ERRORS:
            return {...state, errors: {
                formErrors: action.payload.errors.formErrors,
                globalError: action.payload.errors.globalError
            }};
        default:
            return state
    }
}
