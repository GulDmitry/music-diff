// ES6 https://habrahabr.ru/post/305900/
import "bootstrap";
import "bootstrap/dist/css/bootstrap.css";
import "../css/font-awesome.css";
import "../css/theme.css";

import React from "react";
import ReactDOM from "react-dom";
import App from "./app.jsx";

$(document).ready(function() {
    $('#content').html('Webpack and JQuery work!');

    var news = [
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

    ReactDOM.render(<App news={news}/>, document.getElementById('react-content'));
});
