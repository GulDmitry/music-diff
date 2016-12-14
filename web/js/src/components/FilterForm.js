import React, {PropTypes, Component} from 'react'

export default class FilterForm extends Component {
    static propTypes = {
        actions: PropTypes.object.isRequired,
        filter: PropTypes.shape({
            albums: PropTypes.object.isRequired,
            diff: PropTypes.object.isRequired,
        })
    };

    albumCheckbox(e) {
        let filter = this.props.filter.albums;
        filter[e.target.value].checked = e.target.checked;
        filter = Object.assign(filter, this.props.filter.diff);

        this.props.actions.applyFilter(filter);
    }

    diffCheckbox(e) {
        let filter = this.props.filter.diff;
        filter[e.target.value].checked = e.target.checked;
        filter = Object.assign(filter, this.props.filter.albums);

        this.props.actions.applyFilter(filter);
    }

    render() {
        // TODO. Need some refactoring here.
        const albumsFilterTpl = Object.keys(this.props.filter.albums).map((key) => {
            const row = this.props.filter.albums[key];
            return <label className='checkbox-inline' key={'album_filter_' + key}>
                <input onChange={::this.albumCheckbox} defaultChecked={row.checked} type='checkbox'
                       value={key}/>{row.label}
            </label>
        });
        const diffFilterTpl = Object.keys(this.props.filter.diff).map((key) => {
            const row = this.props.filter.diff[key];
            return <label className='checkbox-inline' key={'diff_filter_' + key}>
                <input onChange={::this.diffCheckbox} defaultChecked={row.checked} type='checkbox'
                       value={key}/>{row.label}
            </label>
        });

        return <object>
            <form>
                <div className='form-group'>
                    <div>
                        <label className='control-label'>Album Types</label>
                        <div className='checkbox'>
                            {albumsFilterTpl}
                        </div>
                    </div>
                    <div>
                        <label className='control-label'>Difference</label>
                        <div className='checkbox'>
                            {diffFilterTpl}
                        </div>
                    </div>
                </div>
            </form>
        </object>
    }
}
