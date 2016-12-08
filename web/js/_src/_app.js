// ES6 https://habrahabr.ru/post/305900/
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import '../css/font-awesome.css'
import '../css/theme.css'

import 'babel-polyfill'

// ReactApp.jsx
import React from 'react'
import {render} from 'react-dom'
import ReactApp from './src/containers/ReactApp'

// ReduxApp.jsx
import {Provider} from 'react-redux'
import ReduxApp from './src/containers/ReduxApp'
import configureStore from './src/store/configureStore'

// ReactRouterApp.jsx
import {Router} from 'react-router'
import {routes} from './src/router'
import {history} from './src/router/history'

const store = configureStore();

$(document).ready(function() {
    $('#content').html('Webpack and JQuery work!');

    /*
     const tenCharges = [
     0.10, 0.10, 0.10, 0.10, 0.10,
     0.10, 0.10, 0.10, 0.10, 0.10,
     ];
     const discountThreshold = 1.00;
     const totalCharge = tenCharges.reduce((acc, each) => acc += each);
     assert.ok(totalCharge < discountThreshold);   // NOT what I want
     */

    let news = [
        {
            author: 'Author 1',
            text: 'Text 1',
            showMore: 'Show more text 1'
        },
        {
            author: 'Author 2',
            text: 'Text 2',
            showMore: 'Show more text 2'
        },
        {
            author: 'Author 3',
            text: 'Text 3',
            showMore: 'Show more text 3'
        }
    ];

    render(
        <Provider store={store}>
            <Router history={history} routes={routes}/>
        </Provider>,
        document.getElementById('react-router-content')
    );

    render(
        <Provider store={store}>
            <ReduxApp />
        </Provider>,
        document.getElementById('redux-content')
    );

    render(<ReactApp news={news}/>, document.getElementById('react-content'));
});
