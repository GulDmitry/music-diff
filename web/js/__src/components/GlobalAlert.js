import React, {PropTypes, Component} from 'react'

export default class GlobalAlert extends Component {
    static propTypes = {
        actions: PropTypes.shape({
            clear: PropTypes.func.isRequired,
        }),
        error: PropTypes.string,
    };

    render() {
        let tpl;

        if (this.props.error) {
            tpl = <div className='alert alert-danger alert-dismissable fade in'>
                <a href='#'
                   onClick={::this.props.actions.clear}
                   className='close'
                   data-dismiss='alert'
                   aria-label='close'
                >
                    &times;
                </a>
                {this.props.error}
            </div>
        }

        return <div>{tpl}</div>
    }
}
