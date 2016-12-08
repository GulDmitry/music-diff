import {
    ARTIST_ADD,
    ARTIST_LOADING,
} from '../constants/Artist'

const initialState = {
    artist: [],
};

export default function artist(state = initialState, action) {
    switch (action.type) {
        case ARTIST_ADD: {
            return {artist: state.artist.concat(action.payload.data)};
        }
        case ARTIST_LOADING: {
            let flag;
            try {
                flag = action.payload.loading;
            } catch (e) {
                flag = true;
            }
            return {...state, loading: flag};
        }
        default:
            return state
    }
}
