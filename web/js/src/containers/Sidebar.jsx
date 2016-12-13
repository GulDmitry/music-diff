import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {connect} from 'react-redux'

import FilterForm from '../components/FilterForm'
import ImportExport from '../components/ImportExport'
import * as filterFormActions from '../actions/filterForm'
import * as importExportActions from '../actions/importExport'
import * as generateDiffActions from '../actions/diffCollection'

class Sidebar extends Component {
    static propTypes = {
        filterForm: React.PropTypes.object.isRequired,
        filterFormActions: React.PropTypes.object.isRequired,
        importExportActions: React.PropTypes.object.isRequired,
        generateDiffActions: React.PropTypes.object.isRequired,
    };

    render() {
        const {filterForm} = this.props;

        return <div className='row col-md-3'>
            <h3>Import\Export</h3>
            <ImportExport actions={this.props.importExportActions}/>
            <h3>Filter</h3>
            <FilterForm actions={this.props.filterFormActions} filter={filterForm.filter}/>
            <br/>
            <button onClick={this.props.generateDiffActions.generateDiff}
                    type='button'
                    className='btn btn-primary btn-lg center-block'>
                Generate Diff
            </button>
        </div>
    }
}

function mapStateToProps(state) {
    return {
        filterForm: state.filterForm,
    }
}

function mapDispatchToProps(dispatch) {
    return {
        filterFormActions: bindActionCreators(filterFormActions, dispatch),
        importExportActions: bindActionCreators(importExportActions, dispatch),
        generateDiffActions: bindActionCreators(generateDiffActions, dispatch),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar)
