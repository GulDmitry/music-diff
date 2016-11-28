import React, {PropTypes, Component} from 'react'


export default class Genre extends Component {
    static propTypes = {
        params: PropTypes.object.isRequired,
        children: PropTypes.object,
    };

    render() {
        return (
            <div className='row'>
                <h3 className='col-md-12'>{this.props.params.genre}</h3>
                <div className='col-md-12'>Releases</div>
                {/* Show children elements */}
                {this.props.children}
            </div>
        )
    }
}
