import React, {PropTypes, Component} from 'react'
import Row from './Row'

export default class Artist extends Component {
    static propTypes = {
        artist: PropTypes.array.isRequired,
        loading: PropTypes.bool,
        actions: PropTypes.object.isRequired,
    };

    render() {
        const {artist, loading} = this.props;
        const data = artist || [];
        let loadingTpl, rowsTpl;

        if (loading) {
            loadingTpl = <p className='text-center'><i className='fa fa-cog fa-spin fa-3x fa-fw'/></p>
        }

        if (data.length) {
            // Better not to show environment if no data available.
            rowsTpl = data.map(function(item, index) {
                return <div className='row' key={'artist_row_' + index}>
                    <Row artist={item}/>
                </div>
            })
        } else {
            rowsTpl = <p className='text-center'>Add your first artist.</p>;
        }

        return <div>
            {loadingTpl}
            {rowsTpl}
        </div>
    }
}
