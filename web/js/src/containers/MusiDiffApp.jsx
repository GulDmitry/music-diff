import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {connect} from 'react-redux'

import Artist from '../components/Artist'
import SearchForm from '../components/SearchForm'
import * as artistActions from '../actions/artist'
import * as searchFormActions from '../actions/searchForm'

class MusicDiffApp extends Component {
    static propTypes = {
        artist: React.PropTypes.object.isRequired,
        artistActions: React.PropTypes.object.isRequired,
        searchForm: React.PropTypes.object.isRequired,
        searchFormActions: React.PropTypes.object.isRequired,
    };

    render() {
        const {artist, searchForm} = this.props;

        return <object>
            <SearchForm
                actions={this.props.searchFormActions}
                errors={searchForm.errors}
            />
            <Artist
                actions={this.props.artistActions}
                artist={artist.artist}
                loading={artist.loading}
            />
        </object>
    }
}

function mapStateToProps(state) {
    return {
        artist: state.artist,
        searchForm: state.searchForm,
    }
}

function mapDispatchToProps(dispatch) {
    return {
        artistActions: bindActionCreators(artistActions, dispatch),
        searchFormActions: bindActionCreators(searchFormActions, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(MusicDiffApp)
