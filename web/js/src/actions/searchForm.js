import {
    ARTIST_ADD,
    ARTIST_LOADING,
} from '../constants/Artist'
import {
    SEARCH_FORM_ERRORS,
    SEARCH_FORM_RENDER,
} from '../constants/SearchForm'
import {BASE_URL} from '../router/baseUrl'

export function handleSearch(payload) {
    return (dispatch) => {
        dispatch({type: ARTIST_LOADING});

        $.ajax({
            method: 'GET',
            url: BASE_URL + '/api/artists/' + payload,
            headers: {
                'X-Accept-Version': 'v1'
            }
        }).done(function(response) {
            dispatch({
                type: ARTIST_ADD,
                payload: {
                    data: response,
                }
            });
            dispatch({type: SEARCH_FORM_RENDER});
        }).fail(function(jqXHR) {
            const response = jqXHR.responseJSON;
            let errorsPayload = {};

            dispatch({
                type: ARTIST_LOADING,
                payload: {
                    loading: false
                }
            });

            if (response.error && response.error.code) {
                errorsPayload.globalError = `Artist "${payload}" is not found.`;
            }
            if (response.form && response.form.children) {
                errorsPayload.formErrors = response.form.children;
            }
            dispatch({
                type: SEARCH_FORM_ERRORS,
                payload: {
                    errors: errorsPayload,
                }
            });
        });
    }
}

