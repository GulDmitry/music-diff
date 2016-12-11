import React, {Component} from 'react'
import {bindActionCreators} from 'redux'
import {connect} from 'react-redux'

import FilterFormComponent from '../components/FilterForm'
import * as filterFormActions from '../actions/filterForm'

class Sidebar extends Component {
    static propTypes = {
        filterForm: React.PropTypes.object.isRequired,
        filterFormActions: React.PropTypes.object.isRequired,
    };

    render() {
        const {filterForm} = this.props;

        return <div className='row col-md-3'>
            <h3>Filter</h3>
            <FilterFormComponent actions={this.props.filterFormActions} filter={filterForm.filter}/>
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
        filterFormActions: bindActionCreators(filterFormActions, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Sidebar)
