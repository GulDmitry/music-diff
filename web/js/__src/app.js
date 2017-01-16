import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import '../css/font-awesome.css'
import '../css/theme.css'
import 'babel-polyfill'

import React from 'react'
import {render} from 'react-dom'
import {Provider} from 'react-redux'

import MusicDiff from './src/containers/MusicDiff'
import Sidebar from './src/containers/Sidebar'
import musicDiffStore from './src/store/musicDiffStore'

// The same store to affect the component from another container.
const store = musicDiffStore();

window.getAjaxConnections = function () {return $.active};

$(document).ready(function() {
    render(
        <Provider store={store}>
            <MusicDiff />
        </Provider>,
        document.getElementById('music-diff')
    );
    render(
        <Provider store={store}>
            <Sidebar />
        </Provider>,
        document.getElementById('sidebar')
    );
});
