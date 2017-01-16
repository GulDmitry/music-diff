import {
    SEARCH_FORM_RENDER,
    SEARCH_FORM_ERRORS,
} from '../constants/SearchForm'

const initialState = {};

export default function searchForm(state = initialState, action) {
    switch (action.type) {
        case SEARCH_FORM_RENDER:
            return {};
        case SEARCH_FORM_ERRORS:
            return {...state, errors: action.payload.errors};
        default:
            return state
    }
}
