import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {Link} from 'react-router'

export default class ReactRouterApp extends Component {
    // constructor(props) {
    //     super(props);
    //     this.state = {
    //         route: window.location.hash.substr(1)
    //     }
    // }
    //
    // static propTypes = {
    //     // user: React.PropTypes.object.isRequired,
    //     // page: React.PropTypes.object.isRequired,
    //     // pageActions: React.PropTypes.object.isRequired,
    //     // userActions: React.PropTypes.object.isRequired
    // };
    //
    // componentDidMount() {
    //     window.addEventListener('hashchange', () => {
    //         this.setState({
    //             route: window.location.hash.substr(1)
    //         })
    //     })
    // }

    render() {
        return (
            <div className='container'>
                <ul className='nav nav-pills'>
                    <li><Link activeClassName='active' className='btn btn-lg' onlyActiveOnIndex={true} to='/'>
                        Main
                    </Link></li>
                    <li><Link activeClassName='active' className='btn btn-lg' to='/login-temp'>Login</Link></li>
                    {/* Can be wrapped and use {...this.props} in order to pass all data to the new component
                     <NavLink className="btn"> -> <Link {...this.props} activeClassName="" />*/}
                    <li><Link activeClassName='active' className='btn btn-lg' to='/list'>List of Genres</Link></li>
                </ul>
                {this.props.children}
            </div>
        )
    }
}

// function mapStateToProps(state) {
//     return {
//         // user: state.user,
//         // page: state.page
//     }
// }
//
// function mapDispatchToProps(dispatch) {
//     return {
//         // pageActions: bindActionCreators(pageActions, dispatch),
//         // userActions: bindActionCreators(userActions, dispatch)
//     }
// }
//
// export default connect(mapStateToProps, mapDispatchToProps)(ReactRouterApp)
