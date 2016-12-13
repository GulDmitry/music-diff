import React, {PropTypes, Component} from 'react'

export default class ImportExport extends Component {
    static propTypes = {
        actions: PropTypes.shape({
            importCollection: PropTypes.func.isRequired,
            exportCollection: PropTypes.func.isRequired,
        }),
    };

    readFile(e) {
        const file = e.target.files[0];
        if (!file) {
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            const contents = e.target.result;
            this.props.actions.importCollection(JSON.parse(contents));
        };
        reader.readAsText(file);
    }

    render() {
        return <object>
            <button type='button' className='btn btn-default btn-block' onClick={this.props.actions.exportCollection}>
                <i className='fa fa-upload fa-lg' aria-hidden='true'> Export</i>
            </button>
            <label type='button' className='btn btn-default btn-block'>
                <i className='fa fa-download fa-lg' aria-hidden='true'>
                    {' '}Import <input onChange={::this.readFile} type='file' className='hide'/>
                </i>
            </label>
        </object>
    }
}
