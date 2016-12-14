import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {connect} from 'react-redux'

import Artist from '../components/Artist'
import SearchForm from '../components/SearchForm'
import GlobalAlert from '../components/GlobalAlert'
import * as globalAlertActions from '../actions/globalAlert'
import * as searchFormActions from '../actions/searchForm'

class MusicDiff extends Component {
    static propTypes = {
        artist: React.PropTypes.object.isRequired,
        artistActions: React.PropTypes.object.isRequired,
        searchForm: React.PropTypes.object.isRequired,
        searchFormActions: React.PropTypes.object.isRequired,
        globalAlert: React.PropTypes.object.isRequired,
        globalAlertActions: React.PropTypes.object.isRequired,
    };

    render() {
        const {artist, searchForm, globalAlert} = this.props;

        return <object>
            <div className='row'>
                <div className='col-md-9'>
                    <GlobalAlert error={globalAlert.error} actions={this.props.globalAlertActions}/>
                    <SearchForm
                        actions={this.props.searchFormActions}
                        errors={searchForm.errors}
                    />
                </div>
            </div>
            <Artist
                actions={this.props.artistActions}
                artist={artist.artist}
                filter={artist.filter}
                loading={artist.loading}
                diff={artist.diff}
            />
        </object>
    }
}

function mapStateToProps(state) {
    return {
        artist: state.artist,
        searchForm: state.searchForm,
        globalAlert: state.globalAlert,
    }
}

function mapDispatchToProps(dispatch) {
    return {
        artistActions: bindActionCreators({}, dispatch),
        searchFormActions: bindActionCreators(searchFormActions, dispatch),
        globalAlertActions: bindActionCreators(globalAlertActions, dispatch),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(MusicDiff)
