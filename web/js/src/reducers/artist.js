import {
    ARTIST_REQUEST,
    ARTIST_FOUND,
    ARTIST_NOT_FOUND,
} from '../constants/Artist'

const initialState = {};

export default function artist(state = initialState, action) {
    switch (action.type) {
        case ARTIST_REQUEST:
            return {...state, loading: true};
        case ARTIST_NOT_FOUND:
            return {error: action.payload.error};
        case ARTIST_FOUND:
            return {artist: action.payload.artist};
        default:
            return state
    }
}
