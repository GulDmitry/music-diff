import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import '../css/font-awesome.css'
import '../css/theme.css'
import 'babel-polyfill'

import React from 'react'
import {render} from 'react-dom'
import {Provider} from 'react-redux'

import MusicDiffApp from './src/containers/MusiDiffApp'
import configureStore from './src/store/configureStore'

const store = configureStore();

$(document).ready(function() {
    render(
        <Provider store={store}>
            <MusicDiffApp />
        </Provider>,
        document.getElementById('music-diff')
    );
});
