import {
    ARTIST_FILTER,
} from '../constants/Artist'

export function applyFilter(filterState) {
    return (dispatch) => {
        const filterStr = Object.keys(filterState).filter((value) => {
            return filterState[value].checked;
        }).join(',');

        dispatch({
            type: ARTIST_FILTER,
            payload: {
                filter: {type: filterStr}
            }
        });
    }
}

