import React, {PropTypes, Component} from 'react'

export default class User extends Component {
    static propTypes = {
        name: PropTypes.string.isRequired,
        handleLogin: PropTypes.func.isRequired,
        error: PropTypes.string
    };

    render() {
        const {name, error} = this.props;
        let template = name ?
            <p>Hello, {name}!</p> :
            <button className='btn' onClick={this.props.handleLogin}>Log in</button>;

        return <div>
            {template}
            {error ? <p className='error'> {error}. <br /> Error occurs.</p> : ''}
        </div>
    }
}
