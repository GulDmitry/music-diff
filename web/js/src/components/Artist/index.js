import React, {PropTypes, Component} from 'react'
import Row from './Row'
import Search from './Search'

export default class Artist extends Component {
    static propTypes = {
        artist: PropTypes.array,
        error: PropTypes.string,
        loading: PropTypes.bool,
        actions: PropTypes.object.isRequired,
    };

    render() {
        const {handleSearch} = this.props.actions;
        const data = this.props.artist || [];
        let error, template;

        if (this.props.error) {
            error = <div className='alert alert-danger'>{this.props.error}</div>
        }
        if (this.props.loading) {
            template = <p className='text-center'><i className='fa fa-cog fa-spin fa-3x fa-fw'/></p>
        }

        if (data.length) {
            // Better not to show environment if no data available.
            template = data.map(function(item, index) {
                return <div className='row' key={'artist-row-' + index}>
                    <Row artist={item}/>
                </div>
            })
        } else {
            template = <p className='text-center'>Add your first artist.</p>;
        }

        return <div>
            {error}
            <Search handleSearch={handleSearch}/>
            <br/>
            <br/>
            {template}
        </div>
    }
}
