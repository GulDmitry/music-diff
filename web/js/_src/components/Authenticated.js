import React, {PropTypes} from 'react'
import {connect} from 'react-redux'
import {REDIRECT} from '../constants/Router'

export default function requireAuthentication(Component) {
    class AuthenticatedComponent extends React.Component {
        static propTypes = {
            user: PropTypes.object.isRequired,
            dispatch: PropTypes.func.isRequired,
        };

        componentWillMount() {
            this.checkAuth(this.props.user)
        }

        componentWillReceiveProps(nextProps) {
            this.checkAuth(nextProps.user)
        }

        checkAuth(user) {
            if (!user.isAuthenticated) {
                // Because of connect().
                this.props.dispatch({
                    type: REDIRECT,
                    payload: {
                        method: 'replace', // push - add the page to history, replace - not.
                        nextUrl: '/login-temp'
                    }
                })
            }
        }

        render() {
            return (
                <div>
                    {this.props.user.isAuthenticated === true
                        ? <Component {...this.props} />
                        : null
                    }
                </div>
            )
        }
    }
    function mapStateToProps(state) {
        return {
            user: state.user
        }
    }

    return connect(mapStateToProps)(AuthenticatedComponent)
}
