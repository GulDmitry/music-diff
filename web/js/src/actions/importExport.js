import {
    ARTIST_ADD,
} from '../constants/Artist'
import {showAlert, clear} from '../actions/globalAlert'

export function importCollection() {
    return (dispatch, getStore) => {
        const store = getStore();
        const downloadData = store.artist.artist;

        if (!downloadData.length) {
            showAlert('Collection is empty.')(dispatch);
            return;
        }
        clear()(dispatch);

        let element = document.createElement('a');
        element.setAttribute('href', 'data:application/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(downloadData)));
        element.setAttribute('download', 'music-diff-collection.json');

        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
}

export function exportCollection(collection) {
    return (dispatch) => {
        dispatch({
            type: ARTIST_ADD,
            payload: {
                data: collection,
            }
        });
    }
}
