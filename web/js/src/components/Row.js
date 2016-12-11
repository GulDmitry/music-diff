import React, {PropTypes, Component} from 'react'
import {Table, Tr, Td} from 'reactable'

export default class Row extends Component {
    static propTypes = {
        artist: PropTypes.shape({
            albums: PropTypes.array.isRequired,
        }).isRequired,
        typeFilter: PropTypes.string.isRequired,
    };

    toggleVisibility(e) {
        const refTokenList = this.refs['table-wrapper'].classList;
        if (refTokenList.contains('hide')) {
            refTokenList.remove('hide');
            e.target.innerText = 'Hide';
        } else {
            refTokenList.add('hide');
            e.target.innerText = 'Show';
        }
    }

    render() {
        const artist = this.props.artist;
        let template;

        template = artist.albums.map(function(album, index) {
            return <Tr key={'album_row_' + index}>
                <Td column='Name' data={album.name}>
                    <b>{album.name}</b>
                </Td>
                <Td column='Release Date' data={album.releaseDate}>{album.releaseDate}</Td>
                <Td column='Type' data={album.types}>{album.types}</Td>
            </Tr>
        });

        return <div>
            <div ref='test' className='row'>
                <div className='col-md-8'><h3>{artist.name}</h3></div>
                <h3>
                    <button onClick={::this.toggleVisibility} type='button' className='btn btn-default btn-sm'>
                        Hide
                    </button>
                </h3>
            </div>
            <div ref='table-wrapper'>
                <Table
                    ref='row-table'
                    className='table table-hover'
                    sortable={true}
                    defaultSort={{column: 'Release Date', direction: 'desc'}}
                    noDataText='No matching albums found.'
                    filterable={[
                        {
                            column: 'Type',
                            filterFunction: function(contents, filter) {
                                const data = contents.split(',');
                                const result = data.filter(function(n) {
                                    return filter.split(',').indexOf(n) > -1;
                                });

                                return result.length;
                            }
                        }
                    ]}
                    filterBy={this.props.typeFilter}
                    hideFilterInput
                >
                    {template}
                </Table>
            </div>
        </div>
    }
}
