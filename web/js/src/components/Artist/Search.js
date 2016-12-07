import React, {PropTypes, Component} from 'react'

export default class Search extends Component {
    static propTypes = {
        handleSearch: PropTypes.func.isRequired
    };

    handleSubmit(e) {
        e.preventDefault();
        this.props.handleSearch(this.refs['artist'].value);

        this.refs['artist'].value = '';
    }

    render() {
        return <div>
            <h2>Artist</h2>
            <form className='form-inline' onSubmit={::this.handleSubmit}>
                <div className='form-group'>
                    <input ref='artist' type='text' className='form-control' id='Artist' placeholder='Artist'/>
                </div>
                {' '}
                <button type='submit' className='btn btn-primary'>Add &raquo;</button>
            </form>
        </div>
    }
}
