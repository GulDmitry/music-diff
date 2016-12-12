import React, {PropTypes, Component} from 'react'
import SearchFormErrorDecorator from './SearchFormErrorDecorator'

export default class SearchForm extends Component {
    static propTypes = {
        actions: PropTypes.shape({
            handleSearch: PropTypes.func.isRequired,
        }),
        errors: PropTypes.object,
    };

    handleSubmit(e) {
        e.preventDefault();
        this.props.actions.handleSearch(this.refs['artist'].value);

        this.refs['artist'].value = '';
    }

    render() {
        let formErrors = this.props.errors;

        return <object>
            <form onSubmit={::this.handleSubmit}>
                <div className={'form-group row ' + (formErrors ? 'has-error' : '')}>
                    <div className='col-lg-3'>
                        <label className='control-label' htmlFor='artist-name'>Artist</label>
                        <input ref='artist'
                               id='artist-name'
                               type='text'
                               className='form-control'
                               placeholder='Artist Name'
                        />
                    </div>
                </div>
                <SearchFormErrorDecorator field='artist-name' errors={formErrors}/>
                <button type='submit' className='btn btn-primary'>Add &raquo;</button>
            </form>
        </object>
    }
}
