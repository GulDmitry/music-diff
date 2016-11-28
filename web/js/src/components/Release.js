import React, {PropTypes, Component} from 'react'

export default class Release extends Component {
    static propTypes = {
        params: PropTypes.object.isRequired,
    };

    render() {
        const releaseName = this.props.params.release.replace(/-/g, ' ');
        return (
            <div className='col-md-12'>
                {releaseName}
            </div>
        )
    }
}
