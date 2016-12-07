import {
    ARTIST_REQUEST,
    ARTIST_FOUND,
    ARTIST_NOT_FOUND,
} from '../constants/Artist'
import {BASE_URL} from '../router/baseUrl'

export function handleSearch(payload) {
    return (dispatch) => {
        dispatch({
            type: ARTIST_REQUEST,
            payload: {
                loading: true
            }
        });

        $.ajax({
            method: 'GET',
            url: BASE_URL + '/api/artists/' + payload,
            headers: {
                'X-Accept-Version': 'v1'
            }
        }).done(function(artist) {
            dispatch({
                type: ARTIST_FOUND,
                payload: {
                    artist: artist,
                }
            });
        }).fail(function() {
            dispatch({
                type: ARTIST_NOT_FOUND,
                payload: {
                    error: `Artist "${payload}" is not found.`,
                }
            });
        });
    }
}
