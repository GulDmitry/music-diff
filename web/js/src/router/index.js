import React from 'react'
import {Route, IndexRoute} from 'react-router'
import ReactRouterApp from '../containers/ReactRouterApp'
import Admin from '../components/Admin'
import List from '../components/List'
import Genre from '../components/Genre'
import Release from '../components/Release'
import Home from '../components/Home'
// import Login from '../components/Login'
import Login from '../containers/LoginPage'
import NotFound from '../components/NotFound'

import requireAuthentication from '../components/Authenticated';

export const routes = (
    <div>
        <Route path='/' component={ReactRouterApp}>
            <IndexRoute component={Home}/>
            <Route path='login-temp' component={Login}/>
            {/*<IndexRedirect to='list' />*/}
            {/*<Redirect from='/list' to='/genre' />*/}
            <Route path='admin' component={requireAuthentication(Admin)} onEnter={Admin.onEnter}/>
            <Route path='/genre/:genre' component={Genre}>
                <Route path=':release' component={Release}/>
            </Route>
            <Route path='list' component={List}/>
        </Route>
        <Route path='*' component={NotFound}/>

        {/*<Route path="/hello(/:name)"> // Optional - /hello, /hello/michael, and /hello/ryan */}
        {/*<Route path="/files/*.*"> // Match /files/hello.jpg and /files/hello.html */}
        {/*<Route path="/**//*.jpg"> // Match /files/hello.jpg and /files/path/to/file.jpg */}
    </div>
);
