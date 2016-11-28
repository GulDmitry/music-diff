import React, {Component, PropTypes} from 'react'

export default class Home extends Component {
    static contextTypes = {
        router: PropTypes.object.isRequired,
    };

    static propTypes = {
        route: PropTypes.object,
    };

    componentDidMount() {
        this.context.router.setRouteLeaveHook(this.props.route, this.routerWillLeave)
    }

    static routerWillLeave() {
        let answer = window.confirm('Are you sure?');
        if (!answer) {
            return false
        }
    }

    handleSubmit(e) {
        e.preventDefault();
        const value = e.target.elements[0].value.toLowerCase();
        this.context.router.push(`/genre/${value}`)
    }

    // render() {
    //     return (
    //         <div className='row'>
    //             <div className='col-md-12'>Home component /</div>
    //             <form className='col-md-4' onSubmit={::this.handleSubmit}>
    //                 <input type='text' placeholder='Genre Name'/>
    //                 <button type='submit'>Go</button>
    //             </form>
    //         </div>
    //     )
    // }

    render() {
        return (
            <div className='row'>
                <div className='col-md-12'>Home component /</div>
                <form className='col-md-4' onSubmit={::this.handleSubmit}>
                    <input type='text' placeholder='Genre Name'/>
                    <button type='submit'>Go</button>
                </form>
            </div>
        )
    }
}
