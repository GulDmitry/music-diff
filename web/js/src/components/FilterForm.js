import React, {PropTypes, Component} from 'react'

export default class FilterForm extends Component {
    static propTypes = {
        actions: PropTypes.object.isRequired,
        filter: PropTypes.shape({
            albums: PropTypes.object.isRequired,
        })
    };

    albumCheckbox(e) {
        const filter = this.props.filter.albums;
        filter[e.target.value].checked = e.target.checked;

        this.props.actions.applyFilter(filter);
    }

    render() {
        let albumsFilterTpl = Object.keys(this.props.filter.albums).map((key) => {
            const row = this.props.filter.albums[key];
            return <label className='checkbox-inline' key={'album_filter_' + key}>
                <input onChange={::this.albumCheckbox} defaultChecked={row.checked} type='checkbox'
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
                </div>
            </form>
        </object>
    }
}
