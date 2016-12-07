import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {connect} from 'react-redux'

import Artist from '../components/Artist'
import * as artistActions from '../actions/artist'

class MusicDiffApp extends Component {
    static propTypes = {
        artist: React.PropTypes.object.isRequired,
        artistActions: React.PropTypes.object.isRequired
    };

    render() {
        const {artist} = this.props;

        return <div>
            <Artist
                artist={artist.artist}
                actions={this.props.artistActions}
                error={artist.error}
                loading={artist.loading}
            />
            {/*<User name={artist.name} />*/}
        </div>
    }
}

function mapStateToProps(state) {
    return {
        artist: state.artist,
    }
}

function mapDispatchToProps(dispatch) {
    return {
        artistActions: bindActionCreators(artistActions, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(MusicDiffApp)
