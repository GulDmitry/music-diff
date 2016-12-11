import {
    FILTER_FORM_FILTER_APPLY,
} from '../constants/FilterForm'

const initialState = {
    filter: {
        albums: {
            album: {label: 'Album', checked: false},
            ep: {label: 'EP', checked: false},
            single: {label: 'Single', checked: false},
        }
    }
};

export default function filterForm(state = initialState, action) {
    switch (action.type) {
        case FILTER_FORM_FILTER_APPLY:
            return {...state};
        default:
            return state
    }
}
