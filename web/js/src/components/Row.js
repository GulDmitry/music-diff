import React, {PropTypes, Component} from 'react'

export default class Row extends Component {
    static propTypes = {
        artist: PropTypes.object.isRequired
    };

    render() {
        const artist = this.props.artist;

        return <div>
            <h3>{artist.name}</h3>
        </div>
    }
}
