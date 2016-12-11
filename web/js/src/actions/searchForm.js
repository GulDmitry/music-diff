/* eslint-disable */
import {
    ARTIST_ADD,
    ARTIST_LOADING,
} from '../constants/Artist'
import {
    SEARCH_FORM_ERRORS,
    SEARCH_FORM_RENDER,
} from '../constants/SearchForm'
import {BASE_URL} from '../router/baseUrl'
import {applyFilter} from '../actions/filterForm'

export function handleSearch(payload) {
    return (dispatch, state) => {
        dispatch({type: ARTIST_LOADING});

        const ajaxPromise = Promise.resolve($.ajax({
            method: 'GET',
            url: BASE_URL + '/api/artists/' + payload,
            headers: {
                'X-Accept-Version': 'v1'
            }
        }));
        ajaxPromise.then((response) => {
            dispatch({
                type: ARTIST_ADD,
                payload: {
                    data: response,
                }
            });
        }, (jqXHR) => {
            const response = jqXHR.responseJSON;
            let errorsPayload = {};

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
            // in case of chain like then({}).then{} the `return new Promise(() => reject());` is required
        });
        ajaxPromise.then(() => {
            dispatch({type: SEARCH_FORM_RENDER});

            // Update Artist according to the filter form.
            applyFilter(state().filterForm.filter.albums)(dispatch);
        }, () => {
            dispatch({
                type: ARTIST_LOADING,
                payload: {
                    loading: false,
                }
            });
        });
    }
}

