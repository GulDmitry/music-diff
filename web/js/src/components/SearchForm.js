import React, {PropTypes, Component} from 'react'
import SearchFormErrorDecorator from './SearchFormErrorDecorator'

export default class SearchForm extends Component {
    static propTypes = {
        actions: PropTypes.shape({
            handleSearch: PropTypes.func.isRequired,
        }),
        errors: PropTypes.shape({
            formErrors: PropTypes.object,
            globalError: PropTypes.string,
        }),
    };

    handleSubmit(e) {
        e.preventDefault();
        this.props.actions.handleSearch(this.refs['artist'].value);

        this.refs['artist'].value = '';
    }

    render() {
        let globalErrorTpl, formErrors;

        if (this.props.errors && this.props.errors.globalError) {
            globalErrorTpl = <div className='alert alert-danger'>{this.props.errors.globalError}</div>
        }
        if (this.props.errors && this.props.errors.formErrors) {
            formErrors = this.props.errors.formErrors;
        }

        return <object>
            {globalErrorTpl}
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
