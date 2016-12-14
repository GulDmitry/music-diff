import React, {PropTypes, Component} from 'react'
import Row from './Row'

export default class Artist extends Component {
    static propTypes = {
        artist: PropTypes.array.isRequired,
        loading: PropTypes.bool,
        actions: PropTypes.object.isRequired,
        filter: PropTypes.object.isRequired,
        diff: PropTypes.array,
    };

    render() {
        const {artist, loading} = this.props;
        const data = artist || [];
        const typeFilter = this.props.filter && this.props.filter.type || '';
        let loadingTpl, rowsTpl;

        if (loading) {
            loadingTpl = <p className='text-center'><i className='fa fa-cog fa-spin fa-3x fa-fw'/></p>
        }

        if (data.length) {
            const rows = data.map((item, index) => {
                return <Row
                    artist={item}
                    typeFilter={typeFilter}
                    key={'artist_row_' + index}
                    diff={
                        this.props.diff ?
                            this.props.diff.find(x => x.name.toLowerCase() === item.name.toLowerCase()) :
                            null
                    }
                />
            });
            rowsTpl = <div className='row'>{rows}</div>;
        } else {
            rowsTpl = <p className='text-center'>Add your first artist.</p>;
        }

        return <div className='col-md-9'>
            {loadingTpl}
            <br/>
            {rowsTpl}
        </div>
    }
}
