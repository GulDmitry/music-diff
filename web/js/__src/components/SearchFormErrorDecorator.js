import React, {PropTypes, Component} from 'react'

export default class SearchFormErrorDecorator extends Component {
    static propTypes = {
        field: PropTypes.string.isRequired,
        errors: PropTypes.object,
    };

    render() {
        let template;

        try {
            const fieldName = this.props.field;
            const fieldErrors = this.props.errors[fieldName].errors;
            const helpBlocks = [];

            fieldErrors.map(function(item, index) {
                helpBlocks.push(<span key={`validation_error_${fieldName}_${index}`} className='help-block has-error'>
                        {item}
                    </span>);
            });

            template = <div className='form-group row has-error'>
                <div className='col-lg-12'>{helpBlocks}</div>
            </div>;
        } catch (e) {
            template = '';
        }

        return <div>{template}</div>
    }
}
