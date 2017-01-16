import React, {PropTypes, Component} from 'react'
import {Table, Tr, Td} from 'reactable'

export default class Row extends Component {
    static propTypes = {
        artist: PropTypes.shape({
            albums: PropTypes.array.isRequired,
        }).isRequired,
        typeFilter: PropTypes.string.isRequired,
        diff: PropTypes.object,
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
        const trTpl = function(album, diff = false, className = null) {
            return <Tr className={className} key={'_' + Math.random().toString(36).substr(2, 9)}>
                <Td column='Name' data={album.name}>
                    <b>{album.name}</b>
                </Td>
                <Td column='Release Date' data={album.releaseDate}/>
                <Td column='Type' value={(diff === true ? 'diff,' : '') + album.types}>{album.types}</Td>
            </Tr>
        };
        const rows = artist.albums.map(function(album) {
            return trTpl(album);
        });

        if (this.props.diff && this.props.diff.albums.length) {
            this.props.diff.albums.forEach(function(album) {
                rows.push(trTpl(album, true, 'success'));
            });
        }

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
                                const columnData = contents.split(',');
                                const filterData = filter.split(',');
                                const diffFilterIndex = filterData.indexOf('diff');

                                // TODO. Refactoring.
                                if (diffFilterIndex !== -1) {
                                    if (columnData.indexOf('diff') === -1) {
                                        return false;
                                    }
                                    filterData.splice(diffFilterIndex, 1);
                                }
                                console.log(columnData, filterData);

                                if (!filterData.length || !columnData.length) {
                                    return true;
                                }

                                const result = columnData.filter(function(n) {
                                    return filterData.indexOf(n) > -1;
                                });
                                return result.length;
                            }
                        },
                    ]}
                    filterBy={this.props.typeFilter}
                    hideFilterInput
                >
                    {rows}
                </Table>
            </div>
        </div>
    }
}
