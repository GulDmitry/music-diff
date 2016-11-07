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

    ReactDOM.render(<App />, document.getElementById('react-content'));
});
