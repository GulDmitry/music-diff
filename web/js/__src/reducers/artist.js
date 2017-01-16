import {
    ARTIST_ADD,
    ARTIST_LOADING,
    ARTIST_FILTER,
    ARTIST_REPLACE,
    ARTIST_MERGE,
} from '../constants/Artist'

const initialState = {
    artist: [],
    filter: {
        type: ''
    },
};

export default function artist(state = initialState, action) {
    switch (action.type) {
        case ARTIST_ADD:
            return {artist: action.payload.data.concat(state.artist), filter: action.payload.filter || state.filter};
        case ARTIST_REPLACE:
            return {artist: action.payload.data, filter: action.payload.filter || state.filter};
        case ARTIST_MERGE:
            return {...state, diff: action.payload.data};
        case ARTIST_LOADING: {
            let flag;
            try {
                flag = action.payload.loading;
            } catch (e) {
                flag = true;
            }
            return {...state, loading: flag};
        }
        case ARTIST_FILTER: {
            return {...state, filter: action.payload.filter || state.filter};
        }
        default:
            return state
    }
}
