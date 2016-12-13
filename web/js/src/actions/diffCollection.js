import {
    ARTIST_REPLACE,
} from '../constants/Artist'
import {
    GLOBAL_ALERT_ALERT,
    GLOBAL_ALERT_CLEAR,
} from '../constants/GlobalAlert'
import {BASE_URL} from '../router/baseUrl'

export function generateDiff() {
    return (dispatch, getStore) => {
        const store = getStore();
        const artistCollection = store.artist.artist;

        if (!artistCollection.length) {
            dispatch({
                type: GLOBAL_ALERT_ALERT,
                payload: {
                    error: 'Collection is empty.',
                }
            });
            return;
        }
        // TODO: how to call this on every action.
        dispatch({type: GLOBAL_ALERT_CLEAR});

        const ajaxPromise = Promise.resolve($.ajax({
            method: 'POST',
            url: BASE_URL + '/api/differences',
            data: {collection: JSON.stringify(artistCollection)},
            // To avoid jquery bug where empty arrays are cut.
            dataType: 'text',
            headers: {
                'X-Accept-Version': 'v1'
            }
        }));
        ajaxPromise.then((response) => {
            dispatch({
                type: ARTIST_REPLACE,
                payload: {
                    data: JSON.parse(response),
                }
            });
        }, (jqXHR) => {
            const response = jqXHR.responseJSON;

            if (response.error && response.error.code) {
                dispatch({
                    type: GLOBAL_ALERT_ALERT,
                    payload: {
                        error: 'Cannot create diff.',
                    }
                });
            }
        });
    }
}
